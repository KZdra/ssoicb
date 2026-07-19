#!/bin/bash
# =============================================================================
#  DEPLOY SCRIPT - ssoICB Laravel Application
#  Jalankan: bash deploy.sh
#  Atau dengan opsi: bash deploy.sh --rebuild  (untuk force rebuild image)
# =============================================================================

set -e

# ============================================================
# KONFIGURASI (ubah sesuai kebutuhan)
# ============================================================
APP_NAME="ssoICB"
COMPOSE_FILE="docker-compose.yml"
CONTAINER_MYSQL="mysql3306"
NETWORK_NAME="bridge"
APP_URL="http://localhost:8001"

# Warna output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m' # No Color

# ============================================================
# FUNCTIONS
# ============================================================

log_header() {
    echo ""
    echo -e "${BOLD}${BLUE}============================================================${NC}"
    echo -e "${BOLD}${BLUE}  $1${NC}"
    echo -e "${BOLD}${BLUE}============================================================${NC}"
}

log_step() {
    echo -e "${CYAN}[STEP]${NC} $1"
}

log_ok() {
    echo -e "${GREEN}  ✓ $1${NC}"
}

log_warn() {
    echo -e "${YELLOW}  ⚠ $1${NC}"
}

log_error() {
    echo -e "${RED}  ✗ ERROR: $1${NC}"
}

log_info() {
    echo -e "  → $1"
}

# ============================================================
# MAIN SCRIPT
# ============================================================

log_header "DEPLOY ${APP_NAME} - $(date +'%Y-%m-%d %H:%M:%S')"

# --- Parse arguments ---
FORCE_REBUILD=false
for arg in "$@"; do
    case $arg in
        --rebuild|-r)
            FORCE_REBUILD=true
            shift
            ;;
        --help|-h)
            echo "Usage: bash deploy.sh [OPTIONS]"
            echo ""
            echo "Options:"
            echo "  --rebuild, -r    Force rebuild Docker image dari awal"
            echo "  --help, -h       Tampilkan pesan bantuan ini"
            exit 0
            ;;
    esac
done

# --- Step 1: Pre-flight checks ---
log_step "Melakukan pengecekan awal..."

# Cek Docker running
if ! docker info > /dev/null 2>&1; then
    log_error "Docker tidak berjalan! Jalankan Docker Desktop terlebih dahulu."
    exit 1
fi
log_ok "Docker berjalan."

# Cek docker compose tersedia
if ! docker compose version > /dev/null 2>&1; then
    log_error "Docker Compose tidak ditemukan (v2). Install Docker Desktop versi terbaru."
    exit 1
fi
log_ok "Docker Compose v2 tersedia."

# Cek docker-compose.yml ada
if [ ! -f "$COMPOSE_FILE" ]; then
    log_error "File $COMPOSE_FILE tidak ditemukan! Pastikan Anda berada di root folder project."
    exit 1
fi
log_ok "File $COMPOSE_FILE ditemukan."

# --- Step 2: Cek container MySQL ---
log_step "Mengecek container MySQL (${CONTAINER_MYSQL})..."
if ! docker ps --format '{{.Names}}' | grep -q "^${CONTAINER_MYSQL}$"; then
    log_error "Container '${CONTAINER_MYSQL}' tidak berjalan!"
    log_info "Pastikan MySQL container sudah aktif sebelum deploy."
    log_info "Cek dengan: docker ps -a | grep mysql3306"
    exit 1
fi
log_ok "Container '${CONTAINER_MYSQL}' sedang berjalan."

# --- Step 3: Cek network bridge ---
log_step "Mengecek Docker network '${NETWORK_NAME}'..."
if ! docker network ls --format '{{.Name}}' | grep -q "^${NETWORK_NAME}$"; then
    log_warn "Network '${NETWORK_NAME}' tidak ditemukan. Mencoba membuat..."
    docker network create "${NETWORK_NAME}" || true
fi

