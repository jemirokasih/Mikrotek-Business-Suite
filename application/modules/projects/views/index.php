<div id="headerbar">
    <h1 class="headerbar-title"><?php _trans('projects'); ?></h1>

    <div class="headerbar-item pull-right">
        <a class="btn btn-sm btn-primary" href="<?php echo site_url('projects/form'); ?>">
            <i class="fa fa-plus"></i> <?php _trans('new'); ?>
        </a>
    </div>

</div>

<div id="content" class="table-content">

    <?php $this->layout->load_view('layout/alerts'); ?>

    <div id="filter_results">
        <?php $this->layout->load_view('projects/partial_projects_table'); ?>
    </div>

</div>
