<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/bulk-mail">Bulk Mail</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-edit"></i>Email Details</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <div class="box-content alerts">
                    <div class="alert alert-info ">
                        <p>Available tags:
                        <ul>
                            <li>{memb___id} - Recipient Username</li>
                            <li>{server_name} - Server Name</li>
                            <li>{site_url} - Link To Website: <?php echo $this->config->base_url; ?></li>
                        </ul>
                        </p>
                    </div>
                </div>
                <?php
                    if(isset($error)){
                        echo '<div class="alert alert-error">' . $error . '</div>';
                    }
                    if(isset($success)){
                        echo '<div class="alert alert-success">' . $success . '</div>';
                    }
                ?>
                <form class="form-horizontal" method="post" action="">
                    <input type="hidden" name="edit_bulk_email" value="1"/>
                    <fieldset>
                        <legend></legend>
                        <div class="control-group">
                            <label class="control-label" for="subject">Subject</label>
                            <div class="controls">
                                <input type="text" class="input-large" name="new_subject" id="new_subject"
                                       value="<?php echo $email_data['subject']; ?>" required/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="server">Server</label>
                            <div class="controls">
                                <select id="server" name="server[]" multiple data-rel="chosen">
                                    <?php
                                        $servers = unserialize($email_data['server']);
                                        foreach($this->website->server_list() as $key => $value){
                                            $selected = in_array($key, $servers) ? 'selected="selected"' : '';
                                            echo '<option value="' . $key . '" ' . $selected . '>' . $value['title'] . "</option>\n";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="body">Body</label>
                            <div class="controls">
                                <textarea class="cleditor" id="body" name="body"
                                          rows="4"><?php echo $email_data['body']; ?></textarea>
                            </div>
                        </div>
                        <!--<div class="control-group">
							<label class="control-label" for="selectError1">Exclude</label>
							<div class="controls">
							  <select id="exclude_list" name="exclude_list[]" multiple data-rel="chosen">
								<?php
                            $excludes = unserialize($email_data['exclude']);
                            echo '<option value="banned"';
                            if(in_array('banned', $excludes)){
                                echo 'selected="selected"';
                            }
                            echo '>Banned Accounts</option>';
                            echo '<option value="gms"';
                            if(in_array('gms', $excludes)){
                                echo 'selected="selected"';
                            }
                            echo '>GM Accounts</option>';
                            echo '<option value="vip"';
                            if(in_array('vip', $excludes)){
                                echo 'selected="selected"';
                            }
                            echo '>Vip Accounts</option>';
                            echo '<option value="inactive"';
                            if(in_array('inactive', $excludes)){
                                echo 'selected="selected"';
                            }
                            echo '>Not Validated  Accounts</option>';
                        ?>
							  </select>
							</div>
						</div>-->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var editorName = $('.cleditor').attr('name');
            CKEDITOR.replace(editorName);
        });
    </script>
</div>