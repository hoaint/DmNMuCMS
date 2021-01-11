<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Reset'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Reset your character level'); ?></h2>

            <div class="entry">
                <?php
                    if(isset($error)){
                        echo '<div class="e_note">' . $error . '</div>';
                    } else{
                        ?>
                        <table class="ranking-table">
                            <thead>
                            <tr class="main-tr">
                                <th><?php echo __('Character'); ?></th>
                                <th><?php echo __('Res'); ?></th>
                                <th><?php echo __('LvL / Req'); ?></th>
                                <th><?php echo __('Zen / Req'); ?></th>
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
                                        <td><span
                                                    id="resets-<?php echo bin2hex($name); ?>"><?php echo $data['resets']; ?></span>
                                        </td>
                                        <td>
                                            <?php if($data['res_info'] != false){
                                                if($this->session->userdata('vip')){
                                                    $data['res_info']['money'] -= $this->session->userdata(['vip' => 'reset_price_decrease']);
                                                    $data['res_info']['level'] -= $this->session->userdata(['vip' => 'reset_level_decrease']);
                                                }
                                                ?>
                                                <span id="lvl-<?php echo bin2hex($name); ?>">
								        <?php if($data['level'] < $data['res_info']['level']){ ?>
                                            <span style="color: red;"><?php echo $data['level']; ?></span>
                                        <?php } else{ ?>
                                            <?php echo $data['level']; ?><?php } ?>
							            </span> / <?php echo $data['res_info']['level']; ?>
                                            <?php } else{ ?>
                                                <span
                                                        id="lvl-<?php echo bin2hex($name); ?>"><?php echo $data['level']; ?></span> / 0

                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php echo $this->website->zen_format($data['money']); ?> /
                                            <?php
                                                if($data['res_info'] != false){
                                                    if($data['res_info']['money_x_reset'] == 1){
                                                        $money = $data['res_info']['money'] * ($data['resets'] + 1);
                                                    } else{
                                                        $money = $data['res_info']['money'];
                                                    }
                                                    echo $this->website->zen_format($money);
                                                } else{
                                                    echo 0;
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if($data['res_info'] != false){ ?>
                                                <a href="#"
                                                   id="reset-char-<?php echo bin2hex($name); ?>"><?php echo __('Reset'); ?></a>
                                            <?php } else{
                                                echo __('Reset Disabled');
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
	