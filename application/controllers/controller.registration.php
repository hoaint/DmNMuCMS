<?php
    in_file();

    class registration extends controller
    {
        protected $vars = [], $errors = [];

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('website');
            $this->load->lib('session', ['DmNCMS']);
			$this->load->lib('csrf');						 
            $this->load->helper('breadcrumbs', [$this->request]);
            $this->load->helper('meta');
        }

        public function index($ref = '', $server = '')
        {
            $this->vars['config'] = $this->config->values('registration_config');
            if($this->vars['config'] && $this->vars['config']['active'] == 1){
                $this->vars['security_config'] = $this->config->values('security_config');
                if($this->vars['security_config'] != false){
                    if($this->vars['security_config']['captcha_type'] == 3){
                        $this->load->lib('recaptcha', [true, $this->vars['security_config']['recaptcha_priv_key']]);
                    }
                }
                if($this->config->values('referral_config', 'active') == 1){
                    if($ref != ''){
                        $this->vars['show_ref'] = true;
                        $this->vars['ref'] = htmlspecialchars($ref);
                        $this->vars['server'] = htmlspecialchars($server);
                    } else{
                        $this->vars['show_ref'] = false;
                        $this->vars['ref'] = '';
                        $this->vars['server'] = '';
                    }
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'registration' . DS . 'view.registration', $this->vars);
            } else{
                $this->disabled();
            }
        }

        public function create_account()
        {
            $this->vars['config'] = $this->config->values('registration_config');
            if($this->vars['config'] && $this->vars['config']['active'] == 1){
                $this->vars['security_config'] = $this->config->values('security_config');
                if($this->vars['security_config'] != false){
                    if($this->vars['security_config']['captcha_type'] == 3){
                        $this->load->lib('recaptcha', [true, $this->vars['security_config']['recaptcha_priv_key']]);
                    }
                }
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($_POST['server'], true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->model('account');
                foreach($_POST as $key => $value){
                    $this->Maccount->$key = trim($value);
                }
                if(!isset($_POST['user']))
                    $this->errors[] = __('You haven\'t entered a username.'); else{
                    if(!$this->Maccount->valid_username($_POST['user'], 'a-zA-Z0-9_-', [$this->vars['config']['min_username'], $this->vars['config']['max_username']]))
                        $this->errors[] = __('The username you entered is invalid.'); else{
                        if($this->Maccount->check_duplicate_account($_POST['user']))
                            $this->errors[] = __('The username you entered is already taken.');
                    }
                }
                if($this->vars['config']['email_validation'] == 0 || $this->vars['config']['generate_password'] == 0){
                    if(!isset($_POST['pass']))
                        $this->errors[] = __('You haven\'t entered a password.'); else{
                        if(!$this->Maccount->valid_password($_POST['pass']))
                            $this->errors[] = __('The password you entered is invalid.');
                        $this->Maccount->test_password_strength($_POST['pass'], [$this->vars['config']['min_password'], $this->vars['config']['max_password']], $this->vars['config']['password_strength']);
                        if(isset($this->Maccount->errors)){
                            $this->errors = $this->Maccount->vars['errors'];
                        }
                    }
                    if(!isset($_POST['rpass']))
                        $this->errors[] = __('You haven\'t entered the password-repetition.'); else{
                        if($_POST['pass'] !== $_POST['rpass'])
                            $this->errors[] = __('The two passwords you entered do not match.');
                    }
                } else{
                    $this->Maccount->pass = $this->Maccount->generate_password($this->vars['config']['min_password'], $this->vars['config']['max_password'], $this->vars['config']['password_strength']);
                }
                if($this->vars['config']['req_email'] == 1){
                    if(!isset($_POST['email']))
                        $this->errors[] = __('You haven\'t entered an email-address.'); else{
                        if(!$this->Maccount->valid_email($_POST['email']))
                            $this->errors[] = __('You have entered an invalid email-address.'); else{
								
                            if($this->Maccount->check_duplicate_email($_POST['email']))
                                $this->errors[] = __('The email-address you entered is already in use.');
                        }
                    }
                }
                if($this->vars['config']['req_secret'] == 1){
                    if(!isset($_POST['fpas_ques']))
                        $this->errors[] = __('You haven\'t selected secret question.'); else{
                        if(!$this->website->secret_questions($_POST['fpas_ques']))
                            $this->errors[] = __('Please select valid secret question.'); else{
                            if(!isset($_POST['fpas_answ']))
                                $this->errors[] = __('You haven\'t entered an secret answer.');
                        }
                    }
                }
                if(!isset($_POST['rules']))
                    $this->errors[] = __('You haven\'t accepted rules.'); else{
                    if($_POST['rules'] != 'on')
                        $this->errors[] = __('You haven\'t accepted rules.');
                }
                if($this->vars['security_config'] != false){
                    if($this->vars['security_config']['captcha_type'] == 1){
                        if(isset($_POST['qaptcha_key'], $_SESSION['qaptcha_key'])){
                            if($_POST['qaptcha_key'] != $_SESSION['qaptcha_key']){
                                $this->errors[] = __('Invalid captcha, Please check slider position.');
                            }
                        } else{
                            $this->errors[] = __('Invalid captcha, Please check slider position.');
                        }
                    }
                    if($this->vars['security_config']['captcha_type'] == 3){
                        if(isset($_POST["g-recaptcha-response"])){
                            $response = $this->recaptcha->verifyResponse(ip(), $_POST["g-recaptcha-response"]);
                            if($response == null || !$response->is_valid){
                                $this->errors[] = __('Incorrect security image response.');
                            }
                        } else{
                            $this->errors[] = __('Incorrect security image response.');
                        }
                    }
                }
                if($this->config->values('referral_config', 'active') == 1){
                    if(isset($_POST['referrer'])){
                        if(!$this->Maccount->valid_id($_POST['referrer']))
                            $this->errors[] = __('The referrer id you entered is invalid.'); else{
                            if(!$referrer_account = $this->Maccount->check_acc_by_guid($_POST['referrer']))
                                $this->errors[] = __('The referrer you entered doesn\'t exists.');
                        }
                    }
                }
                if(count($this->errors) > 0){
                    if(count($this->errors) == 1)
                        json(['error' => $this->errors[0]]); else
                        json(['error' => $this->errors]);
                } else{
                    if($this->vars['config']['email_validation'] == 1){
                        $this->Maccount->set_activation(1);
                    }
                    if($this->Maccount->prepare_account($this->vars['config']['req_email'], $this->vars['config']['req_secret'])){
                        $this->Maccount->log_user_ip($_POST['user']);
                        if($this->config->values('referral_config', 'active') == 1){
                            if(isset($_POST['referrer'])){
                                $this->Maccount->insert_referrer($referrer_account['memb___id']);
                                if($this->config->values('referral_config', 'reward_on_registration') != 0){
                                    if(!$this->Maccount->check_referral_ip()){
                                        $this->Maccount->add_ref_reward_after_reg($referrer_account['memb___id']);
                                    }
                                }
                            }
                        }
                        $this->load->model('shop');
                        $vip_data = isset($_POST['server']) ? $this->Mshop->load_registration_vip_packages($_POST['server']) : $this->Mshop->load_registration_vip_packages();
                        if(!empty($vip_data)){
                            $vip_query_config = $this->config->values('vip_query_config');
                            foreach($vip_data AS $key => $data){
                                $server = isset($_POST['server']) ? $_POST['server'] : $data['server'];
                                $viptime = time() + $data['vip_time'];
                                $this->Mshop->insert_vip_package($data['id'], $viptime, $_POST['user'], $server);
                                $this->Mshop->add_server_vip($viptime, $data['server_vip_package'], $data['connect_member_load'], $vip_query_config, $_POST['user']);
                                $this->Maccount->add_account_log('Added free vip ' . $data['package_title'] . ' package', 0, $_POST['user'], $server);
                            }
                        }
						if(defined('IPS_CONNECT') && IPS_CONNECT == true){
                            $this->load->lib('ipb');
                            if($this->ipb->checkEmail($_POST['email']) == false){
                                $salt = $this->ipb->generateSalt();
                                $this->ipb->register($_POST['user'], $_POST['email'], $this->ipb->encrypt_password($_POST['pass'], $salt), $salt);
                            }
                        }
                        if($this->vars['config']['email_validation'] == 1){
                            json(['success' => __('Your account has been successfully created.') . ' <br />' . __('Please check your email for account activation.')]);
                        } else{
                            json(['success' => __('Your account has been successfully created.')]);
                        }
                    } else{
                        if($this->Maccount->error != false){
                            json(['error' => $this->Maccount->error]);
                        } else{
                            json(['error' => __('There was an error creating your account. Please try again later.')]);
                        }
                    }
                }
            } else{
                json(['error' => __('This module has been disabled.')]);
            }
        }

        public function create_account_with_fb($server, $email)
        {
            $this->vars['config'] = $this->config->values('registration_config');
            if($this->vars['config'] && $this->vars['config']['active'] == 1){
                $this->vars['server'] = ($server != false) ? $server : '';
                if(isset($_POST['add_fb_account'])){
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($_POST['server'], true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->model('account');
                    foreach($_POST as $key => $value){
                        $this->Maccount->$key = trim($value);
                    }
                    $this->Maccount->email = $email;
                    if(!isset($_POST['user']))
                        $this->vars['errors'][] = __('You haven\'t entered a username.'); else{
                        if(!$this->Maccount->valid_username($_POST['user']))
                            $this->vars['errors'][] = __('The username you entered is invalid.'); else{
                            if($this->Maccount->check_duplicate_account($_POST['user']))
                                $this->vars['errors'][] = __('The username you entered is already taken.');
                        }
                    }
                    if($this->vars['config']['generate_password'] == 0){
                        if(!isset($_POST['pass']))
                            $this->vars['errors'][] = __('You haven\'t entered a password.'); else{
                            if(!$this->Maccount->valid_password($_POST['pass']))
                                $this->vars['errors'][] = __('The password you entered is invalid.');
                            $this->Maccount->test_password_strength($_POST['pass'], [$this->vars['config']['min_password'], $this->vars['config']['max_password']], $this->vars['config']['password_strength']);
                            if(isset($this->Maccount->errors)){
                                $this->vars['errors'] = $this->Maccount->vars['errors'];
                            }
                        }
                        if(!isset($_POST['rpass']))
                            $this->vars['errors'][] = __('You haven\'t entered the password-repetition.'); else{
                            if($_POST['pass'] !== $_POST['rpass'])
                                $this->vars['errors'][] = __('The two passwords you entered do not match.');
                        }
                    } else{
                        $this->Maccount->pass = $this->Maccount->generate_password($this->vars['config']['min_password'], $this->vars['config']['max_password'], $this->vars['config']['password_strength']);
                    }
                    if($this->vars['config']['req_secret'] == 1){
                        if(!isset($_POST['fpas_ques']))
                            $this->vars['errors'][] = __('You haven\'t selected secret question.'); else{
                            if(!$this->website->secret_questions($_POST['fpas_ques']))
                                $this->vars['errors'][] = __('Please select valid secret question.'); else{
                                if(!isset($_POST['fpas_answ']))
                                    $this->vars['errors'][] = __('You haven\'t entered an secret answer.');
                            }
                        }
                    }
                    if(isset($this->vars['errors']) && count($this->vars['errors']) > 0){
                        if(count($this->vars['errors']) == 1)
                            $this->vars['errors'] = $this->vars['errors'][0];
                    } else{
                        $this->Maccount->set_activation(0);
                        if($this->Maccount->prepare_account(1, $this->vars['config']['req_secret'])){ 
                            $this->Maccount->check_fb_user($email, $server);
                            $this->Maccount->clear_login_attemts();
                            header('Location: ' . $this->config->base_url . 'account-panel');
                        } else{
                            if($this->Maccount->error != false){
                                $this->vars['errors'][0] = $this->Maccount->error;
                            } else{
                                $this->vars['errors'][0] = __('There was an error creating your account. Please try again later.');
                            }
                        }
                    }
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'registration' . DS . 'view.fb_registration', $this->vars);
            } else{
                $this->disabled();
            }
        }

        public function success()
        {
            $this->vars['config'] = $this->config->values('registration_config');
            if($this->vars['config'] && $this->vars['config']['active'] == 1){
                $this->load->view($this->config->config_entry('main|template') . DS . 'registration' . DS . 'view.successfull', $this->vars);
            } else{
                $this->disabled();
            }
        }

        public function activation($code = '', $server = '')
        {
            if($this->website->is_multiple_accounts() == true){
                $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($server, true)]);
            } else{
                $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
            }
            $this->load->model('account');
            $this->vars['config'] = $this->config->values('registration_config');
            if($this->vars['config'] && $this->vars['config']['active'] == 1){
                $code = strtolower(trim(preg_replace('/[^0-9a-f]/i', '', $code)));
                if(strlen($code) <> 40){
                    $this->vars['error'] = __('Invalid account activation code.');
                } else{
                    if(!$activation = $this->Maccount->check_activation_code($code)){
                        $this->vars['error'] = __('Activation code doesn\'t exist in our database.');
                    } else{
                        if($activation['activated'] == 1){
                            $this->vars['error'] = __('This account is already activated.');
                        } else{
                            if($this->Maccount->activate_account($activation['memb___id'], $code)){
                                if($this->config->values('email_config', 'welcome_email') == 1){
                                    $this->Maccount->send_welcome_email($activation['memb___id'], $activation['mail_addr']);
                                }
                                $this->vars['success'] = __('Account succesfully activated. You can now login.');
                            } else{
                                $this->vars['error'] = __('Unable to activate account.');
                            }
                        }
                    }
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'registration' . DS . 'view.activation', $this->vars);
            } else{
                $this->disabled();
            }
        }

        public function resend_activation()
        {
            $this->vars['config'] = $this->config->values('registration_config');
            $this->vars['security_config'] = $this->config->values('security_config');
            if($this->vars['security_config'] != false){
                if($this->vars['security_config']['captcha_type'] == 3){
                    $this->load->lib('recaptcha', [true, $this->vars['security_config']['recaptcha_priv_key']]);
                }
            }
            if($this->vars['config'] && $this->vars['config']['active'] == 1){
                if($this->vars['config']['email_validation'] == 0){
                    $this->vars['not_required'] = __('Account validation not required');
                } else{
                    if(isset($_POST['email'])){																   
                        if($this->website->is_multiple_accounts() == true){
                            $server = $_POST['server'];
                            $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($_POST['server'], true)]);
                        } else{
                            $server = '';
                            $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                        }
                        $this->load->model('account');
                        foreach($_POST as $key => $value){
                            $this->Maccount->$key = trim($value);
                        }
                        if($_POST['email'] == '')
                            $this->errors[] = __('You haven\'t entered an email-address.'); else{
                            if(!$this->Maccount->valid_email($_POST['email']))
                                $this->errors[] = __('You have entered an invalid email-address.'); else{
                                $validated = $this->Maccount->check_if_validated($_POST['email']);
                                if($validated != false){
                                    if($validated['activated'] == 1){
                                        $this->errors[] = __('The email-address you entered is already activated.');
                                    }
                                } else{
                                    $this->errors[] = __('The email-address you entered not found in our database.');
                                }
                            }
                        }
                        if($this->vars['security_config'] != false){
                            if($this->vars['security_config']['captcha_type'] == 1){
                                if(isset($_POST['qaptcha_key'], $_SESSION['qaptcha_key'])){
                                    if($_POST['qaptcha_key'] != $_SESSION['qaptcha_key']){
                                        $this->errors[] = __('Invalid captcha, Please check slider position.');
                                    }
                                } else{
                                    $this->errors[] = __('Invalid captcha, Please check slider position.');
                                }
                            }
                            if($this->vars['security_config']['captcha_type'] == 3){
                                if(isset($_POST["g-recaptcha-response"])){
                                    $response = $this->recaptcha->verifyResponse(ip(), $_POST["g-recaptcha-response"]);
                                    if($response == null || !$response->is_valid){
                                        $this->errors[] = __('Incorrect security image response.');
                                    }
                                } else{
                                    $this->errors[] = __('Incorrect security image response.');
                                }
                            }
                        }
                        if(count($this->errors) > 0){
                            if(count($this->errors) == 1)
                                $this->vars['error'] = $this->errors[0]; else
                                $this->vars['error'] = $this->errors;
                        } else{
                            if($this->Maccount->resend_activation_email($_POST['email'], $validated['memb___id'], $validated['memb__pwd'], $server, $validated['activation_id'])){
                                $this->vars['success'] = __('Account activation email was successfully sent.');
                            }
                        }
                    }
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'registration' . DS . 'view.resend_activation', $this->vars);
            } else{
                $this->disabled();
            }
        }

        public function disabled()
        {
            $this->load->view($this->config->config_entry('main|template') . DS . 'view.module_disabled');
        }
    }