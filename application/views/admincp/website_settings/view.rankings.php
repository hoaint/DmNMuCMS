<div id="content" class="span10">
    <div>
        <ul class="breadcrumb">
            <li><a href="<?php echo $this->config->base_url; ?>admincp">Home</a> <span class="divider">/</span></li>
            <li><a href="<?php echo $this->config->base_url; ?>admincp/manage-settings/rankings">Rankings Settings</a>
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
        <div class="span12">
            <ul class="nav nav-pills">
                <li role="presentation" class="active"><a href="#player" aria-controls="player" role="tab"
                                                          data-toggle="tab"
                                                          onclick="App.loadRankingsSettings('<?php echo $default_server; ?>', 'player');">Players</a>
                </li>
                <li role="presentation"><a href="#guild" aria-controls="guild" role="tab" data-toggle="tab"
                                           onclick="App.loadRankingsSettings('<?php echo $default_server; ?>', 'guild');">Guilds</a>
                </li>
                <li role="presentation"><a href="#gens" aria-controls="gens" role="tab" data-toggle="tab"
                                           onclick="App.loadRankingsSettings('<?php echo $default_server; ?>', 'gens');">Gens</a>
                </li>
                <li role="presentation"><a href="#voter" aria-controls="voter" role="tab" data-toggle="tab"
                                           onclick="App.loadRankingsSettings('<?php echo $default_server; ?>', 'voter');">Vote</a>
                </li>
                <li role="presentation"><a href="#killer" aria-controls="killer" role="tab" data-toggle="tab"
                                           onclick="App.loadRankingsSettings('<?php echo $default_server; ?>', 'killer');">Killers</a>
                </li>
                <li role="presentation"><a href="#online" aria-controls="online" role="tab" data-toggle="tab"
                                           onclick="App.loadRankingsSettings('<?php echo $default_server; ?>', 'online');">Online</a>
                </li>
                <li role="presentation"><a href="#online_list" aria-controls="online_list" role="tab" data-toggle="tab"
                                           onclick="App.loadRankingsSettings('<?php echo $default_server; ?>', 'online_list');">Online
                        List</a>
                </li>
                <li role="presentation"><a href="#bc" aria-controls="bc" role="tab" data-toggle="tab"
                                           onclick="App.loadRankingsSettings('<?php echo $default_server; ?>', 'bc');">BC</a>
                </li>
                <li role="presentation"><a href="#ds" aria-controls="ds" role="tab" data-toggle="tab"
                                           onclick="App.loadRankingsSettings('<?php echo $default_server; ?>', 'ds');">DS</a>
                </li>
                <li role="presentation"><a href="#cc" aria-controls="cc" role="tab" data-toggle="tab"
                                           onclick="App.loadRankingsSettings('<?php echo $default_server; ?>', 'cc');">CC</a>
                </li>
                <li role="presentation"><a href="#duels" aria-controls="duels" role="tab" data-toggle="tab"
                                           onclick="App.loadRankingsSettings('<?php echo $default_server; ?>', 'duels');">Duels</a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            App.loadRankingsSettings('<?php echo $default_server;?>', 'player');
        });
    </script>
    <div class="row-fluid">
        <div class="box span12">
            <div class="box-header well">
                <h2><i class="icon-edit"></i> Rankings Status</h2>
            </div>
            <div class="box-content">
                <form class="form-horizontal" method="POST" action="" id="ranking_settings_form">
                    <div class="control-group">
                        <label class="control-label" for="server">Server </label>

                        <div class="controls">
                            <select id="server" name="server">
                                <?php foreach($server_list as $key => $server): ?>
                                    <option value="<?php echo $key; ?>" <?php if($default_server == $key){
                                        echo 'selected="selected"';
                                    } ?>><?php echo $server['title']; ?></option>
                                <?php endforeach; ?>
                            </select>

                            <p class="help-block">Current server</p>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="active">Status </label>

                        <div class="controls">
                            <select id="active" name="active">
                                <option
                                        value="0" <?php if(isset($rankings_config[$default_server]['active']) && $rankings_config[$default_server]['active'] == 0){
                                    echo 'selected="selected"';
                                } ?>>Inactive
                                </option>
                                <option
                                        value="1" <?php if(isset($rankings_config[$default_server]['active']) && $rankings_config[$default_server]['active'] == 1){
                                    echo 'selected="selected"';
                                } ?>>Active
                                </option>
                            </select>

                            <p class="help-block">Rankings status</p>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" name="edit_rankings_status"
                                id="edit_rankings_status">Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="box span12">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="player">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Player Top Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="player_settings_form">
                            <input type="hidden" id="rankings_type" name="rankings_type" value="player"/>

                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>" <?php if($default_server == $key){
                                                echo 'selected="selected"';
                                            } ?>><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="is_sidebar_module">Sidebar Module </label>

                                <div class="controls">
                                    <select id="is_sidebar_module" name="is_sidebar_module">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Show player rankings in sidebar</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count">Count </label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count" name="count" value=""/>

                                    <p class="help-block">Top player count in rankings, zero will disable ranking</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count_in_sidebar">Count In Sidebar</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count_in_sidebar" name="count_in_sidebar"
                                           value=""/>

                                    <p class="help-block">Top player count in sidebar rankings</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="cache_time">Cache Time</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="cache_time" name="cache_time" value=""/>

                                    <p class="help-block">Top cache time in seconds</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="display_resets">Display Resets </label>

                                <div class="controls">
                                    <select id="display_resets" name="display_resets">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Display character reset count</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="display_gresets">Display Grand Reset </label>

                                <div class="controls">
                                    <select id="display_gresets" name="display_gresets">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Display character grand reset count</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="display_master_level">Display Master Level </label>

                                <div class="controls">
                                    <select id="display_master_level" name="display_master_level">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Display character master level</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="display_status">Display Status </label>

                                <div class="controls">
                                    <select id="display_status" name="display_status">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Display character status</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="display_gms">Display GMs </label>

                                <div class="controls">
                                    <select id="display_gms" name="display_gms">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Display game master characters</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="excluded_list">Exclude Characters </label>

                                <div class="controls">
                                    <input type="text" class="input-large" data-role="tagsinput" id="excluded_list"
                                           name="excluded_list" value=""/>

                                    <p class="help-block">Exclude character names from ranking, seperated by comma.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="display_country">Display Country </label>

                                <div class="controls">
                                    <select id="display_country" name="display_country">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Display character country flag</p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_player_settings"
                                        id="edit_player_settings" value="Save changes">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade in" id="guild">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Guild Top Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="guild_settings_form">
                            <input type="hidden" id="rankings_type" name="rankings_type" value="guild"/>

                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>" <?php if($default_server == $key){
                                                echo 'selected="selected"';
                                            } ?>><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="is_sidebar_module">Sidebar Module </label>
                                <div class="controls">
                                    <select id="is_sidebar_module" name="is_sidebar_module">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Show guild rankings in sidebar</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count">Count </label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count" name="count" value=""/>

                                    <p class="help-block">Top player count in rankings, zero will disable ranking</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count_in_sidebar">Count In Sidebar</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count_in_sidebar" name="count_in_sidebar"
                                           value=""/>

                                    <p class="help-block">Top guild count in sidebar rankings</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="cache_time">Cache Time</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="cache_time" name="cache_time" value=""/>

                                    <p class="help-block">Top cache time in seconds</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="excluded_list">Exclude Guilds </label>
                                <div class="controls">
                                    <input type="text" class="input-large" data-role="tagsinput" id="excluded_list"
                                           name="excluded_list" value=""/>

                                    <p class="help-block">Exclude guild names from ranking, seperated by comma.</p>
                                </div>
                            </div>
							<div class="control-group">
                                <label class="control-label" for="orderBy">Order By </label>
                                <div class="controls">
                                    <select id="order_by" name="order_by">
                                        <option value="0">Guild Score</option>
                                        <option value="1">Total Master Level</option>
										<option value="2">Total Resets</option>
										<option value="3">Total GrandResets</option>
                                    </select>
                                    <p class="help-block">By what value will be order guild rankings</p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_guild_settings"
                                        id="edit_guild_settings" value="Save changes">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade in" id="gens">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Gens Top Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="gens_settings_form">
                            <input type="hidden" id="rankings_type" name="rankings_type" value="gens"/>

                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>" <?php if($default_server == $key){
                                                echo 'selected="selected"';
                                            } ?>><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count">Count </label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count" name="count" value=""/>

                                    <p class="help-block">Top player count in rankings, zero will disable ranking</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="cache_time">Cache Time</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="cache_time" name="cache_time" value=""/>

                                    <p class="help-block">Top cache time in seconds</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="type">Gens Ranking Type</label>

                                <div class="controls">
                                    <select id="type" name="type">
                                        <option value="igcn">IGCN</option>
                                        <option value="muengine">MuEngine</option>
                                        <option value="scf">TitansTech</option>
                                        <option value="zteam">ZTeam</option>
                                        <option value="exteam">ExTeam</option>
                                        <option value="xteam">X-Team</option>
                                    </select>

                                    <p class="help-block">System of gens used in your server.</p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_gens_settings"
                                        id="edit_gens_settings" value="Save changes">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade in" id="voter">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Voter Top Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="voter_settings_form">
                            <input type="hidden" id="rankings_type" name="rankings_type" value="voter"/>

                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>" <?php if($default_server == $key){
                                                echo 'selected="selected"';
                                            } ?>><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="is_sidebar_module">Sidebar Module </label>

                                <div class="controls">
                                    <select id="is_sidebar_module" name="is_sidebar_module">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Show voter rankings in sidebar</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count">Count </label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count" name="count" value=""/>

                                    <p class="help-block">Top voter count in rankings, zero will disable ranking</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count_in_sidebar">Count In Sidebar</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count_in_sidebar" name="count_in_sidebar"
                                           value=""/>

                                    <p class="help-block">Top voter count in sidebar rankings</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="cache_time">Cache Time</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="cache_time" name="cache_time" value=""/>

                                    <p class="help-block">Top cache time in seconds</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="excluded_list">Exclude Characters </label>

                                <div class="controls">
                                    <input type="text" class="input-large" data-role="tagsinput" id="excluded_list"
                                           name="excluded_list" value=""/>

                                    <p class="help-block">Exclude character names from ranking, seperated by comma.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="display_country">Display Country </label>

                                <div class="controls">
                                    <select id="display_country" name="display_country">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Display character country flag</p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_voter_settings"
                                        id="edit_voter_settings" value="Save changes">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade in" id="killer">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Killer Top Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="killer_settings_form">
                            <input type="hidden" id="rankings_type" name="rankings_type" value="killer"/>

                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>" <?php if($default_server == $key){
                                                echo 'selected="selected"';
                                            } ?>><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="is_sidebar_module">Sidebar Module </label>

                                <div class="controls">
                                    <select id="is_sidebar_module" name="is_sidebar_module">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Show killer rankings in sidebar</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count">Count </label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count" name="count" value=""/>

                                    <p class="help-block">Top killer count in rankings, zero will disable ranking</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count_in_sidebar">Count In Sidebar</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count_in_sidebar" name="count_in_sidebar"
                                           value=""/>

                                    <p class="help-block">Top killer count in sidebar rankings</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="cache_time">Cache Time</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="cache_time" name="cache_time" value=""/>

                                    <p class="help-block">Top cache time in seconds</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="display_resets">Display Resets </label>

                                <div class="controls">
                                    <select id="display_resets" name="display_resets">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Display character reset count</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="display_gresets">Display Grand Reset </label>

                                <div class="controls">
                                    <select id="display_gresets" name="display_gresets">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Display character grand reset count</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="display_master_level">Display Master Level </label>

                                <div class="controls">
                                    <select id="display_master_level" name="display_master_level">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Display character master level</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="display_gms">Display GMs </label>

                                <div class="controls">
                                    <select id="display_gms" name="display_gms">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Display game master characters</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="excluded_list">Exclude Characters </label>

                                <div class="controls">
                                    <input type="text" class="input-large" data-role="tagsinput" id="excluded_list"
                                           name="excluded_list" value=""/>

                                    <p class="help-block">Exclude character names from ranking, seperated by comma.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="display_country">Display Country </label>

                                <div class="controls">
                                    <select id="display_country" name="display_country">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Display character country flag</p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_killer_settings"
                                        id="edit_killer_settings" value="Save changes">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade in" id="online">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Online Top Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="online_settings_form">
                            <input type="hidden" id="rankings_type" name="rankings_type" value="online"/>

                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>" <?php if($default_server == $key){
                                                echo 'selected="selected"';
                                            } ?>><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="is_sidebar_module">Sidebar Module </label>

                                <div class="controls">
                                    <select id="is_sidebar_module" name="is_sidebar_module">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Show online rankings in sidebar</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count">Count </label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count" name="count" value=""/>

                                    <p class="help-block">Top online count in rankings, zero will disable ranking</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count_in_sidebar">Count In Sidebar</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count_in_sidebar" name="count_in_sidebar"
                                           value=""/>

                                    <p class="help-block">Toponline count in sidebar rankings</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="cache_time">Cache Time</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="cache_time" name="cache_time" value=""/>

                                    <p class="help-block">Top cache time in seconds</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="excluded_list">Exclude Characters </label>

                                <div class="controls">
                                    <input type="text" class="input-large" data-role="tagsinput" id="excluded_list"
                                           name="excluded_list" value=""/>

                                    <p class="help-block">Exclude character names from ranking, seperated by comma.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="display_country">Display Country </label>

                                <div class="controls">
                                    <select id="display_country" name="display_country">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Display character country flag</p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_online_settings"
                                        id="edit_online_settings" value="Save changes">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade in" id="online_list">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Online Player List Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="online_list_settings_form">
                            <input type="hidden" id="rankings_type" name="rankings_type" value="online_list"/>

                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>" <?php if($default_server == $key){
                                                echo 'selected="selected"';
                                            } ?>><?php echo $server['title']; ?></option>
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

                                    <p class="help-block">Online list status</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="cache_time">Cache Time</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="cache_time" name="cache_time" value=""/>

                                    <p class="help-block">Online player cache time in seconds</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="display_resets">Display Resets </label>

                                <div class="controls">
                                    <select id="display_resets" name="display_resets">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Display character reset count</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="display_gresets">Display Grand Reset </label>

                                <div class="controls">
                                    <select id="display_gresets" name="display_gresets">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Display character grand reset count</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="display_gms">Display GMs </label>

                                <div class="controls">
                                    <select id="display_gms" name="display_gms">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Display game master characters</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="excluded_list">Exclude Characters </label>

                                <div class="controls">
                                    <input type="text" class="input-large" data-role="tagsinput" id="excluded_list"
                                           name="excluded_list" value=""/>

                                    <p class="help-block">Exclude character names from ranking, seperated by comma.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="display_country">Display Country </label>

                                <div class="controls">
                                    <select id="display_country" name="display_country">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Display character country flag</p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_online_list_settings"
                                        id="edit_online_list_settings" value="Save changes">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade in" id="bc">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> BC Top Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="bc_settings_form">
                            <input type="hidden" id="rankings_type" name="rankings_type" value="bc"/>

                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>" <?php if($default_server == $key){
                                                echo 'selected="selected"';
                                            } ?>><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="is_sidebar_module">Sidebar Module </label>

                                <div class="controls">
                                    <select id="is_sidebar_module" name="is_sidebar_module">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Show bc rankings in sidebar</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count">Count </label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count" name="count" value=""/>

                                    <p class="help-block">Top bc rankings count, zero will disable ranking</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count_in_sidebar">Count In Sidebar</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count_in_sidebar" name="count_in_sidebar"
                                           value=""/>

                                    <p class="help-block">Top bc rankings count in sidebar</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="cache_time">Cache Time</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="cache_time" name="cache_time" value=""/>

                                    <p class="help-block">Top cache time in seconds</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="excluded_list">Exclude Characters </label>

                                <div class="controls">
                                    <input type="text" class="input-large" data-role="tagsinput" id="excluded_list"
                                           name="excluded_list" value=""/>

                                    <p class="help-block">Exclude character names from ranking, seperated by comma.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="is_monthly_reward">Is Monthly Reward </label>

                                <div class="controls">
                                    <select id="is_monthly_reward" name="is_monthly_reward">
                                        <option
                                                value="0">No
                                        </option>
                                        <option
                                                value="1">Yes
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="amount_of_players_to_reward">Player Amount</label>

                                <div class="controls">
                                    <select id="amount_of_players_to_reward" name="amount_of_players_to_reward">
                                        <?php for($a = 1; $a <= 100; $a++): ?>
                                            <option value="<?php echo $a; ?>"><?php echo $a; ?></option>
                                        <?php endfor; ?>
                                    </select>

                                    <p class="help-block">How many players will receive reward.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_formula">Reward Formula </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="reward_formula"
                                           name="reward_formula"
                                           value=""/>

                                    <p class="help-block">
                                        Here you can provide formula how players will be rewarded. Ex: <span
                                                style="color:green;">5000 - ({position} * 450)</span> in result 1st
                                        place would receive 4650 credits but 10th place 500 Available variables
                                    <ul>
                                        <li>{position} - player position</li>
                                        <li>{score} - player blood castle score in current month</li>
                                    </ul>
                                    </p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_type">Reward Type</label>

                                <div class="controls">
                                    <select id="reward_type" name="reward_type">
                                        <option
                                                value="1">Credits 1
                                        </option>
                                        <option
                                                value="2">Credits 2
                                        </option>
                                    </select>

                                    <p class="help-block">Monthly reward type.</p>

                                    <p>For credits types check your credits settings <a
                                                href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                                target="_blank">here</a></p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_bc_settings"
                                        id="edit_bc_settings" value="Save changes">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade in" id="ds">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> DS Top Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="ds_settings_form">
                            <input type="hidden" id="rankings_type" name="rankings_type" value="ds"/>

                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>" <?php if($default_server == $key){
                                                echo 'selected="selected"';
                                            } ?>><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="is_sidebar_module">Sidebar Module </label>

                                <div class="controls">
                                    <select id="is_sidebar_module" name="is_sidebar_module">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Show ds rankings in sidebar</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count">Count </label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count" name="count" value=""/>

                                    <p class="help-block">Top ds rankings count, zero will disable ranking</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count_in_sidebar">Count In Sidebar</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count_in_sidebar" name="count_in_sidebar"
                                           value=""/>

                                    <p class="help-block">Top ds rankings count in sidebar</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="cache_time">Cache Time</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="cache_time" name="cache_time" value=""/>

                                    <p class="help-block">Top cache time in seconds</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="excluded_list">Exclude Characters </label>

                                <div class="controls">
                                    <input type="text" class="input-large" data-role="tagsinput" id="excluded_list"
                                           name="excluded_list" value=""/>

                                    <p class="help-block">Exclude character names from ranking, seperated by comma.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="is_monthly_reward">Is Monthly Reward </label>

                                <div class="controls">
                                    <select id="is_monthly_reward" name="is_monthly_reward">
                                        <option
                                                value="0">No
                                        </option>
                                        <option
                                                value="1">Yes
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="amount_of_players_to_reward">Player Amount</label>

                                <div class="controls">
                                    <select id="amount_of_players_to_reward" name="amount_of_players_to_reward">
                                        <?php for($a = 1; $a <= 100; $a++): ?>
                                            <option value="<?php echo $a; ?>"><?php echo $a; ?></option>
                                        <?php endfor; ?>
                                    </select>

                                    <p class="help-block">How many players will receive reward.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_formula">Reward Formula </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="reward_formula"
                                           name="reward_formula"
                                           value=""/>

                                    <p class="help-block">
                                        Here you can provide formula how players will be rewarded. Ex: <span
                                                style="color:green;">5000 - ({position} * 450)</span> in result 1st
                                        place would receive 4650 credits but 10th place 500 Available variables
                                    <ul>
                                        <li>{position} - player position</li>
                                        <li>{score} - player devil square score in current month</li>
                                    </ul>
                                    </p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_type">Reward Type</label>

                                <div class="controls">
                                    <select id="reward_type" name="reward_type">
                                        <option
                                                value="1">Credits 1
                                        </option>
                                        <option
                                                value="2">Credits 2
                                        </option>
                                    </select>

                                    <p class="help-block">Monthly reward type.</p>

                                    <p>For credits types check your credits settings <a
                                                href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                                target="_blank">here</a></p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_ds_settings"
                                        id="edit_ds_settings" value="Save changes">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade in" id="cc">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> CC Top Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="cc_settings_form">
                            <input type="hidden" id="rankings_type" name="rankings_type" value="cc"/>

                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>" <?php if($default_server == $key){
                                                echo 'selected="selected"';
                                            } ?>><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="is_sidebar_module">Sidebar Module </label>

                                <div class="controls">
                                    <select id="is_sidebar_module" name="is_sidebar_module">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Show cc rankings in sidebar</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count">Count </label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count" name="count" value=""/>

                                    <p class="help-block">Top cc rankings count, zero will disable ranking</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count_in_sidebar">Count In Sidebar</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count_in_sidebar" name="count_in_sidebar"
                                           value=""/>

                                    <p class="help-block">Top cc rankings count in sidebar</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="cache_time">Cache Time</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="cache_time" name="cache_time" value=""/>

                                    <p class="help-block">Top cache time in seconds</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="excluded_list">Exclude Characters </label>

                                <div class="controls">
                                    <input type="text" class="input-large" data-role="tagsinput" id="excluded_list"
                                           name="excluded_list" value=""/>

                                    <p class="help-block">Exclude character names from ranking, seperated by comma.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="is_monthly_reward">Is Monthly Reward </label>

                                <div class="controls">
                                    <select id="is_monthly_reward" name="is_monthly_reward">
                                        <option
                                                value="0">No
                                        </option>
                                        <option
                                                value="1">Yes
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="amount_of_players_to_reward">Player Amount</label>

                                <div class="controls">
                                    <select id="amount_of_players_to_reward" name="amount_of_players_to_reward">
                                        <?php for($a = 1; $a <= 100; $a++): ?>
                                            <option value="<?php echo $a; ?>"><?php echo $a; ?></option>
                                        <?php endfor; ?>
                                    </select>

                                    <p class="help-block">How many players will receive reward.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_formula">Reward Formula </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="reward_formula"
                                           name="reward_formula"
                                           value=""/>

                                    <p class="help-block">
                                        Here you can provide formula how players will be rewarded. Ex: <span
                                                style="color:green;">5000 - ({position} * 450)</span> in result 1st
                                        place would receive 4650 credits but 10th place 500 Available variables
                                    <ul>
                                        <li>{position} - player position</li>
                                        <li>{score} - player chaos castle score in current month</li>
                                    </ul>
                                    </p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_type">Reward Type</label>

                                <div class="controls">
                                    <select id="reward_type" name="reward_type">
                                        <option
                                                value="1">Credits 1
                                        </option>
                                        <option
                                                value="2">Credits 2
                                        </option>
                                    </select>

                                    <p class="help-block">Monthly reward type.</p>

                                    <p>For credits types check your credits settings <a
                                                href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                                target="_blank">here</a></p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_cc_settings"
                                        id="edit_cc_settings" value="Save changes">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane fade in" id="duels">
                    <div class="box-header well">
                        <h2><i class="icon-edit"></i> Dueler Top Settings</h2>
                    </div>
                    <div class="box-content">
                        <form class="form-horizontal" method="POST" action="" id="duels_settings_form">
                            <input type="hidden" id="rankings_type" name="rankings_type" value="duels"/>

                            <div class="control-group">
                                <label class="control-label" for="server">Settings Server </label>

                                <div class="controls">
                                    <select id="server" name="server">
                                        <?php foreach($server_list as $key => $server): ?>
                                            <option value="<?php echo $key; ?>" <?php if($default_server == $key){
                                                echo 'selected="selected"';
                                            } ?>><?php echo $server['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <p class="help-block">Current settings server</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="is_sidebar_module">Sidebar Module </label>

                                <div class="controls">
                                    <select id="is_sidebar_module" name="is_sidebar_module">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>

                                    <p class="help-block">Show duels rankings in sidebar</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count">Count </label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count" name="count" value=""/>

                                    <p class="help-block">Top duels rankings count, zero will disable ranking</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="count_in_sidebar">Count In Sidebar</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="count_in_sidebar" name="count_in_sidebar"
                                           value=""/>

                                    <p class="help-block">Top duels rankings count in sidebar</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="cache_time">Cache Time</label>

                                <div class="controls">
                                    <input type="text" class="input-large" id="cache_time" name="cache_time" value=""/>

                                    <p class="help-block">Top cache time in seconds</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="excluded_list">Exclude Characters </label>

                                <div class="controls">
                                    <input type="text" class="input-large" data-role="tagsinput" id="excluded_list"
                                           name="excluded_list" value=""/>

                                    <p class="help-block">Exclude character names from ranking, seperated by comma.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="is_monthly_reward">Is Monthly Reward </label>

                                <div class="controls">
                                    <select id="is_monthly_reward" name="is_monthly_reward">
                                        <option
                                                value="0">No
                                        </option>
                                        <option
                                                value="1">Yes
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="amount_of_players_to_reward">Player Amount</label>

                                <div class="controls">
                                    <select id="amount_of_players_to_reward" name="amount_of_players_to_reward">
                                        <?php for($a = 1; $a <= 100; $a++): ?>
                                            <option value="<?php echo $a; ?>"><?php echo $a; ?></option>
                                        <?php endfor; ?>
                                    </select>

                                    <p class="help-block">How many players will receive reward.</p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_formula">Reward Formula </label>

                                <div class="controls">
                                    <input type="text" class="span6 typeahead" id="reward_formula"
                                           name="reward_formula"
                                           value=""/>

                                    <p class="help-block">
                                        Here you can provide formula how players will be rewarded. Ex: <span
                                                style="color:green;">5000 - ({position} * 450)</span> in result 1st
                                        place would receive 4650 credits but 10th place 500 Available variables
                                    <ul>
                                        <li>{position} - player position</li>
                                    </ul>
                                    </p>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="reward_type">Reward Type</label>

                                <div class="controls">
                                    <select id="reward_type" name="reward_type">
                                        <option
                                                value="1">Credits 1
                                        </option>
                                        <option
                                                value="2">Credits 2
                                        </option>
                                    </select>

                                    <p class="help-block">Monthly reward type.</p>

                                    <p>For credits types check your credits settings <a
                                                href="<?php echo $this->config->base_url; ?>admincp/manage-settings/credits"
                                                target="_blank">here</a></p>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary" name="edit_duels_settings"
                                        id="edit_duels_settings" value="Save changes">Save changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>