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
                <a href="<?php echo $this->config->base_url; ?>admincp/change-class-skill-tree"
                   class="btn btn-large btn-primary">SkillTree</a>
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
                <h2><i class="icon-edit"></i> Allowed Class List</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="">
                    <fieldset>
                        <legend></legend>
                        <?php foreach($class_list AS $key => $class): ?>
                            <div class="control-group">
                                <label class="control-label" for="selectError1"><?php echo $class['long']; ?>
                                    (<?php echo $key; ?>)</label>
                                <div class="input-append">
                                    <select multiple class="controls" id="class_list_<?php echo $key; ?>"
                                            name="allowed_class_list[<?php echo $key; ?>][]">
                                        <?php foreach($class_list AS $k => $v): ?>
                                            <option value="<?php echo $k; ?>" <?php if(isset($changeclass_config['class_list'][$key]) && in_array($k, $changeclass_config['class_list'][$key])): echo 'selected="selected"'; endif; ?>><?php echo $v['long']; ?>
                                                (<?php echo $k; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button class="btn" type="button" id="select_all_<?php echo $key; ?>">Select All
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" name="edit_config">Save changes</button>

                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>