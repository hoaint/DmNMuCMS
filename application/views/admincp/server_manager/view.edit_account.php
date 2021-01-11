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
                <h2>Edit Account: <?php if($account_data != false){
                        echo $account_data['memb___id'];
                    }; ?></h2>

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
                    if($account_data == false){
                        echo '<div class="alert alert-error">Account not found.</div>';
                    } else{
                        ?>
                        <form class="form-horizontal" method="post" action="">
                            <fieldset>
                                <legend></legend>
                                <div class="control-group">
                                    <label class="control-label" for="password">Password </label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="password" id="password"
                                               value="<?php if(MD5 == 0){
                                                   echo $account_data['memb__pwd'];
                                               } ?>"/>

                                        <p>Account password. Leave blank if password is encrypted with md5 and don't want to
                                            change it.</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="email">Email </label>

                                    <div class="controls">
                                        <input type="email" class="input-xlarge" name="email" id="email"
                                               value="<?php echo $account_data['mail_addr']; ?>"/>

                                        <p>Account email address</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="sno_numb">Personal ID </label>

                                    <div class="controls">
                                        <input type="text" class="input-xlarge" name="sno_numb" id="sno_numb"
                                               value="<?php echo $account_data['sno__numb']; ?>"/>

                                        <p>Account personal id number</p>
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
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2>Account Characters</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Character</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        if(!empty($char_list)){
                            foreach($char_list as $key => $value){
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($value['Name']); ?></td>
                                    <td class="center">
                                        <a class="btn btn-success"
                                           href="<?php echo $this->config->base_url; ?>admincp/edit-character/<?php echo htmlspecialchars($value['id']); ?>">
                                            <i class="icon-edit icon-white"></i> Edit Character </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else{
                            echo '<tr><td colspan="3"><div class="alert alert-info">No characters found</div></td></tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2>Account IP Log</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <table class="table">
                    <thead>
                    <tr>
                        <th>IP</th>
                        <th>Last Connected</th>
                        <th>Login Type</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                        if(!empty($ip_logs)){
                            foreach($ip_logs as $key => $value){
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($value['ip']); ?></td>
                                    <td><?php echo htmlspecialchars($value['last_connected']); ?></td>
                                    <td><?php echo ($value['login_type'] == 1) ? 'Website' : 'Server'; ?></td>
                                    <td class="center">
                                        <a class="btn btn-success"
                                           href="<?php echo $this->config->base_url; ?>admincp/search-ip/<?php echo htmlspecialchars($value['ip']); ?>">
                                            <i class="icon-edit icon-white"></i> Search IP </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else{
                            echo '<tr><td colspan="4"><div class="alert alert-info">No ip logs found</div></td></tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>