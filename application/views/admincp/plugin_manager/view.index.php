<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-plugins">Plugin Manager</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Current plugins</h2>
            </div>
            <div class="box-content">
                <table class="table table-striped table-bordered bootstrap-datatable datatable"
                       id="current_plugin_sortable">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody id="current_plugin_sortable_content">
                    <?php
                        if(isset($plugin_data) && !empty($plugin_data)):
                            foreach($plugin_data AS $name => $plugin):
                                ?>
                                <tr id="<?php echo $name; ?>">
                                    <td style="width:500px;"><a href="<?php echo $plugin['admin_module_url']; ?>"><?php echo $plugin['about']['name']; ?></a>
                                    </td>
                                    <td style="width:100px;" class="center" id="status_icon">
                                        <?php if($plugin['installed'] == 1): ?>
                                            <span class="label label-success">Active</span>
                                        <?php else: ?>
                                            <span class="label label-important">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($plugin['installed'] == 1): ?>
                                            <a id="status_button" class="btn btn-warning" href="#"
                                               onclick="App.disablePlugin('<?php echo $name; ?>');"><i
                                                        class="icon-edit icon-white"></i>Disable</a>
                                        <?php else: ?>
                                            <a id="status_button" class="btn btn-success" href="#"
                                               onclick="App.enablePlugin('<?php echo $name; ?>');"><i
                                                        class="icon-edit icon-white"></i>Enable</a>
                                        <?php endif; ?>
                                        <a id="uninstall_button" class="btn btn-danger" href="#"
                                           onclick="App.uninstallPlugin('<?php echo $name; ?>');"><i
                                                    class="icon-remove icon-white"></i>Remove</a>
                                        <a id="about_button" class="btn btn-inverse" href="#"
                                           onclick="App.aboutPlugin('<?php echo $name; ?>');"><i
                                                    class="icon-leaf icon-white"></i> About</a>
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                        else:
                            ?>
                            <tr id="no_current_plugins">
                                <td colspan="3">
                                    <div class="alert alert-info">No plugins</div>
                                </td>
                            </tr>
                        <?php
                        endif;
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Available plugins</h2>
            </div>
            <div class="box-content">
                <table class="table table-striped table-bordered bootstrap-datatable datatable"
                       id="available_plugin_sortable">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody id="available_plugin_sortable_content">
                    <?php
                        if(isset($available_data) && !empty($available_data)):
                            foreach($available_data AS $name => $plugin):
                                ?>
                                <tr id="<?php echo $name; ?>">
                                    <td style="width:500px;"><?php echo $plugin['about']['name']; ?></td>
                                    <td style="width:100px;" class="center">
                                        <span class="label">Not Installed</span>
                                    </td>
                                    <td>
                                        <a id="status_button" class="btn btn-success" href="#"
                                           onclick="App.installPlugin('<?php echo $name; ?>');"><i
                                                    class="icon-download-alt icon-white"></i>Install</a>
                                        <a id="about_button" class="btn btn-inverse" href="#"
                                           onclick="App.aboutPlugin('<?php echo $name; ?>');"><i
                                                    class="icon-leaf icon-white"></i> About</a>
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                        else:
                            ?>
                            <tr id="no_available_plugins">
                                <td colspan="3">
                                    <div class="alert alert-info">No plugins</div>
                                </td>
                            </tr>
                        <?php
                        endif;
                    ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
	