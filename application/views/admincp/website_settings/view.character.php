<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/character">Character Panel
                    Settings</a></li>
        </ul>
    </div>
    <?php
        if(isset($error)){
            echo '<div class="alert alert-error span9">' . $error . '</div>';
        }
        if(isset($success)){
            echo '<div class="alert alert-success span9">' . $success . '</div>';
        }
        $args = $this->request->get_args();
        if(empty($args[0]))
            $args[0] = 'character_' . $default;
    ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Character Panel Settings</h2>
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
                            <label class="control-label" for="max_stats">Max Stats </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="max_stats" name="max_stats"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->max_stats; ?>"/>

                                <p class="help-block">Character max stats limit.</p>
                            </div>
                        </div>
						<div class="control-group">
                            <label class="control-label" for="allow_reset_skilltree">Allow Reset Skill Tree </label>
                            <div class="controls">
                                <select id="allow_reset_skilltree" name="allow_reset_skilltree">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0] . '_' . $default]->allow_reset_skilltree == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->allow_reset_skilltree == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Is reset skill tree of character allowed.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="skill_tree_type">SkillTree Type </label>

                            <div class="controls">
                                <select id="skill_tree_type" name="skill_tree_type">
                                    <option
                                            value="igcn" <?php if($this->config->val[$args[0] . '_' . $default]->skill_tree_type == 'igcn'){
                                        echo 'selected="selected"';
                                    } ?>>IGCN
                                    </option>
                                    <option
                                            value="scf" <?php if($this->config->val[$args[0] . '_' . $default]->skill_tree_type == 'scf'){
                                        echo 'selected="selected"';
                                    } ?>>TitansTech
                                    </option>
                                    <option
                                            value="zteam" <?php if($this->config->val[$args[0] . '_' . $default]->skill_tree_type == 'zteam'){
                                        echo 'selected="selected"';
                                    } ?>>ZTeam / Ex-Team
                                    </option>
                                    <option
                                            value="muengine" <?php if($this->config->val[$args[0] . '_' . $default]->skill_tree_type == 'muengine'){
                                        echo 'selected="selected"';
                                    } ?>>MuEngine
                                    </option>
                                    <option
                                            value="xteam" <?php if($this->config->val[$args[0] . '_' . $default]->skill_tree_type == 'xteam'){
                                        echo 'selected="selected"';
                                    } ?>>X-Team
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="skilltree_reset_price">Skill Tree Reset Price </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="skilltree_reset_price"
                                       name="skilltree_reset_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->skilltree_reset_price; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="skilltree_reset_price_type">Payment Type</label>

                            <div class="controls">
                                <select id="skilltree_reset_price_type" name="skilltree_reset_price_type">
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->skilltree_reset_price_type == 1){
                                        echo 'selected="selected"';
                                    } ?>><?php echo $this->config->config_entry('credits_' . $default . '|title_1'); ?></option>
                                    <option
                                            value="2" <?php if($this->config->val[$args[0] . '_' . $default]->skilltree_reset_price_type == 2){
                                        echo 'selected="selected"';
                                    } ?>><?php echo $this->config->config_entry('credits_' . $default . '|title_2'); ?></option>
                                </select>

                                <p class="help-block">Skill tree reset payment type.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="skilltree_reset_level">Reset Master Level</label>

                            <div class="controls">
                                <select id="skilltree_reset_level" name="skilltree_reset_level">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0] . '_' . $default]->skilltree_reset_level == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->skilltree_reset_level == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Set master level to 0.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="skilltree_reset_points">Reset Master Points</label>

                            <div class="controls">
                                <select id="skilltree_reset_points" name="skilltree_reset_points">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0] . '_' . $default]->skilltree_reset_points == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->skilltree_reset_points == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Set master points to 0.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="skilltree_points_multiplier">Master Points
                                Multiplier</label>

                            <div class="controls">
                                <select id="skilltree_points_multiplier" name="skilltree_points_multiplier">
                                    <?php for($i = 1; $i <= 100; $i++): ?>
                                        <option
                                                value="<?php echo $i; ?>" <?php if($this->config->val[$args[0] . '_' . $default]->skilltree_points_multiplier == $i){
                                            echo 'selected="selected"';
                                        } ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>

                                <p class="help-block">How many master points character will receive if master points
                                    reset is disabled. Formula: Master Level * Master Points Multiplier</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="pk_clear_price">PK Clear Price </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="pk_clear_price" name="pk_clear_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->pk_clear_price; ?>"/>

                                <p class="help-block">How much zen cost pk clear.</p>
                            </div>
                        </div>
						<div class="control-group">
                            <label class="control-label" for="pk_clear_payment_method">PK Clear Payment Method</label>

                            <div class="controls">
                                <select id="pk_clear_payment_method" name="pk_clear_payment_method">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0] . '_' . $default]->pk_clear_payment_method == 0){
                                        echo 'selected="selected"';
                                    } ?>>Zen
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->pk_clear_payment_method == 1){
                                        echo 'selected="selected"';
                                    } ?>>Credits 1
                                    </option>
									<option
                                            value="2" <?php if($this->config->val[$args[0] . '_' . $default]->pk_clear_payment_method == 2){
                                        echo 'selected="selected"';
                                    } ?>>Credits 2
                                    </option>
									<option
                                            value="3" <?php if($this->config->val[$args[0] . '_' . $default]->pk_clear_payment_method == 3){
                                        echo 'selected="selected"';
                                    } ?>>WCoins
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="allow_reset_stats">Allow Reassign Stats </label>
                            <div class="controls">
                                <select id="allow_reset_stats" name="allow_reset_stats">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0] . '_' . $default]->allow_reset_stats == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->allow_reset_stats == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Is reassigning of character stats allowed.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="reset_stats_price">Reset Stats Price </label>

                            <div class="controls">
                                <input type="text" class="span6 typeahead" id="reset_stats_price"
                                       name="reset_stats_price"
                                       value="<?php echo $this->config->val[$args[0] . '_' . $default]->reset_stats_price; ?>"/>

                                <p class="help-block">How much cost reset stats.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="reset_stats_payment_type">Payment Type</label>

                            <div class="controls">
                                <select id="reset_stats_payment_type" name="reset_stats_payment_type">
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->reset_stats_payment_type == 1){
                                        echo 'selected="selected"';
                                    } ?>>Credits 1
                                    </option>
                                    <option
                                            value="2" <?php if($this->config->val[$args[0] . '_' . $default]->reset_stats_payment_type == 2){
                                        echo 'selected="selected"';
                                    } ?>>Credits 2
                                    </option>
                                </select>

                                <p class="help-block">For payment types check your credits settings <a
                                            href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                            target="_blank">here</a></p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="show_equipment">Show Character Equipment</label>

                            <div class="controls">
                                <select id="show_equipment" name="show_equipment">
                                    <option
                                            value="0" <?php if($this->config->val[$args[0] . '_' . $default]->show_equipment == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if($this->config->val[$args[0] . '_' . $default]->show_equipment == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>

                                <p class="help-block">Is character equipment visible in info page.</p>
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