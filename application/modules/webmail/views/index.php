<?php if ($is_configured) : ?>
    <!-- Floating Options Button on top-right edge -->
    <div class="btn-group" style="position: fixed; top: 18px; right: 25px; z-index: 99999;">
        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 20px; padding: 6px 14px; background: rgba(255,255,255,0.95); backdrop-filter: blur(8px); border: 1px solid #cbd5e1; box-shadow: 0 4px 10px rgba(0,0,0,0.12); font-weight: 600; color: #334155;">
            <i class="fa fa-cog" style="color: #3b82f6;"></i> Opsi Webmail <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-right" style="border-radius: 10px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.15); border: 1px solid #e2e8f0; font-size: 13px; padding: 6px 0; min-width: 180px;">
            <li>
                <a href="#" onclick="var f = document.getElementById('webmail-iframe'); if (f) f.src = f.src; return false;">
                    <i class="fa fa-refresh" style="color: #3b82f6; width: 18px;"></i> Refresh Webmail
                </a>
            </li>
            <li>
                <a href="<?php echo htmlsc($webmail_url); ?>" target="_blank" rel="noopener noreferrer">
                    <i class="fa fa-external-link" style="color: #10b981; width: 18px;"></i> Buka di Tab Baru
                </a>
            </li>
            <?php if (!empty($is_admin)) : ?>
                <li role="separator" class="divider" style="margin: 4px 0;"></li>
                <li>
                    <a href="<?php echo site_url('webmail/settings'); ?>">
                        <i class="fa fa-sliders" style="color: #64748b; width: 18px;"></i> Pengaturan Webmail
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
<?php endif; ?>

<?php echo $this->layout->load_view('layout/alerts'); ?>

<div id="content" class="table-content" style="padding: 0; margin: -10px -15px -15px -15px;">
    <?php if ($is_configured) : ?>
        <div class="webmail-container" style="background: #ffffff; overflow: hidden; height: calc(100vh - 20px); width: 100%; border: none;">
            <iframe id="webmail-iframe"
                    src="<?php echo htmlsc($webmail_url); ?>"
                    style="width: 100%; height: 100%; border: none; display: block;"
                    title="Roundcube Webmail"
                    allow="autoplay; camera; microphone; clipboard-read; clipboard-write"
                    allowfullscreen>
            </iframe>
        </div>
    <?php else : ?>
        <div class="panel panel-default" style="border-radius: 12px; padding: 40px 20px; text-align: center; background: #ffffff; border: 1px solid var(--card-border, #e2e8f0); box-shadow: var(--card-shadow, 0 1px 3px rgba(0,0,0,0.05)); margin: 20px;">
            <div style="font-size: 54px; color: #3b82f6; margin-bottom: 16px;">
                <i class="fa fa-envelope-o"></i>
            </div>
            <h3 style="font-weight: 700; color: #1e293b; margin-bottom: 10px; font-size: 20px;">Konfigurasi Roundcube Webmail</h3>
            <p style="color: #64748b; max-width: 520px; margin: 0 auto 24px auto; line-height: 1.6; font-size: 14px;">
                Hubungkan portal Roundcube Webmail Anda agar dapat mengakses email secara langsung dari dalam Mikrotek Business Suite.
            </p>
            <a href="<?php echo site_url('webmail/settings'); ?>" class="btn btn-primary btn-lg" style="border-radius: 8px; padding: 10px 24px; font-weight: 600;">
                <i class="fa fa-cog"></i> Atur Webmail Sekarang
            </a>
        </div>
    <?php endif; ?>
</div>
