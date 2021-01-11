<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/server-list-manager">Server List Manager</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <p class="left">
                <a href="<?php echo $this->config->base_url; ?>admincp/add-server" class="btn btn-large btn-primary">
                    Add New Server</a>
            </p>

            <div class="clearfix"></div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="box span9">
            <div class="box-header well" data-original-title>
                <h2>List Servers</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <?php
                    if(isset($error)){
                        echo '<div class="alert alert-error">' . $error . '</div>';
                    }
                    if(isset($success)){
                        echo '<div class="alert alert-success">' . $success . '</div>';
                    }
                ?>
                <table class="table" id="serverlist_sortable">
                    <thead>
                    <tr>
                        <th>Key</th>
                        <th>Title</th>
                        <th>Version</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody id="serverlist_sortable_content" style="cursor: move;">
                    <?php
                        foreach($server_list AS $key => $server){
                            ?>
                            <tr id="<?php echo $key; ?>">

                                <td><?php echo $key; ?></td>
                                <td><?php echo $server['title']; ?></td>
                                <td><?php echo $server['version']; ?></td>
                                <td class="center" id="status_icon_<?php echo $key; ?>">
                                    <?php if($server['visible'] == 1): ?>
                                        <span class="label label-success">Visible</span>
                                    <?php else: ?>
                                        <span class="label label-important">Hidden</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($server['visible'] == 1): ?>
                                        <a class="btn btn-danger" href="#" id="server_status_button_<?php echo $key; ?>"
                                           onclick="App.changeServerStatus('<?php echo $key; ?>', 0);">
                                            <i class="icon-edit icon-white"></i> Disable </a>
                                    <?php else: ?>
                                        <a class="btn btn-success" href="#"
                                           id="server_status_button_<?php echo $key; ?>"
                                           onclick="App.changeServerStatus('<?php echo $key; ?>', 1);">
                                            <i class="icon-edit icon-white"></i> Enable </a>
                                    <?php endif; ?>
                                    <a class="btn btn-warning" href="#"
                                       onclick="App.deleteServer('<?php echo $key; ?>');">
                                        <i class="icon-edit icon-white"></i> Remove </a> <a class="btn btn-primary"
                                                                                            href="<?php echo $this->config->base_url; ?>admincp/edit-server/<?php echo $key; ?>">
                                        <i class="icon-edit icon-white"></i> Edit </a>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box span3">
            <div class="box-header well" data-original-title>
                <h2>Multiple Account Database Settings</h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <form class="form-inline">
                    <fieldset>
                        <div class="control-group">
                            <div class="controls">
                                <label class="radio"> <input type="radio" name="multiple_accoun_db_status" value="0"
                                                             data-no-uniform="true"
                                                             onclick="App.setUseMultiAccountDB(0);" <?php if(!$this->website->is_multiple_accounts()){
                                        echo 'checked';
                                    } ?>> Accounts are loaded from first server in server list </label>

                                <div style="clear:both"></div>
                                <label class="radio"> <input type="radio" name="multiple_accoun_db_status" value="1"
                                                             data-no-uniform="true"
                                                             onclick="App.setUseMultiAccountDB(1);" <?php if($this->website->is_multiple_accounts()){
                                        echo 'checked';
                                    } ?>> Accounts are loaded from each server in server list </label>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>