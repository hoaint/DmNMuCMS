<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/vip">Vip Settings</a>
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
        <div class="span12">
            <p class="left">
                <a href="<?php echo $this->config->base_url; ?>admincp/add-vip" class="btn btn-large btn-primary">
                    Add Vip Package</a>
            </p>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Vip Settings</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="" id="vip_settings_form">
                    <div class="control-group">
                        <label class="control-label" for="active">Status </label>
                        <div class="controls">
                            <select id="active" name="active">
                                <option value="0" <?php if(isset($vip_config['active']) && $vip_config['active'] == 0){
                                    echo 'selected="selected"';
                                } ?>>Inactive
                                </option>
                                <option value="1" <?php if(isset($vip_config['active']) && $vip_config['active'] == 1){
                                    echo 'selected="selected"';
                                } ?>>Active
                                </option>
                            </select>
                            <p class="help-block">Use vip system.</p>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" name="edit_vip_settings" id="edit_vip_settings">
                            Save changes
                        </button>
                    </div>
                </form>
            </div>
            <div class="box-header well">
                <h2><i class="icon-edit"></i> List Packages</h2>
            </div>
            <div class="box-content">
                <table class="table" id="vip_sortable">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Server</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody id="vip_sortable_content" style="cursor: move;">
                    <?php
                        foreach($vip_packages AS $key => $package){
                            ?>
                            <tr id="<?php echo $package['id']; ?>">

                                <td><?php echo $package['package_title']; ?></td>
                                <td><?php echo $package['price']; ?><?php echo $this->website->translate_credits($package['payment_type'], $package['server']); ?></td>
                                <td><?php echo $this->website->get_title_from_server($package['server']); ?></td>
                                <td><?php echo $this->website->seconds2days($package['vip_time']); ?></td>
                                <td class="center" id="vip_status_icon_<?php echo $package['id']; ?>">
                                    <?php if($package['status'] == 1): ?>
                                        <span class="label label-success">Visible</span>
                                    <?php else: ?>
                                        <span class="label label-important">Hidden</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($package['status'] == 1): ?>
                                        <a class="btn btn-danger" href="#"
                                           id="vip_status_button_<?php echo $package['id']; ?>"
                                           onclick="App.changeVipStatus(<?php echo $package['id']; ?>, 0);">
                                            <i class="icon-edit icon-white"></i>
                                            Disable
                                        </a>
                                    <?php else: ?>
                                        <a class="btn btn-success" href="#"
                                           id="vip_status_button_<?php echo $package['id']; ?>"
                                           onclick="App.changeVipStatus(<?php echo $package['id']; ?>, 1);">
                                            <i class="icon-edit icon-white"></i>
                                            Enable
                                        </a>
                                    <?php endif; ?>
                                    <a class="btn btn-warning" href="#"
                                       onclick="App.deleteVipPackage(<?php echo $package['id']; ?>);">
                                        <i class="icon-edit icon-white"></i>
                                        Remove
                                    </a>
                                    <a class="btn btn-primary"
                                       href="<?php echo $this->config->base_url; ?>admincp/edit-vip/<?php echo $package['id']; ?>">
                                        <i class="icon-edit icon-white"></i>
                                        Edit
                                    </a>
                                    <?php if($package['is_registration_package'] == 0): ?>
                                        <a class="btn btn-primary"
                                           href="<?php echo $this->config->base_url; ?>admincp/add-vip-on-registration/<?php echo $package['id']; ?>">
                                            <i class="icon-edit icon-white"></i>
                                            Add On Registration
                                        </a>
                                    <?php else: ?>
                                        <a class="btn btn-warning"
                                           href="<?php echo $this->config->base_url; ?>admincp/remove-vip-from-registration/<?php echo $package['id']; ?>">
                                            <i class="icon-edit icon-white"></i>
                                            Remove From Registration
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
