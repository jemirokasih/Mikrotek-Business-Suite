<form method="post">
    <?php _csrf_field(); ?>

    <div id="headerbar">
        <h1 class="headerbar-title"><?php echo trans('receipt_form'); ?></h1>
        <?php $this->layout->load_view('layout/header_buttons'); ?>
    </div>

    <div id="content">
        <?php $this->layout->load_view('layout/alerts'); ?>

        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading"><?php echo trans('receipt_details'); ?></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="client_id"><?php echo trans('client'); ?> *</label>
                            <select name="client_id" id="client_id" class="form-control simple-select" required>
                                <option value=""><?php echo trans('select_client'); ?></option>
                                <?php
                                $selected_client = $this->mdl_receipts->form_value('client_id');
                                foreach ($clients as $client) : ?>
                                    <option value="<?php echo $client->client_id; ?>" <?php check_select($selected_client, $client->client_id); ?>>
                                        <?php echo html_escape($client->client_name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="company_id"><?php echo trans('company'); ?></label>
                            <select name="company_id" id="company_id" class="form-control simple-select">
                                <option value=""><?php echo trans('none'); ?></option>
                                <?php
                                $selected_company = $this->mdl_receipts->form_value('company_id') ?: $this->session->userdata('company_id');
                                foreach ($companies as $comp) : ?>
                                    <option value="<?php echo $comp->company_id; ?>" <?php check_select($selected_company, $comp->company_id); ?>>
                                        <?php echo html_escape($comp->company_name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="invoice_id"><?php echo trans('invoice'); ?></label>
                            <select name="invoice_id" id="invoice_id" class="form-control simple-select">
                                <option value=""><?php echo trans('none'); ?></option>
                                <?php
                                $selected_invoice = $this->mdl_receipts->form_value('invoice_id');
                                foreach ($invoices as $inv) : ?>
                                    <option value="<?php echo $inv->invoice_id; ?>" <?php check_select($selected_invoice, $inv->invoice_id); ?>>
                                        <?php echo html_escape($inv->invoice_number); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="receipt_date"><?php echo trans('date'); ?> *</label>
                            <input type="text" name="receipt_date" id="receipt_date" class="form-control datepicker"
                                   value="<?php echo date_from_mysql($this->mdl_receipts->form_value('receipt_date') ?: date('Y-m-d')); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="receipt_amount"><?php echo trans('amount'); ?> *</label>
                            <input type="text" name="receipt_amount" id="receipt_amount" class="form-control"
                                   value="<?php echo format_amount($this->mdl_receipts->form_value('receipt_amount')); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="receipt_payment_method_id"><?php echo trans('payment_method'); ?></label>
                            <select name="receipt_payment_method_id" id="receipt_payment_method_id" class="form-control simple-select">
                                <option value=""><?php echo trans('none'); ?></option>
                                <?php
                                $selected_pm = $this->mdl_receipts->form_value('receipt_payment_method_id');
                                foreach ($payment_methods as $pm) : ?>
                                    <option value="<?php echo $pm->payment_method_id; ?>" <?php check_select($selected_pm, $pm->payment_method_id); ?>>
                                        <?php echo html_escape($pm->payment_method_name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="receipt_notes"><?php echo trans('notes'); ?> / Untuk Pembayaran</label>
                            <textarea name="receipt_notes" id="receipt_notes" class="form-control" rows="3"><?php echo html_escape($this->mdl_receipts->form_value('receipt_notes')); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
