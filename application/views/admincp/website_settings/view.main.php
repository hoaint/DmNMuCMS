<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings">Website Settings</a></li>
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
            $args[0] = 'main';
    ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Main Settings</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="">
                    <fieldset>
                        <legend>Main Website Configuration</legend>
                        <div class="control-group">
                            <label class="control-label" for="servername">Server Name </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="servername" name="servername"
                                       value="<?php echo $this->config->val[$args[0]]->servername; ?>"/>

                                <p class="help-block">Your server name.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="site_url">Website Url </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="site_url" name="site_url"
                                       value="<?php echo $this->config->val[$args[0]]->site_url; ?>"/>

                                <p class="help-block">Your website url.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="forum_url">Forum Url </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="forum_url" name="forum_url"
                                       value="<?php echo $this->config->val[$args[0]]->forum_url; ?>"/>

                                <p class="help-block">Your forum url.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="con_check">Check ConnectStat </label>

                            <div class="controls">
                                <select id="con_check" name="con_check">
                                    <option value="0" <?php if($this->config->val[$args[0]]->con_check == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option value="1" <?php if($this->config->val[$args[0]]->con_check == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Check account connectstat before any website action.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="template">Template </label>

                            <div class="controls">
                                <select id="template" name="template">
                                    <?php
                                        $templates = [];
                                        $dir = scandir(APP_PATH . DS . 'views');
                                        foreach($dir as $folders){
                                            if(is_dir(APP_PATH . DS . 'views' . DS . $folders)){
                                                if($folders != 'admincp' && $folders != 'errors' && $folders != 'gmcp'){
                                                    if(!preg_match('/[_|.|..]$/', $folders)){
                                                        $templates[] = [$folders];
                                                    }
                                                }
                                            }
                                        }
                                        foreach($templates as $key => $template){
                                            $selected = ($templates[$key][0] == $this->config->val[$args[0]]->template) ? 'selected="selected"' : '';
                                            echo '<option value="' . $templates[$key][0] . '" ' . $selected . '>' . $templates[$key][0] . '</option>';
                                        }
                                    ?>
                                </select>

                                <p class="help-block">Website template.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="timezone">Website TimeZone </label>

                            <div class="controls">
                                <select id="timezone" name="timezone">
                                    <?php
                                        $timezones = $this->website->timezone_list();
                                        foreach($timezones as $key => $zone){
                                            $selected = ($key == $this->config->val[$args[0]]->timezone) ? 'selected="selected"' : '';
                                            echo '<option value="' . $key . '" ' . $selected . '>' . $zone . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="cache_type">Cache Type </label>

                            <div class="controls">
                                <select id="cache_type" name="cache_type">
                                    <option value="file" <?php if($this->config->val[$args[0]]->cache_type == 'file'){
                                        echo 'selected="selected"';
                                    } ?>>Txt file
                                    </option>
                                    <option
                                            value="memcached" <?php if($this->config->val[$args[0]]->cache_type == 'memcached'){
                                        echo 'selected="selected"';
                                    } ?>>Memcached
                                    </option>
                                </select>

                                <p class="help-block">Website cache storage type. Memcached requires memcached
                                    server.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="mem_cached_ip">Memcached Server IP </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="mem_cached_ip" name="mem_cached_ip"
                                       value="<?php echo $this->config->val[$args[0]]->mem_cached_ip; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="mem_cached_port">Memcached Server Port </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="mem_cached_port" name="mem_cached_port"
                                       value="<?php echo $this->config->val[$args[0]]->mem_cached_port; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="grand_open_timer">Grand Opening Count Down </label>

                            <div class="controls">
                                <input type="text" class="span6 datetimepicker" id="grand_open_timer"
                                       name="grand_open_timer"
                                       value="<?php echo $this->config->val[$args[0]]->grand_open_timer; ?>"/>

                                <p class="help-block">Show grand opening count down.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="grand_open_timer_text">Grand Opening Title </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="grand_open_timer_text"
                                       name="grand_open_timer_text"
                                       value="<?php echo $this->config->val[$args[0]]->grand_open_timer_text; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="maintenance">Maintenance Mode </label>

                            <div class="controls">
                                <select id="maintenance" name="maintenance">
                                    <option value="0" <?php if($this->config->val[$args[0]]->maintenance == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option value="1" <?php if($this->config->val[$args[0]]->maintenance == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Turn website online / offline.</p>
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