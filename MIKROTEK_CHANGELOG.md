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
  - **Hasil**: Form bahasa pada wizard setup (`/index.php/setup/language`) kini berhasil memproses session dan mengarah ke tahap berikutnya (`/index.php/setup/prerequisites`).

---
