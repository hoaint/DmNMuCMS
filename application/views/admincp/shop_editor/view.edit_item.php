<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/item-list">Edit Items</a></li>
        </ul>
    </div>
    <?php
        if(!empty($load_error)){
            echo '<div class="alert alert-error span9">' . $load_error . '</div>';
        } else{
            if(!empty($error)){
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
            if(!empty($success)){
                echo '<div class="alert alert-success span9">' . $success . '</div>';
            }
            ?>
            <div class="row-fluid">
                <div class="box span12">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Edit Shop Item</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
                            <fieldset>
                                <legend>Add new shop item.</legend>
                                <div class="control-group">
                                    <label class="control-label" for="item_id">Item Id </label>

                                    <div class="controls">
                                        <input type="text" class="typeahead" id="item_id" name="item_id"
                                               value="<?php echo $info['item_id']; ?>"/>

                                        <p class="help-block">Your new item id. Can be found in item.txt.</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="name">Item Name </label>

                                    <div class="controls">
                                        <input type="text" class="typeahead" id="name" name="name"
                                               value="<?php echo $info['name']; ?>"/>

                                        <p class="help-block">Your new item name. Can be found in item.txt.</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="item_cat">Category</label>

                                    <div class="controls">
                                        <select id="item_cat" name="item_cat">
                                            <?php echo $this->webshop->load_cat_list(true, $info['item_cat'], false, '', true); ?>
                                        </select>

                                        <p class="help-block">Item category.</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="original_item_cat">Original Category</label>

                                    <div class="controls">
                                        <select id="original_item_cat" name="original_item_cat">
                                            <?php echo $this->webshop->load_cat_list(true, $info['original_item_cat'], true, '', true); ?>
                                        </select>

                                        <p class="help-block">Original item category. Can be found in item.txt.</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="max_item_lvl">Max Item Level</label>

                                    <div class="controls">
                                        <select id="max_item_lvl" name="max_item_lvl">
                                            <?php for($i = 0; $i <= 15; $i++): ?>
                                                <option value="<?php echo $i; ?>"
                                                        <?php if($info['max_item_lvl'] == $i){ ?>selected="selected"<?php } ?>>
                                                    + <?php echo $i; ?></option>
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
                                                <option value="<?php echo $i; ?>"
                                                        <?php if($info['stick_level'] == $i){ ?>selected="selected"<?php } ?>><?php echo $i; ?></option>
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
                                                <option value="<?php echo $i; ?>"
                                                        <?php if($info['max_item_opt'] == $i){ ?>selected="selected"<?php } ?>>
                                                    + <?php echo $i * 4; ?></option>
                                            <?php endfor; ?>
                                        </select>

                                        <p class="help-block">Max item life option which user will be able to select.</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="exetype">Exe Type</label>

                                    <div class="controls">
                                        <select id="exetype" name="exetype">
                                            <option value="-1"
                                                    <?php if($info['exetype'] == -1){ ?>selected="selected"<?php } ?>>
                                                None
                                            </option>
                                            <option value="1"
                                                    <?php if($info['exetype'] == 1){ ?>selected="selected"<?php } ?>>
                                                Weapons & Pendants
                                            </option>
                                            <option value="9"
                                                    <?php if($info['exetype'] == 9){ ?>selected="selected"<?php } ?>>
                                                Weapons Dark/Blood/Holy Angel
                                            </option>
                                            <option value="2"
                                                    <?php if($info['exetype'] == 2){ ?>selected="selected"<?php } ?>>
                                                Shields & Sets
                                            </option>
                                            <option value="10"
                                                    <?php if($info['exetype'] == 10){ ?>selected="selected"<?php } ?>>
                                                Shields & Sets Dark/Blood/Holy Angel
                                            </option>
                                            <option value="3"
                                                    <?php if($info['exetype'] == 3){ ?>selected="selected"<?php } ?>>
                                                Wings LVL 1-2
                                            </option>
                                            <option value="8"
                                                    <?php if($info['exetype'] == 8){ ?>selected="selected"<?php } ?>>
                                                Wings LVL 2.5
                                            </option>
                                            <option value="6"
                                                    <?php if($info['exetype'] == 6){ ?>selected="selected"<?php } ?>>
                                                Wings LVL 3
                                            </option>
                                            <option value="7"
                                                    <?php if($info['exetype'] == 7){ ?>selected="selected"<?php } ?>>
                                                Wings LVL 4
                                            </option>
                                            <option value="11"
                                                    <?php if($info['exetype'] == 11){ ?>selected="selected"<?php } ?>>
                                                Wings LVL 5
                                            </option>
                                            <option value="4"
                                                    <?php if($info['exetype'] == 4){ ?>selected="selected"<?php } ?>>
                                                Fenrir
                                            </option>
                                            <option value="5"
                                                    <?php if($info['exetype'] == 5){ ?>selected="selected"<?php } ?>>
                                                Rings
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="luck">Luck </label>

                                    <div class="controls">
                                        <select id="luck" name="luck">
                                            <option value="0"
                                                    <?php if($info['luck'] == 0){ ?>selected="selected"<?php } ?>>No
                                            </option>
                                            <option value="1"
                                                    <?php if($info['luck'] == 1){ ?>selected="selected"<?php } ?>>Yes
                                            </option>
                                        </select>

                                        <p class="help-block">Item has luck option?</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="use_sockets">Sockets </label>

                                    <div class="controls">
                                        <select id="use_sockets" name="use_sockets">
                                            <option value="0"
                                                    <?php if($info['use_sockets'] == 0){ ?>selected="selected"<?php } ?>>
                                                No
                                            </option>
                                            <option value="1"
                                                    <?php if($info['use_sockets'] == 1){ ?>selected="selected"<?php } ?>>
                                                Yes
                                            </option>
                                        </select>

                                        <p class="help-block">Item can have socket options?</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="use_harmony">Harmony </label>

                                    <div class="controls">
                                        <select id="use_harmony" name="use_harmony">
                                            <option value="0"
                                                    <?php if($info['use_harmony'] == 0){ ?>selected="selected"<?php } ?>>
                                                No
                                            </option>
                                            <option value="1"
                                                    <?php if($info['use_harmony'] == 1){ ?>selected="selected"<?php } ?>>
                                                Yes
                                            </option>
                                        </select>

                                        <p class="help-block">Item can have harmony option?</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="use_refinary">Refinery </label>

                                    <div class="controls">
                                        <select id="use_refinary" name="use_refinary">
                                            <option value="0"
                                                    <?php if($info['use_refinary'] == 0){ ?>selected="selected"<?php } ?>>
                                                No
                                            </option>
                                            <option value="1"
                                                    <?php if($info['use_refinary'] == 1){ ?>selected="selected"<?php } ?>>
                                                Yes
                                            </option>
                                        </select>

                                        <p class="help-block">Item can have refinery option?</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="price">Price </label>

                                    <div class="controls">
                                        <input type="text" class="typeahead" id="price" name="price"
                                               value="<?php echo $info['price']; ?>"/>

                                        <p class="help-block">Item price.</p>
                                    </div>
                                </div>
								<div class="control-group">
									<label class="control-label" for="allow_upgrade">Upgrade </label>

									<div class="controls">
										<select id="allow_upgrade" name="allow_upgrade">
											<option value="0" <?php if($info['allow_upgrade'] == 0){ ?>selected="selected"<?php } ?>>No</option>
											<option value="1" <?php if($info['allow_upgrade'] == 1){ ?>selected="selected"<?php } ?>>Yes</option>
										</select>

										<p class="help-block">Allow item to be upgraded in workshop?</p>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="upgrade_price">Upgrade Price </label>

									<div class="controls">
										<input type="text" class="typeahead" id="upgrade_price" name="upgrade_price" value="<?php echo $info['upgrade_price']; ?>"/>

										<p class="help-block">Item upgrade price.</p>
									</div>
								</div>
                                <div class="control-group">
                                    <label class="control-label" for="payment_type">Payment methods </label>

                                    <div class="controls">
                                        <select id="payment_type" name="payment_type">
                                            <option value="0"
                                                    <?php if($info['payment_type'] == 0){ ?>selected="selected"<?php } ?>>
                                                All
                                            </option>
                                            <option value="1"
                                                    <?php if($info['payment_type'] == 1){ ?>selected="selected"<?php } ?>>
                                                Credits 1
                                            </option>
                                            <option value="2"
                                                    <?php if($info['payment_type'] == 2){ ?>selected="selected"<?php } ?>>
                                                Credits 2
                                            </option>
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
                                        <input class="input-file uniform_on" name="itemimage" id="itemimage"
                                               type="file">
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary" name="edit_item">Edit Item</button>
                                    <button type="reset" class="btn">Cancel</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
    ?>
</div>