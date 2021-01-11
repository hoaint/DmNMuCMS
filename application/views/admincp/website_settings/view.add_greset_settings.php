<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/greset">Grand Reset Settings</a>
            </li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Add New Grand Reset Settings</h2>
            </div>
            <div class="box-content">
                <?php
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
                                                <?php if(isset($_POST['server']) && $key == $_POST['server']){ ?>selected="selected"<?php } ?>><?php echo $servers[$key]['title']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="sreset">Starting GReset</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="sreset" name="sreset"
                                       value="<?php if(isset($_POST['sreset'])){
                                           echo $_POST['sreset'];
                                       } ?>" placeholder="0"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="ereset">Ending GReset</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="ereset" name="ereset"
                                       value="<?php if(isset($_POST['ereset'])){
                                           echo $_POST['ereset'];
                                       } ?>" placeholder="9999"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="money">Required Zen</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="money" name="money"
                                       value="<?php if(isset($_POST['money'])){
                                           echo $_POST['money'];
                                       } ?>" placeholder="9999"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="money_x_reset">Is Zen Multiplied by Grand Reset</label>

                            <div class="controls">
                                <select name="money_x_reset" id="money_x_reset">
                                    <option value="0"
                                            <?php if(isset($_POST['money_x_reset']) && 0 == $_POST['money_x_reset']){ ?>selected="selected"<?php } ?>>
                                        No
                                    </option>
                                    <option value="1"
                                            <?php if(isset($_POST['money_x_reset']) && 1 == $_POST['money_x_reset']){ ?>selected="selected"<?php } ?>>
                                        Yes
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="level">Required level</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="level" name="level"
                                       value="<?php if(isset($_POST['level'])){
                                           echo $_POST['level'];
                                       } ?>" placeholder="400"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="reset">Required reset</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="reset" name="reset"
                                       value="<?php if(isset($_POST['reset'])){
                                           echo $_POST['reset'];
                                       } ?>" placeholder="100"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="clear_all_resets">Clear All Resets</label>

                            <div class="controls">
                                <select name="clear_all_resets" id="clear_all_resets">
                                    <option value="0"
                                            <?php if(isset($_POST['clear_all_resets']) && 0 == $_POST['clear_all_resets']){ ?>selected="selected"<?php } ?>>
                                        No
                                    </option>
                                    <option value="1"
                                            <?php if(isset($_POST['clear_all_resets']) && 1 == $_POST['clear_all_resets']){ ?>selected="selected"<?php } ?>>
                                        Yes
                                    </option>
                                </select>

                                <p>Clear all character resets or only resets required above.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="clear_magic">Clear MagicList</label>

                            <div class="controls">
                                <select name="clear_magic" id="clear_magic">
                                    <option value="0"
                                            <?php if(isset($_POST['clear_magic']) && 0 == $_POST['clear_magic']){ ?>selected="selected"<?php } ?>>
                                        No
                                    </option>
                                    <option value="1"
                                            <?php if(isset($_POST['clear_magic']) && 1 == $_POST['clear_magic']){ ?>selected="selected"<?php } ?>>
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
                                            <?php if(isset($_POST['clear_inventory']) && 0 == $_POST['clear_inventory']){ ?>selected="selected"<?php } ?>>
                                        No
                                    </option>
                                    <option value="1"
                                            <?php if(isset($_POST['clear_inventory']) && 1 == $_POST['clear_inventory']){ ?>selected="selected"<?php } ?>>
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
                                            <?php if(isset($_POST['clear_stats']) && 0 == $_POST['clear_stats']){ ?>selected="selected"<?php } ?>>
                                        No
                                    </option>
                                    <option value="1"
                                            <?php if(isset($_POST['clear_stats']) && 1 == $_POST['clear_stats']){ ?>selected="selected"<?php } ?>>
                                        Yes
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="new_stat_points">New Stat Points</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="new_stat_points" name="new_stat_points"
                                       value="<?php if(isset($_POST['new_stat_points'])){
                                           echo $_POST['new_stat_points'];
                                       } ?>" placeholder="0"/>

                                <p>Every stat changed to this value if Clear Stats is activated</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="clear_level_up">Clear LevelUp Points</label>

                            <div class="controls">
                                <select name="clear_level_up" id="clear_level_up">
                                    <option value="0"
                                            <?php if(isset($_POST['clear_level_up']) && 0 == $_POST['clear_level_up']){ ?>selected="selected"<?php } ?>>
                                        No
                                    </option>
                                    <option value="1"
                                            <?php if(isset($_POST['clear_level_up']) && 1 == $_POST['clear_level_up']){ ?>selected="selected"<?php } ?>>
                                        Yes
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="new_free_points">New LevelUp Points</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="new_free_points" name="new_free_points"
                                       value="<?php if(isset($_POST['new_free_points'])){
                                           echo $_POST['new_free_points'];
                                       } ?>" placeholder="0"/>

                                <p>LevelUpPoints changed to this value if Clear LevelUp Points is activated</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_dw">Bonus LevelUp Points DW</label>

                            <div class="controls">
                                <div class="input-append">
                                    <input type="text" size="16" id="bonus_lvl_up_dw" name="bonus_lvl_up_dw"
                                           value="<?php if(isset($_POST['bonus_lvl_up_dw'])){
                                               echo $_POST['bonus_lvl_up_dw'];
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
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_sm" name="bonus_lvl_up_sm"
                                       value="<?php if(isset($_POST['bonus_lvl_up_sm'])){
                                           echo $_POST['bonus_lvl_up_sm'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_gm">Bonus LevelUp Points GM</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_gm" name="bonus_lvl_up_gm"
                                       value="<?php if(isset($_POST['bonus_lvl_up_gm'])){
                                           echo $_POST['bonus_lvl_up_gm'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_sw">Bonus LevelUp Points SW</label>
                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_sw"
                                       name="bonus_lvl_up_sw"
                                       value="<?php if(isset($_POST['bonus_lvl_up_sw'])){
                                           echo $_POST['bonus_lvl_up_sw'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_dk">Bonus LevelUp Points DK</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_dk" name="bonus_lvl_up_dk"
                                       value="<?php if(isset($_POST['bonus_lvl_up_dk'])){
                                           echo $_POST['bonus_lvl_up_dk'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_bk">Bonus LevelUp Points BK</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_bk" name="bonus_lvl_up_bk"
                                       value="<?php if(isset($_POST['bonus_lvl_up_bk'])){
                                           echo $_POST['bonus_lvl_up_bk'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_bm">Bonus LevelUp Points BM</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_bm" name="bonus_lvl_up_bm"
                                       value="<?php if(isset($_POST['bonus_lvl_up_bm'])){
                                           echo $_POST['bonus_lvl_up_bm'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_drk">Bonus LevelUp Points DrK</label>
                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_drk"
                                       name="bonus_lvl_up_drk"
                                       value="<?php if(isset($_POST['bonus_lvl_up_drk'])){
                                           echo $_POST['bonus_lvl_up_drk'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_fe">Bonus LevelUp Points ELF</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_fe" name="bonus_lvl_up_fe"
                                       value="<?php if(isset($_POST['bonus_lvl_up_fe'])){
                                           echo $_POST['bonus_lvl_up_fe'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_me">Bonus LevelUp Points ME</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_me" name="bonus_lvl_up_me"
                                       value="<?php if(isset($_POST['bonus_lvl_up_me'])){
                                           echo $_POST['bonus_lvl_up_me'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_he">Bonus LevelUp Points HE</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_he" name="bonus_lvl_up_he"
                                       value="<?php if(isset($_POST['bonus_lvl_up_he'])){
                                           echo $_POST['bonus_lvl_up_he'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_ne">Bonus LevelUp Points NE</label>
                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_ne"
                                       name="bonus_lvl_up_ne"
                                       value="<?php if(isset($_POST['bonus_lvl_up_ne'])){
                                           echo $_POST['bonus_lvl_up_ne'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_mg">Bonus LevelUp Points MG</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_mg" name="bonus_lvl_up_mg"
                                       value="<?php if(isset($_POST['bonus_lvl_up_mg'])){
                                           echo $_POST['bonus_lvl_up_mg'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_dm">Bonus LevelUp Points DM</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_dm" name="bonus_lvl_up_dm"
                                       value="<?php if(isset($_POST['bonus_lvl_up_dm'])){
                                           echo $_POST['bonus_lvl_up_dm'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_mk">Bonus LevelUp Points MK</label>
                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_mk"
                                       name="bonus_lvl_up_mk"
                                       value="<?php if(isset($_POST['bonus_lvl_up_mk'])){
                                           echo $_POST['bonus_lvl_up_mk'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_dl">Bonus LevelUp Points DL</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_dl" name="bonus_lvl_up_dl"
                                       value="<?php if(isset($_POST['bonus_lvl_up_dl'])){
                                           echo $_POST['bonus_lvl_up_dl'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_le">Bonus LevelUp Points LE</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_le" name="bonus_lvl_up_le"
                                       value="<?php if(isset($_POST['bonus_lvl_up_le'])){
                                           echo $_POST['bonus_lvl_up_le'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_er">Bonus LevelUp Points ER</label>
                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_er"
                                       name="bonus_lvl_up_er"
                                       value="<?php if(isset($_POST['bonus_lvl_up_er'])){
                                           echo $_POST['bonus_lvl_up_er'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_su">Bonus LevelUp Points SUM</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_su" name="bonus_lvl_up_su"
                                       value="<?php if(isset($_POST['bonus_lvl_up_su'])){
                                           echo $_POST['bonus_lvl_up_su'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_bs">Bonus LevelUp Points BS</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_bs" name="bonus_lvl_up_bs"
                                       value="<?php if(isset($_POST['bonus_lvl_up_bs'])){
                                           echo $_POST['bonus_lvl_up_bs'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_dim">Bonus LevelUp Points DIM</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_dim" name="bonus_lvl_up_dim"
                                       value="<?php if(isset($_POST['bonus_lvl_up_dim'])){
                                           echo $_POST['bonus_lvl_up_dim'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_ds">Bonus LevelUp Points DS</label>
                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_ds"
                                       name="bonus_lvl_up_ds"
                                       value="<?php if(isset($_POST['bonus_lvl_up_ds'])){
                                           echo $_POST['bonus_lvl_up_ds'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_rf">Bonus LevelUp Points RF</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_rf" name="bonus_lvl_up_rf"
                                       value="<?php if(isset($_POST['bonus_lvl_up_rf'])){
                                           echo $_POST['bonus_lvl_up_rf'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_fm">Bonus LevelUp Points FM</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_fm" name="bonus_lvl_up_fm"
                                       value="<?php if(isset($_POST['bonus_lvl_up_fm'])){
                                           echo $_POST['bonus_lvl_up_fm'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_fb">Bonus LevelUp Points FB</label>
                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_fb"
                                       name="bonus_lvl_up_fb"
                                       value="<?php if(isset($_POST['bonus_lvl_up_fb'])){
                                           echo $_POST['bonus_lvl_up_fb'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_gl">Bonus LevelUp Points GL</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_gl" name="bonus_lvl_up_gl"
                                       value="<?php if(isset($_POST['bonus_lvl_up_gl'])){
                                           echo $_POST['bonus_lvl_up_gl'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_ml">Bonus LevelUp Points ML</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_ml" name="bonus_lvl_up_ml"
                                       value="<?php if(isset($_POST['bonus_lvl_up_ml'])){
                                           echo $_POST['bonus_lvl_up_ml'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_sl">Bonus LevelUp Points SL</label>
                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_sl"
                                       name="bonus_lvl_up_sl"
                                       value="<?php if(isset($_POST['bonus_lvl_up_sl'])){
                                           echo $_POST['bonus_lvl_up_sl'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_rw">Bonus LevelUp Points RW</label>
                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_rw"
                                       name="bonus_lvl_up_rw"
                                       value="<?php if(isset($_POST['bonus_lvl_up_rw'])){
                                           echo $_POST['bonus_lvl_up_rw'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_rsm">Bonus LevelUp Points RSM</label>
                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_rsm"
                                       name="bonus_lvl_up_rsm"
                                       value="<?php if(isset($_POST['bonus_lvl_up_rsm'])){
                                           echo $_POST['bonus_lvl_up_rsm'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_grm">Bonus LevelUp Points GRM</label>
                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_grm"
                                       name="bonus_lvl_up_grm"
                                       value="<?php if(isset($_POST['bonus_lvl_up_grm'])){
                                           echo $_POST['bonus_lvl_up_grm'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
						<div class="control-group">
                            <label class="control-label" for="bonus_lvl_up_rw4">Bonus LevelUp Points MRW</label>
                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_lvl_up_rw4"
                                       name="bonus_lvl_up_rw4"
                                       value="<?php if(isset($_POST['bonus_lvl_up_rw4'])){
                                           echo $_POST['bonus_lvl_up_rw4'];
                                       } ?>" placeholder="0"/>

                                <p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
                            </div>
                        </div>
						<div class="control-group">
							<label class="control-label" for="bonus_lvl_up_slr">Bonus LevelUp Points SLR</label>
							<div class="controls">
								<input type="text" class="span3 typeahead" id="bonus_lvl_up_slr"
									   name="bonus_lvl_up_slr"
									   value="<?php if(isset($_POST['bonus_lvl_up_slr'])){ echo $_POST['bonus_lvl_up_slr']; } ?>" placeholder="0"/>

								<p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="bonus_lvl_up_rsl">Bonus LevelUp Points RSL</label>
							<div class="controls">
								<input type="text" class="span3 typeahead" id="bonus_lvl_up_rsl"
									   name="bonus_lvl_up_rsl"
									   value="<?php if(isset($_POST['bonus_lvl_up_rsl'])){ echo $_POST['bonus_lvl_up_rsl']; } ?>" placeholder="0"/>

								<p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="bonus_lvl_up_msl">Bonus LevelUp Points MSL</label>
							<div class="controls">
								<input type="text" class="span3 typeahead" id="bonus_lvl_up_msl"
									   name="bonus_lvl_up_msl"
									   value="<?php if(isset($_POST['bonus_lvl_up_msl'])){ echo $_POST['bonus_lvl_up_msl']; } ?>" placeholder="0"/>

								<p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="bonus_lvl_up_slt">Bonus LevelUp Points SLT</label>
							<div class="controls">
								<input type="text" class="span3 typeahead" id="bonus_lvl_up_slt"
									   name="bonus_lvl_up_slt"
									   value="<?php if(isset($_POST['bonus_lvl_up_slt'])){ echo $_POST['bonus_lvl_up_slt']; } ?>" placeholder="0"/>

								<p>Bonus LevelUp Points after reset character this value is multiplied by resets</p>
							</div>
						</div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_points_save">Bonus LevelUp Point Save</label>

                            <div class="controls">
                                <select name="bonus_points_save" id="bonus_points_save">
                                    <option value="0"
                                            <?php if(isset($_POST['bonus_points_save']) && 0 == $_POST['bonus_points_save']){ ?>selected="selected"<?php } ?>>
                                        No
                                    </option>
                                    <option value="1"
                                            <?php if(isset($_POST['bonus_points_save']) && 1 == $_POST['bonus_points_save']){ ?>selected="selected"<?php } ?>>
                                        Yes
                                    </option>
                                </select>

                                <p>Multiply bonus points after each grand reset</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_reset_stats">Bonus Reset Stats</label>

                            <div class="controls">
                                <select name="bonus_reset_stats" id="bonus_reset_stats">
                                    <option value="0"
                                            <?php if(isset($_POST['bonus_reset_stats']) && 0 == $_POST['bonus_reset_stats']){ ?>selected="selected"<?php } ?>>
                                        No
                                    </option>
                                    <option value="1"
                                            <?php if(isset($_POST['bonus_reset_stats']) && 1 == $_POST['bonus_reset_stats']){ ?>selected="selected"<?php } ?>>
                                        Yes
                                    </option>
                                </select>

                                <p>Add bonus stats earned by reseting character</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_credits">Bonus Credits</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_credits" name="bonus_credits"
                                       value="<?php if(isset($_POST['bonus_credits'])){
                                           echo $_POST['bonus_credits'];
                                       } ?>" placeholder="0"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="bonus_gcredits">Bonus Gold Credits</label>

                            <div class="controls">
                                <input type="text" class="span3 typeahead" id="bonus_gcredits" name="bonus_gcredits"
                                       value="<?php if(isset($_POST['bonus_gcredits'])){
                                           echo $_POST['bonus_gcredits'];
                                       } ?>" placeholder="0"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="clear_masterlevel">Clear MasterLevel</label>

                            <div class="controls">
                                <select name="clear_masterlevel" id="clear_masterlevel">
                                    <option value="0"
                                            <?php if(isset($_POST['clear_masterlevel']) && 0 == $_POST['clear_masterlevel']){ ?>selected="selected"<?php } ?>>
                                        No
                                    </option>
                                    <option value="1"
                                            <?php if(isset($_POST['clear_masterlevel']) && 1 == $_POST['clear_masterlevel']){ ?>selected="selected"<?php } ?>>
                                        Yes
                                    </option>
                                </select>

                                <p>Clear master level and experience on grand reset</p>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" name="add_greset_settings">Add settings
                            </button>
                            <button type="reset" class="btn">Cancel</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>