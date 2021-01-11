<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/donate">Donate Settings</a></li>
        </ul>
    </div>
    <?php
        if(isset($error)){
            echo '<div class="alert alert-error span9">' . $error . '</div>';
        }
        if(isset($success)){
            echo '<div class="alert alert-success span9">' . $success . '</div>';
        }
    ?>
    <div class="row-fluid">
        <div class="span12">
            <ul class="nav nav-pills">
                <li role="presentation" class="dropdown active">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                       aria-expanded="false">Paypal<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#paypalsettings" aria-controls="paypalsettings" role="tab" data-toggle="tab"
                               onclick="App.loadDonationSettings('<?php echo $default_server; ?>', 'paypal');">Manage
                                Settings</a>
                        </li>
                        <li>
                            <a href="#paypalpackages" aria-controls="paypalpackages" role="tab" data-toggle="tab">Manage
                                Packages</a>
                        </li>
                    </ul>
                </li>
                <li role="presentation"><a href="#paymentwall" aria-controls="paymentwall" role="tab" data-toggle="tab"
                                           onclick="App.loadDonationSettings('<?php echo $default_server; ?>', 'paymentwall');">Paymentwall</a>
                </li>
                <li role="presentation"><a href="#fortumo" aria-controls="fortumo" role="tab" data-toggle="tab"
                                           onclick="App.loadDonationSettings('<?php echo $default_server; ?>', 'fortumo');">Fortumo</a>
                </li>
                <li role="presentation"><a href="#paygol" aria-controls="paygol" role="tab" data-toggle="tab"
                                           onclick="App.loadDonationSettings('<?php echo $default_server; ?>', 'paygol');">PayGol</a>
                </li>
                <li role="presentation" class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                       aria-expanded="false">2CheckOut<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#2checkoutsettings" aria-controls="2checkoutsettings" role="tab" data-toggle="tab"
                               onclick="App.loadDonationSettings('<?php echo $default_server; ?>', '2checkout');">Manage
                                Settings</a>
                        </li>
                        <li>
                            <a href="#2checkoutpackages" aria-controls="2checkoutpackages" role="tab" data-toggle="tab">Manage
                                Packages</a>
                        </li>
                    </ul>
                </li>
                <li role="presentation" class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                       aria-expanded="false">PagSeguro<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#pagsegurosettings" aria-controls="pagsegurosettings" role="tab" data-toggle="tab"
                               onclick="App.loadDonationSettings('<?php echo $default_server; ?>', 'pagseguro');">Manage
                                Settings</a>
                        </li>
                        <li>
                            <a href="#pagseguropackages" aria-controls="pagseguropackages" role="tab" data-toggle="tab">Manage
                                Packages</a>
                        </li>
                    </ul>
                </li>
                <li role="presentation" class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                       aria-expanded="false">PayCall<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#paycallsettings" aria-controls="paycallsettings" role="tab" data-toggle="tab"
                               onclick="App.loadDonationSettings('<?php echo $default_server; ?>', 'paycall');">Manage
                                Settings</a>
                        </li>
                        <li>
                            <a href="#paycallpackages" aria-controls="paycallpackages" role="tab" data-toggle="tab">Manage
                                Packages</a>
                        </li>
                    </ul>
                </li>
                <li role="presentation"><a href="#superrewards" aria-controls="superrewards" role="tab"
                                           data-toggle="tab"
                                           onclick="App.loadDonationSettings('<?php echo $default_server; ?>', 'superrewards');">SuperRewards</a>
                </li>
                <li role="presentation" class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                       aria-expanded="false">Interkassa<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#interkassasettings" aria-controls="interkassasettings" role="tab"
                               data-toggle="tab"
                               onclick="App.loadDonationSettings('<?php echo $default_server; ?>', 'interkassa');">Manage
                                Settings</a>
                        </li>
                        <li><a href="#interkassapackages" aria-controls="interkassapackages" role="tab"
                               data-toggle="tab">Manage Packages</a></li>
                    </ul>
                </li>
                <li role="presentation" class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                       aria-expanded="false">Cuenta Digital<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#cuenta_digitalsettings" aria-controls="cuenta_digitalsettings" role="tab"
                               data-toggle="tab"
                               onclick="App.loadDonationSettings('<?php echo $default_server; ?>', 'cuenta_digital');">Manage
                                Settings</a>
                        </li>
                        <li><a href="#cuenta_digitalpackages" aria-controls="cuenta_digitalpackages" role="tab"
                               data-toggle="tab">Manage Packages</a></li>
                    </ul>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            App.loadDonationSettings('<?php echo $default_server;?>', 'paypal');

            $('#type').on("change", function () {
                if ($(this).val() == 2) {
                    $('#express_checkout').show();
                }
                else {
                    $('#express_checkout').hide();
                }
            });
        });
    </script>
    <div class="row-fluid">
        <div class="box span12">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="paypalsettings">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Paypal Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="paypal_settings_form">
                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="active">Status </label>

                                <div class="controls">
                                    <select id="active" name="active">
                                        <option value="0">Inactive</option>
                                        <option value="1">Active</option>
                                    </select>

                                    <p class="help-block">Use paypal donation method.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="type">Type </label>

                                <div class="controls">
                                    <select id="type" name="type">
                                        <option value="1">Instant Payment Notification</option>
                                        <option value="2">Express CheckOut</option>
                                    </select>

                                    <p class="help-block">Use paypal donation method.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sandbox">Sanbox</label>

                                <div class="controls">
                                    <select id="sandbox" name="sandbox">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Use PayPal sanbox for testing.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="email">Email </label>

                                <div class="controls">
                                    <input type="text" class="span4 typeahead" id="email" name="email" value=""/>

                                    <p class="help-block">Paypal donation email.</p>
                                </div>
                            </div>
                            <div id="express_checkout" style="display:none;">
                                <div class="control-group">
                                    <label class="control-label" for="api_username">Api Username </label>

                                    <div class="controls">
                                        <input type="text" class="span4 typeahead" id="api_username" name="api_username"
                                               value=""/>

                                        <p class="help-block">Paypal Api Username.</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="api_password">Api Password </label>

                                    <div class="controls">
                                        <input type="text" class="span4 typeahead" id="api_password" name="api_password"
                                               value=""/>

                                        <p class="help-block">Paypal Api Password.</p>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="api_signature">Api Signature </label>

                                    <div class="controls">
                                        <input type="text" class="span4 typeahead" id="api_signature"
                                               name="api_signature" value=""/>

                                        <p class="help-block">Paypal Api Signature.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="punish_player">Punish Player </label>

                                <div class="controls">
                                    <select id="punish_player" name="punish_player">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Block player on paypal refund.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_type">Reward Type</label>

                                <div class="controls">
                                    <select id="reward_type" name="reward_type">
                                        <option value="1">Credits 1</option>
                                        <option value="2">Credits 2</option>
                                    </select>

                                    <p class="help-block">Which donation points user will receive after paypal
                                        donation.</p>

                                    <p>For reward types check your credits settings <a
                                                href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                                target="_blank">here</a></p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="paypal_fee">Transaction Fee</label>

                                <div class="controls">
                                    <select id="paypal_fee" name="paypal_fee">
                                        <?php for($i = .00; $i <= 100; $i += 0.01){ ?>
                                            <option value="<?php echo round($i, 3); ?>"><?php echo round($i, 3); ?>%
                                            </option>
                                        <?php } ?>
                                    </select>

                                    <p class="help-block">Paypal transaction fee.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="paypal_fixed_fee">Fixed Fee</label>

                                <div class="controls">
                                    <input type="text" class="span4 typeahead" id="paypal_fixed_fee"
                                           name="paypal_fixed_fee" value="" placeholder="0.00"/>

                                    <p class="help-block">Paypal fixed fee.</p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_paypal_settings"
                                        id="edit_paypal_settings">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="paypalpackages">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Paypal Packages</h2>
                    </div>
                    <div class="box-content">
                        <div id="donate_package_list">
                            <table class="table table-striped table-bordered bootstrap-datatable datatable"
                                   id="donate_sortable">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Currency</th>
                                    <th>Reward Points</th>
                                    <th>Server</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="donate_sortable_content" style="cursor: move;">
                                <?php foreach($packages_paypal as $pack): ?>
                                    <tr id="<?php echo $pack['id']; ?>">
                                        <td><input class="input-medium" type="text"
                                                   id="pack_title_<?php echo $pack['id']; ?>"
                                                   value="<?php echo $pack['package']; ?>"/></td>
                                        <td class="center"><input class="input-small" type="text"
                                                                  id="pack_price_<?php echo $pack['id']; ?>"
                                                                  value="<?php echo $pack['price']; ?>"/></td>
                                        <td class="center"><input class="input-small" type="text"
                                                                  id="pack_currency_<?php echo $pack['id']; ?>"
                                                                  value="<?php echo $pack['currency']; ?>"/></td>
                                        <td class="center"><input class="input-small" type="text"
                                                                  id="pack_reward_<?php echo $pack['id']; ?>"
                                                                  value="<?php echo $pack['reward']; ?>"/></td>
                                        <td class="center">
                                            <select id="pack_server_<?php echo $pack['id']; ?>" class="input-medium">
                                                <?php
                                                    foreach($server_list as $key => $server):
                                                        ?>
                                                        <option value="<?php echo $key; ?>"
                                                                <?php if($key == $pack['server']){ ?>selected="selected"<?php } ?>><?php echo $server_list[$key]['title']; ?></option>
                                                    <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="center" id="status_icon_<?php echo $pack['id']; ?>">
                                            <?php if($pack['status'] == 1): ?>
                                                <span class="label label-success">Active</span>
                                            <?php else: ?>
                                                <span class="label label-important">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="center">
                                            <?php if($pack['status'] == 1): ?>
                                                <a class="btn btn-danger" href="#"
                                                   id="status_button_<?php echo $pack['id']; ?>"
                                                   onclick="App.changePaypalStatus(<?php echo $pack['id']; ?>, 0);">
                                                    <i class="icon-edit icon-white"></i> Disable </a>
                                            <?php else: ?>
                                                <a class="btn btn-success" href="#"
                                                   id="status_button_<?php echo $pack['id']; ?>"
                                                   onclick="App.changePaypalStatus(<?php echo $pack['id']; ?>, 1);">
                                                    <i class="icon-edit icon-white"></i> Enable </a>
                                            <?php endif; ?>

                                            <a class="btn btn-info" href="#"
                                               onclick="App.editPaypal(<?php echo $pack['id']; ?>);">
                                                <i class="icon-edit icon-white"></i> Edit </a>
                                            <a class="btn btn-danger" href="#"
                                               onclick="App.deletePaypal(<?php echo $pack['id']; ?>);">
                                                <i class="icon-trash icon-white"></i> Delete </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <table class="table table-striped table-bordered bootstrap-datatable datatable">
                            <tbody>
                            <tr>
                                <td><input class="input-medium" type="text" id="title_new" placeholder="Title"
                                           value=""/></td>
                                <td><input class="input-small" type="text" id="price_new" placeholder="Price" value=""/>
                                </td>
                                <td><input class="input-small" type="text" id="currency_new" placeholder="Currency"
                                           value=""/></td>
                                <td><input class="input-small" type="text" id="reward_new" placeholder="Reward"
                                           value=""/></td>
                                <td class="center">
                                    <select id="server_new" class="input-medium">
                                        <?php
                                            foreach($server_list as $key => $server):
                                                ?>
                                                <option
                                                        value="<?php echo $key; ?>"><?php echo $server_list[$key]['title']; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <a class="btn btn-success" href="#" onclick="App.addPaypalPackage();">
                                        <i class="icon-edit icon-white"></i> Add Package </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="paymentwall">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> PaymentWall Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="paymentwall_settings_form">
                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="active">Status </label>

                                <div class="controls">
                                    <select id="active" name="active">
                                        <option value="0">Inactive</option>
                                        <option value="1">Active</option>
                                    </select>

                                    <p class="help-block">Use paymentwall donation method.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="api_key">Api Key</label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="api_key" name="api_key" value=""/>

                                    <p class="help-block">PaymentWall application key.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="secret_key">Secret Key</label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="secret_key" name="secret_key"
                                           value=""/>

                                    <p class="help-block">PaymentWall secret key.</p>
                                </div>
                            </div>
							<div class="control-group">
                                <label class="control-label" for="sign_version">Sign Version</label>
                                <div class="controls">
                                    <select id="sign_version" name="sign_version">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                    </select>

                                    <p class="help-block">Pingback signature version.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="widget">Widget</label>
                                <div class="controls">
                                    <select id="widget" name="widget">
                                        <option value="m2_1">Paymentwall Mobile</option>
                                        <option value="m1_1">MobilePay+</option>
                                        <option value="p4_1">2-Click Payments</option>
                                        <option value="p3_1">Paymentwall Combo</option>
                                        <option value="p1_1">Paymentwall Multi</option>
                                        <option value="p2_1">Paymentwall Uni</option>
                                        <option value="w1_1">Offerwall Classic</option>
                                        <option value="s2_1">Standalone Offer</option>
                                        <option value="s1_1"> Standalone Piano Bar</option>
                                        <option value="s3_1">Piano Bar Slim</option>
                                    </select>

                                    <p class="help-block">Choose same widget as in your paymentwall settings.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="width">Width </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="width" name="width" value=""/>

                                    <p class="help-block">PaymentWall widget width.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_type">Reward Type</label>

                                <div class="controls">
                                    <select id="reward_type" name="reward_type">
                                        <option value="1">Credits 1</option>
                                        <option value="2">Credits 2</option>
                                    </select>

                                    <p class="help-block">Which donation points user will receive after paymentwall
                                        donation.</p>

                                    <p>For reward types check your credits settings <a
                                                href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                                target="_blank">here</a></p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="pingback">PostBack Url</label>

                                <div class="controls">
                                    <span id="pingback"><?php echo $this->config->base_url; ?>payment/paymentwall</span>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_paymentwall_settings"
                                        id="edit_paymentwall_settings">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="fortumo">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Fortumo Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="fortumo_settings_form">
                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="active">Status </label>

                                <div class="controls">
                                    <select id="active" name="active">
                                        <option value="0">Inactive</option>
                                        <option value="1">Active</option>
                                    </select>

                                    <p class="help-block">Use fortumo donation method.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sandbox">Sanbox</label>

                                <div class="controls">
                                    <select id="sandbox" name="sandbox">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Use Fortumo sanbox for testing.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="service_id">Id </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="service_id" name="service_id"
                                           value=""/>

                                    <p class="help-block">Fortumo service key.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="secret">Secret </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="secret" name="secret" value=""/>

                                    <p class="help-block">Fortumo service secret key.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="allowed_ip_list">Allowed IP List</label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" data-role="tagsinput"
                                           id="allowed_ip_list" name="allowed_ip_list" value=""/>

                                    <p class="help-block">Fortumo ip list. Do not edit if you don't know what are you
                                        doing.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_type">Reward Type</label>

                                <div class="controls">
                                    <select id="reward_type" name="reward_type">
                                        <option value="1">Credits 1</option>
                                        <option value="2">Credits 2</option>
                                    </select>

                                    <p class="help-block">Which donation points user will receive after fortumo
                                        donation.</p>

                                    <p>For reward types check your credits settings <a
                                                href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                                target="_blank">here</a></p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_fortumo_settings"
                                        id="edit_fortumo_settings">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="paygol">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> PayGol Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="paygol_settings_form">
                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="active">Status </label>

                                <div class="controls">
                                    <select id="active" name="active">
                                        <option value="0">Inactive</option>
                                        <option value="1">Active</option>
                                    </select>

                                    <p class="help-block">Use paygol donation method.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="service_id">Id </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="service_id" name="service_id"
                                           value=""/>

                                    <p class="help-block">Paygol service key.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward">Reward </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="reward" name="reward" value=""/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_type">Reward Type</label>

                                <div class="controls">
                                    <select id="reward_type" name="reward_type">
                                        <option value="1">Credits 1</option>
                                        <option value="2">Credits 2</option>
                                    </select>

                                    <p class="help-block">Which donation points user will receive after paygol
                                        donation.</p>

                                    <p>For reward types check your credits settings <a
                                                href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                                target="_blank">here</a></p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="currency_code">Currency </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="currency_code" name="currency_code"
                                           value=""/>

                                    <p class="help-block">PayGol service currency code.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="service_price">Price </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="service_price" name="service_price"
                                           value=""/>

                                    <p class="help-block">PayGol service price.</p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_paygol_settings"
                                        id="edit_paygol_settings">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="2checkoutsettings">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> 2CheckOut Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="2checkout_settings_form">
                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="active">Status </label>

                                <div class="controls">
                                    <select id="active" name="active">
                                        <option value="0">Inactive</option>
                                        <option value="1">Active</option>
                                    </select>

                                    <p class="help-block">Use 2checkout donation method.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="seller_id">Seller Id </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="seller_id" name="seller_id"
                                           value=""/>

                                    <p class="help-block">Can be found in your 2CheckOut account.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="private_key">Api Secret Key</label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="private_key" name="private_key"
                                           value=""/>

                                    <p class="help-block">Can be found in your 2CheckOut account.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="private_secret_word">Secret Word</label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="private_secret_word"
                                           name="private_secret_word" value=""/>

                                    <p class="help-block">Can be found in your 2CheckOut account.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_type">Reward Type</label>

                                <div class="controls">
                                    <select id="reward_type" name="reward_type">
                                        <option value="1">Credits 1</option>
                                        <option value="2">Credits 2</option>
                                    </select>

                                    <p class="help-block">Which donation points user will receive after 2checkout
                                        donation.</p>

                                    <p>For reward types check your credits settings <a
                                                href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                                target="_blank">here</a></p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_2checkout_settings"
                                        id="edit_2checkout_settings">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="2checkoutpackages">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> 2CheckOut Packages</h2>
                    </div>
                    <div class="box-content">
                        <div id="donate_package_list">
                            <table class="table table-striped table-bordered bootstrap-datatable datatable"
                                   id="donate_sortable_checkout">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Currency</th>
                                    <th>Reward Points</th>
                                    <th>Server</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="donate_sortable_content_checkout" style="cursor: move;">
                                <?php foreach($packages_twocheckout as $pack): ?>
                                    <tr id="2checkout_<?php echo $pack['id']; ?>">
                                        <td><input class="input-medium" type="text"
                                                   id="pack_title_2checkout_<?php echo $pack['id']; ?>"
                                                   value="<?php echo $pack['package']; ?>"/></td>
                                        <td class="center"><input class="input-small" type="text"
                                                                  id="pack_price_2checkout_<?php echo $pack['id']; ?>"
                                                                  value="<?php echo $pack['price']; ?>"/></td>
                                        <td class="center"><input class="input-small" type="text"
                                                                  id="pack_currency_2checkout_<?php echo $pack['id']; ?>"
                                                                  value="<?php echo $pack['currency']; ?>"/></td>
                                        <td class="center"><input class="input-small" type="text"
                                                                  id="pack_reward_2checkout_<?php echo $pack['id']; ?>"
                                                                  value="<?php echo $pack['reward']; ?>"/></td>
                                        <td class="center">
                                            <select id="pack_server_2checkout_<?php echo $pack['id']; ?>"
                                                    class="input-medium">
                                                <?php
                                                    foreach($server_list as $key => $server):
                                                        ?>
                                                        <option value="<?php echo $key; ?>"
                                                                <?php if($key == $pack['server']){ ?>selected="selected"<?php } ?>><?php echo $server_list[$key]['title']; ?></option>
                                                    <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="center" id="status_icon_2checkout_<?php echo $pack['id']; ?>">
                                            <?php if($pack['status'] == 1): ?>
                                                <span class="label label-success">Active</span>
                                            <?php else: ?>
                                                <span class="label label-important">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="center">
                                            <?php if($pack['status'] == 1): ?>
                                                <a class="btn btn-danger" href="#"
                                                   id="status_button_2checkout_<?php echo $pack['id']; ?>"
                                                   onclick="App.change2CheckOutStatus(<?php echo $pack['id']; ?>, 0);">
                                                    <i class="icon-edit icon-white"></i> Disable </a>
                                            <?php else: ?>
                                                <a class="btn btn-success" href="#"
                                                   id="status_button_2checkout_<?php echo $pack['id']; ?>"
                                                   onclick="App.change2CheckOutStatus(<?php echo $pack['id']; ?>, 1);">
                                                    <i class="icon-edit icon-white"></i> Enable </a>
                                            <?php endif; ?>

                                            <a class="btn btn-info" href="#"
                                               onclick="App.edit2CheckOut(<?php echo $pack['id']; ?>);">
                                                <i class="icon-edit icon-white"></i> Edit </a>
                                            <a class="btn btn-danger" href="#"
                                               onclick="App.delete2CheckOut(<?php echo $pack['id']; ?>);">
                                                <i class="icon-trash icon-white"></i> Delete </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <table class="table table-striped table-bordered bootstrap-datatable datatable">
                            <tbody>
                            <tr>
                                <td><input class="input-medium" type="text" id="title_new_2checkout" placeholder="Title"
                                           value=""/></td>
                                <td><input class="input-small" type="text" id="price_new_2checkout" placeholder="Price"
                                           value=""/></td>
                                <td><input class="input-small" type="text" id="currency_new_2checkout"
                                           placeholder="Currency" value=""/></td>
                                <td><input class="input-small" type="text" id="reward_new_2checkout"
                                           placeholder="Reward" value=""/></td>
                                <td class="center">
                                    <select id="server_new_2checkout" class="input-medium">
                                        <?php
                                            foreach($server_list as $key => $server):
                                                ?>
                                                <option
                                                        value="<?php echo $key; ?>"><?php echo $server_list[$key]['title']; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <a class="btn btn-success" href="#" onclick="App.add2CheckOutPackage();">
                                        <i class="icon-edit icon-white"></i> Add Package </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="pagsegurosettings">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> PagSeguro Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="pagseguro_settings_form">
                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="active">Status </label>

                                <div class="controls">
                                    <select id="active" name="active">
                                        <option value="0">Inactive</option>
                                        <option value="1">Active</option>
                                    </select>

                                    <p class="help-block">Use pagseguro donation method.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="email">Email </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="email" name="email" value=""/>

                                    <p class="help-block">Email which you used when you registered PagSeguro
                                        account.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="token">Token</label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="token" name="token" value=""/>

                                    <p class="help-block">Can be generated in PagSeguro account.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Notification Url</label>

                                <div class="controls">
                                    <?php echo $this->config->base_url; ?>
                                    payment/pagseguro/<?php echo $default_server; ?>
                                    <p class="help-block">PagSeguro notification url need to be set in pagseguro
                                        account.</p>

                                    <p class="help-block"><a
                                                href="https://pagseguro.uol.com.br/preferencias/integracoes.jhtml"
                                                target="_blank">https://pagseguro.uol.com.br/preferencias/integracoes.jhtml</a>
                                    </p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_type">Reward Type</label>

                                <div class="controls">
                                    <select id="reward_type" name="reward_type">
                                        <option value="1">Credits 1</option>
                                        <option value="2">Credits 2</option>
                                    </select>

                                    <p class="help-block">Which donation points user will receive after pagseguro
                                        donation.</p>

                                    <p>For reward types check your credits settings <a
                                                href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                                target="_blank">here</a></p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_pagseguro_settings"
                                        id="edit_pagseguro_settings">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="pagseguropackages">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> PagSeguro Packages</h2>
                    </div>
                    <div class="box-content">
                        <div id="donate_package_list">
                            <table class="table table-striped table-bordered bootstrap-datatable datatable"
                                   id="donate_sortable_pagseguro">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Currency</th>
                                    <th>Reward Points</th>
                                    <th>Server</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="donate_sortable_content_pagseguro" style="cursor: move;">
                                <?php foreach($packages_pagseguro as $pack): ?>
                                    <tr id="pagseguro_<?php echo $pack['id']; ?>">
                                        <td><input class="input-medium" type="text"
                                                   id="pack_title_pagseguro_<?php echo $pack['id']; ?>"
                                                   value="<?php echo $pack['package']; ?>"/></td>
                                        <td class="center"><input class="input-small" type="text"
                                                                  id="pack_price_pagseguro_<?php echo $pack['id']; ?>"
                                                                  value="<?php echo $pack['price']; ?>"/></td>
                                        <td class="center"><input class="input-small" type="text"
                                                                  id="pack_currency_pagseguro_<?php echo $pack['id']; ?>"
                                                                  value="<?php echo $pack['currency']; ?>"/></td>
                                        <td class="center"><input class="input-small" type="text"
                                                                  id="pack_reward_pagseguro_<?php echo $pack['id']; ?>"
                                                                  value="<?php echo $pack['reward']; ?>"/></td>
                                        <td class="center">
                                            <select id="pack_server_pagseguro_<?php echo $pack['id']; ?>"
                                                    class="input-medium">
                                                <?php
                                                    foreach($server_list as $key => $server):
                                                        ?>
                                                        <option value="<?php echo $key; ?>"
                                                                <?php if($key == $pack['server']){ ?>selected="selected"<?php } ?>><?php echo $server_list[$key]['title']; ?></option>
                                                    <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="center" id="status_icon_pagseguro_<?php echo $pack['id']; ?>">
                                            <?php if($pack['status'] == 1): ?>
                                                <span class="label label-success">Active</span>
                                            <?php else: ?>
                                                <span class="label label-important">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="center">
                                            <?php if($pack['status'] == 1): ?>
                                                <a class="btn btn-danger" href="#"
                                                   id="status_button_pagseguro_<?php echo $pack['id']; ?>"
                                                   onclick="App.changePagSeguroStatus(<?php echo $pack['id']; ?>, 0);">
                                                    <i class="icon-edit icon-white"></i> Disable </a>
                                            <?php else: ?>
                                                <a class="btn btn-success" href="#"
                                                   id="status_button_pagseguro_<?php echo $pack['id']; ?>"
                                                   onclick="App.changePagSeguroStatus(<?php echo $pack['id']; ?>, 1);">
                                                    <i class="icon-edit icon-white"></i> Enable </a>
                                            <?php endif; ?>

                                            <a class="btn btn-info" href="#"
                                               onclick="App.editPagSeguro(<?php echo $pack['id']; ?>);">
                                                <i class="icon-edit icon-white"></i> Edit </a>
                                            <a class="btn btn-danger" href="#"
                                               onclick="App.deletePagSeguro(<?php echo $pack['id']; ?>);">
                                                <i class="icon-trash icon-white"></i> Delete </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <table class="table table-striped table-bordered bootstrap-datatable datatable">
                            <tbody>
                            <tr>
                                <td><input class="input-medium" type="text" id="title_new_pagseguro" placeholder="Title"
                                           value=""/></td>
                                <td><input class="input-small" type="text" id="price_new_pagseguro" placeholder="Price"
                                           value=""/></td>
                                <td><input class="input-small" type="text" id="currency_new_pagseguro"
                                           placeholder="Currency" value=""/></td>
                                <td><input class="input-small" type="text" id="reward_new_pagseguro"
                                           placeholder="Reward" value=""/></td>
                                <td class="center">
                                    <select id="server_new_pagseguro" class="input-medium">
                                        <?php
                                            foreach($server_list as $key => $server):
                                                ?>
                                                <option
                                                        value="<?php echo $key; ?>"><?php echo $server_list[$key]['title']; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <a class="btn btn-success" href="#" onclick="App.addPagSeguroPackage();">
                                        <i class="icon-edit icon-white"></i> Add Package </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="paycallsettings">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> PayCall Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="paycall_settings_form">
                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="active">Status </label>

                                <div class="controls">
                                    <select id="active" name="active">
                                        <option value="0">Inactive</option>
                                        <option value="1">Active</option>
                                    </select>

                                    <p class="help-block">Use paycall donation method.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="sandbox">Sanbox</label>

                                <div class="controls">
                                    <select id="sandbox" name="sandbox">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Use paycall sanbox for testing.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="business_code">Business Code </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="business_code" name="business_code"
                                           value=""/>

                                    <p class="help-block">PayCall Business code.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_type">Reward Type</label>

                                <div class="controls">
                                    <select id="reward_type" name="reward_type">
                                        <option value="1">Credits 1</option>
                                        <option value="2">Credits 2</option>
                                    </select>

                                    <p class="help-block">Which donation points user will receive after paycall
                                        donation.</p>

                                    <p>For reward types check your credits settings <a
                                                href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                                target="_blank">here</a></p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_paycall_settings"
                                        id="edit_paycall_settings">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="paycallpackages">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Paycall Packages</h2>
                    </div>
                    <div class="box-content">
                        <div id="donate_package_list">
                            <table class="table table-striped table-bordered bootstrap-datatable datatable"
                                   id="donate_sortable_paycall">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Reward Points</th>
                                    <th>Server</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="donate_sortable_paycall_content" style="cursor: move;">
                                <?php foreach($packages_paycall as $pack): ?>
                                    <tr id="paycall_<?php echo $pack['id']; ?>">
                                        <td><input class="input-medium" type="text"
                                                   id="pack_title_paycall_<?php echo $pack['id']; ?>"
                                                   value="<?php echo $pack['package']; ?>"/></td>
                                        <td class="center"><input class="input-small" type="text"
                                                                  id="pack_price_paycall_<?php echo $pack['id']; ?>"
                                                                  value="<?php echo $pack['price']; ?>"/></td>
                                        <td class="center"><input class="input-small" type="text"
                                                                  id="pack_reward_paycall_<?php echo $pack['id']; ?>"
                                                                  value="<?php echo $pack['reward']; ?>"/></td>
                                        <td class="center">
                                            <select id="pack_server_paycall_<?php echo $pack['id']; ?>"
                                                    class="input-medium">
                                                <?php
                                                    foreach($server_list as $key => $server):
                                                        ?>
                                                        <option value="<?php echo $key; ?>"
                                                                <?php if($key == $pack['server']){ ?>selected="selected"<?php } ?>><?php echo $server_list[$key]['title']; ?></option>
                                                    <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="center" id="status_icon_paycall_<?php echo $pack['id']; ?>">
                                            <?php if($pack['status'] == 1): ?>
                                                <span class="label label-success">Active</span>
                                            <?php else: ?>
                                                <span class="label label-important">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="center">
                                            <?php if($pack['status'] == 1): ?>
                                                <a class="btn btn-danger" href="#"
                                                   id="status_button_paycall_<?php echo $pack['id']; ?>"
                                                   onclick="App.changePaycallStatus(<?php echo $pack['id']; ?>, 0);">
                                                    <i class="icon-edit icon-white"></i> Disable </a>
                                            <?php else: ?>
                                                <a class="btn btn-success" href="#"
                                                   id="status_button_paycall_<?php echo $pack['id']; ?>"
                                                   onclick="App.changePaycallStatus(<?php echo $pack['id']; ?>, 1);">
                                                    <i class="icon-edit icon-white"></i> Enable </a>
                                            <?php endif; ?>

                                            <a class="btn btn-info" href="#"
                                               onclick="App.editPaycall(<?php echo $pack['id']; ?>);">
                                                <i class="icon-edit icon-white"></i> Edit </a>
                                            <a class="btn btn-danger" href="#"
                                               onclick="App.deletePaycall(<?php echo $pack['id']; ?>);">
                                                <i class="icon-trash icon-white"></i> Delete </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <table class="table table-striped table-bordered bootstrap-datatable datatable">
                            <tbody>
                            <tr>
                                <td><input class="input-medium" type="text" id="title_new_paycall" placeholder="Title"
                                           value=""/></td>
                                <td><input class="input-small" type="text" id="price_new_paycall" placeholder="Price"
                                           value=""/></td>
                                <td><input class="input-small" type="text" id="reward_new_paycall" placeholder="Reward"
                                           value=""/></td>
                                <td class="center">
                                    <select id="server_new_paycall" class="input-medium">
                                        <?php
                                            foreach($server_list as $key => $server):
                                                ?>
                                                <option
                                                        value="<?php echo $key; ?>"><?php echo $server_list[$key]['title']; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <a class="btn btn-success" href="#" onclick="App.addPaycallPackage();">
                                        <i class="icon-edit icon-white"></i> Add Package </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="superrewards">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> SuperRewards Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="superrewards_settings_form">
                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="active">Superrewards Status </label>

                                <div class="controls">
                                    <select id="active" name="active">
                                        <option value="0">Inactive</option>
                                        <option value="1">Active</option>
                                    </select>

                                    <p class="help-block">Use superrewards donation method.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="app_hash">App Hash</label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="app_hash" name="app_hash" value=""/>

                                    <p class="help-block">Superrewards app hash.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="postback_key">Postback Key</label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="postback_key" name="postback_key"
                                           value=""/>

                                    <p class="help-block">Superrewards postback key.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="allowed_ip_list">Allowed IP List</label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" data-role="tagsinput"
                                           id="allowed_ip_list" name="allowed_ip_list" value=""/>

                                    <p class="help-block">SuperRewards ip list. Do not edit if you don't know what are
                                        you doing.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_type">Reward Type</label>

                                <div class="controls">
                                    <select id="reward_type" name="reward_type">
                                        <option value="1">Credits 1</option>
                                        <option value="2">Credits 2</option>
                                    </select>

                                    <p class="help-block">Which donation points user will receive after superrewards
                                        donation.</p>

                                    <p>For reward types check your credits settings <a
                                                href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                                target="_blank">here</a></p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="pingback">PostBack Url</label>

                                <div class="controls">
                                    <span id="pingback"><?php echo $this->config->base_url; ?>
                                        payment/superrewards</span>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_superrewards_settings"
                                        id="edit_superrewards_settings">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="interkassasettings">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Interkassa Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="interkassa_settings_form">
                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="active">Status </label>

                                <div class="controls">
                                    <select id="active" name="active">
                                        <option value="0">Inactive</option>
                                        <option value="1">Active</option>
                                    </select>

                                    <p class="help-block">Use interkassa donation method.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="shop_id">Shop Id </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="shop_id" name="shop_id" value=""/>

                                    <p class="help-block">Interkassa shop id.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="secret_key">Sign Key </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="secret_key" name="secret_key"
                                           value=""/>

                                    <p class="help-block">Interkassa sign key or test key.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_type">Reward Type</label>

                                <div class="controls">
                                    <select id="reward_type" name="reward_type">
                                        <option value="1">Credits 1</option>
                                        <option value="2">Credits 2</option>
                                    </select>

                                    <p class="help-block">Which donation points user will receive after Interkassa
                                        donation.</p>

                                    <p>For reward types check your credits settings <a
                                                href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                                target="_blank">here</a></p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_interkassa_settings"
                                        id="edit_interkassa_settings">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="interkassapackages">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Interkassa Packages</h2>
                    </div>
                    <div class="box-content">
                        <div id="donate_package_list">
                            <table class="table table-striped table-bordered bootstrap-datatable datatable"
                                   id="donate_sortable_interkassa">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Currency</th>
                                    <th>Reward Points</th>
                                    <th>Server</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="donate_sortable_interkassa_content" style="cursor: move;">
                                <?php foreach($packages_interkassa as $pack): ?>
                                    <tr id="interkassa_<?php echo $pack['id']; ?>">
                                        <td><input class="input-medium" type="text"
                                                   id="pack_title_interkassa_<?php echo $pack['id']; ?>"
                                                   value="<?php echo $pack['package']; ?>"/></td>
                                        <td class="center"><input class="input-small" type="text"
                                                                  id="pack_price_interkassa_<?php echo $pack['id']; ?>"
                                                                  value="<?php echo $pack['price']; ?>"/></td>
                                        <td class="center"><input class="input-small" type="text"
                                                                  id="pack_currency_interkassa_<?php echo $pack['id']; ?>"
                                                                  value="<?php echo $pack['currency']; ?>"/></td>
                                        <td class="center"><input class="input-small" type="text"
                                                                  id="pack_reward_interkassa_<?php echo $pack['id']; ?>"
                                                                  value="<?php echo $pack['reward']; ?>"/></td>
                                        <td class="center">
                                            <select id="pack_server_interkassa_<?php echo $pack['id']; ?>"
                                                    class="input-medium">
                                                <?php
                                                    foreach($server_list as $key => $server):
                                                        ?>
                                                        <option value="<?php echo $key; ?>"
                                                                <?php if($key == $pack['server']){ ?>selected="selected"<?php } ?>><?php echo $server_list[$key]['title']; ?></option>
                                                    <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="center" id="status_icon_interkassa_<?php echo $pack['id']; ?>">
                                            <?php if($pack['status'] == 1): ?>
                                                <span class="label label-success">Active</span>
                                            <?php else: ?>
                                                <span class="label label-important">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="center">
                                            <?php if($pack['status'] == 1): ?>
                                                <a class="btn btn-danger" href="#"
                                                   id="status_button_interkassa_<?php echo $pack['id']; ?>"
                                                   onclick="App.changeInterkassaStatus(<?php echo $pack['id']; ?>, 0);">
                                                    <i class="icon-edit icon-white"></i> Disable </a>
                                            <?php else: ?>
                                                <a class="btn btn-success" href="#"
                                                   id="status_button_interkassa_<?php echo $pack['id']; ?>"
                                                   onclick="App.changeInterkassaStatus(<?php echo $pack['id']; ?>, 1);">
                                                    <i class="icon-edit icon-white"></i> Enable </a>
                                            <?php endif; ?>

                                            <a class="btn btn-info" href="#"
                                               onclick="App.editInterkassa(<?php echo $pack['id']; ?>);">
                                                <i class="icon-edit icon-white"></i> Edit </a>
                                            <a class="btn btn-danger" href="#"
                                               onclick="App.deleteInterkassa(<?php echo $pack['id']; ?>);">
                                                <i class="icon-trash icon-white"></i> Delete </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <table class="table table-striped table-bordered bootstrap-datatable datatable">
                            <tbody>
                            <tr>
                                <td><input class="input-medium" type="text" id="title_new_interkassa"
                                           placeholder="Title" value=""/></td>
                                <td><input class="input-small" type="text" id="price_new_interkassa" placeholder="Price"
                                           value=""/></td>
                                <td><input class="input-small" type="text" id="currency_new_interkassa"
                                           placeholder="Currency" value=""/></td>
                                <td><input class="input-small" type="text" id="reward_new_interkassa"
                                           placeholder="Reward" value=""/></td>
                                <td class="center">
                                    <select id="server_new_interkassa" class="input-medium">
                                        <?php
                                            foreach($server_list as $key => $server):
                                                ?>
                                                <option
                                                        value="<?php echo $key; ?>"><?php echo $server_list[$key]['title']; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <a class="btn btn-success" href="#" onclick="App.addInterkassaPackage();">
                                        <i class="icon-edit icon-white"></i> Add Package </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="cuenta_digitalsettings">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> CuentaDigital Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="cuenta_digital_settings_form">
                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>"><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="active">Status </label>

                                <div class="controls">
                                    <select id="active" name="active">
                                        <option value="0">Inactive</option>
                                        <option value="1">Active</option>
                                    </select>

                                    <p class="help-block">Use CuentaDigital donation method.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="api_type">Api Type </label>

                                <div class="controls">
                                    <select id="api_type" name="api_type">
                                        <option value="1">Api Direct</option>
                                        <option value="2">Api Voucher Digital</option>
                                    </select>

                                    <p class="help-block">Cuenta Digital api type, Can be checked <a
                                                href="https://www.cuentadigital.com/area.php?name=Herramientas"
                                                target="_blank">here</a>.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="account_id">Account Id </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="account_id" name="account_id"
                                           value=""/>

                                    <p class="help-block">CuentaDigital merchant account id.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="voucher_api_password">ApiVoucher Password </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="voucher_api_password"
                                           name="voucher_api_password" value=""/>

                                    <p class="help-block">Your ApiVoucher password. Can create <a
                                                href="https://www.cuentadigital.com/area.php?name=ApiVoucher"
                                                target="_blank">here</a>. </p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_type">Reward Type</label>

                                <div class="controls">
                                    <select id="reward_type" name="reward_type">
                                        <option value="1">Credits 1</option>
                                        <option value="2">Credits 2</option>
                                    </select>

                                    <p class="help-block">Which donation points user will receive after CuentaDigital
                                        donation.</p>

                                    <p>For reward types check your credits settings <a
                                                href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                                target="_blank">here</a></p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_cuenta_digital_settings"
                                        id="edit_cuenta_digital_settings">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="cuenta_digitalpackages">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Cuenta Digital Packages</h2>
                    </div>
                    <div class="box-content">
                        <div id="donate_package_list">
                            <table class="table table-striped table-bordered bootstrap-datatable datatable"
                                   id="donate_sortable_cuenta_digital">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Currency</th>
                                    <th>Reward Points</th>
                                    <th>Server</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="donate_sortable_cuenta_digital_content" style="cursor: move;">
                                <?php foreach($packages_cuenta_digital as $pack): ?>
                                    <tr id="cuenta_digital_<?php echo $pack['id']; ?>">
                                        <td><input class="input-medium" type="text"
                                                   id="pack_title_cuenta_digital_<?php echo $pack['id']; ?>"
                                                   value="<?php echo $pack['package']; ?>"/></td>
                                        <td class="center"><input class="input-small" type="text"
                                                                  id="pack_price_cuenta_digital_<?php echo $pack['id']; ?>"
                                                                  value="<?php echo $pack['price']; ?>"/></td>
                                        <td class="center"><input class="input-small" type="text"
                                                                  id="pack_currency_cuenta_digital_<?php echo $pack['id']; ?>"
                                                                  value="<?php echo $pack['currency']; ?>"/></td>
                                        <td class="center"><input class="input-small" type="text"
                                                                  id="pack_reward_cuenta_digital_<?php echo $pack['id']; ?>"
                                                                  value="<?php echo $pack['reward']; ?>"/></td>
                                        <td class="center">
                                            <select id="pack_server_cuenta_digital_<?php echo $pack['id']; ?>"
                                                    class="input-medium">
                                                <?php
                                                    foreach($server_list as $key => $server):
                                                        ?>
                                                        <option value="<?php echo $key; ?>"
                                                                <?php if($key == $pack['server']){ ?>selected="selected"<?php } ?>><?php echo $server_list[$key]['title']; ?></option>
                                                    <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td class="center" id="status_icon_cuenta_digital_<?php echo $pack['id']; ?>">
                                            <?php if($pack['status'] == 1): ?>
                                                <span class="label label-success">Active</span>
                                            <?php else: ?>
                                                <span class="label label-important">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="center">
                                            <?php if($pack['status'] == 1): ?>
                                                <a class="btn btn-danger" href="#"
                                                   id="status_button_cuenta_digital_<?php echo $pack['id']; ?>"
                                                   onclick="App.changeCuentaDigitalStatus(<?php echo $pack['id']; ?>, 0);">
                                                    <i class="icon-edit icon-white"></i> Disable </a>
                                            <?php else: ?>
                                                <a class="btn btn-success" href="#"
                                                   id="status_button_cuenta_digital_<?php echo $pack['id']; ?>"
                                                   onclick="App.changeCuentaDigitalStatus(<?php echo $pack['id']; ?>, 1);">
                                                    <i class="icon-edit icon-white"></i> Enable </a>
                                            <?php endif; ?>

                                            <a class="btn btn-info" href="#"
                                               onclick="App.editCuentaDigital(<?php echo $pack['id']; ?>);">
                                                <i class="icon-edit icon-white"></i> Edit </a>
                                            <a class="btn btn-danger" href="#"
                                               onclick="App.deleteCuentaDigital(<?php echo $pack['id']; ?>);">
                                                <i class="icon-trash icon-white"></i> Delete </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <table class="table table-striped table-bordered bootstrap-datatable datatable">
                            <tbody>
                            <tr>
                                <td><input class="input-medium" type="text" id="title_new_cuenta_digital"
                                           placeholder="Title" value=""/></td>
                                <td><input class="input-small" type="text" id="price_new_cuenta_digital"
                                           placeholder="Price" value=""/></td>
                                <td><input class="input-small" type="text" id="currency_new_cuenta_digital"
                                           placeholder="Currency" value=""/></td>
                                <td><input class="input-small" type="text" id="reward_new_cuenta_digital"
                                           placeholder="Reward" value=""/></td>
                                <td class="center">
                                    <select id="server_new_cuenta_digital" class="input-medium">
                                        <?php
                                            foreach($server_list as $key => $server):
                                                ?>
                                                <option
                                                        value="<?php echo $key; ?>"><?php echo $server_list[$key]['title']; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <a class="btn btn-success" href="#" onclick="App.addCuentaDigitalPackage();">
                                        <i class="icon-edit icon-white"></i> Add Package </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>