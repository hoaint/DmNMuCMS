<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/buylevel">BuyLevel Settings</a>
            </li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Add Level Settings</h2>
            </div>
            <div class="box-content">
                <?php
                    if(isset($not_found)){
                        echo '<div class="alert alert-error">' . $not_found . '</div>';
                    } else{
                        if(isset($error)){
                            if(is_array($error)){
                                foreach($error AS $note){
                                    echo '<div class="alert alert-error">' . $note . '</div>';
                                }
                            } else{
                                echo '<div class="alert alert-error">' . $error . '</div>';
                            }
                        }
                        if(isset($success)){
                            echo '<div class="alert alert-success">' . $success . '</div>';
                        }
                        ?>
                        <form class="form-horizontal" method="POST" action="">
                            <fieldset>
                                <legend></legend>
                                <div class="control-group">
                                    <label class="control-label" for="server">Server</label>

                                    <div class="controls">
                                        <select name="server" id="server">
                                            <?php foreach($servers as $key => $server): ?>
                                                <option value="<?php echo $key; ?>"
                                                        <?php if(isset($_POST['server']) && $key == $_POST['server']){ ?>selected="selected"<?php } ?>><?php echo $servers[$key]['title']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="level">Level</label>
                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="level" name="level"
                                               value="<?php if(isset($_POST['level'])){
                                                   echo $_POST['level'];
                                               } ?>" placeholder="400"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="price">Price</label>
                                    <div class="controls">
                                        <input type="text" class="span3 typeahead" id="price" name="price"
                                               value="<?php if(isset($_POST['price'])){
                                                   echo $_POST['price'];
                                               } ?>" placeholder="9999"/>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="payment_type">Payment Method <span
                                                style="color:red;">*</span></label>
                                    <div class="controls">
                                        <select id="payment_type" name="payment_type" required>
                                            <option value="1" <?php if(isset($_POST['payment_type']) && $_POST['payment_type'] == 1): echo 'selected="selected"'; endif; ?>>
                                                Credits 1
                                            </option>
                                            <option value="2 "<?php if(isset($_POST['payment_type']) && $_POST['payment_type'] == 2): echo 'selected="selected"'; endif; ?>>
                                                Credits 2
                                            </option>
                                        </select>
                                        <p class="help-block">Which donation points will be used for payment.</p>
                                        <p>For reward types check your credits settings <a
                                                    href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                                    target="_blank">here</a></p>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary" name="add_buylevel_settings">Add settings
                                    </button>
                                    <button type="reset" class="btn">Cancel</button>
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