<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/wcoin-exchange">WCoin Exchange
                    Settings</a></li>
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
    <script>
        $(document).ready(function () {
            App.loadWcoinSettings('<?php echo $default_server;?>');
        });
    </script>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> WCoin Exchange Settings</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="" id="wcoin_settings_form">
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
                            <p class="help-block">Use wcoin exchange module.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="reward_coin">Cost Credits </label>
                        <div class="controls">
                            <input type="text" class="span6 typeahead" id="reward_coin" name="reward_coin" value=""
                                   placeholder="1"/>
                            <p class="help-block">How much credits will cost 1 wcoin. Value can be set as negative
                                also.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="credits_type">Wcoin exchange type</label>
                        <div class="controls">
                            <select id="credits_type" name="credits_type">
                                <option value="1">Credits 1</option>
                                <option value="2">Credits 2</option>
                            </select>
                            <p class="help-block">Wcoin exchange type.</p>
                            <p>For credits types check your credits settings <a
                                        href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                        target="_blank">here</a></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="change_back">Allow Change Back</label>
                        <div class="controls">
                            <select id="change_back" name="change_back">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                                >
                            </select>
                            <p class="help-block">Allow to change wcoins back to credits.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="min_rate">Min Exchange Rate </label>

                        <div class="controls">
                            <input type="text" class="span6 typeahead" id="min_rate" name="min_rate" value=""
                                   placeholder="1"/>
                            <p class="help-block">
                                Minimal credits value for exchange.</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="display_wcoins">Display wcoins in sidebar</label>
                        <div class="controls">
                            <select id="display_wcoins" name="display_wcoins">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" name="edit_wcoin_settings"
                                id="edit_wcoin_settings">Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>