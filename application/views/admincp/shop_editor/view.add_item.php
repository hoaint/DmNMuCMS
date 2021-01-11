<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/add-item">Add Item</a></li>
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
                <h2><i class="icon-edit"></i> Add Shop Item</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
                    <fieldset>
                        <legend>Add new shop item.</legend>
                        <div class="control-group">
                            <label class="control-label" for="item_id">Item Id </label>

                            <div class="controls">
                                <input type="text" class="typeahead" id="item_id" name="item_id" value=""/>

                                <p class="help-block">Your new item id. Can be found in item.txt.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="name">Item Name </label>

                            <div class="controls">
                                <input type="text" class="typeahead" id="name" name="name" value=""/>

                                <p class="help-block">Your new item name. Can be found in item.txt.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="item_cat">Category</label>

                            <div class="controls">
                                <select id="item_cat" name="item_cat">
                                    <option value="">Select</option>
                                    <?php echo $this->webshop->load_cat_list(true); ?>
                                </select>

                                <p class="help-block">Item category.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="original_item_cat">Original Category</label>

                            <div class="controls">
                                <select id="original_item_cat" name="original_item_cat">
                                    <option value="">Select</option>
                                    <?php echo $this->webshop->load_cat_list(true, '', true); ?>
                                </select>

                                <p class="help-block">Original item category. Can be found in item.txt.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="max_item_lvl">Max Item Level</label>

                            <div class="controls">
                                <select id="max_item_lvl" name="max_item_lvl">
                                    <?php for($i = 0; $i <= 15; $i++): ?>
                                        <option value="<?php echo $i; ?>">+ <?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>

                                <p class="help-block">Max item level which user will be able to select.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="stick_level">Item Stick Level</label>

                            <div class="controls">
                                <select id="stick_level" name="stick_level">
                                    <?php for($i = 0; $i <= 100; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>

                                <p class="help-block">Item stick level if item have it.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="max_item_opt">Max Item Option</label>

                            <div class="controls">
                                <select id="max_item_opt" name="max_item_opt">
                                    <?php for($i = 0; $i <= 7; $i++): ?>
                                        <option value="<?php echo $i; ?>">+ <?php echo $i * 4; ?></option>
                                    <?php endfor; ?>
                                </select>

                                <p class="help-block">Max item life option which user will be able to select.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="exetype">Exe Type</label>

                            <div class="controls">
                                <select id="exetype" name="exetype">
                                    <option value="-1">None</option>
                                    <option value="1">Weapons & Pendants</option>
                                    <option value="9">Weapons Dark/Blood/Holy Angel</option>
                                    <option value="2">Shields & Sets</option>
                                    <option value="10">Shields & Sets Dark/Blood/Holy Angel</option>
                                    <option value="3">Wings LVL 1-2</option>
                                    <option value="8">Wings LVL 2.5</option>
                                    <option value="6">Wings LVL 3</option>
                                    <option value="7">Wings LVL 4</option>
                                    <option value="11">Wings LVL 5</option>
                                    <option value="4">Fenrir</option>
                                    <option value="5">Rings</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="luck">Luck </label>

                            <div class="controls">
                                <select id="luck" name="luck">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>

                                <p class="help-block">Item has luck option?</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="use_sockets">Sockets </label>

                            <div class="controls">
                                <select id="use_sockets" name="use_sockets">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>

                                <p class="help-block">Item can have socket options?</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="use_harmony">Harmony </label>

                            <div class="controls">
                                <select id="use_harmony" name="use_harmony">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>

                                <p class="help-block">Item can have harmony option?</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="use_refinary">Refinery </label>

                            <div class="controls">
                                <select id="use_refinary" name="use_refinary">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>

                                <p class="help-block">Item can have refinery option?</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="price">Price </label>

                            <div class="controls">
                                <input type="text" class="typeahead" id="price" name="price" value=""/>

                                <p class="help-block">Item price.</p>
                            </div>
                        </div>
						 <div class="control-group">
                            <label class="control-label" for="allow_upgrade">Upgrade </label>

                            <div class="controls">
                                <select id="allow_upgrade" name="allow_upgrade">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>

                                <p class="help-block">Allow item to be upgraded in workshop?</p>
                            </div>
                        </div>
						<div class="control-group">
                            <label class="control-label" for="upgrade_price">Upgrade Price </label>

                            <div class="controls">
                                <input type="text" class="typeahead" id="upgrade_price" name="upgrade_price" value="0"/>

                                <p class="help-block">Item upgrade price.</p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="payment_type">Payment methods </label>

                            <div class="controls">
                                <select id="payment_type" name="payment_type">
                                    <option value="0">All</option>
                                    <option value="1">Credits 1</option>
                                    <option value="2">Credits 2</option>
                                </select>

                                <p class="help-block">Allowed payment methods.</p>

                                <p>For credits types check your credits settings <a
                                            href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                            target="_blank">here</a></p>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="is_ancient">Item Image </label>

                            <div class="controls">
                                <input class="input-file uniform_on" name="itemimage" id="itemimage" type="file">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" name="add_item">Add Item</button>
                            <button type="reset" class="btn">Cancel</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
</div>