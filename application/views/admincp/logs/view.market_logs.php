<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/logs-market">Market Logs</a></li>
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
                <h2><i class="icon-edit"></i> Search logs by account</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="">
                    <div class="control-group">
                        <label class="control-label" for="date01">Date from</label>

                        <div class="controls">
                            <input type="text" class="input-xlarge datepicker" id="date01" name="date01"
                                   value="<?php echo date('m/d/Y', time()); ?>">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="date02">Date to</label>

                        <div class="controls">
                            <input type="text" class="input-xlarge datepicker" id="date02" name="date02"
                                   value="<?php echo date('m/d/Y', strtotime('+1 day')); ?>">
                        </div>
                    </div>
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
                        <label class="control-label" for="appendedInputButton">Seller Account</label>

                        <div class="controls">
                            <div class="input-append">
                                <input id="appendedInputButton" size="16" name="account" value="" type="text">
                                <button class="btn" type="submit" name="search_market_logs">Search</button>
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
                        <script>

                        </script>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Seller</th>
                                <th>Buyer</th>
                                <th>Server</th>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach($logs as $key => $value){
                                    echo '<tr>
							<td>' . date('j/n/Y H:i', strtotime($value['date'])) . '</td>
							<td class="center">' . $value['seller'] . '</td>
							<td class="center">' . $value['buyer'] . '</td>
							<td class="center">' . $this->website->get_title_from_server($value['server']) . '</td>
							<td class="center"><span id="log_item_' . $value['pos'] . '" data-info="' . $value['hex'] . '">' . $value['name'] . '</span></td>
							<td class="center">' . $value['price'] . ' ' . $value['payment_type'] . '</td>
							<td class="center">
								<a class="btn btn-success" href="' . $this->config->base_url . 'admincp/find-item/' . $value['server'] . '/' . $value['serial'] . '"><i class="icon-edit icon-white"></i> Find Item</a>
							</td>
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