<?php
    in_file();

    class donate extends controller
    {
        public $vars = [];

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('website');
            $this->load->lib('session', ['DmNCMS']);
			 $this->load->lib('csrf');						 
            $this->load->helper('breadcrumbs', [$this->request]);
            $this->load->helper('meta');
            $this->load->model('donate');
        }

        public function index()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['donation_config'] = $this->config->values('donation_config', $this->session->userdata(['user' => 'server']));
                $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.method', $this->vars);
            } else{
                $this->login();
            }
        }

        public function paypal()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['donation_config'] = $this->config->values('donation_config', [$this->session->userdata(['user' => 'server']), 'paypal']);
                if($this->vars['donation_config'] != false && $this->vars['donation_config']['active'] == 1){
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->model('account');
                    if(!$this->Maccount->check_connect_stat())
                        $this->vars['error'] = __('Please logout from game.'); else{
                        $this->vars['paypal_packages'] = $this->Mdonate->get_paypal_packages();
                    }
                    $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.paypal', $this->vars);
                } else{
                    $this->disabled();
                }
            } else{
                $this->login();
            }
        }

        public function paypal_checkout($id)
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['donation_config'] = $this->config->values('donation_config', [$this->session->userdata(['user' => 'server']), 'paypal']);
                if($this->vars['donation_config'] != false && $this->vars['donation_config']['active'] == 1){
                    if(isset($this->vars['donation_config']['type']) && $this->vars['donation_config']['type'] == 2){
                        if($this->website->is_multiple_accounts() == true){
                            $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                        } else{
                            $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                        }
                        $this->load->model('account');
                        if(!$this->Maccount->check_connect_stat())
                            $this->vars['error'] = __('Please logout from game.'); else{
                            //$this->csrf->verifyToken('get', 'exception', 3600, false);
                            if($package_data = $this->Mdonate->get_paypal_package_data_by_id($id)){
                                if($this->Mdonate->insert_paypal_order($package_data['reward'], $package_data['price'], $package_data['currency'])){
                                    $order_data = $this->Mdonate->get_paypal_data();
                                    $this->load->lib('paypal_express', [$this->vars['donation_config']]);
                                    $tax = 0;
                                    if(isset($this->vars['donation_config']['paypal_fee']) && $this->vars['donation_config']['paypal_fee'] != ''){
                                        $tax = ($this->vars['donation_config']['paypal_fee'] / 100) * $package_data['price'];
                                    }
                                    if(isset($this->vars['donation_config']['paypal_fixed_fee']) && $this->vars['donation_config']['paypal_fixed_fee'] != ''){
                                        $tax += $this->vars['donation_config']['paypal_fixed_fee'];
                                    }
                                    $data = ['desc' => 'Purchase from ' . $this->config->config_entry('main|servername') . ' Store', 'currency' => $package_data['currency'], 'type' => 'Sale', 'return_URL' => $this->config->base_url . 'donate/paypal-complete', 'cancel_URL' => $this->config->base_url . 'donate/paypal', 'shipping_amount' => 0, 'tax_amount' => $tax, 'get_shipping' => false];
                                    $data['products'][] = ['name' => 'Buy Virtual Currency', 'desc' => 'Purchase ' . $package_data['reward'] . ' ' . $this->website->translate_credits($this->vars['donation_config']['reward_type'], $this->session->userdata(['user' => 'server'])), 'number' => $order_data['item'], 'quantity' => 1, 'amount' => $package_data['price']];
                                    $return = $this->paypal_express->setExpressCheckout($data);
                                    if(isset($return['ec_status']) && ($return['ec_status'] === true)){
                                        $this->paypal_express->redirectToPaypal($return['TOKEN']);
                                    } else{
                                        $this->paypal_error($return);
                                    }
                                } else{
                                    throw new Exception(__('Unable to checkout please try again.'));
                                }
                            } else{
                                throw new Exception(__('Paypal package not found.'));
                            }
                        }
                    } else{
                        throw new Exception(__('Express Chekout Api Has Been Disabled.'));
                    }
                } else{
                    $this->disabled();
                }
            } else{
                $this->login();
            }	 
        }

		public function paypal_complete()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['donation_config'] = $this->config->values('donation_config', [$this->session->userdata(['user' => 'server']), 'paypal']);
                if($this->vars['donation_config'] != false && $this->vars['donation_config']['active'] == 1){
                    if(isset($this->vars['donation_config']['type']) && $this->vars['donation_config']['type'] == 2){
                        if($this->website->is_multiple_accounts() == true){
                            $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                        } else{
                            $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                        }
                        $this->load->model('account');
                        if(!$this->Maccount->check_connect_stat())
                            $this->vars['error'] = __('Please logout from game.'); 
						else{
                            $this->load->lib('paypal_express', [$this->vars['donation_config']]);
                            $token = $_GET['token'];
                            $payer_id = $_GET['PayerID'];
                            $get_details = $this->paypal_express->getExpressCheckoutDetails($token);
                            if(isset($get_details['ec_status']) && ($get_details['ec_status'] === true)){
                                $details = ['token' => $token, 'payer_id' => $payer_id, 'currency' => $get_details['CURRENCYCODE'], 'amount' => $get_details['PAYMENTREQUEST_0_AMT'], 'IPN_URL' => $this->config->base_url . 'payments/paypal', 'type' => 'Sale'];
                                $do_payment = $this->paypal_express->doExpressCheckoutPayment($details);
                                if(isset($do_payment['ec_status']) && ($do_payment['ec_status'] === true)){
                                    if(isset($do_payment['L_ERRORCODE0'])){
                                        $this->vars['error'] = $do_payment['L_LONGMESSAGE0'];
                                    } else{
                                        if($this->Mdonate->check_order_number($get_details['L_PAYMENTREQUEST_0_NUMBER0'])){
                                            if($do_payment['PAYMENTINFO_0_AMT'] == number_format($this->Mdonate->order_details['amount'], 2, '.', ',')){
                                                if($do_payment['PAYMENTINFO_0_CURRENCYCODE'] == $this->Mdonate->order_details['currency']){
                                                    if('Completed' == $do_payment["PAYMENTINFO_0_PAYMENTSTATUS"]){
                                                        if($this->Mdonate->check_completed_transaction($do_payment["PAYMENTINFO_0_TRANSACTIONID"])){
                                                            $this->vars['error'] = 'Transaction already proccessed.';
                                                        } else{
                                                            if($this->Mdonate->check_pending_transaction($do_payment["PAYMENTINFO_0_TRANSACTIONID"])){
                                                                if($this->Mdonate->update_transaction_status(false, $do_payment["PAYMENTINFO_0_TRANSACTIONID"], $do_payment["PAYMENTINFO_0_PAYMENTSTATUS"])){
                                                                    $this->Mdonate->add_account_log('Reward ' . $this->website->translate_credits($this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paypal', 'reward_type']), $this->Mdonate->order_details['server']) . ' Paypal', $this->Mdonate->order_details['credits'], $this->Mdonate->order_details['account'], $this->Mdonate->order_details['server']);
                                                                    $this->Mdonate->reward_user($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->Mdonate->order_details['credits'], $this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paypal', 'reward_type']), $this->Mdonate->get_guid($this->Mdonate->order_details['account']));
                                                                    if($this->config->values('email_config', 'donate_email_user') == 1){
                                                                        $this->Mdonate->sent_donate_email($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->Mdonate->get_email($this->Mdonate->order_details['account']), $this->Mdonate->order_details['credits'], $this->website->translate_credits($this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paypal', 'reward_type']), $this->Mdonate->order_details['server']), $this->website->get_user_credits_balance($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paypal', 'reward_type']), $this->Mdonate->get_guid($this->Mdonate->order_details['account'])));
                                                                    }
                                                                    if($this->config->values('email_config', 'donate_email_admin') == 1){
                                                                        $this->Mdonate->sent_donate_email_admin($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->config->values('email_config', 'server_email'), $this->Mdonate->order_details['credits'], $this->website->translate_credits($this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paypal', 'reward_type']), $this->Mdonate->order_details['server']), $this->website->get_user_credits_balance($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paypal', 'reward_type']), $this->Mdonate->get_guid($this->Mdonate->order_details['account'])), 'PayPal');
                                                                    }
                                                                    $this->vars['success'] = 'Payment Received! Your game currency will be sent to you very soon!';
                                                                }
                                                            } else{
                                                                if($this->Mdonate->insert_transaction_status($do_payment["PAYMENTINFO_0_TRANSACTIONID"], $do_payment['PAYMENTINFO_0_AMT'], $do_payment['PAYMENTINFO_0_CURRENCYCODE'], $do_payment["PAYMENTINFO_0_PAYMENTSTATUS"], $get_details['EMAIL'], $get_details['L_PAYMENTREQUEST_0_NUMBER0'], get_country_code(ip()))){
                                                                    $this->Mdonate->add_account_log('Reward ' . $this->website->translate_credits($this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paypal', 'reward_type']), $this->Mdonate->order_details['server']) . ' Paypal', $this->Mdonate->order_details['credits'], $this->Mdonate->order_details['account'], $this->Mdonate->order_details['server']);
                                                                    $this->Mdonate->reward_user($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->Mdonate->order_details['credits'], $this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paypal', 'reward_type']), $this->Mdonate->get_guid($this->Mdonate->order_details['account']));
                                                                    if($this->config->values('email_config', 'donate_email_user') == 1){
                                                                        $this->Mdonate->sent_donate_email($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->Mdonate->get_email($this->Mdonate->order_details['account']), $this->Mdonate->order_details['credits'], $this->website->translate_credits($this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paypal', 'reward_type']), $this->Mdonate->order_details['server']), $this->website->get_user_credits_balance($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paypal', 'reward_type']), $this->Mdonate->get_guid($this->Mdonate->order_details['account'])));
                                                                    }
                                                                    if($this->config->values('email_config', 'donate_email_admin') == 1){
                                                                        $this->Mdonate->sent_donate_email_admin($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->config->values('email_config', 'server_email'), $this->Mdonate->order_details['credits'], $this->website->translate_credits($this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paypal', 'reward_type']), $this->Mdonate->order_details['server']), $this->website->get_user_credits_balance($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paypal', 'reward_type']), $this->Mdonate->get_guid($this->Mdonate->order_details['account'])), 'PayPal');
                                                                    }
                                                                    $this->vars['success'] = 'Payment Received! Your game currency will be sent to you very soon!';
                                                                }
                                                            }
                                                        }
                                                    } else if('Pending' == $do_payment["PAYMENTINFO_0_PAYMENTSTATUS"]){
                                                        if(!$this->Mdonate->check_completed_transaction($do_payment["PAYMENTINFO_0_TRANSACTIONID"]) && !$this->Mdonate->check_pending_transaction($do_payment["PAYMENTINFO_0_TRANSACTIONID"])){
                                                            $this->Mdonate->insert_transaction_status($do_payment["PAYMENTINFO_0_TRANSACTIONID"], $do_payment['PAYMENTINFO_0_AMT'], $do_payment['PAYMENTINFO_0_CURRENCYCODE'], $do_payment["PAYMENTINFO_0_PAYMENTSTATUS"], $get_details['EMAIL'], $get_details['L_PAYMENTREQUEST_0_NUMBER0'], get_country_code(ip()));
                                                        }
                                                        $this->vars['success'] = 'Transaction Complete, but payment is still pending!<br />You need to manually authorize this payment in your <a target="_new" href="http://www.paypal.com">Paypal Account</a>';
                                                    }
                                                } else{
                                                    $this->vars['error'] = 'Package currency does not match.';
                                                    $this->Mdonate->writelog('Package currency does not match [currency received: ' . $do_payment['PAYMENTINFO_0_CURRENCYCODE'] . '], [package currency: ' . $this->Mdonate->order_details['currency'] . ']', 'Paypal');
                                                }
                                            } else{
                                                $this->vars['error'] = 'Package price does not match.';
                                                $this->Mdonate->writelog('Package price does not match [price received: ' . $do_payment['PAYMENTINFO_0_AMT'] . '], [package price: ' . number_format($this->Mdonate->order_details['amount'], 2, '.', ',') . ']', 'Paypal');
                                            }
                                        } else{
                                            $this->vars['error'] = 'Order number not found.';
                                            $this->Mdonate->writelog('PayPal sent invalid order [transaction id: ' . $get_details['L_PAYMENTREQUEST_0_NUMBER0'] . ']', 'Paypal');
                                        }
                                    }
                                    $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.paypal_status', $this->vars);
                                } else{
                                    $this->paypal_error($do_payment);
                                }
                            } else{
                                $this->paypal_error($get_details);
                            }
                        }
                    } else{
                        throw new Exception(__('Express Chekout Api Has Been Disabled.'));
                    }
                } else{
                    $this->disabled();
                }
            } else{
                $this->login();
            }
        }

        private function paypal_error($data)
        {
            $data = "<div style=\"margin-left:20px;\">Error at Paypal Express Checkout<br />";
            $data .= "<pre>" . print_r($data, true) . "</pre>";
            $data .= $this->session->userdata('curl_error_msg') . "</div>";
            throw new Exception($data);
        }
		
        public function cuenta_digital($id = '')
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['donation_config'] = $this->config->values('donation_config', [$this->session->userdata(['user' => 'server']), 'cuenta_digital']);
                if($this->vars['donation_config'] != false && $this->vars['donation_config']['active'] == 1){
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->model('account');
                    if($id == ''){
                        $this->vars['cuenta_digital_packages'] = $this->Mdonate->get_cuenta_digital_packages();
                    } else{
                        if($this->vars['package'] = $this->Mdonate->get_cuenta_digital_package_data_by_id((int)$id)){
                            $refference = $this->session->userdata(['user' => 'username']) . '-server-' . $this->session->userdata(['user' => 'server']) . '-' . uniqid();
                            $md5 = md5($refference . $this->vars['package']['price'] . $this->vars['package']['currency'] . $this->vars['donation_config']['voucher_api_password']);
                            $this->Mdonate->insert_cuenta_digital_order($this->vars['package']['reward'], $this->vars['package']['price'], $this->vars['package']['currency'], $md5);
                            if($this->vars['donation_config']['api_type'] == 2){
                                $url = 'https://www.cuentadigital.com/apivoucher.php?id=' . $this->vars['donation_config']['account_id'];
                                $url .= '&comercio=' . urlencode($this->config->config_entry('main|servername'));
                                $url .= '&concepto=' . urlencode(sprintf(__('Purchase %d virtual game currency'), $this->vars['package']['reward']));
                                $url .= '&precio=' . $this->vars['package']['price'];
                                $url .= '&moneda=' . strtoupper($this->vars['package']['currency']);
                                $url .= '&codigo=' . $refference;
                                $url .= '&back=' . urlencode($this->config->base_url . 'payment/cuenta-digital');
                                $url .= '&cancel=' . urlencode($this->config->base_url . 'donate/cuenta-digital');
                            } else{
                                $url = 'https://www.cuentadigital.com/api.php?id=' . $this->vars['donation_config']['account_id'];
                                $url .= '&precio=' . $this->vars['package']['price'];
                                $url .= '&venc=7';
                                $url .= '&codigo=' . $refference;
                                $url .= '&hacia=' . $this->session->userdata(['user' => 'email']);
                                $url .= '&concepto=' . urlencode(sprintf(__('Purchase %d virtual game currency'), $this->vars['package']['reward']));
                                $url .= '&moneda=' . strtoupper($this->vars['package']['currency']);
                                $url .= '&site=' . urlencode($this->config->base_url);
                            }
                            header('Location: ' . $url);
                        } else{
                            $this->vars['error'] = $this->vars['error'] = __('CuentaDigital Package Not Found.');
                        }
                    }
                    $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.cuenta_digital', $this->vars);
                } else{
                    $this->disabled();
                }
            } else{
                $this->login();
            }
        }

        public function paycall()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['donation_config'] = $this->config->values('donation_config', [$this->session->userdata(['user' => 'server']), 'paycall']);
                if($this->vars['donation_config'] != false && $this->vars['donation_config']['active'] == 1){
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->model('account');
                    $this->vars['paycall_packages'] = $this->Mdonate->get_paycall_packages();
                    $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.paycall', $this->vars);
                } else{
                    $this->disabled();
                }
            } else{
                $this->login();
            }
        }

        public function paymentwall()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['donation_config'] = $this->config->values('donation_config', [$this->session->userdata(['user' => 'server']), 'paymentwall']);
                if($this->vars['donation_config'] != false && $this->vars['donation_config']['active'] == 1){
					$this->load->lib('paymentwall');
					$this->paymentwall->setup($this->vars['donation_config']['api_key'], $this->vars['donation_config']['secret_key']);
					$widget = new \Paymentwall_Widget(
						urlencode($this->session->userdata(['user' => 'username']).'-server-'.$this->session->userdata(['user' => 'server'])), // uid
						$this->vars['donation_config']['widget'], 
						[], 
						[
							'email' => $this->session->userdata(['user' => 'email']), 
							'history[registration_date]' => $this->session->userdata(['user' => 'joined']),
							'ps' => 'all',
							'customer[username]' => $this->session->userdata(['user' => 'username']),
							'customer[country]' => $this->session->userdata(['user' => 'country']),
							'sign_version' => $this->vars['donation_config']['sign_version']
						]
					);
					$this->vars['widget'] = $widget->getHtmlCode(['width' => $this->vars['donation_config']['width']]);
                    $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.pw', $this->vars);
                } else{
                    $this->disabled();
                }
            } else{
                $this->login();
            }
        }

        public function superrewards()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['donation_config'] = $this->config->values('donation_config', [$this->session->userdata(['user' => 'server']), 'superrewards']);
                if($this->vars['donation_config'] != false && $this->vars['donation_config']['active'] == 1){
                    $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.superrewards', $this->vars);
                } else{
                    $this->disabled();
                }
            } else{
                $this->login();
            }
        }

        public function fortumo()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['donation_config'] = $this->config->values('donation_config', [$this->session->userdata(['user' => 'server']), 'fortumo']);
                if($this->vars['donation_config'] != false && $this->vars['donation_config']['active'] == 1){
                    $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.fortumo', $this->vars);
                } else{
                    $this->disabled();
                }
            } else{
                $this->login();
            }
        }

        public function paygol()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['donation_config'] = $this->config->values('donation_config', [$this->session->userdata(['user' => 'server']), 'paygol']);
                if($this->vars['donation_config'] != false && $this->vars['donation_config']['active'] == 1){
                    $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.paygol', $this->vars);
                } else{
                    $this->disabled();
                }
            } else{
                $this->login();
            }
        }

        public function two_checkout($id = '')
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['donation_config'] = $this->config->values('donation_config', [$this->session->userdata(['user' => 'server']), '2checkout']);
                if($this->vars['donation_config'] != false && $this->vars['donation_config']['active'] == 1){
                    $this->vars['packages'] = $this->Mdonate->get_2checkout_packages();
                    if($id != ''){
                        if($this->vars['package'] = $this->Mdonate->check_2checkout_package((int)$id)){
                            $this->load->lib('two_checkout');
                            $this->two_checkout->setup($this->vars['donation_config']['seller_id'], $this->vars['donation_config']['private_key']);
                            $item = md5($this->session->userdata(['user' => 'username']) . uniqid(microtime(), 1));
                            $params = ['sid' => $this->vars['donation_config']['seller_id'], 'mode' => '2CO', 'li_0_type' => 'product', 'li_0_name' => $this->vars['package']['package'], 'li_0_quantity' => 1, 'li_0_price' => $this->vars['package']['price'], 'li_0_tangible' => 'N', 'li_0_description' => 'Description test', 'currency_code' => $this->vars['package']['currency'], 'x_receipt_link_url' => $this->config->base_url . 'payment/two-checkout', 'dmn_user' => $this->session->userdata(['user' => 'username']), 'dmn_server' => $this->session->userdata(['user' => 'server']), 'dmn_hash' => $item];
                            $this->Mdonate->insert_2checkout_order($this->vars['package']['reward'], $this->vars['package']['price'], $this->vars['package']['currency'], $item);
                            $this->two_checkout->redirect($params);
                        } else{
                            $this->vars['error'] = __('2Checkout Package Not Found.');
                        }
                    }
                    $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.2checkout', $this->vars);
                } else{
                    $this->disabled();
                }
            } else{
                $this->login();
            }
        }

        public function pagseguro($id = '')
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['donation_config'] = $this->config->values('donation_config', [$this->session->userdata(['user' => 'server']), 'pagseguro']);
                if($this->vars['donation_config'] != false && $this->vars['donation_config']['active'] == 1){
                    $this->vars['packages'] = $this->Mdonate->get_pagseguro_packages();
                    if($id != ''){
                        if($this->vars['package'] = $this->Mdonate->check_pagseguro_package((int)$id)){
                            require_once(APP_PATH . DS . 'libraries' . DS . 'PagSeguroLibrary' . DS . 'PagSeguroLibrary.php');
                            $paymentrequest = new PagSeguroPaymentRequest();
                            $paymentrequest->addItem((int)$id, $this->vars['package']['package'], 1, number_format($this->vars['package']['price'], 2));
                            $paymentrequest->setCurrency($this->vars['package']['currency']);
                            $paymentrequest->setShipping(3);
                            $paymentrequest->setRedirectURL($this->config->base_url . 'donate/pagseguro');
                            $paymentrequest->setNotificationURL($this->config->base_url . 'payment/pagseguro/' . $this->session->userdata(['user' => 'server']));
                            $paymentrequest->addParameter('userserver', $this->session->userdata(['user' => 'server']));
								
                            $credentials = new PagSeguroAccountCredentials($this->vars['donation_config']['email'], $this->vars['donation_config']['token']);
                            $item = md5($this->session->userdata(['user' => 'username']) . uniqid(microtime(), 1));
                            $this->Mdonate->insert_pagseguro_order($this->vars['package']['reward'], $this->vars['package']['price'], $this->vars['package']['currency'], $item);
                            $paymentrequest->setReference($item);
                            header('location: ' . $paymentrequest->register($credentials)); 
                        } else{
                            $this->vars['error'] = __('PagSeguro Package Not Found.');
                        }
                    }
                    $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.pagseguro', $this->vars);
                } else{
                    $this->disabled();
                }
            } else{
                $this->login();
            }
        }

        public function interkassa($id = '')
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['donation_config'] = $this->config->values('donation_config', [$this->session->userdata(['user' => 'server']), 'interkassa']);
                if($this->vars['donation_config'] != false && $this->vars['donation_config']['active'] == 1){
                    if($id == ''){
                        $this->vars['interkassa_packages'] = $this->Mdonate->get_interkassa_packages();
                        $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.interkassa', $this->vars);
                    } else{
                        if($package_data = $this->Mdonate->get_interkassa_package_data_by_id($id)){
                            if($this->Mdonate->insert_interkassa_order($package_data['reward'], $package_data['price'], $package_data['currency'])){
                                $this->load->lib('interkassa');
                                $shop = Interkassa_Shop::factory(['id' => $this->vars['donation_config']['shop_id'], 'secret_key' => $this->vars['donation_config']['secret_key']]);
                                $this->vars['desc'] = vsprintf(__('Purchase %d %s for %s %s'), [$package_data['reward'], $this->website->translate_credits($this->vars['donation_config']['reward_type'], $this->session->userdata(['user' => 'server'])), $package_data['price'], $package_data['currency']]);
                                $this->vars['payment'] = $shop->createPayment(['id' => $this->Mdonate->hash_item, 'amount' => $package_data['price'], 'description' => $this->vars['desc'], 'status_url' => $this->config->base_url . 'payment/interkassa', 'success_url' => $this->config->base_url . 'donate/interkassa-success', 'fail_url' => $this->config->base_url . 'donate/interkassa-fail']);
                                $this->vars['payment']->setCurrency($package_data['currency']);
                            } else
                                $this->vars['error'] = __('Unable to checkout please try again.');
                        } else{
                            $this->vars['error'] = __('Interkassa package not found.');
                        }
                        $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.interkassa_checkout', $this->vars);
                    }
                } else{
                    $this->disabled();
                }
            } else{
                $this->login();
            }
        }

        public function interkassa_success()
        {
            $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.interkassa_success', $this->vars);
        }

        public function interkassa_fail()
        {
            $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.interkassa_fail', $this->vars);
        }

        public function method_1()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.method_1');
            } else{
                $this->login();
            }
        }

        public function method_2()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.method_2');
            } else{
                $this->login();
            }
        }

        public function method_3()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.method_3');
            } else{
                $this->login();
            }
        }

        public function method_4()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.method_4');
            } else{
                $this->login();
            }
        }

		public function other()
        {
			$this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.other');
        }
		
        public function bank()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if(isset($_POST['add_ref_number'])){
                    $bank = (isset($_POST['bank']) && ctype_digit($_POST['bank'])) ? $_POST['bank'] : '';
                    $ref_number = isset($_POST['ref_number']) ? $_POST['ref_number'] : '';
                    if($bank == '')
                        $this->vars['error'] = __('Please select valid bank.'); else{
                        if($ref_number == '')
                            $this->vars['error'] = __('Please enter refference number.'); else{
                            $bank_data = $this->Mdonate->get_bank_data($bank, $ref_number);
                            if($bank_data != false){
                                if($bank_data['is_assigned'] == 0){
                                    $account = ($bank_data['account'] != null) ? $bank_data['account'] : $this->session->userdata(['user' => 'username']);
                                    $server = ($bank_data['server'] != null) ? $bank_data['server'] : $this->session->userdata(['user' => 'server']);
                                    if($bank_data['reward'] != null && $bank_data['reward_type']){
                                        $this->Mdonate->assign_refference_number($bank_data['id'], $account, $server);
                                        $this->Mdonate->reward_user($account, $server, $bank_data['reward'], $bank_data['reward_type']);
                                        $this->vars['success'] = __('Thank You, your request was successfull.');
                                    } else{
                                        $this->vars['error'] = __('Your request is still being reviewed.');
                                    }
                                } else{
                                    $this->vars['error'] = __('Refference number was already registered.');
                                }
                            } else{
                                $this->Mdonate->insert_refference_number($bank, $ref_number);
                                $this->vars['success'] = __('Your request will be reviewed and approved manually.');
                            }
                        }
                    }
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'donate' . DS . 'view.bank', $this->vars);
            } else{
                $this->login();
            }
        }

        public function login()
        {
            $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.login');
        }

        public function disabled()
        {
            $this->load->view($this->config->config_entry('main|template') . DS . 'view.module_disabled');
        }
    }