<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/account-manager">Account Manager</a></li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2>Member Filter</h2>
                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                </div>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="post" action="" id="member_filter">
                    <div class="control-group">
                        <label class="control-label">Joined</label>
                        <div class="controls">
                            <input type="text" class="input-small datepicker_account_filter" id="joined1" name="joined1"
                                   value="<?php if(isset($_COOKIE['filter_joined'])){
                                       echo $_COOKIE['filter_joined'];
                                   } ?>">
                            Between <input type="text" class="input-small datepicker_account_filter" id="joined2"
                                           name="joined2" value="<?php if(isset($_COOKIE['filter_joined_end'])){
                                echo $_COOKIE['filter_joined_end'];
                            } ?>">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="status">Status</label>
                        <div class="controls">
                            <?php
                                $status = [];
                                if(isset($_COOKIE['filter_status']) && $_COOKIE['filter_status'] != ''){
                                    $status = unserialize($_COOKIE['filter_status']);
                                }
                            ?>
                            <select id="status" name="status[]" multiple data-rel="chosen">
                                <option value="activated" <?php if(in_array('activated', $status)){
                                    echo 'selected="selected"';
                                } ?>>Activated
                                </option>
                                <option value="not_activated" <?php if(in_array('not_activated', $status)){
                                    echo 'selected="selected"';
                                } ?>>Not Activated
                                </option>
                                <option value="blocked" <?php if(in_array('blocked', $status)){
                                    echo 'selected="selected"';
                                } ?>>Blocked
                                </option>
                                <option value="vip" <?php if(in_array('vip', $status)){
                                    echo 'selected="selected"';
                                } ?>>Vip
                                </option>
                                <option value="gm" <?php if(in_array('gm', $status)){
                                    echo 'selected="selected"';
                                } ?>>Game Master
                                </option>
                            </select>
                        </div>
                    </div>
                    <?php $country_list = $this->website->codeToCountryName('', true); ?>
                    <div class="control-group">
                        <label class="control-label" for="country">Country</label>
                        <div class="controls">
                            <?php
                                $countr = [];
                                if(isset($_COOKIE['filter_country']) && $_COOKIE['filter_country'] != ''){
                                    $countr = unserialize($_COOKIE['filter_country']);
                                }
                            ?>
                            <select id="country" name="country[]" multiple data-rel="chosen">
                                <?php foreach($country_list AS $short => $country): ?>
                                    <option value="<?php echo strtolower($short); ?>" <?php if(in_array(strtolower($short), $countr)){
                                        echo 'selected="selected"';
                                    } ?>><?php echo $country; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="server">Server</label>
                        <div class="controls">
                            <select id="server" name="server">
                                <option value="">Select</option>
                                <?php foreach($servers AS $key => $value): ?>
                                    <option value="<?php echo $key; ?>" <?php if(isset($_COOKIE['filter_server']) && $_COOKIE['filter_server'] == $key){
                                        echo 'selected="selected"';
                                    } ?>><?php echo $value['title']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" value="1" id="apply_account_filter">Apply Filter
                        </button>
                        <button type="submit" class="btn btn-warning" value="1" id="reset_account_filter">Reset Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header well" data-original-title>
                <h2><i class="icon-user"></i> Members</h2>
                <div class="box-icon">
                    <a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
                    <a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
                    <a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <table class="table table-striped table-bordered bootstrap-datatable accounts_datatable">
                    <thead>
                    <tr>
                        <th>Username</th>
                        <th>Date registered</th>
                        <th>Country</th>
                        <th class="no-sort">Server</th>
                        <th class="no-sort">Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>