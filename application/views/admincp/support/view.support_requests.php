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
    ?>
    <div class="row-fluid">
        <div class="box span9">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Support Requests (<?php echo $ticket_count; ?>)</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="" id="ticket_form">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Subject</th>
                            <th>User</th>
                            <th>Created</th>
                            <th>Replies</th>
                            <th>Status</th>
                            <th>Action</th>
                            <th><input type="checkbox" name="check_all" id="check_all_tickets" data-no-uniform="true"/>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(!empty($tickets)){
                                foreach($tickets AS $ticket){
                                    ?>
                                    <tr>
                                        <td><?php echo $ticket['subject']; ?></td>
                                        <td><?php echo $ticket['user']; ?></td>
                                        <td><?php echo date('d/m/Y g:i a', $ticket['create_time']); ?></td>
                                        <td><?php echo $ticket['reply_count']; ?></td>
                                        <td><?php echo $this->Madmin->readable_status($ticket['status']); ?></td>
                                        <td>
                                            <a class="btn btn-success"
                                               href="<?php echo $this->config->base_url . '/admincp/view-request/' . $ticket['id']; ?>">
                                                <i class="icon-edit icon-white"></i>
                                                View Ticket
                                            </a>
                                        </td>
                                        <td><input type="checkbox" name="id[<?php echo $ticket['id']; ?>]"
                                                   data-no-uniform="true"/></td>
                                    </tr>
                                    <?php
                                }
                            } else{
                                echo '<tr><td colspan="7"><div class="alert alert-info">No Tickets Found</div></td></tr>';
                            }
                        ?>
                        </tbody>
                    </table>
                    <div style="text-align:right;">
                        <select id="set_status" name="set_status" class="input-small">
                            <option>Set Status</option>
                            <?php
                                foreach($status AS $key => $st){
                                    echo '<option value="' . $key . '">' . $st . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                </form>
                <?php
                    if(isset($pagination)):
                        ?>
                        <div style="padding:10px;text-align:center;">
                            <table style="width: 100%;">
                                <tr>
                                    <td><?php echo $pagination; ?></td>
                                </tr>
                            </table>
                        </div>
                    <?php
                    endif;
                ?>
            </div>
        </div>
        <div class="box span3">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Filter</h2>
            </div>
            <div class="box-content">
                <form class="form-inline" method="POST" action="">
                    <div class="control-group">
                        <label class="control-label" for="department">Department</label>
                        <div class="controls">
                            <select id="department" name="department[]" multiple data-rel="chosen">
                                <?php
                                    if(!empty($department_list)){
                                        foreach($department_list AS $key => $department){
                                            $selected = '';
                                            if($filter['filter_department'] != false){
                                                if(in_array($department['id'], $filter['filter_department'])){
                                                    $selected = 'selected';
                                                }
                                            }
                                            echo '<option value="' . $department['id'] . '" ' . $selected . '>' . $department['department_name'] . '</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="priority">Priority</label>
                        <div class="controls">
                            <select id="priority" name="priority[]" multiple data-rel="chosen">
                                <?php
                                    $priority = $this->Madmin->generate_priority(1, true, false);
                                    if(!empty($priority)){
                                        foreach($priority AS $key => $pr){
                                            $selected = '';
                                            if($filter['filter_priority'] != false){
                                                if(in_array($key, $filter['filter_priority'])){
                                                    $selected = 'selected';
                                                }
                                            }
                                            echo '<option value="' . $key . '" ' . $selected . '>' . $pr[1] . '</option>';
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="status">Status</label>
                        <div class="controls">
                            <select id="status" name="status[]" multiple data-rel="chosen">
                                <?php
                                    foreach($status AS $key => $st){
                                        $selected = '';
                                        if($filter['filter_status'] != false){
                                            if(in_array($key, $filter['filter_status'])){
                                                $selected = 'selected';
                                            }
                                        }
                                        echo '<option value="' . $key . '" ' . $selected . '>' . $st . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="order">Order By</label>
                        <div class="controls">
                            <select id="order" name="order">
                                <option value="1" <?php if($filter['sort_by'][0] == 1){
                                    echo 'selected';
                                }; ?>>Date Created
                                </option>
                                <option value="2" <?php if($filter['sort_by'][0] == 2){
                                    echo 'selected';
                                }; ?>>Last Reply
                                </option>
                            </select>
                            <select id="order2" name="order2" class="input-mini">
                                <option value="1" <?php if($filter['sort_by'][1] == 1){
                                    echo 'selected';
                                }; ?>>ASC
                                </option>
                                <option value="2" <?php if($filter['sort_by'][1] == 2){
                                    echo 'selected';
                                }; ?>>DESC
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" name="filter_tickets" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>