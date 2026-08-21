<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead>
        <tr>
            <th><?php _trans('receipt_number'); ?></th>
            <th><?php _trans('date'); ?></th>
            <th><?php _trans('client'); ?></th>
            <th><?php _trans('invoice'); ?></th>
            <th class="amount last"><?php _trans('amount'); ?></th>
            <th><?php _trans('options'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($receipts as $receipt) : ?>
            <tr>
                <td>
                    <a href="<?php echo site_url('receipts/view/' . $receipt->receipt_id); ?>">
                        <strong><?php echo htmlsc($receipt->receipt_number); ?></strong>
                    </a>
                </td>
                <td><?php echo date_from_mysql($receipt->receipt_date); ?></td>
                <td>
                    <a href="<?php echo site_url('clients/view/' . $receipt->client_id); ?>">
                        <?php echo htmlsc($receipt->client_name); ?>
                    </a>
                </td>
                <td>
                    <?php if ($receipt->invoice_id) : ?>
                        <a href="<?php echo site_url('invoices/view/' . $receipt->invoice_id); ?>">
                            <?php echo htmlsc($receipt->invoice_number); ?>
                        </a>
                    <?php else : ?>
                        <span class="text-muted">&mdash;</span>
                    <?php endif; ?>
                </td>
                <td class="amount last"><?php echo format_currency($receipt->receipt_amount); ?></td>
                <td>
                    <div class="options btn-group">
                        <a class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-cog"></i> <?php _trans('options'); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?php echo site_url('receipts/view/' . $receipt->receipt_id); ?>">
                                    <i class="fa fa-eye fa-margin"></i> <?php _trans('view'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('receipts/generate_pdf/' . $receipt->receipt_id); ?>" target="_blank">
                                    <i class="fa fa-print fa-margin"></i> <?php _trans('pdf'); ?>
                                </a>
                            </li>
                            <?php if (has_permission('receipts', 'edit')) : ?>
                                <li>
                                    <a href="<?php echo site_url('receipts/form/' . $receipt->receipt_id); ?>">
                                        <i class="fa fa-edit fa-margin"></i> <?php _trans('edit'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (has_permission('receipts', 'delete')) : ?>
                                <li>
                                    <form action="<?php echo site_url('receipts/delete/' . $receipt->receipt_id); ?>" method="POST">
                                        <?php _csrf_field(); ?>
                                        <button type="submit" class="dropdown-button" onclick="return confirm('<?php _trans('delete_record_warning'); ?>');">
                                            <i class="fa fa-trash-o fa-margin"></i> <?php _trans('delete'); ?>
                                        </button>
                                    </form>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($receipts)) : ?>
            <tr>
                <td colspan="6" class="text-center text-muted"><?php _trans('no_records'); ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
