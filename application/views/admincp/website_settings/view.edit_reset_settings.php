<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/reset">Reset Settings</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Edit Reset Settings</h2>
            </div>
            <div class="box-content">
                <?php
                    if(isset($not_found)){
                        echo '<div class="alert alert-error">' . $not_found . '</div>';
                    } else{
                        if(isset($error)){
                            if(is_array($error)){
                                foreach($error AS $note){
                                    echo '<div class="alert alert-error">' . $note . '</div>';
                                }
                            } else{
                                echo '<div class="alert alert-error">' . $error . '</div>';
                            }
                        }
                        if(isset($success)){
                            echo '<div class="alert alert-success">' . $success . '</div>';
                        }
                        ?>
                        <form class="form-horizontal" method="POST" action="">
                            <fieldset>
                                <legend></legend>
                                <div class="control-group">
                                    <label class="control-label" for="server">Server</label>

                                    <div class="controls">
                                        <select name="server" id="server">
                                            <?php foreach($servers as $key => $server): ?>
                                                <option value="<?php echo $key; ?>"
                                                        <?php if(isset($selected_server) && $key == $selected_server){ ?>selected="selected"<?php } ?>><?php echo $servers[$key]['title']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sreset">Starting Reset</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="sreset" name="sreset"
                                               value="<?php if(isset($r_config['sreset'])){
                                                   echo $r_config['sreset'];
                                               } ?>" placeholder="0"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="ereset">Ending Reset</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="ereset" name="ereset"
                                               value="<?php if(isset($r_config['ereset'])){
                                                   echo $r_config['ereset'];
                                               } ?>" placeholder="9999"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="grand_reset">Required Grand Reset</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="grand_reset" name="grand_reset"
                                               value="<?php if(isset($r_config['grand_reset'])){
                                                   echo $r_config['grand_reset'];
                                               } ?>" placeholder="0"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="money">Required Zen</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="money" name="money"
                                               value="<?php if(isset($r_config['money'])){
                                                   echo $r_config['money'];
                                               } ?>" placeholder="9999"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="money_x_reset">Is Zen Multiplied by Resets</label>

                                    <div class="controls">
                                        <select name="money_x_reset" id="money_x_reset">
                                            <option value="0"
                                                    <?php if(isset($r_config['money_x_reset']) && 0 == $r_config['money_x_reset']){ ?>selected="selected"<?php } ?>>
                                                No
                                            </option>
                                            <option value="1"
                                                    <?php if(isset($r_config['money_x_reset']) && 1 == $r_config['money_x_reset']){ ?>selected="selected"<?php } ?>>
                                                Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="level">Required level</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="level" name="level"
                                               value="<?php if(isset($r_config['level'])){
                                                   echo $r_config['level'];
                                               } ?>" placeholder="400"/>
                                    </div>
                                </div>
								<div class="control-group">
                                    <label class="control-label" for="level_after_reset">Level after reset</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="level_after_reset" name="level_after_reset"
                                               value="<?php if(isset($r_config['level_after_reset'])){
                                                   echo $r_config['level_after_reset'];
                                               } ?>" placeholder="1"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="reset_cooldown">Reset CoolDown</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="reset_cooldown"
                                               name="reset_cooldown"
                                               value="<?php if(isset($r_config['reset_cooldown'])){
                                                   echo $r_config['reset_cooldown'];
                                               } ?>" placeholder="60"/>

                                        <p>How often can make resets, value should be set in seconds</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="clear_magic">Clear MagicList</label>

                                    <div class="controls">
                                        <select name="clear_magic" id="clear_magic">
                                            <option value="0"
                                                    <?php if(isset($r_config['clear_magic']) && 0 == $r_config['clear_magic']){ ?>selected="selected"<?php } ?>>
                                                No
                                            </option>
                                            <option value="1"
                                                    <?php if(isset($r_config['clear_magic']) && 1 == $r_config['clear_magic']){ ?>selected="selected"<?php } ?>>
                                                Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="clear_inventory">Clear Inventory</label>

                                    <div class="controls">
                                        <select name="clear_inventory" id="clear_inventory">
                                            <option value="0"
                                                    <?php if(isset($r_config['clear_inventory']) && 0 == $r_config['clear_inventory']){ ?>selected="selected"<?php } ?>>
                                                No
                                            </option>
                                            <option value="1"
                                                    <?php if(isset($r_config['clear_inventory']) && 1 == $r_config['clear_inventory']){ ?>selected="selected"<?php } ?>>
                                                Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="clear_exp_inventory">Clear Expanded Inventory</label>

                                    <div class="controls">
                                        <select name="clear_exp_inventory" id="clear_exp_inventory">
                                            <option value="0"
                                                    <?php if(isset($r_config['clear_exp_inventory']) && 0 == $r_config['clear_exp_inventory']){ ?>selected="selected"<?php } ?>>
                                                No
                                            </option>
                                            <option value="1"
                                                    <?php if(isset($r_config['clear_exp_inventory']) && 1 == $r_config['clear_exp_inventory']){ ?>selected="selected"<?php } ?>>
                                                Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="clear_equipment">Clear Equipment</label>

                                    <div class="controls">
                                        <select name="clear_equipment" id="clear_equipment">
                                            <option value="0"
                                                    <?php if(isset($r_config['clear_equipment']) && 0 == $r_config['clear_equipment']){ ?>selected="selected"<?php } ?>>
                                                No
                                            </option>
                                            <option value="1"
                                                    <?php if(isset($r_config['clear_equipment']) && 1 == $r_config['clear_equipment']){ ?>selected="selected"<?php } ?>>
                                                Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="clear_store">Clear Personal Store</label>

                                    <div class="controls">
                                        <select name="clear_store" id="clear_store">
                                            <option value="0"
                                                    <?php if(isset($r_config['clear_store']) && 0 == $r_config['clear_store']){ ?>selected="selected"<?php } ?>>
                                                No
                                            </option>
                                            <option value="1"
                                                    <?php if(isset($r_config['clear_store']) && 1 == $r_config['clear_store']){ ?>selected="selected"<?php } ?>>
                                                Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="clear_stats">Clear Stats</label>

                                    <div class="controls">
                                        <select name="clear_stats" id="clear_stats">
                                            <option value="0"
                                                    <?php if(isset($r_config['clear_stats']) && 0 == $r_config['clear_stats']){ ?>selected="selected"<?php } ?>>
                                                No
                                            </option>
                                            <option value="1"
                                                    <?php if(isset($r_config['clear_stats']) && 1 == $r_config['clear_stats']){ ?>selected="selected"<?php } ?>>
                                                Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="new_stat_points">New Stat Points</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="new_stat_points"
                                               name="new_stat_points"
                                               value="<?php if(isset($r_config['new_stat_points'])){
                                                   echo $r_config['new_stat_points'];
                                               } ?>" placeholder="0"/>

                                        <p>Every stat changed to this value if Clear Stats is activated</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="clear_level_up">Clear LevelUp Points</label>

                                    <div class="controls">
                                        <select name="clear_level_up" id="clear_level_up">
                                            <option value="0"
                                                    <?php if(isset($r_config['clear_level_up']) && 0 == $r_config['clear_level_up']){ ?>selected="selected"<?php } ?>>
                                                No
                                            </option>
                                            <option value="1"
                                                    <?php if(isset($r_config['clear_level_up']) && 1 == $r_config['clear_level_up']){ ?>selected="selected"<?php } ?>>
                                                Yes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="new_free_points">New LevelUp Points</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="new_free_points"
                                               name="new_free_points"
                                               value="<?php if(isset($r_config['new_free_points'])){
                                                   echo $r_config['new_free_points'];
                                               } ?>" placeholder="0"/>

                                        <p>LevelUpPoints changed to this value if Clear LevelUp Points is activated</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_dw">Bonus LevelUp Points DW</label>

                                    <div class="controls">
                                        <div class="input-append">
                                            <input type="text" size="16" id="bonus_lvl_up_dw" name="bonus_lvl_up_dw"
                                                   value="<?php if(isset($r_config['bonus_points']['dw'])){
                                                       echo $r_config['bonus_points']['dw'];
                                                   } ?>" placeholder="0"/>
                                            <button class="btn" type="button" id="apply_to_all_classes">Apply To All Classes
                                            </button>
                                        </div>
                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_sm">Bonus LevelUp Points SM</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_sm"
                                               name="bonus_lvl_up_sm"
                                               value="<?php if(isset($r_config['bonus_points']['sm'])){
                                                   echo $r_config['bonus_points']['sm'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_gm">Bonus LevelUp Points GM</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_gm"
                                               name="bonus_lvl_up_gm"
                                               value="<?php if(isset($r_config['bonus_points']['gm'])){
                                                   echo $r_config['bonus_points']['gm'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_sw">Bonus LevelUp Points SW</label>
                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_sw"
                                               name="bonus_lvl_up_sw"
                                               value="<?php if(isset($r_config['bonus_points']['sw'])){
                                                   echo $r_config['bonus_points']['sw'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_dk">Bonus LevelUp Points DK</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_dk"
                                               name="bonus_lvl_up_dk"
                                               value="<?php if(isset($r_config['bonus_points']['dk'])){
                                                   echo $r_config['bonus_points']['dk'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_bk">Bonus LevelUp Points BK</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_bk"
                                               name="bonus_lvl_up_bk"
                                               value="<?php if(isset($r_config['bonus_points']['bk'])){
                                                   echo $r_config['bonus_points']['bk'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_bm">Bonus LevelUp Points BM</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_bm"
                                               name="bonus_lvl_up_bm"
                                               value="<?php if(isset($r_config['bonus_points']['bm'])){
                                                   echo $r_config['bonus_points']['bm'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_drk">Bonus LevelUp Points DrK</label>
                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_drk"
                                               name="bonus_lvl_up_drk"
                                               value="<?php if(isset($r_config['bonus_points']['drk'])){
                                                   echo $r_config['bonus_points']['drk'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_fe">Bonus LevelUp Points ELF</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_fe"
                                               name="bonus_lvl_up_fe"
                                               value="<?php if(isset($r_config['bonus_points']['fe'])){
                                                   echo $r_config['bonus_points']['fe'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_me">Bonus LevelUp Points ME</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_me"
                                               name="bonus_lvl_up_me"
                                               value="<?php if(isset($r_config['bonus_points']['me'])){
                                                   echo $r_config['bonus_points']['me'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_he">Bonus LevelUp Points HE</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_he"
                                               name="bonus_lvl_up_he"
                                               value="<?php if(isset($r_config['bonus_points']['he'])){
                                                   echo $r_config['bonus_points']['he'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_ne">Bonus LevelUp Points NE</label>
                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_ne"
                                               name="bonus_lvl_up_ne"
                                               value="<?php if(isset($r_config['bonus_points']['ne'])){
                                                   echo $r_config['bonus_points']['ne'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_mg">Bonus LevelUp Points MG</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_mg"
                                               name="bonus_lvl_up_mg"
                                               value="<?php if(isset($r_config['bonus_points']['mg'])){
                                                   echo $r_config['bonus_points']['mg'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_dm">Bonus LevelUp Points DM</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_dm"
                                               name="bonus_lvl_up_dm"
                                               value="<?php if(isset($r_config['bonus_points']['dm'])){
                                                   echo $r_config['bonus_points']['dm'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_mk">Bonus LevelUp Points MK</label>
                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_mk"
                                               name="bonus_lvl_up_mk"
                                               value="<?php if(isset($r_config['bonus_points']['mk'])){
                                                   echo $r_config['bonus_points']['mk'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_dl">Bonus LevelUp Points DL</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_dl"
                                               name="bonus_lvl_up_dl"
                                               value="<?php if(isset($r_config['bonus_points']['dl'])){
                                                   echo $r_config['bonus_points']['dl'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_le">Bonus LevelUp Points LE</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_le"
                                               name="bonus_lvl_up_le"
                                               value="<?php if(isset($r_config['bonus_points']['le'])){
                                                   echo $r_config['bonus_points']['le'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_er">Bonus LevelUp Points ER</label>
                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_er"
                                               name="bonus_lvl_up_er"
                                               value="<?php if(isset($r_config['bonus_points']['er'])){
                                                   echo $r_config['bonus_points']['er'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_su">Bonus LevelUp Points SUM</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_su"
                                               name="bonus_lvl_up_su"
                                               value="<?php if(isset($r_config['bonus_points']['su'])){
                                                   echo $r_config['bonus_points']['su'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_bs">Bonus LevelUp Points BS</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_bs"
                                               name="bonus_lvl_up_bs"
                                               value="<?php if(isset($r_config['bonus_points']['bs'])){
                                                   echo $r_config['bonus_points']['bs'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_dim">Bonus LevelUp Points DIM</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_dim"
                                               name="bonus_lvl_up_dim"
                                               value="<?php if(isset($r_config['bonus_points']['dim'])){
                                                   echo $r_config['bonus_points']['dim'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_ds">Bonus LevelUp Points DS</label>
                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_ds"
                                               name="bonus_lvl_up_ds"
                                               value="<?php if(isset($r_config['bonus_points']['ds'])){
                                                   echo $r_config['bonus_points']['ds'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_rf">Bonus LevelUp Points RF</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_rf"
                                               name="bonus_lvl_up_rf"
                                               value="<?php if(isset($r_config['bonus_points']['rf'])){
                                                   echo $r_config['bonus_points']['rf'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_fm">Bonus LevelUp Points FM</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_fm"
                                               name="bonus_lvl_up_fm"
                                               value="<?php if(isset($r_config['bonus_points']['fm'])){
                                                   echo $r_config['bonus_points']['fm'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_fb">Bonus LevelUp Points FB</label>
                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_fb"
                                               name="bonus_lvl_up_fb"
                                               value="<?php if(isset($r_config['bonus_points']['fb'])){
                                                   echo $r_config['bonus_points']['fb'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_gl">Bonus LevelUp Points GL</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_gl"
                                               name="bonus_lvl_up_gl"
                                               value="<?php if(isset($r_config['bonus_points']['gl'])){
                                                   echo $r_config['bonus_points']['gl'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_ml">Bonus LevelUp Points ML</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_ml"
                                               name="bonus_lvl_up_ml"
                                               value="<?php if(isset($r_config['bonus_points']['ml'])){
                                                   echo $r_config['bonus_points']['ml'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_sl">Bonus LevelUp Points SL</label>
                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_sl"
                                               name="bonus_lvl_up_sl"
                                               value="<?php if(isset($r_config['bonus_points']['sl'])){
                                                   echo $r_config['bonus_points']['sl'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_rw">Bonus LevelUp Points RW</label>
                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_rw"
                                               name="bonus_lvl_up_rw"
                                               value="<?php if(isset($r_config['bonus_points']['rw'])){
                                                   echo $r_config['bonus_points']['rw'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_rsm">Bonus LevelUp Points RSM</label>
                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_rsm"
                                               name="bonus_lvl_up_rsm"
                                               value="<?php if(isset($r_config['bonus_points']['rsm'])){
                                                   echo $r_config['bonus_points']['rsm'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_lvl_up_grm">Bonus LevelUp Points GRM</label>
                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_lvl_up_grm"
                                               name="bonus_lvl_up_grm"
                                               value="<?php if(isset($r_config['bonus_points']['grm'])){
                                                   echo $r_config['bonus_points']['grm'];
                                               } ?>" placeholder="0"/>

                                        <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                                    </div>
                                </div>
								<div class="control-group">
									<label class="control-label" for="bonus_lvl_up_rw4">Bonus LevelUp Points MRW</label>
									<div class="controls">
										<input type="text" class="span3 typeahead" id="bonus_lvl_up_rw4"
											   name="bonus_lvl_up_rw4"
											   value="<?php if(isset($r_config['bonus_points']['rw4'])){
												   echo $r_config['bonus_points']['rw4'];
											   } ?>" placeholder="0"/>

										<p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="bonus_lvl_up_slr">Bonus LevelUp Points SLR</label>
									<div class="controls">
										<input type="text" class="span3 typeahead" id="bonus_lvl_up_slr"
											   name="bonus_lvl_up_slr"
											   value="<?php if(isset($r_config['bonus_points']['slr'])){
												   echo $r_config['bonus_points']['slr'];
											   } ?>" placeholder="0"/>

										<p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="bonus_lvl_up_rsl">Bonus LevelUp Points RSL</label>
									<div class="controls">
										<input type="text" class="span3 typeahead" id="bonus_lvl_up_rsl"
											   name="bonus_lvl_up_rsl"
											   value="<?php if(isset($r_config['bonus_points']['rsl'])){
												   echo $r_config['bonus_points']['rsl'];
											   } ?>" placeholder="0"/>

										<p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="bonus_lvl_up_msl">Bonus LevelUp Points MSL</label>
									<div class="controls">
										<input type="text" class="span3 typeahead" id="bonus_lvl_up_msl"
											   name="bonus_lvl_up_msl"
											   value="<?php if(isset($r_config['bonus_points']['msl'])){
												   echo $r_config['bonus_points']['msl'];
											   } ?>" placeholder="0"/>

										<p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="bonus_lvl_up_slt">Bonus LevelUp Points SLT</label>
									<div class="controls">
										<input type="text" class="span3 typeahead" id="bonus_lvl_up_slt"
											   name="bonus_lvl_up_slt"
											   value="<?php if(isset($r_config['bonus_points']['slt'])){
												   echo $r_config['bonus_points']['slt'];
											   } ?>" placeholder="0"/>

										<p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
									</div>
								</div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_credits">Bonus Credits</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_credits"
                                               name="bonus_credits"
                                               value="<?php if(isset($r_config['bonus_credits'])){
                                                   echo $r_config['bonus_credits'];
                                               } ?>" placeholder="0"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_gcredits">Bonus Gold Credits</label>

                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="bonus_gcredits"
                                               name="bonus_gcredits"
                                               value="<?php if(isset($r_config['bonus_gcredits'])){
                                                   echo $r_config['bonus_gcredits'];
                                               } ?>" placeholder="0"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="bonus_gr_points">Bonud GR StatPoints</label>

                                    <div class="controls">
                                        <select name="bonus_gr_points" id="bonus_gr_points">
                                            <option value="0"
                                                    <?php if(isset($r_config['bonus_gr_points']) && 0 == $r_config['bonus_gr_points']){ ?>selected="selected"<?php } ?>>
                                                No
                                            </option>
                                            <option value="1"
                                                    <?php if(isset($r_config['bonus_gr_points']) && 1 == $r_config['bonus_gr_points']){ ?>selected="selected"<?php } ?>>
                                                Yes
                                            </option>
                                        </select>

                                        <p>Add earned bonus grand reset points</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="clear_masterlevel">Clear MasterLevel</label>

                                    <div class="controls">
                                        <select name="clear_masterlevel" id="clear_masterlevel">
                                            <option value="0"
                                                    <?php if(isset($r_config['clear_masterlevel']) && 0 == $r_config['clear_masterlevel']){ ?>selected="selected"<?php } ?>>
                                                No
                                            </option>
                                            <option value="1"
                                                    <?php if(isset($r_config['clear_masterlevel']) && 1 == $r_config['clear_masterlevel']){ ?>selected="selected"<?php } ?>>
                                                Yes
                                            </option>
                                        </select>

                                        <p>Clear master level on reset</p>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary" name="edit_settings">Edit settings
                                    </button>
                                    <button type="reset" class="btn">Cancel</button>
                                </div>
                            </fieldset>
                        </form>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>