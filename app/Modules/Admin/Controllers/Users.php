<?php

namespace Modules\Admin\Controllers;

use App\Controllers\BaseController;
use Modules\Shared\Models\UserModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Users extends BaseController
{
    /**
     * READ: Menampilkan daftar pengguna.
     */
    public function index()
    {
        $userModel = new UserModel();

        $keyword = trim((string) $this->request->getGet('keyword'));
        $role = (string) $this->request->getGet('role');
        $status = (string) $this->request->getGet('status');

        $allowedRoles = [
            'klien',
            'konsultan',
            'administrator',
        ];

        $allowedStatuses = [
            'aktif',
            'nonaktif',
            'ditangguhkan',
        ];

        if (!in_array($role, $allowedRoles, true)) {
            $role = '';
        }

        if (!in_array($status, $allowedStatuses, true)) {
            $status = '';
        }

        // Pencarian berdasarkan nama atau email.
        if ($keyword !== '') {
            $userModel
                ->groupStart()
                ->like('nama_lengkap', $keyword)
                ->orLike('email', $keyword)
                ->groupEnd();
        }

        // Filter berdasarkan role.
        if ($role !== '') {
            $userModel->where('role', $role);
        }

        // Filter berdasarkan status akun.
        if ($status !== '') {
            $userModel->where('status_akun', $status);
        }

        return view('Modules\Admin\Views\users\index', [
            'title' => 'Kelola Pengguna - Counseling Platform',
            'users' => $userModel
                ->orderBy('created_at', 'DESC')
                ->paginate(10, 'users'),
            'pager' => $userModel->pager,
            'keyword' => $keyword,
            'selectedRole' => $role,
            'selectedStatus' => $status,
        ]);
    }

    /**
     * CREATE: Menampilkan formulir tambah pengguna.
     */
    public function create()
    {
        return view('Modules\Admin\Views\users\create', [
            'title' => 'Tambah Pengguna - Counseling Platform',
        ]);
    }

    /**
     * CREATE: Menyimpan pengguna baru.
     */
    public function store()
    {
        $name = trim(
            (string) $this->request->getPost('nama_lengkap')
        );

        $email = strtolower(
            trim((string) $this->request->getPost('email'))
        );

        $password = (string) $this->request->getPost('password');

        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[150]|is_unique[users.email]',
            'password' => 'required|min_length[8]|max_length[72]',
            'password_confirm' => 'required|matches[password]',
            'role' => 'required|in_list[klien,konsultan,administrator]',
            'status_akun' => 'required|in_list[aktif,nonaktif,ditangguhkan]',
            'no_telepon' => 'permit_empty|max_length[20]',
            'alamat' => 'permit_empty',
        ];

        // Normalisasi data sebelum validasi.
        $input = $this->request->getPost();

        $input['nama_lengkap'] = $name;
        $input['email'] = $email;

        if (!$this->validateData($input, $rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();

        $data = [
            'nama_lengkap' => $name,
            'email' => $email,
            'password_hash' => password_hash(
                $password,
                PASSWORD_DEFAULT
            ),
            'no_telepon' => trim(
                (string) $this->request->getPost('no_telepon')
            ) ?: null,
            'alamat' => trim(
                (string) $this->request->getPost('alamat')
            ) ?: null,
            'role' => $input['role'],
            'status_akun' => $input['status_akun'],
        ];

        try {
            if ($userModel->insert($data) === false) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('errors', $userModel->errors());
            }

        } catch (DatabaseException $e) {
            log_message(
                'error',
                'Gagal membuat pengguna: {message}',
                ['message' => $e->getMessage()]
            );

            return redirect()
                ->back()
                ->withInput()
                ->with('errors', [
                    'general' => 'Pengguna gagal disimpan. Periksa kembali data dan coba lagi.',
                ]);
        }

        return redirect()
            ->to(site_url('admin/users'))
            ->with(
                'success',
                'Pengguna baru berhasil ditambahkan.'
            );
    }

    /**
     * READ DETAIL: Menampilkan detail pengguna.
     */
    public function show($id)
    {
        // Validasi ID pengguna.
        if (!ctype_digit((string) $id) || (int) $id < 1) {
            return redirect()
                ->to(site_url('admin/users'))
                ->with('error', 'ID pengguna tidak valid.');
        }

        $userModel = new UserModel();

        $user = $userModel->find((int) $id);

        // Jika pengguna tidak ditemukan.
        if (!$user) {
            return redirect()
                ->to(site_url('admin/users'))
                ->with('error', 'Data pengguna tidak ditemukan.');
        }

        return view('Modules\Admin\Views\users\show', [
            'title' => 'Detail Pengguna - Counseling Platform',
            'user' => $user,
        ]);
    }

    /**
     * UPDATE: Menampilkan formulir edit pengguna.
     */
    public function edit($id)
    {
        // Validasi ID pengguna.
        if (!ctype_digit((string) $id) || (int) $id < 1) {
            return redirect()
                ->to(site_url('admin/users'))
                ->with('error', 'ID pengguna tidak valid.');
        }

        $userModel = new UserModel();

        $user = $userModel->find((int) $id);

        // Jika pengguna tidak ditemukan.
        if (!$user) {
            return redirect()
                ->to(site_url('admin/users'))
                ->with('error', 'Data pengguna tidak ditemukan.');
        }

        return view('Modules\Admin\Views\users\edit', [
            'title' => 'Edit Pengguna - Counseling Platform',
            'user' => $user,
        ]);
    }

    /**
     * UPDATE: Memproses perubahan data pengguna.
     *
     * Setelah berhasil:
     * - Menyimpan data terbaru.
     * - Mencatat field yang berubah.
     * - Menampilkan nilai terbaru tanpa tanda panah.
     * - Kembali ke halaman Manajemen Pengguna.
     * - Mengirim pesan untuk popup Bootstrap.
     */
    public function update($id)
    {
        // Validasi ID pengguna.
        if (!ctype_digit((string) $id) || (int) $id < 1) {
            return redirect()
                ->to(site_url('admin/users'))
                ->with('error', 'ID pengguna tidak valid.');
        }

        $userModel = new UserModel();

        // Ambil data sebelum diedit.
        $user = $userModel->find((int) $id);

        if (!$user) {
            return redirect()
                ->to(site_url('admin/users'))
                ->with('error', 'Data pengguna tidak ditemukan.');
        }

        // Ambil dan rapikan data dari form.
        $namaLengkap = trim(
            (string) $this->request->getPost('nama_lengkap')
        );

        $email = strtolower(
            trim((string) $this->request->getPost('email'))
        );

        $noTelepon = trim(
            (string) $this->request->getPost('no_telepon')
        );

        $alamat = trim(
            (string) $this->request->getPost('alamat')
        );

        $role = (string) $this->request->getPost('role');

        $statusAkun = (string) $this->request->getPost('status_akun');

        $password = (string) $this->request->getPost('password');

        $confirmation = (string) $this->request->getPost(
            'password_confirm'
        );

        // Aturan validasi.
        $rules = [
            'nama_lengkap' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama lengkap wajib diisi.',
                    'min_length' => 'Nama lengkap minimal 3 karakter.',
                    'max_length' => 'Nama lengkap maksimal 100 karakter.',
                ],
            ],

            'email' => [
                'rules' => "required|valid_email|max_length[150]|is_unique[users.email,id_user,{$id}]",
                'errors' => [
                    'required' => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.',
                    'is_unique' => 'Email sudah digunakan pengguna lain.',
                ],
            ],

            'no_telepon' => [
                'rules' => 'permit_empty|max_length[20]',
            ],

            'alamat' => [
                'rules' => 'permit_empty',
            ],

            'role' => [
                'rules' => 'required|in_list[klien,konsultan,administrator]',
            ],

            'status_akun' => [
                'rules' => 'required|in_list[aktif,nonaktif,ditangguhkan]',
            ],
        ];

        // Password hanya divalidasi jika diisi.
        if ($password !== '' || $confirmation !== '') {
            $rules['password'] = [
                'rules' => 'required|min_length[8]|max_length[72]',
                'errors' => [
                    'required' => 'Password baru wajib diisi.',
                    'min_length' => 'Password minimal 8 karakter.',
                    'max_length' => 'Password maksimal 72 karakter.',
                ],
            ];

            $rules['password_confirm'] = [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password wajib diisi.',
                    'matches' => 'Konfirmasi password tidak cocok.',
                ],
            ];
        }

        // Normalisasi data sebelum validasi.
        $input = $this->request->getPost();

        $input['nama_lengkap'] = $namaLengkap;
        $input['email'] = $email;

        if (!$this->validateData($input, $rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Data yang akan disimpan.
        $data = [
            'nama_lengkap' => $namaLengkap,
            'email' => $email,
            'no_telepon' => $noTelepon !== '' ? $noTelepon : null,
            'alamat' => $alamat !== '' ? $alamat : null,
            'role' => $role,
            'status_akun' => $statusAkun,
        ];

        // Hash password jika ada password baru.
        if ($password !== '') {
            $data['password_hash'] = password_hash(
                $password,
                PASSWORD_DEFAULT
            );
        }

        // ========================================
        // SIAPKAN RINCIAN PERUBAHAN
        // ========================================

        $roleLabels = [
            'klien' => 'Klien',
            'konsultan' => 'Konsultan / Psikolog',
            'administrator' => 'Administrator',
        ];

        $statusLabels = [
            'aktif' => 'Aktif',
            'nonaktif' => 'Nonaktif',
            'ditangguhkan' => 'Ditangguhkan',
        ];

        $fields = [
            'nama_lengkap' => 'Nama Lengkap',
            'email' => 'Email',
            'no_telepon' => 'Nomor Telepon',
            'alamat' => 'Alamat',
            'role' => 'Role',
            'status_akun' => 'Status Akun',
        ];

        $changes = [];

        foreach ($fields as $field => $label) {
            $oldValue = (string) ($user[$field] ?? '');
            $newValue = (string) ($data[$field] ?? '');

            // Lewati field yang tidak berubah.
            if ($oldValue === $newValue) {
                continue;
            }

            // Ubah role menjadi label.
            if ($field === 'role') {
                $newValue = $roleLabels[$newValue] ?? $newValue;
            }

            // Ubah status menjadi label.
            if ($field === 'status_akun') {
                $newValue = $statusLabels[$newValue] ?? $newValue;
            }

            // Tampilkan nilai terbaru saja.
            $changes[] = $label . ': '
                . ($newValue !== '' ? $newValue : '-');
        }

        // Jangan tampilkan password asli.
        if ($password !== '') {
            $changes[] = 'Password: Berhasil diperbarui';
        }

        // ========================================
        // SIMPAN PERUBAHAN KE DATABASE
        // ========================================

        try {
            $updated = $userModel->update((int) $id, $data);

            if ($updated === false) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('errors', $userModel->errors());
            }

            // ========================================
            // PESAN POPUP SUKSES EDIT
            // ========================================

            $successMessage = 'Data pengguna '
                . $namaLengkap
                . ' berhasil diperbarui.';

            if (!empty($changes)) {
                $successMessage .= "\n\nRincian perubahan:\n"
                    . implode("\n", $changes);
            } else {
                $successMessage .= "\nTidak ada perubahan data.";
            }

            // Kembali ke halaman Manajemen Pengguna.
            return redirect()
                ->to(site_url('admin/users'))
                ->with('success', $successMessage);

        } catch (\Throwable $e) {
            log_message(
                'error',
                'Gagal memperbarui pengguna: {message}',
                ['message' => $e->getMessage()]
            );

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal memperbarui pengguna. Silakan coba lagi.'
                );
        }
    }

    /**
     * DELETE: Menghapus pengguna secara permanen.
     *
     * Menggunakan session sesuai Login.php:
     * - user_id
     * - user_role
     * - isLoggedIn
     *
     * Tidak menggunakan soft delete.
     * Tidak membutuhkan kolom deleted_at.
     */
    public function delete($id)
    {
        // ========================================
        // VALIDASI SESSION ADMIN
        // ========================================

        $loggedInUserId = session()->get('user_id');
        $isLoggedIn = session()->get('isLoggedIn');
        $userRole = session()->get('user_role');

        // Pastikan pengguna login sebagai Admin.
        if (
            $isLoggedIn !== true ||
            $userRole !== 'admin' ||
            !is_numeric($loggedInUserId)
        ) {
            return redirect()
                ->to(site_url('admin/users'))
                ->with(
                    'error',
                    'Sesi administrator tidak valid. Silakan login kembali.'
                );
        }

        // ========================================
        // VALIDASI ID PENGGUNA
        // ========================================

        if (!ctype_digit((string) $id) || (int) $id < 1) {
            return redirect()
                ->to(site_url('admin/users'))
                ->with('error', 'ID pengguna tidak valid.');
        }

        // Admin tidak boleh menghapus akun sendiri.
        if ((int) $loggedInUserId === (int) $id) {
            return redirect()
                ->to(site_url('admin/users'))
                ->with(
                    'error',
                    'Anda tidak dapat menghapus akun sendiri.'
                );
        }

        $userModel = new UserModel();

        // Cari pengguna yang akan dihapus.
        $user = $userModel->find((int) $id);

        if (!$user) {
            return redirect()
                ->to(site_url('admin/users'))
                ->with(
                    'error',
                    'Data pengguna tidak ditemukan.'
                );
        }

        // ========================================
        // HAPUS PERMANEN DARI DATABASE
        // ========================================

        try {
            $deleted = $userModel->delete((int) $id, true);

            if ($deleted === false) {
                return redirect()
                    ->to(site_url('admin/users'))
                    ->with(
                        'error',
                        'Pengguna gagal dihapus.'
                    );
            }

            // ========================================
            // PESAN POPUP SUKSES HAPUS
            // ========================================

            return redirect()
                ->to(site_url('admin/users'))
                ->with(
                    'success',
                    'Pengguna ' . $user['nama_lengkap']
                    . ' berhasil dihapus secara permanen.'
                );

        } catch (\Throwable $e) {
            log_message(
                'error',
                'Gagal menghapus pengguna: {message}',
                ['message' => $e->getMessage()]
            );

            return redirect()
                ->to(site_url('admin/users'))
                ->with(
                    'error',
                    'Pengguna tidak dapat dihapus. Akun mungkin masih terhubung dengan data konseling atau jadwal.'
                );
        }
    }
}
