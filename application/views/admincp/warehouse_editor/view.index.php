<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/warehouse-editor">Warehouse Editor</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2>Warehouse</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php
                    if(isset($error)){
                        echo '<div class="alert alert-error">' . $error . '</div>';
                    }
                    if(isset($success)){
                        echo '<div class="alert alert-success">' . $success . '</div>';
                    }
                ?>
                <form class="form-horizontal" method="post"
                      action="<?php echo $this->config->base_url; ?>admincp/warehouse-editor">
                    <fieldset>
                        <legend></legend>
                        <div class="control-group">
                            <label class="control-label" for="account">Username <span
                                        style="color:red;">*</span></label>

                            <div class="controls">
                                <input type="text" class="input-xlarge" name="account" id="account"
                                       value="<?php if(isset($_POST['account'])): echo $_POST['account']; endif; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="server">Server <span style="color:red;">*</span></label>

                            <div class="controls">
                                <select id="server" name="server">
                                    <option value="">Select</option>
                                    <?php
                                        foreach($this->website->server_list() as $key => $value){
                                            if(isset($_POST['server']) && $_POST['server'] == $key){
                                                echo '<option value="' . $key . '" selected="selected">' . $value['title'] . "</option>\n";
                                            } else{
                                                echo '<option value="' . $key . '">' . $value['title'] . "</option>\n";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <?php if($show_vault){ ?>
        <div class="row-fluid">
            <div class="box span5">
                <div class="box-header well" data-original-title>
                    <h2>User Warehouse</h2>

                    <div class="box-icon">
                        <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                    </div>
                </div>
                <div class="box-content">
                    <?php
                        $wh_content = '<div style="float:left;width: 261px; margin:2px auto; padding-top:6px; padding-left:3px; height:485px; background-image: url(' . $this->config->base_url . 'assets/' . $this->config->config_entry('main|template') . '/images/wh.jpg)" id="wh_content">' . "\n";
                        for($i = 1; $i <= 120; $i++){
                            if(isset($items[$i])){
                                $wh_content .= '<div id="item-slot-' . $i . '" class="square" style="margin-top:' . ($items[$i]['yy'] * 32) . 'px; margin-left:' . ($items[$i]['xx'] * 32) . 'px; position:absolute; width:' . ($items[$i]['x'] * 32) . 'px; cursor:pointer; background-image: url(' . $this->config->base_url . 'assets/' . $this->config->config_entry('main|template') . '/images/wh_root_on.png); height:' . ($items[$i]['y'] * 32) . 'px;" data-info="' . $items[$i]['hex'] . '"><img width="100%" height="100%" alt="' . $items[$i]['name'] . '" src="' . $this->itemimage->load($items[$i]['item_id'], $items[$i]['item_cat'], $items[$i]['level'], 0) . '" /></div>' . "\n";
                            } else{
                                $wh_content .= '<div id="item-slot-' . $i . '" class="square"></div>' . "\n";
                            }
                        }
                        $wh_content .= '</div>';
                        echo $wh_content;
                        if($total_items > 120){
                            $wh_content2 = '<div style="float:right;width: 261px; margin:2px auto; padding-top:6px; padding-left:3px; height:485px; background-image: url(' . $this->config->base_url . 'assets/' . $this->config->config_entry('main|template') . '/images/wh.jpg)" id="wh_content">' . "\n";
                            for($i = 121; $i <= 240; $i++){
                                if(isset($items[$i])){
                                    $wh_content2 .= '<div id="item-slot-' . $i . '" class="square" style="margin-top:' . ($items[$i]['yy'] * 32) . 'px; margin-left:' . ($items[$i]['xx'] * 32) . 'px; position:absolute; width:' . ($items[$i]['x'] * 32) . 'px; cursor:pointer; background-image: url(' . $this->config->base_url . 'assets/' . $this->config->config_entry('main|template') . '/images/wh_root_on.png); height:' . ($items[$i]['y'] * 32) . 'px;" data-info="' . $items[$i]['hex'] . '"><img width="100%" height="100%" alt="' . $items[$i]['name'] . '" src="' . $this->itemimage->load($items[$i]['item_id'], $items[$i]['item_cat'], $items[$i]['level'], 0) . '" /></div>' . "\n";
                                } else{
                                    $wh_content2 .= '<div id="item-slot-' . $i . '"></div>' . "\n";
                                }
                            }
                            $wh_content2 .= '</div>';
                            echo $wh_content2;
                        }
                    ?>
                </div>
            </div>
            <div class="box span7">
                <div class="box-header well" data-original-title>
                    <h2>Add Item</h2>

                    <div class="box-icon">
                        <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                    </div>
                </div>
                <div class="box-content">
                    <form class="form-horizontal" method="post" action="" id="item_form">
                        <fieldset>
                            <legend></legend>
                            <div class="control-group">
                                <label class="control-label" for="category">Category</label>

                                <div class="controls">
                                    <select id="category_wh" name="category_wh">
                                        <option value=""> ----</option>
                                        <?php
                                            echo $this->webshop->load_cat_list(true);
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="items_wh">Item</label>

                                <div class="controls">
                                    <select id="items_wh" name="items_wh">
                                        <option value="">None</option>
                                    </select>
                                </div>
                            </div>
                            <div id="item_options" style="display:none;">
                                <div class="control-group">
                                    <label class="control-label" for="items_lvl">Item Lvl</label>

                                    <div class="controls">
                                        <select id="items_lvl" name="items_lvl">
                                            <?php for($i = 0; $i <= 15; $i++){ ?>
                                                <option value="<?php echo $i; ?>">+ <?php echo $i; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="items_opt">Item Option</label>

                                    <div class="controls">
                                        <select id="items_opt" name="items_opt">
                                            <?php for($i = 0; $i <= 7; $i++){ ?>
                                                <option value="<?php echo $i; ?>">+ <?php echo $i * 4; ?> |
                                                    + <?php echo $i * 5; ?> | <?php echo $i; ?>%
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="items_luck">Item Luck</label>

                                    <div class="controls">
                                        <select id="items_luck" name="items_luck">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="items_skill">Item Skill</label>

                                    <div class="controls">
                                        <select id="items_skill" name="items_skill">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="items_ref">Item Refinery Option</label>

                                    <div class="controls">
                                        <select id="items_ref" name="items_ref">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="items_harm">Item Harmony Option</label>

                                    <div class="controls">
                                        <select id="items_harm" name="items_harm">
                                            <option value="" selected="selected"> ---</option>
                                            <optgroup label="Weapons" id="Weapons">
                                                <option value="1">Min. attack increase</option>
                                                <option value="2">Max. attack increase</option>
                                                <option value="3">Required strength decrease</option>
                                                <option value="4">Required agility decrease</option>
                                                <option value="5">Attack (Max, Min)</option>
                                                <option value="6">Critical Damage increase</option>
                                                <option value="7">Skill Damage increase</option>
                                                <option value="8">Attack Success Rate (PvP) increase</option>
                                                <option value="9">SD Reduction</option>
                                                <option value="10">SD Ignore Rate</option>
                                            </optgroup>
                                            <optgroup label="Staffs" id="Staffs">
                                                <option value="1">Wizardly attack increase</option>
                                                <option value="2">Required strength decrease</option>
                                                <option value="3">Required agility decrease</option>
                                                <option value="4">Skill Damage increase</option>
                                                <option value="5">Critical Damage increase</option>
                                                <option value="6">SD Reduction</option>
                                                ";
                                                <option value="7">Attack Success Rate (PvP) increase</option>
                                                <option value="8">SD Ignore Rate</option>
                                            </optgroup>
                                            <optgroup label="Sets & Shields" id="Sets">
                                                <option value="1">Defense increase</option>
                                                <option value="2">Max. AG Increase</option>
                                                <option value="3">Max. HP increase</option>
                                                <option value="4">Life auto increment increase</option>
                                                <option value="5">Mana auto increment increase</option>
                                                <option value="6">Defense success rate increase(PVP)</option>
                                                <option value="7">Damage decrement increase</option>
                                                <option value="8">SD Ratio Rate increase</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group" id="harmonyoption" style="display: none;">
                                    <label class="control-label" for="harmonyvalue">Item Harmony Value</label>

                                    <div class="controls">
                                        <select id="harmonyvalue" name="harmonyvalue">

                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="items_anc">Ancient</label>

                                    <div class="controls">
                                        <select id="items_anc" name="items_anc">
                                            <option value="0">No</option>
                                            <option value="1">Tier 1</option>
                                            <option value="2">Tier 2</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="items_exe_type">Exe Type</label>

                                    <div class="controls">
                                        <select id="items_exe_type" name="items_exe_type">
                                            <option value="">None</option>
                                            <option value="1">Weapons & Pendants</option>
                                            <option value="2">Shields & Sets</option>
                                            <option value="3">Wings LVL 1-2</option>
                                            <option value="6">Wings LVL 3</option>
                                            <option value="7">Wings LVL 4</option>
                                            <option value="4">Fenrir</option>
                                            <option value="5">Rings</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="exe-1" style="display:none;">
                                    <div class="control-group">
                                        <label class="control-label">Exe Options</label>

                                        <div class="controls">
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="1"
                                                       data-no-uniform="true">
                                                Increases Mana After monster +Mana/8
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="2"
                                                       data-no-uniform="true">
                                                Increases Life After monster +Life/8
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="3"
                                                       data-no-uniform="true">
                                                Increase attacking(wizardly)speed+7
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="4"
                                                       data-no-uniform="true">
                                                Increase Damage +2%
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="5"
                                                       data-no-uniform="true">
                                                Increase Damage +level/20
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="6"
                                                       data-no-uniform="true">
                                                Excellent Damage Rate +10%
                                            </label><br/>
                                        </div>
                                    </div>
                                </div>
                                <div id="exe-2" style="display:none;">
                                    <div class="control-group">
                                        <label class="control-label">Exe Options</label>

                                        <div class="controls">
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="1"
                                                       data-no-uniform="true">
                                                Increase Zen After Hunt +40%
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="2"
                                                       data-no-uniform="true"> Defense
                                                success rate +10%
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="3"
                                                       data-no-uniform="true"> Reflect
                                                damage +5%
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="4"
                                                       data-no-uniform="true"> Damage
                                                decrease +4%
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="5"
                                                       data-no-uniform="true">
                                                Increase MaxMana +4%
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="6"
                                                       data-no-uniform="true">
                                                Increase MaxHP +4%
                                            </label><br/>
                                        </div>
                                    </div>
                                </div>
                                <div id="exe-3" style="display:none;">
                                    <div class="control-group">
                                        <label class="control-label">Exe Options</label>

                                        <div class="controls">
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="1"
                                                       data-no-uniform="true"> HP +115
                                                Increase
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="2"
                                                       data-no-uniform="true"> MP +115
                                                Increase
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="3"
                                                       data-no-uniform="true"> Ignore
                                                enemys defensive power by 3%
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="4"
                                                       data-no-uniform="true"> Max AG
                                                +50 Increase
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="5"
                                                       data-no-uniform="true">
                                                Increase attacking(wizardly)speed+5
                                            </label><br/>
                                        </div>
                                    </div>
                                </div>
                                <div id="exe-4" style="display:none;">
                                    <div class="control-group">
                                        <label class="control-label">Exe Options</label>

                                        <div class="controls">
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="radio" name="fenrir" value="1"
                                                       data-no-uniform="true"> Fenrir
                                                +Damage
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="radio" name="fenrir" value="2"
                                                       data-no-uniform="true"> Fenrir
                                                +Defense
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="radio" name="fenrir" value="4"
                                                       data-no-uniform="true"> Fenrir
                                                +Illusion
                                            </label><br/>
                                        </div>
                                    </div>
                                </div>
                                <div id="exe-5" style="display:none;">
                                    <div class="control-group">
                                        <label class="control-label">Exe Options</label>

                                        <div class="controls">
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="1"
                                                       data-no-uniform="true">
                                                Increase Zen After Hunt +40%
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="2"
                                                       data-no-uniform="true"> Defense
                                                success rate +10%
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="3"
                                                       data-no-uniform="true"> Reflect
                                                damage +5%
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="4"
                                                       data-no-uniform="true"> Damage
                                                decrease +4%
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="5"
                                                       data-no-uniform="true">
                                                Increase MaxMana +4%
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="6"
                                                       data-no-uniform="true">
                                                Increase MaxHP +4%
                                            </label><br/>
                                        </div>
                                    </div>
                                </div>
                                <div id="exe-6" style="display:none;">
                                    <div class="control-group">
                                        <label class="control-label">Exe Options</label>

                                        <div class="controls">
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="1"
                                                       data-no-uniform="true"> Ingore
                                                opponents defensive power by 5%
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="2"
                                                       data-no-uniform="true"> Returns
                                                the enemy's attack power in 5%
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="3"
                                                       data-no-uniform="true">
                                                Complete recovery of life in 5% rate
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="4"
                                                       data-no-uniform="true">
                                                Complete recover of Mana in 5%
                                            </label><br/>
                                        </div>
                                    </div>
                                </div>
                                <div id="exe-7" style="display:none;">
                                    <div class="control-group">
                                        <label class="control-label">Exe Options</label>

                                        <div class="controls">
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="1"
                                                       data-no-uniform="true"> Chance
                                                of Double Damage +4%
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="2"
                                                       data-no-uniform="true"> Chance
                                                of Damage From Breaking Enemy's Defense +4
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="3"
                                                       data-no-uniform="true">
                                                Complete recovery of life in 4% rate
                                            </label><br/>
                                            <label class="checkbox inline">
                                                <input class="exe_check" type="checkbox" name="exe[]" value="4"
                                                       data-no-uniform="true">
                                                Complete recover of Mana in 5% rate
                                            </label><br/>
                                        </div>
                                    </div>
                                </div>
                                <div id="socket_opts" style="display:none;">
                                    <div class="control-group">
                                        <label class="control-label" for="socket1">Socket 1</label>

                                        <div class="controls">
                                            <select id="socket1" name="socket1">
                                                <option value="no">None</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="socket2">Socket 2</label>

                                        <div class="controls">
                                            <select id="socket2" name="socket2">
                                                <option value="no">None</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="socket3">Socket 3</label>

                                        <div class="controls">
                                            <select id="socket3" name="socket3">
                                                <option value="no">None</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="socket4">Socket 4</label>

                                        <div class="controls">
                                            <select id="socket4" name="socket4">
                                                <option value="no">None</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="socket5">Socket 5</label>

                                        <div class="controls">
                                            <select id="socket5" name="socket5">
                                                <option value="no">None</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" id="add_item_but" class="btn btn-primary">Add Item</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
</div>