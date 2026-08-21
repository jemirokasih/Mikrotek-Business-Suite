<form method="post">
    <?php _csrf_field(); ?>

    <div id="headerbar">
        <h1 class="headerbar-title"><?php echo trans('company_form'); ?></h1>
        <?php $this->layout->load_view('layout/header_buttons'); ?>
    </div>

    <div id="content">
        <?php $this->layout->load_view('layout/alerts'); ?>

        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading"><?php echo trans('company_details'); ?></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="company_name"><?php echo trans('company_name'); ?> *</label>
                            <input type="text" name="company_name" id="company_name" class="form-control"
                                   value="<?php echo html_escape($this->mdl_companies->form_value('company_name')); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="company_email"><?php echo trans('email'); ?></label>
                            <input type="email" name="company_email" id="company_email" class="form-control"
                                   value="<?php echo html_escape($this->mdl_companies->form_value('company_email')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="company_phone"><?php echo trans('phone'); ?></label>
                            <input type="text" name="company_phone" id="company_phone" class="form-control"
                                   value="<?php echo html_escape($this->mdl_companies->form_value('company_phone')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="company_address_1"><?php echo trans('street_address'); ?></label>
                            <input type="text" name="company_address_1" id="company_address_1" class="form-control"
                                   value="<?php echo html_escape($this->mdl_companies->form_value('company_address_1')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="company_address_2"><?php echo trans('street_address_2'); ?></label>
                            <input type="text" name="company_address_2" id="company_address_2" class="form-control"
                                   value="<?php echo html_escape($this->mdl_companies->form_value('company_address_2')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="company_city"><?php echo trans('city'); ?></label>
                            <input type="text" name="company_city" id="company_city" class="form-control"
                                   value="<?php echo html_escape($this->mdl_companies->form_value('company_city')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="company_state"><?php echo trans('state'); ?></label>
                            <input type="text" name="company_state" id="company_state" class="form-control"
                                   value="<?php echo html_escape($this->mdl_companies->form_value('company_state')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="company_zip"><?php echo trans('zip_code'); ?></label>
                            <input type="text" name="company_zip" id="company_zip" class="form-control"
                                   value="<?php echo html_escape($this->mdl_companies->form_value('company_zip')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="company_country"><?php echo trans('country'); ?></label>
                            <select name="company_country" id="company_country" class="form-control simple-select">
                                <option value=""><?php echo trans('none'); ?></option>
                                <?php foreach ($countries as $c_code => $c_name) : ?>
                                    <option value="<?php echo $c_code; ?>" <?php check_select($selected_country, $c_code); ?>>
                                        <?php echo html_escape($c_name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
