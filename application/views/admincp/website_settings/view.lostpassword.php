<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/lostpassword">Lost Password
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
                <h2><i class="icon-edit"></i> Lost Password Settings</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="" id="lostpassword_settings_form">
                    <fieldset>
                        <legend></legend>
                        <div class="control-group">
                            <label class="control-label" for="active">Module Status </label>
                            <div class="controls">
                                <select id="active" name="active">
                                    <option value="0" <?php if(isset($lostpassword_config['active']) && $lostpassword_config['active'] == 0){
                                        echo 'selected="selected"';
                                    } ?>>Disabled
                                    </option>
                                    <option value="1" <?php if(isset($lostpassword_config['active']) && $lostpassword_config['active'] == 1){
                                        echo 'selected="selected"';
                                    } ?>>Enabled
                                    </option>
                                </select>
                                <p class="help-block">Lost Password Module Status.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="method">Method </label>
                            <div class="controls">
                                <select id="method" name="method">
                                    <option value="1" <?php if(isset($lostpassword_config['method']) && $lostpassword_config['method'] == 1){
                                        echo 'selected="selected"';
                                    } ?>>Email
                                    </option>
                                    <option value="2" <?php if(isset($lostpassword_config['method']) && $lostpassword_config['method'] == 2){
                                        echo 'selected="selected"';
                                    } ?>>Secret Q & A
                                    </option>
                                </select>
                                <p class="help-block">What method will be used to retrieve password.</p>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" name="edit_config"
                                    id="edit_lostpassword_settings">Save changes
                            </button>
                            <button type="reset" class="btn">Cancel</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>