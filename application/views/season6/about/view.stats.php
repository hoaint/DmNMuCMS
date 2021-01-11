<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('About Server'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo strtoupper($title); ?><?php echo __('Server Statistics'); ?></h2>

            <div class="entry">
                <div style="float:right;">
                    <?php
                        foreach($this->website->server_list() as $key => $serv):
                            if($serv['visible'] == 1):
                                ?>
                                <a class="custom_button"
                                   href="<?php echo $this->config->base_url; ?>about/stats/<?php echo $key; ?>"><?php echo $serv['title']; ?><?php echo __('Statistics'); ?></a>
                            <?php
                            endif;
                        endforeach;
                    ?>
                </div>
                <div style="padding-top:40px;">
                    <table class="ranking-table">
                        <thead>
                        <tr class="main-tr">
                            <th colspan="2"><?php echo $title; ?><?php echo __('Server Statistics'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="width:50%;text-align: left;"><?php echo __('Total Accounts'); ?></td>
                            <td style="width:50%;text-align: left;"><?php echo $stats['accounts']; ?></td>
                        </tr>
                        <tr>
                            <td style="width:50%;text-align: left;"><?php echo __('Total Characters'); ?></td>
                            <td style="width:50%;text-align: left;"><?php echo $stats['chars']; ?></td>
                        </tr>
                        <tr>
                            <td style="width:50%;text-align: left;"><?php echo __('Total Guilds'); ?></td>
                            <td style="width:50%;text-align: left;"><?php echo $stats['guilds']; ?></td>
                        </tr>
                        <tr>
                            <td style="width:50%;text-align: left;"><?php echo __('Total Gms'); ?></td>
                            <td style="width:50%;text-align: left;"><?php echo $stats['gms']; ?></td>
                        </tr>
                        <tr>
                            <td style="width:50%;text-align: left;"><?php echo __('Total Online'); ?></td>
                            <td style="width:50%;text-align: left;"><?php echo $stats['online']; ?></td>
                        </tr>
                        <tr>
                            <td style="width:50%;text-align: left;"><?php echo __('Active In 24 Hours'); ?></td>
                            <td style="width:50%;text-align: left;" class="end"><?php echo $stats['active']; ?></td>
                        </tr>
                        </tbody>
                    </table>
                    <br/>
                    <?php
                        if($this->config->config_entry('market|module_status') == 1){
                            ?>
                            <table class="ranking-table">
                                <thead>
                                <tr class="main-tr">
                                    <th colspan="2"><?php echo $title; ?><?php echo __('Server Market Statistics'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style="width:50%;text-align: left;"><?php echo __('Total Items'); ?></td>
                                    <td style="width:50%;text-align: left;"><?php echo $stats['market_items']; ?></td>
                                </tr>
                                <tr>
                                    <td style="width:50%;text-align: left;"><?php echo __('Active Items'); ?></td>
                                    <td style="width:50%;text-align: left;"><?php echo $stats['market_active']; ?></td>
                                </tr>
                                <tr>
                                    <td style="width:50%;text-align: left;"><?php echo __('Expired Items'); ?></td>
                                    <td style="width:50%;text-align: left;"><?php echo $stats['market_expired']; ?></td>
                                </tr>
                                <tr>
                                    <td style="width:50%;text-align: left;"><?php echo __('Total Sold'); ?></td>
                                    <td style="width:50%;text-align: left;"><?php echo $stats['total_sold']; ?></td>
                                </tr>
                                <tr>
                                    <td style="width:50%;text-align: left;"><?php echo __('Total Sales For'); ?><?php echo $this->config->config_entry('credits_' . $server . '|title_1'); ?></td>
                                    <td style="width:50%;text-align: left;"><?php echo $stats['sales_credits']; ?></td>
                                </tr>
                                <tr>
                                    <td style="width:50%;text-align: left;"><?php echo __('Total Sales For'); ?><?php echo $this->config->config_entry('credits_' . $server . '|title_2'); ?></td>
                                    <td style="width:50%;text-align: left;"><?php echo $stats['sales_gcredits']; ?></td>
                                </tr>
                                <tr>
                                    <td style="width:50%;text-align: left;"><?php echo __('Total Sales For'); ?><?php echo $this->config->config_entry('credits_' . $server . '|title_3'); ?></td>
                                    <td style="width:50%;text-align: left;"
                                        class="end"><?php echo $this->website->zen_format($stats['sales_zen']); ?></td>
                                </tr>
                                </tbody>
                            </table>
                            <br/>
                            <?php
                        }
					if(MU_VERSION >= 1){
					?>	
                    <table class="ranking-table">
                        <thead>
                        <tr class="main-tr">
                            <th colspan="2"><?php echo __('CryWolf Info'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="width:50%;text-align: left;"><?php echo __('Status Of The Fortress'); ?></td>
                            <td style="width:50%;text-align: left;" class="end"><?php echo $crywolf_state; ?></td>
                        </tr>
                        </tbody>
                    </table>
                    <br/>
					<?php
					}
					?>
                    <table class="ranking-table" cellpadding="0" cellspacing="0">
                        <thead>
                        <tr class="main-tr">
                            <th colspan="2"><?php echo __('Castle Siege Info'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            if($cs_info['guild'] != null){
                                ?>
                                <tr>
                                    <td style="width:50%;text-align: left;"><?php echo __('Owner Guild'); ?></td>
                                    <td style="width:50%;text-align: left;"><?php echo $cs_info['guild']; ?></td>
                                </tr>
                                <tr>
                                    <td style="width:50%;text-align: left;"><?php echo __('State'); ?></td>
                                    <td style="width:50%;text-align: left;"><?php echo $cs_info['period']; ?></td>
                                </tr>
                                <tr>
                                    <td style="width:50%;text-align: left;"><?php echo __('Time'); ?></td>
                                    <td style="width:50%;text-align: left;"><span id="cs_countdown"></span>
                                        <script type="text/javascript">$(document).ready(function () {
                                                App.castleSiegeCountDown("cs_countdown", <?php echo $cs_info['battle_start'];?>, <?php echo time();?>);
                                            });</script>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width:50%;text-align: left;"><?php echo __('Money'); ?></td>
                                    <td style="width:50%;text-align: left;"><?php echo $this->website->zen_format($cs_info['money']); ?><?php echo __('Zen'); ?></td>
                                </tr>
                                <tr>
                                    <td style="width:50%;text-align: left;"><?php echo __('tax_chaos'); ?></td>
                                    <td style="width:50%;text-align: left;"><?php echo $cs_info['tax_chaos']; ?>%</td>
                                </tr>
                                <tr>
                                    <td style="width:50%;text-align: left;"><?php echo __('Tax Store'); ?></td>
                                    <td style="width:50%;text-align: left;"><?php echo $cs_info['tax_store']; ?>%</td>
                                </tr>
                                <tr>
                                    <td style="width:50%;text-align: left;"><?php echo __('Tax Hunt Zone'); ?></td>
                                    <td style="width:50%;text-align: left;"
                                        class="end"><?php echo $cs_info['tax_hunt']; ?><?php echo __('Zen'); ?></td>
                                </tr>
                                <?php
                            } else{
                                ?>
                                <tr>
                                    <td colspan="2">
                                        <div
                                                class="i_note"><?php echo __('Castle Siege Not Active.'); ?></div>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                        </tbody>
                    </table>
                    <br/>
                    <table class="ranking-table">
                        <thead>
                        <tr class="main-tr">
                            <th>#</th>
                            <th><?php echo __('Guild'); ?></th>
                            <th><?php echo __('Master'); ?></th>
                            <th><?php echo __('Reg Marks'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(!empty($cs_guild_list)){
                                foreach($cs_guild_list as $key => $guild){
                                    ?>
                                    <tr>
                                        <td style="padding: 6px 3px 6px 3px;"><?php echo($key + 1); ?></td>
                                        <td style="padding:0;"><a
                                                    href="<?php echo $this->config->base_url; ?>guild/<?php echo bin2hex($guild['REG_SIEGE_GUILD']); ?>/<?php echo $server; ?>"><?php echo $guild['REG_SIEGE_GUILD']; ?></a>
                                        </td>
                                        <td style="padding:0;"><a style="font-size:12px; font-weight:bold; color:#555555;"
                                                                  href="<?php echo $this->config->base_url; ?>character/<?php echo bin2hex($guild['G_Master']); ?>/<?php echo $server; ?>"><?php echo $guild['G_Master']; ?></a>
                                        </td>
                                        <td style="padding:0;" class="end"><?php echo $guild['REG_MARKS']; ?></td>
                                    </tr>
                                    <?php
                                }
                            } else{
                                echo '<tr><td colspan="4"><div class="i_note">' . __('No Registered Guilds') . '</div></td></tr>';
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
	