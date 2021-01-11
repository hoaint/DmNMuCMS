<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/logs-paycall-transactions">PayCall
                    Transactions</a>
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
                <h2><i class="icon-edit"></i> Search transactions by account</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="">
                    <div class="control-group">
                        <label class="control-label" for="server">Server </label>

                        <div class="controls">
                            <select id="server" name="server">
                                <option value="All">All Servers</option>
                                <?php
                                    foreach($this->website->server_list() as $key => $value){
                                        echo '<option value="' . $key . '">' . $value['title'] . "</option>\n";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="appendedInputButton">Account</label>

                        <div class="controls">
                            <div class="input-append">
                                <input id="appendedInputButton" size="16" name="account" value="" type="text">
                                <button class="btn" type="submit" name="search_paycall_transactions">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2>Logs</h2>

            </div>
            <div class="box-content">
                <?php
                    if(isset($logs) && !empty($logs)):
                        ?>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Transaction Id</th>
                                <th>Amount</th>
                                <th>Account - Server</th>
                                <th>Credits
                                    Issued
                                </th>
                                <th>Order Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach($logs as $key => $value){
                                    echo '<tr>
							<td>' . $value['transaction'] . '</td>
							<td>' . $value['amount'] . '</td>
							<td class="center">' . $value['acc'] . ' - ' . $this->website->get_title_from_server($value['server']) . '</td>
							<td class="center">' . $value['credits'] . '</td>
							<td class="center">' . $value['order_date'] . '</td>
						  </tr>';
                                }
                            ?>
                            </tbody>
                        </table>
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
                    <?php
                    else:
                        echo '<div class="alert alert-info">No Logs Found</div>';
                    endif;
                ?>
            </div>
        </div>
    </div>
</div>