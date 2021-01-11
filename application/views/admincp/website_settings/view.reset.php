<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/reset">Reset Settings</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <p class="left">
                <a href="<?php echo $this->config->base_url; ?>admincp/add-reset-settings"
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
                if(!empty($reset_config)){
                    foreach($reset_config AS $server => $settings){
                        ?>
                        <div class="box-header well">
                            <h2><i class="icon-edit"></i> <?php echo $this->website->get_title_from_server($server); ?>
                                Reset Settings</h2>
                        </div>
                        <div class="box-content">
                            <div class="box span9">
                                <div class="box-content">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Reset From</th>
                                            <th>Reset To</th>
                                            <th>Req GrandResets</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="reset-settings-<?php echo $server; ?>">
                                        <?php
                                            if(count($settings) > 1){
                                                $settings_copy = $settings;
                                                unset($settings_copy['allow_reset']);
                                                asort($settings_copy);
                                                foreach($settings_copy AS $key => $data){
                                                    list($from, $to) = explode('-', $key);
                                                    ?>
                                                    <tr id="reset-<?php echo $key; ?>-<?php echo $server; ?>">
                                                        <td><?php echo $from; ?></td>
                                                        <td><?php echo $to; ?></td>
                                                        <td>
                                                            <?php echo isset($data['grand_reset']) ? $data['grand_reset'] : 0; ?>
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-warning" href="#"
                                                               onclick="App.deleteResSettings('<?php echo $key; ?>', '<?php echo $server; ?>');">
                                                                <i class="icon-edit icon-white"></i>
                                                                Remove
                                                            </a>
                                                            <a class="btn btn-primary"
                                                               href="<?php echo $this->config->base_url; ?>admincp/edit-reset-settings/<?php echo $key; ?>/<?php echo $server; ?>">
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
                                                        <input type="radio" name="allow_resets_<?php echo $server; ?>"
                                                               data-no-uniform="true"
                                                               value="1"
                                                               onclick="App.changeResetStatus(1, '<?php echo $server; ?>');" <?php if($settings['allow_reset'] == 1){
                                                            echo 'checked';
                                                        } ?>>
                                                        Allow Reset On Server
                                                    </label>
                                                    <br/>
                                                    <label class="radio">
                                                        <input type="radio" name="allow_resets_<?php echo $server; ?>"
                                                               data-no-uniform="true"
                                                               value="0"
                                                               onclick="App.changeResetStatus(0, '<?php echo $server; ?>');" <?php if($settings['allow_reset'] == 0){
                                                            echo 'checked';
                                                        } ?>>
                                                        Disallow Reset On Server
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