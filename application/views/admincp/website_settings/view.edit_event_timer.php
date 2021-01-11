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
        if(isset($event_not_found)){
            echo '<div class="alert alert-error span12">' . $event_not_found . '</div>';
        } else{
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
                        <form class="form-horizontal" method="POST" action="" id="edit_event_timer">
                            <div class="control-group">
                                <label class="control-label" for="package_title">Event Name <span
                                            style="color:red;">*</span></label>
                                <div class="controls">
                                    <input type="text" class="input-xlarge" name="name" id="name"
                                           value="<?php if(isset($event_data['name'])): echo $event_data['name']; endif; ?>"
                                           required/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="days">Event Days</label>
                                <div class="controls">
                                    <select id="days" name="days[]" multiple data-rel="chosen" required>
                                        <option value="0" <?php if(isset($event_data['days']) && !is_array($event_data['days'])): echo 'selected="selected"'; endif; ?>>
                                            Every Day
                                        </option>
                                        <option value="1" <?php if(isset($event_data['days']) && is_array($event_data['days']) && array_key_exists(1, $event_data['days'])): echo 'selected="selected"'; endif; ?>>
                                            Monday
                                        </option>
                                        <option value="2" <?php if(isset($event_data['days']) && is_array($event_data['days']) && array_key_exists(2, $event_data['days'])): echo 'selected="selected"'; endif; ?>>
                                            Tuesday
                                        </option>
                                        <option value="3" <?php if(isset($event_data['days']) && is_array($event_data['days']) && array_key_exists(3, $event_data['days'])): echo 'selected="selected"'; endif; ?>>
                                            Wednesday
                                        </option>
                                        <option value="4" <?php if(isset($event_data['days']) && is_array($event_data['days']) && array_key_exists(4, $event_data['days'])): echo 'selected="selected"'; endif; ?>>
                                            Thursday
                                        </option>
                                        <option value="5" <?php if(isset($event_data['days']) && is_array($event_data['days']) && array_key_exists(5, $event_data['days'])): echo 'selected="selected"'; endif; ?>>
                                            Friday
                                        </option>
                                        <option value="6" <?php if(isset($event_data['days']) && is_array($event_data['days']) && array_key_exists(6, $event_data['days'])): echo 'selected="selected"'; endif; ?>>
                                            Saturday
                                        </option>
                                        <option value="7" <?php if(isset($event_data['days']) && is_array($event_data['days']) && array_key_exists(7, $event_data['days'])): echo 'selected="selected"'; endif; ?>>
                                            Sunday
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group" id="all_timers" style="display:none;">
                                <label class="control-label" for="time">Event Times <span
                                            style="color:red;">*</span></label>
                                <div class="controls">
                                    <input type="text" class="input-xlarge" data-role="tagsinput" name="time" id="time"
                                           value="<?php if(isset($event_data['days']) && !is_array($event_data['days'])): echo $event_data['days']; endif; ?>"/>
                                    <p class="help-block">Event times seperated by comma. Format: hh:mm or hh:mm:ss</p>
                                </div>
                            </div>
                            <div class="control-group" id="timers_monday" style="display:none;">
                                <label class="control-label" for="time_monday">Event Times Monday<span
                                            style="color:red;">*</span></label>
                                <div class="controls">
                                    <input type="text" class="input-xlarge" data-role="tagsinput" name="time_monday"
                                           id="time_monday"
                                           value="<?php if(isset($event_data['days']) && is_array($event_data['days']) && array_key_exists(1, $event_data['days'])): echo $event_data['days'][1]; endif; ?>"/>
                                    <p class="help-block">Event times seperated by comma. Format: hh:mm or hh:mm:ss</p>
                                </div>
                            </div>
                            <div class="control-group" id="timers_tuesday" style="display:none;">
                                <label class="control-label" for="time_tuesday">Event Times Tuesday<span
                                            style="color:red;">*</span></label>
                                <div class="controls">
                                    <input type="text" class="input-xlarge" data-role="tagsinput" name="time_tuesday"
                                           id="time_tuesday"
                                           value="<?php if(isset($event_data['days']) && is_array($event_data['days']) && array_key_exists(2, $event_data['days'])): echo $event_data['days'][2]; endif; ?>"/>
                                    <p class="help-block">Event times seperated by comma. Format: hh:mm or hh:mm:ss</p>
                                </div>
                            </div>
                            <div class="control-group" id="timers_wednesday" style="display:none;">
                                <label class="control-label" for="time_wednesday">Event Times Wednesday<span
                                            style="color:red;">*</span></label>
                                <div class="controls">
                                    <input type="text" class="input-xlarge" data-role="tagsinput" name="time_wednesday"
                                           id="time_wednesday"
                                           value="<?php if(isset($event_data['days']) && is_array($event_data['days']) && array_key_exists(3, $event_data['days'])): echo $event_data['days'][3]; endif; ?>"/>
                                    <p class="help-block">Event times seperated by comma. Format: hh:mm or hh:mm:ss</p>
                                </div>
                            </div>
                            <div class="control-group" id="timers_thursday" style="display:none;">
                                <label class="control-label" for="time_thursday">Event Times Thursday<span
                                            style="color:red;">*</span></label>
                                <div class="controls">
                                    <input type="text" class="input-xlarge" data-role="tagsinput" name="time_thursday"
                                           id="time_thursday"
                                           value="<?php if(isset($event_data['days']) && is_array($event_data['days']) && array_key_exists(4, $event_data['days'])): echo $event_data['days'][4]; endif; ?>"/>
                                    <p class="help-block">Event times seperated by comma. Format: hh:mm or hh:mm:ss</p>
                                </div>
                            </div>
                            <div class="control-group" id="timers_friday" style="display:none;">
                                <label class="control-label" for="time_friday">Event Times Friday<span
                                            style="color:red;">*</span></label>
                                <div class="controls">
                                    <input type="text" class="input-xlarge" data-role="tagsinput" name="time_friday"
                                           id="time_friday"
                                           value="<?php if(isset($event_data['days']) && is_array($event_data['days']) && array_key_exists(5, $event_data['days'])): echo $event_data['days'][5]; endif; ?>"/>
                                    <p class="help-block">Event times seperated by comma. Format: hh:mm or hh:mm:ss</p>
                                </div>
                            </div>
                            <div class="control-group" id="timers_saturday" style="display:none;">
                                <label class="control-label" for="time_saturday">Event Times Saturday<span
                                            style="color:red;">*</span></label>
                                <div class="controls">
                                    <input type="text" class="input-xlarge" data-role="tagsinput" name="time_saturday"
                                           id="time_saturday"
                                           value="<?php if(isset($event_data['days']) && is_array($event_data['days']) && array_key_exists(6, $event_data['days'])): echo $event_data['days'][6]; endif; ?>"/>
                                    <p class="help-block">Event times seperated by comma. Format: hh:mm or hh:mm:ss</p>
                                </div>
                            </div>
                            <div class="control-group" id="timers_sunday" style="display:none;">
                                <label class="control-label" for="time_sunday">Event Times Sunday<span
                                            style="color:red;">*</span></label>
                                <div class="controls">
                                    <input type="text" class="input-xlarge" data-role="tagsinput" name="time_sunday"
                                           id="time_sunday"
                                           value="<?php if(isset($event_data['days']) && is_array($event_data['days']) && array_key_exists(7, $event_data['days'])): echo $event_data['days'][7]; endif; ?>"/>
                                    <p class="help-block">Event times seperated by comma. Format: hh:mm or hh:mm:ss</p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_event_timer" id="edit_event_timer"
                                        value="edit_event_timer">Edit Timer
                                </button>
                            </div>
                        </form>
                        <script>
                            $(document).ready(function () {
                                var selected_days = [];
                                $('#days :selected').each(function () {
                                    selected_days[$(this).val()] = $(this).val();
                                });
                                App.showHideTimes(selected_days);
                            });
                        </script>
                    </div>
                </div>
            </div>
        <?php } ?>
</div>
