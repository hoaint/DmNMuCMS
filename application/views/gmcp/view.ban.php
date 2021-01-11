<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>gmcp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>gmcp/ban">Ban Manager</a></li>
        </ul>
    </div>
    <?php
        if(isset($not_allowed)){
            echo '<div class="alert alert-error span9">' . $not_allowed . '</div>';
        } else{
            if(isset($error)){
                echo '<div class="alert alert-error span9">' . $error . '</div>';
            }
            if(isset($success)){
                echo '<div class="alert alert-success span9">' . $success . '</div>';
            }
            $args = $this->request->get_args();
            if(!empty($args[0]) && !empty($args[1])){
                $name = $args[1];
                $type = $args[0];
            } else{
                $name = '';
                $type = 'account';
            }
            ?>
            <div class="row-fluid">
                <div class="box span12">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Ban Manager</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="">
                            <fieldset>
                                <legend>Ban Character Or Account</legend>
                                <div class="control-group">
                                    <label class="control-label" for="name">Name </label>

                                    <div class="controls">
                                        <input type="text" class="span6 typeahead" id="name" name="name"
                                               value="<?php echo $name; ?>"/>

                                        <p class="help-block">Enter character or account name.</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="type">Type </label>

                                    <div class="controls">
                                        <select name="type" id="type">
                                            <option value="1"
                                                    <?php if(isset($_POST['type']) && $_POST['type'] == 'account'){ ?>selected="selected"<?php } ?>>
                                                Account
                                            </option>
                                            <option value="2"
                                                    <?php if(isset($_POST['type']) && $_POST['type'] == 'char'){ ?>selected="selected"<?php } ?>>
                                                Character
                                            </option>
                                        </select>

                                        <p class="help-block">Select ban type.</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="ban_acc">Permanent ban </label>

                                    <div class="controls">
                                        <label class="checkbox inline">
                                            <input type="checkbox" id="permanent_ban" name="permanent_ban" value="1"
                                                   data-no-uniform="true">
                                        </label>

                                        <p class="help-block">Ban will never expire </p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="ban_hwid">HWID ban </label>

                                    <div class="controls">
                                        <label class="checkbox inline">
                                            <input type="checkbox" id="ban_hwid" name="ban_hwid" value="1"
                                                   data-no-uniform="true">
                                        </label>

                                        <p class="help-block">Ban users harware id (ONLY IGCN) </p>
                                        <p class="help-block">Works Only if user has been in game atleast once</p>
                                    </div>
                                </div>
                                <div class="control-group" id="ban_time">
                                    <label class="control-label" for="time">Time </label>

                                    <div class="controls">
                                        <input type="text" class="span6 typeahead datetimepicker" id="time" name="time"
                                               value=""/>

                                        <p class="help-block">Enter ban time.</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="name">Reason </label>

                                    <div class="controls">
                                        <input type="text" class="span6 typeahead" id="reason" name="reason" value=""/>

                                        <p class="help-block">Enter ban reason.</p>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary" name="ban">Ban</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="box span12">
                    <div class="box-header well" data-original-title>
                        <h2>Ban List</h2>
                    </div>
                    <div class="box-content">
                        <?php
                            echo '<table class="table">
						  <thead>
							  <tr>
								  <th>Name</th>
								  <th>Type</th>
								  <th>Time Until Ban Ends</th>
								  <th>Action</th>                                        
							  </tr>
						  </thead>   
						  <tbody>';
                            foreach($ban_list as $key => $value){
                                echo '<tr>
								<td>' . $value['name'] . '</td>
								<td class="center">' . $value['type'] . '</td>
								<td class="center">' . $value['time'] . '</td>
								<td class="center">
									<a class="btn btn-info" href="' . $this->config->base_url . 'gmcp/unban/' . strtolower($value['type']) . '/' . $value['name'] . '">
										<i class="icon-edit icon-white"></i>  
										Unban                                           
									</a>
								</td>  
							  </tr>';
                            }
                            echo '</tbody></table>';
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
    ?>
</div>