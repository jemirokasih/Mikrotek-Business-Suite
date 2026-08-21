<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mikrotek Webmail Suite</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/core/css/style.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/core/css/custom.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        .webmail-app-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            display: flex;
            min-height: 580px;
            overflow: hidden;
        }
        .webmail-sidebar {
            width: 240px;
            background: #f8fafc;
            border-right: 1px solid #e2e8f0;
            padding: 20px;
            box-sizing: border-box;
        }
        .webmail-btn-compose {
            display: block;
            width: 100%;
            padding: 10px 16px;
            background: #3b82f6;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(59,130,246,0.25);
            transition: all 0.2s;
        }
        .webmail-btn-compose:hover {
            background: #2563eb;
        }
        .webmail-nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .webmail-nav-item {
            margin-bottom: 4px;
        }
        .webmail-nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: #475569;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
        }
        .webmail-nav-link:hover, .webmail-nav-link.active {
            background: #eff6ff;
            color: #2563eb;
        }
        .webmail-main {
            flex: 1;
            padding: 24px;
            box-sizing: border-box;
        }
        .form-control-custom {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
            margin-bottom: 16px;
        }
        .form-control-custom:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }
        textarea.form-control-custom {
            min-height: 200px;
            resize: vertical;
        }
    </style>
</head>
<body>

<div class="webmail-app-card">
    <div class="webmail-sidebar">
        <button type="button" class="webmail-btn-compose" onclick="document.getElementById('compose-section').style.display='block'; document.getElementById('inbox-section').style.display='none';">
            <i class="fa fa-pencil"></i> Tulis Pesan
        </button>

        <ul class="webmail-nav-list">
            <li class="webmail-nav-item">
                <a href="#" class="webmail-nav-link active" onclick="document.getElementById('compose-section').style.display='none'; document.getElementById('inbox-section').style.display='block'; return false;">
                    <i class="fa fa-inbox"></i> Kotak Masuk (Inbox)
                </a>
            </li>
            <li class="webmail-nav-item">
                <a href="#" class="webmail-nav-link" onclick="document.getElementById('compose-section').style.display='block'; document.getElementById('inbox-section').style.display='none'; return false;">
                    <i class="fa fa-paper-plane-o"></i> Pesan Terkirim
                </a>
            </li>
        </ul>
    </div>

    <div class="webmail-main">
        <!-- Inbox List Section -->
        <div id="inbox-section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #0f172a;">Kotak Masuk Email</h3>
                <span style="font-size: 12px; color: #64748b; background: #f1f5f9; padding: 4px 10px; border-radius: 12px;">
                    <?php echo html_escape($webmail_email ?: 'Default Mailer'); ?>
                </span>
            </div>

            <div style="text-align: center; padding: 60px 20px; color: #64748b;">
                <i class="fa fa-envelope-open-o" style="font-size: 48px; color: #94a3b8; margin-bottom: 16px;"></i>
                <h4 style="margin: 0 0 8px 0; color: #334155; font-size: 16px;">Webmail Siap Digunakan</h4>
                <p style="margin: 0; max-width: 420px; margin: 0 auto; font-size: 13px; line-height: 1.5;">
                    Sistem email terintegrasi langsung dengan Mikrotek Suite. Anda dapat mengirim email bisnis, tagihan faktur, dan konfirmasi ke klien kapan saja.
                </p>
            </div>
        </div>

        <!-- Compose Mail Section -->
        <div id="compose-section" style="display: none;">
            <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 700; color: #0f172a; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px;">
                Tulis Pesan Email Baru
            </h3>

            <form method="post" action="<?php echo site_url('webmail/send_message'); ?>" target="_parent">
                <?php _csrf_field(); ?>
                <label style="font-weight: 600; margin-bottom: 6px; display: block; color: #334155;">Penerima Email (To):</label>
                <input type="email" name="to_email" class="form-control-custom" placeholder="email.klien@domain.com" required>

                <label style="font-weight: 600; margin-bottom: 6px; display: block; color: #334155;">Subjek Pesan:</label>
                <input type="text" name="subject" class="form-control-custom" placeholder="Tulis subjek email di sini" required>

                <label style="font-weight: 600; margin-bottom: 6px; display: block; color: #334155;">Isi Pesan Email:</label>
                <textarea name="message" class="form-control-custom" placeholder="Tulis isi pesan email Anda di sini..." required></textarea>

                <div style="text-align: right; margin-top: 10px;">
                    <button type="submit" style="padding: 10px 24px; background: #3b82f6; color: #ffffff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                        <i class="fa fa-paper-plane"></i> Kirim Pesan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
