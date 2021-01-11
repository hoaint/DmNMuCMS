<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/tables">SQL Table Settings</a>
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
    ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Pre Defined Template</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="" id="pre_defined_template_form">
                    <div class="control-group">
                        <label class="control-label" for="template">Template </label>
                        <div class="controls">
                            <select id="team_template" name="team_template">
                                <option value="">Select</option>
                                <?php foreach($pre_defined_table_config AS $key => $config): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $pre_defined_table_config[$key]['title']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="help-block">Load sql information from pre defined server template.</p>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" name="load_pre_defined_template"
                                id="load_pre_defined_template">Load Template
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            App.loadTableSettings('<?php echo $default_server;?>');
        });
    </script>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> SQL Table Configuration</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="" id="sql_table_settings_form">
                    <div class="control-group">
                        <label class="control-label" for="server">Settings Server </label>
                        <div class="controls">
                            <select id="server" name="server">
                                <?php foreach($server_list as $key => $server): ?>
                                    <option value="<?php echo $key; ?>" <?php if($default_server == $key){
                                        echo 'selected="selected"';
                                    } ?>><?php echo $server['title']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="help-block">Current settings server</p>
                        </div>
                    </div>
                    <div class="box-header well">
                        <h4><i class="icon-pencil"></i> Resets</h4>
                    </div>
                    <br/>
                    <div class="control-group">
                        <label class="control-label" for="resets-db">Database <span style="color:red;">*</span></label>
                        <div class="controls">
                            <select id="resets-db" name="resets-db" required>
                                <option value="">Select</option>
                                <option value="account">Account DB</option>
                                <option value="game" selected="selected">Character DB</option>
                                <option value="web">Web DB</option>
                                <option value="custom">Custom DB</option>
                            </select>
                            <input style="display:none;" type="text" class="input-large" id="resets_custom-db"
                                   name="resets_custom-db" value=""/>
                            <p class="help-block">Database where resets column is located.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="resets-table">Table <span style="color:red;">*</span></label>
                        <div class="controls">
                            <input type="text" class="input-large" id="resets-table" name="resets-table" value=""
                                   placeholder="Character" required/>
                            <p class="help-block">Table where resets column is located.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="resets-column">Column </label>
                        <div class="controls">
                            <input type="text" class="input-large" id="resets-column" name="resets-column" value=""
                                   placeholder="resets"/>
                            <p class="help-block">Resets column name.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="resets-identifier_column">Identifier Column <span
                                    style="color:red;">*</span></label>
                        <div class="controls">
                            <input type="text" class="input-large" id="resets-identifier_column"
                                   name="resets-identifier_column" value="" placeholder="Name" required/>
                            <p class="help-block">Column name by which can identify resets, usually character name
                                column.</p>
                        </div>
                    </div>
                    <div class="box-header well">
                        <h4><i class="icon-pencil"></i> Grand Resets</h4>
                    </div>
                    <br/>
                    <div class="control-group">
                        <label class="control-label" for="grand_resets-db">Database <span
                                    style="color:red;">*</span></label>
                        <div class="controls">
                            <select id="grand_resets-db" name="grand_resets-db" required>
                                <option value="">Select</option>
                                <option value="account">Account DB</option>
                                <option value="game" selected="selected">Character DB</option>
                                <option value="web">Web DB</option>
                                <option value="custom">Custom DB</option>
                            </select>
                            <input style="display:none;" type="text" class="input-large" id="grand_resets_custom-db"
                                   name="grand_resets_custom-db" value=""/>
                            <p class="help-block">Database where grand resets column is located.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="grand_resets-table">Table <span
                                    style="color:red;">*</span></label>
                        <div class="controls">
                            <input type="text" class="input-large" id="grand_resets-table" name="grand_resets-table"
                                   value="" placeholder="Character" required/>
                            <p class="help-block">Table where grand resets column is located.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="grand_resets-column">Column </label>
                        <div class="controls">
                            <input type="text" class="input-large" id="grand_resets-column" name="grand_resets-column"
                                   value="" placeholder="grand_resets"/>
                            <p class="help-block">Grand Resets column name.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="grand_resets-identifier_column">Identifier Column <span
                                    style="color:red;">*</span></label>
                        <div class="controls">
                            <input type="text" class="input-large" id="grand_resets-identifier_column"
                                   name="grand_resets-identifier_column" value="" placeholder="Name" required/>
                            <p class="help-block">Column name by which can identify grand resets, usually character name
                                column.</p>
                        </div>
                    </div>
                    <div class="box-header well">
                        <h4><i class="icon-pencil"></i> WCoins</h4>
                    </div>
                    <br/>
                    <div class="control-group">
                        <label class="control-label" for="wcoins-db">Database <span style="color:red;">*</span></label>
                        <div class="controls">
                            <select id="wcoins-db" name="wcoins-db">
                                <option value="">Select</option>
                                <option value="account" selected="selected">Account DB</option>
                                <option value="game">Character DB</option>
                                <option value="web">Web DB</option>
                                <option value="custom">Custom DB</option>
                            </select>
                            <input style="display:none;" type="text" class="input-large" id="wcoins_custom-db"
                                   name="wcoins_custom-db" value=""/>
                            <p class="help-block">Database where wcoins column is located.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="wcoins-table">Table <span style="color:red;">*</span></label>
                        <div class="controls">
                            <input type="text" class="input-large" id="wcoins-table" name="wcoins-table" value=""
                                   placeholder="MEMB_INFO"/>
                            <p class="help-block">Table where wcoins column is located.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="wcoins-column">Column <span
                                    style="color:red;">*</span></label>
                        <div class="controls">
                            <input type="text" class="input-large" id="wcoins-column" name="wcoins-column" value=""
                                   placeholder="WCoin"/>
                            <p class="help-block">WCoins column name.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="wcoins-identifier_column">Identifier Column <span
                                    style="color:red;">*</span></label>
                        <div class="controls">
                            <input type="text" class="input-large" id="wcoins-identifier_column"
                                   name="wcoins-identifier_column" value="" placeholder="memb___id"/>
                            <p class="help-block">Column name by which can identify wcoins, usually member username
                                column.</p>
                        </div>
                    </div>
                    <div class="box-header well">
                        <h4><i class="icon-pencil"></i> Master Level</h4>
                    </div>
                    <br/>
                    <div class="control-group">
                        <label class="control-label" for="master_level-db">Database <span
                                    style="color:red;">*</span></label>
                        <div class="controls">
                            <select id="master_level-db" name="master_level-db">
                                <option value="">Select</option>
                                <option value="account">Account DB</option>
                                <option value="game" selected="selected">Character DB</option>
                                <option value="web">Web DB</option>
                                <option value="custom">Custom DB</option>
                            </select>
                            <input style="display:none;" type="text" class="input-large" id="master_level_custom-db"
                                   name="master_level_custom-db" value=""/>
                            <p class="help-block">Database where master level column is located.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="master_level-table">Table <span
                                    style="color:red;">*</span></label>
                        <div class="controls">
                            <input type="text" class="input-large" id="master_level-table" name="master_level-table"
                                   value="" placeholder="Character"/>
                            <p class="help-block">Table where master level column is located.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="master_level-column">Column <span style="color:red;">*</span></label>
                        <div class="controls">
                            <input type="text" class="input-large" id="master_level-column" name="master_level-column"
                                   value="" placeholder="MasterLevel"/>
                            <p class="help-block">Master Level column name.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="master_level-identifier_column">Identifier Column <span
                                    style="color:red;">*</span></label>
                        <div class="controls">
                            <input type="text" class="input-large" id="master_level-identifier_column"
                                   name="master_level-identifier_column" value="" placeholder="Name"/>
                            <p class="help-block">Column name by which can identify master level, usually character name
                                column.</p>
                        </div>
                    </div>
                    <div class="box-header well">
                        <h4><i class="icon-pencil"></i> Blood Castle</h4>
                    </div>
                    <br/>
                    <div class="control-group">
                        <label class="control-label" for="bc-db">Database </label>
                        <div class="controls">
                            <select id="bc-db" name="bc-db">
                                <option value="">Select</option>
                                <option value="account">Account DB</option>
                                <option value="game" selected="selected">Character DB</option>
                                <option value="web">Web DB</option>
                                <option value="custom">Custom DB</option>
                            </select>
                            <input style="display:none;" type="text" class="input-large" id="bc_custom-db"
                                   name="bc_custom-db" value=""/>
                            <p class="help-block">Database where bc ranking columns are located.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="bc-table">Table </label>
                        <div class="controls">
                            <input type="text" class="input-large" id="bc-table" name="bc-table" value=""/>
                            <p class="help-block">Table where bc rankings columns are located.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="bc-column">Column </label>
                        <div class="controls">
                            <input type="text" class="input-large" id="bc-column" name="bc-column" value=""
                                   placeholder="Score"/>
                            <p class="help-block">Score column name.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="bc-identifier_column">Identifier Column </label>
                        <div class="controls">
                            <input type="text" class="input-large" id="bc-identifier_column" name="bc-identifier_column"
                                   value="" placeholder="Name"/>
                            <p class="help-block">Column name by which can identify score, usually character name
                                column.</p>
                        </div>
                    </div>
                    <div class="box-header well">
                        <h4><i class="icon-pencil"></i> Devil Square</h4>
                    </div>
                    <br/>
                    <div class="control-group">
                        <label class="control-label" for="ds-db">Database </label>
                        <div class="controls">
                            <select id="ds-db" name="ds-db">
                                <option value="">Select</option>
                                <option value="account">Account DB</option>
                                <option value="game" selected="selected">Character DB</option>
                                <option value="web">Web DB</option>
                                <option value="custom">Custom DB</option>
                            </select>
                            <input style="display:none;" type="text" class="input-large" id="ds_custom-db"
                                   name="ds_custom-db" value=""/>
                            <p class="help-block">Database where ds ranking columns are located.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="ds-table">Table </label>
                        <div class="controls">
                            <input type="text" class="input-large" id="ds-table" name="ds-table" value=""/>
                            <p class="help-block">Table where ds rankings columns are located.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="ds-column">Column </label>
                        <div class="controls">
                            <input type="text" class="input-large" id="ds-column" name="ds-column" value=""
                                   placeholder="Score"/>
                            <p class="help-block">Score column name.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="ds-identifier_column">Identifier Column </label>
                        <div class="controls">
                            <input type="text" class="input-large" id="ds-identifier_column" name="ds-identifier_column"
                                   value="" placeholder="Name"/>
                            <p class="help-block">Column name by which can identify score, usually character name
                                column.</p>
                        </div>
                    </div>
                    <div class="box-header well">
                        <h4><i class="icon-pencil"></i> Chaos Castle</h4>
                    </div>
                    <br/>
                    <div class="control-group">
                        <label class="control-label" for="cc-db">Database </label>
                        <div class="controls">
                            <select id="cc-db" name="cc-db">
                                <option value="">Select</option>
                                <option value="account">Account DB</option>
                                <option value="game" selected="selected">Character DB</option>
                                <option value="web">Web DB</option>
                                <option value="custom">Custom DB</option>
                            </select>
                            <input style="display:none;" type="text" class="input-large" id="cc_custom-db"
                                   name="cc_custom-db" value=""/>
                            <p class="help-block">Database where cc ranking columns are located.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="cc-table">Table </label>
                        <div class="controls">
                            <input type="text" class="input-large" id="cc-table" name="cc-table" value=""/>
                            <p class="help-block">Table where cc rankings columns are located.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="cc-column">Column </label>
                        <div class="controls">
                            <input type="text" class="input-large" id="cc-column" name="cc-column" value=""
                                   placeholder="Score"/>
                            <p class="help-block">Score column name.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="cc-column2">Column 2</label>
                        <div class="controls">
                            <input type="text" class="input-large" id="cc-column2" name="cc-column2" value=""
                                   placeholder="PKillCount"/>
                            <p class="help-block">Player KillCount column name. Can leave empty if dont have.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="cc-column3">Column 3</label>
                        <div class="controls">
                            <input type="text" class="input-large" id="cc-column3" name="cc-column3" value=""
                                   placeholder="MKillCount"/>
                            <p class="help-block">Monster KillCount column name. Can leave empty if dont have.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="cc-identifier_column">Identifier Column </label>
                        <div class="controls">
                            <input type="text" class="input-large" id="cc-identifier_column" name="cc-identifier_column"
                                   value="" placeholder="Name"/>
                            <p class="help-block">Column name by which can identify score, usually character name
                                column.</p>
                        </div>
                    </div>
                    <div class="box-header well">
                        <h4><i class="icon-pencil"></i> Castle Siege</h4>
                    </div>
                    <br/>
                    <div class="control-group">
                        <label class="control-label" for="cs-db">Database </label>
                        <div class="controls">
                            <select id="cs-db" name="cs-db">
                                <option value="">Select</option>
                                <option value="account">Account DB</option>
                                <option value="game" selected="selected">Character DB</option>
                                <option value="web">Web DB</option>
                                <option value="custom">Custom DB</option>
                            </select>
                            <input style="display:none;" type="text" class="input-large" id="cs_custom-db"
                                   name="cs_custom-db" value=""/>
                            <p class="help-block">Database where cs ranking columns are located.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="cs-table">Table </label>
                        <div class="controls">
                            <input type="text" class="input-large" id="cs-table" name="cs-table" value=""/>
                            <p class="help-block">Table where cs rankings columns are located.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="cs-column">Column </label>
                        <div class="controls">
                            <input type="text" class="input-large" id="cs-column" name="cs-column" value=""
                                   placeholder="Score"/>
                            <p class="help-block">Score column name.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="cs-identifier_column">Identifier Column </label>
                        <div class="controls">
                            <input type="text" class="input-large" id="cs-identifier_column" name="cs-identifier_column"
                                   value="" placeholder="Name"/>
                            <p class="help-block">Column name by which can identify score, usually character name
                                column.</p>
                        </div>
                    </div>
                    <div class="box-header well">
                        <h4><i class="icon-pencil"></i> Duels</h4>
                    </div>
                    <br/>
                    <div class="control-group">
                        <label class="control-label" for="duels-db">Database </label>
                        <div class="controls">
                            <select id="duels-db" name="duels-db">
                                <option value="">Select</option>
                                <option value="account">Account DB</option>
                                <option value="game" selected="selected">Character DB</option>
                                <option value="web">Web DB</option>
                                <option value="custom">Custom DB</option>
                            </select>
                            <input style="display:none;" type="text" class="input-large" id="duels_custom-db"
                                   name="duels_custom-db" value=""/>
                            <p class="help-block">Database where duels ranking columns are located.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="duels-table">Table </label>
                        <div class="controls">
                            <input type="text" class="input-large" id="duels-table" name="duels-table" value=""/>
                            <p class="help-block">Table where duels rankings columns are located.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="duels-column">Win Column </label>
                        <div class="controls">
                            <input type="text" class="input-large" id="duels-column" name="duels-column" value=""
                                   placeholder="Win"/>
                            <p class="help-block">Win Score column name.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="duels-column2">Lose Column </label>
                        <div class="controls">
                            <input type="text" class="input-large" id="duels-column2" name="duels-column2" value=""
                                   placeholder="Lose"/>
                            <p class="help-block">Lose Score column name.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="duels-identifier_column">Identifier Column </label>
                        <div class="controls">
                            <input type="text" class="input-large" id="duels-identifier_column"
                                   name="duels-identifier_column" value="" placeholder="Name"/>
                            <p class="help-block">Column name by which can identify score, usually character name
                                column.</p>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" name="edit_sql_table_settings"
                                id="edit_sql_table_settings" value="Save changes">Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>