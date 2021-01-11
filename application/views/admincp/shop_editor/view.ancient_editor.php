<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/edit-ancient-sets">Edit Ancient Sets</a></li>
        </ul>
    </div>
    <?php
        if(isset($error)){
            echo '<div class="alert alert-error span9">' . $error . '</div>';
        }
        if(isset($success)){
            echo '<div class="alert alert-success span9">' . $success . '</div>';
        }
    ?>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Edit Shop Ancient Sets</h2>
            </div>
            <div class="box-content">
                <table class="table table-striped table-bordered bootstrap-datatable datatable">
                    <thead>
                    <tr>
                        <td>Category</td>
                        <td>Item Id</td>
                        <td>Set Type A</td>
                        <td>Status</td>
                        <td>Set Type B</td>
                        <td>Status</td>
                        <td>Action</td>
                    </tr>
                    </thead>
                </table>
                <?php foreach($ancient as $anc): ?>
                    <form action="" method="POST">
                        <input type="hidden" name="set_id" value="<?php echo $anc['id']; ?>"/>
                        <table class="table table-striped table-bordered bootstrap-datatable datatable">
                            <tbody>
                            <tr>
                                <td><select id="set_cat" name="set_cat"
                                            class="input-small"><?php echo $anc['cat']; ?></select></td>
                                <td><input type="text" id="item_id" name="item_id"
                                           value="<?php echo $anc['item_id']; ?>" class="input-small"/></td>
                                <td><input type="text" id="typeA" name="typeA" value="<?php echo $anc['typeA']; ?>"
                                           class="input-medium"/></td>
                                <td><input type="checkbox" id="statusA" name="statusA" value="1"
                                           <?php if($anc['statusA'] == 1){ ?>checked="checked"<?php } ?>
                                           data-no-uniform="true"/></td>
                                <td><input type="text" id="typeB" name="typeB" value="<?php echo $anc['typeB']; ?>"
                                           class="input-medium"/></td>
                                <td><input type="checkbox" id="statusB" name="statusB" value="1"
                                           <?php if($anc['statusB'] == 1){ ?>checked="checked"<?php } ?>
                                           data-no-uniform="true"/></td>
                                <td><input type="submit" id="edit_set" name="edit_set" value="Edit Set"
                                           class="btn btn-primary"/></td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                <?php endforeach; ?>
                <form action="" method="POST">
                    <table class="table table-striped table-bordered bootstrap-datatable datatable">
                        <thead>
                        <tr>
                            <td>Category</td>
                            <td>Item Id</td>
                            <td>Set Type A</td>
                            <td>Status</td>
                            <td>Set Type B</td>
                            <td>Status</td>
                            <td>Action</td>
                        </tr>
                        <thead>
                        <tbody>
                        <tr>
                            <td><select id="set_cat" name="set_cat"
                                        class="input-small"><?php echo $this->webshop->load_cat_list(true); ?></select>
                            </td>
                            <td><input type="text" id="item_id" name="item_id" value="" class="input-small"/></td>
                            <td><input type="text" id="typeA" name="typeA" value="" class="input-medium"/></td>
                            <td><input type="checkbox" id="statusA" name="statusA" value="1"/></td>
                            <td><input type="text" id="typeB" name="typeB" value="" class="input-medium"/></td>
                            <td><input type="checkbox" id="statusB" name="statusB" value="1"/></td>
                            <td colspan="2"><input type="submit" id="add_set" name="add_set" value="Add Set"
                                                   class="btn btn-primary"/></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>

    </div>
</div>