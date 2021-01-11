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
                <h2>Edit Vip: <?php echo $account; ?></h2>

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
                    if($account == ''){
                        echo '<div class="alert alert-error">Account not found.</div>';
                    } else{
                        ?>
                        <form class="form-horizontal" method="post" action="">
                            <fieldset>
                                <legend></legend>
								<div class="control-group">
                                    <label class="control-label" for="vip_package">Vip Package</label>
                                    <div class="controls">
                                        <select id="vip_package" name="vip_package">
                                            <option value="0">None</option>
                                            <?php
												
                                                foreach($vip_packages as $key => $value){
													$selected = '';
													if($existing_vip != NULL && $existing_vip['viptype'] == $value['id']){
														$selected = 'selected';
													}
													echo '<option value="' . $value['id'] . '" '.$selected.'>' . $value['package_title'] . "</option>\n";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="vip_time">Vip Time </label>
                                    <div class="controls">
										<input type="text" class="input-xlarge datetimepicker" id="vip_time" name="vip_time" value="<?php if($existing_vip != NULL){ echo date('Y/m/d H:i', $existing_vip['viptime']); } ?>"/>
                                        <p class="help-block">Enter vip time.</p>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Edit</button>
                                </div>
                            </fieldset>
                        </form>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>