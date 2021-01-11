<div class="span2 main-menu-span">
    <div class="well nav-collapse sidebar-nav">
        <ul class="nav nav-tabs nav-stacked main-menu">
            <li class="nav-header hidden-tablet">Main Menu</li>
            <li>
                <a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp">
                    <i class="icon-home"></i><span class="hidden-tablet"> Dashboard</span></a>
            </li>
            <li>
                <a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/news-composer">
                    <i class="icon-edit"></i><span class="hidden-tablet"> News Composer</span></a>
            </li>
            <li>
                <a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/manage-gallery">
                    <i class="icon-picture"></i><span class="hidden-tablet"> Manage Gallery</span></a>
            </li>
            <li>
                <a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/manage-downloads">
                    <i class="icon-file"></i><span class="hidden-tablet"> Manage Downloads</span></a>
            </li>
            <li>
                <a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/manage-plugins">
                    <i class="icon-cog"></i><span class="hidden-tablet"> Manage Plugins</span></a>
            </li>
            <li class="accordion">
                <a href="#"><i class="icon-align-justify"></i><span class="hidden-tablet"> Website Settings</span></a>
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings">Main Settings</a></li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/email">Email Settings</a>
                    </li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits">Credits
                            Settings</a></li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/tables">SQL Table
                            Settings</a></li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/security">Security
                            Settings</a></li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/scheduler">Scheduler
                            Settings</a></li>
                </ul>
            </li>
            <li class="accordion">
                <a href="#"><i class="icon-adjust"></i><span class="hidden-tablet"> Modules Settings</span></a>
                <ul class="nav nav-pills nav-stacked">
                    <li>
                        <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/account">Account Panel
                            Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/buylevel">Buy Level
                            Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/buygm">Buy GM Access
                            Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/changeclass">Change Class
                            Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/changename">Change Name
                            Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/character">Character
                            Panel Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/donate">Donate
                            Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/event-timers">Event Timer
                            Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/greset">Grand Reset
                            Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/lostpassword">Lost
                            Password Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/market">Market
                            Settings</a>
                    </li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/media">Media Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/modules">Sidebar Modules
                            Settings</a>
                    </li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/news">News Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/rankings">Rankings
                            Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/registration">Registration
                            Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/referral">Referral System
                            Settings</a>
                    </li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/reset">Reset Settings</a>
                    </li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/shop">Shop Settings</a>
                    </li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/vip">Vip Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/warehouse">Warehouse
                            Settings</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/wcoin-exchange">Wcoin
                            Exchange Settings</a>
                    </li>
                </ul>
            </li>
            <li class="accordion">
                <a href="#"><i class="icon-folder-open"></i><span class="hidden-tablet"> Logs</span></a>
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/logs-shop">Shop Logs</a></li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/logs-market">Market Logs</a></li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/logs-account">Account Logs</a></li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/logs-cuenta-digital-transactions">CuentaDigital
                            Transactions</a></li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/logs-fortumo-transactions">Fortumo
                            Transactions</a></li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/logs-interkassa-transactions">Interkassa
                            Transactions</a></li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/logs-paygol-transactions">Paygol
                            Transactions</a></li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/logs-paycall-transactions">PayCall
                            Transactions</a></li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/logs-paypal-transactions">Paypal
                            Transactions</a></li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/logs-paymentwall-transactions">PaymentWall
                            Transactions</a>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/logs-superrewards-transactions">Superrewards
                            Transactions</a></li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/logs-twocheckout-transactions">2CheckOut
                            Transactions</a></li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/logs-pagseguro-transactions">PagSeguro
                            Transactions</a></li>
                    <li><a href="<?php echo $this->config->base_url; ?>admincp/gm-logs">GM Logs</a></li>
                </ul>
            </li>
            <li class="nav-header hidden-tablet">Server Manager</li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/account-manager"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Account Manager</span></a></li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/character-manager"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Character Manager</span></a></li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/search-ip"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Search IP</span></a></li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/server-list-manager"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Server List Manager</span></a></li>
            <li class="nav-header hidden-tablet">Bulk Mailer</li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/bulk-mail"><i
                            class="icon-star"></i><span class="hidden-tablet"> Bulk Mail</span></a></li>
            <li class="nav-header hidden-tablet">Shop Editor</li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/add-item"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Add Item</span></a></li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/item-list"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Edit Items</span></a></li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/import-items"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Import Items</span></a></li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/edit-category-list"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Edit Category List</span></a></li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/edit-ancient-sets"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Edit Ancient Sets</span></a></li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/edit-harmony-options"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Edit Harmony Options</span></a></li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/edit-socket-options"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Edit Socket Options</span></a></li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/warehouse-editor"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Warehouse Editor</span></a></li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/credits-editor"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Credits Editor</span></a></li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/find-item"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Find Item By Serial</span></a></li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/custom-price-list"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> View Custom Item Price List</span></a>
            </li>
            <li class="nav-header hidden-tablet">Support</li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/support-departments"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Departments</span></a></li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/support-requests"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Requests</span></a></li>
            <li class="nav-header hidden-tablet">VoteReward</li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/vote-links"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Edit Voting Links</span></a></li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/top-voters"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Check Top Voters</span></a></li>
            <li class="nav-header hidden-tablet">GM Panel</li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/gm-manager"><i
                            class="icon-star"></i><span class="hidden-tablet"> GM Manager</span></a></li>
            <li class="nav-header hidden-tablet">Language Manager</li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/list-languages"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> List Languages</span></a></li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/add-language"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Add Languages</span></a></li>
            <li class="nav-header hidden-tablet">Guides Manager</li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/list-guides"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> List Guides</span></a></li>
            <li><a class="ajax-link" href="<?php echo $this->config->base_url; ?>admincp/add-guide"><i
                            class="icon-pencil"></i><span class="hidden-tablet"> Add Guide</span></a></li>
        </ul>
    </div>
</div>
<noscript>
    <div class="alert alert-block span10">
        <h4 class="alert-heading">Warning!</h4>

        <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to
            use this site.</p>
    </div>
</noscript>