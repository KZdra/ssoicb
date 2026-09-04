import './bootstrap';
import Swal from 'sweetalert2';

window.Swal = Swal;

// Global custom confirmation handler using SweetAlert2
document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (form && form.dataset && form.dataset.confirm) {
            e.preventDefault();
            const message = form.dataset.confirm;
            const title = form.dataset.title || 'Konfirmasi Tindakan';
            const confirmText = form.dataset.confirmText || 'Ya, Lanjutkan!';
            const iconType = form.dataset.icon || 'warning';
            const isDanger = form.dataset.danger !== undefined;

            Swal.fire({
                title: title,
                text: message,
                icon: iconType,
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal',
                confirmButtonColor: isDanger ? '#e11d48' : '#4f46e5',
                cancelButtonColor: '#64748b',
                reverseButtons: true,
                padding: '1.5rem',
                customClass: {
                    popup: 'rounded-3xl shadow-2xl border border-slate-100 font-sans',
                    title: 'text-base font-extrabold text-slate-900',
                    htmlContainer: 'text-xs text-slate-600',
                    confirmButton: 'rounded-xl text-xs font-bold px-4 py-2.5 shadow-sm cursor-pointer',
                    cancelButton: 'rounded-xl text-xs font-bold px-4 py-2.5 cursor-pointer'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    delete form.dataset.confirm;
                    form.submit();
                }
            });
        }
    });
});

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
