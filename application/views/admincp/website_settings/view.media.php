<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/media">Media Settings</a></li>
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
            $args[0] = 'lostpassword';
    ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Media Settings</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="">
                    <fieldset>
                        <legend></legend>
                        <div class="control-group">
                            <label class="control-label" for="module_status">Module Status </label>

                            <div class="controls">
                                <select id="module_status" name="module_status">
                                    <option value="0" <?php if($this->config->val[$args[0]]->module_status == 0){
                                        echo 'selected="selected"';
                                    } ?>>Disabled
                                    </option>
                                    <option value="1" <?php if($this->config->val[$args[0]]->module_status == 1){
                                        echo 'selected="selected"';
                                    } ?>>Enabled
                                    </option>
                                </select>

                                <p class="help-block">Media Module Status.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="images_per_page">Images per page </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="images_per_page" name="images_per_page"
                                       value="<?php echo $this->config->val[$args[0]]->images_per_page; ?>"/>

                                <p class="help-block">How many images will show in one page.</p>
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
</div>