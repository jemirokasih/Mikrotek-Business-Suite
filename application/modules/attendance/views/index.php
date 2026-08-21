<div id="headerbar">
    <h1 class="headerbar-title"><?php _trans('daily_attendance'); ?></h1>

    <div class="headerbar-item pull-right">
        <a class="btn btn-sm btn-default" href="<?php echo site_url('attendance/clock'); ?>">
            <i class="fa fa-clock-o"></i> <?php _trans('attendance_portal'); ?>
        </a>
        <a class="btn btn-sm btn-primary" href="<?php echo site_url('attendance/report'); ?>">
            <i class="fa fa-bar-chart"></i> <?php _trans('attendance_report'); ?>
        </a>
    </div>
</div>

<div id="content">
    <?php $this->layout->load_view('layout/alerts'); ?>

    <!-- Date Picker & Filter Form -->
    <div class="panel panel-default" style="margin-bottom: 20px;">
        <div class="panel-body">
            <form method="get" action="<?php echo site_url('attendance/index'); ?>" class="form-inline">
                <div class="form-group">
                    <label for="date" style="margin-right: 10px;"><?php _trans('date'); ?>:</label>
                    <input type="text" name="date" id="date" class="form-control datepicker"
                           value="<?php echo $date; ?>" style="width: 160px;">
                </div>
                <button type="submit" class="btn btn-primary btn-sm" style="margin-left: 5px;">
                    <i class="fa fa-search"></i> <?php _trans('filter_employees'); ?>
                </button>
                <a href="<?php echo site_url('attendance/index/' . date('Y-m-d')); ?>" class="btn btn-default btn-sm">
                    <?php _trans('today'); ?>
                </a>
            </form>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-xs-6 col-sm-3">
            <div class="panel panel-primary text-center" style="margin-bottom: 0;">
                <div class="panel-heading">
                    <h4 style="margin: 0; font-weight: bold;"><?php echo $total_employees; ?></h4>
                    <small><?php _trans('total_employees'); ?></small>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="panel panel-success text-center" style="margin-bottom: 0;">
                <div class="panel-heading">
                    <h4 style="margin: 0; font-weight: bold;"><?php echo $present_count; ?></h4>
                    <small><?php _trans('present'); ?></small>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="panel panel-warning text-center" style="margin-bottom: 0;">
                <div class="panel-heading">
                    <h4 style="margin: 0; font-weight: bold;"><?php echo $late_count; ?></h4>
                    <small><?php _trans('late'); ?></small>
                </div>
            </div>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="panel panel-danger text-center" style="margin-bottom: 0;">
                <div class="panel-heading">
                    <h4 style="margin: 0; font-weight: bold;"><?php echo $absent_count; ?></h4>
                    <small><?php _trans('absent'); ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-list"></i> <?php _trans('attendance'); ?> - <?php echo date_from_mysql($date); ?>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th><?php _trans('employee_number'); ?></th>
                    <th><?php _trans('employee'); ?></th>
                    <th><?php _trans('department'); ?></th>
                    <th><?php _trans('status'); ?></th>
                    <th><?php _trans('clock_in'); ?></th>
                    <th><?php _trans('clock_out'); ?></th>
                    <th><?php _trans('hours_worked'); ?></th>
                    <th><?php _trans('ip_address'); ?> & <?php _trans('location'); ?></th>
                    <th class="text-right"><?php _trans('options'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($employees)) : ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted"><?php _trans('no_records_found'); ?></td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($employees as $emp) : ?>
                        <?php
                        $att       = $attendance_map[$emp->employee_id] ?? null;
                        $status    = $att ? $att->status : 'absent';
                        $clock_in  = ($att && $att->clock_in) ? date('H:i:s', strtotime($att->clock_in)) : '-';
                        $clock_out = ($att && $att->clock_out) ? date('H:i:s', strtotime($att->clock_out)) : '-';

                        $hours = '-';
                        if ($att && $att->clock_in && $att->clock_out) {
                            $sec = strtotime($att->clock_out) - strtotime($att->clock_in);
                            if ($sec > 0) {
                                $h     = floor($sec / 3600);
                                $m     = floor(($sec % 3600) / 60);
                                $hours = sprintf('%02dh %02dm', $h, $m);
                            }
                        }

                        $badge_class = 'label-default';
                        if ($status == 'present') {
                            $badge_class = 'label-success';
                        } elseif ($status == 'late') {
                            $badge_class = 'label-warning';
                        } elseif ($status == 'leave' || $status == 'sick') {
                            $badge_class = 'label-info';
                        } elseif ($status == 'absent') {
                            $badge_class = 'label-danger';
                        }
                        ?>
                        <tr>
                            <td><code><?php echo html_escape($emp->employee_number); ?></code></td>
                            <td>
                                <strong>
                                    <a href="<?php echo site_url('employees/view/' . $emp->employee_id); ?>">
                                        <?php echo html_escape($emp->first_name . ' ' . $emp->last_name); ?>
                                    </a>
                                </strong>
                            </td>
                            <td><?php echo html_escape($emp->department ?: '-'); ?></td>
                            <td>
                                <span class="label <?php echo $badge_class; ?>">
                                    <?php _trans($status); ?>
                                </span>
                                <?php if ($att && $att->is_manual) : ?>
                                    <span class="label label-default" data-toggle="tooltip" title="Manual Attendance by Admin">Manual</span>
                                <?php endif; ?>
                            </td>
                            <td><i class="fa fa-sign-in text-success"></i> <?php echo $clock_in; ?></td>
                            <td><i class="fa fa-sign-out text-danger"></i> <?php echo $clock_out; ?></td>
                            <td><strong><?php echo $hours; ?></strong></td>
                            <td>
                                <?php if ($att && ($att->clock_in_ip || $att->clock_in_location)) : ?>
                                    <small class="text-muted">
                                        <i class="fa fa-laptop"></i> <?php echo html_escape($att->clock_in_ip ?: '-'); ?><br>
                                        <i class="fa fa-map-marker"></i> <?php echo html_escape($att->clock_in_location ?: '-'); ?>
                                    </small>
                                <?php else : ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                                <?php if (has_permission('attendance', 'edit')) : ?>
                                    <button type="button" class="btn btn-xs btn-default btn-manual-attendance"
                                            data-employee-id="<?php echo $emp->employee_id; ?>"
                                            data-date="<?php echo $date; ?>">
                                        <i class="fa fa-pencil"></i> <?php _trans('manual_attendance'); ?>
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modal-placeholder"></div>

<script type="text/javascript">
    $(function () {
        $('.btn-manual-attendance').click(function () {
            var employee_id = $(this).data('employee-id');
            var date = $(this).data('date');

            $('#modal-placeholder').load('<?php echo site_url('attendance/modal_manual_attendance'); ?>', {
                employee_id: employee_id,
                date: date,
                _ip_csrf: '<?php echo $this->security->get_csrf_hash(); ?>'
            }, function () {
                $('#modal-manual-attendance').modal('show');
            });
        });
    });
</script>
