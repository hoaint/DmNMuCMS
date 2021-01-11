<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>gmcp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>gmcp/search">Search Account Or Char</a></li>
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
            ?>
            <div class="row-fluid">
                <div class="box span12">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Find Character Account</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="">
                            <fieldset>
                                <legend>Search And View Character Or Account</legend>
                                <div class="control-group">
                                    <label class="control-label" for="name">Name </label>

                                    <div class="controls">
                                        <input type="text" class="span6 typeahead" id="name" name="name" value=""/>

                                        <p class="help-block">Enter character name.</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="type">Type </label>

                                    <div class="controls">
                                        <select name="type" id="type">
                                            <option value="1">Account</option>
                                            <option value="2">Character</option>
                                        </select>

                                        <p class="help-block">Select search type.</p>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary" name="search_acc">Search</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="box span12">
                    <div class="box-header well" data-original-title>
                        <h2>Found Account List</h2>

                    </div>
                    <div class="box-content">
                        <?php
                            if(isset($acc_not_found)){
                                echo '<div class="alert alert-info">' . $acc_not_found . '</div>';
                            } else{
                                if(isset($account)){
                                    echo '<table class="table">
							  <thead>
								  <tr>
									  <th>Account</th>
									  <th>Char</th>
									  <th>IP</th>
									  <th>Action</th>                                        
								  </tr>
							  </thead>   
							  <tbody>';
                                    echo '<tr>
											<td>' . htmlspecialchars($account['AccountId']) . '</td>
											<td>
											' . htmlspecialchars($account['Name']) . '
											<a class="btn btn-info" href="' . $this->config->base_url . 'gmcp/ban/char/' . $account['Name'] . '">
												<i class="icon-edit icon-white"></i>  
												Ban ' . htmlspecialchars($account['Name']) . '
											</a>
											</td>
											<td>' . $ip . '</td>
											<td class="center">
												<a class="btn btn-info" href="' . $this->config->base_url . 'gmcp/ban/account/' . $account['AccountId'] . '">
													<i class="icon-edit icon-white"></i>  
													Ban Account                                           
												</a>
											</td>  
										  </tr>';
                                    echo '</tbody></table>';
                                } else{
                                    echo '<div class="alert alert-info">Enter text for search</div>';
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
    ?>
</div>