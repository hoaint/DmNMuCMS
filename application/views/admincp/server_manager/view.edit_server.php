<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/server-list-manager">Server List Manager</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="box span9">
            <div class="box-header well" data-original-title>
                <h2>Edit Server</h2>

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
                    if(isset($not_found)){
                        echo '<div class="alert alert-error">' . $not_found . '</div>';
                    } else{
                        ?>
                        <form class="form-horizontal" method="post" action="">
                            <fieldset>
                                <legend></legend>
                                <div class="control-group">
                                    <label class="control-label" for="key">Server Key</label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="key" id="key"
                                               value="<?php if(isset($key)): echo $key; endif; ?>" readonly/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="title">Server Title <span
                                                style="color:red;">*</span></label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="title" id="title"
                                               value="<?php if(isset($data['title'])): echo $data['title']; endif; ?>"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="char_db">Character DB <span
                                                style="color:red;">*</span></label>

                                    <div class="controls">
                                        <select id="char_db" name="char_db">
                                            <option value="">Select</option>
                                            <?php
                                                foreach($databases as $value){
                                                    if(isset($data['db']) && $data['db'] == $value['name']){
                                                        echo '<option value="' . $value['name'] . '" selected="selected">' . $value['name'] . "</option>\n";
                                                    } else{
                                                        echo '<option value="' . $value['name'] . '">' . $value['name'] . "</option>\n";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="account_db">Account DB <span
                                                style="color:red;">*</span></label>

                                    <div class="controls">
                                        <select id="account_db" name="account_db">
                                            <option value="">Select</option>
                                            <?php
                                                foreach($databases as $value){
                                                    if(isset($data['db_acc']) && $data['db_acc'] == $value['name']){
                                                        echo '<option value="' . $value['name'] . '" selected="selected">' . $value['name'] . "</option>\n";
                                                    } else{
                                                        echo '<option value="' . $value['name'] . '">' . $value['name'] . "</option>\n";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="gs_ip">GameServer IP <span
                                                style="color:red;">*</span></label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="gs_ip" id="gs_ip"
                                               value="<?php if(isset($data['gs_ip'])): echo $data['gs_ip']; endif; ?>"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="gs_port">GameServer Port <span
                                                style="color:red;">*</span></label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="gs_port" id="gs_port"
                                               value="<?php if(isset($data['gs_port'])): echo $data['gs_port']; endif; ?>"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="gs_names">GameServer Names</label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" data-role="tagsinput" name="gs_names"
                                               id="gs_names"
                                               value="<?php if(isset($data['gs_list'])): echo $data['gs_list']; endif; ?>"/>

                                        <p>Can be located in ServerInfo.dat or GameServer.ini</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="max_players">Max Players</label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="max_players" id="max_players"
                                               value="<?php if(isset($data['max_players'])): echo $data['max_players']; endif; ?>"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="version">Version</label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="version" id="version"
                                               value="<?php if(isset($data['version'])): echo $data['version']; endif; ?>"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="exp">Experience</label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="exp" id="exp"
                                               value="<?php if(isset($data['exp'])): echo $data['exp']; endif; ?>"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="drop">Drop</label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="drop" id="drop"
                                               value="<?php if(isset($data['drop'])): echo $data['drop']; endif; ?>"/>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" name="edit_server" class="btn btn-primary">Edit</button>
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