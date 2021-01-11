<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/account-manager">Account Manager</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2>Ban Manager</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php
                    if(isset($not_allowed)){
                        echo '<div class="alert alert-error">' . $not_allowed . '</div>';
                    } else{
                        if(isset($error)){
                            echo '<div class="alert alert-error">' . $error . '</div>';
                        }
                        if(isset($success)){
                            echo '<div class="alert alert-success">' . $success . '</div>';
                        }
                        ?>
                        <form class="form-horizontal" method="POST" action="">
                            <fieldset>
                                <legend>Ban Account</legend>
                                <div class="control-group">
                                    <label class="control-label" for="name">Name </label>

                                    <div class="controls">
                                        <input type="text" class="span6 typeahead" id="name" name="name"
                                               value="<?php echo $name; ?>" readonly="readonly"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="ban_acc">Permanent ban </label>

                                    <div class="controls">
                                        <label class="checkbox inline">
                                            <input type="checkbox" id="permanent_ban" name="permanent_ban" value="1">
                                        </label>

                                        <p class="help-block">Ban will never expire </p>
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
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2>Account Ban List</h2>
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
									<a class="btn btn-info" href="' . $this->config->base_url . 'admincp/unban/' . strtolower($value['type']) . '/' . $value['name'] . '/' . $server . '">
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
</div>