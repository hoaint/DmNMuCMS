<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/search-ip">Search IP</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2>Search IP</h2>

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
                      action="<?php echo $this->config->base_url; ?>admincp/search-ip">
                    <fieldset>
                        <legend></legend>
                        <div class="control-group">
                            <label class="control-label" for="account">IP Address <span
                                        style="color:red;">*</span></label>

                            <div class="controls">
                                <input type="text" class="input-xlarge" name="ip" id="ip" value=""/>
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
                <h2>IP Log</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php
                    if(isset($ip_log) && count($ip_log) > 0){
                        echo '<table class="table">
						  <thead>
							  <tr>
								  <th>Username</th>
								  <th>Last Connected</th>
								  <th>Login Type</th>       
							  </tr>
						  </thead>   
						  <tbody>';
                        foreach($ip_log as $key => $value){
                            $l_type = ($value['login_type'] == 1) ? 'Website' : 'Server';
                            echo '<tr>
								<td>' . htmlspecialchars($value['account']) . '</td>
								<td>' . $value['last_connected'] . '</td>
								<td>' . $l_type . '</td>
							  </tr>';
                        }
                        echo '</tbody></table>';
                    } else{
                        echo '<div class="alert alert-info">No records found</div>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>