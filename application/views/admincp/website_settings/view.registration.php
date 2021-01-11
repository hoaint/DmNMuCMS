<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/registration">Registration
                    Settings</a></li>
        </ul>
    </div>
    <?php
        if(isset($error)){
            echo '<div class="alert alert-error span12">' . $error . '</div>';
        }
        if(isset($success)){
            echo '<div class="alert alert-success span12">' . $success . '</div>';
        }
    ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Registration Settings</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="" id="registration_settings_form">
                    <fieldset>
                        <legend></legend>
                        <div class="control-group">
                            <label class="control-label" for="active">Module Status </label>

                            <div class="controls">
                                <select id="active" name="active">
                                    <option value="0" <?php if(isset($registration_config['active']) && $registration_config['active'] == 0){
                                        echo 'selected="selected"';
                                    } ?>>Disabled
                                    </option>
                                    <option value="1" <?php if(isset($registration_config['active']) && $registration_config['active'] == 1){
                                        echo 'selected="selected"';
                                    } ?>>Enabled
                                    </option>
                                </select>

                                <p class="help-block">Registration Module Status.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="req_email">Reg Email </label>
                            <div class="controls">
                                <select id="req_email" name="req_email">
                                    <option value="0" <?php if(isset($registration_config['req_email']) && $registration_config['req_email'] == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option value="1" <?php if(isset($registration_config['req_email']) && $registration_config['req_email'] == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>
                                <p class="help-block">Is email required on registration.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="req_secret">Reg Secret </label>
                            <div class="controls">
                                <select id="req_secret" name="req_secret">
                                    <option value="0" <?php if(isset($registration_config['req_secret']) && $registration_config['req_secret'] == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option value="1" <?php if(isset($registration_config['req_secret']) && $registration_config['req_secret'] == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>
                                <p class="help-block">Is secret question & answer required on registration.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="min_username">Min Username </label>
                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="min_username" name="min_username"
                                       value="<?php echo (isset($registration_config['min_username'])) ? $registration_config['min_username'] : ''; ?>"
                                       placeholder="4"/>
                                <p class="help-block">Minimum username length.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="max_username">Max Username </label>
                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="max_username" name="max_username"
                                       value="<?php echo (isset($registration_config['max_username'])) ? $registration_config['max_username'] : ''; ?>"
                                       placeholder="10"/>
                                <p class="help-block">Maximum username length.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="min_password">Min Password </label>
                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="min_password" name="min_password"
                                       value="<?php echo (isset($registration_config['min_password'])) ? $registration_config['min_password'] : ''; ?>"
                                       placeholder="4"/>
                                <p class="help-block">Minimum password length.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="max_password">Max Password </label>
                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="max_password" name="max_password"
                                       value="<?php echo (isset($registration_config['max_password'])) ? $registration_config['max_password'] : ''; ?>"
                                       placeholder="10"/>
                                <p class="help-block">Maximum password length.</p>
                            </div>
                        </div>
                        <div class="box-header well">
                            <h4><i class="icon-pencil"></i> Password Strength</h4>
                        </div>
                        <br/>
                        <div class="control-group">
                            <label class="control-label" for="atleast_one_lowercase">Req LowerCase </label>
                            <div class="controls">
                                <select id="atleast_one_lowercase" name="atleast_one_lowercase">
                                    <option value="0" <?php if(isset($registration_config['password_strength']['atleast_one_lowercase']) && $registration_config['password_strength']['atleast_one_lowercase'] == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option value="1" <?php if(isset($registration_config['password_strength']['atleast_one_lowercase']) && $registration_config['password_strength']['atleast_one_lowercase'] == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>
                                <p class="help-block">Password should contain at least one lowercase letter.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="atleast_one_uppercase">Req UpperCase </label>
                            <div class="controls">
                                <select id="atleast_one_uppercase" name="atleast_one_uppercase">
                                    <option value="0" <?php if(isset($registration_config['password_strength']['atleast_one_uppercase']) && $registration_config['password_strength']['atleast_one_uppercase'] == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option value="1" <?php if(isset($registration_config['password_strength']['atleast_one_uppercase']) && $registration_config['password_strength']['atleast_one_uppercase'] == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>
                                <p class="help-block">Password should contain at least one uppercase letter.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="atleast_one_number">Req Number </label>
                            <div class="controls">
                                <select id="atleast_one_number" name="atleast_one_number">
                                    <option value="0" <?php if(isset($registration_config['password_strength']['atleast_one_number']) && $registration_config['password_strength']['atleast_one_number'] == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option value="1" <?php if(isset($registration_config['password_strength']['atleast_one_number']) && $registration_config['password_strength']['atleast_one_number'] == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>
                                <p class="help-block">Password should contain at least one number.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="atleast_one_symbol">Req Symbol </label>
                            <div class="controls">
                                <select id="atleast_one_symbol" name="atleast_one_symbol">
                                    <option value="0" <?php if(isset($registration_config['password_strength']['atleast_one_symbol']) && $registration_config['password_strength']['atleast_one_symbol'] == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option value="1" <?php if(isset($registration_config['password_strength']['atleast_one_symbol']) && $registration_config['password_strength']['atleast_one_symbol'] == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>
                                <p class="help-block">Password should contain at least one symbol.</p>
                            </div>
                        </div>
                        <div class="box-header well">
                            <h4><i class="icon-pencil"></i> Validation</h4>
                        </div>
                        <br/>
                        <div class="control-group">
                            <label class="control-label" for="email_validation">Email Validation </label>
                            <div class="controls">
                                <select id="email_validation" name="email_validation">
                                    <option value="0" <?php if(isset($registration_config['email_validation']) && $registration_config['email_validation'] == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option value="1" <?php if(isset($registration_config['email_validation']) && $registration_config['email_validation'] == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>
                                <p class="help-block">Validate user by email. Requires configured <a
                                            href="<?php echo $this->config->base_url; ?>admincp/manage-settings/email"
                                            target="_blank">email settings</a>.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="generate_password">Generate Password </label>
                            <div class="controls">
                                <select id="generate_password" name="generate_password">
                                    <option value="0" <?php if(isset($registration_config['generate_password']) && $registration_config['generate_password'] == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option value="1" <?php if(isset($registration_config['generate_password']) && $registration_config['generate_password'] == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>
                                <p class="help-block">Generate password automatically and send to email.</p>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" name="edit_registration_settings"
                                    id="edit_registration_settings">Save changes
                            </button>
                            <button type="reset" class="btn">Cancel</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>