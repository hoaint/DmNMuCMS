<?php
    $this->load->view('admincp' . DS . 'view.header');
    $this->load->view('admincp' . DS . 'view.sidebar');
?>
<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-plugins">Manage Plugins</a></li>
        </ul>
    </div>
    <?php $server_list = ($is_multi_server == 0) ? ['all' => ['title' => 'All']] : $this->website->server_list(); ?>
    <div class="row-fluid">
        <div class="span12">
            <ul class="nav nav-pills">
                <?php
                    $i = 0;
                    foreach($server_list AS $key => $val):
                        $i++;
                        ?>
                        <li role="presentation" <?php if($i == 1){ ?> class="active"<?php } ?>><a
                                    href="#<?php echo $key; ?>" aria-controls="<?php echo $key; ?>" role="tab"
                                    data-toggle="tab"><?php echo $val['title']; ?> Server Settings</a></li>
                    <?php endforeach; ?>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
    <?php if(isset($js)): ?>
        <script src="<?php echo $js; ?>"></script>
        <script type="text/javascript">
            var monsterKill = new monsterKill();
            monsterKill.setUrl('<?php echo $this->config->base_url . $this->request->get_controller();?>/<?php echo $this->request->get_method();?>');
            $(document).ready(function () {
                $('form[id^="monster_kill_settings_form_"]').on("submit", function (e) {
                    e.preventDefault();
                    monsterKill.saveSettings($(this));
                });
            });
        </script>
    <?php endif; ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="tab-content">
                <?php
                    $i = 0;
                    foreach($server_list AS $key => $data):
                        $val = ($is_multi_server == 0) ? $plugin_config : (isset($plugin_config[$key]) ? $plugin_config[$key] : false);
                        $i++;
                        ?>
                        <div role="tabpanel" class="tab-pane fade in <?php if($i == 1){ ?>active<?php } ?>"
                             id="<?php echo $key; ?>">
                            <div class="box-header well">
                                <h2><i class="icon-edit"></i> <?php echo $data['title']; ?> Server Settings</h2>
                            </div>
                            <div class="box-content">
                                <form class="form-horizontal" method="POST" action="" id="monster_kill_settings_form__<?php echo $key; ?>">
                                    <input type="hidden" id="server" name="server" value="<?php echo $key; ?>"/>
                                    <div class="control-group">
                                        <label class="control-label" for="active">Status </label>
                                        <div class="controls">
                                            <select id="active" name="active" required>
                                                <option value="0" <?php if($val['active'] == 0){
                                                    echo 'selected="selected"';
                                                } ?>>Inactive
                                                </option>
                                                <option value="1" <?php if($val['active'] == 1){
                                                    echo 'selected="selected"';
                                                } ?>>Active
                                                </option>
                                            </select>
                                            <p class="help-block">Use monster kill rankings module.</p>
                                        </div>
                                    </div>
									<div class="control-group">
										<label class="control-label" for="top">Count</label>
										<div class="controls">
											<input type="text" class="input-xlarge" name="top" id="top" value="<?php if(isset($val['top'])): echo $val['top']; endif; ?>"/>
											<p>Count of players in rankings.</p>
										</div>
									</div>
                                   <div class="control-group">
										<label class="control-label" for="cache_time">Cache Time</label>
										<div class="controls">
											<input type="text" class="input-large" id="cache_time" name="cache_time" value="<?php echo $val['cache_time']; ?>"/>

											<p class="help-block">Rankings cache time in seconds</p>
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="monsters">Monster Ids</label>
										<div class="controls">
											<input type="text" class="input-xlarge" data-role="tagsinput" name="monsters" id="monsters" value="<?php if(isset($val['monsters'])): echo $val['monsters']; endif; ?>"/>
											<p>Monster Ids seperated by comma.</p>
										</div>
									</div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary" name="edit_monster_kill_settings" id="edit_monster_kill_settings">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php
    $this->load->view('admincp' . DS . 'view.footer');
?>
