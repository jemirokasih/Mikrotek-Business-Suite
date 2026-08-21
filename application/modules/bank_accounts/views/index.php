<div id="headerbar">
    <h1 class="headerbar-title">Master Rekening Bank</h1>

    <div class="headerbar-item pull-right">
        <a class="btn btn-sm btn-primary" href="<?php echo site_url('bank_accounts/form'); ?>">
            <i class="fa fa-plus"></i> <?php _trans('new'); ?>
        </a>
    </div>

    <div class="headerbar-item pull-right">
        <?php echo pager(site_url('bank_accounts/index'), 'mdl_bank_accounts'); ?>
    </div>
</div>

<div id="content" class="table-content">

    <?php $this->layout->load_view('layout/alerts'); ?>

    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>Nama Bank</th>
                    <th>No. Rekening</th>
                    <th>Atas Nama</th>
                    <th>Metode Pembayaran</th>
                    <th>Status</th>
                    <th><?php _trans('options'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bank_accounts as $bank) : ?>
                    <tr>
                        <td><strong><?php echo html_escape($bank->bank_name); ?></strong></td>
                        <td><code><?php echo html_escape($bank->account_number); ?></code></td>
                        <td><?php echo html_escape($bank->account_name); ?></td>
                        <td><?php echo html_escape($bank->payment_method_name ?: '- Semua -'); ?></td>
                        <td>
                            <?php if ($bank->bank_active == 1) : ?>
                                <span class="label label-success">Aktif</span>
                            <?php else : ?>
                                <span class="label label-default">Non-Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="options btn-group">
                                <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-cog"></i> <?php _trans('options'); ?>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="<?php echo site_url('bank_accounts/form/' . $bank->bank_id); ?>">
                                            <i class="fa fa-edit fa-margin"></i> <?php _trans('edit'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <form action="<?php echo site_url('bank_accounts/delete/' . $bank->bank_id); ?>" method="POST">
                                            <?php _csrf_field(); ?>
                                            <button type="submit" class="dropdown-button" onclick="return confirm('Hapus rekening bank ini?');">
                                                <i class="fa fa-trash-o fa-margin"></i> <?php _trans('delete'); ?>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($bank_accounts)) : ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data rekening bank. Klik tombol Tambah untuk membuat baru.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
