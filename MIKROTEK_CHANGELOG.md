# Mikrotek InvoicePlane Changelog

Dokumen ini mencatat seluruh perubahan, perbaikan, dan fitur baru yang dikembangkan pada repository Mikrotek-InvoicePlane.

---

## 📌 Roadmap & Fitur Mendatang (Future Features)
1. **📂 Integrasi File Storage Synology NAS (`modules/files`)**:
   - Modul File Manager terpadu yang terhubung ke Synology NAS via protokol WebDAV / Local SMB Mount Path.
   - Fitur Auto-Archive PDF Invoice/Quote otomatis ke folder Synology NAS.
   - Manajemen dokumen lampiran klien, resi pembayaran, dan kontrak perusahaan.

---

## [v1.7.47] - 2026-08-22

### 🔧 Perbaikan Fitur Modal Reimburse & Akses Portal Karyawan
- **Perbaikan Event Handler Modal**: Menambahkan callback `.modal('show')` pada pemanggilan `.load()` modal di `reimbursements/index.php` sehingga tombol **Ajukan Reimburse**, **Detail Klaim**, **Tinjau & Setujui**, dan **Tandai Lunas** dapat terbuka secara sempurna saat diklik.
- **Integrasi Portal Karyawan & Navigasi**: Menambahkan tautan **Riwayat Klaim Reimburse** dan **Ajukan Reimburse Baru** pada menu navigasi atas (*Navbar*) di bagian Presensi, Cuti & Reimburse, menu *Sidebar*, *Portal Presensi Karyawan* (`attendance/clock`), serta tombol pintas pada widget karyawan di Dashboard Utama.
- **Dukungan URL Parameter & Hak Akses Self-Service**: Mendukung parameter `?action=create` untuk membuka modal pengajuan secara otomatis dan memberikan fallback izin `view` & `create` bagi seluruh pengguna karyawan/staff.

---

## [v1.7.46] - 2026-08-22

### 💳 Integrasi Form & Riwayat Reimburse di Halaman Detail Karyawan (`employees/view`)
- **Panel Riwayat Reimburse Karyawan**: Menambahkan tabel riwayat klaim reimburse dan tombol **`+ Ajukan Reimburse`** langsung di halaman detail karyawan (`employees/view/{id}`).
- **Pengajuan Berbasis Karyawan Spresifik**: Form modal pengajuan klaim secara otomatis mendeteksi ID Karyawan (`employee_id`) yang sedang dibuka dan menghubungkan data klaim secara otomatis.

---

## [v1.7.45] - 2026-08-22

### 💸 Modul Baru: Sistem Reimburse Karyawan (`reimbursements`)
- **Fitur Pengajuan Klaim Karyawan**: Karyawan dapat mengumpulkan pengajuan klaim biaya (Judul, Tanggal Pengeluaran, Kategori, Nominal, Deskripsi, dan Upload Nota Struk Pembayaran).
- **Peninjauan & Persetujuan Manajemen (Approval Workflow)**: Admin/Manager dapat memantau seluruh klaim karyawan via KPI Cards, menyetujui, menolak dengan alasan penolakan, atau menandai lunas (dengan tanggal & metode pembayaran).
- **Pembatasan Hak Akses RBAC & Sidebar**: Karyawan biasa hanya dapat melihat riwayat klaim sendiri, sedangkan Admin/Manager dapat mengelola seluruh pengajuan klaim perusahaan. Menambahkan permission `reimbursements` pada matriks RBAC dan navigasi sidebar *Human Resources*.

---

## [v1.7.44] - 2026-08-22

### 🐛 Perbaikan Tag Penutup HTML & Tampilan Konten Detail Invoice (`invoices/view.php`)
- **Perbaikan DOM Structure**: Menghapus tag penutup `</div>` berlebih di antara `#headerbar` dan `#content` yang sebelumnya menyebabkan kontainer konten utama tertutup secara tidak sengaja dan tidak muncul di layar.
- **Tampilan Normal Kembali**: Seluruh bagian rincian barang/jasa, tabel pembayaran, dan properti invoice kini tampil sempurna di bawah headerbar.

---

## [v1.7.43] - 2026-08-22

### 📐 Penyesuaian Layout Headerbar & Tombol Simpan/Options di Pojok Kanan (`invoices` & `quotes`)
- **Reposisi Tombol Aksi di Pojok Kanan**: Mengstruktur ulang tata letak komponen `#headerbar` pada halaman detail Invoice (`invoices/view.php`) dan Penawaran (`quotes/view.php`).
- **Pemisahan Headerbar Left & Right**: Judul dan Badge Status Invoice ditempatkan secara teratur di sisi kiri (`.headerbar-left`), sedangkan tombol-tombol aksi (*Convert Proforma*, *Kwitansi*, dropdown *Options*, dan tombol *Simpan*) ditempatkan secara rata kanan (`.headerbar-item.pull-right`) sejajar dengan layout modul lainnya.

---

## [v1.7.42] - 2026-08-22

### 🔍 Pemisahan Eksklusif Digital Signature (QR Code) & Digital Signature Image (`pdf`)
- **Penyematan QR Code Tanpa Gambar TTD**: Saat opsi **`Digital Signature (QR Code)`** dipilih, template PDF Invoice (`Mikrotek.php`) & Quote (`Mikrotek.php`) kini secara eksklusif hanya menampilkan QR Code verifikasi faktur digital tanpa menampilkan gambar scan TTD.
- **Pemisahan Mode yang Jelas**: Mode *Digital Signature Image* khusus menampilkan gambar TTD scan, sedangkan mode *Digital Signature (QR Code)* khusus menampilkan QR Code verifikasi.

