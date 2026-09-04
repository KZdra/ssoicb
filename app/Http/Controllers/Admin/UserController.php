<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class UserController extends Controller
{
    protected $userService;

    public function __construct(\App\Services\UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $status = $request->input('status');

        $query = User::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (!empty($role)) {
            $query->where('role', $role);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'admins' => User::where('role', 'admin')->count(),
        ];

        return view('admin.users.index', compact('users', 'search', 'role', 'status', 'stats'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(\App\Http\Requests\Admin\UserStoreRequest $request)
    {
        $validated = $request->validated();
        $this->userService->createUser($validated);
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = $this->userService->findUserById($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(\App\Http\Requests\Admin\UserUpdateRequest $request, $id)
    {
        $validated = $request->validated();
        $this->userService->updateUser($id, $validated);
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        if (auth()->id() == $id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete yourself.');
        }
        
        $this->userService->deleteUser($id);

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Download Template Import Excel (.xlsx)
     */
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Users');

        // Headers
        $headers = ['fullname', 'username', 'email', 'phone', 'password', 'role', 'status'];
        
        // Header Styling
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'], // Indigo brand color
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '3730A3'],
                ],
            ],
        ];

        foreach ($headers as $colIndex => $header) {
            $colLetter = chr(65 + $colIndex); // A, B, C, ...
            $sheet->setCellValue("{$colLetter}1", $header);
        }

        $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Sample Rows
        $sampleData = [
            ['Ahmad Fauzi', '10293841', 'ahmad.fauzi@example.com', '081234567890', 'password123', 'user', 'active'],
            ['Siti Rahmawati', '10293842', 'siti.rahma@example.com', '081234567891', 'password123', 'user', 'active'],
            ['Budi Santoso', '10293843', 'budi.santoso@example.com', '081234567892', 'password123', 'user', 'active'],
        ];

        $rowNum = 2;
        foreach ($sampleData as $row) {
            foreach ($row as $colIndex => $value) {
                $colLetter = chr(65 + $colIndex);
                // Explicitly set as string to avoid phone/NIS leading zeros getting stripped
                $sheet->setCellValueExplicit("{$colLetter}{$rowNum}", $value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            }
            $sheet->getRowDimension($rowNum)->setRowHeight(20);
            $rowNum++;
        }

        // Auto-fit column widths
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'template_import_user_sso.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Bulk Import Users from Excel (.xlsx, .xls, .csv)
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240', // Max 10MB
        ]);

        $file = $request->file('file');
        
        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            if (count($rows) <= 1) {
                return redirect()->back()->with('error', 'File Excel kosong atau hanya berisi baris header.');
            }

            // Extract header (row 1)
            $headerRow = array_shift($rows);
            $headers = array_map(function ($h) {
                return strtolower(trim((string)$h));
            }, $headerRow);

            // Column index mapping
            $colMap = array_flip($headers);

            $requiredCols = ['fullname', 'username', 'email', 'password'];
            foreach ($requiredCols as $req) {
                if (!isset($colMap[$req])) {
                    return redirect()->back()->with('error', "Kolom wajib '{$req}' tidak ditemukan pada header file Excel.");
                }
            }

            $successCount = 0;
            $skippedCount = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNum = $index; // 1-indexed row number from spreadsheet

                $fullname = trim((string)($row[$colMap['fullname']] ?? ''));
                $username = trim((string)($row[$colMap['username']] ?? ''));
                $email    = trim((string)($row[$colMap['email']] ?? ''));
                $phone    = isset($colMap['phone']) ? trim((string)($row[$colMap['phone']] ?? '')) : null;
                $password = trim((string)($row[$colMap['password']] ?? ''));
                $role     = isset($colMap['role']) ? strtolower(trim((string)($row[$colMap['role']] ?? 'user'))) : 'user';
                $status   = isset($colMap['status']) ? strtolower(trim((string)($row[$colMap['status']] ?? 'active'))) : 'active';

                // Skip completely empty rows
                if (empty($fullname) && empty($username) && empty($email)) {
                    continue;
                }

                // Validation
                if (empty($fullname) || empty($username) || empty($email) || empty($password)) {
                    $skippedCount++;
                    $errors[] = "Baris {$rowNum}: Data wajib (fullname, username, email, password) tidak lengkap.";
                    continue;
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $skippedCount++;
                    $errors[] = "Baris {$rowNum}: Format email '{$email}' tidak valid.";
                    continue;
                }

                // Check duplicate username
                if (User::where('username', $username)->exists()) {
                    $skippedCount++;
                    $errors[] = "Baris {$rowNum}: Username '{$username}' sudah terdaftar.";
                    continue;
                }

                // Check duplicate email
                if (User::where('email', $email)->exists()) {
                    $skippedCount++;
                    $errors[] = "Baris {$rowNum}: Email '{$email}' sudah terdaftar.";
                    continue;
                }

                // Normalize role & status
                $role = in_array($role, ['admin', 'user']) ? $role : 'user';
                $status = in_array($status, ['active', 'inactive']) ? $status : 'active';

                User::create([
                    'fullname' => $fullname,
                    'username' => $username,
                    'email'    => $email,
                    'phone'    => $phone ?: null,
                    'password' => Hash::make($password),
                    'role'     => $role,
                    'status'   => $status,
                ]);

                $successCount++;
            }

            DB::commit();

            // Log action to AuditLog
            AuditLog::create([
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'browser' => $request->header('User-Agent'),
                'operating_system' => php_uname('s'),
                'action' => 'user.bulk_import',
                'description' => "Import massal user via Excel: {$successCount} berhasil, {$skippedCount} dilewati.",
            ]);

            $message = "Berhasil mengimpor <strong>{$successCount} user</strong>.";
            if ($skippedCount > 0) {
                $message .= " Dilewati <strong>{$skippedCount} baris</strong> karena duplikasi atau format tidak valid.";
            }

            return redirect()->route('admin.users.index')
                ->with('success', $message)
                ->with('import_errors', $errors);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }
    }
}
