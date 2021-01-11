<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/warehouse">Warehouse Settings</a>
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
            $args[0] = 'warehouse';
    ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Warehouse Settings</h2>
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

                                <p class="help-block">Warehouse Module Status.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_sell_for_credits">Allow Sell For Credits 1 </label>

                            <div class="controls">
                                <select id="allow_sell_for_credits" name="allow_sell_for_credits">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0]]->allow_sell_for_credits == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0]]->allow_sell_for_credits == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow selling items in market for Credits 1.</p>

                                <p>For credits types check your credits settings <a
                                            href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                            target="_blank">here</a></p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_sell_for_gcredits">Allow Sell For Credits 2 </label>

                            <div class="controls">
                                <select id="allow_sell_for_gcredits" name="allow_sell_for_gcredits">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0]]->allow_sell_for_gcredits == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0]]->allow_sell_for_gcredits == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow selling items in market for Credits 2.</p>

                                <p>For credits types check your credits settings <a
                                            href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                            target="_blank">here</a></p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_sell_for_zen">Allow Sell For Credits 3 </label>

                            <div class="controls">
                                <select id="allow_sell_for_zen" name="allow_sell_for_zen">
                                    <option value="0" <?php if($this->config->val[$args[0]]->allow_sell_for_zen == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option value="1" <?php if($this->config->val[$args[0]]->allow_sell_for_zen == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow selling items in market for Credits 3.</p>

                                <p>For credits types check your credits settings <a
                                            href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                            target="_blank">here</a></p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_sell_for_chaos">Allow Sell For Jewel of
                                Chaos </label>

                            <div class="controls">
                                <select id="allow_sell_for_zen" name="allow_sell_for_chaos">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0]]->allow_sell_for_chaos == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0]]->allow_sell_for_chaos == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow selling items in market for Jewel of chaos.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_sell_for_bless">Allow Sell For Jewel of
                                Bless </label>

                            <div class="controls">
                                <select id="allow_sell_for_zen" name="allow_sell_for_bless">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0]]->allow_sell_for_bless == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0]]->allow_sell_for_bless == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow selling items in market for Jewel of Bless.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_sell_for_soul">Allow Sell For Jewel of Soul </label>

                            <div class="controls">
                                <select id="allow_sell_for_zen" name="allow_sell_for_soul">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0]]->allow_sell_for_soul == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0]]->allow_sell_for_soul == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow selling items in market for Jewel of Soul.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_sell_for_life">Allow Sell For Jewel of Life </label>

                            <div class="controls">
                                <select id="allow_sell_for_zen" name="allow_sell_for_life">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0]]->allow_sell_for_life == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0]]->allow_sell_for_life == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow selling items in market for Jewel of Life.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_sell_for_creation">Allow Sell For Jewel of
                                Creation </label>

                            <div class="controls">
                                <select id="allow_sell_for_zen" name="allow_sell_for_creation">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0]]->allow_sell_for_creation == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0]]->allow_sell_for_creation == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow selling items in market for Jewel of Creation.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_sell_for_harmony">Allow Sell For Jewel of
                                Harmony </label>

                            <div class="controls">
                                <select id="allow_sell_for_zen" name="allow_sell_for_harmony">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0]]->allow_sell_for_harmony == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0]]->allow_sell_for_harmony == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow selling items in market for Jewel of Harmony.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_delete_item">Allow Delete Item </label>

                            <div class="controls">
                                <select id="allow_delete_item" name="allow_delete_item">
                                    <option value="0" <?php if($this->config->val[$args[0]]->allow_delete_item == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option value="1" <?php if($this->config->val[$args[0]]->allow_delete_item == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_item_upgrade">Allow Item Upgrade </label>

                            <div class="controls">
                                <select id="allow_item_upgrade" name="allow_item_upgrade">
                                    <option value="0" <?php if($this->config->val[$args[0]]->allow_item_upgrade == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option value="1" <?php if($this->config->val[$args[0]]->allow_item_upgrade == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_move_to_web_warehouse">Allow Move To Web </label>

                            <div class="controls">
                                <select id="allow_move_to_web_warehouse" name="allow_move_to_web_warehouse">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0]]->allow_move_to_web_warehouse == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0]]->allow_move_to_web_warehouse == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow moving items to web warehouse.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="web_wh_item_expires_after">Web Wh Item Expiry Time</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="web_wh_item_expires_after"
                                       name="web_wh_item_expires_after"
                                       value="<?php echo $this->config->val[$args[0]]->web_wh_item_expires_after; ?>"/>

                                <p class="help-block">After how long time item in web warehouse expire.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="web_items_per_page">Items Per Page </label>

                            <div class="controls">
                                <select id="web_items_per_page" name="web_items_per_page">
                                    <?php for($i = 1; $i <= 100; $i++){ ?>
                                        <option
                                                value="<?php echo $i; ?>" <?php if($this->config->val[$args[0]]->web_items_per_page == $i){
                                            echo 'selected="selected"';
                                        } ?>><?php echo $i; ?></option>
                                    <?php } ?>
                                </select>

                                <p class="help-block">How many items per page show in web warehouse.</p>
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