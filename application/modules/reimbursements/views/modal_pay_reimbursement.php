<script>
$(function () {
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });

    $('#btn_confirm_pay').click(function () {
        $.post("<?php echo site_url('reimbursements/ajax/pay_reimbursement'); ?>", {
            reimbursement_id: "<?php echo $reimbursement->reimbursement_id; ?>",
            payment_date: $('#payment_date').val(),
            payment_method: $('#payment_method').val(),
            _ip_csrf: Cookies.get('ip_csrf_cookie')
        }, function (data) {
            var response = JSON.parse(data);
            if (response.success === 1) {
                window.location.reload();
            } else {
                alert('Gagal memproses status lunas.');
            }
        });
    });
});
</script>

<div id="modal-pay-reimbursement" class="modal col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden;">

        <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 15px 20px;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="modal-title" style="font-weight: 700; color: #0f172a; font-size: 16px;">
                <i class="fa fa-money" style="color: #059669;"></i> Proses Pembayaran Klaim Reimburse
            </h4>
        </div>

        <div class="modal-body" style="padding: 20px;">
            <?php _csrf_field(); ?>

            <div class="well well-sm" style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px;">
                <h5 style="margin: 0 0 5px 0; font-weight: 700; color: #065f46;"><?php echo htmlsc($reimbursement->reimbursement_title); ?></h5>
                <p style="margin: 0; color: #047857; font-size: 13px;">
                    Pencairan Klaim: <strong><?php echo htmlsc($reimbursement->user_name); ?></strong><br/>
                    Nominal Pencairan: <strong style="font-size: 16px;">Rp <?php echo format_currency($reimbursement->amount); ?></strong>
                </p>
            </div>

            <div class="form-group">
                <label for="payment_date" style="font-weight: 600; color: #334155;">Tanggal Pembayaran/Pencairan <span class="text-danger">*</span></label>
                <input type="text" name="payment_date" id="payment_date" class="form-control datepicker" value="<?php echo date('Y-m-d'); ?>" required style="border-radius: 8px;">
            </div>

            <div class="form-group">
                <label for="payment_method" style="font-weight: 600; color: #334155;">Metode Pembayaran / Kas <span class="text-danger">*</span></label>
                <select name="payment_method" id="payment_method" class="form-control" style="border-radius: 8px;">
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="Kas Kecil / Cash">Kas Kecil / Cash</option>
                    <option value="Payroll / Gaji Bulanan">Dinegosiasikan Via Payroll Gaji</option>
                </select>
            </div>
        </div>

        <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 12px 20px;">
            <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
            <button type="button" class="btn btn-success" id="btn_confirm_pay" style="border-radius: 8px; font-weight: 600;">
                <i class="fa fa-check-circle"></i> Tandai Sebagai Lunas
            </button>
        </div>

    </div>
</div>
