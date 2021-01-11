<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Change Name'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <div style="float:left;">
                <h2 class="title"><?php echo __('Change character name.'); ?></h2>
            </div>
            <div style="float:right;padding-right:20px;">
                <a class="custom_button"
                   href="<?php echo $this->config->base_url; ?>shop/change-name-history"><?php echo __('Change Name History'); ?></a>
            </div>
            <div style="clear:both;"></div>
            <div class="entry">
                <?php
                    if(isset($not_found)){
                        echo '<div class="e_note">' . $not_found . '</div>';
                    } else{
                        if(isset($error)){
                            echo '<div class="e_note">' . $error . '</div>';
                        }
                        if(isset($success)){
                            echo '<div class="s_note">' . $success . '</div>';
                        }
                        ?>

                        <div class="form">
                            <table style="width: 100%;text-align:center;">
                                <?php
                                    if($char_list):
                                        $i = 0;
                                        foreach($char_list as $char):
                                            $i++;
                                            ?>
                                            <tr>
                                                <td style="padding-left: 70px;"><?php echo __('Character') . ' ' . $i; ?>
                                                    :
                                                </td>
                                                <td>
                                                    <input type="text" name="charname"
                                                           id="charname-<?php echo bin2hex($char['name']); ?>"
                                                           value="<?php echo $char['name']; ?>" tabindex="<?php echo $i; ?>"/>
                                                </td>
                                            </tr>
                                        <?php
                                        endforeach;
                                    endif;
                                ?>

                            </table>
                        </div>
                        <div id="ucp_info">
                            <ul align="left">
                                <?php
                                    $price = $this->config->config_entry('changename|price');
                                    if($this->session->userdata('vip')){
                                        $price -= ($price / 100) * $this->session->userdata(['vip' => 'change_name_discount']);
                                    }
                                ?>
                                <li><?php echo __('Character Name Change Cost') . ' ' . vsprintf(__('<span style="color:red;">%d</span> %s'), [$price, $this->website->translate_credits($this->config->config_entry('changename|price_type'), $this->session->userdata(['user' => 'server']))]); ?></li>
                                <li><?php echo sprintf(__('Character Name can be 4-%d chars long!'), $this->config->config_entry('changename|max_length')); ?></li>
                                <li><?php echo sprintf(__('Character Name can contain the following chars: %s'), stripslashes($this->config->config_entry('changename|allowed_pattern'))); ?></li>
                                <?php if($this->config->config_entry('changename|check_guild') == 1): ?>
                                    <li><?php echo __('Character cannot be a part from a guild at this moment in order successfully to change name.'); ?></li>
                                <?php endif; ?>
                            </ul>
                        </div>
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
