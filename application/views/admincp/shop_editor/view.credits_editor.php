<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/credits-editor">Credits Editor</a></li>
        </ul>
    </div>
    <?php
        if(isset($error)){
            if(is_array($error)){
                echo '<div class="alert alert-error span9">';
                foreach($error as $err){
                    echo $err . '<br />';
                }
                echo '</div>';
            } else{
                echo '<div class="alert alert-error span9">' . $error . '</div>';
            }
        }
        if(isset($success)){
            echo '<div class="alert alert-success span9">' . $success . '</div>';
        }
    ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Edit User Credits</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="">
                    <fieldset>
                        <legend>Find user and edit credits.</legend>
                        <div class="control-group">
                            <label class="control-label" for="username">Username </label>

                            <div class="controls">
                                <input type="text" class="typeahead" id="username" name="username"
                                       value="<?php if($acc != ''){
                                           echo $acc;
                                       } ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="server">Server </label>

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
                        <div class="control-group">
                            <label class="control-label" for="c_type">Credit Type </label>

                            <div class="controls">
                                <select id="c_type" name="c_type">
                                    <option value="">Select</option>
                                    <option value="1" <?php if(isset($_POST['c_type']) && $_POST['c_type'] == 1){
                                        echo 'selected="selected"';
                                    } ?>>Credits 1
                                    </option>
                                    <option value="2" <?php if(isset($_POST['c_type']) && $_POST['c_type'] == 2){
                                        echo 'selected="selected"';
                                    } ?>>Credits 2
                                    </option>
                                    <option value="3" <?php if(isset($_POST['c_type']) && $_POST['c_type'] == 3){
                                        echo 'selected="selected"';
                                    } ?>>Credits 3
                                    </option>
                                </select>

                                <p>For reward types check your credits settings <a
                                            href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                            target="_blank">here</a></p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="act">Action </label>

                            <div class="controls">
                                <select id="act" name="act">
                                    <option value="1" <?php if(isset($_POST['act']) && $_POST['c_type'] == 1){
                                        echo 'selected="selected"';
                                    } ?>>Add
                                    </option>
                                    <option value="2" <?php if(isset($_POST['act']) && $_POST['c_type'] == 2){
                                        echo 'selected="selected"';
                                    } ?>>Remove
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="amount">Amount </label>

                            <div class="controls">
                                <input type="text" class="typeahead" id="amount" name="amount"
                                       value="<?php if(isset($_POST['amount'])){
                                           echo $_POST['amount'];
                                       } ?>"/>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" name="edit_credits">Edit Credits</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <?php if($found): ?>
        <div class="row-fluid">
            <div class="box span12">
                <div class="box-header well">
                    <h2><i class="icon-edit"></i> Similar Accounts</h2>
                </div>
                <div class="box-content">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Account</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($similar_accounts as $key => $value): ?>
                            <tr>
                                <td><?php echo $similar_accounts[$key]['memb___id']; ?></td>
                                <td><a class="btn btn-success"
                                       href="<?php echo $this->config->base_url; ?>admincp/credits-editor/<?php echo $similar_accounts[$key]['memb___id']; ?>"><i
                                                class="icon-edit icon-white"></i> Add Credits</a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <br/>
    <?php
        if(isset($error2)){
            if(is_array($error2)){
                echo '<div class="alert alert-error span9">';
                foreach($error2 as $err){
                    echo $err . '<br />';
                }
                echo '</div>';
            } else{
                echo '<div class="alert alert-error span9">' . $error2 . '</div>';
            }
        }
    ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> View User Credits</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="">
                    <fieldset>
                        <legend>Find user and view credits.</legend>
                        <div class="control-group">
                            <label class="control-label" for="username">Username </label>

                            <div class="controls">
                                <input type="text" class="typeahead" id="username" name="username"
                                       value="<?php if($acc != ''){
                                           echo $acc;
                                       } ?>"/>
                            </div>
                        </div>
                        <label class="control-label" for="server">Server </label>

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
                <button type="submit" class="btn btn-primary" name="view_credits">View Credits</button>
            </div>
            </fieldset>
            </form>
        </div>
    </div>
    <?php if($found_acc_credits && !isset($error2)): ?>

        <div class="row-fluid">
            <div class="box span12">
                <div class="box-header well">
                    <h2><i class="icon-edit"></i> Account credits details</h2>
                </div>
                <div class="box-content">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Server</th>
                            <th>Credits 1</th>
                            <th>Credits 2</th>
                            <th>Credits 3</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?php echo $this->website->get_title_from_server($credits_info['server']); ?></td>
                            <td><?php echo $credits_info['credits']['credits']; ?></td>
                            <td><?php echo $credits_info['credits2']['credits']; ?></td>
                            <td><?php echo $this->website->zen_format($credits_info['credits3']['credits']); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

</div>