# Mikrotek InvoicePlane Changelog

Dokumen ini mencatat seluruh perubahan, perbaikan, dan fitur baru yang dikembangkan pada repository Mikrotek-InvoicePlane.

---

## [v1.6.2] - 2026-08-21

### 🛠️ Perbaikan Callback Loading & Form Submit Modal Approval Cuti
- **Fix Modal Load & Display Callback (`index.php` & `my_leaves.php`)**:
  - Mengubah handler klik tombol `btn-approve-reject` dan `btn-apply-leave` agar memanggil `.modal('show')` secara eksplisit pada callback `.load()` jQuery.
- **Form Submit Handler (`modal_approve_reject.php` & `modal_form.php`)**:
  - Mengubah tipe button menjadi `type="submit"` dan menerapkan handler AJAX submit form yang bersih dengan indikator loading fa-spin.

---

## [v1.6.1] - 2026-08-21

### 🛠️ Perbaikan Schema Field `job_title` pada Modul Cuti & Dashboard
- **Fix Unknown Column `position` Error**: Mengubah query JOIN `ip_employees.position` menjadi `ip_employees.job_title` pada `Mdl_leaves.php` dan view dashboard karyawan untuk mengatasi kesalahan PHP Fatal Exception (*HTTP 500*).

---

## [v1.6.0] - 2026-08-21

### 🏖️ Modul Pengajuan Cuti (Leave Request Module)
- **Database Migration (`055_1.8.4.sql`)**:
  - Membuat tabel `ip_leave_requests` untuk menyimpan pengajuan cuti, jenis cuti (`annual`, `sick`, `emergency`, `maternity`, `unpaid`), tanggal mulai/selesai, total hari, alasan, status (`pending`, `approved`, `rejected`, `cancelled`), serta catatan admin.
- **Backend Controller & Model (`Leaves.php` & `Mdl_leaves.php`)**:
  - Implementasi CRUD pengajuan cuti, perhitungan otomatis total hari kerja, serta workflow persetujuan/penolakan oleh Admin/Manager.
- **Employee Portal & Admin Approval Views (`leaves/my_leaves.php` & `leaves/index.php`)**:
  - Portal pengajuan mandiri karyawan (`my_leaves`) dengan modal dialog form pengajuan cuti (`modal_form.php`) dan tombol pembatalan mandiri.
  - Dashboard manajemen persetujuan Admin (`index.php`) dengan modal review & decision (`modal_approve_reject.php`).
- **RBAC & Navigation Integration**:
  - Menambahkan permission `leaves` ke matriks hak akses `Mdl_roles.php` dan link navigasi pada `navbar.php`.

---

## [v1.5.0] - 2026-08-21

### 📱 Redesain Dashboard Karyawan & Filtering Widget Berbasis Permission
- **Pembersihan Dashboard Karyawan (`dashboard/index.php`)**:
  - Menyembunyikan widget finansial (Ringkasan Faktur, Ringkasan Penawaran, Faktur Jatuh Tempo, dan Quick Actions) dari karyawan yang tidak memiliki hak akses finansial (`invoices`, `quotes`, `clients`, `payments`).
- **Widget Khusus Karyawan**:
  - **Attendance Clock-In/Out Quick Widget**: Banner interaktif absen 1-klik di bagian atas dashboard.
  - **My Attendance Logs Table**: Menampilkan 7 riwayat absensi terbaru karyawan.
  - **Employee Profile Summary**: Kartu ringkasan data diri karyawan (NIP, Departemen, Jabatan, Email).
- **Controller Data Loading (`Dashboard.php`)**:
  - Memuat data riwayat absensi karyawan (`employee_attendances`) secara efisien.

---

## [v1.4.4] - 2026-08-21

### 🛠️ Fix Settings Helper Loading Order in `Base_Controller.php`
- **Fix Undefined Function `get_setting()` Error**: Mengubah urutan muat helper `settings` agar dimuat sebelum pemanggilan `$this->mdl_settings->setting('time_zone')` pada `Base_Controller.php` untuk mencegah PHP Fatal Error (*HTTP 500*).

---

## [v1.4.3] - 2026-08-21

### 🌐 Fitur Pengaturan Timezone Sistem (`time_zone`)
- **System Settings Dropdown (`partial_settings_general.php`)**:
  - Menambahkan opsi pengaturan Zona Waktu (**Time Zone**) pada System Settings (General).
  - Pilihan teratas mencakup zona waktu utama Indonesia & internasional:
    - `[GMT+7] Asia/Jakarta (WIB) - Indonesia Barat`
    - `[GMT+8] Asia/Makassar (WITA) - Indonesia Tengah`
    - `[GMT+9] Asia/Jayapura (WIT) - Indonesia Timur`
    - `[GMT+0] UTC (Coordinated Universal Time)`
    - Serta seluruh zona waktu standar PHP (`DateTimeZone::listIdentifiers()`).