# Cek apakah MySQL sudah terhubung ke network ini
MYSQL_NETWORKS=$(docker inspect "${CONTAINER_MYSQL}" --format='{{range $k, $v := .NetworkSettings.Networks}}{{$k}} {{end}}' 2>/dev/null || echo "")
if echo "$MYSQL_NETWORKS" | grep -q "${NETWORK_NAME}"; then
    log_ok "Container '${CONTAINER_MYSQL}' sudah terhubung ke network '${NETWORK_NAME}'."
else
    log_warn "Container '${CONTAINER_MYSQL}' belum terhubung ke network '${NETWORK_NAME}'. Menghubungkan..."
    docker network connect "${NETWORK_NAME}" "${CONTAINER_MYSQL}" 2>/dev/null || true
    log_ok "Container '${CONTAINER_MYSQL}' berhasil dihubungkan ke network '${NETWORK_NAME}'."
fi

# --- Step 4: Stop container yang sedang berjalan ---
log_step "Menghentikan container lama (jika ada)..."
docker compose -f "$COMPOSE_FILE" down --remove-orphans 2>/dev/null || true
log_ok "Container lama dihentikan."

# --- Step 5: Build Docker image ---
log_step "Membangun Docker image..."
if [ "$FORCE_REBUILD" = true ]; then
    log_warn "Mode --rebuild aktif. Membangun ulang image dari awal (no-cache)..."
    docker compose -f "$COMPOSE_FILE" build --no-cache --pull
else
    log_info "Membangun image (menggunakan cache jika ada)..."
    docker compose -f "$COMPOSE_FILE" build --pull
fi
log_ok "Docker image berhasil dibangun."

# --- Step 6: Jalankan container ---
log_step "Menjalankan container..."
docker compose -f "$COMPOSE_FILE" up -d
log_ok "Container berhasil dijalankan."

# --- Step 7: Tunggu app container sehat ---
log_step "Menunggu aplikasi siap (max 120 detik)..."
MAX_WAIT=60
WAITED=0
while [ $WAITED -lt $MAX_WAIT ]; do
    HEALTH=$(docker inspect --format='{{if .State.Health}}{{.State.Health.Status}}{{else}}running{{end}}' ssoicb_app 2>/dev/null || echo "starting")
    STATUS=$(docker inspect --format='{{.State.Status}}' ssoicb_app 2>/dev/null || echo "starting")

    if [ "$STATUS" = "running" ] && ([ "$HEALTH" = "healthy" ] || [ "$HEALTH" = "running" ]); then
        log_ok "Aplikasi sudah berjalan!"
        break
    elif [ "$STATUS" = "exited" ] || [ "$STATUS" = "dead" ]; then
        log_error "Container gagal berjalan! Melihat log..."
        docker compose -f "$COMPOSE_FILE" logs --tail=50 app
        exit 1
    fi

    echo -ne "  ... Menunggu (${WAITED}s / ${MAX_WAIT}s)\r"
    sleep 2
    WAITED=$((WAITED + 2))
done
echo ""

# --- Step 8: Tampilkan status & info ---
log_header "DEPLOYMENT SELESAI!"
echo ""
echo -e "${GREEN}${BOLD}  ✓ ${APP_NAME} berhasil di-deploy!${NC}"
echo ""
echo -e "  ${BOLD}URL Aplikasi:${NC}    ${APP_URL}"
echo -e "  ${BOLD}Container App:${NC}   ssoicb_app"
echo -e "  ${BOLD}Container Queue:${NC} ssoicb_queue"
echo ""
echo -e "${BOLD}  Perintah berguna:${NC}"
echo -e "  ${CYAN}docker compose logs -f app${NC}           → Lihat log aplikasi"
echo -e "  ${CYAN}docker compose logs -f queue_worker${NC}  → Lihat log queue"
echo -e "  ${CYAN}docker compose exec app bash${NC}         → Masuk ke container"
echo -e "  ${CYAN}docker compose down${NC}                  → Hentikan semua container"
echo -e "  ${CYAN}bash deploy.sh --rebuild${NC}             → Deploy ulang dari awal"
echo ""

# --- Tampilkan log awal ---
echo -e "${BLUE}--- Log Awal Aplikasi (15 detik terakhir) ---${NC}"
docker compose -f "$COMPOSE_FILE" logs --tail=20 app 2>/dev/null || true
echo ""
