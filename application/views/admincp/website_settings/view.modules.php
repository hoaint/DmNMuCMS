<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/modules">Modules Settings</a>
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
            $args[0] = 'modules';
    ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Modules Settings</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="">
                    <fieldset>
                        <legend></legend>
                        <div class="control-group">
                            <label class="control-label" for="recent_forum_module">Recent On Forum </label>

                            <div class="controls">
                                <select id="recent_forum_module" name="recent_forum_module">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0]]->recent_forum_module == 0){
                                        echo 'selected="selected"';
                                    } ?>>Disabled
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0]]->recent_forum_module == 1){
                                        echo 'selected="selected"';
                                    } ?>>Enabled
                                    </option>
                                </select>

                                <p class="help-block">Show recent forum topics in sidebar.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="recent_forum_rss_url">Url </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="recent_forum_rss_url"
                                       name="recent_forum_rss_url"
                                       value="<?php echo $this->config->val[$args[0]]->recent_forum_rss_url; ?>"/>

                                <p class="help-block">Recent forum topics rss url.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="recent_forum_rss_count">Topic Count </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="recent_forum_rss_count"
                                       name="recent_forum_rss_count"
                                       value="<?php echo $this->config->val[$args[0]]->recent_forum_rss_count; ?>"/>

                                <p class="help-block">Recent forum topics rss item count.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="recent_forum_rss_cache_time">Cache </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="recent_forum_rss_cache_time"
                                       name="recent_forum_rss_cache_time"
                                       value="<?php echo $this->config->val[$args[0]]->recent_forum_rss_cache_time; ?>"/>

                                <p class="help-block">Recent forum topics cache time.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="last_market_items_module">Last Items </label>

                            <div class="controls">
                                <select id="last_market_items_module" name="last_market_items_module">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0]]->last_market_items_module == 0){
                                        echo 'selected="selected"';
                                    } ?>>Disabled
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0]]->last_market_items_module == 1){
                                        echo 'selected="selected"';
                                    } ?>>Enabled
                                    </option>
                                </select>

                                <p class="help-block">Show lattest market items in sidebar.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="last_market_items_count">Item Count </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="last_market_items_count"
                                       name="last_market_items_count"
                                       value="<?php echo $this->config->val[$args[0]]->last_market_items_count; ?>"/>

                                <p class="help-block">How many market items will load.</p>
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