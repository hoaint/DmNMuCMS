<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits">Credits Settings</a>
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
            $args[0] = 'credits';
    ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Credits Settings</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="">
                    <label class="control-label" for="switch_server_file">Server</label>

                    <div class="controls">
                        <select name="switch_server_file" id="switch_server_file" onchange="this.form.submit()">
                            <?php foreach($servers as $key => $server): ?>
                                <option value="<?php echo $key; ?>"
                                        <?php if($key == $default){ ?>selected="selected"<?php } ?>><?php echo $servers[$key]['title']; ?></option>
                            <?php endforeach; ?>
                        </select>

                        <p class="help-block">Select server for which your editing config. Currently
                            Selected: <?php echo $servers[$default]['title']; ?></p>
                    </div>
                </form>
                <form class="form-horizontal" method="POST" action="">
                    <fieldset>
                        <legend></legend>
                        <div class="control-group">
                            <label class="control-label" for="db_1">Credits 1 Database</label>

                            <div class="controls">
                                <select id="db_1" name="db_1">
                                    <option
                                            value="account" <?php if($this->config->val[$args[0] . '_' . $default]->db_1 == 'account'){
                                        echo 'selected="selected"';
                                    } ?>>Account DB
                                    </option>
                                    <option
                                            value="game" <?php if($this->config->val[$args[0] . '_' . $default]->db_1 == 'game'){
                                        echo 'selected="selected"';
                                    } ?>>Char DB
                                    </option>
                                    <option
                                            value="web" <?php if($this->config->val[$args[0] . '_' . $default]->db_1 == 'web'){
                                        echo 'selected="selected"';
                                    } ?>>Web DB
                                    </option>
                                </select>

                                <p class="help-block">Database where is located credits 1 table.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="table_1">Credits 1 Table </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="table_1" name="table_1"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->table_1; ?>"/>

                                <p class="help-block">Table where is located credits 1.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="account_column_1">Credits 1 Account Column </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="account_column_1" name="account_column_1"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->account_column_1; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="credits_column_1">Credits 1 Column </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="credits_column_1" name="credits_column_1"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->credits_column_1; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="title_1">Credits 1 Title </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="title_1" name="title_1"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->title_1; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="db_2">Credits 2 Database</label>

                            <div class="controls">
                                <select id="db_2" name="db_2">
                                    <option
                                            value="account" <?php if($this->config->val[$args[0] . '_' . $default]->db_2 == 'account'){
                                        echo 'selected="selected"';
                                    } ?>>Account DB
                                    </option>
                                    <option
                                            value="game" <?php if($this->config->val[$args[0] . '_' . $default]->db_2 == 'game'){
                                        echo 'selected="selected"';
                                    } ?>>Char DB
                                    </option>
                                    <option
                                            value="web" <?php if($this->config->val[$args[0] . '_' . $default]->db_2 == 'web'){
                                        echo 'selected="selected"';
                                    } ?>>Web DB
                                    </option>
                                </select>

                                <p class="help-block">Database where is located credits 2 table.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="table_2">Credits 2 Table </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="table_2" name="table_2"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->table_2; ?>"/>

                                <p class="help-block">Table where is located credits 2.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="account_column_2">Credits 2 Account Column </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="account_column_2" name="account_column_2"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->account_column_2; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="credits_column_2">Credits 2 Column </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="credits_column_2" name="credits_column_2"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->credits_column_2; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="title_2">Credits 2 Title </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="title_2" name="title_2"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->title_2; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="db_3">Credits 3 Database</label>

                            <div class="controls">
                                <select id="db_3" name="db_3">
                                    <option
                                            value="account" <?php if($this->config->val[$args[0] . '_' . $default]->db_3 == 'account'){
                                        echo 'selected="selected"';
                                    } ?>>Account DB
                                    </option>
                                    <option
                                            value="game" <?php if($this->config->val[$args[0] . '_' . $default]->db_3 == 'game'){
                                        echo 'selected="selected"';
                                    } ?>>Char DB
                                    </option>
                                    <option
                                            value="web" <?php if($this->config->val[$args[0] . '_' . $default]->db_3 == 'web'){
                                        echo 'selected="selected"';
                                    } ?>>Web DB
                                    </option>
                                </select>

                                <p class="help-block">Database where is located credits 3 table.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="table_3">Credits 3 Table </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="table_3" name="table_3"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->table_3; ?>"/>

                                <p class="help-block">Table where is located credits 3.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="account_column_3">Credits 3 Account Column </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="account_column_3" name="account_column_3"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->account_column_3; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="credits_column_3">Credits 3 Column </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="credits_column_3" name="credits_column_3"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->credits_column_3; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="title_3">Credits 3 Title </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="title_3" name="title_3"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->title_3; ?>"/>
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