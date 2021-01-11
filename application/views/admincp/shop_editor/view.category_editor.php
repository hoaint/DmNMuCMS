<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/edit-category-list">Edit Category List</a></li>
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
                <h2><i class="icon-edit"></i> Edit Shop Categories</h2>
            </div>
            <div class="box-content">
                <?php foreach($categories as $cat): ?>
                    <form action="" method="POST">
                        <input type="hidden" name="old_cat_id" value="<?php echo $cat['id']; ?>"/>
                        <table class="table table-striped table-bordered bootstrap-datatable datatable">
                            <tbody>
                            <tr>
                                <td><input type="text" id="cat_id" name="cat_id" value="<?php echo $cat['id']; ?>"
                                           class="input-medium"
                                           <?php if($cat['id'] <= 15){ ?>readonly="readonly"<?php } ?> /></td>
                                <td><input type="text" id="cat_name" name="cat_name" value="<?php echo $cat['name']; ?>"
                                           class="input-medium"/></td>
                                <td>
                                    <select id="cat_status" name="cat_status">
                                        <option value="0"
                                                <?php if($cat['status'] == 0){ ?>selected="selected"<?php } ?>>Hidden
                                        </option>
                                        <option value="1"
                                                <?php if($cat['status'] == 1){ ?>selected="selected"<?php } ?>>Visible
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <input type="submit" id="edit_cat" name="edit_cat" value="Save"
                                           class="btn btn-primary"/>
                                    <?php if($cat['id'] > 15){ ?>
                                        <input type="submit" id="delete_cat" name="delete_cat" value="Delete"
                                               class="btn btn-primary"/>
                                    <?php } ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                <?php endforeach; ?>
                <form action="" method="POST">
                    <table class="table table-striped table-bordered bootstrap-datatable datatable">
                        <thead>
                        <tr>
                            <td>Category Id</td>
                            <td>Category Name</td>
                            <td>Action</td>
                        </tr>
                        <thead>
                        <tbody>
                        <tr>
                            <td><input type="text" id="cat_id" name="cat_id" value="" class="input-medium"/></td>
                            <td><input type="text" id="cat_name" name="cat_name" value="" class="input-medium"/></td>
                            <td colspan="2"><input type="submit" id="add_cat" name="add_cat" value="Add New"
                                                   class="btn btn-primary"/></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>

    </div>
</div>