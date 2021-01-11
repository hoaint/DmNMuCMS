<?php
    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
?>
<div id="content">
    <div id="box1">
        <div class="title1">
            <h1><?php echo __('Donate'); ?></h1>
        </div>
        <div class="box-style1" style="margin-bottom: 20px;">
            <h2 class="title"><?php echo __('Choose Donation Method'); ?></h2>

            <div class="entry">
                <?php
                    if($donation_config != false){
                        echo '<div style="margin: 5px auto; text-align:center;">';
                        if(isset($donation_config['paypal']) && $donation_config['paypal']['active'] == 1){
                            echo '<a href="' . $this->config->base_url . 'donate/paypal" class="custom_button">' . __('PayPal') . '</a>&nbsp;';
                        }
                        if(isset($donation_config['paymentwall']) && $donation_config['paymentwall']['active'] == 1){
                            echo '<a href="' . $this->config->base_url . 'donate/paymentwall" class="custom_button">' . __('PaymentWall') . '</a>&nbsp;';
                        }
                        if(isset($donation_config['fortumo']) && $donation_config['fortumo']['active'] == 1){
                            echo '<a href="' . $this->config->base_url . 'donate/fortumo" class="custom_button">' . __('Fortumo') . '</a>&nbsp;';
                        }
                        if(isset($donation_config['paygol']) && $donation_config['paygol']['active'] == 1){
                            echo '<a href="' . $this->config->base_url . 'donate/paygol" class="custom_button">' . __('PayGol') . '</a>&nbsp;';
                        }
                        if(isset($donation_config['2checkout']) && $donation_config['2checkout']['active'] == 1){
                            echo '<a href="' . $this->config->base_url . 'donate/two-checkout" class="custom_button">' . __('2CheckOut') . '</a>&nbsp;';
                        }
                        if(isset($donation_config['pagseguro']) && $donation_config['pagseguro']['active'] == 1){
                            echo '<a href="' . $this->config->base_url . 'donate/pagseguro" class="custom_button">' . __('PagSeguro') . '</a>&nbsp;';
                        }
                        if(isset($donation_config['superrewards']) && $donation_config['superrewards']['active'] == 1){
                            echo '<a href="' . $this->config->base_url . 'donate/superrewards" class="custom_button">' . __('SuperRewars') . '</a>&nbsp;';
                        }
                        if(isset($donation_config['paycall']) && $donation_config['paycall']['active'] == 1){
                            echo '<a href="' . $this->config->base_url . 'donate/paycall" class="custom_button">' . __('Paycall') . '</a>&nbsp;';
                        }
                        if(isset($donation_config['interkassa']) && $donation_config['interkassa']['active'] == 1){
                            echo '<a href="' . $this->config->base_url . 'donate/interkassa" class="custom_button">' . __('Interkassa') . '</a>&nbsp;';
                        }
                        if(isset($donation_config['cuenta_digital']) && $donation_config['cuenta_digital']['active'] == 1){
                            echo '<a href="' . $this->config->base_url . 'donate/cuenta-digital" class="custom_button">' . __('Cuenta Digital') . '</a>&nbsp;';
                        }
                        $plugins = $this->config->plugins();
                        $is_any = false;
                        if(!empty($plugins)):
                            foreach($plugins AS $plugin):
                                if($plugin['installed'] == 1 && $plugin['donation_panel_item'] == 1):
                                    $is_any = true;
                                    echo '<a href="' . $plugin['module_url'] . '" class="custom_button">' . $plugin['about']['name'] . '</a>&nbsp;';
                                endif;
                            endforeach;
                        endif;
                        if((!isset($donation_config['paypal']) || $donation_config['paypal']['active'] == 0) && (!isset($donation_config['paymentwall']) || $donation_config['paymentwall']['active'] == 0) && (!isset($donation_config['fortumo']) || $donation_config['fortumo']['active'] == 0) && (!isset($donation_config['paygol']) || $donation_config['paygol']['active'] == 0) && (!isset($donation_config['2checkout']) || $donation_config['2checkout']['active'] == 0) && (!isset($donation_config['pagseguro']) || $donation_config['pagseguro']['active'] == 0) && (!isset($donation_config['paycall']) || $donation_config['paycall']['active'] == 0) && (!isset($donation_config['interkassa']) || $donation_config['interkassa']['active'] == 0) && (!isset($donation_config['cuenta_digital']) || $donation_config['cuenta_digital']['active'] == 0) && (!isset($donation_config['superrewards']) || $donation_config['superrewards']['active'] == 0) && ($is_any == false)){
                            echo '<div class="e_note">' . __('No Donation Methods Found.') . '</div>';
                        }
                        echo '</div>';
                    } else{
                        echo '<div class="e_note">' . __('Donation modules not configured for this server.') . '</div>';
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
	