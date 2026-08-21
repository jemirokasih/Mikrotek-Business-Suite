<form method="post">
    <?php _csrf_field(); ?>

    <div id="headerbar">
        <h1 class="headerbar-title">
            <?php echo $id ? trans('edit_employee') : trans('add_employee'); ?>
        </h1>
        <?php $this->layout->load_view('layout/header_buttons'); ?>
    </div>

    <div id="content">

        <?php $this->layout->load_view('layout/alerts'); ?>

        <div class="row">
            <!-- Left Column: Personal & Contact Information -->
            <div class="col-xs-12 col-md-6">

                <!-- PANEL 1: Personal Information -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-user"></i> <?php _trans('personal_information'); ?>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="employee_number"><?php _trans('employee_number'); ?> *</label>
                                    <input type="text" name="employee_number" id="employee_number" class="form-control"
                                           value="<?php echo $this->mdl_employees->form_value('employee_number', true); ?>" required>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="national_id"><?php _trans('national_id'); ?></label>
                                    <input type="text" name="national_id" id="national_id" class="form-control"
                                           value="<?php echo $this->mdl_employees->form_value('national_id', true); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="first_name"><?php _trans('first_name'); ?> *</label>
                                    <input type="text" name="first_name" id="first_name" class="form-control"
                                           value="<?php echo $this->mdl_employees->form_value('first_name', true); ?>" required>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="last_name"><?php _trans('last_name'); ?></label>
                                    <input type="text" name="last_name" id="last_name" class="form-control"
                                           value="<?php echo $this->mdl_employees->form_value('last_name', true); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label for="gender"><?php _trans('gender'); ?></label>
                                    <select name="gender" id="gender" class="form-control">
                                        <option value=""><?php _trans('none'); ?></option>
                                        <option value="male" <?php echo $this->mdl_employees->form_value('gender') == 'male' ? 'selected' : ''; ?>><?php _trans('male'); ?></option>
                                        <option value="female" <?php echo $this->mdl_employees->form_value('gender') == 'female' ? 'selected' : ''; ?>><?php _trans('female'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label for="birth_place"><?php _trans('birth_place'); ?></label>
                                    <input type="text" name="birth_place" id="birth_place" class="form-control"
                                           value="<?php echo $this->mdl_employees->form_value('birth_place', true); ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label for="birth_date"><?php _trans('birth_date'); ?></label>
                                    <input type="text" name="birth_date" id="birth_date" class="form-control datepicker"
                                           value="<?php echo date_from_mysql($this->mdl_employees->form_value('birth_date')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PANEL 2: Contact Details -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-envelope"></i> <?php _trans('contact_details'); ?>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="email"><?php _trans('email'); ?> *</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                           value="<?php echo $this->mdl_employees->form_value('email', true); ?>" required>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3">
                                <div class="form-group">
                                    <label for="mobile"><?php _trans('mobile'); ?></label>
                                    <input type="text" name="mobile" id="mobile" class="form-control"
                                           value="<?php echo $this->mdl_employees->form_value('mobile', true); ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3">
                                <div class="form-group">
                                    <label for="phone"><?php _trans('phone'); ?></label>
                                    <input type="text" name="phone" id="phone" class="form-control"
                                           value="<?php echo $this->mdl_employees->form_value('phone', true); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="address_1"><?php _trans('street_address'); ?></label>
                                    <input type="text" name="address_1" id="address_1" class="form-control"
                                           value="<?php echo $this->mdl_employees->form_value('address_1', true); ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="address_2"><?php _trans('street_address_2'); ?></label>
                                    <input type="text" name="address_2" id="address_2" class="form-control"
                                           value="<?php echo $this->mdl_employees->form_value('address_2', true); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-3">
                                <div class="form-group">
                                    <label for="city"><?php _trans('city'); ?></label>
                                    <input type="text" name="city" id="city" class="form-control"
                                           value="<?php echo $this->mdl_employees->form_value('city', true); ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3">
                                <div class="form-group">
                                    <label for="state"><?php _trans('state'); ?></label>
                                    <input type="text" name="state" id="state" class="form-control"
                                           value="<?php echo $this->mdl_employees->form_value('state', true); ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3">
                                <div class="form-group">
                                    <label for="zip_code"><?php _trans('zip_code'); ?></label>
                                    <input type="text" name="zip_code" id="zip_code" class="form-control"
                                           value="<?php echo $this->mdl_employees->form_value('zip_code', true); ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-3">
                                <div class="form-group">
                                    <label for="country"><?php _trans('country'); ?></label>
                                    <input type="text" name="country" id="country" class="form-control"
                                           value="<?php echo $this->mdl_employees->form_value('country', true); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Employment & Bank/Payroll Information -->
            <div class="col-xs-12 col-md-6">

                <!-- PANEL 3: Employment Information -->
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <i class="fa fa-briefcase"></i> <?php _trans('employment_information'); ?>
                        <div class="pull-right">
                            <label for="active" class="control-label" style="font-weight: normal; margin-bottom: 0;">
                                <input type="checkbox" name="active" id="active" value="1"
                                    <?php echo $this->mdl_employees->form_value('active') ? 'checked' : ''; ?>>
                                <strong><?php _trans('active'); ?></strong>
                            </label>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="company_id"><?php _trans('company'); ?></label>
                                    <select name="company_id" id="company_id" class="form-control">
                                        <?php foreach ($companies as $company) : ?>
                                            <option value="<?php echo $company->company_id; ?>"
                                                <?php echo $this->mdl_employees->form_value('company_id') == $company->company_id ? 'selected' : ''; ?>>
                                                <?php echo html_escape($company->company_name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="department"><?php _trans('department'); ?></label>
                                    <input type="text" name="department" id="department" class="form-control"
                                           placeholder="e.g. IT, Finance, Operations"
                                           value="<?php echo $this->mdl_employees->form_value('department', true); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label for="job_title"><?php _trans('job_title'); ?></label>
                                    <input type="text" name="job_title" id="job_title" class="form-control"
                                           placeholder="e.g. Senior Software Engineer"
                                           value="<?php echo $this->mdl_employees->form_value('job_title', true); ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label for="employment_status"><?php _trans('employment_status'); ?></label>
                                    <select name="employment_status" id="employment_status" class="form-control">
                                        <option value="full_time" <?php echo $this->mdl_employees->form_value('employment_status') == 'full_time' ? 'selected' : ''; ?>><?php _trans('full_time'); ?></option>
                                        <option value="part_time" <?php echo $this->mdl_employees->form_value('employment_status') == 'part_time' ? 'selected' : ''; ?>><?php _trans('part_time'); ?></option>
                                        <option value="contract" <?php echo $this->mdl_employees->form_value('employment_status') == 'contract' ? 'selected' : ''; ?>><?php _trans('contract'); ?></option>
                                        <option value="intern" <?php echo $this->mdl_employees->form_value('employment_status') == 'intern' ? 'selected' : ''; ?>><?php _trans('intern'); ?></option>
                                        <option value="freelance" <?php echo $this->mdl_employees->form_value('employment_status') == 'freelance' ? 'selected' : ''; ?>><?php _trans('freelance'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label for="join_date"><?php _trans('join_date'); ?></label>
                                    <input type="text" name="join_date" id="join_date" class="form-control datepicker"
                                           value="<?php echo date_from_mysql($this->mdl_employees->form_value('join_date')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PANEL 4: Bank & Payroll Details -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-bank"></i> <?php _trans('bank_payroll_details'); ?>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label for="bank_name"><?php _trans('bank_name'); ?></label>
                                    <input type="text" name="bank_name" id="bank_name" class="form-control"
                                           placeholder="e.g. Bank Central Asia"
                                           value="<?php echo $this->mdl_employees->form_value('bank_name', true); ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label for="bank_account_number"><?php _trans('account_number'); ?></label>
                                    <input type="text" name="bank_account_number" id="bank_account_number" class="form-control"
                                           value="<?php echo $this->mdl_employees->form_value('bank_account_number', true); ?>">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <label for="bank_account_holder"><?php _trans('bank_account_holder'); ?></label>
                                    <input type="text" name="bank_account_holder" id="bank_account_holder" class="form-control"
                                           value="<?php echo $this->mdl_employees->form_value('bank_account_holder', true); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <div class="form-group">
                                    <label for="tax_id"><?php _trans('tax_id'); ?></label>
                                    <input type="text" name="tax_id" id="tax_id" class="form-control"
                                           value="<?php echo $this->mdl_employees->form_value('tax_id', true); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="notes"><?php _trans('notes'); ?></label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3"><?php echo $this->mdl_employees->form_value('notes', true); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

</form>
