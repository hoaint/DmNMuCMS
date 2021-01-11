<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/account">Account Settings</a>
            </li>
        </ul>
    </div>
    <?php
        if(isset($error)){
            echo '<div class="alert alert-error span12">' . $error . '</div>';
        }
        if(isset($success)){
            echo '<div class="alert alert-success span12">' . $success . '</div>';
        }
        $args = $this->request->get_args();
        if(empty($args[0]))
            $args[0] = 'account';
    ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Account Settings</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="">
                    <fieldset>
                        <legend></legend>
                        <div class="control-group">
                            <label class="control-label" for="account_logs_per_page">Logs </label>

                            <div class="controls">
                                <select id="account_logs_per_page" name="account_logs_per_page">
                                    <?php for($i = 0; $i <= 100; $i++): ?>
                                        <option
                                                value="<?php echo $i; ?>" <?php if($this->config->val[$args[0]]->account_logs_per_page == $i){
                                            echo 'selected="selected"';
                                        } ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>

                                <p class="help-block">Account logs per page.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="hide_char_enabled">Hide char </label>

                            <div class="controls">
                                <select id="hide_char_enabled" name="hide_char_enabled">
                                    <option value="0" <?php if($this->config->val[$args[0]]->hide_char_enabled == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option value="1" <?php if($this->config->val[$args[0]]->hide_char_enabled == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow hidding character info.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="hide_char_days">Days </label>

                            <div class="controls">
                                <select id="hide_char_days" name="hide_char_days">
                                    <?php for($i = 0; $i <= 100; $i++): ?>
                                        <option
                                                value="<?php echo $i; ?>" <?php if($this->config->val[$args[0]]->hide_char_days == $i){
                                            echo 'selected="selected"';
                                        } ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>

                                <p class="help-block">How long time hide will stay.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="hide_char_price">Hide price </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="hide_char_price" name="hide_char_price"
                                       value="<?php echo $this->config->val[$args[0]]->hide_char_price; ?>"/>

                                <p class="help-block">How much character hide will cost.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="hide_char_price_type">Payment type</label>

                            <div class="controls">
                                <select id="hide_char_price_type" name="hide_char_price_type">
                                    <option
                                            value="1" <?php if($this->config->val[$args[0]]->hide_char_price_type == 1){
                                        echo 'selected="selected"';
                                    } ?>>Credits 1
                                    </option>
                                    <option
                                            value="2" <?php if($this->config->val[$args[0]]->hide_char_price_type == 2){
                                        echo 'selected="selected"';
                                    } ?>>Credits 2
                                    </option>
                                </select>

                                <p class="help-block">Character hide payment type.</p>

                                <p>For credits types check your credits settings <a
                                            href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                            target="_blank">here</a></p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="max_char_zen">Max Char Zen</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="max_char_zen" name="max_char_zen"
                                       value="<?php echo $this->config->val[$args[0]]->max_char_zen; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="max_ware_zen">Max Vault Zen</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="max_ware_zen" name="max_ware_zen"
                                       value="<?php echo $this->config->val[$args[0]]->max_ware_zen; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="online_trade_reward">Online Hours Reward</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="online_trade_reward"
                                       name="online_trade_reward"
                                       value="<?php echo $this->config->val[$args[0]]->online_trade_reward; ?>"/>

                                <p class="help-block">How much reward user will receive per one online hour.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="online_trade_reward_type">Online Hours Reward Type</label>

                            <div class="controls">
                                <select id="igcn_vip_price_type" name="online_trade_reward_type">
                                    <option
                                            value="1" <?php if($this->config->val[$args[0]]->online_trade_reward_type == 1){
                                        echo 'selected="selected"';
                                    } ?>>Credits 1
                                    </option>
                                    <option
                                            value="2" <?php if($this->config->val[$args[0]]->online_trade_reward_type == 2){
                                        echo 'selected="selected"';
                                    } ?>>Credits 2
                                    </option>
                                    <option
                                            value="3" <?php if($this->config->val[$args[0]]->online_trade_reward_type == 3){
                                        echo 'selected="selected"';
                                    } ?>>Credits 3
                                    </option>
                                </select>

                                <p class="help-block">For credits types check your credits settings <a
                                            href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                            target="_blank">here</a></p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_mail_change">Allow Change Email</label>

                            <div class="controls">
                                <select id="allow_recover_masterkey" name="allow_mail_change">
                                    <option value="0" <?php if($this->config->val[$args[0]]->allow_mail_change == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option value="1" <?php if($this->config->val[$args[0]]->allow_mail_change == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow change email of account.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_recover_masterkey">Master Key Recovery</label>

                            <div class="controls">
                                <select id="allow_recover_masterkey" name="allow_recover_masterkey">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0]]->allow_recover_masterkey == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0]]->allow_recover_masterkey == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow master key recovery. Supported by MuEngine server files.</p>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" name="edit_config">Save changes</button>
                            <button type="reset" class="btn">Cancel</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>