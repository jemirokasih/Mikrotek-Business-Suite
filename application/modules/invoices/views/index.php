<div id="headerbar">

    <div class="headerbar-left">
        <h1 class="headerbar-title"><?php _trans('invoices'); ?></h1>

        <div class="headerbar-item visible-lg">
            <div class="btn-group btn-group-sm index-options">
                <a href="<?php echo site_url('invoices/status/all'); ?>"
                   class="btn <?php echo $status == 'all' ? 'btn-primary' : 'btn-default' ?>">
                    <?php _trans('all'); ?>
                </a>
                <a href="<?php echo site_url('invoices/status/draft'); ?>"
                   class="btn <?php echo $status == 'draft' ? 'btn-primary' : 'btn-default' ?>">
                    <?php _trans('draft'); ?>
                </a>
                <a href="<?php echo site_url('invoices/status/sent'); ?>"
                   class="btn <?php echo $status == 'sent' ? 'btn-primary' : 'btn-default' ?>">
                    <?php _trans('sent'); ?>
                </a>
                <a href="<?php echo site_url('invoices/status/viewed'); ?>"
                   class="btn <?php echo $status == 'viewed' ? 'btn-primary' : 'btn-default' ?>">
                    <?php _trans('viewed'); ?>
                </a>
                <a href="<?php echo site_url('invoices/status/paid'); ?>"
                   class="btn <?php echo $status == 'paid' ? 'btn-primary' : 'btn-default' ?>">
                    <?php _trans('paid'); ?>
                </a>
                <a href="<?php echo site_url('invoices/status/overdue'); ?>"
                   class="btn <?php echo $status == 'overdue' ? 'btn-primary' : 'btn-default' ?>">
                    <?php _trans('overdue'); ?>
                </a>
                <a href="<?php echo site_url('invoices/status/proforma'); ?>"
                   class="btn <?php echo $status == 'proforma' ? 'btn-warning' : 'btn-default' ?>">
                    <?php echo trans('proforma_invoices'); ?>
                </a>
            </div>
        </div>
    </div>

    <div class="headerbar-item pull-right">
        <button type="button" class="btn btn-default btn-sm submenu-toggle hidden-lg"
                data-toggle="collapse" data-target="#ip-submenu-collapse">
            <i class="fa fa-bars"></i> <?php _trans('submenu'); ?>
        </button>
        <a class="create-invoice btn btn-sm btn-primary" href="#">
            <i class="fa fa-plus"></i> <?php _trans('new'); ?>
        </a>
    </div>

</div>

<div id="submenu">
    <div class="collapse clearfix" id="ip-submenu-collapse">

        <div class="submenu-row">
            <div class="btn-group btn-group-sm index-options">
                <a href="<?php echo site_url('invoices/status/all'); ?>"
                   class="btn <?php echo $status == 'all' ? 'btn-primary' : 'btn-default' ?>">
                    <?php _trans('all'); ?>
                </a>
                <a href="<?php echo site_url('invoices/status/draft'); ?>"
                   class="btn  <?php echo $status == 'draft' ? 'btn-primary' : 'btn-default' ?>">
                    <?php _trans('draft'); ?>
                </a>
                <a href="<?php echo site_url('invoices/status/sent'); ?>"
                   class="btn  <?php echo $status == 'sent' ? 'btn-primary' : 'btn-default' ?>">
                    <?php _trans('sent'); ?>
                </a>
                <a href="<?php echo site_url('invoices/status/viewed'); ?>"
                   class="btn  <?php echo $status == 'viewed' ? 'btn-primary' : 'btn-default' ?>">
                    <?php _trans('viewed'); ?>
                </a>
                <a href="<?php echo site_url('invoices/status/paid'); ?>"
                   class="btn  <?php echo $status == 'paid' ? 'btn-primary' : 'btn-default' ?>">
                    <?php _trans('paid'); ?>
                </a>
                <a href="<?php echo site_url('invoices/status/overdue'); ?>"
                   class="btn  <?php echo $status == 'overdue' ? 'btn-primary' : 'btn-default' ?>">
                    <?php _trans('overdue'); ?>
                </a>
            </div>
        </div>

    </div>
</div>

<div id="content" class="table-content">
    <div id="filter_results">
        <?php $this->layout->load_view('invoices/partial_invoice_table'); ?>
    </div>
</div>
