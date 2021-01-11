<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/market">Market Settings</a></li>
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
            $args[0] = 'market';
    ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Market Settings</h2>
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

                                <p class="help-block">Market Module Status.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="items_per_page">Items Per Page </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="items_per_page" name="items_per_page"
                                       value="<?php echo $this->config->val[$args[0]]->items_per_page; ?>"/>

                                <p class="help-block">Market items in one page.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="price_limit_credits">Credits 1 Limit </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="price_limit_credits"
                                       name="price_limit_credits"
                                       value="<?php echo $this->config->val[$args[0]]->price_limit_credits; ?>"/>

                                <p class="help-block">Max price in Credits 1.</p>

                                <p>For credits types check your credits settings <a
                                            href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                            target="_blank">here</a></p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="price_limit_gcredits">Credits 2 Limit </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="price_limit_gcredits"
                                       name="price_limit_gcredits"
                                       value="<?php echo $this->config->val[$args[0]]->price_limit_gcredits; ?>"/>

                                <p class="help-block">Max price in Credits 2.</p>

                                <p>For credits types check your credits settings <a
                                            href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                            target="_blank">here</a></p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="price_limit_zen">Credits 3 Limit </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="price_limit_zen" name="price_limit_zen"
                                       value="<?php echo $this->config->val[$args[0]]->price_limit_zen; ?>"/>

                                <p class="help-block">Max price in Credits 3.</p>

                                <p>For credits types check your credits settings <a
                                            href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                            target="_blank">here</a></p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="price_limit_jewels">Jewels Limit </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="price_limit_jewels"
                                       name="price_limit_jewels"
                                       value="<?php echo $this->config->val[$args[0]]->price_limit_jewels; ?>"/>

                                <p class="help-block">Max amount of jewels for price.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="price_highlight">Highlight Price </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="price_highlight" name="price_highlight"
                                       value="<?php echo $this->config->val[$args[0]]->price_highlight; ?>"/>

                                <p class="help-block">Item highlight price Credits 1.</p>

                                <p>For credits types check your credits settings <a
                                            href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                            target="_blank">here</a></p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="sell_tax">Tax </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="sell_tax" name="sell_tax"
                                       value="<?php echo $this->config->val[$args[0]]->sell_tax; ?>"/>

                                <p class="help-block">Item selling tax in percents.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="sell_item_limit">Limit Items </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="sell_item_limit" name="sell_item_limit"
                                       value="<?php echo $this->config->val[$args[0]]->sell_item_limit; ?>"/>

                                <p class="help-block">How many items user can sell per day.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_sell_shop_items">Allow sell shop items </label>

                            <div class="controls">
                                <select id="allow_sell_shop_items" name="allow_sell_shop_items">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0]]->allow_sell_shop_items == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0]]->allow_sell_shop_items == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow selling of items which where purchased in webshop.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_remove_only_when_expired">Item Restore
                                Limit </label>

                            <div class="controls">
                                <select id="allow_remove_only_when_expired" name="allow_remove_only_when_expired">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0]]->allow_remove_only_when_expired == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0]]->allow_remove_only_when_expired == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow restoring item from market only when it expires.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="max_exe">Item Max Exe </label>

                            <div class="controls">
                                <select id="max_exe" name="max_exe">
                                    <?php for($i = 0; $i <= 9; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php if($this->config->val[$args[0]]->max_exe == $i){
                                            echo 'selected="selected"';
                                        } ?>><?php echo $i; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>

                                <p class="help-block">Item maximum excellent option count</p>
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