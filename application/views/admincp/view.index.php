<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li>Dashboard</li>
        </ul>
    </div>
    <div class="sortable row-fluid">
        <a class="well span3 top-block" href="<?php echo $this->config->base_url; ?>admincp/account-manager">
            <div>Total Accounts</div>
            <div><?php echo $total_acccounts; ?></div>
        </a>
        <a class="well span3 top-block" href="<?php echo $this->config->base_url; ?>admincp/character-manager">
            <div>Total Characters</div>
            <div><?php echo $total_characters; ?></div>
        </a>
        <a class="well span3 top-block" href="#">
            <div>Total Guilds</div>
            <div><?php echo $total_guilds; ?></div>
        </a>
        <a class="well span3 top-block" href="#">
            <div>Online</div>
            <div><?php echo $total_online; ?></div>
        </a>
    </div>
    <?php
        if(!empty($tickets)){
            echo '<div class="alert alert-success span11">You have ' . count($tickets) . ' new support requests. <a href="' . $this->config->base_url . 'admincp/support-requests">Click To View</a></div>';
        }
        if($cron == false){
            echo '<div class="alert alert-error span11">Your scheduler is not working. please check your <a href="' . $this->config->base_url . 'admincp/manage-settings/scheduler" target="_blank">settings</a> and fix it.</div>';
        } else{
            if(time() - $cron > 720 * 2){
                echo '<div class="alert alert-error span11">Your scheduler last run on ' . date('d/M/Y H:i', $cron) . '. please check your <a href="' . $this->config->base_url . 'admincp/manage-settings/scheduler" target="_blank">settings</a> and fix it.</div>';
            }
        }
    ?>
    <div style="clear:both;"></div>
    <!--/span-->
    <div class="box span6">
        <div class="box-header well">
            <h2><i class="icon-list"></i> Registration Statistics</h2>

        </div>
        <div class="box-content">
            <ul class="dashboard-list">
                <?php
                    if($this->website->is_multiple_accounts() == true):
                        foreach($this->website->server_list() AS $key => $server):
                            ?>
                            <li>
                                <a href="#">
                                    <span class="green"><?php echo $stats[$key]['reg_day']; ?></span> Registrations
                                    Today <?php echo $this->website->get_title_from_server($key); ?>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="red"><?php echo $stats[$key]['reg_week']; ?></span> Registrations This
                                    Week <?php echo $this->website->get_title_from_server($key); ?>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="blue"><?php echo $stats[$key]['reg_month']; ?></span> Registrations This
                                    Month <?php echo $this->website->get_title_from_server($key); ?>
                                </a>
                            </li>
                        <?php
                        endforeach;
                    else:
                        ?>
                        <li>
                            <a href="#"> <span class="green"><?php echo $stats['reg_day']; ?></span> Registrations Today
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="red"><?php echo $stats['reg_week']; ?></span> Registrations This Week </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="blue"><?php echo $stats['reg_month']; ?></span> Registrations This Month
                            </a>
                        </li>
                        <li>
                            <a href="#"> <span class="green"><?php echo $stats['activ_day']; ?></span> Activations Today
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="red"><?php echo $stats['activ_week']; ?></span> Activations This Week </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="blue"><?php echo $stats['activ_month']; ?></span> Activations This Month
                            </a>
                        </li>
                    <?php
                    endif;
                ?>
            </ul>
        </div>
    </div>
    <!--/span-->
    <div class="box span5">
        <div class="box-header well">
            <h2><i class="icon-list"></i> Admin Login Logs</h2>

        </div>
        <div class="box-content">
            <table class="table">
                <thead>
                <tr>
                    <th>Username</th>
                    <th>Ip</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    if(count($login_attemts) > 0):
                        foreach($login_attemts as $attemt):
                            ?>
                            <tr>
                                <td><span class="green"><?php echo $attemt['memb___id']; ?></span></td>
                                <td><?php echo $attemt['ip']; ?></td>
                                <td><?php echo date('Y-m-d h:i:s', strtotime($attemt['time'])); ?></td>
                            </tr>
                        <?php
                        endforeach;
                    endif;
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--/span-->
</div>