---

## [v1.7.41] - 2026-08-22

### ✍️ Penambahan Opsi Digital Signature Image di Form Invoice & Quote (`invoices` & `quotes`)
- **Penambahan Pilihan "Digital Signature Image" di Form View & Modal**: Memperbarui form view per-invoice (`invoices/view.php`), modal buat invoice (`modal_create_invoice.php`), view quote (`quotes/view.php`), serta modal buat quote (`modal_create_quote.php`) agar menyertakan opsi **`Digital Signature Image`** (`value="image"`).
- **Auto-Select Default Setting**: Opsi secara otomatis memilih tipe tanda tangan default yang disetting di menu Pengaturan.

---

## [v1.7.40] - 2026-08-22

### ✍️ Pembaruan Opsi & Label Tipe Tanda Tangan (`settings`)
- **Pembaruan Opsi Tipe Tanda Tangan**: Memperbarui pilihan dan label Tipe Tanda Tangan di Pengaturan Invoice (`partial_settings_invoices.php`) sesuai permintaan:
  1. `Digital Signature (QR Code)` — Verifikasi QR Code Faktur Digital.
  2. `Digital Signature Image` — Menampilkan gambar hasil scan tanda tangan yang diunggah.
  3. `Manual Signature` — Ruang kosong untuk tanda tangan basah & tempel materai.

---

## [v1.7.39] - 2026-08-22

### ✍️ Opsi Tipe Tanda Tangan Digital & Upload Gambar TTD Scan (`settings` & `pdf`)
- **Opsi Tipe Tanda Tangan (`signature_type`)**: Menambahkan pengaturan pilihan tipe tanda tangan pada Pengaturan Invoice (`partial_settings_invoices.php`):
  1. *Teks / Garis Manual (Wet Signature Space)*
  2. *Digital Signature Image (Scan TTD)*
  3. *Verifikasi QR Code Digital (Digital QR Signature)*
- **Upload Gambar Tanda Tangan (`signature_image`)**: Menambahkan form upload file gambar tanda tangan scan (PNG/JPG/WEBP) lengkap dengan preview gambar dan tombol hapus gambar TTD di menu Pengaturan.
- **Dukungan Template PDF Invoice, Quote, & Kwitansi**: Memperbarui template PDF `Mikrotek.php` (Invoice & Quote) serta `Kwitansi.php` (Receipt) untuk langsung merender gambar scan TTD di atas nama penanggung jawab ketika opsi *Digital Signature Image* aktif.

---

## [v1.7.38] - 2026-08-22

### 📐 Presisi Alignment Padding `#page-content-wrapper` (`index.php`)
- **Penyelarasan Presisi Tata Letak**: Menghapus inline style padding/margin manual pada `#content` agar mengikuti standar padding global 28px dari `#page-content-wrapper` (`modern_sidebar.css`).
- **Konsistensi Layout**: Container Roundcube Webmail kini 100% presisi dan sejajar sempurna dengan posisi card pada modul *Invoices*, *Quotes*, *Clients*, *Payments*, dan *Products*.

---

## [v1.7.37] - 2026-08-22

### 📐 Presisi Alignment Padding `#page-content-wrapper` (`index.php`)
- **Penyelarasan Presisi Tata Letak**: Menghapus inline style padding/margin manual pada `#content` agar mengikuti standar padding global 28px dari `#page-content-wrapper` (`modern_sidebar.css`).
- **Konsistensi Layout**: Container Roundcube Webmail kini 100% presisi dan sejajar sempurna dengan posisi card pada modul *Invoices*, *Quotes*, *Clients*, *Payments*, dan *Products*.

---

## [v1.7.36] - 2026-08-22

### 🎨 Penyesuaian Padding Container Roundcube & Tombol Icon-Only Samping (`index.php`)
- **Penyesuaian Padding Card (`padding: 15px`)**: Menyesuaikan padding container webmail dengan standar tata letak komponen aplikasi Mikrotek Suite lainnya (`border-radius: 12px` & `box-shadow` card halus), sehingga tampilan halaman konsisten dan rapi.
- **Tombol Opsi Icon-Only Melayang (`fa-cog`)**: Mengubah tombol opsi samping di pinggir kanan layar menjadi format **Icon-Only** (`fa-cog`) tanpa teks, menjadikannya ultra-ringkas, elegan, dan tidak menghalangi antarmuka.

---

## [v1.7.35] - 2026-08-22

### 💄 Reposisi Tombol Melayang Opsi Webmail (`index.php`)
- **Penyesuaian Posisi Melayang Di Bawah Topbar (`top: 130px; right: 0px;`)**: Memindahkan posisi tombol opsi melayang ke pinggir kanan layar di bawah topbar header (`top: 130px`). Perubahan ini menjamin 100% tombol opsi tidak menutupi topbar header, nama profil user, atau menu navigasi atas.
- **Desain Tab Samping**: Memakai gaya tab melayang (`border-radius: 20px 0 0 20px`) yang menempel di pinggir kanan layar secara rapi.

---

## [v1.7.34] - 2026-08-22

### 🖥️ Full-screen Webmail Container & Floating Right Options Button (`index.php`)
- **Pembersihan Header Card**: Menghapus container headerbar putih di atas iframe sepenuhnya sehingga area tampilan Roundcube Webmail menjadi 100% full height & full screen (`calc(100vh - 20px)`).
- **Floating Button Kanan Layar (`top: 18px; right: 25px;`)**: Memasang tombol melayang transparan modern (glassmorphism style) di pojok kanan layar. Saat diklik, tombol ini akan menampilkan opsi *Refresh Webmail*, *Buka di Tab Baru*, dan *Pengaturan Webmail* (Admin) tanpa mengganggu tampilan Roundcube.

