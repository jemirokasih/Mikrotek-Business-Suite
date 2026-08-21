<div id="headerbar">
    <h1 class="headerbar-title"><?php echo trans('receipt'); ?> #<?php echo html_escape($receipt->receipt_number); ?></h1>

    <div class="headerbar-item pull-right btn-group">
        <a class="btn btn-sm btn-default" href="<?php echo site_url('receipts/generate_pdf/' . $receipt->receipt_id); ?>" target="_blank">
            <i class="fa fa-print"></i> <?php echo trans('pdf'); ?>
        </a>
        <?php if (has_permission('receipts', 'edit')) : ?>
            <a class="btn btn-sm btn-primary" href="<?php echo site_url('receipts/form/' . $receipt->receipt_id); ?>">
                <i class="fa fa-edit"></i> <?php echo trans('edit'); ?>
            </a>
        <?php endif; ?>
        <a class="btn btn-sm btn-default" href="<?php echo site_url('receipts'); ?>">
            <i class="fa fa-arrow-left"></i> <?php echo trans('back'); ?>
        </a>
    </div>
</div>

<div id="content">
    <?php $this->layout->load_view('layout/alerts'); ?>

    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2">
            <div class="panel panel-default" style="border: 2px solid #333; padding: 20px; background: #fff;">
                <div class="text-center" style="border-bottom: 2px double #333; padding-bottom: 15px; margin-bottom: 20px;">
                    <h2 style="margin: 0; font-weight: bold; text-transform: uppercase;">KWITANSI PEMBAYARAN</h2>
                    <p style="margin: 5px 0 0 0;">No: <strong><?php echo html_escape($receipt->receipt_number); ?></strong></p>
                </div>

                <table class="table table-borderless" style="font-size: 15px;">
                    <tr>
                        <td style="width: 25%;"><strong>Sudah Diterima Dari</strong></td>
                        <td style="width: 5%;">:</td>
                        <td><strong><?php echo html_escape($receipt->client_name); ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong>Banyaknya Uang</strong></td>
                        <td>:</td>
                        <td style="background: #f4f4f4; font-style: italic; font-weight: bold; padding: 8px;">
                            # <?php echo $terbilang; ?> #
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Untuk Pembayaran</strong></td>
                        <td>:</td>
                        <td>
                            <?php echo nl2br(html_escape($receipt->receipt_notes ?: '-')); ?>
                            <?php if ($receipt->invoice_number) : ?>
                                <br><small class="text-muted">(Faktur: #<?php echo html_escape($receipt->invoice_number); ?>)</small>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Metode Pembayaran</strong></td>
                        <td>:</td>
                        <td><?php echo html_escape($receipt->payment_method_name ?: 'Tunai / Transfer'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah</strong></td>
                        <td>:</td>
                        <td style="font-size: 20px; font-weight: bold; color: #27ae60;">
                            <?php echo format_currency($receipt->receipt_amount); ?>
                        </td>
                    </tr>
                </table>

                <div class="row" style="margin-top: 40px;">
                    <div class="col-xs-6">
                        <p>Tanggal: <strong><?php echo date_from_mysql($receipt->receipt_date); ?></strong></p>
                    </div>
                    <div class="col-xs-6 text-center pull-right">
                        <p><?php echo html_escape($receipt->company_name ?: get_setting('custom_title')); ?></p>
                        <br><br><br>
                        <p style="text-decoration: underline; font-weight: bold;">
                            ( <?php echo html_escape($receipt->user_name ?: 'Kasir / Bendahara'); ?> )
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
