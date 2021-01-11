<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/item-list">Edit Items</a></li>
        </ul>
    </div>
    <?php
        if(isset($load_error)){
            echo '<div class="alert alert-error span9">' . $load_error . '</div>';
        } else{
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
                        <h2><i class="icon-edit"></i> Set Custom Price</h2>
                    </div>
                    <div class="box-content">
                        <div class="control-group">
                            <form class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
                                <fieldset>
                                    <legend>Edit Item Price.</legend>
                                    <div class="control-group">
                                        <label class="control-label" for="name">Item Name </label>

                                        <div class="controls">
                                            <input type="text" class="typeahead" id="name" name="name"
                                                   value="<?php echo $info['name']; ?>" readonly="readonly"/>
                                        </div>
                                    </div>
                                    <?php
                                        $price = [];
                                        foreach($this->website->server_list() as $key => $value):
                                            if($price_info != false){
                                                if(array_key_exists($key, $price_info)){
                                                    $price[$key] = $price_info[$key];
                                                } else{
                                                    $price[$key] = 0;
                                                }
                                            } else{
                                                $price[$key] = 0;
                                            }
                                            ?>
                                            <div class="control-group">
                                                <label class="control-label" for="price_<?php echo $key; ?>">Price
                                                    On <?php echo $value['title']; ?> Server</label>

                                                <div class="controls">
                                                    <input type="text" class="typeahead" id="prices"
                                                           name="prices[<?php echo $key; ?>]"
                                                           value="<?php echo $price[$key]; ?>"/>

                                                    <p class="help-block">Item Price On <?php echo $value['title']; ?>
                                                        Server.</p>
                                                </div>
                                            </div>
                                        <?php
                                        endforeach;
                                    ?>
                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary" name="set_item_price">Set Price
                                        </button>
                                        <button type="reset" class="btn">Cancel</button>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    ?>
</div>
</div>