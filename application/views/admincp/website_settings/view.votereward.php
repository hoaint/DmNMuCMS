<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li>
                <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/votereward">Votereward
                    Settings</a>
            </li>
        </ul>
    </div>
    <?php
        if(isset($error)){
            echo '<div class="alert alert-error span9">' . $error . '</div>';
        }
        if(isset($success)){
            echo '<div class="alert alert-success span9">' . $success . '</div>';
        }
    ?>
    <div class="row-fluid">
        <div class="span12">
            <ul class="nav nav-pills">
                <li role="presentation">
                    <a href="<?php echo $this->config->base_url; ?>admincp/vote-links">Voting Link Editor</a></li>
                <?php
                    $i = 0;
                    foreach($this->website->server_list() AS $key => $val):
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
    <div class="row-fluid">
        <div class="box span12">
            <div class="tab-content">
                <?php
                    $i = 0;
                    foreach($this->website->server_list() AS $key => $data):
                        $val = isset($votereward_config[$key]) ? $votereward_config[$key] : false;
                        $i++;
                        ?>
                        <div role="tabpanel" class="tab-pane fade in <?php if($i == 1){ ?>active<?php } ?>"
                             id="<?php echo $key; ?>">
                            <div class="box-header well">
                                <h2><i class="icon-edit"></i> <?php echo $data['title']; ?> Server Settings</h2>
                            </div>
                            <div class="box-content">
                                <form class="form-horizontal" method="POST" action=""
                                      id="votereward_settings_form_<?php echo $key; ?>">
                                    <input type="hidden" id="server" name="server" value="<?php echo $key; ?>"/>

                                    <div class="control-group">
                                        <label class="control-label" for="active">Status </label>

                                        <div class="controls">
                                            <select id="active" name="active">
                                                <option value="0" <?php if($val['active'] == 0){
                                                    echo 'selected="selected"';
                                                } ?>>Inactive
                                                </option>
                                                <option value="1" <?php if($val['active'] == 1){
                                                    echo 'selected="selected"';
                                                } ?>>Active
                                                </option>
                                            </select>

                                            <p class="help-block">Use votereward system.</p>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="req_char">Votereward require character</label>

                                        <div class="controls">
                                            <select id="req_char" name="req_char">
                                                <option
                                                        value="0" <?php if($val['req_char'] == 0){
                                                    echo 'selected="selected"';
                                                } ?>>No
                                                </option>
                                                <option
                                                        value="1" <?php if($val['req_char'] == 1){
                                                    echo 'selected="selected"';
                                                } ?>>Yes
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="req_lvl">Votereward require level</label>

                                        <div class="controls">
                                            <select id="req_lvl" name="req_lvl">
                                                <?php for($a = 0; $a <= 1000; $a++): ?>
                                                    <option
                                                            value="<?php echo $a; ?>" <?php if($val['req_lvl'] == $a){
                                                        echo 'selected="selected"';
                                                    } ?>><?php echo $a; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="req_res">Votereward require resets</label>

                                        <div class="controls">
                                            <select id="req_res" name="req_res">
                                                <?php for($a = 0; $a <= 1000; $a++): ?>
                                                    <option
                                                            value="<?php echo $a; ?>" <?php if($val['req_res'] == $a){
                                                        echo 'selected="selected"';
                                                    } ?>><?php echo $a; ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="xtremetop_same_acc_vote">XtremeTop100 Vote</label>

                                        <div class="controls">
                                            <select id="xtremetop_same_acc_vote" name="xtremetop_same_acc_vote">
                                                <option
                                                        value="0" <?php if($val['xtremetop_same_acc_vote'] == 0){
                                                    echo 'selected="selected"';
                                                } ?>>No
                                                </option>
                                                <option
                                                        value="1" <?php if($val['xtremetop_same_acc_vote'] == 1){
                                                    echo 'selected="selected"';
                                                } ?>>Yes
                                                </option>
                                            </select>

                                            <p class="help-block">Allow to vote for xtremetop100 links from same acc but
                                                different ip.</p>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="xtremetop_link_numbers">XtremeTop Links </label>

                                        <div class="controls">
                                            <input type="text" class="span6 typeahead" id="xtremetop_link_numbers"
                                                   name="xtremetop_link_numbers"
                                                   value="<?php echo $this->Madmin->xtremetop100_autoload_links($key); ?>"/>

                                            <p class="help-block">XtremeTop100 link numbers seperated by comma. They are
                                                autoloaded no need to edit this field.</p>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="count_down">Count Down </label>

                                        <div class="controls">
                                            <input type="text" class="span6 typeahead" id="count_down" name="count_down"
                                                   value="<?php echo $val['count_down']; ?>" placeholder="60" required/>

                                            <p class="help-block">How long will take voting for link before vote submission
                                                value in seconds</p>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="is_monthly_reward">Is Monthly Reward </label>

                                        <div class="controls">
                                            <select id="is_monthly_reward" name="is_monthly_reward">
                                                <option
                                                        value="0" <?php if($val['is_monthly_reward'] == 0){
                                                    echo 'selected="selected"';
                                                } ?>>No
                                                </option>
                                                <option
                                                        value="1" <?php if($val['is_monthly_reward'] == 1){
                                                    echo 'selected="selected"';
                                                } ?>>Yes
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="amount_of_players_to_reward">Player Amount</label>

                                        <div class="controls">
                                            <select id="amount_of_players_to_reward" name="amount_of_players_to_reward">
                                                <?php for($a = 1; $a <= 100; $a++): ?>
                                                    <option
                                                            value="<?php echo $a; ?>" <?php if($val['amount_of_players_to_reward'] == $a){
                                                        echo 'selected="selected"';
                                                    } ?>><?php echo $a; ?></option>
                                                <?php endfor; ?>
                                            </select>

                                            <p class="help-block">How many players will receive reward.</p>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="reward_formula">Reward Formula </label>

                                        <div class="controls">
                                            <input type="text" class="span6 typeahead" id="reward_formula"
                                                   name="reward_formula"
                                                   value="<?php echo $val['reward_formula']; ?>"/>

                                            <p class="help-block">
                                                Here you can provide formula how players will be rewarded. Ex: <span
                                                        style="color:green;">5000 - ({position} * 450)</span> in result 1st
                                                place would receive 4650 credits but 10th place 500 Available variables
                                            <ul>
                                                <li>{position} - player position</li>
                                                <li>{totalvotes} - player total votes in current month</li>
                                            </ul>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="reward_type">Reward Type</label>

                                        <div class="controls">
                                            <select id="reward_type" name="reward_type">
                                                <option
                                                        value="1" <?php if($val['reward_type'] == 1){
                                                    echo 'selected="selected"';
                                                } ?>>Credits 1
                                                </option>
                                                <option
                                                        value="2" <?php if($val['reward_type'] == 2){
                                                    echo 'selected="selected"';
                                                } ?>>Credits 2
                                                </option>
                                            </select>

                                            <p class="help-block">Monthly reward type.</p>

                                            <p>For credits types check your credits settings <a
                                                        href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                                        target="_blank">here</a></p>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary" name="edit_vote_settings"
                                                id="edit_vote_settings">Save changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>