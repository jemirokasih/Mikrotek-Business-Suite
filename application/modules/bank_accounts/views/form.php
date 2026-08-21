<form method="post" class="form-horizontal">

    <?php _csrf_field(); ?>

    <div id="headerbar">
        <h1 class="headerbar-title">Form Rekening Bank</h1>
        <?php $this->layout->load_view('layout/header_buttons'); ?>
    </div>

    <div id="content">

        <?php $this->layout->load_view('layout/alerts'); ?>

        <div class="form-group">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="bank_name" class="control-label">Nama Bank: *</label>
            </div>
            <div class="col-xs-12 col-sm-6">
                <input type="text" name="bank_name" id="bank_name" class="form-control"
                       value="<?php echo $this->mdl_bank_accounts->form_value('bank_name', true); ?>" placeholder="Contoh: Bank BCA / Mandiri / BNI / BRI" required>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="account_number" class="control-label">No. Rekening / Virtual Account: *</label>
            </div>
            <div class="col-xs-12 col-sm-6">
                <input type="text" name="account_number" id="account_number" class="form-control"
                       value="<?php echo $this->mdl_bank_accounts->form_value('account_number', true); ?>" placeholder="Contoh: 1234567890" required>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="account_name" class="control-label">Atas Nama (Rekening): *</label>
            </div>
            <div class="col-xs-12 col-sm-6">
                <input type="text" name="account_name" id="account_name" class="form-control"
                       value="<?php echo $this->mdl_bank_accounts->form_value('account_name', true); ?>" placeholder="Contoh: PT Mikrotek Zemiro Indonesia" required>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="payment_method_id" class="control-label">Tautkan ke Metode Pembayaran:</label>
            </div>
            <div class="col-xs-12 col-sm-6">
                <select name="payment_method_id" id="payment_method_id" class="form-control simple-select">
                    <option value="">- Tampilkan di Semua Pembayaran Transfer -</option>
                    <?php foreach ($payment_methods as $method) : ?>
                        <option value="<?php echo $method->payment_method_id; ?>" <?php check_select($this->mdl_bank_accounts->form_value('payment_method_id'), $method->payment_method_id); ?>>
                            <?php echo html_escape($method->payment_method_name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="bank_notes" class="control-label">Catatan Tambahan:</label>
            </div>
            <div class="col-xs-12 col-sm-6">
                <textarea name="bank_notes" id="bank_notes" class="form-control" rows="2" placeholder="Catatan opsional untuk rekening ini..."><?php echo $this->mdl_bank_accounts->form_value('bank_notes', true); ?></textarea>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12 col-sm-2 text-right text-left-xs">
                <label for="bank_active" class="control-label">Status Aktif:</label>
            </div>
            <div class="col-xs-12 col-sm-6">
                <select name="bank_active" id="bank_active" class="form-control simple-select">
                    <option value="1" <?php check_select($this->mdl_bank_accounts->form_value('bank_active'), 1); ?>>Aktif (Tampilkan di Invoice & PDF)</option>
                    <option value="0" <?php check_select($this->mdl_bank_accounts->form_value('bank_active'), 0); ?>>Non-Aktif</option>
                </select>
            </div>
        </div>

    </div>

</form>
