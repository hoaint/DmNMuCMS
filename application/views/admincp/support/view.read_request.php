<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/support-requests">Support Requests</a></li>
        </ul>
    </div>

    <?php
        if(isset($error)){
            echo '<div class="alert alert-error span12">' . $error . '</div>';
        }
        if(isset($success)){
            echo '<div class="alert alert-success span12">' . $success . '</div>';
        }
        if(isset($not_found)){
            echo '<div class="alert alert-error span12">' . $not_found . '</div>';
        } else{
            ?>
            <div class="page-header">
                <h1><?php echo $ticket_data['subject']; ?></h1>
            </div>
            <div class="row-fluid">
                <div class="box span12">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Information</h2>

                        <div class="box-icon">
                            <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                        </div>
                    </div>
                    <div class="box-content">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th colspan="4">Information</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>User</td>
                                <td><?php echo $ticket_data['creator_account']; ?></td>
                                <td>Id</td>
                                <td><?php echo $ticket_data['id']; ?></td>
                            </tr>
                            <tr>
                                <td>Department</td>
                                <td>
                                    <select name="department" id="department">
                                        <?php foreach($department_list AS $department){ ?>
                                            <option
                                                    value="<?php echo $department['id']; ?>" <?php if($department['id'] == $ticket_data['department']){
                                                echo 'selected';
                                            } ?>><?php echo $department['department_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>Created</td>
                                <td><?php echo date('d/m/Y g:i a', $ticket_data['create_time']); ?></td>
                            </tr>
                            <tr>
                                <td>Priority</td>
                                <td><?php echo $this->Madmin->generate_priority($ticket_data['priority'], false, true); ?></td>
                                <td>Time Elapsed</td>
                                <td><?php echo $time_elapsed; ?></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>
                                    <select name="status" id="status">
                                        <?php foreach($status AS $key => $st){ ?>
                                            <option value="<?php echo $key; ?>" <?php if($key == $ticket_data['status']){
                                                echo 'selected';
                                            } ?>><?php echo $st; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>Attachment</td>
                                <td>
                                    <?php
                                        if($ticket_data['attachment'] != null){
                                            $attachment = @unserialize($ticket_data['attachment']);
                                            if($attachment != false){
                                                $i = 0;
                                                $f = '';
                                                foreach($attachment AS $files){
                                                    $i++;
                                                    $f .= '<a href="' . $this->config->base_url . 'assets/uploads/attachment/' . $files . '" target="_blank">File ' . $i . '</a>, ';
                                                }
                                                echo substr($f, 0, -2);
                                            } else{
                                                echo 'No attachment';
                                            }
                                        } else{
                                            echo 'No attachment';
                                        }
                                    ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <script>
                            $(document).ready(function () {
                                $('#department').on('change', function () {
                                    $.ajax({
                                        type: 'POST',
                                        dataType: 'json',
                                        url: DmNConfig.base_url + 'admincp/change_department',
                                        data: {department: $(this).val(), id: <?php echo $ticket_data['id'];?>},
                                        success: function (data) {
                                            if (data.error) {
                                                noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                                            }
                                            else {
                                                noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                                            }
                                        }
                                    });
                                });
                                $('#status').on('change', function () {
                                    $.ajax({
                                        type: 'POST',
                                        dataType: 'json',
                                        url: DmNConfig.base_url + 'admincp/change_status',
                                        data: {status: $(this).val(), id: <?php echo $ticket_data['id'];?>},
                                        success: function (data) {
                                            if (data.error) {
                                                noty($.parseJSON('{"text":"' + data.error + '","layout":"topRight","type":"error"}'));
                                            }
                                            else {
                                                noty($.parseJSON('{"text":"' + data.success + '","layout":"topRight","type":"success"}'));
                                            }
                                        }
                                    });
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="box span12">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Responses</h2>

                        <div class="box-icon">
                            <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                        </div>
                    </div>
                    <div class="box-content">
                        <div class="page-header">
                            <h3><?php echo $ticket_data['creator_account']; ?></h3>
                        </div>
                        <div class="row-fluid">
                            <div class="span4">
                                <?php echo $ticket_data['message']; ?>
                            </div>
                        </div>
                        <?php
                            if(!empty($ticket_replies)){
                                $pos = 0;
                                foreach($ticket_replies AS $replies){
                                    ?>
                                    <div class="page-header">
                                        <h3><?php echo ($replies['sender'] == $this->session->userdata(['admin' => 'username'])) ? 'Me' : $replies['sender']; ?>
                                            <small style="float:right;"><?php echo $replies['time_between']; ?></small>
                                        </h3>
                                    </div>
                                    <div class="row-fluid">
                                        <div class="span4">
                                            <?php echo $replies['reply']; ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="box span12">
                    <div class="box-header well" data-original-title>
                        <h2><i class="icon-edit"></i>Reply</h2>

                        <div class="box-icon">
                            <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                        </div>
                    </div>
                    <div class="box-content">
                        <?php
                            if(isset($reply_error)){
                                echo '<div class="alert alert-error">' . $reply_error . '</div>';
                            }
                            if(isset($reply_success)){
                                echo '<div class="alert alert-success">' . $reply_success . '</div>';
                            }
                        ?>
                        <form class="form-horizontal" method="post" action="">
                            <fieldset>
                                <div class="control-group">
                                    <label class="control-label" for="reply">Write Reply</label>

                                    <div class="controls">
                                        <textarea class="cleditor" id="reply" name="reply" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" name="submit_reply" class="btn btn-primary">Submit</button>
                                </div>
                            </fieldset>
                        </form>
                        <script>
                            $(document).ready(function () {
                                var editorName = $('.cleditor').attr('name');
                                CKEDITOR.replace(editorName);
                            });
                        </script>
                    </div>
                </div>
            </div>
            <?php
        }
    ?>
</div>