---

## [v1.7.33] - 2026-08-22

### 🖥️ Maksimasi Area Tampilan Roundcube & Menu Opsi Ringkas (`index.php`)
- **Penambahan Tinggi Container Roundcube (`calc(100vh - 125px)`)**: Memperbesar area tampilan container Roundcube Webmail agar dapat memanfaatkan seluruh ruang layar secara maksimal dan immersive.
- **Dropdown Menu Opsi Pojok Kanan Atas**: Merangkum tombol Refresh Webmail, Buka di Tab Baru, dan Pengaturan Webmail ke dalam 1 tombol dropdown mini (`fa-ellipsis-v`) di pojok kanan atas bar, sehingga tidak mengganggu antarmuka Roundcube.

---

## [v1.7.32] - 2026-08-22

### 🚀 Install Source Code Official Roundcube Webmail 1.6.10 Complete Package
- **Pemasangan Source Code Resmi (`assets/roundcube/`)**: Mendownload dan mengekstrak rilis resmi **Roundcube Webmail 1.6.10 Complete Package** (beserta skin Elastic dan vendor dependencies) secara penuh ke dalam folder repository `assets/roundcube/`.
- **Inisialisasi Database SQLite Local (`roundcube.db`)**: Menginisialisasi skema basis data SQLite bawaan Roundcube di `assets/roundcube/temp/roundcube.db`.
- **Konfigurasi Server Config (`config.inc.php`)**: Membuat file konfigurasi server bawaan `config.inc.php` untuk mengarahkan autentikasi IMAP & pengiriman SMTP secara otomatis.

---

## [v1.7.31] - 2026-08-22

### 🔀 Fitur Dual-Mode Webmail (URL External cPanel vs Built-in Internal App)
- **Pilihan Opsi Mode Integrasi (`settings.php` & `Webmail.php`)**: Menambahkan Opsi Pemilihan Mode Webmail di halaman Pengaturan Administrator:
  - **Opsi 1: URL External (Roundcube cPanel / Hosting Webmail)**: Sangat cocok untuk perusahaan yang sudah memiliki portal Webmail cPanel tersendiri (`https://webmail.domain.com`).
  - **Opsi 2: Built-in Internal Webmail App**: Sangat cocok untuk klien baru yang belum memiliki webmail eksternal, sehingga langsung menggunakan webmail bawaan aplikasi Mikrotek Suite.

---

## [v1.7.30] - 2026-08-22

### 🔐 Layar Login Wajib Roundcube Webmail Karyawan (`roundcube.php`)
- **Enforce Mandatory Login Screen**: Memasang antarmuka layar login resmi **Roundcube Webmail** saat pertama kali karyawan/user membuka menu Buka Email (`webmail/roundcube`). Karyawan wajib memasukkan **Alamat Email** & **Password Email** mereka terlebih dahulu untuk dapat mengakses kotak masuk.
- **Manajemen Sesi & Logout**: Menyediakan tombol **Logout** (`fa-sign-out`) di bar navigasi Roundcube agar karyawan dapat keluar dari sesi email mereka kapan saja.

---

## [v1.7.29] - 2026-08-22

### ✉️ Menu "Buka Email" Langsung Khusus Karyawan (`sidebar_menu.php`)
- **Navigasi Karyawan Simpel**: Karyawan/User biasa kini hanya melihat 1 menu sederhana **"Buka Email"** di bawah grup Communication tanpa submenu dropdown atau opsi pengaturan.
- **Direct Login Experience**: Mengklik **Buka Email** akan membawa karyawan langsung ke portal login Roundcube Webmail untuk masuk dan mulai menggunakan email seperti biasa.

---

## [v1.7.28] - 2026-08-22

### 🧹 Penyederhanaan Form Pengaturan Webmail (Pembersihan Field URL Manual)
- **Eliminasi Field URL Eksternal (`settings.php`)**: Menghapus input field `URL Portal Roundcube Webmail` karena aplikasi Roundcube sudah **100% built-in** secara otomatis di dalam Mikrotek Suite (`site_url('webmail/roundcube')`).
- **Fokus Pengaturan Admin**: Admin kini hanya perlu mengonfigurasi alamat Mail Server perusahaan (Server IMAP, Server SMTP, & Domain Default).

---

## [v1.7.27] - 2026-08-22

### ⚙️ Pemisahan Pengaturan Server Admin & Login Karyawan Webmail
- **Panel Konfigurasi Server Admin (`settings.php` & `Webmail.php`)**: Memindahkan seluruh konfigurasi infrastruktur webmail (URL Roundcube Portal, Server IMAP Host & Port, Server SMTP Host & Port, serta Domain Email Perusahaan Default) khusus ke dalam halaman Administrator.
- **Pengalaman Karyawan / Employee**: Karyawan tidak lagi dibebani oleh konfigurasi teknis server. Karyawan cukup memasukkan **Alamat Email** & **Password** mereka untuk langsung masuk dan mengoperasikan Roundcube Webmail.

---

## [v1.7.26] - 2026-08-22

### 🔐 Hak Akses Pengaturan Webmail Khusus Administrator
- **Restriksi Menu Konfigurasi (`Webmail.php` & `index.php`)**: Mengunci menu **Pengaturan Webmail** (`settings` & `save_settings`) khusus untuk pengguna bereperan **Administrator** (`has_permission('settings')`).
- **Pengalaman Pengguna Biasa**: Pengguna biasa yang membuka menu **Webmail / Email** akan langsung diarahkan ke layar login / portal Roundcube Webmail tanpa melihat atau dapat mengubah URL/kredensial konfigurasi sistem.

