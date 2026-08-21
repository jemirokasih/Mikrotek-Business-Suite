<form method="post" class="form-horizontal">

    <?php _csrf_field(); ?>

    <div id="headerbar">
        <h1 class="headerbar-title"><?php _trans('payment_method_form'); ?></h1>
        <?php $this->layout->load_view('layout/header_buttons'); ?>
    </div>

    <div id="content">

        <?php $this->layout->load_view('layout/alerts'); ?>

        <input class="hidden" name="is_update" type="hidden"
            <?php if ($this->mdl_payment_methods->form_value('is_update')) {
                echo 'value="1"';
            } else {
                echo 'value="0"';
            } ?>
        >

        <div class="form-group">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="payment_method_name" class="control-label">
                    <?php _trans('payment_method'); ?>:
                </label>
            </div>
            <div class="col-xs-12 col-sm-6">
                <input type="text" name="payment_method_name" id="payment_method_name" class="form-control"
                       value="<?php echo $this->mdl_payment_methods->form_value('payment_method_name', true); ?>" required>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="payment_method_bank_name" class="control-label">
                    Nama Bank / Penyedia:
                </label>
            </div>
            <div class="col-xs-12 col-sm-6">
                <input type="text" name="payment_method_bank_name" id="payment_method_bank_name" class="form-control"
                       value="<?php echo $this->mdl_payment_methods->form_value('payment_method_bank_name', true); ?>" placeholder="Contoh: Bank BCA / Mandiri / BNI">
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="payment_method_account_number" class="control-label">
                    No. Rekening / Virtual Account:
                </label>
            </div>
            <div class="col-xs-12 col-sm-6">
                <input type="text" name="payment_method_account_number" id="payment_method_account_number" class="form-control"
                       value="<?php echo $this->mdl_payment_methods->form_value('payment_method_account_number', true); ?>" placeholder="Contoh: 1234567890">
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="payment_method_account_name" class="control-label">
                    Atas Nama (Rekening):
                </label>
            </div>
            <div class="col-xs-12 col-sm-6">
                <input type="text" name="payment_method_account_name" id="payment_method_account_name" class="form-control"
                       value="<?php echo $this->mdl_payment_methods->form_value('payment_method_account_name', true); ?>" placeholder="Contoh: PT Mikrotek Zemiro Indonesia">
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="payment_method_notes" class="control-label">
                    Catatan Instruksi Transfer:
                </label>
            </div>
            <div class="col-xs-12 col-sm-6">
                <textarea name="payment_method_notes" id="payment_method_notes" class="form-control" rows="3" placeholder="Instruksi tambahan untuk pelanggan saat melakukan transfer..."><?php echo $this->mdl_payment_methods->form_value('payment_method_notes', true); ?></textarea>
            </div>
        </div>

    </div>

</form>
