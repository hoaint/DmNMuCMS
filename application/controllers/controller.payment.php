<?php
    in_file();

    class payment extends controller
    {
        public $vars = [];

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('website');
            $this->load->lib('session', ['DmNCMS']);
            $this->load->model('donate');
        }

        public function index()
        {
            throw new exception('Nothing to see here!');
        }

        public function paypal()
        {
            $this->Mdonate->writelog('Paypal request initialized.', 'Paypal');
            if(count($_POST) > 0){
                $post_data = $this->website->c(file_get_contents('php://input'));
                $this->Mdonate->set_ipn_listeners($_POST['custom']);
                $this->Mdonate->gen_post_fields($post_data);
                if(function_exists('curl_init')){
                    if($this->Mdonate->post_back_paypal()){
                        foreach($_POST as $key => $value){
                            $this->Mdonate->$key = trim($value);
                        }
                        if($this->Mdonate->validate_paypal_payment()){
                            if($this->website->is_multiple_accounts() == true){
                                $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->Mdonate->order_details['server'], true)]);
                            } else{
                                $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                            }
                            $this->Mdonate->add_account_log('Reward ' . $this->website->translate_credits($this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paypal', 'reward_type']), $this->Mdonate->order_details['server']) . ' Paypal', $this->Mdonate->order_details['credits'], $this->Mdonate->order_details['account'], $this->Mdonate->order_details['server']);
                            $this->Mdonate->reward_user($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->Mdonate->order_details['credits'], $this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paypal', 'reward_type']), $this->Mdonate->get_guid($this->Mdonate->order_details['account']));
                            if($this->config->values('email_config', 'donate_email_user') == 1){
                                $this->Mdonate->sent_donate_email($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->Mdonate->get_email($this->Mdonate->order_details['account']), $this->Mdonate->order_details['credits'], $this->website->translate_credits($this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paypal', 'reward_type']), $this->Mdonate->order_details['server']), $this->website->get_user_credits_balance($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paypal', 'reward_type']), $this->Mdonate->get_guid($this->Mdonate->order_details['account'])));
                            }
                            if($this->config->values('email_config', 'donate_email_admin') == 1){
                                $this->Mdonate->sent_donate_email_admin($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->config->values('email_config', 'server_email'), $this->Mdonate->order_details['credits'], $this->website->translate_credits($this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paypal', 'reward_type']), $this->Mdonate->order_details['server']), $this->website->get_user_credits_balance($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paypal', 'reward_type']), $this->Mdonate->get_guid($this->Mdonate->order_details['account'])), 'PayPal');
                            }
                        }																								
					} else{
                        $this->Mdonate->writelog('Unable to proccess payment curl request to paypal failed.', 'Paypal');
                    }
                } else{
                    $this->Mdonate->writelog('Unable to proccess payment php curl extension not found.', 'Paypal');
                }
            } else{
                $this->Mdonate->writelog('No $_POST data returned', 'Paypal');
            }
        }

        public function paycall()
        {
            if(count($_REQUEST) > 0){
                $custom = isset($_REQUEST["custom_data"]) ? trim(($_REQUEST["custom_data"])) : '';
                $total_amount = isset($_REQUEST["total"]) ? $_REQUEST["total"] : 0;
                $paycall_unique = isset($_REQUEST["paycall_unique"]) ? trim($_REQUEST['paycall_unique']) : 0;
                $business_code = isset($_REQUEST["business_code"]) ? $_REQUEST['business_code'] : 0;
                $this->Mdonate->check_paycall_order($custom);
                if($this->Mdonate->order_details != false){
                    if($business_code == $this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paycall', 'business_code'])){
                        if(!$this->Mdonate->verifiedTransaction($paycall_unique, $this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paycall', 'business_code']), $this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paycall', 'sandbox']))){
                            $this->Mdonate->writelog('Error - Transaction [' . $paycall_unique . '] is not verified !', 'Paycall');
                        } else{
                            if($this->Mdonate->validate_paycall_payment($paycall_unique, $custom, $total_amount)){
                                if($this->website->is_multiple_accounts() == true){
                                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->Mdonate->order_details['server'], true)]);
                                } else{
                                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                                }
                                $this->Mdonate->add_account_log('Reward ' . $this->website->translate_credits($this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paycall', 'reward_type']), $this->Mdonate->order_details['server']) . ' Paycall', $this->Mdonate->order_details['credits'], $this->Mdonate->order_details['account'], $this->Mdonate->order_details['server']);
                                $this->Mdonate->reward_user($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->Mdonate->order_details['credits'], $this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paycall', 'reward_type']), $this->Mdonate->get_guid($this->Mdonate->order_details['account']));
                                if($this->config->values('email_config', 'donate_email_user') == 1){
                                    $this->Mdonate->sent_donate_email($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->Mdonate->get_email($this->Mdonate->order_details['account']), $this->Mdonate->order_details['credits'], $this->website->translate_credits($this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paycall', 'reward_type']), $this->Mdonate->order_details['server']), $this->website->get_user_credits_balance($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paycall', 'reward_type']), $this->Mdonate->get_guid($this->Mdonate->order_details['account'])));
                                }
                                if($this->config->values('email_config', 'donate_email_admin') == 1){
                                    $this->Mdonate->sent_donate_email_admin($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->config->values('email_config', 'server_email'), $this->Mdonate->order_details['credits'], $this->website->translate_credits($this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paycall', 'reward_type']), $this->Mdonate->order_details['server']), $this->website->get_user_credits_balance($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->config->values('donation_config', [$this->Mdonate->order_details['server'], 'paycall', 'reward_type']), $this->Mdonate->get_guid($this->Mdonate->order_details['account'])), 'PayCall');
                                }
                            }
                        }
                    } else{
                        $this->Mdonate->writelog('Invalid business code: ' . $business_code, 'Paycall');
                    }
                } else{
                    $this->Mdonate->writelog('Order not found: ' . $custom, 'Paycall');
                }
            } else{
                $this->Mdonate->writelog('No $_REQUEST data returned', 'Paycall');
            }
        }

        public function paymentwall()
        {
            if(count($_GET) > 0){
                if(!isset($_GET['uid'])){
                    $this->Mdonate->writelog('Error: Uid is not set. Correct format username-server-servername', 'paymentwall');
                    echo 'Uid is not set. Correct format username-server-servername';
                } else{
                    if(preg_match('/\b-server-\b/i', $_GET['uid'])){
                            if(isset($_GET['uid']) && $_GET['uid'] != ''){
                                $acc_serv = explode('-server-', $_GET['uid']);
								
								$this->vars['donation_config'] = $this->config->values('donation_config', [$acc_serv[1], 'paymentwall']);
								
								$this->load->lib('paymentwall');
								$this->paymentwall->setup($this->vars['donation_config']['api_key'], $this->vars['donation_config']['secret_key']);
								
								$pingback = new \Paymentwall_Pingback($_GET, ip());
								
								if($pingback->validate()){
									$virtualCurrency = $pingback->getVirtualCurrencyAmount();
									$order_id = $pingback->getReferenceId();
									
									if($pingback->isDeliverable()){
										if($this->website->is_multiple_accounts() == true){
											$this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($acc_serv[1], true)]);
										} else{
											$this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
										}
										
										if(!$this->Mdonate->check_reference($acc_serv[0], $acc_serv[1], $_GET['ref'])){
											$guid = $this->Mdonate->get_guid($acc_serv[0]);
											$reward_type = $this->website->translate_credits($this->vars['donation_config']['reward_type'], $acc_serv[1]);
											$this->Mdonate->log_pw_transaction($acc_serv[0], $acc_serv[1], $_GET['currency'], $_GET['type'], $_GET['ref']);
											$this->Mdonate->add_account_log('Reward ' .$reward_type . ' Paymentwall', $_GET['currency'], $acc_serv[0], $acc_serv[1]);
											$this->Mdonate->reward_user($acc_serv[0], $acc_serv[1], $_GET['currency'], $this->vars['donation_config']['reward_type'], $guid);
											$balance = $this->website->get_user_credits_balance($acc_serv[0], $acc_serv[1], $this->vars['donation_config']['reward_type'], $guid);
											if($this->config->values('email_config', 'donate_email_user') == 1){
												$this->Mdonate->sent_donate_email($acc_serv[0], $acc_serv[1], $this->Mdonate->get_email($acc_serv[0]), $_GET['currency'], $reward_type, $balance);
											}
											if($this->config->values('email_config', 'donate_email_admin') == 1){
												$this->Mdonate->sent_donate_email_admin($acc_serv[0], $acc_serv[1], $this->config->values('email_config', 'server_email'), $_GET['currency'], $reward_type, $balance, 'PaymentWall');
											}
											
											$delivery = new \Paymentwall_GenerericApiObject('delivery');

											$response = $delivery->post(array(
												'payment_id' => $_GET['ref'],
												'merchant_reference_id' => md5($_GET['ref']),
												'type' => 'digital',
												'status' => 'delivered',
												'estimated_delivery_datetime' => date('Y/m/d H:i:s O', time()),
												'estimated_update_datetime' => date('Y/m/d H:i:s O', time()),
												'refundable' => true,
												'details' => 'Virtual currency was credited into customer account',
												'shipping_address[email]' => $this->Mdonate->get_email($acc_serv[0]),
												'reason' => 'none',
												'attachments[0]' => null
											));
											if(isset($response['success'])){
												 echo 'OK';
											} 
											elseif(isset($response['error'])){
												$this->Mdonate->writelog('Error: ' . print_r($response['error'], true) . ', notice: '.print_r($response['notices'], true).'', 'paymentwall');
												//var_dump($response['error'], $response['notices']);
												echo 'OK';
											}
										}
										else{
											$this->Mdonate->writelog('Error: payment: ' . htmlspecialchars($_GET['ref']) . ' already proccessed', 'paymentwall');
											 echo 'OK';
										}	
									}
									elseif($pingback->isCancelable()){
										$this->Mdonate->change_pw_transaction_status($_GET['currency'], $_GET['reason'], $acc_serv[0], $_GET['ref']);
										if($_GET['reason'] == 2 || $_GET['reason'] == 3){
											$this->Mdonate->block_user($acc_serv[0], $acc_serv[1]);
										}
										$this->Mdonate->decrease_credits($acc_serv[0], $acc_serv[1], $_GET['currency'], $this->vars['donation_config']['reward_type']);
										$this->Mdonate->add_account_log('Decrease ' . $this->website->translate_credits($this->vars['donation_config']['reward_type'], $acc_serv[1]) . ' Paymentwall', $_GET['currency'], $acc_serv[0], $acc_serv[1]);
										echo 'OK';
									} 
									elseif($pingback->isUnderReview()) {
									// set "pending" status to order
									}
								}
								else{
									$this->Mdonate->writelog($pingback->getErrorSummary(), 'paymentwall');
									echo $pingback->getErrorSummary();
								}
                            } else{
                                $this->Mdonate->writelog('Error: Missing uid', 'paymentwall');
                                echo 'Error: Missing uid';
                            }
                        
                    } else{
                        $this->Mdonate->writelog('Error: invalid uid ' . htmlspecialchars($_GET['uid']) . '. Correct format username-server-servername', 'paymentwall');
                        echo 'Invalid uid ' . htmlspecialchars($_GET['uid']) . '. Correct format username-server-servername';
                    }																										 
                }
            }
        }

        public function superrewards()
        {
            if(count($_REQUEST) > 0){
                if(preg_match('/\b-server-\b/i', $_REQUEST['uid'])){
                    if($this->Mdonate->validate_ip_list($_REQUEST['uid'], 'superrewards')){
                        if(isset($_REQUEST['uid']) && $_REQUEST['uid'] != ''){
                            $_REQUEST['acc_server'] = $_REQUEST['uid'];
                            $acc_serv = explode('-server-', $_REQUEST['uid']);
                            $_REQUEST['uid'] = $acc_serv[0];
                            $_REQUEST['server'] = $acc_serv[1];
                            if($this->website->is_multiple_accounts() == true){
                                $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($_REQUEST['server'], true)]);
                            } else{
                                $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                            }
                            foreach($_REQUEST as $key => $value){
                                $this->Mdonate->$key = trim($value);
                            }
                            if($this->Mdonate->validate_superrewards_signature()){
                                if($this->Mdonate->validate_superrewards_payment()){
                                    $this->Mdonate->add_account_log('Reward ' . $this->website->translate_credits($this->config->values('donation_config', [$this->Mdonate->vars['server'], 'superrewards', 'reward_type']), $this->Mdonate->vars['server']) . ' Superrewards', $this->Mdonate->vars['new'], $this->Mdonate->vars['uid'], $this->Mdonate->vars['server']);
                                    $this->Mdonate->reward_user($this->Mdonate->vars['uid'], $this->Mdonate->vars['server'], $this->Mdonate->vars['new'], $this->config->values('donation_config', [$this->Mdonate->vars['server'], 'superrewards', 'reward_type']), $this->Mdonate->get_guid($this->Mdonate->vars['uid']));
                                    if($this->config->values('email_config', 'donate_email_user') == 1){
                                        $this->Mdonate->sent_donate_email($this->Mdonate->vars['uid'], $this->Mdonate->vars['server'], $this->Mdonate->get_email($this->Mdonate->vars['uid']), $this->Mdonate->vars['new'], $this->website->translate_credits($this->config->values('donation_config', [$this->Mdonate->vars['server'], 'superrewards', 'reward_type']), $this->Mdonate->vars['server']), $this->website->get_user_credits_balance($this->Mdonate->vars['uid'], $this->Mdonate->vars['server'], $this->config->values('donation_config', [$this->Mdonate->vars['server'], 'superrewards', 'reward_type']), $this->Mdonate->get_guid($this->Mdonate->vars['uid'])));
                                    }
                                    if($this->config->values('email_config', 'donate_email_admin') == 1){
                                        $this->Mdonate->sent_donate_email_admin($this->Mdonate->vars['uid'], $this->Mdonate->vars['server'], $this->config->values('email_config', 'server_email'), $this->Mdonate->vars['new'], $this->website->translate_credits($this->config->values('donation_config', [$this->Mdonate->vars['server'], 'superrewards', 'reward_type']), $this->Mdonate->vars['server']), $this->website->get_user_credits_balance($this->Mdonate->vars['uid'], $this->Mdonate->vars['server'], $this->config->values('donation_config', [$this->Mdonate->vars['server'], 'superrewards', 'reward_type']), $this->Mdonate->get_guid($this->Mdonate->vars['uid'])), 'SuperRewards');
                                    }
                                    echo 1;
                                } else{
                                    echo 0;
                                }
                            } else{
                                $this->Mdonate->writelog('Error: Invalid superrewards signature', 'superrewards');
                                echo 'Error: Invalid superewards signature';
                            }
                        } else{
                            $this->Mdonate->writelog('Error: Missing uid', 'superewards');
                            echo 'Error: Missing uid';
                        }
                    } else{
                        writelog('Error: Unknown IP', 'superewards');
                        echo 'Unknown IP';
                    }
                } else{
                    $this->Mdonate->writelog('Error: invalid uid ' . $_REQUEST['uid'] . '. Correct format username-server-servername', 'superewards');
                    echo 'Invalid uid ' . $_REQUEST['uid'] . '. Correct format username-server-servername';
                }
            }
        }

        public function two_checkout()
        {
            $params = [];
            foreach($_REQUEST as $k => $v){
                $params[$k] = $v;
            }
            $this->load->lib('two_checkout');
            $this->two_checkout->setup($this->config->values('donation_config', [$params['dmn_server'], '2checkout', 'seller_id']), $this->config->values('donation_config', [$params['dmn_server'], '2checkout', 'private_key']));
            $passback = $this->two_checkout->check($params, $this->config->values('donation_config', [$params['dmn_server'], '2checkout', 'private_secret_word']));
            if($passback['response_code'] == 'Success'){
                if($this->vars['order_data'] = $this->Mdonate->get_2checkout_order_data($params['dmn_hash'])){
                    if($this->Mdonate->check_existing_2checkout_transaction($params['order_number'])){
                        echo 'Transaction already processed: ' . $params['order_number'];
                        $this->Mdonate->writelog('Error: Transaction already processed: ' . $params['order_number'], '2checkout');
                    } else{
                        if($this->website->is_multiple_accounts() == true){
                            $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->vars['order_data']['server'], true)]);
                        } else{
                            $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                        }
                        $this->Mdonate->insert_2checkout_transaction($params['order_number'], $this->vars['order_data']['amount'], $this->vars['order_data']['currency'], $this->vars['order_data']['account'], $this->vars['order_data']['server'], $this->vars['order_data']['credits'], $params['email'], $params['dmn_hash']);
                        $this->Mdonate->add_account_log('Reward ' . $this->website->translate_credits($this->config->values('donation_config', [$params['dmn_server'], '2checkout', 'reward_type']), $this->vars['order_data']['server']) . ' 2CheckOut', $this->vars['order_data']['credits'], $this->vars['order_data']['account'], $this->vars['order_data']['server']);
                        $this->Mdonate->reward_user($this->vars['order_data']['account'], $this->vars['order_data']['server'], $this->vars['order_data']['credits'], $this->config->values('donation_config', [$params['dmn_server'], '2checkout', 'reward_type']), $this->Mdonate->get_guid($this->vars['order_data']['account']));
                        if($this->config->values('email_config', 'donate_email_user') == 1){
                            $this->Mdonate->sent_donate_email($this->vars['order_data']['account'], $this->vars['order_data']['server'], $this->Mdonate->get_email($this->vars['order_data']['account']), $this->vars['order_data']['credits'], $this->website->translate_credits($this->config->values('donation_config', [$this->vars['order_data']['server'], '2checkout', 'reward_type']), $this->vars['order_data']['server']), $this->website->get_user_credits_balance($this->vars['order_data']['account'], $this->vars['order_data']['server'], $this->config->values('donation_config', [$this->vars['order_data']['server'], '2checkout', 'reward_type']), $this->Mdonate->get_guid($this->vars['order_data']['account'])));
                        }
                        if($this->config->values('email_config', 'donate_email_admin') == 1){
                            $this->Mdonate->sent_donate_email_admin($this->vars['order_data']['account'], $this->vars['order_data']['server'], $this->config->values('email_config', 'server_email'), $this->vars['order_data']['credits'], $this->website->translate_credits($this->config->values('donation_config', [$this->vars['order_data']['server'], '2checkout', 'reward_type']), $this->vars['order_data']['server']), $this->website->get_user_credits_balance($this->vars['order_data']['account'], $this->vars['order_data']['server'], $this->config->values('donation_config', [$this->vars['order_data']['server'], '2checkout', 'reward_type']), $this->Mdonate->get_guid($this->vars['order_data']['account'])), '2CheckOut');
                        }
                        header('Location: ' . $this->config->base_url . 'account-panel/logs');
                    }
                } else{
                    echo 'Unable to find order data.';
                    $this->Mdonate->writelog('Error: Unable to find order data.', '2checkout');
                }
            } else{
                echo 'Error: ' . $passback['response_message'];
                $this->Mdonate->writelog('Error: ' . $passback['response_message'] . '', '2checkout');
            }
        }

        public function cuenta_digital()
        {
            if(count($_REQUEST) > 0){
                $code = isset($_REQUEST['codigo']) ? $_REQUEST['codigo'] : '';
                if($code != ''){
                    $this->vars['order_data'] = $this->Mdonate->get_cuenta_digital_order_data($code);
                    if($this->vars['order_data'] != false){
                        if($this->Mdonate->check_existing_cuenta_digital_transaction($code)){
                            echo 'Transaction already processed: ' . $code;
                            $this->Mdonate->writelog('Transaction already processed: ' . $code, 'CuentaDigital');
                        } else{
                            if($this->website->is_multiple_accounts() == true){
                                $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->vars['order_data']['server'], true)]);
                            } else{
                                $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                            }
                            $this->Mdonate->insert_cuenta_digital_transaction($this->vars['order_data']['amount'], $this->vars['order_data']['currency'], $this->vars['order_data']['account'], $this->vars['order_data']['server'], $this->vars['order_data']['credits'], $code);
                            $this->Mdonate->add_account_log('Reward ' . $this->website->translate_credits($this->config->values('donation_config', [$this->vars['order_data']['server'], 'cuenta_digital', 'reward_type']), $this->vars['order_data']['server']) . ' CuentaDigital', $this->vars['order_data']['credits'], $this->vars['order_data']['account'], $this->vars['order_data']['server']);
                            $this->Mdonate->reward_user($this->vars['order_data']['account'], $this->vars['order_data']['server'], $this->vars['order_data']['credits'], $this->config->values('donation_config', [$this->vars['order_data']['server'], 'cuenta_digital', 'reward_type']), $this->Mdonate->get_guid($this->vars['order_data']['account']));
                            if($this->config->values('email_config', 'donate_email_user') == 1){
                                $this->Mdonate->sent_donate_email($this->vars['order_data']['account'], $this->vars['order_data']['server'], $this->Mdonate->get_email($this->vars['order_data']['account']), $this->vars['order_data']['credits'], $this->website->translate_credits($this->config->values('donation_config', [$this->vars['order_data']['server'], 'cuenta_digital', 'reward_type']), $this->vars['order_data']['server']), $this->website->get_user_credits_balance($this->vars['order_data']['account'], $this->vars['order_data']['server'], $this->config->values('donation_config', [$this->vars['order_data']['server'], 'cuenta_digital', 'reward_type']), $this->Mdonate->get_guid($this->vars['order_data']['account'])));
                            }
                            if($this->config->values('email_config', 'donate_email_admin') == 1){
                                $this->Mdonate->sent_donate_email_admin($this->vars['order_data']['account'], $this->vars['order_data']['server'], $this->config->values('email_config', 'server_email'), $this->vars['order_data']['credits'], $this->website->translate_credits($this->config->values('donation_config', [$this->vars['order_data']['server'], 'cuenta_digital', 'reward_type']), $this->vars['order_data']['server']), $this->website->get_user_credits_balance($this->vars['order_data']['account'], $this->vars['order_data']['server'], $this->config->values('donation_config', [$this->vars['order_data']['server'], 'cuenta_digital', 'reward_type']), $this->Mdonate->get_guid($this->vars['order_data']['account'])), 'CuentaDigital');
                            }
                            echo 'OK';
                        }
                    } else{
                        echo 'Order not found - ' . htmlspecialchars($code);
                        $this->Mdonate->writelog('Order not found - ' . htmlspecialchars($code), 'CuentaDigital');
                    }
                } else{
                    echo '$_REQUEST[\'codigo\'] was empty';
                    $this->Mdonate->writelog('$_REQUEST[\'codigo\'] was empty', 'CuentaDigital');
                }
            } else{
                echo 'No $_REQUEST data returned';
                $this->Mdonate->writelog('No $_REQUEST data returned', 'CuentaDigital');
            }
        }

        public function interkassa()
        {
            if(count($_POST) > 0){
                if(isset($_POST['ik_x_userinfo'])){
                    if(preg_match('/\b-server-\b/i', $_POST['ik_x_userinfo'])){
                        $userinfo = explode('-server-', $_POST['ik_x_userinfo']);
                        $this->vars['donation_config'] = $this->config->values('donation_config', [$userinfo[1], 'interkassa']);
                        if($this->vars['donation_config'] != false && $this->vars['donation_config']['active'] == 1){
                            $this->load->lib('interkassa');
                            $shop = Interkassa_Shop::factory(['id' => $this->vars['donation_config']['shop_id'], 'secret_key' => $this->vars['donation_config']['secret_key']]);
                            try{
                                $status = $shop->receiveStatus($_POST);
                            } catch(Interkassa_Exception $e){
                                $this->Mdonate->writelog($e->getMessage(), 'Interkassa');
                                header('HTTP/1.0 400 Bad Request');
                                exit;
                            }
                            $payment = $status->getPayment();
                            if($status->getState() == 'success'){
                                if($this->Mdonate->check_interkassa_order_number($payment->getId())){
                                    if($payment->getAmount() != $this->Mdonate->order_details['amount']){
                                        $this->Mdonate->writelog('Wrong order amount: ' . $payment->getAmount() . ' not found.', 'Interkassa');
                                    } else{
                                        if($this->Mdonate->check_completed_interkassa_transaction($payment->getId())){
                                            $this->Mdonate->writelog('Transaction already proccessed: ' . $payment->getId(), 'Interkassa');
                                            header('HTTP/1.0 400 Bad Request');
                                            exit;
                                        } else{
                                            $this->Mdonate->insert_interkassa_transaction($payment->getId(), $payment->getAmount());
                                            if($this->website->is_multiple_accounts() == true){
                                                $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->Mdonate->order_details['server'], true)]);
                                            } else{
                                                $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                                            }
                                            $this->Mdonate->add_account_log('Reward ' . $this->website->translate_credits($this->vars['donation_config']['reward_type'], $this->Mdonate->order_details['server']) . ' Interkassa', $this->Mdonate->order_details['credits'], $this->Mdonate->order_details['account'], $this->Mdonate->order_details['server']);
                                            $this->Mdonate->reward_user($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->Mdonate->order_details['credits'], $this->vars['donation_config']['reward_type'], $this->Mdonate->get_guid($this->Mdonate->order_details['account']));
                                            if($this->config->values('email_config', 'donate_email_user') == 1){
                                                $this->Mdonate->sent_donate_email($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->Mdonate->get_email($this->Mdonate->order_details['account']), $this->Mdonate->order_details['credits'], $this->website->translate_credits($this->vars['donation_config']['reward_type'], $this->Mdonate->order_details['server']), $this->website->get_user_credits_balance($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->vars['donation_config']['reward_type'], $this->Mdonate->get_guid($this->Mdonate->order_details['account'])));
                                            }
                                            if($this->config->values('email_config', 'donate_email_admin') == 1){
                                                $this->Mdonate->sent_donate_email_admin($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->config->values('email_config', 'server_email'), $this->Mdonate->order_details['credits'], $this->website->translate_credits($this->vars['donation_config']['reward_type'], $this->Mdonate->order_details['server']), $this->website->get_user_credits_balance($this->Mdonate->order_details['account'], $this->Mdonate->order_details['server'], $this->vars['donation_config']['reward_type'], $this->Mdonate->get_guid($this->Mdonate->order_details['account'])), 'Interkassa');
                                            }
                                            header('HTTP/1.0 200 OK');
                                        }
                                    }
                                } else{
                                    $this->Mdonate->writelog('Order with id: ' . $payment->getId() . ' not found.', 'Interkassa');
                                    header('HTTP/1.0 400 Bad Request');
                                    exit;
                                }
                            } else{
                                $this->Mdonate->writelog('Wrong payment state: ' . $status->getState(), 'Interkassa');
                                header('HTTP/1.0 400 Bad Request');
                                exit;
                            }
                        } else{
                            $this->Mdonate->writelog('Payment system not configured or disabled', 'Interkassa');
                            header('HTTP/1.0 400 Bad Request');
                            exit;
                        }
                    } else{
                        $this->Mdonate->writelog('Parameter $_POST[\'ik_x_userinfo\'] is formated wrongly', 'Interkassa');
                        header('HTTP/1.0 400 Bad Request');
                        exit;
                    }
                } else{
                    $this->Mdonate->writelog('Missing $_POST[\'ik_x_userinfo\'] parameter', 'Interkassa');
                    header('HTTP/1.0 400 Bad Request');
                    exit;
                }
            } else{
                $this->Mdonate->writelog('No $_POST data returned', 'Interkassa');
                header('HTTP/1.0 400 Bad Request');
                exit;
            }
        }

        public function pagseguro($server = '')
        {
            if($server == ''){
                echo 'Server variable is not set';
                $this->Mdonate->writelog('Server variable is not set', 'pagseguro_log');
            } else{
                //$server_list = $this->website->server_list();
                if(array_key_exists($server, $this->website->server_list())){
                    //header("access-control-allow-origin: https://sandbox.pagseguro.uol.com.br");
                    //$this->Mdonate->writelog(print_r($_POST, true), 'pagseguro_log');
                    $notificationType = (isset($_POST['notificationType']) && trim($_POST['notificationType']) !== "" ? trim($_POST['notificationType']) : null);
                    $notificationCode = (isset($_POST['notificationCode']) && trim($_POST['notificationCode']) !== "" ? trim($_POST['notificationCode']) : null);
                    //$refference = (isset($_POST['Referencia']) && trim($_POST['Referencia']) !== "" ? trim($_POST['Referencia']) : NULL);
                    //if($refference != null){
                    //	$this->vars['order_data'] = $this->Mdonate->get_pagseguro_order_data($refference);
                    //}
                    //$this->Mdonate->writelog(print_r($_POST, true), 'pagseguro_log');
                    //$server = (isset($_POST['userserver']) && trim($_POST['userserver']) !== "" ? trim($_POST['userserver']) : 'DEFAULT');
                    require_once(APP_PATH . DS . 'libraries' . DS . 'PagSeguroLibrary' . DS . 'PagSeguroLibrary.php');
                    $notificationType = new PagSeguroNotificationType($notificationType);
                    $strType = $notificationType->getTypeFromValue();
                    if(strtoupper($strType) == 'TRANSACTION'){
                        //if(isset($this->vars['order_data']) && $this->vars['order_data'] != false){
                        //	$credentials = new PagSeguroAccountCredentials($this->config->values('donation_config', [$this->vars['order_data']['server'], 'pagseguro', 'email']), $this->config->values('donation_config', [$this->vars['order_data']['server'], 'pagseguro', 'token']));
                        //}
                        //else{
                        $credentials = new PagSeguroAccountCredentials($this->config->values('donation_config', [$server, 'pagseguro', 'email']), $this->config->values('donation_config', [$server, 'pagseguro', 'token']));
                        //}
                        $transaction = PagSeguroNotificationService::checkTransaction($credentials, $notificationCode);
                        $status = $transaction->getStatus();
                        if($status->getValue() == 3){
                            $item = $transaction->getReference();
                            //if(!isset($this->vars['order_data'])){
                            $this->vars['order_data'] = $this->Mdonate->get_pagseguro_order_data($item);
                            //}
                            if($this->vars['order_data'] != false){
                                if($this->Mdonate->check_existing_pagseguro_transaction($notificationCode)){
                                    echo 'Transaction already processed: ' . $item;
                                    writelog('Error: Transaction already processed: ' . $item, 'pagseguro_log');
                                } else{
                                    if($this->website->is_multiple_accounts() == true){
                                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->vars['order_data']['server'], true)]);
                                    } else{
                                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                                    }
                                    $this->Mdonate->insert_pagseguro_transaction($_POST['notificationCode'], $this->vars['order_data']['amount'], $this->vars['order_data']['currency'], $this->vars['order_data']['account'], $this->vars['order_data']['server'], $this->vars['order_data']['credits'], $item);
                                    $this->Mdonate->add_account_log('Reward ' . $this->website->translate_credits($this->config->values('donation_config', [$this->vars['order_data']['server'], 'pagseguro', 'reward_type']), $this->vars['order_data']['server']) . ' PagSeguro', $this->vars['order_data']['credits'], $this->vars['order_data']['account'], $this->vars['order_data']['server']);
                                    $this->Mdonate->reward_user($this->vars['order_data']['account'], $this->vars['order_data']['server'], $this->vars['order_data']['credits'], $this->config->values('donation_config', [$this->vars['order_data']['server'], 'pagseguro', 'reward_type']), $this->Mdonate->get_guid($this->vars['order_data']['account']));
                                    if($this->config->values('email_config', 'donate_email_user') == 1){
                                        $this->Mdonate->sent_donate_email($this->vars['order_data']['account'], $this->vars['order_data']['server'], $this->Mdonate->get_email($this->vars['order_data']['account']), $this->vars['order_data']['credits'], $this->website->translate_credits($this->config->values('donation_config', [$this->vars['order_data']['server'], 'pagseguro', 'reward_type']), $this->vars['order_data']['server']), $this->website->get_user_credits_balance($this->vars['order_data']['account'], $this->vars['order_data']['server'], $this->config->values('donation_config', [$this->vars['order_data']['server'], 'pagseguro', 'reward_type']), $this->Mdonate->get_guid($this->vars['order_data']['account'])));
                                    }
                                    if($this->config->values('email_config', 'donate_email_admin') == 1){
                                        $this->Mdonate->sent_donate_email_admin($this->vars['order_data']['account'], $this->vars['order_data']['server'], $this->config->values('email_config', 'server_email'), $this->vars['order_data']['credits'], $this->website->translate_credits($this->config->values('donation_config', [$this->vars['order_data']['server'], 'pagseguro', 'reward_type']), $this->vars['order_data']['server']), $this->website->get_user_credits_balance($this->vars['order_data']['account'], $this->vars['order_data']['server'], $this->config->values('donation_config', [$this->vars['order_data']['server'], 'pagseguro', 'reward_type']), $this->Mdonate->get_guid($this->vars['order_data']['account'])), 'PagSeguro');
                                    }
                                    echo 'ok';
                                }
                            } else{
                                echo 'Unable to find order data.';
                                $this->Mdonate->writelog('Error: Unable to find order data.', 'pagseguro_log');
                            }
                        } else{
                            echo 'wrong transaction status.';
                            $this->Mdonate->writelog('Error: wrong transaction status ' . $this->_getStatusTranslation($this->_getStatusString($status->getValue())), 'pagseguro_log');
                        }
                    } else{
                        echo 'authorization';
                        $this->Mdonate->writelog('Error: authorization - ' . $strType . '', 'pagseguro_log');
                    }
                } else{
                    echo 'Server key: ' . $server . ' not found in server list';
                    $this->Mdonate->writelog('Server key: ' . $server . ' not found in server list', 'pagseguro_log');
                }
            }
        }

        private function _getStatusTranslation($status)
        {
            $order_status = ['INITIATED' => 'Initiated', 'WAITING_PAYMENT' => 'Waiting payment', 'IN_ANALYSIS' => 'In analysis', 'PAID' => 'Paid', 'AVAILABLE' => 'Available', 'IN_DISPUTE' => 'In dispute', 'REFUNDED' => 'Refunded', 'CANCELLED' => 'Cancelled'];
            if(isset($order_status[$status]))
                return $order_status[$status];
            return 0;
        }

        private function _getStatusString($statusPagSeguro)
        {
            $transactionStatus = new PagSeguroTransactionStatus($statusPagSeguro);
            return $transactionStatus->getTypeFromValue();
        }

        public function fortumo()
        {
            if(count($_GET) > 0){
                //if($this->Mdonate->validate_ip_list($_GET['cuid'], 'fortumo')){
                    if($_GET['sig'] != $sigi = $this->Mdonate->fortumo_sig_check($_GET)){
                        $this->Mdonate->writelog('Error: Invalid signature ' . $_GET['sig'] . '-' . $sigi, 'fortumo');
                        throw new exception('Error: Invalid signature');
                    }
                    if(preg_match("/OK|COMPLETED/i", $_GET['status']) || ((isset($_GET['billing_type']) && preg_match("/MO/i", $_GET['billing_type'])) && preg_match("/pending/i", $_GET['status']))){
                        if($this->Mdonate->check_fortumo_transaction($_GET['payment_id'])){
                            $this->Mdonate->writelog('Error: payment id: ' . $_GET['payment_id'] . ' already rewarded.', 'fortumo');
                        } else{
                            if(preg_match('/\b-server-\b/i', $_GET['cuid'])){
                                $acc_serv = explode('-server-', $_GET['cuid']);
                                if($this->website->is_multiple_accounts() == true){
                                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($acc_serv[1], true)]);
                                } else{
                                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                                }
                                $this->Mdonate->log_fortumo_transaction($_GET['payment_id'], $_GET['sender'], $acc_serv[0], $acc_serv[1], $_GET['amount']);
                                $this->Mdonate->add_account_log('Reward ' . $this->website->translate_credits($this->config->values('donation_config', [$acc_serv[1], 'fortumo', 'reward_type']), $acc_serv[1]) . ' Fortumo', $_GET['amount'], $acc_serv[0], $acc_serv[1]);
                                $this->Mdonate->reward_user($acc_serv[0], $acc_serv[1], $_GET['amount'], $this->config->values('donation_config', [$acc_serv[1], 'fortumo', 'reward_type']), $this->Mdonate->get_guid($acc_serv[0]));
                                if($this->config->values('email_config', 'donate_email_user') == 1){
                                    $this->Mdonate->sent_donate_email($acc_serv[0], $acc_serv[1], $this->Mdonate->get_email($acc_serv[0]), $_GET['amount'], $this->website->translate_credits($this->config->values('donation_config', [$acc_serv[1], 'fortumo', 'reward_type']), $acc_serv[1]), $this->website->get_user_credits_balance($acc_serv[0], $acc_serv[1], $this->config->values('donation_config', [$acc_serv[1], 'fortumo', 'reward_type']), $this->Mdonate->get_guid($acc_serv[0])));
                                }
                                if($this->config->values('email_config', 'donate_email_admin') == 1){
                                    $this->Mdonate->sent_donate_email_admin($acc_serv[0], $acc_serv[1], $this->config->values('email_config', 'server_email'), $_GET['amount'], $this->website->translate_credits($this->config->values('donation_config', [$acc_serv[1], 'fortumo', 'reward_type']), $acc_serv[1]), $this->website->get_user_credits_balance($acc_serv[0], $acc_serv[1], $this->config->values('donation_config', [$acc_serv[1], 'fortumo', 'reward_type']), $this->Mdonate->get_guid($acc_serv[0])), 'Fortumo');
                                }
                            } else{
                                $this->Mdonate->writelog('Error: invalid cuid ' . $_GET['cuid'] . '. Correct format username-server-servername', 'fortumo');
                            }
                        }
                    } else{
                        $this->Mdonate->writelog('Error: payment failed phone: ' . $_GET['sender'] . ', account: ' . $_GET['cuid'] . ', amount credits: ' . $_GET['amount'] . ', status: ' . $_GET['status'], 'fortumo');
                    }
                //} else{
                //    $this->Mdonate->writelog('Error: Unknown IP', 'fortumo');
                 //   throw new exception('Unknown IP');
                //}
            }
        }

        public function paygol()
        {
            if(count($_GET) > 0){
                if(!in_array(ip(), ['109.70.3.48', '109.70.3.146', '109.70.3.58'])){
                    $this->Mdonate->writelog('Error: Unknown IP', 'paygol');
                    throw new exception('Unknown IP');
                } else{
                    if(preg_match('/\b-server-\b/i', $_GET['custom'])){
                        $acc_serv = explode('-server-', $_GET['custom']);
                        if($_GET['service_id'] == $this->config->values('donation_config', [$acc_serv[1], 'paygol', 'service_id'])){
                            if($this->website->is_multiple_accounts() == true){
                                $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($acc_serv[1], true)]);
                            } else{
                                $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                            }
                            $this->Mdonate->log_paygol_transaction($_GET['message_id'], $_GET['message'], $_GET['shortcode'], $_GET['sender'], $_GET['operator'], $_GET['country'], $_GET['currency'], $_GET['price'], $acc_serv[0], $acc_serv[1]);
                            $this->Mdonate->add_account_log('Reward ' . $this->website->translate_credits($this->config->values('donation_config', [$acc_serv[1], 'paygol', 'reward_type']), $acc_serv[1]) . ' Paygol', $this->config->values('donation_config', [$acc_serv[1], 'paygol', 'reward']), $acc_serv[0], $acc_serv[1]);
                            $this->Mdonate->reward_user($acc_serv[0], $acc_serv[1], $this->config->values('donation_config', [$acc_serv[1], 'paygol', 'reward']), $this->config->values('donation_config', [$acc_serv[1], 'paygol', 'reward_type']), $this->Mdonate->get_guid($acc_serv[0]));
                            if($this->config->values('email_config', 'donate_email_user') == 1){
                                $this->Mdonate->sent_donate_email($acc_serv[0], $acc_serv[1], $this->Mdonate->get_email($acc_serv[0]), $this->config->values('donation_config', [$acc_serv[1], 'paygol', 'reward']), $this->website->translate_credits($this->config->values('donation_config', [$acc_serv[1], 'paygol', 'reward_type']), $acc_serv[1]), $this->website->get_user_credits_balance($acc_serv[0], $acc_serv[1], $this->config->values('donation_config', [$acc_serv[1], 'paygol', 'reward_type']), $this->Mdonate->get_guid($acc_serv[0])));
                            }
                            if($this->config->values('email_config', 'donate_email_admin') == 1){
                                $this->Mdonate->sent_donate_email_admin($acc_serv[0], $acc_serv[1], $this->config->values('email_config', 'server_email'), $this->config->values('donation_config', [$acc_serv[1], 'paygol', 'reward']), $this->website->translate_credits($this->config->values('donation_config', [$acc_serv[1], 'paygol', 'reward_type']), $acc_serv[1]), $this->website->get_user_credits_balance($acc_serv[0], $acc_serv[1], $this->config->values('donation_config', [$acc_serv[1], 'paygol', 'reward_type']), $this->Mdonate->get_guid($acc_serv[0])), 'PayGol');
                            }
                        } else{
                            $this->Mdonate->writelog('Error: Wrong service id', 'paygol');
                        }
                    } else{
                        $this->Mdonate->writelog('Error: invalid user: ' . $_GET['custom'] . '. Correct format username-server-servername', 'paygol');
                    }
                }
            }
        }
    }