---

## [v1.7.25] - 2026-08-22

### 🐛 Perbaikan Permisi 403 Forbidden pada Modul Webmail
- **Routing Route Internal (`site_url('webmail/roundcube')`)**: Mengubah pemanggilan `webmail_url` agar melewati controller CodeIgniter (`Webmail::roundcube()`) bukannya mengakses path file `application/` secara langsung yang diblokir oleh aturan keamanan `.htaccess` (`Deny from all`). Menghilangkan error 403 Forbidden secara permanen.

---

## [v1.7.24] - 2026-08-22

### ✉️ Modul Roundcube Webmail Built-in (Unified Single Package Application)
- **Built-in Roundcube Engine (`application/modules/webmail/roundcube/index.php`)**: Mengintegrasikan aplikasi **Roundcube Webmail** bawaan yang menyatu 100% di dalam kode sumber proyek Mikrotek Suite.
- **Tampilan Roundcube Elastic Interface**: Menyediakan fitur bawaan Roundcube lengkap: Tulis Email (Compose), Kotak Masuk (Inbox), Pesan Terkirim, Draft, Sampah, Buku Alamat (Contacts/Address Book), Pencarian Email, dan Pengaturan.

---

## [v1.7.23] - 2026-08-22

### ✉️ Perbaikan Modul Webmail (Direct Roundcube Full Portal Integration)
- **Direct Roundcube Webmail Iframe**: Menghapus override otomatis ke UI sederhana dan memastikan modul `webmail` memuat antarmuka **Roundcube Webmail** lengkap secara utuh (Kotak Masuk, Draft, Sampah, Folder, Kontak/Address Book, Pencarian, Filter, dan Pengaturan Identitas).
- **Prompt Konfigurasi URL**: Menyajikan layar penyiapan yang mengarahkan pengguna untuk memasukkan URL Roundcube Webmail mereka jika belum dikonfigurasi.

---

## [v1.7.22] - 2026-08-22

### ✉️ Fitur Baru: Internal Integrated Webmail Suite (Unified App Package)
- **Bundled Webmail Application (`app.php`)**: Menyediakan aplikasi webmail internal langsung di dalam paket aplikasi Mikrotek Suite (`site_url('webmail/app')`). Pengguna dapat langsung berkirim pesan email, melihat kotak masuk, dan mengelola email tanpa ketergantungan server eksternal.
- **Dukungan Dual-Mode**: Mendukung mode internal bawaan (langsung jadi 1 dengan aplikasi) maupun mode eksternal jika dikonfigurasi ke URL Roundcube tersendiri.

---

## [v1.7.21] - 2026-08-22

### ✉️ Fitur Baru: Integrasi Roundcube Webmail (Embedded Iframe & Secure Credentials)
- **Module Baru (`application/modules/webmail/`)**: Menambahkan modul `webmail` lengkap dengan Controller `Webmail.php` dan Views `index.php` & `settings.php`.
- **Form Kredensial & Enkripsi AES-256 (`Cryptor`)**: Menyediakan form konfigurasi URL Roundcube Webmail, Email Default, dan Password yang tersimpan aman menggunakan enkripsi AES-256.
- **Embedded Iframe Container**: Menampilkan portal Roundcube Webmail langsung secara penuh di dalam layout Mikrotek Suite, dilengkapi tombol Refresh, Buka di Tab Baru, dan Pengaturan.
- **Menu Sidebar Navigasi (`sidebar_menu.php`)**: Menambahkan grup navigasi **Communication** & menu **Webmail / Email** (`fa-envelope`).

---

## [v1.7.32] - 2026-08-21

