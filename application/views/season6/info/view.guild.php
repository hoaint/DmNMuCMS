<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Info'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title">
                <?php $args = $this->request->get_args(); ?>
                <?php echo sprintf(__('Guild %s Info'), $this->website->hex2bin($args[0])); ?>
            </h2>

            <div class="entry">
                <?php
                    if(isset($error)){
                        echo '<div class="e_note">' . $error . '</div>';
                    } else{
                        ?>
                        <table class="ranking-table">
                            <thead>
                            <tr class="main-tr">
                                <th colspan="2"
                                    style="padding-left: 15px;"><?php echo __('Information'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="width: 70px;">
                                    <img
                                            src="<?php echo $this->config->base_url; ?>rankings/get_mark/<?php echo $guild_info['G_Mark']; ?>/132"
                                            style="border: 0;"/>
                                </td>
                                <td style="width: 240px;">
                                    <table style="width:100%;margin: 0 auto;">
                                        <tr>
                                            <td style="width:50%;text-align: left;"><?php echo __('Guild'); ?></td>
                                            <td style="width:50%;text-align: left;"><a
                                                        href="<?php echo $this->config->base_url; ?>guild/<?php echo bin2hex($guild_info['G_Name']); ?>/<?php echo $args[1]; ?>"><?php echo $guild_info['G_Name']; ?></a>
                                            </td>
                                        </tr>
                                        <tr style="height:15px;">
                                            <td style="width:50%;text-align: left;"><?php echo __('Master'); ?></td>
                                            <td style="width:50%;text-align: left;"><a
                                                        href="<?php echo $this->config->base_url; ?>character/<?php echo bin2hex($guild_info['G_Master']); ?>/<?php echo $args[1]; ?>"><?php echo $guild_info['G_Master']; ?></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:50%;text-align: left;"><?php echo __('Score'); ?></td>
                                            <td style="width:50%;text-align: left;"><?php echo $guild_info['G_Score']; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="width:50%;text-align: left;"><?php echo __('Member Count'); ?></td>
                                            <td style="width:50%;text-align: left;"><?php echo $guild_info['MemberCount']; ?></td>
                                        </tr>
                                        <tr>
                                            <td style="width:50%;text-align: left;"><?php echo __('Alliance'); ?></td>
                                            <td style="width:50%;text-align: left;"><?php echo $guild_info['aliance_guilds']; ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <br/>
                        <table class="ranking-table" cellpadding="0" cellspacing="0">
                            <thead>
                            <tr class="main-tr">
                                <th>#
                                </td>
                                <th><?php echo __('Name'); ?></th>
                                <th><?php echo __('Class'); ?></th>
                                <th><?php if($this->config->values('rankings_config', [$args[1], 'player', 'display_resets']) == 1){ ?><?php echo __('Resets'); ?><?php } ?> <?php if($this->config->values('rankings_config', [$args[1], 'player', 'display_gresets']) == 1){ ?>
                                        <sup style="color: red;"><?php echo __('GR'); ?></sup><?php } ?>
                                </th>
                                <th><?php echo __('LvL'); ?></th>
                                <th><?php echo __('Position'); ?></th>
								<th><?php echo __('Status'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                if(!empty($guild_members)){
                                    foreach($guild_members as $key => $member){
                                        ?>
                                        <tr>
                                            <td style="text-align: center;"><?php echo($key + 1); ?></td>
                                            <td>
                                                <a href="<?php echo $this->config->base_url; ?>character/<?php echo bin2hex($member['name']); ?>/<?php echo $args[1]; ?>"><?php echo $member['name']; ?></a>
                                            </td>
                                            <td style="text-align: center;"><?php echo $member['class']; ?></td>
                                            <td style="text-align: center;"><?php if($this->config->values('rankings_config', [$args[1], 'player', 'display_resets']) == 1){ ?><?php echo $member['resets']; ?><?php } ?> <?php if($this->config->values('rankings_config', [$args[1], 'player', 'display_gresets']) == 1){ ?>
                                                    <sup style="color: red;"><?php echo $member['gresets']; ?></sup><?php } ?>
                                            </td>
                                            <td style="text-align: center;"><?php echo $member['level']; ?></td>
                                            <td style="text-align: center;"><?php echo $member['position']; ?></td>
											<td style="text-align: center;">
											<?php if($member['status'] == 1){ ?>
											<img src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template');?>/images/online.png" title="Online" />
											<?php  } else { ?>
											<img src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template');?>/images/offline.png" title="Offline" />
											<?php } ?>
											</td>
                                        </tr>
                                        <?php
                                    }
                                } else{
                                    echo '<tr><td colspan="6"><div class="i_note">' . __('No Members') . '</div></td></tr>';
                                }
                            ?>
                            </tbody>
                        </table>
                    <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
	