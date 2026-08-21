<div id="headerbar" style="margin-bottom: 12px; padding: 10px 20px; min-height: auto;">
    <div class="headerbar-left" style="display: flex; align-items: center; gap: 10px;">
        <h1 class="headerbar-title" style="font-size: 16px; margin: 0; display: inline-flex; align-items: center; gap: 8px;">
            <i class="fa fa-envelope-o" style="color: #3b82f6;"></i> <?php echo trans('webmail') ?: 'Buka Email'; ?>
        </h1>
        <?php if ($is_configured) : ?>
            <span class="label label-success" style="font-size: 10px; padding: 2px 7px; border-radius: 10px; font-weight: 600;">
                <i class="fa fa-check-circle"></i> Terhubung
            </span>
        <?php endif; ?>
    </div>

    <div class="headerbar-item pull-right">
        <!-- Compact Options Dropdown Menu -->
        <div class="btn-group pull-right">
            <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 500;">
                <i class="fa fa-ellipsis-v"></i> Opsi Webmail <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right" style="border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; font-size: 13px; padding: 6px 0;">
                <?php if ($is_configured) : ?>
                    <li>
                        <a href="#" onclick="var f = document.getElementById('webmail-iframe'); if (f) f.src = f.src; return false;">
                            <i class="fa fa-refresh" style="color: #3b82f6; width: 16px;"></i> Refresh Webmail
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo htmlsc($webmail_url); ?>" target="_blank" rel="noopener noreferrer">
                            <i class="fa fa-external-link" style="color: #10b981; width: 16px;"></i> Buka di Tab Baru
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (!empty($is_admin)) : ?>
                    <?php if ($is_configured) : ?><li role="separator" class="divider" style="margin: 4px 0;"></li><?php endif; ?>
                    <li>
                        <a href="<?php echo site_url('webmail/settings'); ?>">
                            <i class="fa fa-cog" style="color: #64748b; width: 16px;"></i> Pengaturan Webmail
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<?php echo $this->layout->load_view('layout/alerts'); ?>

<div id="content" class="table-content" style="padding: 0 15px 15px 15px;">
    <?php if ($is_configured) : ?>
        <div class="webmail-container" style="background: #ffffff; border-radius: 12px; border: 1px solid var(--card-border, #e2e8f0); box-shadow: var(--card-shadow, 0 1px 3px rgba(0,0,0,0.05)); overflow: hidden; height: calc(100vh - 125px); min-height: 680px; width: 100%;">
            <iframe id="webmail-iframe"
                    src="<?php echo htmlsc($webmail_url); ?>"
                    style="width: 100%; height: 100%; border: none; display: block;"
                    title="Roundcube Webmail"
                    allow="autoplay; camera; microphone; clipboard-read; clipboard-write"
                    allowfullscreen>
            </iframe>
        </div>
    <?php else : ?>
        <div class="panel panel-default" style="border-radius: 12px; padding: 40px 20px; text-align: center; background: #ffffff; border: 1px solid var(--card-border, #e2e8f0); box-shadow: var(--card-shadow, 0 1px 3px rgba(0,0,0,0.05)); margin-top: 10px;">
            <div style="font-size: 54px; color: #3b82f6; margin-bottom: 16px;">
                <i class="fa fa-envelope-o"></i>
            </div>
            <h3 style="font-weight: 700; color: #1e293b; margin-bottom: 10px; font-size: 20px;">Konfigurasi Roundcube Webmail</h3>
            <p style="color: #64748b; max-width: 520px; margin: 0 auto 24px auto; line-height: 1.6; font-size: 14px;">
                Hubungkan portal Roundcube Webmail Anda agar dapat mengakses email dan pesan secara langsung dari dalam dashboard Mikrotek Business Suite.
            </p>
            <a href="<?php echo site_url('webmail/settings'); ?>" class="btn btn-primary btn-lg" style="border-radius: 8px; padding: 10px 24px; font-weight: 600;">
                <i class="fa fa-cog"></i> Atur Webmail Sekarang
            </a>
        </div>
    <?php endif; ?>
</div>
