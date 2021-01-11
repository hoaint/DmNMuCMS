<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/event-timers">Event Timer
                    Settings</a>
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
                <a href="<?php echo $this->config->base_url; ?>admincp/add-event-timers"
                   class="btn btn-large btn-primary">
                    Add New Timer</a>
            </p>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Event Timer Settings</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="" id="event_settings_form">
                    <div class="control-group">
                        <label class="control-label" for="active">Status </label>
                        <div class="controls">
                            <select id="active" name="active">
                                <option value="0" <?php if(isset($event_config['active']) && $event_config['active'] == 0){
                                    echo 'selected="selected"';
                                } ?>>Inactive
                                </option>
                                <option value="1" <?php if(isset($event_config['active']) && $event_config['active'] == 1){
                                    echo 'selected="selected"';
                                } ?>>Active
                                </option>
                            </select>
                            <p class="help-block">Use event timer module.</p>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" name="edit_event_settings"
                                id="edit_event_settings">Save changes
                        </button>
                    </div>
                </form>
            </div>
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Event List</h2>
            </div>
            <div class="box-content">
                <table class="table" id="event_sortable">
                    <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody id="event_sortable_content" style="cursor: move;">
                    <?php
                        if(isset($event_config['event_timers'])){
                            foreach($event_config['event_timers'] AS $key => $event){
                                ?>
                                <tr id="<?php echo $key; ?>">

                                    <td><?php echo $event['name']; ?></td>
                                    <td>
                                        <a class="btn btn-warning" href="#"
                                           onclick="App.deleteEventTimer(<?php echo $key; ?>);">
                                            <i class="icon-edit icon-white"></i>
                                            Remove
                                        </a>
                                        <a class="btn btn-primary"
                                           href="<?php echo $this->config->base_url; ?>admincp/edit-event-timer/<?php echo $key; ?>">
                                            <i class="icon-edit icon-white"></i>
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
