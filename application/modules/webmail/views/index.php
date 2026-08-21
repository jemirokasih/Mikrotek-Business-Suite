<div id="headerbar">
    <div class="headerbar-left">
        <h1 class="headerbar-title"><i class="fa fa-envelope-o"></i> <?php echo trans('webmail') ?: 'Webmail / Email'; ?></h1>
        <?php if ($is_configured) : ?>
            <span class="label label-success" style="font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                <i class="fa fa-check-circle"></i> Terhubung <?php echo !empty($webmail_email) ? '(' . htmlsc($webmail_email) . ')' : ''; ?>
            </span>
        <?php else : ?>
            <span class="label label-warning" style="font-size: 11px; padding: 4px 8px; border-radius: 4px;">
                <i class="fa fa-exclamation-triangle"></i> Belum Dikonfigurasi
            </span>
        <?php endif; ?>
    </div>

    <div class="headerbar-item pull-right">
        <?php if ($is_configured) : ?>
            <button type="button" class="btn btn-sm btn-default" onclick="var f = document.getElementById('webmail-iframe'); if (f) f.src = f.src;" title="Refresh Webmail">
                <i class="fa fa-refresh"></i> Refresh
            </button>
            <a href="<?php echo htmlsc($webmail_url); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-default" title="Buka di Tab Baru">
                <i class="fa fa-external-link"></i> Tab Baru
            </a>
        <?php endif; ?>
        <a href="<?php echo site_url('webmail/settings'); ?>" class="btn btn-sm btn-primary">
            <i class="fa fa-cog"></i> Pengaturan Webmail
        </a>
    </div>
</div>

<?php echo $this->layout->load_view('layout/alerts'); ?>

<div id="content" class="table-content">
    <?php if ($is_configured) : ?>
        <div class="webmail-container" style="background: #ffffff; border-radius: 12px; border: 1px solid var(--card-border, #e2e8f0); box-shadow: var(--card-shadow, 0 1px 3px rgba(0,0,0,0.05)); overflow: hidden; height: calc(100vh - 180px); min-height: 650px; width: 100%;">
            <iframe id="webmail-iframe"
                    src="<?php echo htmlsc($webmail_url); ?>"
                    style="width: 100%; height: 100%; border: none; display: block;"
                    title="Roundcube Webmail"
                    allow="autoplay; camera; microphone; clipboard-read; clipboard-write"
                    allowfullscreen>
            </iframe>
        </div>
    <?php else : ?>
        <div class="panel panel-default" style="border-radius: 12px; padding: 40px 20px; text-align: center; background: #ffffff; border: 1px solid var(--card-border, #e2e8f0); box-shadow: var(--card-shadow, 0 1px 3px rgba(0,0,0,0.05)); margin-top: 20px;">
            <div style="font-size: 54px; color: #3b82f6; margin-bottom: 16px;">
                <i class="fa fa-envelope-o"></i>
            </div>
            <h3 style="font-weight: 700; color: #1e293b; margin-bottom: 10px; font-size: 20px;">Konfigurasi Roundcube Webmail</h3>
            <p style="color: #64748b; max-width: 520px; margin: 0 auto 24px auto; line-height: 1.6; font-size: 14px;">
                Hubungkan portal Roundcube Webmail Anda (misal: <code>https://webmail.mzi.co.id</code>) agar dapat mengakses email dan pesan secara langsung dari dalam dashboard Mikrotek Business Suite.
            </p>
            <a href="<?php echo site_url('webmail/settings'); ?>" class="btn btn-primary btn-lg" style="border-radius: 8px; padding: 10px 24px; font-weight: 600;">
                <i class="fa fa-cog"></i> Atur Kredensial Webmail Sekarang
            </a>
        </div>
    <?php endif; ?>
</div>
