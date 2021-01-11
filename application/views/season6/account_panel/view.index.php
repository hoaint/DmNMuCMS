<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Account Panel'); ?></h1>
        </div>
        <div id="content_center">
            <div class="box-style4" style="margin-bottom: 20px;">
                <h2 class="title"><?php echo __('View account and character options'); ?></h2>

                <div class="entry">
                    <div id="ucp_info">
                        <div class="half">
                            <table width="100%">
                                <tr>
                                    <td width="5%"><img
                                                src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/images/icons/user.png"/>
                                    </td>
                                    <td width="45%"><?php echo __('Account'); ?></td>
                                    <td width="50%"><?php echo $this->session->userdata(['user' => 'username']); ?></td>
                                </tr>
                                <tr>
                                    <td><img
                                                src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/images/icons/email.png"/>
                                    </td>
                                    <td><?php echo __('Email'); ?></td>
                                    <td><?php echo $this->session->userdata(['user' => 'email']); ?></td>
                                </tr>
                                <tr>
                                    <td><img
                                                src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/images/icons/award_star_bronze_1.png"/>
                                    </td>
                                    <td><?php echo __('Rank'); ?></td>
                                    <td><?php echo __('User'); ?></td>
                                </tr>
                                <tr>
                                    <td><img
                                                src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/images/icons/server.png"/>
                                    </td>
                                    <td><?php echo __('Server'); ?></td>
                                    <td><?php echo $this->session->userdata(['user' => 'server_t']); ?></td>
                                </tr>
                                <?php if($this->config->values('vip_config', 'active') == 1): ?>
                                    <tr>
                                        <td><img
                                                    src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/images/icons/shield.png"/>
                                        </td>
                                        <td><?php echo __('Vip'); ?></td>
                                        <td><?php echo ($this->session->userdata('vip')) ? $this->session->userdata(['vip' => 'title']) . ' (<a href="' . $this->config->base_url . 'shop/vip">' . __('Extend Now') . '</a>)' : __('None') . ' (<a href="' . $this->config->base_url . 'shop/vip">' . __('Buy Now') . '</a>)'; ?></td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                        <div class="half">
                            <table width="100%">
                                <tr>
                                    <td width="5%"><img
                                                src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/images/icons/date.png"/>
                                    </td>
                                    <td width="40%"><?php echo __('Member Since'); ?></td>
                                    <td width="55%"><?php echo date('Y-m-d', strtotime($this->session->userdata(['user' => 'joined']))); ?></td>
                                </tr>
                                <tr>
                                    <td><img
                                                src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/images/icons/shield.png"/>
                                    </td>
                                    <td><?php echo __('Last Login'); ?></td>
                                    <td>
                                        <?php
                                            if(date('Y-m-d', strtotime($this->session->userdata(['user' => 'last_login']))) == date('Y-m-d', time())):
                                                echo __('Today') . ' ' . date('H:i', strtotime($this->session->userdata(['user' => 'last_login'])));
                                            else:
                                                echo date('Y-m-d', strtotime($this->session->userdata(['user' => 'last_login'])));
                                            endif;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><img
                                                src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/images/icons/ip.png"/>
                                    </td>
                                    <td><?php echo __('Last Login Ip'); ?></td>
                                    <td><?php echo $this->session->userdata(['user' => 'last_ip']); ?></td>
                                </tr>
                                <tr>
                                    <td><img
                                                src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/images/icons/ip.png"/>
                                    </td>
                                    <td><?php echo __('Current Ip'); ?></td>
                                    <td><?php echo $this->website->ip(); ?></td>
                                </tr>
                                <?php if($this->config->values('vip_config', 'active') == 1): ?>
                                    <tr>
                                        <td><img
                                                    src="<?php echo $this->config->base_url; ?>assets/<?php echo $this->config->config_entry('main|template'); ?>/images/icons/lightning.png"/>
                                        </td>
                                        <td><?php echo __('Vip Expires'); ?></td>
                                        <td><?php echo ($this->session->userdata('vip')) ? date('Y-m-d h:i', $this->session->userdata(['vip' => 'time'])) : __('Expired'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                </div>
            </div>
            <div class="box-style4">
                <h2 class="title"><?php echo __('Character Options'); ?></h2>
                <div class="entry">
                    <div id="character-info">
                        <table>
                            <tbody>
                            <tr>
                                <td>
                                    <div>
                                        <ul>
                                            <li>
                                                <a href="<?php echo $this->config->base_url; ?>account-panel/reset">
                                                    <p><?php echo __('Reset'); ?></p>
                                                </a>
                                                <?php echo __('Reset your character level'); ?>
                                                <br/>&nbsp;
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <ul>
                                            <li>
                                                <a href="<?php echo $this->config->base_url; ?>grand-reset-character">
                                                    <p><?php echo __('Grand Reset'); ?></p>
                                                </a>
                                                <?php echo __('Grand Reset your character'); ?>
                                                <br/>&nbsp;
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <ul>
                                            <li>
                                                <a href="<?php echo $this->config->base_url; ?>add-stats">
                                                    <p><?php echo __('Add Stats'); ?></p>
                                                </a>
                                                <?php echo __('Add level up points. Str. Agi. Vit. etc'); ?>
                                                <br/>&nbsp;
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <ul>
                                            <li>
                                                <a href="<?php echo $this->config->base_url; ?>reset-stats">
                                                    <p><?php echo __('Reset Stats'); ?></p>
                                                </a>
                                                <?php echo __('Reassign your stats'); ?>
                                                <br/>&nbsp;
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <ul>
                                            <li>
                                                <a href="<?php echo $this->config->base_url; ?>hide-character-info">
                                                    <p><?php echo __('Hide Info'); ?></p>
                                                </a>
                                                <?php echo __('Hide inventory / location from others'); ?>
                                                <br/>&nbsp;
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <ul>
                                            <li>
                                                <a href="<?php echo $this->config->base_url; ?>exchange-wcoins">
                                                    <p><?php echo __('Exchange Wcoins'); ?></p>
                                                </a>
                                                <?php echo __('Exchange credits to Wcoins'); ?>
                                                <br/>&nbsp;
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <ul>
                                            <li>
                                                <a href="<?php echo $this->config->base_url; ?>warp-char">
                                                    <p><?php echo __('Warp Character'); ?></p>
                                                </a>
                                                <?php echo __('Move to another location.<br />Use to unstuck character!'); ?>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <ul>
                                            <li>
                                                <a href="<?php echo $this->config->base_url; ?>pk-clear">
                                                    <p><?php echo __('PK Clear'); ?></p>
                                                </a>
                                                <?php echo __('Clear player kills.<br />Receive normal status'); ?>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <ul>
                                            <li>
                                                <a href="<?php echo $this->config->base_url; ?>clear-inventory">
                                                    <p><?php echo __('Clear Inventory'); ?></p>
                                                </a>
                                                <?php echo __('Remove items from inventory'); ?>
                                                <br/>&nbsp;
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <ul>
                                            <li>
                                                <a href="<?php echo $this->config->base_url; ?>zen-wallet">
                                                    <p><?php echo __('Zen Wallet'); ?></p>
                                                </a>
                                                <?php echo __('Transfer zen between characters and other places.'); ?>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div>
                                        <ul>
                                            <li>
                                                <a href="<?php echo $this->config->base_url; ?>clear-skilltree">
                                                    <p><?php echo __('Clear SkillTree'); ?></p>
                                                </a>
                                                <?php echo __('Reset character skilltree.'); ?>
                                                <br/>&nbsp;
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div>
                                        <ul>
                                            <li>
                                                <a href="<?php echo $this->config->base_url; ?>account-panel/exchange-online">
                                                    <p><?php echo __('Trade Online Time'); ?></p>
                                                </a>
                                                <?php echo __('More online more can exchange and receive nice rewards'); ?>
                                                <br/>&nbsp;
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php if($this->config->values('referral_config', 'active') == 1): ?>
                                <tr>
                                    <td colspan="2">
                                        <div>
                                            <ul>
                                                <li>
                                                    <a href="<?php echo $this->config->base_url; ?>account-panel/my-referral-list">
                                                        <p><?php echo __('Referral System'); ?></p>
                                                    </a>
                                                    <?php echo __('Invite friends and get rewards.'); ?>
                                                    <br/>&nbsp;
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if($this->config->config_entry('changename|module_status') == 1): ?>
                                <tr>
                                    <td colspan="2">
                                        <div>
                                            <ul>
                                                <li>
                                                    <a href="<?php echo $this->config->base_url; ?>shop/change-name">
                                                        <p><?php echo __('Change Name'); ?></p>
                                                    </a>
                                                    <?php echo __('Change character name.'); ?>
                                                    <br/>&nbsp;
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if($this->config->values('change_class_config', 'active') == 1): ?>
                                <tr>
                                    <td colspan="2">
                                        <div>
                                            <ul>
                                                <li>
                                                    <a href="<?php echo $this->config->base_url; ?>shop/change-class">
                                                        <p><?php echo __('Change Class'); ?></p>
                                                    </a>
                                                    <?php echo __('Change character class.'); ?>
                                                    <br/>&nbsp;
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if($this->config->values('buylevel_config', [$this->session->userdata(['user' => 'server']), 'active']) == 1): ?>
                                <tr>
                                    <td colspan="2">
                                        <div>
                                            <ul>
                                                <li>
                                                    <a href="<?php echo $this->config->base_url; ?>shop/buy-level">
                                                        <p><?php echo __('Buy Level'); ?></p>
                                                    </a>
                                                    <?php echo __('Buy level for your character.'); ?>
                                                    <br/>&nbsp;
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if($this->config->config_entry('buypoints|module_status') == 1): ?>
                                <tr>
                                    <td colspan="2">
                                        <div>
                                            <ul>
                                                <li>
                                                    <a href="<?php echo $this->config->base_url; ?>shop/buy-stats">
                                                        <p><?php echo __('Buy Stats'); ?></p>
                                                    </a>
                                                    <?php echo __('Buy StatPoints for your character'); ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if($this->config->config_entry('buygm|module_status') == 1): ?>
                                <tr>
                                    <td colspan="2">
                                        <div>
                                            <ul>
                                                <li>
                                                    <a href="<?php echo $this->config->base_url; ?>shop/buy-gm">
                                                        <p><?php echo __('Buy GM'); ?></p>
                                                    </a>
                                                    <?php echo __('Buy GameMaster status for your character'); ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if($this->config->values('vip_config', 'active') == 1): ?>
                                <tr>
                                    <td colspan="2">
                                        <div>
                                            <ul>
                                                <li>
                                                    <a href="<?php echo $this->config->base_url; ?>shop/vip">
                                                        <p><?php echo __('Buy vip'); ?></p>
                                                    </a>
                                                    <?php echo __('Buy vip status.'); ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php
                                $plugins = $this->config->plugins();
                                if(!empty($plugins)):
                                    if(array_key_exists('merchant', $plugins)){
                                        if($this->session->userdata(['user' => 'is_merchant']) != 1){
                                            unset($plugins['merchant']);
                                        }
                                    }
                                    foreach($plugins AS $plugin):
                                        if($plugin['installed'] == 1 && $plugin['account_panel_item'] == 1):
                                            ?>
                                            <tr>
                                                <td colspan="2">
                                                    <div>
                                                        <ul>
                                                            <li>
                                                                <a href="<?php echo $plugin['module_url']; ?>">
                                                                    <p><?php echo $plugin['about']['name']; ?></p>
                                                                </a>
                                                                <?php echo $plugin['description']; ?>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php
                                        endif;
                                    endforeach;
                                endif;
                            ?>
                            <?php if(defined('RES_CUSTOM_BACKUP_MASTER') && RES_CUSTOM_BACKUP_MASTER == true): ?>
                                <tr>
                                    <td colspan="2">
                                        <div>
                                            <ul>
                                                <li>
                                                    <a href="<?php echo $this->config->base_url; ?>account-panel/recover-master">
                                                        <p><?php echo __('Recover MasterLevel'); ?></p>
                                                    </a>
                                                    <?php echo __('Recover your character master level and class'); ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.right_sidebar');
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.footer');
?>
	