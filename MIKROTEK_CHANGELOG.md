# Mikrotek InvoicePlane Changelog

Dokumen ini mencatat seluruh perubahan, perbaikan, dan fitur baru yang dikembangkan pada repository Mikrotek-InvoicePlane.

---

## [2026-08-21] - Initial Setup & Fixes (Branch `dev`)

### 🚀 Setup & Konfigurasi
- **Repository Clone**: Inisialisasi clone repository `Mikrotek-InvoicePlane` ke lingkungan lokal `/Users/jemirokasih/invoiceplane`.
- **Symlink MAMP**: Membuat symlink `/Applications/MAMP/htdocs/invoiceplane` dan `/Applications/MAMP/htdocs/mikrotek-invoiceplane` mengarah ke folder kerja.
- **Dependencies**: 
  - Backend: Menginstall composer dependencies menggunakan PHP 8.2 dari MAMP (`/Applications/MAMP/bin/php/php8.2.0/bin/php`).
  - Frontend: Menginstall yarn dependencies dan melakukan `yarn build` untuk mengompilasi seluruh asset CSS, JS, dan font.
- **Git Branching**: Membuat branch `dev` dari `develop` untuk seluruh pengerjaan revisi.

### 🛠️ Perbaikan & Patch (Bug Fixes)
- **Session & Setup Fix (`index.php` & `ipconfig.php`)**:
  - Memperbaiki penanganan `SESS_SAVE_PATH` kosong pada `ipconfig.php` yang menyebabkan kegagalan inisialisasi session (`Session: Configured save path '' is not a directory`).
  - Mengupdate helper `env()` pada `index.php` agar mengembalikan nilai *default* apabila variabel lingkungan bernilai string kosong `""`.
  - Memperbaiki `IP_URL` di `ipconfig.php` menjadi `http://localhost/mikrotek-invoiceplane` (tanpa *trailing slash*).
### ✨ Fitur Baru: Role & Multi-User Access Control (RBAC) (Branch `dev`)
- **Database Schema**:
  - Membuat tabel `ip_roles` (`role_id`, `role_name`, `role_description`, `role_permissions`).
  - Menambahkan kolom `user_role_id` pada tabel `ip_users`.
  - Menambahkan file migrasi `application/modules/setup/sql/044_1.7.3.sql`.
- **RBAC Helper & Core Controllers**:
  - Membuat `application/helpers/permissions_helper.php` dengan fungsi `has_permission($module, $action)` & `check_permission()`.
  - Mengupdate `User_Controller.php` & `Admin_Controller.php` untuk mendukung tipe user `3` (`Staff / Custom Role`).
- **Modul User Roles (`application/modules/roles/`)**:
  - `Mdl_roles.php`: Model pengelola tabel `ip_roles` dan matriks izin modul (Invoices, Quotes, Clients, Payments, Products, Projects, Reports, Settings, Users, Roles).
  - `Roles.php`: Controller CRUD Role.
  - `views/index.php` & `views/form.php`: UI daftar role dan form matriks centang hak akses per modul.
- **Integrasi Modul Users & Navigasi Layout**:
  - Mengupdate `Mdl_users.php` & `Users.php` controller untuk menyimpan pilihan role pengguna.
  - Mengupdate `users/views/form.php` dengan grup seleksi Role otomatis jika tipe pengguna memilih Staff/Custom Role.
  - Mengupdate `application/modules/layout/views/includes/navbar.php` agar hanya menampilkan menu navigasi sesuai izin role user yang sedang login.

---

