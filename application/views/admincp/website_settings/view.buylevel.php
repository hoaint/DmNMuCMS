<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/buylevel">Buy Level Settings</a>
            </li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <p class="left">
                <a href="<?php echo $this->config->base_url; ?>admincp/add-buylevel-settings"
                   class="btn btn-large btn-primary"> Add New Settings</a>
            </p>

            <div class="clearfix"></div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <?php
                if(isset($error)){
                    echo '<div class="alert alert-error span9">' . $error . '</div>';
                }
                if(isset($success)){
                    echo '<div class="alert alert-success span9">' . $success . '</div>';
                }
                if(!empty($buylevel_config)){
                    foreach($buylevel_config AS $server => $settings){
                        ?>
                        <div class="box-header well">
                            <h2><i class="icon-edit"></i> <?php echo $this->website->get_title_from_server($server); ?>
                                Buy Level Settings</h2>
                        </div>
                        <div class="box-content">
                            <div class="box span9">
                                <?php
                                    if(count($settings['levels']) > 0){
                                        ?>
                                        <div class="box-content">
                                            <form class="form-horizontal" method="POST" action=""
                                                  id="max_level_settings_form_<?php echo $server; ?>">
                                                <fieldset>
                                                    <div class="control-group">
                                                        <label class="control-label" for="max_level">Max Level </label>

                                                        <div class="controls">
                                                            <input type="text" class="span6 typeahead" id="max_level"
                                                                   name="max_level"
                                                                   value="<?php echo (isset($settings['max_level'])) ? $settings['max_level'] : ''; ?>"
                                                                   placeholder="999"/>
                                                            <p class="help-block">Maximum level allowed for purchase.</p>
                                                        </div>
                                                    </div>
                                                    <div class="form-actions">
                                                        <button type="submit" class="btn btn-primary"
                                                                name="edit_max_level_settings_<?php echo $server; ?>"
                                                                id="edit_max_level_settings_<?php echo $server; ?>">Save changes
                                                        </button>
                                                        <button type="reset" class="btn">Cancel</button>
                                                    </div>
                                                </fieldset>
                                            </form>
                                        </div>
                                        <?php
                                    }
                                ?>
                                <div class="box-content">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Level</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="buylevel-settings-<?php echo $server; ?>">
                                        <?php
                                            if(count($settings['levels']) > 0){
                                                foreach($settings['levels'] AS $key => $data){
                                                    ?>
                                                    <tr id="level-<?php echo $key; ?>-<?php echo $server; ?>">
                                                        <td><?php echo $key; ?></td>
                                                        <td><?php echo $data['price'] . ' ' . $this->website->translate_credits($data['payment_type'], $server); ?></td>
                                                        <td>
                                                            <a class="btn btn-warning" href="#"
                                                               onclick="App.deleteBuyLevelSettings('<?php echo $key; ?>', '<?php echo $server; ?>');">
                                                                <i class="icon-edit icon-white"></i>
                                                                Remove
                                                            </a>
                                                            <a class="btn btn-primary"
                                                               href="<?php echo $this->config->base_url; ?>admincp/edit-buylevel-settings/<?php echo $key; ?>/<?php echo $server; ?>">
                                                                <i class="icon-edit icon-white"></i>
                                                                Edit
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else{
                                                echo '<tr><td colspan="3"><div class="alert alert-info">No settings for this server.</div></td></tr>';
                                            }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="box span3">
                                <div class="box-content">
                                    <form class="form-inline">
                                        <fieldset>
                                            <div class="control-group">
                                                <div class="controls">
                                                    <label class="radio">
                                                        <input type="radio" name="status_buylevel_<?php echo $server; ?>"
                                                               data-no-uniform="true"
                                                               value="1"
                                                               onclick="App.changeBuyLevelStatus(1, '<?php echo $server; ?>');" <?php if($settings['active'] == 1){
                                                            echo 'checked';
                                                        } ?>>
                                                        Module Active
                                                    </label>
                                                    <br/>
                                                    <label class="radio">
                                                        <input type="radio" name="status_buylevel_<?php echo $server; ?>"
                                                               data-no-uniform="true"
                                                               value="0"
                                                               onclick="App.changeBuyLevelStatus(0, '<?php echo $server; ?>');" <?php if($settings['active'] == 0){
                                                            echo 'checked';
                                                        } ?>>
                                                        Module Disabled
                                                    </label>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div style="clear:both"></div>
                        <?php
                    }
                } else{
                    echo '<div class="box-content"><div class="alert alert-info">No settings have been added.</div></div>';
                }
            ?>
        </div>
    </div>
</div>