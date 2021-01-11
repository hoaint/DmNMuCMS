<?php
    in_file();

    class lost_password extends controller
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

        public function index()
        {
            $this->vars['config'] = $this->config->values('lostpassword_config');
            if($this->vars['config'] && $this->vars['config']['active'] == 1){
                $this->vars['security_config'] = $this->config->values('security_config');
                $this->vars['rconfig'] = $this->config->values('registration_config');
                if($this->vars['security_config'] != false){
                    if($this->vars['security_config']['captcha_type'] == 3){
                        $this->load->lib('recaptcha', [true, $this->vars['security_config']['recaptcha_priv_key']]);
                    }
                }
                if(isset($_POST['lost_info'])){
                    if($this->website->is_multiple_accounts() == true){
                        if(!isset($_POST['server']) || $_POST['server'] == ''){
                            $this->vars['error'] = __('Please select proper server.');
                        } else{
                            $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($_POST['server'], true)]);
                        }
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->model('account');
                    foreach($_POST as $key => $value){
                        $this->Maccount->$key = trim($value);
                    }
                    if(!isset($this->Maccount->vars['lost_info']) || $this->Maccount->vars['lost_info'] == '')
                        $this->vars['error'] = __('Please enter username.'); 
					else{
                        if(!$this->Maccount->valid_username($this->Maccount->vars['lost_info']) &&  !$this->Maccount->valid_email($this->Maccount->vars['lost_info']))
                            $this->vars['error'] = __('The username/email you entered is invalid.'); 
						else{
                            if(!$this->Maccount->check_duplicate_account($this->Maccount->vars['lost_info']) && !$this->Maccount->check_duplicate_email($this->Maccount->vars['lost_info']))
                                $this->vars['error'] = __('The username/email you entered doesn\'t exists.'); 
							else{
                                if($this->vars['security_config'] != false){
                                    if($this->vars['security_config']['captcha_type'] == 1){
                                        if(isset($_POST['qaptcha_key'], $_SESSION['qaptcha_key'])){
                                            if($_POST['qaptcha_key'] != $_SESSION['qaptcha_key']){
                                                $this->vars['error'] = __('Invalid captcha, Please check slider position.');
                                            }
                                        } else{
                                            $this->vars['error'] = __('Invalid captcha, Please check slider position.');
                                        }
                                    }
                                    if($this->vars['security_config']['captcha_type'] == 3){
                                        if(isset($_POST["g-recaptcha-response"])){
                                            $response = $this->recaptcha->verifyResponse(ip(), $_POST["g-recaptcha-response"]);
                                            if($response == null || !$response->is_valid){
                                                $this->vars['error'] = __('Incorrect security image response.');
                                            }
                                        } else{
                                            $this->vars['error'] = __('Incorrect security image response.');
                                        }
                                    }
                                }
                                if(!isset($this->vars['error'])){
                                    if($this->vars['config']['method'] == 1){
                                        $this->by_email();
                                    } else{
                                        $this->session->register('lost_password', ['user' => $this->Maccount->vars['lost_info'], 'server' => $this->is_server()]);
                                        header('Location: ' . $this->config->base_url . 'lost-password/by-question/');
                                    }
                                }
                            }
                        }
                    }
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'lost_password' . DS . 'view.lost_password', $this->vars);
            } else{
                $this->disabled();
            }
        }

        private function is_server()
        {
            if(isset($this->Maccount->vars['server'])){
                return $this->Maccount->vars['server'];
            }
            return '';
        }

        public function by_question()
        {
            $this->vars['secret_question_list'] = $this->website->secret_questions();
            if($this->session->userdata(['lost_password' => 'user'])){
                if(isset($_POST['fpas_ques'], $_POST['fpas_answ'])){
                    if(!$this->website->secret_questions($_POST['fpas_ques']))
                        $this->vars['error'] = __('Please select valid secret question.'); else{
                        if(!isset($_POST['fpas_answ']))
                            $this->vars['error'] = __('You haven\'t entered an secret answer.'); else{
                            if($this->website->is_multiple_accounts() == true){
                                if($this->session->userdata(['lost_password' => 'server']) == ''){
                                    $this->vars['error'] = __('Please select proper server.');
                                } else{
                                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($_POST['server'], true)]);
                                }
                            } else{
                                $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                            }
                            $this->load->model('account');
                            $data = $this->Maccount->load_account_by_name($this->session->userdata(['lost_password' => 'user']));
                            if($data){
                                $reminder = $this->Maccount->load_reminder_by_name($data['memb___id']);
                                if($reminder){
                                    if((time() - $reminder['used']) < 600){
                                        $this->vars['error'] = __('The \'Lost Password\'-function was already used for this account less than 10 minutes ago.');
                                    } else{
                                        if(!$this->Maccount->check_secret_q_a($this->session->userdata(['lost_password' => 'user']), $_POST['fpas_ques'], $_POST['fpas_answ']))
                                            $this->vars['error'] = __('Wrong secret question and/or answer.'); else{
                                            $this->Maccount->delete_reminder_entries_for_name($data['memb___id']);
                                            $code = $this->Maccount->create_reminder_entry_for_name($data['memb___id']);
                                            $this->session->unset_session_key('lost_password');
                                            header('Location: ' . $this->config->base_url . 'lost-password/activation/' . $code . '/' . $this->session->userdata(['lost_password' => 'server']));
                                        }
                                    }
                                } else{
                                    if(!$this->Maccount->check_secret_q_a($this->session->userdata(['lost_password' => 'user']), $_POST['fpas_ques'], $_POST['fpas_answ']))
                                        $this->vars['error'] = __('Wrong secret question and/or answer.'); else{
                                        $this->Maccount->delete_reminder_entries_for_name($data['memb___id']);
                                        $code = $this->Maccount->create_reminder_entry_for_name($data['memb___id']);
                                        $this->session->unset_session_key('lost_password');
                                        header('Location: ' . $this->config->base_url . 'lost-password/activation/' . $code . '/' . $this->session->userdata(['lost_password' => 'server']));
                                    }
                                }
                            } else{
                                $this->vars['error'] = __('Unable To Load User Info.');
                            }
                        }
                    }
                }
            }
            $this->load->view($this->config->config_entry('main|template') . DS . 'lost_password' . DS . 'view.lost_password', $this->vars);
        }

        private function by_email()
        {
            $data = $this->Maccount->load_account_by_name($this->Maccount->vars['lost_info']);
            if($data){
                $reminder = $this->Maccount->load_reminder_by_name($data['memb___id']);
                if($reminder){
                    if((time() - $reminder['used']) < 600){
                        $this->vars['error'] = __('The \'Lost Password\'-function was already used for this account less than 10 minutes ago.');
                    } else{
                        $this->Maccount->delete_reminder_entries_for_name($data['memb___id']);
                        $code = $this->Maccount->create_reminder_entry_for_name($data['memb___id']);
                        if($this->Maccount->send_lostpassword_email_for_name($data['memb___id'], $data['mail_addr'], $code)){
                            $this->vars['success'] = __('An eMail was sent to your eMail-adress containing information on how to retrieve a new password.');
                        } else{
                            $this->Maccount->delete_reminder_entries_for_name($data['memb___id']);
                            $this->vars['error'] = $this->Maccount->error;
                        }
                    }
                } else{
                    $code = $this->Maccount->create_reminder_entry_for_name($data['memb___id']);
                    if($this->Maccount->send_lostpassword_email_for_name($data['memb___id'], $data['mail_addr'], $code)){
                        $this->vars['success'] = __('An eMail was sent to your eMail-adress containing information on how to retrieve a new password.');
                    } else{
                        $this->Maccount->delete_reminder_entries_for_name($data['memb___id']);
                        $this->vars['error'] = $this->Maccount->error;
                    }
                }
            } else{
                $this->vars['error'] = __('Unable To Load User Info.');
            }
        }

        public function activation($code = '', $server = '')
        {
            $this->vars['config'] = $this->config->values('lostpassword_config');
            if($this->vars['config'] && $this->vars['config']['active'] == 1){
                $this->vars['rconfig'] = $this->config->values('registration_config');
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($server, true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->model('account');
                $code = strtolower(trim(preg_replace('/[^0-9a-f]/i', '', $code)));
                if(strlen($code) <> 40){
                    $this->vars['error'] = __('Invalid lost password reminder code.');
                } else{
                    $reminder = $this->Maccount->load_reminder_by_code($code);
                    if($reminder){
                        $this->vars['valid'] = 1;
                        if(isset($_POST['new_password'])){
                            foreach($_POST as $key => $value){
                                $this->Maccount->$key = trim($value);
                            }
                            if(!isset($this->Maccount->vars['new_password']) || $this->Maccount->vars['new_password'] == '')
                                $this->errors[] = __('You haven\'t entered a password.'); else{
                                if(!$this->Maccount->valid_password($this->Maccount->vars['new_password']))
                                    $this->errors[] = __('The password you entered is invalid.');
                            }
                            if(!isset($this->Maccount->vars['new_password2']) || $this->Maccount->vars['new_password2'] == '')
                                $this->errors[] = __('You haven\'t entered the password-repetition.'); else{
                                if($this->Maccount->vars['new_password'] !== $this->Maccount->vars['new_password2'])
                                    $this->errors[] = __('The two passwords you entered do not match.');
                            }
                            if(count($this->errors) > 0){
                                $this->vars['error'] = $this->errors;
                            } else{
                                if($this->Maccount->update_password($reminder['assignto'])){
                                    $this->Maccount->delete_reminder_entries_for_name($reminder['assignto']);
                                    $this->vars['success'] = sprintf(__('Your password was changed to: <b>%s</b>'), $this->Maccount->vars['new_password']);
                                } else{
                                    $this->vars['error'] = __('Password could not be updated.');
                                }
                            }
                        }
                    } else{
                        $this->vars['error'] = __('Lost Password reminder code does not exist in database.');
                    }
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'lost_password' . DS . 'view.set_new_password', $this->vars);
            } else{
                $this->disabled();
            }
        }

        public function disabled()
        {
            $this->load->view($this->config->config_entry('main|template') . DS . 'view.module_disabled');
        }
    }