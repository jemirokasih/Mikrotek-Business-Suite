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

### 🏢 Fitur Baru: Hirarki Company & Multi-Company User (Branch `dev`)
- **Database Schema**:
  - Membuat tabel `ip_companies` (`company_id`, `company_name`, `company_address_1`, `company_city`, `company_phone`, `company_email`, `company_active`).
  - Menambahkan kolom `company_id` pada tabel `ip_users`.
  - Menambahkan file migrasi `application/modules/setup/sql/045_1.7.4.sql`.
- **Modul Master Perusahaan (`application/modules/companies/`)**:
  - `Mdl_companies.php`, `Companies.php` (CRUD Controller), `views/index.php` & `views/form.php` untuk mengelola master perusahaan.
- **Auto Default Company & Seeding**:
  - Mengotomatisasi pembuatan record perusahaan utama pada `ip_companies` saat setup awal (`Setup.php` -> `create_user()`).
  - Mengisi otomatis record perusahaan default (`PT Mikrotek Zemiro Indonesia`, ID: 1) untuk user admin utama.
  - Menetapkan default seleksi perusahaan pada form user (`users/form`) ke perusahaan user yang sedang login atau perusahaan pertama.

- **Fix Login Staff / Custom Role (`Sessions.php` & `Mdl_sessions.php`)**:
  - Mengupdate controller `Sessions.php` agar user dengan tipe `3` (`Staff / Custom Role`) di-redirect ke `dashboard` setelah login berhasil.
  - Mengupdate `Mdl_sessions.php` untuk memuat `user_role_id`, `company_id`, dan matriks `user_role_permissions` ke dalam sesi pengguna saat login.

### 🧾 Fitur Baru: Modul Receipt / Kwitansi (Terintegrasi Invoice & Payment) (Branch `dev`)
- **Database Schema**:
  - Membuat tabel `ip_receipts` (`receipt_id`, `company_id`, `user_id`, `client_id`, `invoice_id`, `payment_id`, `receipt_number`, `receipt_date`, `receipt_amount`, `receipt_notes`, `receipt_url_key`, `receipt_status`).
  - Menambahkan grup penomoran kwitansi (`Kwitansi` / `KWT-`) pada `ip_invoice_groups`.
  - Menambahkan file migrasi `application/modules/setup/sql/046_1.7.5.sql`.
- **Modul Receipt (`application/modules/receipts/`)**:
  - `Mdl_receipts.php`: Model pengelola kwitansi, penomoran otomatis, & helper fungsi **Terbilang Bahasa Indonesia** (contoh: `Satu Juta Lima Ratus Ribu Rupiah`).
  - `Receipts.php`: Controller CRUD Kwitansi, generasi PDF, serta fitur 1-klik terbitkan Kwitansi dari Invoice (`create_from_invoice`) & Payment (`create_from_payment`).
  - `views/index.php`, `views/form.php`, `views/view.php`: UI pengelola & pratinjau kwitansi.
- **Template PDF Kwitansi**:
  - `application/views/receipt_templates/pdf/Kwitansi.php`: Template PDF standar Kwitansi Indonesia (Kop Perusahaan, Nomor Kwitansi, Terbilang, Faktur Terkait, Jumlah Nominal, Tanggal, & Tanda Tangan).
- **Integrasi Invoice, Payment, Navigasi, & RBAC**:
  - `Invoices.php` & `invoices/views/view.php`: Menambahkan aksi **Terbitkan Kwitansi** pada dropdown opsi Invoice dan menampilkan riwayat Kwitansi terkait di halaman detail Invoice.
  - `partial_payments_table.php`: Menambahkan aksi **Terbitkan Kwitansi** pada opsi transaksi Payment.
  - `navbar.php`: Menambahkan dropdown menu **Kwitansi / Receipts**.
  - `Mdl_roles.php`: Menambahkan hak akses modul `receipts` pada matriks RBAC.

---





