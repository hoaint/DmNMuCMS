<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <?php
            if(isset($config_not_found)):
                echo '<div class="box-style1"><div class="entry"><div class="e_note">' . $config_not_found . '</div></div></div>';
            else:
                if(isset($module_disabled)):
                    echo '<div class="box-style1"><div class="entry"><div class="e_note">' . $module_disabled . '</div></div></div>';
                else:
                    ?>
                    <div class="title1">
                        <h1><?php echo $about['name']; ?></h1>
                    </div>
                    <div id="content_center">
                        <div class="box-style1" style="margin-bottom:55px;">
                            <h2 class="title"><?php echo $about['user_description']; ?></h2>
                            <div class="entry">
                                <?php
                                    if(isset($config_not_found)):
                                        echo '<div class="e_note">' . $config_not_found . '</div>';
                                    else:
                                        if(isset($module_disabled)):
                                            echo '<div class="e_note">' . $module_disabled . '</div>';
                                        else:
                                        if(isset($js)):
                                            ?>
                                            <script src="<?php echo $js; ?>"></script>
                                        <?php endif;
                                        ?>
											<ul class="tabrow">
											<?php
												foreach($plugin_config as $key => $servers):
													if($servers['active'] == 1){
														$selectd = ($firstKey == $key) ? 'class="selected"' : '';
														?>
														<li <?php echo $selectd; ?>><a href="<?php echo $this->config->base_url . $this->request->get_controller() .'/'.$this->request->get_method().'/index/' . $key; ?>"><?php echo $this->website->get_title_from_server($key); ?></a></li>
														<?php
													}
												endforeach;
											?>
											</ul>
											<script>
                                                var monsterKill = new monsterKill();
                                                monsterKill.setUrl('<?php echo $this->config->base_url . $this->request->get_controller();?>/<?php echo $this->request->get_method();?>');
                                                $(document).ready(function () {
												  
                                                  
												    monsterKill.populateRanking('<?php echo $firstKey; ?>');	
												   $('div[id^="rank_by_npc"] a').each(function(){
														$(this).on("click", function(e){
															e.preventDefault();
															var c_npc = $(this).attr("id").split("_")[2],
																server = $(this).attr("id").split("_").slice(3).join('_');
															monsterKill.populateRanking(server, c_npc);
														});
													});
                                                });
                                            </script>	
											<div id="top_list" class="rankings">	
												<div id="rank_by_npc" style="text-align:center;">
													<a href="javascript:;" class="custom_button" id="monster_rankings_all_<?php echo $firstKey; ?>">All</a>
													<?php 
													$monsters = explode(',', $plugin_config[$firstKey]['monsters']); 
													foreach($monsters AS $val):
													?>
													<a href="javascript:;" class="custom_button" id="monster_rankings_<?php echo $val; ?>_<?php echo $firstKey; ?>"><?php echo $this->npc->name_by_id($val); ?></a>
													<?php	
													endforeach;
													?>														
												</div>
												<br />
												<div id="plugin_rankings_content_<?php echo $firstKey; ?>" style="padding: 10px;"></div>
											</div>
                                        <?php
                                        endif;
                                    endif;
                                ?>
                            </div>
                        </div>
                    </div>
                <?php
                endif;
            endif;
        ?>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>

