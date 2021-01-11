<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/scheduler">Scheduler Settings</a>
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
            <ul class="nav nav-pills">
                <li role="presentation" class="active">
                    <a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a>
                </li>
                <li role="presentation">
                    <a href="#tasks" aria-controls="tasks" role="tab" data-toggle="tab">Scheduled Tasks</a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
    <script>
        $(function () {
            var hash = window.location.hash;
            hash && $('ul.nav a[href="' + hash + '"]').tab('show');

            $('.nav a').click(function (e) {
                $(this).tab('show');
                window.location.hash = this.hash;
            });
        });
    </script>
    <div class="row-fluid">
        <div class="box span12">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in" id="tasks">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Task</th>
                            <th>Last Run</th>
                            <th>Schedule (<a href="" target="_blank">Guide</a>)</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $scheduler_config['tasks'] = array_merge($scheduler_config['tasks'], ["CheckUpdates" => ["time" => "2 */3 * * *", "status" => 1, "desc" => "Check for cms updates."]]);
                            foreach($scheduler_config['tasks'] AS $key => $value){
                                $last_run = $this->Madmin->get_task_last_run($key);
                                if($last_run != 'undefined'){
                                    $last_run = date('d/M/Y H:i', $last_run);
                                }
                                if($value['status'] == 1){
                                    $status = '<span class="label label-success" id="status-' . $key . '">Active</span>';
                                } else{
                                    $status = '<span class="label label-important" id="status-' . $key . '">Inactive</span>';
                                }
                                ?>
                                <tr id="<?php echo $key; ?>">
                                    <td><?php echo $key; ?><br/><?php echo $value['desc']; ?></td>
                                    <td><?php echo $last_run; ?></td>
                                    <td>
                                        <?php
                                            $times = explode(' ', $value['time']);
                                        ?>
                                        <div class="input-prepend">
                                            <span class="add-on" style="width:100px;">Minute</span><input
                                                    style="width:40px;" id="min-<?php echo $key; ?>" size="4" type="text"
                                                    value="<?php echo $times[0]; ?>"
                                                    <?php if($key == 'CheckUpdates'){ ?>readonly<?php } ?>>
                                        </div>
                                        <div class="input-prepend">
                                            <span class="add-on" style="width:100px;">Hour</span><input style="width:40px;"
                                                                                                        id="hour-<?php echo $key; ?>"
                                                                                                        size="4" type="text"
                                                                                                        value="<?php echo $times[1]; ?>"
                                                                                                        <?php if($key == 'CheckUpdates'){ ?>readonly<?php } ?>>
                                        </div>
                                        <div class="input-prepend">
                                            <span class="add-on" style="width:100px;">Day of Month</span><input
                                                    style="width:40px;" id="dmonth-<?php echo $key; ?>" size="4" type="text"
                                                    value="<?php echo $times[2]; ?>"
                                                    <?php if($key == 'CheckUpdates'){ ?>readonly<?php } ?>>
                                        </div>
                                        <div class="input-prepend">
                                            <span class="add-on" style="width:100px;">Month</span><input style="width:40px;"
                                                                                                         id="month-<?php echo $key; ?>"
                                                                                                         size="4"
                                                                                                         type="text"
                                                                                                         value="<?php echo $times[3]; ?>"
                                                                                                         <?php if($key == 'CheckUpdates'){ ?>readonly<?php } ?>>
                                        </div>
                                        <div class="input-prepend">
                                            <span class="add-on" style="width:100px;">Day of Week</span><input
                                                    style="width:40px;" id="dweek-<?php echo $key; ?>" size="4" type="text"
                                                    value="<?php echo $times[4]; ?>"
                                                    <?php if($key == 'CheckUpdates'){ ?>readonly<?php } ?>>
                                        </div>
                                    </td>
                                    <td><?php echo $status; ?></td>
                                    <td>
                                        <?php if($key != 'CheckUpdates'){ ?>
                                            <a class="btn btn-primary" href="#"
                                               onclick="App.editTaskSchedule('<?php echo $key; ?>');">Edit</a>
                                            <?php
                                            if($value['status'] == 1){
                                                ?>
                                                <a class="btn btn-danger" href="#"
                                                   onclick="App.changeTaskStatus('<?php echo $key; ?>', 0);"
                                                   id="change_status_<?php echo $key; ?>">Disable</a>
                                                <?php
                                            } else{
                                                ?>
                                                <a class="btn btn-success" href="#"
                                                   onclick="App.changeTaskStatus('<?php echo $key; ?>', 1);"
                                                   id="change_status_<?php echo $key; ?>">Enable</a>
                                                <?php
                                            }
                                        }
                                        ?>
                                        <a class="btn btn-info" href="#" id="run_cron_task_<?php echo $key; ?>"
                                           data-task="<?php echo $key; ?>">Run Task</a>

                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane fade in active" id="settings">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Scheduler Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="scheduler_settings_form">
                            <fieldset>
                                <div class="control-group">
                                    <label class="control-label">Scheduler Method</label>

                                    <div class="controls">
                                        <label class="radio" style="padding-left:25px;">
                                            <input type="radio" name="type"
                                                   value="3" <?php if($scheduler_config['type'] == 3){
                                                echo 'checked';
                                            } ?> data-no-uniform="true">
                                            <span style="font-size:14px;">Run Automatically with Traffic (Default - Not Recommended)</span>

                                            <p>The system will automatically run tasks as members browse the site.
                                                <br/> This is fine for most sites, but if your site does not receive
                                                much member traffic,
                                                <br/> tasks could become backlogged. Tasks will only be triggered by
                                                regular members, not guests.
                                            </p>
                                        </label>

                                        <div style="clear:both;padding-top:15px;"></div>
                                        <label class="radio" style="padding-left:25px;">
                                            <input type="radio" name="type"
                                                   value="1" <?php if($scheduler_config['type'] == 1){
                                                echo 'checked';
                                            } ?> data-no-uniform="true">
                                            <span style="font-size:14px">Use cron (Recommended)</span>

                                            <p>If you are able to set up crons on your web server,
                                                <br/> the server can run these tasks. This is more reliable and so is
                                                recommended if possible on your server.
                                            </p>
                                        </label>

                                        <div class="box-content alerts">
                                            <div class="alert alert-info ">
                                                <h4 class="alert-heading">Warning!</h4>

                                                <p>When this setting is enabled you
                                                    <b>MUST</b> ensure that the cron is set up properly. If this setting
                                                    is turned on but no cron is set up it will cause significant damage
                                                    to your site.<br/><br/>
                                                    <?php if(strtolower(PHP_SHLIB_SUFFIX) === 'dll'): ?>
                                                        For
                                                        <b>Windows</b> hosted web servers execute following command in
                                                        <b>Command Promt</b>:<br/>
                                                        <span
                                                                style="color: #b30000;"><?php echo 'schtasks /create /sc minute /mo 1 /tn "run website cron" /tr "<span style="color:red;">set php executable dir here\php.exe</span> -d memory_limit=-1 -d max_execution_time=0 ' . BASEDIR . 'interface' . DS . 'task.php ' . $scheduler_config['key'] . ' ' . $_SERVER['SERVER_NAME'] . ' ' . $_SERVER['SERVER_PORT'] . ' ' . $_SERVER['HTTP_HOST'] . '"'; ?></span>
                                                    <?php else: ?>
                                                        You should set the cron to run the following command every minute:
                                                        <br/>
                                                        <span
                                                                style="color: #b30000;"><?php echo $php_exe . ' -d memory_limit=-1 -d max_execution_time=0 ' . BASEDIR . 'interface' . DS . 'task.php ' . $scheduler_config['key'] . ' ' . $_SERVER['SERVER_NAME'] . ' ' . $_SERVER['SERVER_PORT'] . ' ' . $_SERVER['HTTP_HOST']; ?></span>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div style="clear:both;"></div>
                                        <label class="radio" style="padding-left:25px;">
                                            <input type="radio" name="type"
                                                   value="2" <?php if($scheduler_config['type'] == 2){
                                                echo 'checked';
                                            } ?> data-no-uniform="true"> Use web service
                                            <p>You can use a web service such as <a href="https://cron-job.org"
                                                                                    target="_blank">CRON-JOBS.org</a> to
                                                remotely trigger these tasks.<br/> This is recommended if using a cron
                                                isn't possible. This service is free.
                                            </p>
                                        </label>

                                        <div class="box-content alerts">
                                            <div class="alert alert-info ">
                                                <h4 class="alert-heading">Warning!</h4>

                                                <p>When this setting is enabled you
                                                    <b>MUST</b> ensure that a web service calls to the following URL. If
                                                    this setting is turned on but no web service is set up it will cause
                                                    significant damage to your site.<br/> If you are using proxy
                                                    services such as
                                                    <b>CloudFlare</b> or any other this url should be whitelisted from
                                                    any checks.<br/><br/> You should set the service to call to the
                                                    following URL as frequently as every minute (or as frequently as
                                                    possible):<br/>
                                                    <b>Do not share this url to 3rd parties!</b><br/>
                                                    <span style="color: #b30000;"><?php echo $this->config->base_url; ?>interface/web.php?key=<?php echo $scheduler_config['key']; ?></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary" name="edit_scheduler_settings"
                                            id="edit_scheduler_settings">Save changes
                                    </button>
                                    <button type="reset" class="btn">Cancel</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>