- **Global Timezone Application (`Base_Controller.php` & `SetTimezoneClass.php`)**:
  - Menerapkan fungsi `date_default_timezone_set()` secara otomatis berdasarkan pilihan setting `time_zone` aplikasi dengan fallback ke `Asia/Jakarta`.

---

## [v1.4.2] - 2026-08-21

### 🛠️ Perbaikan HTTP 500 pada Dashboard Utama (`Dashboard.php`)
- **Fix Missing Models & Variables**: Memperbaiki kegagalan pemanggilan model `mdl_tasks` serta variabel `$invoice_overview_period` dan `$quote_overview_period` pada controller `Dashboard.php` yang memicu PHP Fatal Exception (*HTTP 500*).

---

## [v1.4.1] - 2026-08-21

### ⏱️ Akses Portal Absensi & Quick Widget Dashboard Karyawan
- **Akses Navigasi Karyawan (`navbar.php`)**:
  - Mengizinkan seluruh akun pengguna karyawan (`$this->session->userdata('user_id')`) untuk mengakses menu **Attendance Portal** (`attendance/clock`) di navbar tanpa terhalang oleh pengecekan izin admin.
- **Widget Quick Attendance di Dashboard Utama (`dashboard/index`)**:
  - Menambahkan widget kehadiran interaktif di bagian atas Dashboard utama untuk setiap pengguna login yang memiliki profil karyawan terhubung.
  - Menampilkan nama karyawan, jam digital real-time, status absensi hari ini, serta tombol **Clock In / Clock Out** langsung dari Dashboard.
- **Handler Controller (`Dashboard.php`)**:
  - Memuat profil karyawan dan data absensi hari ini untuk dilewatkan ke halaman dashboard utama.

---

## [v1.4.0] - 2026-08-21

### ⏱️ Modul Absensi (Attendance Module) & Employee Portal Clock-In/Out
- **Database Migration (`054_1.8.3.sql`)**:
  - Membuat tabel `ip_attendance` untuk menyimpan record kehadiran, tanggal, jam masuk, jam keluar, alamat IP, lokasi GPS, status kehadiran, catatan, dan flag absensi manual (`is_manual`).
- **Modul Attendance (`application/modules/attendance/`)**:
  - `Mdl_attendance.php`: Model pengelola query kehadiran, pencatatan otomatis status (Present/Late), penentuan waktu jam masuk/keluar, dan filter multi-perusahaan (`company_id`).
  - `Attendance.php`: Controller pengelola Dashboard Absensi Harian Admin, Portal Absensi Mandiri Karyawan, Modal Absensi Manual, dan Laporan Rekapitulasi Bulanan.
- **Portal Clock-In / Clock-Out Mandiri Karyawan (`attendance/clock`)**:
  - Fitur **Clock In & Clock Out 1-Klik** untuk karyawan dengan akun login terhubung (`ip_users`).
  - **Perekaman Real-time IP Address & GPS Geolocation**: Menggunakan HTML5 Geolocation API (`navigator.geolocation.getCurrentPosition()`) untuk menangkap latitude & longitude lokasi karyawan saat melakukan absensi.
  - Widget Jam Digital Real-Time & Tabel Riwayat Absensi Bulanan Karyawan.
- **Dashboard Absensi & Absensi Manual Admin (`attendance/index`)**:
  - Dashboard ringkasan absensi harian dengan Kartu KPI (Total Employee, Present Today, Late Today, Absent Today, On Leave/Sick).
  - Modal Absensi Manual (`modal_manual_attendance.php`) untuk Admin mengabsenkan atau memperbarui status & jam masuk/keluar karyawan secara manual.
- **Laporan Rekapitulasi Absensi Bulanan (`attendance/report`)**:
  - Halaman laporan rekap absensi per karyawan (Jumlah Hari Hadir, Terlambat, Izin/Sakit, Alpa, dan Total Jam Kerja Kumulatif).
- **Integrasi RBAC & Bahasa**:
  - Mendaftarkan modul `attendance` pada matriks hak akses `Mdl_roles.php` & menambahkan menu navigasi **Attendance** pada `navbar.php`.
  - Menambahkan kamus string bahasa Inggris untuk seluruh komponen absensi pada `custom_lang.php`.

---

## [v1.3.0] - 2026-08-21

