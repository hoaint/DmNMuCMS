<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Rankings'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('List top players, guilds and others'); ?></h2>

            <div class="entry">
                <script>
                    $(document).ready(function () {
                        $('#top_list').show();
                    });
                </script>
                <ul class="tabrow">
                    <?php
                        foreach($this->website->server_list() as $key => $servers):
                            if($servers['visible'] == 1){
                                $selectd = ($server == $key) ? 'class="selected"' : '';
                                ?>
                                <li <?php echo $selectd; ?>><a
                                            href="<?php echo $this->config->base_url . 'rankings/index/' . $key; ?>"><?php echo $servers['title']; ?></a>
                                </li>
                                <?php
                            }
                        endforeach;
                    ?>
                </ul>
                <script>
                    $(document).ready(function () {
                        <?php if (isset($config['player']['count']) && $config['player']['count'] > 0): ?>
                        App.populateRanking('players', '<?php echo $server; ?>');
                        <?php elseif (isset($config['guild']['count']) && $config['guild']['count'] > 0): ?>
                        App.populateRanking('guilds', '<?php echo $server; ?>');
                        <?php elseif (isset($config['killer']['count']) && $config['killer']['count'] > 0): ?>
                        App.populateRanking('killer', '<?php echo $server; ?>');
                        <?php elseif (isset($config['voter']['count']) && $config['voter']['count'] > 0): ?>
                        App.populateRanking('votereward', '<?php echo $server; ?>');
                        <?php elseif (isset($config['online']['count']) && $config['online']['count'] > 0): ?>
                        App.populateRanking('online', '<?php echo $server; ?>');
                        <?php elseif (isset($config['gens']['count']) && $config['gens']['count'] > 0): ?>
                        App.populateRanking('gens', '<?php echo $server; ?>');
                        <?php elseif (isset($config['bc']['count']) && $config['bc']['count'] > 0): ?>
                        App.populateRanking('bc', '<?php echo $server; ?>');
                        <?php elseif (isset($config['ds']['count']) && $config['ds']['count'] > 0): ?>
                        App.populateRanking('ds', '<?php echo $server; ?>');
                        <?php elseif (isset($config['cc']['count']) && $config['cc']['count'] > 0): ?>
                        App.populateRanking('cc', '<?php echo $server; ?>');
                        <?php elseif (isset($config['duels']['count']) && $config['duels']['count'] > 0): ?>
                        App.populateRanking('duels', '<?php echo $server; ?>');
                        <?php endif;?>
						
                    });
                </script>
                <div id="top_list" class="rankings">
                    <div id="rankings_select_<?php echo $server; ?>" style="text-align:center;">
                        <?php if(isset($config['player']['count']) && $config['player']['count'] > 0): ?>
                            <a href="javascript:;" class="custom_button"
                               id="players_ranking_<?php echo $server; ?>"><?php echo __('Top Players'); ?></a>
                        <?php
                        endif;
                            if(isset($config['guild']['count']) && $config['guild']['count'] > 0):
                                ?>
                                <a href="javascript:;" class="custom_button"
                                   id="guilds_ranking_<?php echo $server; ?>"><?php echo __('Top Guilds'); ?></a>
                            <?php
                            endif;
                            if(isset($config['killer']['count']) && $config['killer']['count'] > 0):
                                ?>
                                <a href="javascript:;" class="custom_button"
                                   id="killer_ranking_<?php echo $server; ?>"><?php echo __('Top Killers'); ?></a>
                            <?php
                            endif;
                            if(isset($config['voter']['count']) && $config['voter']['count'] > 0):
                                ?>
                                <a href="javascript:;" class="custom_button"
                                   id="votereward_ranking_<?php echo $server; ?>"><?php echo __('Top Voters'); ?></a>
                            <?php
                            endif;
                            if(isset($config['online']['count']) && $config['online']['count'] > 0):
                                ?>
                                <a href="javascript:;" class="custom_button"
                                   id="online_ranking_<?php echo $server; ?>"><?php echo __('Top Online'); ?></a>
                            <?php
                            endif;
                            if(isset($config['gens']['count']) && $config['gens']['count'] > 0):
                                ?>
                                <a href="javascript:;" class="custom_button"
                                   id="gens_ranking_<?php echo $server; ?>"><?php echo __('Top Gens'); ?></a>
                            <?php
                            endif;
                            if(isset($config['active']) && $config['active'] == 1):
                                ?>
                                <a href="<?php echo $this->config->base_url; ?>rankings/online-players/<?php echo $server; ?>" class="custom_button"><?php echo __('Online Players'); ?></a>
                            <?php
                            endif;
                        ?>
                        <?php
                            if(isset($config['bc']['count']) && $config['bc']['count'] > 0):
                                ?>
                                <a href="javascript:;" class="custom_button"
                                   id="bc_ranking_<?php echo $server; ?>"><?php echo __('Top BC'); ?></a>
                            <?php
                            endif;
                            if(isset($config['ds']['count']) && $config['ds']['count'] > 0):
                                ?>
                                <a href="javascript:;" class="custom_button"
                                   id="ds_ranking_<?php echo $server; ?>"><?php echo __('Top DS'); ?></a>
                            <?php
                            endif;
                            if(isset($config['cc']['count']) && $config['cc']['count'] > 0):
                                ?>
                                <a href="javascript:;" class="custom_button"
                                   id="cc_ranking_<?php echo $server; ?>"><?php echo __('Top CC'); ?></a>
                            <?php
                            endif;
                            if(isset($config['duels']['count']) && $config['duels']['count'] > 0):
                                ?>
                                <a href="javascript:;" class="custom_button"
                                   id="duels_ranking_<?php echo $server; ?>"><?php echo __('Top Duels'); ?></a>
                            <?php
                            endif;
							$plugins = $this->config->plugins();
							if(!empty($plugins)):
								foreach($plugins AS $plugin):
									if($plugin['installed'] == 1 && isset($plugin['rankings_panel_item']) && $plugin['rankings_panel_item'] == 1):
										?>
										<a href="<?php echo $plugin['module_url']; ?>" class="custom_button"><?php echo $plugin['about']['name']; ?></a>
									<?php
									endif;
								endforeach;
							endif;
                        ?>
                    </div>
                    <div id="rankings_content_<?php echo $server; ?>" style="padding: 10px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
	