<div id="headerbar">
    <h1 class="headerbar-title"><?php _trans('attendance_report'); ?></h1>

    <div class="headerbar-item pull-right">
        <a class="btn btn-sm btn-default" href="<?php echo site_url('attendance/index'); ?>">
            <i class="fa fa-arrow-left"></i> <?php _trans('back'); ?>
        </a>
    </div>
</div>

<div id="content">
    <?php $this->layout->load_view('layout/alerts'); ?>

    <!-- Filter Form -->
    <div class="panel panel-default" style="margin-bottom: 20px;">
        <div class="panel-body">
            <form method="get" action="<?php echo site_url('attendance/report'); ?>" class="form-inline">
                <div class="form-group">
                    <label for="month" style="margin-right: 5px;">Month:</label>
                    <select name="month" id="month" class="form-control" style="width: 130px;">
                        <?php for ($m = 1; $m <= 12; $m++) : ?>
                            <?php $m_str = sprintf('%02d', $m); ?>
                            <option value="<?php echo $m_str; ?>" <?php echo $month == $m_str ? 'selected' : ''; ?>>
                                <?php echo date('F', mktime(0, 0, 0, $m, 1)); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group" style="margin-left: 10px;">
                    <label for="year" style="margin-right: 5px;">Year:</label>
                    <select name="year" id="year" class="form-control" style="width: 100px;">
                        <?php for ($y = date('Y') - 2; $y <= date('Y') + 1; $y++) : ?>
                            <option value="<?php echo $y; ?>" <?php echo $year == $y ? 'selected' : ''; ?>>
                                <?php echo $y; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm" style="margin-left: 10px;">
                    <i class="fa fa-filter"></i> Filter Report
                </button>
            </form>
        </div>
    </div>

    <!-- Monthly Summary Table -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-bar-chart"></i> Summary Report: <strong><?php echo date('F Y', strtotime("{$year}-{$month}-01")); ?></strong>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th><?php _trans('employee_number'); ?></th>
                    <th><?php _trans('employee'); ?></th>
                    <th><?php _trans('department'); ?></th>
                    <th class="text-center"><?php _trans('present'); ?></th>
                    <th class="text-center"><?php _trans('late'); ?></th>
                    <th class="text-center">Leave / Sick</th>
                    <th class="text-center"><?php _trans('absent'); ?></th>
                    <th class="text-right"><?php _trans('hours_worked'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($employee_summary)) : ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted"><?php _trans('no_records_found'); ?></td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($employee_summary as $item) : ?>
                        <?php
                        $emp       = $item['employee'];
                        $h         = floor($item['total_seconds'] / 3600);
                        $m         = floor(($item['total_seconds'] % 3600) / 60);
                        $total_hrs = sprintf('%02dh %02dm', $h, $m);
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
                            <td class="text-center"><span class="badge label-success"><?php echo $item['present']; ?></span></td>
                            <td class="text-center"><span class="badge label-warning"><?php echo $item['late']; ?></span></td>
                            <td class="text-center"><span class="badge label-info"><?php echo $item['leave_sick']; ?></span></td>
                            <td class="text-center"><span class="badge label-danger"><?php echo $item['absent']; ?></span></td>
                            <td class="text-right"><strong><?php echo $total_hrs; ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