### 👥 Modul Employee / Karyawan & On-Demand User Account
- **Modul Employees (`application/modules/employees/`)**:
  - `Mdl_employees.php`: Model pengelola data karyawan, penomoran otomatis `EMP-XXXX`, filter status aktif/non-aktif, & integrasi scope perusahaan.
  - `Employees.php`: Controller CRUD Karyawan, handler toggle status aktif, detail profil, dan integrasi RBAC permissions.
- **Redesain Formulir 1-Halaman (Single-Page Form UI)**:
  - Mengubah formulir karyawan (`form.php`) dari tampilan berbasis Tab menjadi **Single-Page 2-Column Stacked Panel Layout** (seperti form Klien) agar seluruh field wajib (termasuk Email) dapat diisi dan divalidasi langsung tanpa berpindah tab.
- **On-Demand User Account Creation & Auto-Linking**:
  - Modal pembuatan akun pengguna (`modal_create_user_account.php`) untuk membuat atau mentautkan akun pengguna `ip_users` secara opsional.
  - **Auto-Link Email Terdaftar**: Jika email karyawan yang didaftarkan sudah memiliki akun `ip_users`, sistem secara otomatis mengaitkan ID pengguna tersebut tanpa memicu error duplikat.

### 🛠️ Perbaikan & Patch (Bug Fixes)
- **Fix `MY_Model::paginate()` Call**: Menambahkan argumen URL wajib (`site_url('employees/status/' . $status)`) pada panggilan `paginate()` di `Employees.php`.
- **Fix CSRF Protection**: Menambahkan helper `_csrf_field()` pada form karyawan dan modal pembuat akun pengguna untuk mencegah error *"The action you have requested is not allowed."*.
- **Fix Multi-Field Save Sanitization**: Mendaftarkan seluruh 27 field input ke dalam `validation_rules()` di `Mdl_employees.php` agar `db_array()` menyimpan seluruh data input form ke database.
- **Fix Crypt Password Hashing**: Mengganti pemanggilan library `Cryptor::genSalt()` menjadi `$this->load->library('crypt')` dengan `$this->crypt->salt()` & `$this->crypt->generate_password()` untuk mengatasi error 500 (*Network error*).

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
- **Fix Penerbitan Kwitansi untuk Invoice Lunas (Paid / Read-Only)**:
  - Mengupdate `Receipts.php` (`create_from_invoice`) agar secara otomatis mengambil tanggal, nominal, metode pembayaran, dan ID transaksi dari pembayaran terakhir jika Invoice sudah berstatus Lunas (Paid).
  - Menambahkan tombol utama **Terbitkan Kwitansi** secara langsung di baris header atas `invoices/views/view.php` agar selalu dapat diakses baik dalam status Draft, Sent, Paid, maupun Read-Only.

### 📋 Fitur Baru: Flag Proforma Invoice pada Modul Invoice Utama (Branch `feature/proforma-flag`)
- **Database Schema**:
  - Menambahkan kolom `is_proforma` TINYINT(1) DEFAULT 0 pada tabel `ip_invoices`.
  - Menambahkan file migrasi `application/modules/setup/sql/048_1.7.7.sql`.
- **Integrasi Modal & Form Invoice (`modal_create_invoice.php` & `Mdl_invoices.php`)**:
  - Menambahkan opsi centang **Jadikan Proforma Invoice (Faktur Sementara)** saat membuat Invoice baru.
  - Menyimpan status flag `is_proforma` ke tabel `ip_invoices`.
- **Tampilan, Filter, & Konversi 1-Klik (`Invoices.php` & `view.php`)**:
  - Menambahkan tab filter **Proforma Invoices** pada halaman daftar Invoice (`invoices/status/proforma`).
  - Menampilkan badge **PROFORMA INVOICE** pada tabel daftar & halaman detail Invoice jika `is_proforma = 1`.
  - Menambahkan tombol **Konversi ke Invoice Resmi** (`invoices/convert_proforma/$id`) untuk mengubah status `is_proforma = 0` dan menerbitkan nomor Invoice resmi secara instan.
- **Fitur Multi Rekening Bank (`bank_accounts`)**:
  - Membuat tabel `ip_bank_accounts` (`bank_id`, `company_id`, `payment_method_id`, `bank_name`, `account_number`, `account_name`, `bank_notes`, `bank_active`) (SQL: `050_1.7.9.sql`).
  - Membuat modul `bank_accounts` (`Mdl_bank_accounts.php`, `Bank_accounts.php`, views `index.php` & `form.php`) untuk mengelola daftar banyak rekening bank perusahaan.
  - Mengintegrasikan navigasi menu **Rekening Bank** pada navbar & matriks hak akses RBAC (`Mdl_roles.php`).
