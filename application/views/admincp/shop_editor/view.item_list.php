<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/item-list">Edit Items</a></li>
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
                <h2><i class="icon-edit"></i> Shop Item List</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="">
                    <label class="control-label" for="category">Category</label>

                    <div class="controls">
                        <select name="category" id="category">
                            <option value="">Select</option>
                            <?php echo $this->webshop->load_cat_list(true); ?>
                        </select>
                    </div>
                </form>
                <form class="form-horizontal" method="POST" action="" id="edit_price_form">
                    <table class="table table-striped table-bordered bootstrap-datatable datatable">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Category</th>
                            <th>Original Category</th>
                            <th>Name</th>
                            <th>Fast Price Editor</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $i = 0;
                            foreach($items

                            as $item):
                            $i++;
                        ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td><?php echo $item['item_cat']; ?></td>
                            <td><?php echo $item['original_item_cat']; ?></td>
                            <td><?php echo $item['name']; ?></td>
                            <td>
                                <input type="text" id="price-<?php echo $item['id']; ?>" name="price"
                                       value="<?php echo $item['price']; ?>" class="input-small"
                                       tabindex="<?php echo $i; ?>"/>
                            </td>
                            <td>
                                <a class="btn btn-danger"
                                   href="<?php echo $this->config->base_url; ?>admincp/delete-item/<?php echo $item['id']; ?>">
                                    <i class="icon-edit icon-white"></i>
                                    Delete
                                </a>
                                <a class="btn btn-success"
                                   href="<?php echo $this->config->base_url; ?>admincp/edit-item/<?php echo $item['id']; ?>">
                                    <i class="icon-edit icon-white"></i>
                                    Edit
                                </a>
                                <a class="btn btn-inverse"
                                   href="<?php echo $this->config->base_url; ?>admincp/set-item-price/<?php echo $item['id']; ?>">
                                    <i class="icon-edit icon-white"></i>
                                    Add To Custom Price List
                                </a>
                            </td>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </form>
                <?php
                    if(isset($pagination)):
                        ?>
                        <div style="padding:10px;text-align:center;">
                            <table style="width: 100%;">
                                <tr>
                                    <td><?php echo $pagination; ?></td>
                                </tr>
                            </table>
                        </div>
                    <?php
                    endif;
                ?>
            </div>
        </div>
    </div>
</div>
	