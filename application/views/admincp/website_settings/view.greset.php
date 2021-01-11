<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/greset">Grand Reset Settings</a>
            </li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <p class="left">
                <a href="<?php echo $this->config->base_url; ?>admincp/add-greset-settings"
                   class="btn btn-large btn-primary"> Add New Settings</a>
            </p>

            <div class="clearfix"></div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <?php
                if(isset($error)){
                    echo '<div class="alert alert-error span12">' . $error . '</div>';
                }
                if(isset($success)){
                    echo '<div class="alert alert-success span12">' . $success . '</div>';
                }
                if(!empty($greset_config)){
                    foreach($greset_config AS $server => $settings){
                        ?>
                        <div class="box-header well">
                            <h2><i class="icon-edit"></i> <?php echo $this->website->get_title_from_server($server); ?>
                                Grand Reset Settings</h2>
                        </div>
                        <div class="box-content">
                            <div class="box span9">
                                <div class="box-content">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>GReset From</th>
                                            <th>GReset To</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="greset-settings-<?php echo $server; ?>">
                                        <?php
                                            if(count($settings) > 1){
                                                $settings_copy = $settings;
                                                unset($settings_copy['allow_greset']);
                                                asort($settings_copy);
                                                foreach($settings_copy AS $key => $data){
                                                    list($from, $to) = explode('-', $key);
                                                    ?>
                                                    <tr id="greset-<?php echo $key; ?>-<?php echo $server; ?>">
                                                        <td><?php echo $from; ?></td>
                                                        <td><?php echo $to; ?></td>
                                                        <td>
                                                            <a class="btn btn-warning" href="#"
                                                               onclick="App.deleteGResSettings('<?php echo $key; ?>', '<?php echo $server; ?>');">
                                                                <i class="icon-edit icon-white"></i>
                                                                Remove
                                                            </a>
                                                            <a class="btn btn-primary"
                                                               href="<?php echo $this->config->base_url; ?>admincp/edit-greset-settings/<?php echo $key; ?>/<?php echo $server; ?>">
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
                                                        <input type="radio" name="allow_gresets_<?php echo $server; ?>"
                                                               data-no-uniform="true"
                                                               value="1"
                                                               onclick="App.changeGResetStatus(1, '<?php echo $server; ?>');" <?php if($settings['allow_greset'] == 1){
                                                            echo 'checked';
                                                        } ?>>
                                                        Allow GReset On Server
                                                    </label>
                                                    <br/>
                                                    <label class="radio">
                                                        <input type="radio" name="allow_gresets_<?php echo $server; ?>"
                                                               data-no-uniform="true"
                                                               value="0"
                                                               onclick="App.changeGResetStatus(0, '<?php echo $server; ?>');" <?php if($settings['allow_greset'] == 0){
                                                            echo 'checked';
                                                        } ?>>
                                                        Disallow GReset On Server
                                                    </label>

                                                    <div style="clear:both"></div>
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