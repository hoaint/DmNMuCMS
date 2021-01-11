<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/referral">Referral System
                    Settings</a></li>
        </ul>
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
        <div class="span12">
            <ul class="nav nav-pills">
                <li role="presentation" class="active"><a href="#settings" aria-controls="settings" role="tab"
                                                          data-toggle="tab" onclick="App.loadReferralSettings();">Settings</a>
                </li>
                <li role="presentation"><a href="#reward_list" aria-controls="reward_list" role="tab" data-toggle="tab">Reward
                        List</a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            App.loadReferralSettings();
        });
    </script>
    <div class="row-fluid">
        <div class="box span12">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="settings">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="referral_settings_form">
                            <div class="control-group">
                                <label class="control-label" for="active">Status </label>
                                <div class="controls">
                                    <select id="active" name="active">
                                        <option value="0">Inactive</option>
                                        <option value="1">Active</option>
                                    </select>
                                    <p class="help-block">Use referral system.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_on_registration">Reward On Registration</label>
                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="reward_on_registration"
                                           name="reward_on_registration" value="" required pattern="\d*"/>
                                    <p class="help-block">Reward user which refer new user on registration</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_type">Reward Type</label>
                                <div class="controls">
                                    <select id="reward_type" name="reward_type">
                                        <option value="1">Credits 1</option>
                                        <option value="2">Credits 2</option>
                                    </select>
                                    <p class="help-block">Which donation points user will receive.</p>
                                    <p>For reward types check your credits settings <a
                                                href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                                target="_blank">here</a></p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="claim_type">Reward Claim Type</label>
                                <div class="controls">
                                    <select id="claim_type" name="claim_type">
                                        <option value="0">Claim Once</option>
                                        <option value="1">Claim Multiple</option>
                                    </select>
                                    <p class="help-block">Allow claiming reward only once or multiple times with
                                        different characters.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="compare_ips">Compare Ips</label>
                                <div class="controls">
                                    <select id="compare_ips" name="compare_ips">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                    <p class="help-block">Check ips of referrer and referral.</p>
                                </div>
                            </div>
							<div class="control-group">
                                <label class="control-label" for="allow_email_invitations">Email Invitations </label>
                                <div class="controls">
                                    <select id="allow_email_invitations" name="allow_email_invitations">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                    <p class="help-block">Allow sent friend invitations to email.</p>
                                </div>
                            </div>
							<div class="control-group">
                                <label class="control-label" for="reward_on_donation">Reward on donation</label>
                                <div class="controls">
                                    <select id="reward_on_donation" name="reward_on_donation">
									<?php for($i = 0; $i <= 100; $i++){ ?>
									<option value="<?php echo $i;?>"><?php echo $i;?>%</option>
									<?php } ?>
                                    </select>
                                    <p class="help-block">For each donation referral makes mentor can get % from donation reward.</p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_referral_settings"
                                        id="edit_referral_settings">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="reward_list">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Reward List</h2>
                    </div>
                    <div class="box-content">
                        <div id="referral_reward_list">
                            <table class="table table-striped table-bordered bootstrap-datatable datatable"
                                   id="referral_sortable">
                                <thead>
                                <tr>
                                    <th>Req LvL</th>
                                    <th>Req Res</th>
                                    <th>Req GRes</th>
                                    <th>Reward</th>
                                    <th>Server</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="referral_sortable_content">
                                <?php foreach($reward_list as $data): ?>
                                    <tr id="<?php echo $data['id']; ?>">
                                        <td><?php echo $data['required_lvl']; ?></td>
                                        <td class="center"><?php echo $data['required_res']; ?></td>
                                        <td class="center"><?php echo $data['required_gres']; ?></td>
                                        <td class="center"><?php echo $data['reward']; ?><?php echo $this->website->translate_credits($data['reward_type'], $data['server']); ?></td>
                                        <td class="center"><?php echo $this->website->get_title_from_server($data['server']); ?></td>
                                        <td class="center" id="status_icon_<?php echo $data['id']; ?>">
                                            <?php if($data['status'] == 1): ?>
                                                <span class="label label-success">Active</span>
                                            <?php else: ?>
                                                <span class="label label-important">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="center">
                                            <?php if($data['status'] == 1): ?>
                                                <a class="btn btn-danger" href="#"
                                                   id="status_button_<?php echo $data['id']; ?>"
                                                   onclick="App.changeReferralRewardStatus(<?php echo $data['id']; ?>, 0);">
                                                    <i class="icon-edit icon-white"></i>
                                                    Disable
                                                </a>
                                            <?php else: ?>
                                                <a class="btn btn-success" href="#"
                                                   id="status_button_<?php echo $data['id']; ?>"
                                                   onclick="App.changeReferralRewardStatus(<?php echo $data['id']; ?>, 1);">
                                                    <i class="icon-edit icon-white"></i>
                                                    Enable
                                                </a>
                                            <?php endif; ?>
                                            <a class="btn btn-danger" href="#"
                                               onclick="App.deleteReferralReward(<?php echo $data['id']; ?>);">
                                                <i class="icon-trash icon-white"></i>
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <table class="table table-striped table-bordered bootstrap-datatable datatable">
                            <tbody>
                            <tr>
                                <td>
                                    <select id="req_lvl" class="input-medium">
                                        <?php for($i = 1; $i <= 1000; $i++): ?>
                                            <option value="<?php echo $i; ?>">Req Lvl <?php echo $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td>
                                    <select id="req_res" class="input-medium">
                                        <?php for($i = 0; $i <= 1000; $i++): ?>
                                            <option value="<?php echo $i; ?>">Req Res <?php echo $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td>
                                    <select id="req_gres" class="input-medium">
                                        <?php for($i = 0; $i <= 100; $i++): ?>
                                            <option value="<?php echo $i; ?>">Req GRes <?php echo $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                                <td><input class="input-small" type="text" id="reward" placeholder="Reward" value=""
                                           required/></td>
                                <td class="center">
                                    <select id="reward_type_custom" class="input-medium">
                                        <option value="1">Credits 1</option>
                                        <option value="2">Credits 2</option>
                                    </select>
                                </td>
                                <td class="center">
                                    <select id="server" class="input-medium">
                                        <?php
                                            foreach($server_list as $key => $server):
                                                ?>
                                                <option value="<?php echo $key; ?>"><?php echo $server_list[$key]['title']; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <a class="btn btn-success" href="#" onclick="App.addReferralReward();">
                                        <i class="icon-edit icon-white"></i>
                                        Add
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>