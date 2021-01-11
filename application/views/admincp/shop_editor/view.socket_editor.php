<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/edit-socket-options">Edit Socket Options</a></li>
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
                <h2><i class="icon-edit"></i> Edit Socket Options</h2>
            </div>
            <div class="box-content">
                <div id="socket_list">
                    <table class="table table-striped table-bordered bootstrap-datatable datatable"
                           id="socket_sortable">
                        <thead>
                        <tr>
                            <th>Socket Id</th>
                            <th>Socket Name</th>
                            <th>Socket Price</th>
                            <th>Socket Part Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="socket_sortable_content" style="cursor: move;">
                        <?php foreach($sockets as $socket): ?>
                            <tr id="<?php echo $socket['id']; ?>">
                                <td><input class="input-medium" type="text" id="socketid_<?php echo $socket['id']; ?>"
                                           value="<?php echo $socket['socket_id']; ?>" disabled="disabled"/></td>
                                <td class="center"><input class="input-xlarge" type="text"
                                                          id="socketname_<?php echo $socket['id']; ?>"
                                                          value="<?php echo $socket['socket_name']; ?>"/></td>
                                <td class="center"><input class="input-small" type="text"
                                                          id="socketprice_<?php echo $socket['id']; ?>"
                                                          value="<?php echo $socket['socket_price']; ?>"/></td>
                                <td class="center">
                                    <select id="socketpart_<?php echo $socket['id']; ?>" class="input-small">
                                        <option value="-1"
                                                <?php if($socket['socket_part_type'] == -1){ ?>selected="selected"<?php } ?>>
                                            Default
                                        </option>
                                        <option value="0"
                                                <?php if($socket['socket_part_type'] == 0){ ?>selected="selected"<?php } ?>>
                                            Sets
                                        </option>
                                        <option value="1"
                                                <?php if($socket['socket_part_type'] == 1){ ?>selected="selected"<?php } ?>>
                                            Weapons
                                        </option>
                                    </select>
                                </td>
                                <td class="center" id="status_icon_<?php echo $socket['id']; ?>">
                                    <?php if($socket['status'] == 1): ?>
                                        <span class="label label-success">Active</span>
                                    <?php else: ?>
                                        <span class="label label-important">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="center">
                                    <?php if($socket['status'] == 1): ?>
                                        <a class="btn btn-success" href="#" id="status"
                                           onclick="App.changeSocketStatus(<?php echo $socket['id']; ?>, 0);">
                                            <i class="icon-edit icon-white"></i>
                                            Disable
                                        </a>
                                    <?php else: ?>
                                        <a class="btn btn-success" href="#" id="status"
                                           onclick="App.changeSocketStatus(<?php echo $socket['id']; ?>, 1);">
                                            <i class="icon-edit icon-white"></i>
                                            Enable
                                        </a>
                                    <?php endif; ?>

                                    <a class="btn btn-info" href="#"
                                       onclick="App.editSocket(<?php echo $socket['id']; ?>);">
                                        <i class="icon-edit icon-white"></i>
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>