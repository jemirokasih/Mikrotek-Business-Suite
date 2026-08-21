<div id="headerbar">
    <div class="headerbar-left">
        <h1 class="headerbar-title"><i class="fa fa-cog"></i> Pengaturan Roundcube Webmail</h1>
    </div>
    <div class="headerbar-item pull-right">
        <a href="<?php echo site_url('webmail'); ?>" class="btn btn-sm btn-default">
            <i class="fa fa-arrow-left"></i> Kembali ke Webmail
        </a>
    </div>
</div>

<?php echo $this->layout->load_view('layout/alerts'); ?>

<div id="content">
    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2">
            <form method="post" action="<?php echo site_url('webmail/save_settings'); ?>" class="card-form">
                <?php _csrf_field(); ?>
                <div class="panel panel-default" style="border-radius: 12px; border: 1px solid var(--card-border, #e2e8f0); box-shadow: var(--card-shadow, 0 1px 3px rgba(0,0,0,0.05)); margin-top: 10px;">
                    <div class="panel-heading" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; font-weight: 700; font-size: 15px; border-top-left-radius: 12px; border-top-right-radius: 12px; padding: 14px 20px;">
                        <i class="fa fa-server" style="color: #3b82f6; margin-right: 6px;"></i> Konfigurasi Server Webmail &amp; Roundcube (Administrator Only)
                    </div>
                    <div class="panel-body" style="padding: 24px;">
                        <div class="form-group">
                            <label>Pilihan Mode Integrasi Webmail:</label>
                            <div class="radio" style="margin-top: 8px;">
                                <label style="font-weight: 600;">
                                    <input type="radio" name="webmail_mode" value="external" <?php echo ($webmail_mode === 'external') ? 'checked' : ''; ?> onclick="document.getElementById('external-url-group').style.display='block';">
                                    <strong>Opsi 1: URL External (Roundcube cPanel / Hosting Webmail)</strong>
                                </label>
                                <p class="help-block" style="margin-left: 20px;">Gunakan ini jika perusahaan/klien Anda sudah memiliki server Webmail Roundcube tersendiri (misal cPanel/Webmail domain).</p>
                            </div>
                            <div class="radio">
                                <label style="font-weight: 600;">
                                    <input type="radio" name="webmail_mode" value="internal" <?php echo ($webmail_mode !== 'external') ? 'checked' : ''; ?> onclick="document.getElementById('external-url-group').style.display='none';">
                                    <strong>Opsi 2: Built-in Internal Webmail App</strong>
                                </label>
                                <p class="help-block" style="margin-left: 20px;">Gunakan aplikasi Webmail bawaan yang langsung menyatu 100% di dalam Mikrotek Suite.</p>
                            </div>
                        </div>

                        <div class="form-group" id="external-url-group" style="display: <?php echo ($webmail_mode === 'external') ? 'block' : 'none'; ?>;">
                            <label for="webmail_url">URL Roundcube cPanel / External <span class="text-danger">*</span></label>
                            <input type="url" name="webmail_url" id="webmail_url" class="form-control"
                                   value="<?php echo html_escape($webmail_url); ?>"
                                   placeholder="Contoh: https://webmail.domain.com atau https://domain.com:2096">
                            <p class="help-block">Masukkan URL domain portal Roundcube Webmail cPanel/Hosting Anda.</p>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <label for="webmail_imap_host">Server IMAP (Incoming Host &amp; Port)</label>
                                    <input type="text" name="webmail_imap_host" id="webmail_imap_host" class="form-control"
                                           value="<?php echo html_escape($webmail_imap_host ?? 'ssl://mail.mzi.co.id:993'); ?>"
                                           placeholder="Contoh: ssl://mail.domain.com:993">
                                    <p class="help-block">Alamat server IMAP untuk penerimaan email karyawan.</p>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <label for="webmail_smtp_host">Server SMTP (Outgoing Host &amp; Port)</label>
                                    <input type="text" name="webmail_smtp_host" id="webmail_smtp_host" class="form-control"
                                           value="<?php echo html_escape($webmail_smtp_host ?? 'ssl://mail.mzi.co.id:465'); ?>"
                                           placeholder="Contoh: ssl://mail.domain.com:465">
                                    <p class="help-block">Alamat server SMTP untuk pengiriman email karyawan.</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="webmail_default_domain">Domain Email Perusahaan Default</label>
                            <input type="text" name="webmail_default_domain" id="webmail_default_domain" class="form-control"
                                   value="<?php echo html_escape($webmail_default_domain ?? 'mzi.co.id'); ?>"
                                   placeholder="Contoh: mzi.co.id">
                            <p class="help-block">Domain email resmi perusahaan yang digunakan oleh karyawan saat login.</p>
                        </div>

                        <hr style="margin: 24px 0; border-color: #f1f5f9;">

                        <div class="text-right">
                            <a href="<?php echo site_url('webmail'); ?>" class="btn btn-default" style="margin-right: 8px;">
                                <?php _trans('cancel'); ?>
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Simpan Konfigurasi Server
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
