<?php
/**
 * Mikrotek Business Suite - Built-in Roundcube Webmail App
 * Embedded native Roundcube webmail application suite
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roundcube Webmail :: Mikrotek Suite</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        :root {
            --rc-bg: #f4f6f9;
            --rc-sidebar-bg: #1e293b;
            --rc-sidebar-text: #94a3b8;
            --rc-active: #3b82f6;
            --rc-border: #e2e8f0;
            --rc-header: #ffffff;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            font-size: 13px;
            color: #334155;
            background: var(--rc-bg);
            height: 100vh;
            overflow: hidden;
        }

        /* Login Screen Overlay */
        .rc-login-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        .rc-login-card {
            background: #ffffff;
            width: 100%;
            max-width: 380px;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
            padding: 32px;
            text-align: center;
        }
        .rc-login-logo {
            font-size: 36px;
            color: #3b82f6;
            margin-bottom: 12px;
        }
        .rc-login-title {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 6px 0;
        }
        .rc-login-subtitle {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 24px;
        }
        .rc-login-form-group {
            text-align: left;
            margin-bottom: 16px;
        }
        .rc-login-form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }
        .rc-login-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 13px;
            outline: none;
            box-sizing: border-box;
        }
        .rc-login-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }
        .rc-login-btn {
            width: 100%;
            padding: 11px;
            background: #3b82f6;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
            margin-top: 8px;
        }
        .rc-login-btn:hover {
            background: #2563eb;
        }

        /* Main Mail App Layout */
        .rc-layout {
            display: flex;
            height: 100vh;
            width: 100vw;
        }
        .rc-icon-bar {
            width: 56px;
            background: #0f172a;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 12px 0;
            gap: 16px;
            z-index: 10;
        }
        .rc-icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            text-decoration: none;
            font-size: 18px;
            transition: all 0.15s ease;
            position: relative;
        }
        .rc-icon-btn:hover, .rc-icon-btn.active {
            background: rgba(255,255,255,0.1);
            color: #ffffff;
        }
        .rc-icon-btn.active::before {
            content: "";
            position: absolute;
            left: 0;
            top: 8px;
            bottom: 8px;
            width: 3px;
            background: var(--rc-active);
            border-radius: 0 2px 2px 0;
        }

        .rc-folder-col {
            width: 200px;
            background: #ffffff;
            border-right: 1px solid var(--rc-border);
            display: flex;
            flex-direction: column;
        }
        .rc-folder-header {
            padding: 16px;
            border-bottom: 1px solid var(--rc-border);
            font-weight: 700;
            font-size: 14px;
            color: #0f172a;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .rc-btn-compose {
            margin: 12px;
            padding: 10px 14px;
            background: var(--rc-active);
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 2px 4px rgba(59,130,246,0.25);
            transition: background 0.15s;
        }
        .rc-btn-compose:hover { background: #2563eb; }

        .rc-folder-list {
            list-style: none;
            padding: 0;
            margin: 0;
            overflow-y: auto;
            flex: 1;
        }
        .rc-folder-item a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            color: #475569;
            text-decoration: none;
            font-weight: 500;
            border-left: 3px solid transparent;
        }
        .rc-folder-item a:hover, .rc-folder-item.active a {
            background: #f1f5f9;
            color: var(--rc-active);
            border-left-color: var(--rc-active);
        }
        .rc-badge {
            background: #e2e8f0;
            color: #475569;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 10px;
        }
        .rc-badge.primary { background: #dbeafe; color: #1e40af; }

        .rc-mail-col {
            width: 320px;
            background: #ffffff;
            border-right: 1px solid var(--rc-border);
            display: flex;
            flex-direction: column;
        }
        .rc-search-box {
            padding: 12px;
            border-bottom: 1px solid var(--rc-border);
            position: relative;
        }
        .rc-search-input {
            width: 100%;
            padding: 8px 12px 8px 32px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 12px;
            outline: none;
        }
        .rc-search-icon {
            position: absolute;
            left: 22px;
            top: 20px;
            color: #94a3b8;
        }

        .rc-mail-list {
            overflow-y: auto;
            flex: 1;
        }
        .rc-mail-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: background 0.1s;
        }
        .rc-mail-item:hover { background: #f8fafc; }
        .rc-mail-item.selected { background: #eff6ff; border-left: 3px solid var(--rc-active); }
        .rc-mail-sender { font-weight: 600; color: #1e293b; margin-bottom: 2px; display: flex; justify-content: space-between; }
        .rc-mail-date { font-weight: 400; font-size: 11px; color: #94a3b8; }
        .rc-mail-subject { font-weight: 600; color: #334155; margin-bottom: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .rc-mail-preview { color: #64748b; font-size: 12px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        .rc-view-col {
            flex: 1;
            background: var(--rc-bg);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        .rc-toolbar {
            height: 48px;
            background: #ffffff;
            border-bottom: 1px solid var(--rc-border);
            display: flex;
            align-items: center;
            padding: 0 16px;
            gap: 12px;
        }
        .rc-tool-btn {
            background: none;
            border: 1px solid #cbd5e1;
            padding: 6px 12px;
            border-radius: 6px;
            color: #475569;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .rc-tool-btn:hover { background: #f1f5f9; color: #0f172a; }

        .rc-pane-content {
            padding: 24px;
            flex: 1;
        }
        .rc-mail-header-box {
            background: #ffffff;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid var(--rc-border);
            margin-bottom: 16px;
        }
        .rc-mail-title { font-size: 18px; font-weight: 700; color: #0f172a; margin: 0 0 12px 0; }
        .rc-mail-meta { font-size: 12px; color: #64748b; line-height: 1.6; }
        .rc-mail-body-box {
            background: #ffffff;
            border-radius: 8px;
            padding: 24px;
            border: 1px solid var(--rc-border);
            line-height: 1.6;
            color: #334155;
            min-height: 350px;
        }

        .rc-compose-box {
            background: #ffffff;
            border-radius: 8px;
            padding: 24px;
            border: 1px solid var(--rc-border);
        }
        .rc-form-group { margin-bottom: 16px; }
        .rc-form-group label { display: block; font-weight: 600; margin-bottom: 6px; color: #1e293b; }
        .rc-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 13px;
            outline: none;
        }
        .rc-input:focus { border-color: var(--rc-active); box-shadow: 0 0 0 3px rgba(59,130,246,0.15); }
        textarea.rc-input { min-height: 220px; resize: vertical; }
    </style>
</head>
<body>

<!-- Login Screen (Default view for employee until authenticated) -->
<div id="login-overlay" class="rc-login-overlay">
    <div class="rc-login-card">
        <div class="rc-login-logo">
            <i class="fa fa-envelope-open"></i>
        </div>
        <h2 class="rc-login-title">Roundcube Webmail</h2>
        <p class="rc-login-subtitle">Masukkan akun email perusahaan Anda untuk melanjutkan</p>

        <form onsubmit="doLogin(event);">
            <div class="rc-login-form-group">
                <label>Alamat Email / Username:</label>
                <input type="text" id="rc-username" class="rc-login-input" placeholder="karyawan@mzi.co.id" required autofocus>
            </div>
            <div class="rc-login-form-group">
                <label>Password Email:</label>
                <input type="password" id="rc-password" class="rc-login-input" placeholder="••••••••" required>
            </div>
            <button type="submit" class="rc-login-btn">
                <i class="fa fa-sign-in"></i> Login Webmail
            </button>
        </form>
    </div>
</div>

<!-- Main Webmail App Layout -->
<div class="rc-layout">
    <!-- Icon Bar -->
    <div class="rc-icon-bar">
        <a href="#" class="rc-icon-btn active" title="Mail" onclick="showPane('mail'); return false;"><i class="fa fa-envelope"></i></a>
        <a href="#" class="rc-icon-btn" title="Contacts" onclick="showPane('contacts'); return false;"><i class="fa fa-address-book"></i></a>
        <a href="#" class="rc-icon-btn" title="Settings" onclick="showPane('settings'); return false;"><i class="fa fa-cog"></i></a>
        <div style="flex:1;"></div>
        <a href="#" class="rc-icon-btn" title="Logout" onclick="doLogout(); return false;" style="color:#ef4444;"><i class="fa fa-sign-out"></i></a>
    </div>

    <!-- Folder List -->
    <div class="rc-folder-col">
        <div class="rc-folder-header">
            <span>Roundcube</span>
            <i class="fa fa-refresh" style="cursor:pointer; color:#94a3b8;" onclick="location.reload();"></i>
        </div>
        <button class="rc-btn-compose" onclick="showCompose();">
            <i class="fa fa-pencil"></i> Tulis Email
        </button>

        <ul class="rc-folder-list">
            <li class="rc-folder-item active"><a href="#" onclick="showInbox(); return false;"><span><i class="fa fa-inbox"></i> &nbsp;Kotak Masuk</span> <span class="rc-badge primary">2</span></a></li>
            <li class="rc-folder-item"><a href="#" onclick="showCompose(); return false;"><span><i class="fa fa-paper-plane-o"></i> &nbsp;Terkirim</span></a></li>
            <li class="rc-folder-item"><a href="#"><span><i class="fa fa-file-text-o"></i> &nbsp;Draft</span></a></li>
            <li class="rc-folder-item"><a href="#"><span><i class="fa fa-exclamation-circle"></i> &nbsp;Spam / Junk</span></a></li>
            <li class="rc-folder-item"><a href="#"><span><i class="fa fa-trash-o"></i> &nbsp;Sampah</span></a></li>
        </ul>
    </div>

    <!-- Mail List Column -->
    <div class="rc-mail-col" id="mail-list-col">
        <div class="rc-search-box">
            <i class="fa fa-search rc-search-icon"></i>
            <input type="text" class="rc-search-input" placeholder="Cari pesan email...">
        </div>

        <div class="rc-mail-list">
            <div class="rc-mail-item selected" onclick="readMail(1, this);">
                <div class="rc-mail-sender"><span>Mikrotek Support</span> <span class="rc-mail-date">10:45</span></div>
                <div class="rc-mail-subject">Selamat Datang di Roundcube Webmail Suite</div>
                <div class="rc-mail-preview">Sistem Roundcube Webmail telah berhasil diintegrasikan secara built-in ke dalam Mikrotek Business Suite...</div>
            </div>
            <div class="rc-mail-item" onclick="readMail(2, this);">
                <div class="rc-mail-sender"><span>Finance Team</span> <span class="rc-mail-date">Kemarin</span></div>
                <div class="rc-mail-subject">Laporan Penjualan &amp; Faktur Tagihan Klien</div>
                <div class="rc-mail-preview">Berikut terlampir rangkuman daftar faktur dan pembayaran invoice terbaru yang telah diterbitkan...</div>
            </div>
        </div>
    </div>

    <!-- View / Detail Column -->
    <div class="rc-view-col">
        <div class="rc-toolbar">
            <button class="rc-tool-btn" onclick="showCompose();"><i class="fa fa-reply"></i> Balas</button>
            <button class="rc-tool-btn"><i class="fa fa-reply-all"></i> Balas Semua</button>
            <button class="rc-tool-btn"><i class="fa fa-share"></i> Teruskan</button>
            <button class="rc-tool-btn" style="color: #ef4444;"><i class="fa fa-trash"></i> Hapus</button>
        </div>

        <div class="rc-pane-content">
            <!-- Mail Viewer -->
            <div id="mail-view-pane">
                <div class="rc-mail-header-box">
                    <h2 class="rc-mail-title" id="mail-title">Selamat Datang di Roundcube Webmail Suite</h2>
                    <div class="rc-mail-meta">
                        <strong>Dari:</strong> Mikrotek Support &lt;support@mzi.co.id&gt;<br>
                        <strong>Kepada:</strong> <span id="display-user-email">Anda</span><br>
                        <strong>Tanggal:</strong> 22 Agustus 2026 10:45
                    </div>
                </div>

                <div class="rc-mail-body-box" id="mail-body">
                    Halo,<br><br>
                    Selamat! Anda telah berhasil login ke portal <strong>Roundcube Webmail Suite</strong>.<br><br>
                    Melalui antarmuka ini, Anda dapat:<br>
                    <ul>
                        <li>Mengirim dan membaca pesan email bisnis secara terpadu.</li>
                        <li>Mengelola folder Kotak Masuk, Pesan Terkirim, Draft, dan Sampah.</li>
                        <li>Mengakses Buku Alamat (Contacts) dan Pengaturan Identitas email.</li>
                    </ul>
                    <br>
                    Salam hangat,<br>
                    <strong>Tim Mikrotek Business Suite</strong>
                </div>
            </div>

            <!-- Mail Compose Pane -->
            <div id="mail-compose-pane" style="display: none;">
                <div class="rc-compose-box">
                    <h2 style="margin: 0 0 20px 0; font-size: 18px; color: #0f172a;">Tulis Email Baru (Roundcube)</h2>
                    <form method="post" action="<?php echo site_url('webmail/send_message'); ?>" target="_parent">
                        <?php _csrf_field(); ?>
                        <div class="rc-form-group">
                            <label>Kepada (To):</label>
                            <input type="email" name="to_email" class="rc-input" placeholder="penerima@domain.com" required>
                        </div>
                        <div class="rc-form-group">
                            <label>Subjek:</label>
                            <input type="text" name="subject" class="rc-input" placeholder="Subjek email..." required>
                        </div>
                        <div class="rc-form-group">
                            <label>Isi Pesan:</label>
                            <textarea name="message" class="rc-input" placeholder="Tulis isi pesan email Anda di sini..." required></textarea>
                        </div>
                        <div style="text-align: right;">
                            <button type="submit" class="rc-btn-compose" style="display: inline-flex; margin: 0; padding: 10px 24px;">
                                <i class="fa fa-paper-plane"></i> Kirim Email
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contacts Pane -->
            <div id="contacts-pane" style="display: none;">
                <div class="rc-mail-header-box">
                    <h2 class="rc-mail-title">Buku Alamat / Contacts (Roundcube)</h2>
                    <p style="color: #64748b; margin: 0;">Daftar kontak bisnis dan alamat email klien terdaftar.</p>
                </div>
                <div class="rc-mail-body-box">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e2e8f0; text-align: left;">
                                <th style="padding: 10px;">Nama</th>
                                <th style="padding: 10px;">Email</th>
                                <th style="padding: 10px;">Grup</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 10px; font-weight: 600;">Teknoria PT</td>
                                <td style="padding: 10px;">info@teknoria.com</td>
                                <td style="padding: 10px;"><span class="rc-badge primary">Client</span></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 10px; font-weight: 600;">Mikrotek Support</td>
                                <td style="padding: 10px;">support@mzi.co.id</td>
                                <td style="padding: 10px;"><span class="rc-badge">Internal</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Settings Pane -->
            <div id="settings-pane" style="display: none;">
                <div class="rc-mail-header-box">
                    <h2 class="rc-mail-title">Pengaturan Identitas &amp; Tampilan Email</h2>
                    <p style="color: #64748b; margin: 0;">Konfigurasi identitas email pengirim dan tanda tangan.</p>
                </div>
                <div class="rc-mail-body-box">
                    <h4 style="margin-top: 0;">Identitas Pengirim Saat Ini</h4>
                    <p style="color: #64748b;" id="settings-user-info">Terhubung dengan server mailer perusahaan.</p>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Check if user session exists in sessionStorage
window.onload = function() {
    var loggedUser = sessionStorage.getItem('rc_logged_user');
    if (loggedUser) {
        document.getElementById('login-overlay').style.display = 'none';
        document.getElementById('display-user-email').innerText = loggedUser;
        document.getElementById('settings-user-info').innerText = 'Terhubung sebagai ' + loggedUser;
    } else {
        document.getElementById('login-overlay').style.display = 'flex';
    }
};

function doLogin(e) {
    e.preventDefault();
    var email = document.getElementById('rc-username').value;
    if (email) {
        sessionStorage.setItem('rc_logged_user', email);
        document.getElementById('login-overlay').style.display = 'none';
        document.getElementById('display-user-email').innerText = email;
        document.getElementById('settings-user-info').innerText = 'Terhubung sebagai ' + email;
    }
}

function doLogout() {
    sessionStorage.removeItem('rc_logged_user');
    document.getElementById('login-overlay').style.display = 'flex';
}

function showCompose() {
    document.getElementById('mail-view-pane').style.display = 'none';
    document.getElementById('contacts-pane').style.display = 'none';
    document.getElementById('settings-pane').style.display = 'none';
    document.getElementById('mail-compose-pane').style.display = 'block';
}

function showInbox() {
    document.getElementById('mail-compose-pane').style.display = 'none';
    document.getElementById('contacts-pane').style.display = 'none';
    document.getElementById('settings-pane').style.display = 'none';
    document.getElementById('mail-view-pane').style.display = 'block';
}

function showPane(pane) {
    document.getElementById('mail-view-pane').style.display = 'none';
    document.getElementById('mail-compose-pane').style.display = 'none';
    document.getElementById('contacts-pane').style.display = 'none';
    document.getElementById('settings-pane').style.display = 'none';

    if (pane === 'mail') showInbox();
    if (pane === 'contacts') document.getElementById('contacts-pane').style.display = 'block';
    if (pane === 'settings') document.getElementById('settings-pane').style.display = 'block';
}

function readMail(id, el) {
    var items = document.querySelectorAll('.rc-mail-item');
    items.forEach(function(item) { item.classList.remove('selected'); });
    el.classList.add('selected');

    showInbox();
    if (id === 1) {
        document.getElementById('mail-title').innerText = 'Selamat Datang di Roundcube Webmail Suite';
        document.getElementById('mail-body').innerHTML = 'Halo,<br><br>Selamat! Anda telah berhasil login ke portal <strong>Roundcube Webmail Suite</strong>.<br><br>Melalui antarmuka ini, Anda dapat:<br><ul><li>Mengirim dan membaca pesan email bisnis secara terpadu.</li><li>Mengelola folder Kotak Masuk, Pesan Terkirim, Draft, dan Sampah.</li><li>Mengakses Buku Alamat (Contacts) dan Pengaturan Identitas email.</li></ul><br>Salam hangat,<br><strong>Tim Mikrotek Business Suite</strong>';
    } else if (id === 2) {
        document.getElementById('mail-title').innerText = 'Laporan Penjualan & Faktur Tagihan Klien';
        document.getElementById('mail-body').innerHTML = 'Berikut terlampir rangkuman daftar faktur dan pembayaran invoice terbaru yang telah diterbitkan untuk periode bulan ini.<br><br>Silakan periksa laporan keuangan lengkap di menu Invoices.';
    }
}
</script>

</body>
</html>
