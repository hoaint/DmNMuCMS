<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Donate'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('With PayCall'); ?></h2>

            <div class="entry">
                <?php
                    if(isset($error)){
                        echo '<div class="e_note">' . $error . '</div>';
                    } else{
                        if(!empty($paycall_packages)){
                            foreach($paycall_packages as $packages){
                                if($this->session->userdata('vip')){
                                    $packages['reward'] += ($packages['reward'] / 100) * $this->session->userdata(['vip' => 'bonus_credits_for_donate']);
                                }
                                echo '<ul id="paypal-options">
									<li>
										<h4 class="left">' . $packages['package'] . '</h4>
										<h3 class="left"><span id="reward_' . $packages['id'] . '">' . $packages['reward'] . '</span> ' . $this->website->translate_credits($donation_config['reward_type'], $this->session->userdata(['user' => 'server'])) . ' (<span id="price_' . $packages['id'] . '">' . number_format($packages['price'], 0, '.', ',') . '</span> <span id="currency_' . $packages['id'] . '">$</span>)</h3>
										<button class="right custom_button" id="buy_paycall_' . $packages['id'] . '" style="margin-top: 8px;" value="buy_paycall_' . $packages['id'] . '">' . __('Buy Now') . '</button>
									</li>
							</ul>';
                            }
                        } else{
                            echo '<div class="i_note">' . __('No PayCall Packages Found.') . '</div>';
                        }
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
	