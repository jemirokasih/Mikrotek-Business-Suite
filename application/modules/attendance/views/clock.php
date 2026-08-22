<div id="headerbar">
    <h1 class="headerbar-title"><?php _trans('attendance_portal'); ?></h1>
    <div class="headerbar-item pull-right" style="display: flex; gap: 8px; flex-wrap: wrap;">
        <a class="btn btn-sm btn-primary" href="<?php echo site_url('reimbursements/index?action=create'); ?>" style="border-radius: 6px; font-weight: 600;">
            <i class="fa fa-plus"></i> Ajukan Reimburse
        </a>
        <a class="btn btn-sm btn-default" href="<?php echo site_url('reimbursements/index'); ?>" style="border-radius: 6px;">
            <i class="fa fa-money"></i> Riwayat Reimburse
        </a>
        <a class="btn btn-sm btn-default" href="<?php echo site_url('leaves/my_leaves'); ?>" style="border-radius: 6px;">
            <i class="fa fa-calendar"></i> <?php _trans('my_leave_requests'); ?>
        </a>
    </div>
</div>

<div id="content">
    <?php $this->layout->load_view('layout/alerts'); ?>

    <?php if ( ! $employee) : ?>
        <div class="alert alert-warning">
            <h4><i class="fa fa-exclamation-triangle"></i> No Linked Employee Profile</h4>
            <p>Your user account is not linked to an Employee record yet. Please contact your system administrator to link your account.</p>
        </div>
    <?php else : ?>

        <div class="row">
            <!-- Clock Card -->
            <div class="col-xs-12 col-md-5">
                <div class="panel panel-default text-center">
                    <div class="panel-heading">
                        <strong><i class="fa fa-clock-o"></i> <?php echo date('l, d F Y'); ?></strong>
                    </div>
                    <div class="panel-body">

                        <div id="digital-clock" style="font-size: 42px; font-weight: bold; font-family: monospace; color: #337ab7; margin: 15px 0;">
                            <?php echo date('H:i:s'); ?>
                        </div>

                        <div class="well well-sm text-left" style="background: #f9f9f9;">
                            <p style="margin-bottom: 5px;">
                                <strong><i class="fa fa-user"></i> <?php _trans('employee'); ?>:</strong>
                                <?php echo html_escape($employee->first_name . ' ' . $employee->last_name); ?>
                                (<code><?php echo html_escape($employee->employee_number); ?></code>)
                            </p>
                            <p style="margin-bottom: 5px;">
                                <strong><i class="fa fa-laptop"></i> <?php _trans('ip_address'); ?>:</strong>
                                <code><?php echo $this->input->ip_address(); ?></code>
                            </p>
                            <p style="margin-bottom: 0;">
                                <strong><i class="fa fa-map-marker text-danger"></i> <?php _trans('location'); ?>:</strong>
                                <span id="location-display" class="text-muted"><i class="fa fa-spinner fa-spin"></i> Detecting GPS Location...</span>
                            </p>
                        </div>

                        <form id="form-clock">
                            <?php _csrf_field(); ?>
                            <input type="hidden" name="location" id="location" value="">

                            <div class="form-group text-left">
                                <label for="notes"><?php _trans('notes'); ?> (Optional):</label>
                                <input type="text" name="notes" id="notes" class="form-control" placeholder="e.g. Working from office / Remote">
                            </div>

                            <div id="clock-alert" class="alert alert-danger hidden"></div>

                            <?php if ( ! $today_attendance || ! $today_attendance->clock_in) : ?>
                                <button type="button" id="btn-clock-in" class="btn btn-lg btn-success btn-block" style="padding: 15px; font-size: 20px; font-weight: bold;">
                                    <i class="fa fa-sign-in"></i> <?php _trans('clock_in'); ?>
                                </button>
                            <?php elseif ( ! $today_attendance->clock_out) : ?>
                                <div class="alert alert-info" style="margin-bottom: 15px;">
                                    <i class="fa fa-check-circle"></i> Clocked In today at <strong><?php echo date('H:i:s', strtotime($today_attendance->clock_in)); ?></strong>
                                    (Status: <span class="label label-<?php echo $today_attendance->status == 'late' ? 'warning' : 'success'; ?>"><?php _trans($today_attendance->status); ?></span>)
                                </div>
                                <button type="button" id="btn-clock-out" class="btn btn-lg btn-danger btn-block" style="padding: 15px; font-size: 20px; font-weight: bold;">
                                    <i class="fa fa-sign-out"></i> <?php _trans('clock_out'); ?>
                                </button>
                            <?php else : ?>
                                <div class="alert alert-success" style="margin-bottom: 0; font-size: 16px;">
                                    <i class="fa fa-check-circle-o"></i> <strong>Already Clocked Out Today</strong><br>
                                    <small>In: <?php echo date('H:i:s', strtotime($today_attendance->clock_in)); ?> | Out: <?php echo date('H:i:s', strtotime($today_attendance->clock_out)); ?></small>
                                </div>
                            <?php endif; ?>
                        </form>

                    </div>
                </div>
            </div>

            <!-- Personal Attendance History Table -->
            <div class="col-xs-12 col-md-7">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-history"></i> <?php _trans('my_attendance'); ?> (<?php echo date('F Y'); ?>)
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th><?php _trans('date'); ?></th>
                                <th><?php _trans('status'); ?></th>
                                <th><?php _trans('clock_in'); ?></th>
                                <th><?php _trans('clock_out'); ?></th>
                                <th><?php _trans('hours_worked'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($monthly_history)) : ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted"><?php _trans('no_records_found'); ?></td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($monthly_history as $history) : ?>
                                    <?php
                                    $st    = $history->status;
                                    $badge = 'label-default';
                                    if ($st == 'present') {
                                        $badge = 'label-success';
                                    } elseif ($st == 'late') {
                                        $badge = 'label-warning';
                                    } elseif ($st == 'leave' || $st == 'sick') {
                                        $badge = 'label-info';
                                    } elseif ($st == 'absent') {
                                        $badge = 'label-danger';
                                    }

                                    $hrs = '-';
                                    if ($history->clock_in && $history->clock_out) {
                                        $sec = strtotime($history->clock_out) - strtotime($history->clock_in);
                                        if ($sec > 0) {
                                            $h   = floor($sec / 3600);
                                            $m   = floor(($sec % 3600) / 60);
                                            $hrs = sprintf('%02dh %02dm', $h, $m);
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo date_from_mysql($history->attendance_date); ?></td>
                                        <td><span class="label <?php echo $badge; ?>"><?php _trans($st); ?></span></td>
                                        <td><?php echo $history->clock_in ? date('H:i:s', strtotime($history->clock_in)) : '-'; ?></td>
                                        <td><?php echo $history->clock_out ? date('H:i:s', strtotime($history->clock_out)) : '-'; ?></td>
                                        <td><strong><?php echo $hrs; ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>

<script type="text/javascript">
    $(function () {
        // Live Clock
        setInterval(function () {
            var now = new Date();
            var h = String(now.getHours()).padStart(2, '0');
            var m = String(now.getMinutes()).padStart(2, '0');
            var s = String(now.getSeconds()).padStart(2, '0');
            $('#digital-clock').text(h + ':' + m + ':' + s);
        }, 1000);

        // HTML5 Geolocation API
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    var lat = position.coords.latitude.toFixed(6);
                    var lng = position.coords.longitude.toFixed(6);
                    var locStr = "Lat: " + lat + ", Long: " + lng;
                    $('#location').val(locStr);
                    $('#location-display').removeClass('text-muted').addClass('text-success').html('<i class="fa fa-check-circle"></i> ' + locStr);
                },
                function (error) {
                    $('#location').val('Location Access Denied');
                    $('#location-display').removeClass('text-muted').addClass('text-warning').html('<i class="fa fa-warning"></i> Location Access Denied (IP Recorded)');
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        } else {
            $('#location').val('Geolocation Not Supported');
            $('#location-display').text('Geolocation Not Supported');
        }

        // Clock In Handler
        $('#btn-clock-in').click(function () {
            var $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
            $('#clock-alert').addClass('hidden').text('');

            $.post('<?php echo site_url('attendance/save_clock_in'); ?>', $('#form-clock').serialize(), function (response) {
                var data = JSON.parse(response);
                if (data.success) {
                    window.location.reload();
                } else {
                    $('#clock-alert').removeClass('hidden').text(data.error);
                    $btn.prop('disabled', false).html('<i class="fa fa-sign-in"></i> <?php _trans('clock_in'); ?>');
                }
            }).fail(function () {
                $('#clock-alert').removeClass('hidden').text('Network error. Please try again.');
                $btn.prop('disabled', false).html('<i class="fa fa-sign-in"></i> <?php _trans('clock_in'); ?>');
            });
        });

        // Clock Out Handler
        $('#btn-clock-out').click(function () {
            var $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
            $('#clock-alert').addClass('hidden').text('');

            $.post('<?php echo site_url('attendance/save_clock_out'); ?>', $('#form-clock').serialize(), function (response) {
                var data = JSON.parse(response);
                if (data.success) {
                    window.location.reload();
                } else {
                    $('#clock-alert').removeClass('hidden').text(data.error);
                    $btn.prop('disabled', false).html('<i class="fa fa-sign-out"></i> <?php _trans('clock_out'); ?>');
                }
            }).fail(function () {
                $('#clock-alert').removeClass('hidden').text('Network error. Please try again.');
                $btn.prop('disabled', false).html('<i class="fa fa-sign-out"></i> <?php _trans('clock_out'); ?>');
            });
        });
    });
</script>
