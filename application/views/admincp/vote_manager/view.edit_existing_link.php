<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/vote-links">Edit Voting Links</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <ul class="nav nav-pills">
                <li role="presentation" class="active"><a
                            href="<?php echo $this->config->base_url; ?>admincp/manage-settings/votereward">VoteReward
                        Settings</a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-edit"></i>Edit Voting Link</h2>
            </div>
            <div class="box-content">
                <?php
                    if(isset($vote['error'])){
                        echo '<div class="alert alert-error">' . $vote['error'] . '</div>';
                    } else{
                        if(isset($error)){
                            echo '<div class="alert alert-error">' . $error . '</div>';
                        }
                        if(isset($success)){
                            echo '<div class="alert alert-success">' . $success . '</div>';
                        }
                        ?>
                        <form class="form-horizontal" method="post" action="">
                            <fieldset>
                                <legend></legend>
                                <div class="control-group">
                                    <label class="control-label" for="votelink">Voting Url <span
                                                style="color:red;">*</span></label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="votelink" id="votelink"
                                               value="<?php echo $link_data['votelink']; ?>"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="name">Voting Site Name <span
                                                style="color:red;">*</span></label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" id="name" name="name"
                                               value="<?php echo $link_data['name']; ?>"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="img_url">Image Url <span
                                                style="color:red;">*</span></label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" id="img_url" name="img_url"
                                               value="<?php echo $link_data['img_url']; ?>"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="hours">Allow Vote Every <span
                                                style="color:red;">*</span></label>

                                    <div class="controls">
                                        <select id="hours" name="hours">
                                            <?php
                                                for($i = 1; $i <= 48; $i++):
                                                    $selected = ($link_data['hours'] == $i) ? 'selected="selected"' : '';
                                                    ?>
                                                    <option
                                                            value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?>
                                                        H
                                                    </option>
                                                <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="reward">Reward <span
                                                style="color:red;">*</span></label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" id="reward" name="reward"
                                               value="<?php echo $link_data['reward']; ?>"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="reward_type">Reward Type <span
                                                style="color:red;">*</span></label>

                                    <div class="controls">
                                        <select id="reward_type" name="reward_type">
                                            <option
                                                    value="1" <?php if(isset($link_data['reward_type']) && $link_data['reward_type'] == 1){
                                                echo 'selected="selected"';
                                            } ?>>Credits 1
                                            </option>
                                            <option
                                                    value="2" <?php if(isset($link_data['reward_type']) && $link_data['reward_type'] == 2){
                                                echo 'selected="selected"';
                                            } ?>>Credits 2
                                            </option>
                                        </select>

                                        <p class="help-block">For reward types check your credits settings <a
                                                    href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                                    target="_blank">here</a></p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="voting_api">Voting Api</span></label>

                                    <div class="controls">
                                        <select id="voting_api" name="voting_api">
                                            <option
                                                    value="0" <?php if(isset($link_data['api']) && $link_data['api'] == 0){
                                                echo 'selected="selected"';
                                            } ?>>None
                                            </option>
                                            <option
                                                    value="1" <?php if(isset($link_data['api']) && $link_data['api'] == 1){
                                                echo 'selected="selected"';
                                            } ?>>XtremeTop100.com
                                            </option>
                                            <option
                                                    value="2" <?php if(isset($link_data['api']) && $link_data['api'] == 2){
                                                echo 'selected="selected"';
                                            } ?>>MMOTOP.ru
                                            </option>
                                            <option
                                                    value="7" <?php if(isset($link_data['api']) && $link_data['api'] == 7){
                                                echo 'selected="selected"';
                                            } ?>>MuServer.top
                                            </option>
                                            <option
                                                    value="3" <?php if(isset($link_data['api']) && $link_data['api'] == 3){
                                                echo 'selected="selected"';
                                            } ?>>GTop100.com
                                            </option>
                                            <option
                                                    value="4" <?php if(isset($link_data['api']) && $link_data['api'] == 4){
                                                echo 'selected="selected"';
                                            } ?>>TopG.org
                                            </option>
                                            <option
                                                    value="5" <?php if(isset($link_data['api']) && $link_data['api'] == 5){
                                                echo 'selected="selected"';
                                            } ?>>Top100Arena.com
                                            </option>
                                            <option
                                                    value="6" <?php if(isset($link_data['api']) && $link_data['api'] == 6){
                                                echo 'selected="selected"';
                                            } ?>>MMOServer.pro
                                            </option>
                                            <option
                                                    value="8" <?php if(isset($link_data['api']) && $link_data['api'] == 8){
                                                echo 'selected="selected"';
                                            } ?>>UltraTop100.com
                                            </option>
                                        </select>

                                        <p class="help-block">If topsite has some vote checking api, you can enable this
                                            option.</p>
                                    </div>
                                </div>

                                <div class="control-group"
                                     id="xtremetop100" <?php if(isset($link_data['api']) && $link_data['api'] != 1){
                                    echo 'style="display:none;"';
                                } ?>>
                                    <div class="controls">
                                        <?php if(!isset($votereward_config['api_key']) || $votereward_config['api_key'] == ''): ?>
                                            Please access <a
                                                    href="<?php echo $this->config->base_url; ?>admincp/manage-settings/votereward">votereward
                                                settings page</a> atleast once to generate api key and then come back.
                                        <?php else: ?>
                                            <b>Do not share this url to 3rd parties!</b><br/>
                                            Postback url: <span
                                                    style="color:red;"><?php echo $this->config->base_url; ?>vote-api/xtremetop/<?php echo $votereward_config['api_key']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div id="mmotop" <?php if(isset($link_data['api']) && $link_data['api'] != 2){
                                    echo 'style="display:none;"';
                                } ?>>
                                    <div class="control-group">
                                        <label class="control-label" for="mmotop_stats_url">MMOTOP Api Stats Url</label>

                                        <div class="controls">
                                            <input type="text" class="input-xlarge" id="mmotop_stats_url"
                                                   name="mmotop_stats_url"
                                                   value="<?php echo $link_data['mmotop_stats_url']; ?>"/>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label" for="mmotop_reward_sms">MMOTOP SMS Vote Reward</label>

                                        <div class="controls">
                                            <input type="text" class="input-xlarge" id="mmotop_reward_sms"
                                                   name="mmotop_reward_sms"
                                                   value="<?php echo $link_data['mmotop_reward_sms']; ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group"
                                     id="gtop" <?php if(isset($link_data['api']) && $link_data['api'] != 3){
                                    echo 'style="display:none;"';
                                } ?>>
                                    <div class="controls">
                                        <?php if(!isset($votereward_config['api_key']) || $votereward_config['api_key'] == ''): ?>
                                            Please access <a
                                                    href="<?php echo $this->config->base_url; ?>admincp/manage-settings/votereward">votereward
                                                settings page</a> atleast once to generate api key and then come back.
                                        <?php else: ?>
                                            <b>Do not share this url to 3rd parties!</b><br/>
                                            Postback url: <span
                                                    style="color:red;"><?php echo $this->config->base_url; ?>vote-api/gtop100/<?php echo $votereward_config['api_key']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="control-group"
                                     id="topg" <?php if(isset($link_data['api']) && $link_data['api'] != 4){
                                    echo 'style="display:none;"';
                                } ?>>
                                    <div class="controls">
                                        <?php if(!isset($votereward_config['api_key']) || $votereward_config['api_key'] == ''): ?>
                                            Please access <a
                                                    href="<?php echo $this->config->base_url; ?>admincp/manage-settings/votereward">votereward
                                                settings page</a> atleast once to generate api key and then come back.
                                        <?php else: ?>
                                            <b>Do not share this url to 3rd parties!</b><br/>
                                            Postback url: <span
                                                    style="color:red;"><?php echo $this->config->base_url; ?>vote-api/topg/<?php echo $votereward_config['api_key']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="control-group"
                                     id="top100arena" <?php if(isset($link_data['api']) && $link_data['api'] != 5){
                                    echo 'style="display:none;"';
                                } ?>>
                                    <div class="controls">
                                        <?php if(!isset($votereward_config['api_key']) || $votereward_config['api_key'] == ''): ?>
                                            Please access <a
                                                    href="<?php echo $this->config->base_url; ?>admincp/manage-settings/votereward">votereward
                                                settings page</a> atleast once to generate api key and then come back.
                                        <?php else: ?>
                                            <b>Do not share this url to 3rd parties!</b><br/>
                                            Postback url: <span
                                                    style="color:red;"><?php echo $this->config->base_url; ?>vote-api/top100arena/<?php echo $votereward_config['api_key']; ?>?postback=</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="control-group"
                                     id="mmoserver" <?php if(isset($link_data['api']) && $link_data['api'] != 6){
                                    echo 'style="display:none;"';
                                } ?>>
                                    <div class="controls">
                                        <?php if(!isset($votereward_config['api_key']) || $votereward_config['api_key'] == ''): ?>
                                            Please access <a
                                                    href="<?php echo $this->config->base_url; ?>admincp/manage-settings/votereward">votereward
                                                settings page</a> atleast once to generate api key and then come back.
                                        <?php else: ?>
                                            <b>Do not share this url to 3rd parties!</b><br/>
                                            Postback url: <span
                                                    style="color:red;"><?php echo $this->config->base_url; ?>vote-api/mmoserver/<?php echo $votereward_config['api_key']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="control-group"
                                     id="ultratop" <?php if(isset($link_data['api']) && $link_data['api'] != 8){
                                    echo 'style="display:none;"';
                                } ?>>
                                    <div class="controls">
                                        <?php if(!isset($votereward_config['api_key']) || $votereward_config['api_key'] == ''): ?>
                                            Please access <a
                                                    href="<?php echo $this->config->base_url; ?>admincp/manage-settings/votereward">votereward
                                                settings page</a> atleast once to generate api key and then come back.
                                        <?php else: ?>
                                            <b>Do not share this url to 3rd parties!</b><br/>
                                            Postback url: <span
                                                    style="color:red;"><?php echo $this->config->base_url; ?>vote-api/ultratop/<?php echo $votereward_config['api_key']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="control-group"
                                     id="muservertop" <?php if(isset($link_data['api']) && $link_data['api'] != 7){
                                    echo 'style="display:none;"';
                                } ?>>
                                    <div class="controls">Under construction</div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="server">Server <span
                                                style="color:red;">*</span></label>

                                    <div class="controls">
                                        <select id="server" name="server">
                                            <option value="">Select</option>
                                            <?php
                                                foreach($this->website->server_list() as $key => $value){
                                                    if($link_data['server'] == $key){
                                                        echo '<option value="' . $key . '" selected="selected">' . $value['title'] . "</option>\n";
                                                    } else{
                                                        echo '<option value="' . $key . '">' . $value['title'] . "</option>\n";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </fieldset>
                        </form>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2>Vote Links</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php
                    if(count($vote_links) > 0){
                        echo '<table class="table">
							  <thead>
								  <tr>
									  <th>Name</th>
									  <th>Url</th>
									  <th>Reward</th>
									  <th>Server</th>
									  <th>Action</th>                                        
								  </tr>
							  </thead>   
							  <tbody>';
                        foreach($vote_links as $key => $value){
                            echo '<tr>
									<td>' . htmlspecialchars($value['name']) . '</td>
									<td>' . htmlspecialchars($value['votelink']) . '</td>
									<td class="center">' . $value['reward'] . '</td>
									<td class="center">' . $this->website->get_title_from_server($value['server']) . '</td>
									<td class="center">
										<a class="btn btn-info" href="' . $this->config->base_url . 'admincp/edit-vote/' . $value['id'] . '">
											<i class="icon-edit icon-white"></i>  
											Edit                                            
										</a>
										<a class="btn btn-danger" href="' . $this->config->base_url . 'admincp/delete-vote/' . $value['id'] . '">
											<i class="icon-trash icon-white"></i> 
											Delete
										</a>
									</td>  
								  </tr>';
                        }
                        echo '</tbody></table>';
                    } else{
                        echo '<div class="alert alert-info">No voting links found</div>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>