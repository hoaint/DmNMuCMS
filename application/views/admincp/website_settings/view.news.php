<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/news">News Settings</a></li>
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
            $args[0] = 'news';
    ?>
    <script>
        $(document).ready(function () {
            App.checkNewsStorage();
        });
    </script>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> News Settings</h2>
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

                                <p class="help-block">News Module Status.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="storage">News Storage </label>

                            <div class="controls">
                                <select id="storage" name="storage">
                                    <option value="dmn" <?php if($this->config->val[$args[0]]->storage == 'dmn'){
                                        echo 'selected="selected"';
                                    } ?>>Website
                                    </option>
                                    <option value="ipb" <?php if($this->config->val[$args[0]]->storage == 'ipb'){
                                        echo 'selected="selected"';
                                    } ?>>Invision Power Board
                                    </option>
                                    <option value="ipb4" <?php if($this->config->val[$args[0]]->storage == 'ipb4'){
                                        echo 'selected="selected"';
                                    } ?>>Invision Power Board 4
                                    </option>
                                    <option value="rss" <?php if($this->config->val[$args[0]]->storage == 'rss'){
                                        echo 'selected="selected"';
                                    } ?>>Rss Feed
                                    </option>
                                    <option value="facebook" <?php if($this->config->val[$args[0]]->storage == 'facebook'){
                                        echo 'selected="selected"';
                                    } ?>>Facebook
                                    </option>
                                </select>

                                <p class="help-block">News storage type.</p>
                            </div>
                        </div>
                        <div class="control-group" id="per_page">
                            <label class="control-label" for="news_per_page">News Per Page </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="news_per_page" name="news_per_page"
                                       value="<?php echo $this->config->val[$args[0]]->news_per_page; ?>"/>

                                <p class="help-block">How many news will show in one page.</p>
                            </div>
                        </div>
                        <div id="ipb_settings" style="display:none;">
                            <div class="control-group">
                                <label class="control-label" for="ipb_host">Host </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="ipb_host" name="ipb_host"
                                           value="<?php echo $this->config->val[$args[0]]->ipb_host; ?>"/>

                                    <p class="help-block">Invision Power Board DB Host.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="ipb_user">User </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="ipb_user" name="ipb_user"
                                           value="<?php echo $this->config->val[$args[0]]->ipb_user; ?>"/>

                                    <p class="help-block">Invision Power Board DB User.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="ipb_pass">Password </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="ipb_pass" name="ipb_pass"
                                           value="<?php echo $this->config->val[$args[0]]->ipb_pass; ?>"/>

                                    <p class="help-block">Invision Power Board DB Password.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="ipb_db">Database </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="ipb_db" name="ipb_db"
                                           value="<?php echo $this->config->val[$args[0]]->ipb_db; ?>"/>

                                    <p class="help-block">Invision Power Board DB Database.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="ipb_tb_prefix">Table Prefix </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="ipb_tb_prefix" name="ipb_tb_prefix"
                                           value="<?php echo $this->config->val[$args[0]]->ipb_tb_prefix; ?>"/>

                                    <p class="help-block">Invision Power Board DB Rable Prefix.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="ipb_forum_ids">Forum Ids </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="ipb_forum_ids" name="ipb_forum_ids"
                                           value="<?php echo $this->config->val[$args[0]]->ipb_forum_ids; ?>"/>

                                    <p class="help-block">Invision Power Board Forum Ids. Form Where To Load News.
                                        Seperated
                                        By Comma</p>
                                </div>
                            </div>
                        </div>
                        <div id="rss_settings" style="display:none;">
                            <div class="control-group">
                                <label class="control-label" for="rss_feed_url">Rss Feed Url </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="rss_feed_url" name="rss_feed_url"
                                           value="<?php echo $this->config->val[$args[0]]->rss_feed_url; ?>"/>

                                    <p class="help-block">Rss Feed Url. If News Storage Set As Rss Feed.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="rss_feed_count">Rss Feed Count </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="rss_feed_count" name="rss_feed_count"
                                           value="<?php echo $this->config->val[$args[0]]->rss_feed_count; ?>"/>

                                    <p class="help-block">Rss Feed Count. If News Storage Set As Rss Feed.</p>
                                </div>
                            </div>
                        </div>
                        <div class="control-group" id="news_cache">
                            <label class="control-label" for="cache_time">Cache Time </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="cache_time" name="cache_time"
                                       value="<?php echo $this->config->val[$args[0]]->cache_time; ?>"/>

                                <p class="help-block">News Cache Time.</p>
                            </div>
                        </div>
                        <div id="fb_settings" style="display:none;">
                            <div class="control-group">
                                <label class="control-label" for="fb_script">FB Script </label>
                                <div class="controls">
                                    <textarea class="form-control span6" id="fb_script" name="fb_script"
                                              rows="8"><?php echo $this->config->val[$args[0]]->fb_script; ?></textarea>
                                    <p class="help-block">Facebook page plugin script</p>
                                    <p><a href="https://developers.facebook.com/docs/plugins/page-plugin"
                                          target="_blank">https://developers.facebook.com/docs/plugins/page-plugin</a>
                                    </p>
                                </div>
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