<div id="headerbar">
    <h1 class="headerbar-title"><?php echo trans('receipts'); ?></h1>

    <div class="headerbar-item pull-right">
        <?php if (has_permission('receipts', 'create')) : ?>
            <a class="btn btn-sm btn-primary" href="<?php echo site_url('receipts/form'); ?>">
                <i class="fa fa-plus"></i> <?php echo trans('new'); ?>
            </a>
        <?php endif; ?>
    </div>
</div>

<div id="content" class="table-content">
    <?php $this->layout->load_view('layout/alerts'); ?>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?php echo trans('receipt_number'); ?></th>
                    <th><?php echo trans('date'); ?></th>
                    <th><?php echo trans('client'); ?></th>
                    <th><?php echo trans('invoice'); ?></th>
                    <th><?php echo trans('amount'); ?></th>
                    <th><?php echo trans('options'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($receipts as $receipt) : ?>
                    <tr>
                        <td>
                            <a href="<?php echo site_url('receipts/view/' . $receipt->receipt_id); ?>">
                                <strong><?php echo html_escape($receipt->receipt_number); ?></strong>
                            </a>
                        </td>
                        <td><?php echo date_from_mysql($receipt->receipt_date); ?></td>
                        <td><?php echo html_escape($receipt->client_name); ?></td>
                        <td>
                            <?php if ($receipt->invoice_id) : ?>
                                <a href="<?php echo site_url('invoices/view/' . $receipt->invoice_id); ?>">
                                    <?php echo html_escape($receipt->invoice_number); ?>
                                </a>
                            <?php else : ?>
                                <span class="text-muted">&mdash;</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo format_currency($receipt->receipt_amount); ?></td>
                        <td>
                            <div class="options btn-group">
                                <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="fa fa-cog"></i> <?php echo trans('options'); ?>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="<?php echo site_url('receipts/view/' . $receipt->receipt_id); ?>">
                                            <i class="fa fa-eye fa-margin"></i> <?php echo trans('view'); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?php echo site_url('receipts/generate_pdf/' . $receipt->receipt_id); ?>" target="_blank">
                                            <i class="fa fa-print fa-margin"></i> <?php echo trans('pdf'); ?>
                                        </a>
                                    </li>
                                    <?php if (has_permission('receipts', 'edit')) : ?>
                                        <li>
                                            <a href="<?php echo site_url('receipts/form/' . $receipt->receipt_id); ?>">
                                                <i class="fa fa-edit fa-margin"></i> <?php echo trans('edit'); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if (has_permission('receipts', 'delete')) : ?>
                                        <li>
                                            <a href="<?php echo site_url('receipts/delete/' . $receipt->receipt_id); ?>" onclick="return confirm('<?php echo trans('delete_record_warning'); ?>');">
                                                <i class="fa fa-trash-o fa-margin"></i> <?php echo trans('delete'); ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($receipts)) : ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted"><?php echo trans('no_records'); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
