<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Reset Stats'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Reassign your stats'); ?></h2>

            <div class="entry">
                <?php
                    if($this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|allow_reset_stats') == 1){
                        if(isset($char_list) && $char_list != false){
                            ?>
                            <table class="ranking-table">
                                <thead>
                                <tr class="main-tr">
                                    <th><?php echo __('Character'); ?></th>
                                    <th><?php echo __('New LevelUp Points'); ?></th>
                                    <?php if($this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|reset_stats_price') > 0){ ?>
                                        <th><?php echo __('Price'); ?></th>
                                    <?php } ?>
                                    <th><?php echo __('Manage'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    foreach($char_list as $char){
                                        $char_info = $this->Mcharacter->check_char($char['name']);
										$baseStats = $this->Mcharacter->getBaseStats($this->Mcharacter->char_info['Class'], $this->session->userdata(['user' => 'server']));
                                        $new_stats = 0;
                                        if($this->Mcharacter->char_info['Strength'] > $baseStats['Strength']){
                                            $new_stats += $this->Mcharacter->char_info['Strength'] - $baseStats['Strength'];
                                        }
                                        if($this->Mcharacter->char_info['Dexterity'] > $baseStats['Dexterity']){
                                            $new_stats += $this->Mcharacter->char_info['Dexterity'] - $baseStats['Dexterity'];
                                        }
                                        if($this->Mcharacter->char_info['Energy'] > $baseStats['Energy']){
                                            $new_stats += $this->Mcharacter->char_info['Energy'] - $baseStats['Energy'];
                                        }
                                        if($this->Mcharacter->char_info['Vitality'] > $baseStats['Vitality']){
                                            $new_stats += $this->Mcharacter->char_info['Vitality'] - $baseStats['Vitality'];
                                        }
                                        if(in_array($this->Mcharacter->char_info['Class'], [64, 65, 66]) && $this->Mcharacter->char_info['Leadership'] > $baseStats['Leadership']){
                                            $new_stats += $this->Mcharacter->char_info['Leadership'] - $baseStats['Leadership'];
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo $this->config->base_url; ?>character/<?php echo bin2hex($char['name']); ?>/<?php echo $this->session->userdata(['user' => 'server']); ?>"><?php echo $char['name']; ?></a>
                                            </td>
                                            <td>
                                        <span
                                                id="new-stats-<?php echo bin2hex($char['name']); ?>"><?php echo $new_stats; ?></span>
                                            </td>
                                            <?php if($this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|reset_stats_price') > 0){ ?>
                                                <td><?php echo $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|reset_stats_price'); ?><?php echo $this->website->translate_credits($this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|reset_stats_payment_type'), $this->session->userdata(['user' => 'server'])); ?></td>
                                            <?php } ?>
                                            <td><a href="#"
                                                   id="reset-stats-char-<?php echo bin2hex($char['name']); ?>"><?php echo __('Reset Stats'); ?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                ?>
                                </tbody>
                            </table>
                            <?php
                        } else{
                            ?>
                            <div
                                    class="e_note"><?php echo __('Character not found.'); ?></div>
                            <?php
                        }
                    } else{
                        ?>
                        <div
                                class="e_note"><?php echo __('Reset Stats Disabled'); ?></div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
	