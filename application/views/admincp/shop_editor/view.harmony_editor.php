<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/edit-harmony-options">Edit Harmony Options</a>
            </li>
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
                <h2><i class="icon-edit"></i> Edit Harmony Options</h2>
            </div>
            <div class="box-content">
                <div id="harmony_list">
                    <table class="table table-striped table-bordered bootstrap-datatable datatable">
                        <thead>
                        <tr>
                            <th>Harmony Name</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($harmony as $h): ?>
                            <tr>
                                <td><input class="input-xlarge" type="text" id="harmonyn_<?php echo $h['id']; ?>"
                                           value="<?php echo $h['hname']; ?>"/></td>
                                <td class="center"><input class="input-small" type="text"
                                                          id="harmonyp_<?php echo $h['id']; ?>"
                                                          value="<?php echo $h['price']; ?>"/></td>
                                <td class="center" id="status_icon_<?php echo $h['id']; ?>">
                                    <?php if($h['status'] == 1): ?>
                                        <span class="label label-success">Active</span>
                                    <?php else: ?>
                                        <span class="label label-important">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="center">
                                    <?php if($h['status'] == 1): ?>
                                        <a class="btn btn-success" href="#" id="status"
                                           onclick="App.changeHarmonyStatus(<?php echo $h['id']; ?>, 0);">
                                            <i class="icon-edit icon-white"></i>
                                            Disable
                                        </a>
                                    <?php else: ?>
                                        <a class="btn btn-success" href="#" id="status"
                                           onclick="App.changeHarmonyStatus(<?php echo $h['id']; ?>, 1);">
                                            <i class="icon-edit icon-white"></i>
                                            Enable
                                        </a>
                                    <?php endif; ?>

                                    <a class="btn btn-info" href="#"
                                       onclick="App.editHarmony(<?php echo $h['id']; ?>);">
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