### ✨ Penambahan Input Pencarian / Filter Real-time di Topbar Header (`layout_sidebar.php`)
- **Integrasi Kotak Pencarian / Filter Live**:
  - Mengintegrasikan kembali fungsi pencarian live tabel (`#filter` & `filter/jquery_filter`) ke dalam `#main-topbar` di [`layout_sidebar.php`](file:///Users/jemirokasih/invoiceplane/application/modules/layout/views/layout_sidebar.php).
  - Menambahkan komponen form pencarian modern dengan ikon kaca pembesar (`fa-search`) di sebelah judul aplikasi pada topbar header. Fitur ini memungkinkan pengguna melakukan pencarian instant real-time pada seluruh halaman daftar (*Invoices*, *Quotes*, *Clients*, *Payments*, *Products*, *Tasks*, *Employees*, dll.).

---

## [v1.7.31] - 2026-08-21

### 🐛 Perbaikan Tampilan Popup / Modal Dialog (`modern_sidebar.css`)
- **Restorasi & Standarisasi Modal Pop-up**:
  - Mengonstruksi ulang selektor form transparan agar mengecualikan form modal dialog (`form:not(.modal-content)`). Perubahan ini mengembalikan tampilan card latar belakang putih, sudut rounded `14px`, border, dan bayangan (*shadow*) pada modal pop-up *Create Invoice*, *Create Quote*, serta dialog aplikasi lainnya.
  - Menambahkan styling modern untuk `.modal-header`, `.modal-body`, `.modal-footer`, dan komponen dropdown `.select2` di dalam modal.

---

## [v1.7.30] - 2026-08-21

### 🎨 Peningkatan Kontras UI & Keterbacaan Teks (`modern_sidebar.css`)
- **Visual Contrast Polish Across UI**:
  - Mengubah warna utama teks aplikasi (`body`) menjadi `#0f172a` (Slate 900) untuk tingkat keterbacaan (*readability*) yang sangat tinggi dan tajam.
  - Meningkatkan warna label formulir (`label`) menjadi `#0f172a` dengan ketebalan `font-weight: 700`.
  - Mempertegas batas input formulir (`border: 1px solid #94a3b8`) serta meningkatkan kontras teks hint/bantuan (`.help-block`, `.text-muted`) menjadi `#334155`.
  - Memperjelas header tabel (`#e2e8f0`, teks `#0f172a`, border `#94a3b8`) dan data sel tabel (`color: #0f172a`, border `#cbd5e1`).
  - Meningkatkan kontras teks menu sidebar (`#cbd5e1`), judul grup sidebar (`#94a3b8`), serta tombol-tombol tab navigasi (`#1e293b`).

---

## [v1.7.29] - 2026-08-21

### 🐛 Perbaikan Indentasi & Alignment Card Settings (`modern_sidebar.css` & `settings/views/index.php`)
- **Penyelarasan Posisi Card Halaman Pengaturan**: Reset padding dan margin bawaan Bootstrap 3 (`padding: 15px`) pada `.tab-content` dan `.tab-pane` serta menambahkan kelas `table-content` pada `#content` di halaman Settings. Perubahan ini menghilangkan kelebihan indentasi `15px` di sisi kiri sehingga batas card *Settings* (*Headerbar*) dan card *General/Panel* di bawahnya sejajar sempurna 1:1.

---

## [v1.7.28] - 2026-08-21

### 🎨 Standarisasi Layout & Padding Halaman Pengaturan (`settings/views/`)
- **Pembersihan Container Wrapper & Presisi Padding**:
  - Mengonversi pembungkus tab-content halaman Pengaturan ke dalam `<div id="content">` standar dan menghapus kelas legacy `.tabbable.tabs-below`.
  - Menghapus pembungkus ganda `.row > .col-xs-12` pada seluruh partial settings views (*General*, *Invoices*, *Quotes*, *Taxes*, *Email*, *Online Payment*, *Projects*, *Updates*) agar seluruh panel/card terhubung langsung dengan container utama.
  - Memastikan batas tepi (left/right border), padding, dan elevation card sejajar secara presisi dengan `#headerbar`.

---

## [v1.7.27] - 2026-08-21

### 🐛 Pembersihan Background Container Form Settings (`modern_sidebar.css`)
- **Transparent Form Wrapper**: Menghapus styling card background putih, border, dan shadow pada tag generic `form` di `modern_sidebar.css`. Pembungkus `<form>` kini transparan sehingga halaman Pengaturan tidak lagi terbungkus kotak putih besar raksasa, dan hanya panel-panel individu (*General*, *Invoices*, *Quotes*, dll.) yang tampil sebagai card terpisah yang rapi.

---

## [v1.7.26] - 2026-08-21

### 🎨 Standarisasi Tab Navigasi Pengaturan (`settings/views/index.php`)
- **Harmonisasi Headerbar & Tab Navigasi Settings**: Mengubah navigasi tab halaman Pengaturan (*General*, *Invoices*, *Quotes*, *Taxes*, *Email*, *Online Payment*, *Projects*, *Updates*) agar berada di dalam `#headerbar` (`.index-options`) dan drawer `#submenu` untuk pengalaman pengguna yang seragam dan responsif di seluruh aplikasi.

---

## [v1.7.25] - 2026-08-21

### 🎨 Standarisasi Tab Filter Halaman Karyawan (`employees/views/index.php`)
- **Harmonisasi Headerbar & Tab Status Filter**: Mengubah navigasi filter status (*Active*, *Linked User Account*, *Inactive*, *All*) pada daftar karyawan agar berada di dalam `#headerbar` (`.index-options`) dan drawer `#submenu` selaras dengan modul lainnya (Invoices, Quotes, Clients, Payments, Products, Tasks).

---

## [v1.7.24] - 2026-08-21

### 🎨 Layout Panel Settings Full-Width Edge-to-Edge (`settings/views/`)
- **Full-Width Card Layout**: Mengubah wrapper container seluruh tab panel pengaturan dari `col-md-8 col-md-offset-2` menjadi `col-xs-12` agar tampilan card/panel melebar penuh dari ujung kiri ke ujung kanan (edge-to-edge).
- **Optimasi Grid 2 Kolom**: Elemen form internal pada seluruh tab pengaturan kini memanfaatkan lebar layar secara maksimal dalam susunan grid 2 kolom / multi-kolom yang seimbang di sisi kiri dan kanan.

---

## [v1.7.23] - 2026-08-21

### 🚚 Pemindahan Menu Rekening Bank ke Settings Submenu (`sidebar_menu.php`)
- **Relokasi Menu Rekening Bank**: Memindahkan item menu **Rekening Bank** dari kelompok *Clients & Directory* ke dalam submenu **Settings (Pengaturan)** di kelompok *Administration*, sejajar dengan *Cara Pembayaran (Payment Methods)*.

---

## [v1.7.22] - 2026-08-21

### 🎨 Penyesuaian Urutan Menu Sidebar (`sidebar_menu.php`)
- **Quotes Before Invoices**: Mengubah urutan navigasi menu sidebar pada kelompok *Sales & Billing* agar menu **Quotes (Penawaran)** tampil lebih dulu sebelum menu **Invoices (Faktur)**.

---

## [v1.7.21] - 2026-08-21

### 🎨 Pembersihan Tombol Rollback Navigasi (`partial_settings_general.php`)
- **Penghapusan Tombol Rollback Redundan**: Menghapus tombol `Rollback` legacy layout di *Settings ➔ General* karena pilihan mode navigasi ("Modern Sidebar Layout" & "Legacy Top Navbar Mode") sudah tersedia secara langsung melalui dropdown selection menu `layout_mode`.

---

## [v1.7.20] - 2026-08-21

### 🐛 Perbaikan Format Teks Header & Grid Template Mikrotek PDF
- **Auto Line-Break Conversion (`mpdf_helper.php`)**: Mengubah baris baru (`\n`) pada string `pdf_invoice_header` & `pdf_invoice_footer` menjadi `<br>` secara otomatis jika teks tidak memiliki tag HTML block. Menghentikan penumpukan teks alamat/kontak menjadi 1 baris bersambung.
- **Table Grid Alignment (`Mikrotek.php` PDF Templates)**: Mengubah box Klien (`To:`) dan Detail Faktur/Penawaran pada template `Mikrotek.php` menjadi grid tabel 2 kolom yang rapi, seimbang, dan tidak lagi mengandalkan float CSS mPDF.

---

## [v1.7.19] - 2026-08-21

### 🐛 Perbaikan Header PDF Download (Fix Broken PDF Header Layout)
- **CSS Margin & Font Cleanup (`custom-pdf.css`)**: Menghapus `margin-top: -130px !important` yang menyebabkan elemen header tergeser dan menabrak logo/margin atas dokumen PDF. Menghapus override font monospace `ccourier` dan menggantinya dengan font clean standard `dejavusans`.
- **Stable 2-Column Table Header (`invoice_templates` & `quote_templates`)**: Mengubah struktur header HTML di template PDF (`InvoicePlane.php`) menjadi tabel 2 kolom yang kokoh dan presisi. Kolom kiri memuat Logo & Data Klien, kolom kanan memuat Data Perusahaan, mencegah bentrokan float CSS pada mPDF.

---

## [v1.7.18] - 2026-08-21

### 🎨 Restorasi Card Headerbar Putih & Layout Urutan Baru (Title -> Tabs -> +New)
- **White Card Container (`modern_sidebar.css`)**: Mengembalikan kotak card putih `#ffffff` untuk `#headerbar` dengan border `1px solid #e2e8f0`, sudut `12px`, padding internal `16px 24px`, dan shadow `var(--card-shadow)`.
- **Layout Order Reordering**: Mengatur urutan elemen headerbar secara presisi:
  - **Kiri**: Judul halaman (e.g. `Invoices`, `Quotes`, `Clients`) berdampingan langsung di sampingnya dengan Tab Status Filter (`All`, `Draft`, `Sent`, `Viewed`, `Paid`, `Overdue`, `Proforma Invoices`).
  - **Pojok Kanan**: Tombol aksi utama `+ New`.

---

## [v1.7.17] - 2026-08-21

### 🎨 Pemindahan Paginasi ke Bawah Tabel (Bottom Table Pagination)
- **Table Pagination Relocation**: Memindahkan navigasi paginasi halaman (`pager`) dari bagian atas `#headerbar` ke bagian bawah tabel (`.table-pagination-footer`).
- **Clean Headerbar & Enhanced Usability**: Headerbar kini tampil sangat bersih dan leluasa, serta navigasi halaman berada di posisi alami di mana pengguna selesai membaca daftar tabel (Invoices, Quotes, Clients, Payments, Products, Projects, Families, Tax Rates, Payment Methods, Units).

---

## [v1.7.16] - 2026-08-21

### 🎨 Standarisasi Skala Ukuran & Konsistensi UI Elements
- **Harmonized Size Scale (`modern_sidebar.css`)**: Mengatur skala ukuran seluruh komponen UI agar konsisten secara presisi across the system:
  - Headerbar Titles: `20px`
  - Card/Panel Headers: `14px / 15px bold`
  - Standard Buttons & Inputs: `36px - 38px` (Font `13px`)
  - Small Controls, Tab Items & Pager Buttons (`.model-pager`): `32px` container / `26px` button (Font `12px`)
  - Tables & Data: `13px` cell text / `12px uppercase` header
  - Badges & Labels (`.label`): `11px` (Padding `4px 8px`)

---

## [v1.7.15] - 2026-08-21

### 🎨 Desain Compact Segmented Control Tab Filter Status (All, Draft, Sent, Viewed, Paid, etc.)
- **Compact Segmented Tab Bar (`modern_sidebar.css`)**: Merapikan dan merapatkan tab filter status (`.index-options`) di headerbar (All, Draft, Sent, Viewed, Paid, Overdue, dll.) menggunakan kontainer pill `background: #f1f5f9` dengan padding internal yang presisi (`3px`), gap rapat (`3px`), dan padding tombol yang ringkas (`5px 12px`).
- **High-Contrast Active Indicator**: Tab aktif kini memiliki kontras tinggi dengan warna latar solid (`#2563eb` atau `#d97706`) dan elevasi lembut (`box-shadow`), memisahkan tombol aktif dengan sangat jelas tanpa memakan banyak ruang secara horizontal.

---

## [v1.7.14] - 2026-08-21

### 🎨 Pembaruan & Merapikan Layout Quick Actions Dashboard
- **Modern Grid Quick Actions (`dashboard/views/index.php` & `modern_sidebar.css`)**: Memperbarui tampilan tombol "Quick Actions" di Dashboard dari `btn-group-justified` yang kaku menjadi **Card Action Grid** yang modern dan responsif.
- **Visual Badge & Descriptive Labels**: Setiap aksi cepat (Tambah Pelanggan, Buat Penawaran, Buat Faktur, Catat Pembayaran) dilengkapi dengan badge ikon berwarna khusus (Soft Blue, Green, Purple, Orange) serta teks sub-label untuk tampilan yang jauh lebih bersih, rapi, dan mudah diakses.

---

## [v1.7.13] - 2026-08-21

### 🎨 Clean Frameless Headerbar (Menghapus Background Putih Headerbar)
- **Frameless Headerbar (`modern_sidebar.css`)**: Menghapus background putih `#ffffff`, border, dan box-shadow dari `#headerbar` dan `.subheaderbar` sehingga headerbar tampil transparan dan bersih menyatu langsung dengan background utama, menghindari penumpukan card di atas card/panel konten.

---

## [v1.7.12] - 2026-08-21

### 🎨 Restorasi Padding Presisi & Card Headerbar Elegant (Corner-to-Corner)
- **Precise 16px 24px Card Padding (`modern_sidebar.css`)**: Mengembalikan kotak card putih `#headerbar` dengan padding internal yang presisi (`16px` atas/bawah, `24px` kiri/kanan), sudut `12px`, dan shadow lembut agar selaras sempurna dengan padding card/panel di bawahnya.
- **Flush Left/Right Alignment**: Judul ("Invoice #6") menempel rapi di sudut kiri card, dan grup tombol aksi ("Terbitkan Kwitansi", "Options", "Save") menempel rapi di sudut kanan card.

---

## [v1.7.11] - 2026-08-21

### 🎨 Clean Frameless Headerbar (Menghapus Latar Card & Shadow pada Judul Halaman)
- **Frameless Transparent Headerbar (`modern_sidebar.css`)**: Mengatur latar `#headerbar` menjadi transparan (`background: transparent`), menghapus kotak putih, border samping, dan shadow card yang tebal. `#headerbar` kini tampil bersih sebagai baris judul halaman yang langsung menyatu dengan layout utama.

---

## [v1.7.10] - 2026-08-21

### 🎨 Perbaikan Layout Fluid Corner-to-Corner Headerbar (Pojok Kiri s.d. Pojok Kanan)
- **Flex Alignment Flush Left & Flush Right (`modern_sidebar.css`)**: Mengatur judul halaman (`h1.headerbar-title`) menempel rata di **pojok kiri ujung** (`margin-right: auto !important`) dan grup tombol aksi (`.headerbar-item`) menempel rata di **pojok kanan ujung** (`margin-left: auto !important`), menghilangkan celah/ruang kosong yang jepret di tengah.
- **Clear Float Overrides**: Menghapus `float: left` dan `float: right` bawaan Bootstrap 3 pada `#headerbar` agar flexbox span 100% dari sudut ke sudut secara presisi.

---

## [v1.7.9] - 2026-08-21

### 🎨 Pembaruan Desain Form Card-Style, Headerbar Fluid & Modern Button Elevation
- **Card-Style Form Containers (`modern_sidebar.css`)**: Mengubah seluruh form, panel, dan card menjadi latar putih (`#ffffff`), sudut membulat (`12px`), border halus (`#e2e8f0`), dan soft elevation shadow (`box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05)`).
- **High-Contrast Form Inputs & Labels**: Memperbarui styling `.form-control` (tinggi 42px, radius 8px, ring fokus biru) dan label form (gelap, bold, micro-spacing).
- **Modern Button Elevation & Save Actions**: Memperbarui tombol aksi (`.btn-primary`, `.btn-success`, `.btn-danger`, `#btn-submit`, `#btn-cancel`) dengan elevasi shadow, mikro-animasi hover translateY(-1px), dan gap ikon yang rapi.
- **Fluid Headerbar & Typography**: Menyelaraskan `#headerbar` dengan judul `h1` yang tegas (`22px`, font bold `#0f172a`) dan perataan tombol aksi yang responsif.

---

## [v1.7.8] - 2026-08-21

### 🛠️ Fix Direct Page Navigation & Chevron Toggle pada Parent Menu Sidebar
- **Direct Navigation Href (`sidebar_menu.php`)**: Mengarahkan `href` link judul menu induk ("Invoices", "Quotes", "Payments", "Clients", "Products", "Projects", "Employees", "Settings", dll) ke URL halaman utama terkait (`invoices/index`, `quotes/index`, `payments/index`, `clients/index`, `employees/index`, `settings`).
- **Independent Chevron Accordion Toggle**: Ikon panah (`.arrow-icon`) menangani fungsi expand/collapse accordion submenu secara independen tanpa mengganggu navigasi langsung saat mengklik teks judul menu.

---

## [v1.7.7] - 2026-08-21

### 🛠️ Fix Instant Click Response untuk Accordion Header Submenu
- **Inline `onclick="toggleSidebarSubmenu(this); return false;"` Handler (`sidebar_menu.php` & `layout_sidebar.php`)**: Memasang handler JavaScript murni langsung di tag `<a>` menu induk. Bebas dependensi waktu muat jQuery/script-defer sehingga klik pada judul menu ("Invoices", "Quotes", "Payments", "Clients", dll) **langsung memicu buka-tutup accordion 100% tanpa delay**.

---

## [v1.7.6] - 2026-08-21

### 🛠️ Penambahan Seluruh Sub-Menu Navigasi & Event Handler Foolproof Accordion
- **Lengkapi Seluruh Sub-Menu Navigasi (`sidebar_menu.php`)**: Menambahkan seluruh item menu bawaan InvoicePlane (Invoice Archive, Enter Payment, Payment Logs, Product Families & Units, Projects & Tasks, All Financial Reports, Companies, Custom Fields, Email Templates, Invoice Groups, Tax Rates, Import, dll).
- **Foolproof Click Selector Handler (`layout_sidebar.php`)**: Menggunakan selector `.sidebar-nav-item.has-submenu > a` agar klik pada elemen teks (`<span class="nav-text">`), ikon (`<i class="nav-icon">`), maupun panah (`<i class="arrow-icon">`) 100% membuka accordion dropdown dengan sempurna.

---

## [v1.7.5] - 2026-08-21

### 🛠️ Perbaikan Tag Script pada Partial Settings General (`partial_settings_general.php`)
- **Fix Syntax Render Text Script**: Mengembalikan tag `<script>` pembuka pada bagian paling atas file `partial_settings_general.php` untuk mencegah kode JavaScript tercetak sebagai teks mentah di tab General Settings.

---

## [v1.7.4] - 2026-08-21

### 🛠️ Fix Event Handler & Display Rules Submenu Dropdown Sidebar
- **Delegated Accordion Toggle Handler (`layout_sidebar.php`)**: Menggunakan `$(document).on('click', '.toggle-submenu', ...)` dan `$(this).closest('.sidebar-nav-item')` untuk memastikan klik pada ikon/teks submenu (invoices, quotes, attendance) 100% membuka & menutup dropdown accordion.
- **CSS Display Priority (`modern_sidebar.css`)**: Menambahkan `display: block !important` pada `body:not(.sidebar-collapsed) .sidebar-nav-item.open > .sidebar-submenu` agar tidak ter-override oleh rule CSS lain.

---

## [v1.7.3] - 2026-08-21

### 🎨 Perbaikan Zebra Striping Tabel, Responsive Layout & Minimizable Sidebar
- **Distinct Table Zebra Striping (`modern_sidebar.css`)**: Mengatur latar baris ganjil (`#ffffff`) dan genap (`#f8fafc`) dengan border yang jelas agar data tabel (invoices, quotes, clients, attendance) mudah dibaca.
- **Minimizable Sidebar & Floating Submenus**: Menyediakan animasi lipat (*collapse*) sidebar ke ukuran 70px dengan popover submenu melayang (*floating accordion*) saat kursor berada di atas ikon.
- **Enhanced Responsive Mobile Layout**: Menambahkan `.sidebar-backdrop` overlay untuk navigasi drawer seluler serta kontainer tabel yang dapat di-scroll secara horizontal pada layar kecil.

---

## [v1.7.2] - 2026-08-21

### 🎨 Fix Asset Inclusion & Component Polish pada Modern Sidebar Layout
- **Fix Asset & Script Loading (`layout_sidebar.php`)**: Memuat `layout/includes/head` dan `scripts.min.js` secara lengkap agar seluruh stylesheet tema, plugin jQuery, datepicker, select2, dan fungsi javascript bawaan InvoicePlane termuat dengan sempurna.
- **Enhanced CSS Component Overrides (`modern_sidebar.css`)**: Memperbarui styling `#headerbar`, `.panel`, `.table`, `.form-control`, `.btn`, dan `.label` untuk menyelaraskan tata letak konten di dalam Modern Sidebar Layout.

---

## [v1.7.1] - 2026-08-21

### 🎨 Pengaturan Layout Default (Modern Sidebar) & Tombol Rollback Legacy
- **Make Sidebar Layout Default (`layout.php`)**: Menjadikan *Modern Sidebar Layout* sebagai tampilan bawaan (*default*) aplikasi secara otomatis.
- **Clean Layout Setting (`partial_settings_general.php`)**: Menghapus tombol sakelar cepat (*quick switch button*) di topbar/navbar dan memusatkan pengaturan layout di *Settings ➔ General* dengan tombol **Rollback to Legacy Mode**.

---

## [v1.7.0] - 2026-08-21

### 🎨 Perombakan UI/UX & Dual Layout Switcher (Sidebar vs Top Navbar)
- **Modern Collapsible Sidebar Layout (`layout_sidebar.php` & `modern_sidebar.css`)**:
  - Menghadirkan layout navigasi vertikal kiri modern yang dapat dilipat (*collapsible*), dilengkapi accordion sub-menu ber-ikon, header pencarian, avatar profil, serta antarmuka yang bersih & responsif.
- **Pengelompokan Menu Logis (`sidebar_menu.php`)**:
  - Menu dikelompokkan secara terstruktur: *Sales & Billing*, *Clients & Accounts*, *Products & Projects*, *Human Resources*, *Reports*, dan *Administration*.
- **Instant Layout Switcher & Restore to Default (`Settings Ajax & UI`)**:
  - Menambahkan sakelar pemindah layout cepat (*Quick Switcher*) di header navigasi.
  - Opsi pengaturan **Navigation Layout Style** (`sidebar` vs `top`) serta tombol **Restore to Default** di *Settings ➔ General* untuk mengembalikan tampilan ke Classic Top Navbar kapan saja.

---

## [v1.6.3] - 2026-08-21

### 🛠️ Resilience Datetime String Parsing pada Helper Tanggal (`date_helper.php`)
- **Fix Fatal Exception `date_from_mysql()`**: Mengubah parsing string tanggal pada `date_helper.php` agar mengekstrak 10 karakter pertama (`substr($date, 0, 10)`) dan memvalidasi `DateTime::createFromFormat()` dari hasil `false` ketika menerima format datetime MySQL (`Y-m-d H:i:s`), mencegah kesalahan fatal PHP (*HTTP 500*).

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
























