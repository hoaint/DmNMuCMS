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
                        <h2><i class="icon-edit"></i> Custom Price List</h2>
                    </div>
                    <div class="box-content">
                        <table class="table table-striped table-bordered bootstrap-datatable datatable">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
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
                                <td><?php echo $item['name']; ?></td>
                                <td>
                                    <a class="btn btn-danger"
                                       href="<?php echo $this->config->base_url; ?>admincp/delete-from-custom-price-list/<?php echo $item['iid']; ?>">
                                        <i class="icon-edit icon-white"></i>
                                        Delete
                                    </a>
                                    <a class="btn btn-success"
                                       href="<?php echo $this->config->base_url; ?>admincp/set-item-price/<?php echo $item['id']; ?>">
                                        <i class="icon-edit icon-white"></i>
                                        Edit Price
                                    </a>
                                </td>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php
        }
    ?>
</div>
</div>