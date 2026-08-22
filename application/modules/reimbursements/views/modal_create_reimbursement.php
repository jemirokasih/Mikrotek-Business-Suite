<script>
$(function () {
    function getCsrfCookie() {
        var cookies = document.cookie.split(';');
        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i].trim();
            if (cookie.indexOf('ip_csrf_cookie=') === 0) {
                return decodeURIComponent(cookie.substring('ip_csrf_cookie='.length));
            }
        }
        return '';
    }

    $('.datepicker').datepicker({
        format: '<?php echo date_format_datepicker(); ?>',
        autoclose: true,
        todayHighlight: true
    });

    $('#form_create_reimbursement').off('submit').on('submit', function (e) {
        e.preventDefault();

        var $form = $(this);
        var $btn = $('#btn_submit_reimbursement');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
        $('#modal-error-container').hide().empty();

        var title = $.trim($form.find('[name="reimbursement_title"]').val());
        var dateVal = $.trim($form.find('[name="reimbursement_date"]').val());
        var category = $.trim($form.find('[name="category"]').val());
        var amount = $.trim($form.find('[name="amount"]').val());
        var description = $.trim($form.find('[name="description"]').val());
        var employeeId = $form.find('[name="employee_id"]').val() || '';

        var formData = new FormData();
        formData.append('reimbursement_title', title);
        formData.append('reimbursement_date', dateVal);
        formData.append('category', category);
        formData.append('amount', amount);
        formData.append('description', description);
        if (employeeId) {
            formData.append('employee_id', employeeId);
        }
        formData.append('<?php echo $this->security->get_csrf_token_name(); ?>', getCsrfCookie());

        var fileInput = $form.find('[name="attachment"]')[0];
        if (fileInput && fileInput.files && fileInput.files.length > 0) {
            formData.append('attachment', fileInput.files[0]);
        }

        $.ajax({
            url: "<?php echo site_url('reimbursements/ajax/create_reimbursement'); ?>",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (data) {
                $btn.prop('disabled', false).html('<i class="fa fa-paper-plane"></i> Kirim Pengajuan');
                if (data.success === 1) {
                    window.location.reload();
                } else {
                    var errors = data.validation_errors;
                    if (typeof errors === 'object') {
                        var errHtml = '<div class="alert alert-danger"><ul style="margin:0; padding-left: 20px;">';
                        $.each(errors, function(k, v) { errHtml += '<li>' + v + '</li>'; });
                        errHtml += '</ul></div>';
                        $('#modal-error-container').html(errHtml).show();
                    } else {
                        $('#modal-error-container').html(errors || '<div class="alert alert-danger">Gagal menyimpan klaim.</div>').show();
                    }
                }
            },
            error: function () {
                $btn.prop('disabled', false).html('<i class="fa fa-paper-plane"></i> Kirim Pengajuan');
                $('#modal-error-container').html('<div class="alert alert-danger">Terjadi kesalahan server saat menyimpan data klaim.</div>').show();
            }
        });
    });
});
</script>

<div id="modal-create-reimbursement" class="modal col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden;">
        <form id="form_create_reimbursement" enctype="multipart/form-data">
            <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 15px 20px;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="modal-title" style="font-weight: 700; color: #0f172a; font-size: 16px;">
                    <i class="fa fa-plus-circle" style="color: #3b82f6;"></i> Formulir Pengajuan Reimburse
                </h4>
            </div>

            <div class="modal-body" style="padding: 20px;">
                <div id="modal-error-container" style="display: none;"></div>

                <?php if (!empty($employee_id)) : ?>
                    <input type="hidden" name="employee_id" id="employee_id_input" value="<?php echo htmlsc($employee_id); ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="reimbursement_title" style="font-weight: 600; color: #334155;">Judul Klaim / Pengeluaran <span class="text-danger">*</span></label>
                    <input type="text" name="reimbursement_title" id="reimbursement_title" class="form-control" placeholder="Contoh: Bensin & Parkir Kunjungan Klien PT Maju Jaya" required style="border-radius: 8px;">
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="reimbursement_date" style="font-weight: 600; color: #334155;">Tanggal Transaksi/Pengeluaran <span class="text-danger">*</span></label>
                            <input type="text" name="reimbursement_date" id="reimbursement_date" class="form-control datepicker" value="<?php echo date('Y-m-d'); ?>" required style="border-radius: 8px;">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="category" style="font-weight: 600; color: #334155;">Kategori Pengeluaran <span class="text-danger">*</span></label>
                            <select name="category" id="category" class="form-control" style="border-radius: 8px;">
                                <?php foreach ($categories as $key => $label) : ?>
                                    <option value="<?php echo htmlsc($key); ?>"><?php echo htmlsc($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="amount" style="font-weight: 600; color: #334155;">Nominal Pengeluaran (Rp) <span class="text-danger">*</span></label>
                            <input type="text" inputmode="numeric" name="amount" id="amount" class="form-control" placeholder="Contoh: 10000 atau 10.000" required style="border-radius: 8px; font-weight: 700; font-size: 15px;">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label for="attachment" style="font-weight: 600; color: #334155;">Lampiran Struk / Nota Pembayaran</label>
                            <input type="file" name="attachment" id="attachment" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp, application/pdf" style="border-radius: 8px;">
                            <span class="help-block" style="font-size: 11px; color: #64748b; margin-top: 4px;">Format: PNG, JPG, WEBP, atau PDF (Maksimal 5MB).</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" style="font-weight: 600; color: #334155;">Keterangan & Rincian Pengeluaran</label>
                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Tuliskan keterangan detail pengeluaran atau catatan tambahan..." style="border-radius: 8px;"></textarea>
                </div>
            </div>

            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 12px 20px;">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                <button type="submit" class="btn btn-primary" id="btn_submit_reimbursement" style="border-radius: 8px; font-weight: 600;">
                    <i class="fa fa-paper-plane"></i> Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>