- **Fitur Opsi Tanda Tangan (Digital vs Manual Signature)**:
  - Menambahkan kolom `signature_type`, `signature_name`, `signature_title`, & `signature_image` pada tabel `ip_invoices` & `ip_quotes` (SQL: `051_1.8.0.sql`).
  - Mengupdate modal pembuatan Invoice & Quote (`modal_create_invoice.php` & `modal_create_quote.php`) untuk memilih **Digital Signature** atau **Manual Signature (Ruang Kosong)** beserta **Nama & Jabatan Penanggung Jawab**.
  - Menambahkan konfigurasi `REMOVE_INDEXPHP=true` pada `ipconfig.php` & `.htaccess` rewrite rule untuk membebaskan `index.php` dari URL scan QR Code.
  - Menambahkan pengaturan **Header PDF Faktur** (`pdf_invoice_header`) & **Header PDF Penawaran** (`pdf_quote_header`) pada System Settings (`partial_settings_invoices.php` & `partial_settings_quotes.php`).
  - Memperbarui `sanitize_pdf_header_content()` & `sanitize_pdf_footer_content()` agar tidak menghapus atribut `style`, `class`, `src`, `width`, `height`, `align` serta tag `table`, `tr`, `td`, `img`, `hr` sehingga desain Kop / Header HTML muncul sempurna 100% pada cetakan PDF.

### 🌐 Pembaruan Lingkungan GitHub & Metadata Repositori (`Mikrotek Business Suite`)
- **Package Manifests**:
  - Mengupdate `package.json`: `"name": "mikrotek-business-suite"`, `"version": "1.2.0"`, `"description"`, dan `"repository"` URL.
  - Mengupdate `composer.json`: `"name": "mikrotek/business-suite"` dan `"description"`.
- **GitHub Actions Workflows**:
  - Mengupdate `.github/workflows/setup.yml`: `Setup Mikrotek Business Suite`.
  - Mengupdate `.github/workflows/release-tag.yml`: Nama rilis dan checkout step `Mikrotek Business Suite`.
- **Dokumentasi & GitHub Templates**:
  - Mengupdate `README.md` dengan judul utama `# Mikrotek Business Suite v1.2.0` serta gambaran fitur lengkap (RBAC, Multi-Company, Kwitansi, Proforma, Restriksi Bank).
  - Mengupdate `.github/copilot-instructions.md`, `.github/CONTRIBUTING.md`, dan `.github/PULL_REQUEST_TEMPLATE.md` sesuai penamaan resmi **Mikrotek Business Suite**.

### 👥 Fitur Baru: Modul Employee / Karyawan & On-Demand User Account Provisioning (`employees`)
- **Database Schema**:
  - Membuat tabel `ip_employees` (`employee_id`, `company_id`, `user_id`, `employee_number`, `first_name`, `last_name`, `gender`, `birth_date`, `birth_place`, `national_id`, `email`, `phone`, `mobile`, `address_1`, `address_2`, `city`, `state`, `zip_code`, `country`, `department`, `job_title`, `employment_status`, `join_date`, `active`, `bank_name`, `bank_account_number`, `bank_account_holder`, `tax_id`, `notes`, `date_created`, `date_modified`).
  - Menambahkan file migrasi `application/modules/setup/sql/053_1.8.2.sql`.
- **Modul Employees (`application/modules/employees/`)**:
  - `Mdl_employees.php`: Model pengelola karyawan, validasi data, & penomoran otomatis `EMP-XXXX`.
  - `Employees.php`: Controller CRUD karyawan, pengelompokan UI/UX, & aksi pengubahan status aktif.
  - `views/index.php`, `views/form.php`, `views/view.php`: UI modern berbasis tab (Personal Details, Contact Details, Employment Details, Bank & Payroll).
- **Trigger On-Demand User Account Creation**:
  - `views/modal_create_user_account.php` & handler AJAX `create_user_account()`: Tombol "Create User Account" memfasilitasi pembuatan akun login sistem (`ip_users`) secara otomatis dan mengaitkan `user_id` ke record karyawan hanya jika dibutuhkan.
- **Integrasi RBAC & Bahasa Inggris**:
  - Mendaftarkan permission `employees` pada `Mdl_roles.php` (View, Create, Edit, Delete).
  - Menambahkan navigasi menu **Employees** pada `navbar.php`.
  - Seluruh label UI, header tabel, dan pesan notifikasi menggunakan Bahasa Inggris (`custom_lang.php`).

---
























