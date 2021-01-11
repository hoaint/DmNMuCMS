<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/character-manager">Character Manager</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2>Search Character</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php
                    if(isset($error)){
                        echo '<div class="alert alert-error">' . $error . '</div>';
                    }
                    if(isset($success)){
                        echo '<div class="alert alert-success">' . $success . '</div>';
                    }
                ?>
                <form class="form-horizontal" method="post"
                      action="<?php echo $this->config->base_url; ?>admincp/character-manager">
                    <fieldset>
                        <legend></legend>
                        <div class="control-group">
                            <label class="control-label" for="account">Char Name <span
                                        style="color:red;">*</span></label>

                            <div class="controls">
                                <input type="text" class="input-xlarge" name="character" id="character"
                                       value="<?php if(isset($_POST['character'])): echo $_POST['character']; endif; ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="server">Server <span style="color:red;">*</span></label>

                            <div class="controls">
                                <select id="server" name="server">
                                    <option value="">Select</option>
                                    <?php
                                        foreach($this->website->server_list() as $key => $value){
                                            if(isset($_POST['server']) && $_POST['server'] == $key){
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
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2>Char List</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php if(!isset($_POST['character'])): ?>
                    <form class="form-horizontal" method="POST" action="">
                        <label class="control-label" for="switch_server_file">Server</label>

                        <div class="controls">
                            <select name="switch_server_file" id="switch_server_file"
                                    onchange="javascript:location.href = '<?php echo $current_page; ?>/'+this.value;">
                                <option value="">Select</option>
                                <?php foreach($servers as $key => $server): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $servers[$key]['title']; ?></option>
                                <?php endforeach; ?>
                            </select>

                            <p class="help-block">Select server from which to load characters.</p>
                        </div>
                    </form>
                <?php endif; ?>
                <?php
                    if(count($char_list) > 0){
                        echo '<table class="table">
							  <thead>
								  <tr>
									  <th>Name</th>
									  <th>Account</th>
									  <th>Action</th>                                        
								  </tr>
							  </thead>   
							  <tbody>';
                        foreach($char_list as $key => $value){
                            echo '<tr>
									<td>' . htmlspecialchars($value['name']) . '</td>
									<td>' . htmlspecialchars($value['account']) . '</td>
									<td class="center">
										<a class="btn btn-success" href="' . $this->config->base_url . 'admincp/edit-character/' . $value['id'] . '/' . $serv . '">
											<i class="icon-edit icon-white"></i>  
											Edit                                            
										</a>
										<a class="btn btn-primary" href="' . $this->config->base_url . 'admincp/ban-character/' . $value['id'] . '/' . $serv . '">
											<i class="icon-remove icon-white"></i> 
											Ban
										</a>
									</td>  
								  </tr>';
                        }
                        echo '</tbody></table>';
                        if(isset($pagination)):
                            echo '<div style="padding:10px;text-align:center;">
								<table style="width: 100%;"><tr><td>' . $pagination . '</td></tr></table>
							</div>';
                        endif;
                    } else{
                        echo '<div class="alert alert-info">No characters found</div>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>