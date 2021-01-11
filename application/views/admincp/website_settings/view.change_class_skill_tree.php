<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/changeclass">Change Class
                    Settings</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <p class="left">
                <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/changeclass"
                   class="btn btn-large btn-primary">Change Class Settings</a>
                <a href="<?php echo $this->config->base_url; ?>admincp/change-class-allowed-class-list"
                   class="btn btn-large btn-primary">Class List</a>
            </p>
            <div class="clearfix"></div>
        </div>
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
                <h2><i class="icon-edit"></i> Change Class SkillTree Settings</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="">
                    <fieldset>
                        <legend></legend>
                        <div class="control-group">
                            <label class="control-label" for="active">Reset SkillTree </label>
                            <div class="controls">
                                <select id="active" name="active">
                                    <option value="0" <?php if(isset($changeclass_config['skill_tree']['active']) && $changeclass_config['skill_tree']['active'] == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option value="1" <?php if(isset($changeclass_config['skill_tree']['active']) && $changeclass_config['skill_tree']['active'] == 1){
                                        echo 'selected="selected"';
                                    } ?>>Yes
                                    </option>
                                </select>
                                <p class="help-block">Reset skilltree when changing character class.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="skilltree_reset_level">Reset Master Level</label>
                            <div class="controls">
                                <select id="skilltree_reset_level" name="skilltree_reset_level">
                                    <option
                                            value="0" <?php if(isset($changeclass_config['skill_tree']['reset_level']) && $changeclass_config['skill_tree']['reset_level'] == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if(isset($changeclass_config['skill_tree']['reset_level']) && $changeclass_config['skill_tree']['reset_level'] == 1){
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
                                            value="0" <?php if(isset($changeclass_config['skill_tree']['reset_points']) && $changeclass_config['skill_tree']['reset_points'] == 0){
                                        echo 'selected="selected"';
                                    } ?>>No
                                    </option>
                                    <option
                                            value="1" <?php if(isset($changeclass_config['skill_tree']['reset_points']) && $changeclass_config['skill_tree']['reset_points'] == 1){
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
                                                value="<?php echo $i; ?>" <?php if(isset($changeclass_config['skill_tree']['points_multiplier']) && $changeclass_config['skill_tree']['points_multiplier'] == $i){
                                            echo 'selected="selected"';
                                        } ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>

                                <p class="help-block">How many master points character will receive if master points
                                    reset is disabled. Formula: Master Level * Master Points Multiplier</p>
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