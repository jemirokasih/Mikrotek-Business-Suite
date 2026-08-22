<?php
if (!empty($reimbursement_title)) : ?>
<?php endif; ?>
<script>
$(function () {
    // -------------------------------------------------------
    // CSRF helper
    // -------------------------------------------------------
    function getCsrf() {
        var match = document.cookie.match(/(^|;\s*)ip_csrf_cookie=([^;]+)/);
        return match ? decodeURIComponent(match[2]) : '';
    }

    // -------------------------------------------------------
    // Date picker
    // -------------------------------------------------------
    $('#create_reimb_date').datepicker({
        format: '<?php echo date_format_datepicker(); ?>',
        autoclose: true,
        todayHighlight: true
    });

    // -------------------------------------------------------
    // Submit handler
    // -------------------------------------------------------
    $('#form_create_reimbursement').off('submit').on('submit', function (e) {
        e.preventDefault();

        var $form = $(this);
        var $btn  = $form.find('#btn_submit_reimb');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
        $form.find('#reimb-error-box').hide().html('');

        // Build FormData field by field from THIS form's elements
        var fd = new FormData();
        fd.append('reimbursement_title',  $form.find('[name=reimbursement_title]').val());
        fd.append('reimbursement_date',   $form.find('[name=reimbursement_date]').val());
        fd.append('category',             $form.find('[name=category]').val());
        fd.append('reimbursement_amount',   $form.find('#create_reimb_amount').val());
        fd.append('description',          $form.find('[name=description]').val());

        var emp = $form.find('[name=employee_id]');
        if (emp.length && emp.val()) {
            fd.append('employee_id', emp.val());
        }

        var file = $form.find('[name=attachment]')[0];
        if (file && file.files.length > 0) {
            fd.append('attachment', file.files[0]);
        }

        // CSRF token
        fd.append('_ip_csrf', getCsrf());

        $.ajax({
            url: '<?php echo site_url("reimbursements/ajax/create_reimbursement"); ?>',
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                $btn.prop('disabled', false).html('<i class="fa fa-paper-plane"></i> Kirim Pengajuan');
                if (res && res.success == 1) {
                    $('#modal-create-reimbursement').modal('hide');
                    window.location.reload();
                } else {
                    var msg = (res && res.error) ? res.error : 'Gagal menyimpan data. Silakan coba lagi.';
                    $form.find('#reimb-error-box').html('<div class="alert alert-danger">' + msg + '</div>').show();
                }
            },
            error: function (xhr) {
                $btn.prop('disabled', false).html('<i class="fa fa-paper-plane"></i> Kirim Pengajuan');
                var msg = 'Terjadi kesalahan server.';
                try {
                    var r = JSON.parse(xhr.responseText);
                    if (r && r.error) msg = r.error;
                } catch(ex) {}
                $form.find('#reimb-error-box').html('<div class="alert alert-danger">' + msg + '</div>').show();
            }
        });
    });
});
</script>

<div id="modal-create-reimbursement" class="modal fade col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" role="dialog" aria-labelledby="modal-create-title" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.12); border: none;">
            <form id="form_create_reimbursement" enctype="multipart/form-data" autocomplete="off">
                <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; border-radius: 12px 12px 0 0;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal-create-title" style="font-weight: 700; color: #0f172a;">
                        <i class="fa fa-plus-circle" style="color: #3b82f6;"></i>&nbsp;Formulir Pengajuan Reimburse
                    </h4>
                </div>

                <div class="modal-body" style="padding: 22px;">
                    <div id="reimb-error-box" style="display:none;"></div>

                    <?php if (!empty($employee_id)) : ?>
                        <input type="hidden" name="employee_id" value="<?php echo (int) $employee_id; ?>">
                    <?php endif; ?>

                    <!-- Judul -->
                    <div class="form-group">
                        <label style="font-weight: 600; color: #334155;">Judul Klaim / Pengeluaran <span class="text-danger">*</span></label>
                        <input type="text" name="reimbursement_title" class="form-control"
                               placeholder="Contoh: Bensin &amp; Parkir Kunjungan Klien"
                               style="border-radius: 8px;">
                    </div>

                    <div class="row">
                        <!-- Tanggal -->
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label style="font-weight: 600; color: #334155;">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                                <input type="text" name="reimbursement_date" id="create_reimb_date"
                                       class="form-control datepicker"
                                       value="<?php echo date_from_mysql(date('Y-m-d')); ?>"
                                       style="border-radius: 8px;" autocomplete="off">
                            </div>
                        </div>
                        <!-- Kategori -->
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label style="font-weight: 600; color: #334155;">Kategori <span class="text-danger">*</span></label>
                                <select name="category" class="form-control" style="border-radius: 8px;">
                                    <?php foreach ($categories as $key => $label) : ?>
                                        <option value="<?php echo htmlsc($key); ?>"><?php echo htmlsc($label); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Nominal -->
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label style="font-weight: 600; color: #334155;">Nominal (Rp) <span class="text-danger">*</span></label>
                                <input type="text" inputmode="numeric" name="reimbursement_amount" id="create_reimb_amount" class="form-control"
                                       placeholder="Contoh: 50000"
                                       style="border-radius: 8px; font-weight: 700; font-size: 15px;">
                            </div>
                        </div>
                        <!-- Lampiran -->
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label style="font-weight: 600; color: #334155;">Lampiran Nota / Struk</label>
                                <input type="file" name="attachment" class="form-control"
                                       accept="image/*,application/pdf"
                                       style="border-radius: 8px;">
                                <span class="help-block" style="font-size: 11px; color: #64748b;">PNG, JPG, WEBP, atau PDF.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="form-group">
                        <label style="font-weight: 600; color: #334155;">Keterangan / Rincian</label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="Tuliskan keterangan detail..."
                                  style="border-radius: 8px;"></textarea>
                    </div>
                </div>

                <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; border-radius: 0 0 12px 12px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" id="btn_submit_reimb" class="btn btn-primary" style="border-radius: 8px; font-weight: 600;">
                        <i class="fa fa-paper-plane"></i>&nbsp;Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
