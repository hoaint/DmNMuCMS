<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/shop">Shop Settings</a></li>
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
            $args[0] = 'shop_' . $default;
    ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Shop Settings</h2>
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
                            <label class="control-label" for="module_status">Module Status </label>

                            <div class="controls">
                                <select id="module_status" name="module_status">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0] . '_' . $default]->module_status == 0){
                                        echo 'selected="selected"';
                                    } ?>>Disabled
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->module_status == 1){
                                        echo 'selected="selected"';
                                    } ?>>Enabled
                                    </option>
                                </select>

                                <p class="help-block">Shop Module Status.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="item_per_page">Items Per Page </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="item_per_page" name="item_per_page"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->item_per_page; ?>"/>

                                <p class="help-block">How many items will show in one page.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="columns">Columns </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="columns" name="columns"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->columns; ?>"/>

                                <p class="help-block">How many coulmns will show in one line.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="exe_price">Exe Price </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="exe_price" name="exe_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->exe_price; ?>"/>

                                <p class="help-block">Excellent option price.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="luck_price">Luck Price </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="luck_price" name="luck_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->luck_price; ?>"/>

                                <p class="help-block">Item luck price.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="skill_price">Skill Price </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="skill_price" name="skill_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->skill_price; ?>"/>

                                <p class="help-block">Item Skill price.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="lvl_price">Level Price </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="lvl_price" name="lvl_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->lvl_price; ?>"/>

                                <p class="help-block">Item level price.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="opt_price">Option Price </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="opt_price" name="opt_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->opt_price; ?>"/>

                                <p class="help-block">Item Life option price.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="anc1_price">Ancient settype 1 price </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="anc1_price" name="anc1_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->anc1_price; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="anc2_price">Ancient settype 2 price </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="anc2_price" name="anc2_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->anc2_price; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="dfenrir_price">Fenrir + Destroy Price </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="dfenrir_price" name="dfenrir_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->dfenrir_price; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="pfenrir_price">Fenrir +Protect Price </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="pfenrir_price" name="pfenrir_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->pfenrir_price; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="gfenrir_price">Gold Fenrir Price </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="gfenrir_price" name="gfenrir_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->gfenrir_price; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="ref_price">Refinery Price </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="ref_price" name="ref_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->ref_price; ?>"/>

                                <p class="help-block">Refinery option price.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="exe_limit">Exe limit </label>

                            <div class="controls">
                                <select id="exe_limit" name="exe_limit">
                                    <?php for($i = 0; $i <= 9; $i++): ?>
                                        <option
                                                value="<?php echo $i; ?>" <?php if($this->config->val[$args[0] . '_' . $default]->exe_limit == $i){
                                            echo 'selected="selected"';
                                        } ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>

                                <p class="help-block">Maximum excellent options what user can select.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="use_harmony">Use Harmony </label>

                            <div class="controls">
                                <select id="use_harmony" name="use_harmony">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0] . '_' . $default]->use_harmony == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->use_harmony == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Enable harmony system in webshop.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="use_socket">Use Sockets </label>

                            <div class="controls">
                                <select id="use_socket" name="use_socket">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0] . '_' . $default]->use_socket == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->use_socket == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Enable socket system in webshop.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_select_socket">Allow Select Sockets </label>

                            <div class="controls">
                                <select id="allow_select_socket" name="allow_select_socket">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0] . '_' . $default]->allow_select_socket == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->allow_select_socket == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow to choose sockets in shop.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="equal_seed">Equal Seed Sockets </label>

                            <div class="controls">
                                <select id="equal_seed" name="equal_seed">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0] . '_' . $default]->equal_seed == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->equal_seed == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow select equal seed sockets.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="equal_socket">Equal Sockets </label>

                            <div class="controls">
                                <select id="equal_socket" name="equal_socket">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0] . '_' . $default]->equal_socket == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->equal_socket == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow selecting equal sockets.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="empty_socket">Empty Sockets </label>

                            <div class="controls">
                                <select id="empty_socket" name="empty_socket">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0] . '_' . $default]->empty_socket == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->empty_socket == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">If Use Sockets is disabled add empty sockets to item?.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="socket_limit_credits">Limit Credits 1 </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="socket_limit_credits"
                                       name="socket_limit_credits"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->socket_limit_credits; ?>"/>

                                <p class="help-block">How many sockets allowed when buy with Credits 1.</p>

                                <p>For credits types check your credits settings <a
                                            href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                            target="_blank">here</a></p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="socket_limit_gcredits">Limit Credits 2 </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="socket_limit_gcredits"
                                       name="socket_limit_gcredits"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->socket_limit_gcredits; ?>"/>

                                <p class="help-block">How many sockets allowed when buy with Credits 2.</p>

                                <p>For credits types check your credits settings <a
                                            href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                            target="_blank">here</a></p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="check_socket_part_type">Check Part </label>

                            <div class="controls">
                                <select id="check_socket_part_type" name="check_socket_part_type">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0] . '_' . $default]->check_socket_part_type == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->check_socket_part_type == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Check if socket is allowed for exe part type.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="max_sockets_to_show">Max sockets to select </label>

                            <div class="controls">
                                <select id="max_sockets_to_show" name="max_sockets_to_show">
                                    <?php for($i = 0; $i <= 5; $i++){ ?>
                                        <option
                                                value="<?php echo $i; ?>" <?php if($this->config->val[$args[0] . '_' . $default]->max_sockets_to_show == $i){
                                            echo 'selected="selected"';
                                        } ?>><?php echo $i; ?></option>
                                    <?php } ?>
                                </select>

                                <p class="help-block">How many sockets user will be able to select.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_exe_anc">Exe & Anc </label>

                            <div class="controls">
                                <select id="allow_exe_anc" name="allow_exe_anc">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0] . '_' . $default]->allow_exe_anc == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->allow_exe_anc == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow Exellent and Ancient options in same item.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_exe_socket">Exe & Socket </label>

                            <div class="controls">
                                <select id="allow_exe_socket" name="allow_exe_socket">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0] . '_' . $default]->allow_exe_socket == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->allow_exe_socket == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow Exellent and Socket options in same item.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_anc_harmony">Harmony & Ancient </label>

                            <div class="controls">
                                <select id="allow_anc_harmony" name="allow_anc_harmony">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0] . '_' . $default]->allow_anc_harmony == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->allow_anc_harmony == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Allow Harmony and Ancient options in same item.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="element_type_price">Element Type </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="element_type_price"
                                       name="element_type_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->element_type_price; ?>"/>

                                <p class="help-block">Pentagram and Errtel element type price.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="element_rank_1_price">Element Rank 1 Lvl Price</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="element_rank_1_price"
                                       name="element_rank_1_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->element_rank_1_price; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="element_rank_2_price">Element Rank 2 Lvl Price</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="element_rank_2_price"
                                       name="element_rank_2_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->element_rank_2_price; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="element_rank_3_price">Element Rank 3 Lvl Price</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="element_rank_3_price"
                                       name="element_rank_3_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->element_rank_3_price; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="element_rank_4_price">Element Rank 4 Lvl Price</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="element_rank_4_price"
                                       name="element_rank_4_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->element_rank_4_price; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="element_rank_5_price">Element Rank 5 Lvl Price</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="element_rank_5_price"
                                       name="element_rank_5_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->element_rank_5_price; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="pentagram_slot_anger_price">Pentagram Slot Anger
                                Price</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="pentagram_slot_anger_price"
                                       name="pentagram_slot_anger_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->pentagram_slot_anger_price; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="pentagram_slot_blessing_price">Pentagram Slot Blessing
                                Price</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="pentagram_slot_blessing_price"
                                       name="pentagram_slot_blessing_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->pentagram_slot_blessing_price; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="pentagram_slot_integrity_price">Pentagram Slot Integrity
                                Price</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="pentagram_slot_integrity_price"
                                       name="pentagram_slot_integrity_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->pentagram_slot_integrity_price; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="pentagram_slot_divinity_price">Pentagram Slot Divinity
                                Price</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="pentagram_slot_divinity_price"
                                       name="pentagram_slot_divinity_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->pentagram_slot_divinity_price; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="pentagram_slot_gale_price">Pentagram Slot Gale
                                Price</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="pentagram_slot_gale_price"
                                       name="pentagram_slot_gale_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->pentagram_slot_gale_price; ?>"/>
                            </div>
                        </div>
						<div class="control-group">
                            <label class="control-label" for="wing_element_main_price">Wing Element Main
                                Price</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="wing_element_main_price"
                                       name="wing_element_main_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->wing_element_main_price; ?>"/>
                            </div>
                        </div>
						<div class="control-group">
                            <label class="control-label" for="wing_element_additional_price">Wing Element Additional #1
                                Price</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="wing_element_additional_price"
                                       name="wing_element_additional_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->wing_element_additional_price; ?>"/>
                            </div>
                        </div>
						<div class="control-group">
                            <label class="control-label" for="wing_element_additional2_price">Wing Element Additional #2
                                Price</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="wing_element_additional2_price"
                                       name="wing_element_additional2_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->wing_element_additional2_price; ?>"/>
                            </div>
                        </div>
						<div class="control-group">
                            <label class="control-label" for="wing_element_additional3_price">Wing Element Additional #3
                                Price</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="wing_element_additional3_price"
                                       name="wing_element_additional3_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->wing_element_additional3_price; ?>"/>
                            </div>
                        </div>
						<div class="control-group">
                            <label class="control-label" for="wing_element_additional4_price">Wing Element Additional #4
                                Price</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="wing_element_additional4_price"
                                       name="wing_element_additional4_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->wing_element_additional4_price; ?>"/>
                            </div>
                        </div>
						<div class="control-group">
                            <label class="control-label" for="wing_element_additional5_price">Wing Element Additional #5
                                Price</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="wing_element_additional5_price"
                                       name="wing_element_additional5_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->wing_element_additional5_price; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="discount">Discount </label>

                            <div class="controls">
                                <select id="discount" name="discount">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0] . '_' . $default]->discount == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->discount == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Enable item discount.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="discount_time">Discount Time </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead datepicker" id="discount_time"
                                       name="discount_time"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->discount_time; ?>"/>

                                <p class="help-block">Time until end of discount.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="discount_perc">Discount Percents </label>

                            <div class="controls">
                                <select id="discount_perc" name="discount_perc">
                                    <?php for($i = 0; $i <= 100; $i++): ?>
                                        <option
                                                value="<?php echo $i; ?>" <?php if($this->config->val[$args[0] . '_' . $default]->discount_perc == $i){
                                            echo 'selected="selected"';
                                        } ?>><?php echo $i; ?>%
                                        </option>
                                    <?php endfor; ?>
                                </select>

                                <p class="help-block">How big discount you want in percents.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="discount_notice">Discount Notice </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="discount_notice" name="discount_notice"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->discount_notice; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="gold_credits_price">Credits 2 Price </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="gold_credits_price"
                                       name="gold_credits_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->gold_credits_price; ?>"/>

                                <p class="help-block">Item price difference from Credits 1 in percents.</p>

                                <p>For credits types check your credits settings <a
                                            href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                            target="_blank">here</a></p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="card_item_expires">Item expiration in card</label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="card_item_expires"
                                       name="card_item_expires"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->card_item_expires; ?>"/>
                                <p class="help-block">After how many seconds items in shopping card will expire.</p>
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