<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/support-departments">Support Departments</a></li>
        </ul>
    </div>
    <?php
        if(isset($error)){
            echo '<div class="alert alert-error span12">' . $error . '</div>';
        }
        if(isset($success)){
            echo '<div class="alert alert-success span12">' . $success . '</div>';
        }
    ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Add Departments</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="">
                    <fieldset>
                        <legend></legend>
                        <div class="control-group">
                            <label class="control-label" for="title">Department Name <span
                                        style="color:red;">*</span></label>

                            <div class="controls">
                                <input type="text" class="typeahead" id="title" name="title"
                                       value="<?php if(isset($_POST['title'])){
                                           echo $_POST['title'];
                                       } ?>"/>
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
                        <div class="control-group">
                            <label class="control-label" for="pay_per_incident">Pay Per Incident </label>

                            <div class="controls">
                                <input type="text" class="typeahead" id="pay_per_incident" name="pay_per_incident"
                                       value="<?php if(isset($_POST['pay_per_incident'])){
                                           echo $_POST['pay_per_incident'];
                                       } else{
                                           echo 0;
                                       } ?>"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="payment_type">Payment Type </label>

                            <div class="controls">
                                <select id="payment_type" name="payment_type">
                                    <option
                                            value="1" <?php if(isset($_POST['payment_type']) && $_POST['payment_type'] == 1){
                                        echo 'selected="selected"';
                                    } ?>>Credits 1
                                    </option>
                                    <option
                                            value="2" <?php if(isset($_POST['payment_type']) && $_POST['payment_type'] == 2){
                                        echo 'selected="selected"';
                                    } ?>>Credits 2
                                    </option>
                                </select>

                                <p class="help-block">For payment types check your credits settings <a
                                            href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                            target="_blank">here</a></p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="status">Status </label>

                            <div class="controls">
                                <select id="status" name="status">
                                    <option value="1" <?php if(isset($_POST['status']) && $_POST['status'] == 1){
                                        echo 'selected="selected"';
                                    } ?>>Active
                                    </option>
                                    <option value="0" <?php if(isset($_POST['status']) && $_POST['status'] == 0){
                                        echo 'selected="selected"';
                                    } ?>>Inactive
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" name="add_department" class="btn btn-primary">Add Department</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>