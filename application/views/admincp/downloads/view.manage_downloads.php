<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-downloads">Manage Downloads</a> <span
                        class="divider"></li>
        </ul>
    </div>

    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-edit"></i>Add Download Links</h2>
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
                      action="<?php echo $this->config->base_url; ?>admincp/manage-downloads">
                    <fieldset>
                        <legend></legend>
                        <div class="control-group">
                            <label class="control-label" for="link_name">File Title</label>

                            <div class="controls">
                                <input type="text" class="input-xlarge" name="link_name" id="link_name" value=""/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="link_desc">File Description</label>

                            <div class="controls">
                                <input type="text" class="input-xlarge" id="link_desc" name="link_desc" value=""/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="link_size">File Size</label>

                            <div class="controls">
                                <input type="text" class="input-xlarge" id="link_size" name="link_size" value=""/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="link_type">File Type</label>

                            <div class="controls">
                                <select id="link_type" name="link_type">
                                    <option value="Installer">Installer</option>
                                    <option value="Archive">Archive</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="link_url">File Url</label>

                            <div class="controls">
                                <input type="text" class="input-xlarge" id="link_url" name="link_url" value="http://"/>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2>File List</h2>
            </div>
            <div class="box-content">
                <?php
                    if(isset($files['error'])){
                        echo '<div class="alert alert-info">' . $files['error'] . '</div>';
                    } else{
                        echo '<table class="table table-striped table-bordered bootstrap-datatable datatable" id="downloads_sortable">
						  <thead>
							  <tr>
								  <th>File Name</th>
								  <th>File Url</th>
								  <th>File Type</th>
								  <th>Action</th>                                        
							  </tr>
						  </thead>   
						  <tbody id="downloads_sortable_content" style="cursor: move;">';
                        foreach($files as $key => $value){
                            echo '<tr id="' . $value['id'] . '">
								<td>' . htmlspecialchars($value['link_name']) . '</td>
								<td>' . htmlspecialchars($value['link_url']) . '</td>
								<td>' . htmlspecialchars($value['link_type']) . '</td>
								<td class="center">
									<a class="btn btn-info" href="' . $this->config->base_url . 'admincp/edit-download/' . $value['id'] . '">
										<i class="icon-edit icon-white"></i> 
										Edit
									</a>
									<a class="btn btn-danger" href="' . $this->config->base_url . 'admincp/delete-file/' . $value['id'] . '">
										<i class="icon-trash icon-white"></i> 
										Delete
									</a>
								</td>  
							  </tr>';
                        }
                        echo '</tbody></table>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>