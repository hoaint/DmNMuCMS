<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Grand Reset'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Grand Reset your character'); ?></h2>

            <div class="entry">
                <?php
                    if(isset($error)){
                        echo '<div class="e_note">' . $error . '</div>';
                    } else{
                        ?>
                        <script>
                            $(document).ready(function () {
                                $('a.view_reward').each(function () {
                                    App.initializeTooltip($(this), false);
                                });
                            });
                        </script>
                        <table class="ranking-table">
                            <thead>
                            <tr class="main-tr">
                                <th><?php echo __('Character'); ?></th>
                                <th><?php echo __('Res / Req'); ?></th>
                                <th><?php echo __('LvL / Req'); ?></th>
                                <th><?php echo __('Zen / Req'); ?></th>
                                <th><?php echo __('Reward'); ?></th>
                                <th><?php echo __('Manage'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach($chars AS $name => $data){
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo $this->config->base_url; ?>character/<?php echo bin2hex($name); ?>/<?php echo $this->session->userdata(['user' => 'server']); ?>"><?php echo $name; ?></a>
                                        </td>
                                        <td>
                                            <?php if($data['gres_info'] != false){
                                                if($this->session->userdata('vip')){
                                                    $data['gres_info']['bonus_credits'] += $this->session->userdata(['vip' => 'grand_reset_bonus_credits']);
                                                    $data['gres_info']['bonus_gcredits'] += $this->session->userdata(['vip' => 'grand_reset_bonus_credits']);
                                                }
                                                ?>
                                                <span id="resets-<?php echo bin2hex($name); ?>">
								    <?php if($data['resets'] < $data['gres_info']['reset']){ ?>
                                        <span style="color: red;"><?php echo $data['resets']; ?></span>
                                    <?php } else{ ?>
                                        <?php echo $data['resets']; ?><?php } ?>
							        </span> / <?php echo $data['gres_info']['reset']; ?>
                                            <?php } else{ ?>
                                                <span
                                                        id="resets-<?php echo bin2hex($name); ?>"><?php echo $data['resets']; ?></span> / 0
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if($data['gres_info'] != false){ ?>
                                                <span id="lvl-<?php echo bin2hex($name); ?>">
								<?php if($data['level'] < $data['gres_info']['level']){ ?>
                                    <span style="color: red;"><?php echo $data['level']; ?></span>
                                <?php } else{ ?>
                                    <?php echo $data['level']; ?><?php } ?>
							</span> / <?php echo $data['gres_info']['level']; ?>
                                            <?php } else{ ?>
                                                <span
                                                        id="lvl-<?php echo bin2hex($name); ?>"><?php echo $data['level']; ?></span> / 0
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php echo $this->website->zen_format($data['money']); ?> /
                                            <?php
                                                if($data['gres_info'] != false){
                                                    if($data['gres_info']['money_x_reset'] == 1){
                                                        echo $this->website->zen_format($data['gres_info']['money'] * ($data['resets'] + 1));
                                                    } else{
                                                        echo $this->website->zen_format($data['gres_info']['money']);
                                                    }
                                                } else{
                                                    echo 0;
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                $reward = '';
                                                if($data['gres_info'] != false){
                                                    if($data['gres_info']['bonus_credits'] > 0){
                                                        $reward .= $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_1') . ' ' . __('Bonus') . ' + ' . $data['gres_info']['bonus_credits'] . '<br />';
                                                    }
                                                    if($data['gres_info']['bonus_gcredits'] > 0){
                                                        $reward .= $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_2') . ' ' . __('Bonus') . ' + ' . $data['gres_info']['bonus_gcredits'] . '<br />';
                                                    }
                                                    if($data['gres_info']['bonus_points_save'] == 1){
                                                        $reward .= __('Stats Bonus') . ' + ' . $this->Mcharacter->bonus_points_by_class($data['Class'], 'gres_info', $data) * ($data['gresets'] + 1) . '<br />';
                                                    } else{
                                                        $reward .= __('Stats Bonus') . ' + ' . $this->Mcharacter->bonus_points_by_class($data['Class'], 'gres_info', $data) . '<br />';
                                                    }
                                                    if($data['gres_info']['bonus_reset_stats'] == 1 && $data['bonus_reset_stats'] > 0){
                                                        $reward .= __('Bonus Stats For Resets') . ' + ' . $data['bonus_reset_stats'] . '<br />';
                                                    }
                                                }
                                            ?>
                                            <a class="view_reward" href="#"
                                               data-info="<?php echo $reward; ?>"><?php echo __('View Reward'); ?></a>
                                        </td>
                                        <td>
                                            <?php if($data['gres_info'] != false){ ?><a href="#"
                                                                                        id="greset-char-<?php echo bin2hex($name); ?>"><?php echo __('Grand Reset'); ?></a><?php } else{
                                                echo __('Grand Reset Disabled');
                                            } ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            ?>
                            </tbody>
                        </table>
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
	