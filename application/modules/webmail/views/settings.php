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
                        <i class="fa fa-lock" style="color: #3b82f6; margin-right: 6px;"></i> Kredensial &amp; URL Roundcube Webmail
                    </div>
                    <div class="panel-body" style="padding: 24px;">
                        <div class="form-group">
                            <label for="webmail_url">URL Roundcube Webmail <span class="text-danger">*</span></label>
                            <input type="url" name="webmail_url" id="webmail_url" class="form-control"
                                   value="<?php echo html_escape($webmail_url); ?>"
                                   placeholder="Contoh: https://webmail.mzi.co.id atau https://mail.domain.com" required>
                            <p class="help-block">Masukkan URL domain portal Roundcube Webmail Anda.</p>
                        </div>

                        <div class="form-group">
                            <label for="webmail_email">Alamat Email Default</label>
                            <input type="email" name="webmail_email" id="webmail_email" class="form-control"
                                   value="<?php echo html_escape($webmail_email); ?>"
                                   placeholder="Contoh: info@mzi.co.id">
                        </div>

                        <div class="form-group">
                            <label for="webmail_password">Password Email</label>
                            <input type="password" name="webmail_password" id="webmail_password" class="form-control"
                                   value="<?php echo html_escape($webmail_password); ?>"
                                   placeholder="Tulis ulang password jika ingin memperbarui">
                            <p class="help-block">Password Anda tersimpan dengan enkripsi aman AES-256 (Cryptor).</p>
                        </div>

                        <hr style="margin: 24px 0; border-color: #f1f5f9;">

                        <div class="text-right">
                            <a href="<?php echo site_url('webmail'); ?>" class="btn btn-default" style="margin-right: 8px;">
                                <?php _trans('cancel'); ?>
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> <?php _trans('save'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
