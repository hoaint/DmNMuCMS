<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/import-items">Import Items</a></li>
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
                <h2><i class="icon-edit"></i> Shop Item Importer</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="">
                    <div class="control-group">
                        <label class="control-label" for="switch_server_file">Server</label>

                        <div class="controls">
                            <select name="switch_server_file" id="switch_server_file">
                                <?php foreach($this->website->server_list() as $key => $server): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $server['title']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <label class="control-label" for="category">Category</label>

                    <div class="controls">
                        <select name="category" id="category-import">
                            <option value="">Select</option>
                            <?php echo $this->webshop->load_cat_list(true); ?>
                        </select>
                    </div>
                </form>
                <script>

                </script>
                <form class="form-horizontal" method="POST" action="" id="import_item_form">
                    <table class="table table-striped table-bordered bootstrap-datatable datatable">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th><input type="text" name="all_prices" id="all_prices" value="0"/> Set Price To All</th>
                            <th><input type="checkbox" name="check_all" id="check_all" data-no-uniform="true"/> Check
                                All
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(!empty($items)):
                                foreach($items AS $key => $item):
                                    ?>
                                    <tr>
                                        <td><?php echo $item['name']; ?></td>
                                        <td><?php echo $category; ?></td>
                                        <td><input type="text" name="price[<?php echo $item['id']; ?>]" value="0"/>
                                        </td>
                                        <td>
                                            <input type="checkbox" name="import[<?php echo $item['id']; ?>]"
                                                   data-no-uniform="true"/>
                                            <input type="hidden" name="name[<?php echo $item['id']; ?>]"
                                                   value="<?php echo $item['name']; ?>"/>
                                            <input type="hidden" name="slot[<?php echo $item['id']; ?>]"
                                                   value="<?php echo $item['slot']; ?>"/>
                                        </td>
                                    </tr>
                                <?php
                                endforeach;
                            endif;
                        ?>
                        </tbody>
                    </table>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" name="import_items">Import Items</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>