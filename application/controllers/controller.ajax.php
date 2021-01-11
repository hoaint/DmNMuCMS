<?php
    in_file();

    class ajax extends controller
    {
        protected $vars = [], $errors = [];
		protected $resetSkillTreeClass = [2, 3, 7, 18, 19, 23, 34, 35, 39, 49, 50, 51, 54, 65, 66, 67, 70, 82, 83, 84, 87, 97, 98, 99, 102, 114, 115, 118, 130, 131, 135, 147, 151];

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('website');
            $this->load->lib('session', ['DmNCMS']);
			$this->load->lib('csrf');						 
            if($this->session->userdata(['user' => 'logged_in'])){
                if(!in_array($this->request->get_method(), ['event_timers', 'get_time'])){
                    if($this->config->values('scheduler_config', 'type') == 3){
                        file_get_contents($this->config->base_url . 'interface/web.php?key=' . $this->config->values('scheduler_config', 'key'));
                    }
                }
            }
        }

        public function index()
        {
            throw new exception('Nothing to see in here');
        }

        public function checkcaptcha()
        {
            if(isset($_POST['act'], $_POST['qaptcha_key'])){
                $_SESSION['qaptcha_key'] = false;
                if(htmlentities($_POST['act'], ENT_QUOTES, 'UTF-8') == 'qaptcha'){
                    $_SESSION['qaptcha_key'] = $_POST['qaptcha_key'];
                    json(['error' => false]);
                } else{
                    json(['error' => true]);
                }
            } else{
                json(['error' => true]);
            }
        }

        public function login()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                json(['error' => __('You are already logged in. Please logout first.')]);
            } else{
                $servers = $this->website->server_list();
                $default = array_keys($servers)[0];
                if(!isset($_POST['server'])){
                    $_POST['server'] = $default;
                } else{
                    if(!array_key_exists($_POST['server'], $servers)){
                        $_POST['server'] = $default;
                    }
                }
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($_POST['server'], true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->model('account');
                $this->vars['config'] = $this->config->values('registration_config');
                if($this->vars['config'] != false && !empty($this->vars['config'])){
                    $this->Maccount->servers = $servers;
                    foreach($_POST as $key => $value){
                        $this->Maccount->$key = trim($value);
                    }
                    if($this->Maccount->check_login_attemts() == true){
                        json(['error' => __('You have reached max failed login attemts, please come back after 15 minutes.')]);
                    } else{
                        if(!isset($this->Maccount->vars['username']))
                            json(['error' => __('You haven\'t entered a username.')]); else{
                            if($this->Maccount->vars['username'] == '')
                                json(['error' => __('You haven\'t entered a username.')]); else{
                                if(!isset($this->Maccount->vars['password']))
                                    json(['error' => __('You haven\'t entered a password.')]); else{
                                    if($this->Maccount->vars['password'] == '')
                                        json(['error' => __('You haven\'t entered a password.')]); else{
                                        if(!$this->Maccount->valid_username($this->Maccount->vars['username'], '\w\W', [$this->vars['config']['min_username'], $this->vars['config']['max_username']]))
                                            json(['error' => __('The username you entered is invalid.')]); else{
                                            if(!$this->Maccount->valid_password($this->Maccount->vars['password'], '\w\W', [$this->vars['config']['min_password'], $this->vars['config']['max_password']]))
                                                json(['error' => __('The password you entered is invalid.')]); else{
                                                if(isset($this->Maccount->vars['server'])){
                                                    if($this->Maccount->vars['server'] == '')
                                                        json(['error' => __('Please select proper server.')]); 
													else{
                                                        $ban_info = $this->Maccount->check_acc_ban();
                                                        if($ban_info != false){
                                                            if($ban_info['time'] > time() && $ban_info['is_permanent'] == 0){
                                                                json(['error' => sprintf(__('Your account is blocked until %s'), date('d.m.Y H:i', $ban_info['time']))]);
                                                            } else{
                                                                if($ban_info['is_permanent'] == 1){
                                                                    json(['error' => __('Your account is blocked permanently')]);
                                                                } else{
                                                                    goto login;
                                                                }
                                                            }
                                                        } else{
                                                            goto login;
                                                        }
                                                    }
                                                } else{
                                                    goto login;
                                                }
                                                login:
                                                if($login = $this->Maccount->login_user()){			
                                                    if(($this->vars['config']['email_validation'] == 1) && ($login['activated'] == 0)){
                                                        $this->session->unset_session_key('user');
                                                        json(['error' => __('Please activate your account first. <a id="repeat_activation" href="' . $this->config->base_url . 'registration/resend-activation">Did not receive activation email?</a>'), 'sticky' => 1]);
                                                    } else{
                                                        $this->Maccount->log_user_ip();
                                                        $this->Maccount->clear_login_attemts();
                                                        $this->change_user_vip_session($this->Maccount->vars['username'], $this->Maccount->vars['server']);
                                                        setcookie("DmN_Current_User_Server_" . $this->Maccount->vars['username'], $_POST['server'], strtotime('+1 days', time()), "/");
                                                        if(defined('IPS_CONNECT') && IPS_CONNECT == true){
                                                            $this->load->lib('ipb');
                                                            if($this->ipb->checkEmail($this->session->userdata(['user' => 'email'])) == true){
                                                                $salt = $this->ipb->fetchSalt(2, $this->session->userdata(['user' => 'email']));
                                                                $ipb_login_data = $this->ipb->login(2, $this->session->userdata(['user' => 'email']), $this->ipb->encrypt_password($this->Maccount->vars['password'], $salt));
                                                                $this->session->session_key_overwrite('user', [0 => 'ipb_id', 1 => $ipb_login_data['connect_id']]);
                                                                json(['success' => __('You have logged in successfully.'), 'ipb_login' => $this->ipb->crossLogin($ipb_login_data['connect_id'], $this->config->base_url . 'account-panel')]);
																   
																																									  
                                                            }
                                                        }
																																													   
                                                        json(['success' => __('You have logged in successfully.')]);
                                                    }
                                                } else{
                                                    $this->Maccount->add_login_attemt();
                                                    json(['error' => __('Wrong username and/or password.')]);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else{
                    json(['error' => __('Registration settings has not yet been configured.')]);
                }
            }
        }

		public function switch_server()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if(isset($_POST['server'])){
                    $server_list = $this->website->server_list();
                    if(array_key_exists($_POST['server'], $server_list)){
                        if($this->website->is_multiple_accounts() == true){
                            $this->load->model('account');
                            if($this->Maccount->check_user_on_server($this->session->userdata(['user' => 'username']), $_POST['server'])){
                                $this->change_user_session_server($this->session->userdata(['user' => 'username']), $_POST['server'], $server_list);
                                $this->change_user_vip_session($this->session->userdata(['user' => 'username']), $_POST['server']);
                                json(['success' => __('Server Changed.')]);
                            } else{
                                json(['error' => __('You have not created account on this server. Please logout and create.')]);
                            }
                        } else{
                            $this->change_user_session_server($this->session->userdata(['user' => 'username']), $_POST['server'], $server_list);
                            $this->change_user_vip_session($this->session->userdata(['user' => 'username']), $_POST['server']);
                            json(['success' => __('Server Changed.')]);
                        }
                    } else{
                        json(['error' => __('Invalid server selected.')]);
                    }
                } else{
                    json(['error' => __('Invalid server selected.')]);
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        private function change_user_session_server($user, $server, $server_list)
        {
            $this->session->session_key_overwrite('user', [0 => 'server', 1 => $server]);
            $this->session->session_key_overwrite('user', [0 => 'server_t', 1 => $server_list[$server]['title']]);
            setcookie("DmN_Current_User_Server_" . $user, $server, strtotime('+1 days', time()), "/");
        }

        private function change_user_vip_session($user, $server)
        {
			$this->vars['config'] = $this->config->values('vip_config');
			
			if(!empty($this->vars['config']) && $this->vars['config']['active'] == 1){
				$this->load->model('account');
				if($this->vars['vip_data'] = $this->Maccount->check_vip($user, $server)){
					$this->vars['vip_package_info'] = $this->Maccount->load_vip_package_info($this->vars['vip_data']['viptype'], $server);
					if($this->vars['vip_data']['viptime'] <= time()){
						$this->Maccount->remove_vip($this->vars['vip_data']['viptype'], $user, $server);
						if($this->vars['vip_package_info'] != false){
							$this->Maccount->check_connect_member_file($this->vars['vip_package_info']['connect_member_load'], $user);
						}
					} else{
						$this->Maccount->set_vip_session($this->vars['vip_data']['viptime'], $this->vars['vip_package_info']);
					}
				} else{
					$this->session->unset_session_key('vip');
				}
			}
        }
		
        public function change_password()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['config'] = $this->config->values('registration_config');
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->model('account');
                foreach($_POST as $key => $value){
                    $this->Maccount->$key = trim($value);
                }
                if(!isset($this->Maccount->vars['old_password']))
                    json(['error' => __('You haven\'t entered your current password.')]); else{
                    if(!$this->Maccount->compare_passwords())
                        json(['error' => __('The current password you entered is wrong.')]); else{
                        if(!isset($this->Maccount->vars['new_password']))
                            json(['error' => __('You haven\'t entered your new password.')]); else{
                            if(!$this->Maccount->valid_password($this->Maccount->vars['new_password']))
                                json(['error' => __('The new password you entered is invalid.')]); else{
                                $this->Maccount->test_password_strength($this->Maccount->vars['new_password'], [$this->vars['config']['min_password'], $this->vars['config']['max_password']], $this->vars['config']['password_strength']);
                                if(isset($this->Maccount->errors))
                                    json(['error' => $this->Maccount->vars['errors']]); else{
                                    if(!isset($this->Maccount->vars['new_password2']))
                                        json(['error' => __('You haven\'t entered new password-repetition.')]); else{
                                        if($this->Maccount->vars['new_password'] != $this->Maccount->vars['new_password2'])
                                            json(['error' => __('The two passwords you entered do not match.')]); else{
                                            if($this->Maccount->vars['old_password'] == $this->Maccount->vars['new_password'])
                                                json(['error' => __('New password cannot be same as old!')]); else{
                                                if($this->Maccount->update_password()){
                                                    $this->session->destroy();
                                                    json(['success' => [__('Your password was successfully changed.'), __('You\'ve been logged out for security reasons!')]]);
                                                } else{
                                                    json(['error' => __('Password could not be updated.')]);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function change_email()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->config->config_entry('account|allow_mail_change') == 1){
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->model('account');
                    foreach($_POST as $key => $value){
                        $this->Maccount->$key = trim($value);
                    }
                    if(!isset($this->Maccount->vars['email']))
                        json(['error' => __('You haven\'t entered your current email.')]); else{
                        if(!$this->Maccount->valid_email($this->Maccount->vars['email']))
                            json(['error' => __('You have entered an invalid email-address.')]); else{
                            if(!$this->Maccount->check_existing_email())
                                json(['error' => __('Email-address is wrong for this account.')]); else{
                                if($this->Maccount->create_email_confirmation_entry(1)){
                                    if($this->Maccount->send_email_confirmation()){
                                        json(['success' => __('Please check your current mail-box for confirmation link.')]);
                                    } else{
                                        $this->Maccount->delete_old_confirmation_entries($this->session->userdata(['user' => 'username']), 1);
                                        json(['error' => $this->Maccount->error]);
                                    }
                                } else{
                                    json(['error' => __('Unable to write confirmation code into database.')]);
                                }
                            }
                        }
                    }
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function set_new_email()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->config->config_entry('account|allow_mail_change') == 1){
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->model('account');
                    foreach($_POST as $key => $value){
                        $this->Maccount->$key = trim($value);
                    }
                    if(!isset($this->Maccount->vars['email']))
                        json(['error' => __('You haven\'t entered your new email-address.')]); else{
                        if(!$this->Maccount->valid_email($this->Maccount->vars['email']))
                            json(['error' => __('You have entered an invalid email-address.')]); else{
                            if($this->Maccount->check_duplicate_email($this->Maccount->vars['email']))
                                json(['error' => __('This email-address is already used.')]); else{
                                if($this->Maccount->create_email_confirmation_entry(0)){
                                    if($this->Maccount->send_email_confirmation()){
                                        $this->Maccount->delete_old_confirmation_entries($this->session->userdata(['user' => 'username']), 1);
                                        json(['success' => __('Please check your new mail-box for confirmation link.')]);
                                    } else{
                                        $this->Maccount->delete_old_confirmation_entries($this->session->userdata(['user' => 'username']));
                                        json(['error' => $this->Maccount->error]);
                                    }
                                } else{
                                    json(['error' => __('Unable to write confirmation code into database.')]);
                                }
                            }
                        }
                    }
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function status()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                json(['success' => true]);
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function checkcredits()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->model('account');
                $payment_method = (isset($_POST['payment_method']) && ctype_digit($_POST['payment_method'])) ? (int)$_POST['payment_method'] : '';
                $credits = (isset($_POST['credits']) && ctype_digit($_POST['credits'])) ? (int)$_POST['credits'] : '';
                $gcredits = (isset($_POST['gcredits']) && ctype_digit($_POST['gcredits'])) ? (int)$_POST['gcredits'] : '';
                if(!in_array($payment_method, [1, 2]))
                    json(['error' => __('Invalid payment method.')]); else if($credits === '')
                    json(['error' => sprintf(__('Invalid amount of %s'), $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_1'))]);
                else if($gcredits === '')
                    json(['error' => sprintf(__('Invalid amount of %s'), $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_2'))]);
                else{
                    $status = $this->website->get_user_credits_balance($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $payment_method, $this->session->userdata(['user' => 'id']));
                    if($payment_method == 1){
                        if($status['credits'] < $credits){
                            json(['error' => sprintf(__('You have insufficient amount of %s'), $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_1'))]);
                        } else{
                            json(['success' => true]);
                        }
                    }
                    if($payment_method == 2){
                        if($status['credits'] < $gcredits){
                            json(['error' => sprintf(__('You have insufficient amount of %s'), $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_2'))]);
                        } else{
                            json(['success' => true]);
                        }
                    }
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function vote()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['votereward_config'] = $this->config->values('votereward_config', $this->session->userdata(['user' => 'server']));
                if($this->vars['votereward_config']['active'] == 1){
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                    $this->load->model('account');
					$this->load->model('character');
                    if(isset($_POST['vote']) && ctype_digit($_POST['vote'])){
						if($this->vars['votereward_config']['req_char'] == 1){
							$this->vars['has_char'] = ($info = $this->Mcharacter->load_char_list()) ? $info : false;
						}
						if(isset($this->vars['has_char']) && $this->vars['has_char'] == false){
							 json(['error' => __('Voting require character.')]);
						}
						if(isset($this->vars['has_char'])) {
							$lvl_total = 0;
							$res_total = 0;
							foreach ($this->vars['has_char'] as $key => $value) {
								$lvl_total += $value['level'];
								$res_total += $value['resets'];
							}

							if ($this->vars['votereward_config']['req_lvl'] > $lvl_total) {
								json(['error' => __('Your character total level sum need to be atleast') . ' ' . $this->vars['votereward_config']['req_lvl']]);
							}
							if ($this->vars['votereward_config']['req_res'] > $res_total) {
								json(['error' => __('Your character total res sum need to be atleast') . ' ' . $this->vars['votereward_config']['req_res']]);
							}
						}
                        if(!$check_link = $this->Maccount->check_vote_link($_POST['vote'])){
                            json(['error' => __('Voting link not found.')]);
                        } else{
                            if($check_link['api'] == 2){
                                json(['success_mmotop' => __('Thank You, we will review your vote and reward you.')]);
                            } else{
                                if($check_last_vote = $this->Maccount->get_last_vote($_POST['vote'], $check_link['hours'], 0, $this->vars['votereward_config']['xtremetop_same_acc_vote'], $this->vars['votereward_config']['xtremetop_link_numbers'])){
                                    json(['error' => sprintf(__('Already voted. Next vote after %s'), $this->Maccount->calculate_next_vote($check_last_vote, $check_link['hours']))]);
                                } else{
                                    if($check_link['api'] == 1){
                                        if($valid_votes = $this->Maccount->check_xtremetop_vote()){
                                            if(!empty($valid_votes)){
                                                $count = count($valid_votes);
                                                $i = 0;
                                                foreach($valid_votes AS $valid){
                                                    $i++;
                                                    $this->Maccount->set_valid_vote_xtremetop($valid['id']);
                                                    $this->Maccount->reward_voter($check_link['reward'], $check_link['reward_type'], $this->session->userdata(['user' => 'server']));
                                                    $this->Maccount->check_vote_rankings($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                                    if($i == $count){
                                                        if($this->Maccount->log_vote($_POST['vote'])){
                                                            json(['success' => vsprintf(__('Vote was successful. You have received %d %s'), [$check_link['reward'], $this->website->translate_credits($check_link['reward_type'], $this->session->userdata(['user' => 'server']))]), 'next_vote' => $this->Maccount->calculate_next_vote((time() - 60), $check_link['hours']), 'reward' => $check_link['reward']]);
                                                        } else{
                                                            json(['error' => __('Unable to log vote. Please try again latter')]);
                                                        }
                                                    }
                                                }
                                            } else{
                                                json(['error' => __('Unable to log vote. Please try again latter')]);
                                            }
                                        } else{
                                            json(['error' => __('Unable to validate vote. Please try again after few minutes.')]);
                                        }
                                    } else if($check_link['api'] == 3){
                                        if($valid_votes = $this->Maccount->check_gtop100_vote()){
                                            if(!empty($valid_votes)){
                                                $count = count($valid_votes);
                                                $i = 0;
                                                foreach($valid_votes AS $valid){
                                                    $i++;
                                                    $this->Maccount->set_valid_vote_gtop100($valid['id']);
                                                    $this->Maccount->reward_voter($check_link['reward'], $check_link['reward_type'], $this->session->userdata(['user' => 'server']));
                                                    $this->Maccount->check_vote_rankings($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                                    if($i == $count){
                                                        if($this->Maccount->log_vote($_POST['vote'])){
                                                            json(['success' => vsprintf(__('Vote was successful. You have received %d %s'), [$check_link['reward'], $this->website->translate_credits($check_link['reward_type'], $this->session->userdata(['user' => 'server']))]), 'next_vote' => $this->Maccount->calculate_next_vote((time() - 60), $check_link['hours']), 'reward' => $check_link['reward']]);
                                                        } else{
                                                            json(['error' => __('Unable to log vote. Please try again latter')]);
                                                        }
                                                    }
                                                }
                                            } else{
                                                json(['error' => __('Unable to validate vote. Please try again after few minutes.')]);
                                            }
                                        } else{
                                            json(['error' => __('Unable to validate vote. Please try again after few minutes.')]);
                                        }
                                    } else if($check_link['api'] == 4){
                                        if($valid_votes = $this->Maccount->check_topg_vote()){
                                            if(!empty($valid_votes)){
                                                $count = count($valid_votes);
                                                $i = 0;
                                                foreach($valid_votes AS $valid){
                                                    $i++;
                                                    $this->Maccount->set_valid_vote_topg($valid['id']);
                                                    $this->Maccount->reward_voter($check_link['reward'], $check_link['reward_type'], $this->session->userdata(['user' => 'server']));
                                                    $this->Maccount->check_vote_rankings($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                                    if($i == $count){
                                                        if($this->Maccount->log_vote($_POST['vote'])){
                                                            json(['success' => vsprintf(__('Vote was successful. You have received %d %s'), [$check_link['reward'], $this->website->translate_credits($check_link['reward_type'], $this->session->userdata(['user' => 'server']))]), 'next_vote' => $this->Maccount->calculate_next_vote((time() - 60), $check_link['hours']), 'reward' => $check_link['reward']]);
                                                        } else{
                                                            json(['error' => __('Unable to log vote. Please try again latter')]);
                                                        }
                                                    }
                                                }
                                            } else{
                                                json(['error' => __('Unable to validate vote. Please try again after few minutes.')]);
                                            }
                                        } else{
                                            json(['error' => __('Unable to validate vote. Please try again after few minutes.')]);
                                        }
                                    } else if($check_link['api'] == 5){
                                        if($valid = $this->Maccount->check_top100arena_vote()){
                                            if($this->Maccount->log_vote($_POST['vote']) && $this->Maccount->set_valid_vote_top100arena($valid['id'])){
                                                $this->Maccount->reward_voter($check_link['reward'], $check_link['reward_type'], $this->session->userdata(['user' => 'server']));
                                                $this->Maccount->check_vote_rankings($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                                json(['success' => vsprintf(__('Vote was successful. You have received %d %s'), [$check_link['reward'], $this->website->translate_credits($check_link['reward_type'], $this->session->userdata(['user' => 'server']))]), 'next_vote' => $this->Maccount->calculate_next_vote((time() - 60), $check_link['hours']), 'reward' => $check_link['reward']]);
                                            } else{
                                                json(['error' => __('Unable to log vote. Please try again latter')]);
                                            }
                                        } else{
                                            json(['error' => __('Unable to validate vote. Please try again after few minutes.')]);
                                        }
                                    } else if($check_link['api'] == 6){
                                        if($valid = $this->Maccount->check_mmoserver_vote()){
                                            if($this->Maccount->log_vote($_POST['vote']) && $this->Maccount->set_valid_vote_mmoserver($valid['id'])){
                                                $this->Maccount->reward_voter($check_link['reward'], $check_link['reward_type'], $this->session->userdata(['user' => 'server']));
                                                $this->Maccount->check_vote_rankings($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                                json(['success' => vsprintf(__('Vote was successful. You have received %d %s'), [$check_link['reward'], $this->website->translate_credits($check_link['reward_type'], $this->session->userdata(['user' => 'server']))]), 'next_vote' => $this->Maccount->calculate_next_vote((time() - 60), $check_link['hours']), 'reward' => $check_link['reward']]);
                                            } else{
                                                json(['error' => __('Unable to log vote. Please try again latter')]);
                                            }
                                        } else{
                                            json(['error' => __('Unable to validate vote. Please try again after few minutes.')]);
                                        }
                                    } else if($check_link['api'] == 8){
                                        if($valid_votes = $this->Maccount->check_ultratop_vote()){
                                            if(!empty($valid_votes)){
                                                $count = count($valid_votes);
                                                $i = 0;
                                                foreach($valid_votes AS $valid){
                                                    $i++;
                                                    $this->Maccount->set_valid_vote_ultratop($valid['id']);
                                                    $this->Maccount->reward_voter($check_link['reward'], $check_link['reward_type'], $this->session->userdata(['user' => 'server']));
                                                    $this->Maccount->check_vote_rankings($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                                    if($i == $count){
                                                        if($this->Maccount->log_vote($_POST['vote'])){
                                                            json(['success' => vsprintf(__('Vote was successful. You have received %d %s'), [$check_link['reward'], $this->website->translate_credits($check_link['reward_type'], $this->session->userdata(['user' => 'server']))]), 'next_vote' => $this->Maccount->calculate_next_vote((time() - 60), $check_link['hours']), 'reward' => $check_link['reward']]);
                                                        } else{
                                                            json(['error' => __('Unable to log vote. Please try again latter')]);
                                                        }
                                                    }
                                                }
                                            } else{
                                                json(['error' => __('Unable to log vote. Please try again latter')]);
                                            }
                                        } else{
                                            json(['error' => __('Unable to validate vote. Please try again after few minutes.')]);
                                        }
                                    } else if($check_link['api'] == 9){
                                        if($valid_votes = $this->Maccount->check_gametop100_vote()){
                                            if(!empty($valid_votes)){
                                                $count = count($valid_votes);
                                                $i = 0;
                                                foreach($valid_votes AS $valid){
                                                    $i++;
                                                    $this->Maccount->set_valid_vote_gametop100($valid['id']);
                                                    $this->Maccount->reward_voter($check_link['reward'], $check_link['reward_type'], $this->session->userdata(['user' => 'server']));
                                                    $this->Maccount->check_vote_rankings($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                                    if($i == $count){
                                                        if($this->Maccount->log_vote($_POST['vote'])){
                                                            json(['success' => vsprintf(__('Vote was successful. You have received %d %s'), [$check_link['reward'], $this->website->translate_credits($check_link['reward_type'], $this->session->userdata(['user' => 'server']))]), 'next_vote' => $this->Maccount->calculate_next_vote((time() - 60), $check_link['hours']), 'reward' => $check_link['reward']]);
                                                        } else{
                                                            json(['error' => __('Unable to log vote. Please try again latter')]);
                                                        }
                                                    }
                                                }
                                            } else{
                                                json(['error' => __('Unable to log vote. Please try again latter')]);
                                            }
                                        } else{
                                            json(['error' => __('Unable to validate vote. Please try again after few minutes.')]);
                                        }
                                    } else{
                                        if($this->Maccount->log_vote($_POST['vote'])){
                                            $this->Maccount->reward_voter($check_link['reward'], $check_link['reward_type'], $this->session->userdata(['user' => 'server']));
                                            $this->Maccount->check_vote_rankings($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                            json(['success' => vsprintf(__('Vote was successful. You have received %d %s'), [$check_link['reward'], $this->website->translate_credits($check_link['reward_type'], $this->session->userdata(['user' => 'server']))]), 'next_vote' => $this->Maccount->calculate_next_vote((time() - 60), $check_link['hours']), 'reward' => $check_link['reward']]);
                                        } else{
                                            json(['error' => __('Unable to log vote. Please try again latter')]);
                                        }
                                    }
                                }
                            }
                        }
                    } else{
                        json(['error' => __('Invalid voting link.')]);
                    }
                } else{
                    json(['error' => __('Module disabled.')]);
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function reset_character()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                $this->load->model('account');
                $this->load->model('character');
                foreach($_POST as $key => $value){
                    if($key == 'character'){
                        $this->Mcharacter->$key = trim($this->website->hex2bin($value));
                    } else{
                        $this->Mcharacter->$key = trim($value);
                    }
                }

				usleep(mt_rand(1000000, 5000000));
				
                if(!$this->Maccount->check_connect_stat())
                    json(['error' => __('Please logout from game.')]); else{
                    if(!isset($_POST['character']) || $_POST['character'] == ''){
                        json(['error' => __('Invalid Character')]);
                    } else{
                        if(!$this->Mcharacter->check_char())
                            json(['error' => __('Character not found.')]); else{
                            $reset_config = $this->config->values('reset_config', $this->session->userdata(['user' => 'server']));
                            $greset_config = $this->config->values('greset_config', $this->session->userdata(['user' => 'server']));
                            if(!$reset_config)
                                json(['error' => __('Reset configuration for this server not found.')]); else{
                                if($reset_config['allow_reset'] == 0)
                                    json(['error' => __('Reset function is disabled for this server')]); else{
                                    unset($reset_config['allow_reset']);
                                    if(isset($greset_config)){
                                        unset($greset_config['allow_greset']);
                                    }
																 
                                    foreach($reset_config AS $key => $values){
                                        list($start_res, $end_res) = explode('-', $key);
                                        if($this->Mcharacter->char_info['resets'] >= $start_res && $this->Mcharacter->char_info['resets'] < $end_res){
                                            $this->Mcharacter->char_info['res_info'] = $values;
                                        }
                                    }
                                    $this->Mcharacter->char_info['bonus_greset_stats_points'] = 0;
                                    if(isset($this->Mcharacter->char_info['res_info'])){
                                        if($this->Mcharacter->char_info['res_info']['bonus_gr_points'] == 1){
                                            $greset_bonus_data = [];
                                            $greset_bonus_info = [];
                                            foreach($greset_config AS $key => $values){
                                                $greset_range = explode('-', $key);
                                                for($i = $greset_range[0]; $i < $greset_range[1]; $i++){
                                                    $greset_bonus_data[$i] = $values['bonus_points'];
                                                    $greset_bonus_info[$i] = $values['bonus_points_save'];
                                                }
                                            }
                                            foreach($greset_bonus_data AS $gres => $data){
                                                if($this->Mcharacter->char_info['grand_resets'] <= $gres)
                                                    break;
                                                if($greset_bonus_info[$gres] == 1){
                                                    $this->Mcharacter->char_info['bonus_greset_stats_points'] += $data[$this->Mcharacter->class_code_to_readable($this->Mcharacter->char_info['Class'])];
                                                } else{
                                                    $this->Mcharacter->char_info['bonus_greset_stats_points'] = $data[$this->Mcharacter->class_code_to_readable($this->Mcharacter->char_info['Class'])];
                                                }
                                            }
                                        }
																					  
                                        if($this->Mcharacter->char_info['res_info']['clear_equipment'] == 1){							   
                                            if(!$this->Mcharacter->check_equipment()){
                                                json(['error' => __('Before reset please remove your equipped items.')]);
                                                return;
                                            }
                                        }

                                        $next_reset = (int)$this->Mcharacter->char_info['last_reset_time'] + $this->Mcharacter->char_info['res_info']['reset_cooldown'];
                                        if($next_reset > time()){
                                            json(['error' => sprintf(__('You will be able to reset at %s'), date('d/m/Y H:i', $next_reset))]);
                                        } else{
                                            $req_zen = $this->Mcharacter->check_zen($this->Mcharacter->char_info['res_info']['money'], $this->Mcharacter->char_info['res_info']['money_x_reset'], 'resets');
                                            if($req_zen !== true){
                                                $req_zen_wallet = $this->Mcharacter->check_zen_wallet($this->Mcharacter->char_info['res_info']['money'], $this->Mcharacter->char_info['res_info']['money_x_reset'], 'resets');
                                                if($req_zen_wallet !== true){
                                                    json(['error' => sprintf(__('Your have insufficient amount of zen. Need: %s'), $this->website->zen_format($req_zen))]);
                                                    return;
                                                }
                                            }
                                            $req_lvl = $this->Mcharacter->check_lvl($this->Mcharacter->char_info['res_info']['level']);
                                            if($req_lvl !== true)
                                                json(['error' => sprintf(__('Your lvl is too low. You need %d lvl.'), $req_lvl)]); else{
                                                if($this->Mcharacter->reset_character()){
                                                    json(['success' => __('Your character has been successfully reseted.')]);
                                                } else{
                                                    json(['error' => __('Unable to reset character.')]);
                                                }
                                            }
                                        }
                                    } else{
                                        json(['error' => __('Reset Disabled')]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        public function add_level_reward()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                $this->load->model('account');
                $this->load->model('character');
                $id = (isset ($_POST['id']) && preg_match('/^\d*$/', $_POST['id'])) ? $_POST['id'] : '';
                $char = isset($_POST['char']) ? $_POST['char'] : '';
                $level_rewards = $this->config->values('level_rewards_config');
                if($id == '')
                    json(['error' => __('Invalid level reward id.')]); else{
                    if($level_rewards == false || !array_key_exists($id, $level_rewards))
                        json(['error' => __('Level reward not found.')]); 
					else{
                        if($char == '')
                            json(['error' => __('Invalid Character')]); else{
                            if(!$this->Mcharacter->check_char($char))
                                json(['error' => __('Character not found.')]); else{
                                if($level_rewards[$id]['req_level'] > $this->Mcharacter->char_info['cLevel'])
                                    json(['error' => sprintf(__('Character lvl is too low required %d lvl'), $level_rewards[$id]['req_level'])]); else{
                                    if($level_rewards[$id]['req_mlevel'] > $this->Mcharacter->char_info['mlevel'])
                                        json(['error' => sprintf(__('Character master lvl is too low required %d lvl'), $level_rewards[$id]['req_mlevel'])]); else{
                                        if($this->Mcharacter->check_claimed_level_rewards($id, $char, $this->session->userdata(['user' => 'server']))){
                                            json(['error' => __('Reward was already claimed with this character.')]);
                                        } else{
                                            $this->website->add_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $level_rewards[$id]['reward'], $level_rewards[$id]['reward_type']);
                                            $this->Maccount->add_account_log('Claimed level reward from character ' . $char . ' for ' . $this->website->translate_credits($level_rewards[$id]['reward_type']), $level_rewards[$id]['reward'], $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                            $this->Mcharacter->log_level_reward($id, $char, $this->session->userdata(['user' => 'server']));
                                            json(['success' => __('Referral reward was claimed successfully.')]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function add_ref_reward()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                $this->load->model('account');
                $this->load->model('character');
                $id = (isset ($_POST['id']) && preg_match('/^\d*$/', $_POST['id'])) ? $_POST['id'] : '';
                $char = isset($_POST['char']) ? $_POST['char'] : '';
														  															
                if($id == '')
                    json(['error' => __('Invalid referral reward id.')]); else{
                    if(!$reward_data = $this->Maccount->check_referral_reward($id, $this->session->userdata(['user' => 'server'])))
                        json(['error' => __('Referral reward not found.')]); else{
                        if($char == '')
                            json(['error' => __('Invalid Character')]); else{
                            if(!$this->Mcharacter->check_char_no_account($char, $this->session->userdata(['user' => 'server'])))
                                json(['error' => __('Character not found.')]); else{
                                if($reward_data['required_lvl'] > $this->Mcharacter->char_info['cLevel'] + $this->Mcharacter->char_info['mlevel'])
                                    json(['error' => sprintf(__('Character lvl is too low required %d lvl'), $reward_data['required_lvl'])]); else{
                                    if(!$this->check_ref_req_resets($reward_data['required_res'], $this->Mcharacter->char_info))
                                        json(['error' => sprintf(__('Character reset is too low required %d reset'), $reward_data['required_res'])]); else{
                                        if(!$this->check_ref_req_gresets($reward_data['required_gres'], $this->Mcharacter->char_info))
                                            json(['error' => sprintf(__('Character grand reset is too low required %d grand reset'), $reward_data['required_gres'])]); else{
                                            $history = $this->Maccount->check_name_in_history($char, $reward_data['server']);
                                            if(!empty($history)){
                                                $check_chars = [$char];
                                                foreach($history AS $names){
                                                    $check_chars[] = $names['old_name'];
                                                    $check_chars[] = $names['new_name'];					   
                                                }
                                                $check_chars = array_unique($check_chars);
                                            } else{
                                                $check_chars = [$char];
                                            }
                                            if($this->Maccount->check_claimed_referral_rewards($reward_data['id'], $check_chars, $reward_data['server'])){
                                                json(['error' => __('Reward was already claimed with this character.')]);
                                            } else{
                                                if($this->config->values('referral_config', 'claim_type') == 0){
                                                    if($this->Maccount->check_if_reward_was_claimed($reward_data['id'], $reward_data['server'], $this->Mcharacter->char_info['AccountId'])){
                                                        json(['error' => __('Reward can be claimed only once. It was already claimed by different character.')]);
                                                        return; 
                                                    }
                                                }
                                                if($this->config->values('referral_config', 'compare_ips') == 1){
                                                    if($this->Maccount->check_referral_ips($this->Mcharacter->char_info['AccountId'])){
                                                        json(['error' => __('You can not claim rewards for own accounts.')]);
                                                        return; 
                                                    }																		 
                                                }
                                                $this->Maccount->add_referral_reward($reward_data['reward'], $reward_data['reward_type'], $char);
                                                $this->Maccount->log_reward($reward_data['id'], $char, $reward_data['server'], $this->Mcharacter->char_info['AccountId']);
                                                json(['success' => __('Referral reward was claimed successfully.')]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        private function check_ref_req_resets($req, $char_info)
        {
            if($req > 0){
                if($req > $char_info['resets']){
                    return false;
                }
            }
            return true;
        }

        private function check_ref_req_gresets($req, $char_info)
        {
            if($req > 0){
                if($req > $char_info['grand_resets']){
                    return false;
                }
            }
            return true;
        }

        public function greset_character()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                $this->load->model('account');
                $this->load->model('character');
                foreach($_POST as $key => $value){
                    if($key == 'character'){
                        $this->Mcharacter->$key = trim($this->website->hex2bin($value));
                    } else{
                        $this->Mcharacter->$key = trim($value);
                    }
                }
				
				usleep(mt_rand(1000000, 5000000));
				
                if(!$this->Maccount->check_connect_stat())
                    json(['error' => __('Please logout from game.')]); else{
                    if(!isset($_POST['character']) || $_POST['character'] == ''){
                        json(['error' => __('Invalid Character')]);
                    } else{
                        if(!$this->Mcharacter->check_char())
                            json(['error' => __('Character not found.')]); else{
                            $reset_config = $this->config->values('reset_config', $this->session->userdata(['user' => 'server']));
                            $greset_config = $this->config->values('greset_config', $this->session->userdata(['user' => 'server']));
                            if(!$greset_config)
                                json(['error' => __('Grand Reset configuration for this server not found.')]); else{
                                if($greset_config['allow_greset'] == 0)
                                    json(['error' => __('Grand Reset function is disabled for this server')]); else{
                                    unset($greset_config['allow_greset']);
                                    if(isset($reset_config)){
                                        unset($reset_config['allow_reset']);
                                    }
																  
                                    foreach($greset_config AS $key => $values){
                                        list($start_gres, $end_gres) = explode('-', $key);
                                        if($this->Mcharacter->char_info['grand_resets'] >= $start_gres && $this->Mcharacter->char_info['grand_resets'] < $end_gres){
                                            $this->Mcharacter->char_info['gres_info'] = $values;
                                        }
                                    }
                                    $this->Mcharacter->char_info['bonus_reset_stats_points'] = 0;
                                    if(isset($this->Mcharacter->char_info['gres_info'])){
                                        if($this->Mcharacter->char_info['gres_info']['bonus_reset_stats'] == 1){
                                            $reset_data = [];
                                            foreach($reset_config AS $key => $values){
                                                $reset_range = explode('-', $key);
                                                for($i = $reset_range[0]; $i < $reset_range[1]; $i++){
                                                    $reset_data[$i] = $values['bonus_points'];
                                                }
                                            }
										 
                                            foreach($reset_data AS $res => $data){
                                                if($this->Mcharacter->char_info['resets'] <= $res)
                                                    break;
                                                $this->Mcharacter->char_info['bonus_reset_stats_points'] += $data[$this->Mcharacter->class_code_to_readable($this->Mcharacter->char_info['Class'])];
                                            }
                                        }
									 
																						 
                                        $req_zen = $this->Mcharacter->check_zen($this->Mcharacter->char_info['gres_info']['money'], $this->Mcharacter->char_info['gres_info']['money_x_reset'], 'grand_resets');
                                        if($req_zen !== true){
                                            $req_zen_wallet = $this->Mcharacter->check_zen_wallet($this->Mcharacter->char_info['gres_info']['money'], $this->Mcharacter->char_info['gres_info']['money_x_reset'], 'grand_resets');
                                            if($req_zen_wallet !== true){
                                                json(['error' => sprintf(__('Your have insufficient amount of zen. Need: %s'), $this->website->zen_format($req_zen))]);
                                                return;
                                            }
                                        }
                                        if(!$this->Mcharacter->check_lvl($this->Mcharacter->char_info['gres_info']['level']))
                                            json(['error' => sprintf(__('Your lvl is too low. You need %d lvl.'), $this->Mcharacter->char_info['gres_info']['level'])]); else{
                                            if(!$this->Mcharacter->check_resets($this->Mcharacter->char_info['gres_info']['reset']))
                                                json(['error' => sprintf(__('Your resets is too low. You need %d resets.'), $this->Mcharacter->char_info['gres_info']['reset'])]); else{
                                                if($this->Mcharacter->greset_character()){
                                                    json(['success' => __('Your character has been successfully reseted.')]);
                                                } else{
                                                    json(['error' => __('Unable to reset character.')]);
                                                }
                                            }
                                        }
                                    } else{
                                        json(['error' => __('GrandReset Disabled')]);
                                    }
                                }
                            }
                        }
                    }
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function pk_clear()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                $this->load->model('account');
                $this->load->model('character');
                foreach($_POST as $key => $value){
					if($key == 'character'){
                        $this->Mcharacter->$key = trim($this->website->hex2bin($value));
                    }																	
                }
                if(!$this->Maccount->check_connect_stat())
                    json(['error' => __('Please logout from game.')]); 
				else{
                    if(!isset($_POST['character']))
                        json(['error' => __('Invalid Character')]); 
					else{
                        if(!$this->Mcharacter->check_char())
                            json(['error' => __('Character not found.')]); 
						else{
                            if(!$this->Mcharacter->check_pk())
                                json(['error' => __('You are not a murder.')]); 
							else{
                                $price = $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|pk_clear_price');
								$method = $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|pk_clear_payment_method');
								
                                if($this->session->userdata('vip')){
                                    $price -= $this->session->userdata(['vip' => 'pk_clear_discount']);
                                }
								if($method == 0){
									if($this->Mcharacter->char_info['Money'] < $price)
										json(['error' => sprintf(__('Your have insufficient amount of zen. Need: %s'), $this->website->zen_format($price))]); 
									else{
										$this->Mcharacter->clear_pk($price);
										json(['success' => __('Your murders have been successfully reseted.')]);
									}
								}
								else{
									if(in_array($method, [1,2])){
										 $status = $this->website->get_user_credits_balance($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $method, $this->session->userdata(['user' => 'id']));
										 if($status['credits'] < $price){
											json(['error' => sprintf(__('You have insufficient amount of %s'), $this->website->translate_credits($method, $this->session->userdata(['user' => 'server'])))]);
										 }
										 else{
											$this->Mcharacter->clear_pk(0);
											$this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $price, $method);
											json(['success' => __('Your murders have been successfully reseted.')]);
										 }
									}
									else{
										 $this->vars['table_config'] = $this->config->values('table_config', $this->session->userdata(['user' => 'server']));
										 
										 if($status = $this->Mcharacter->get_wcoins($this->vars['table_config']['wcoins'], $this->session->userdata(['user' => 'server']))){
											if($status < $price)
												json(['error' => sprintf(__('You have insufficient amount of %s'), __('WCoins'))]);
											else{
												$this->Mcharacter->clear_pk(0);
												$this->Mcharacter->remove_wcoins($this->vars['table_config']['wcoins'], $price);
												json(['success' => __('Your murders have been successfully reseted.')]);
											}
										} else{
											json(['error' => __('Unable to load wcoins')]);
										}
									}
								}
                            }
                        }
                    }
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function reset_stats()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                if($this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|allow_reset_stats') == 1){
                    $this->load->model('account');
                    $this->load->model('character');
                    foreach($_POST as $key => $value){
                        if($key == 'character'){
                            $this->Mcharacter->$key = trim($this->website->hex2bin($value));
                        } else{
                            $this->Mcharacter->$key = trim($value);
                        }
                    }
                    if(!$this->Maccount->check_connect_stat())
                        json(['error' => __('Please logout from game.')]); 
					else{
                        if(!isset($_POST['character']))
                            json(['error' => __('Invalid Character')]); 
						else{
                            if(!$this->Mcharacter->check_char())
                                json(['error' => __('Character not found.')]); 
							else{
                                if($this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|reset_stats_price') > 0){
                                    $status = $this->website->get_user_credits_balance($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|reset_stats_payment_type'), $this->session->userdata(['user' => 'id']));
                                    if($status['credits'] < $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|reset_stats_price')){
                                        json(['error' => sprintf(__('You have insufficient amount of %s'), $this->website->translate_credits($this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|reset_stats_payment_type'), $this->session->userdata(['user' => 'server'])))]);
                                    } else{
                                        $this->Mcharacter->reset_stats();
                                        $this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|reset_stats_price'), $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|reset_stats_payment_type'));
                                        $this->Mcharacter->add_account_log('Cleared character ' . $this->website->hex2bin($_POST['character']) . ' stats for ' . $this->website->translate_credits($this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|reset_stats_payment_type'), $this->session->userdata(['user' => 'server'])) . '', -$this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|reset_stats_price'), $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                        json(['success' => __('Stats successfully reseted.')]);
                                    }
                                } else{
                                    $this->Mcharacter->reset_stats();
                                    json(['success' => __('Stats successfully reseted.')]);
                                }
                            }
                        }
                    }
                } else{
                    json(['error' => __('Reset Stats Disabled')]);
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function reset_skilltree()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                if($this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|allow_reset_skilltree') == 1){
					$this->load->model('account');
					$this->load->model('character');
					foreach($_POST as $key => $value){
						if($key == 'character'){
							$this->Mcharacter->$key = trim(hex2bin($value));
						} else{
							$this->Mcharacter->$key = trim($value);
						}
					}
					if(!$this->Maccount->check_connect_stat())
						json(['error' => __('Please logout from game.')]); 
					else{
						if(!isset($_POST['character']))
							json(['error' => __('Invalid Character')]); 
						else{
							if(!$this->Mcharacter->check_char())
								json(['error' => __('Character not found.')]); 
							else{
								if(!in_array($this->Mcharacter->char_info['Class'], $this->resetSkillTreeClass))
									json(['error' => __('Your class is not allowed to reset skilltree.')]); else{
									$status = $this->Maccount->get_amount_of_credits($this->session->userdata(['user' => 'username']), $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|skilltree_reset_price_type'), $this->session->userdata(['user' => 'server']), $this->session->userdata(['user' => 'id']));
									$price = $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|skilltree_reset_price');
									if($this->session->userdata('vip')){
										$price -= ($price / 100) * $this->session->userdata(['vip' => 'clear_skilltree_discount']);
									}
									if($status < $price){
										json(['error' => sprintf(__('You have insufficient amount of %s'), $this->website->translate_credits($this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|skilltree_reset_price_type'), $this->session->userdata(['user' => 'server'])))]);
									} else{
										$skill_tree = $this->Mcharacter->reset_skill_tree($this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|skill_tree_type'), $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|skilltree_reset_level'), $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|skilltree_reset_points'), $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|skilltree_points_multiplier'));
										if($skill_tree){
											$this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $price, $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|skilltree_reset_price_type'));
											$this->Mcharacter->add_account_log('Cleared character ' . $this->website->hex2bin($_POST['character']) . ' skill tree for ' . $this->website->translate_credits($this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|skilltree_reset_price_type'), $this->session->userdata(['user' => 'server'])), -$price, $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
											json(['success' => __('SkillTree successfully reseted.')]);
										} else{
											json(['error' => __('Unable to reset skilltree.')]);
										}
									}
								}
							}
						}
					}
				}
				else{
					json(['error' => __('Reset SkillTree Disabled')]);
				}
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function clear_inventory()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                $this->load->model('account');
                $this->load->model('character');
                foreach($_POST as $key => $value){
                    $this->Mcharacter->$key = trim($value);
                }
                if(!$this->Maccount->check_connect_stat())
                    json(['error' => __('Please logout from game.')]); 
								else{
                    if(!isset($this->Mcharacter->vars['character']))
                        json(['error' => __('Invalid Character')]); 
											else{
                        if(!isset($this->Mcharacter->vars['inventory']) && !isset($this->Mcharacter->vars['equipment']) && !isset($this->Mcharacter->vars['store']) && !isset($this->Mcharacter->vars['exp_inv_1']) && !isset($this->Mcharacter->vars['exp_inv_2']))
                            json(['error' => __('Please select one of options.')]); 
													else{
                            if(!$this->Mcharacter->check_char())
                                json(['error' => __('Character not found.')]); 
															else{
                                $this->Mcharacter->clear_inv();
                                json(['success' => __('Character inventory successfully cleared.')]);
                            }
                        }
                    }
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function buy_level()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                $this->load->model('account');
                $this->load->model('character');
                $level_conf = $this->config->values('buylevel_config', $this->session->userdata(['user' => 'server']));
                foreach($_POST as $key => $value){
                    $this->Mcharacter->$key = trim($value);
                }
                if(!$this->Maccount->check_connect_stat())
                    json(['error' => __('Please logout from game.')]); else{
                    if(!isset($this->Mcharacter->vars['character']))
                        json(['error' => __('Invalid Character')]); else{
                        if(!isset($this->Mcharacter->vars['level']))
                            json(['error' => __('Please select level.')]); else{
                            if(!$this->Mcharacter->check_char())
                                json(['error' => __('Character not found.')]); else{
                                if(!array_key_exists($this->Mcharacter->vars['level'], $level_conf['levels']))
                                    json(['error' => __('Invalid level selected.')]); else{
                                    if(!$this->check_max_level_allowed($level_conf, $this->Mcharacter->vars['level'], $this->Mcharacter->char_info['cLevel']))
                                        json(['error' => sprintf(__('You will exceed max level allowed: %d, please try to buy lower level.'), isset($level_conf['max_level']) ? $level_conf['max_level'] : 0)]); else{
                                        $status = $this->Maccount->get_amount_of_credits($this->session->userdata(['user' => 'username']), $level_conf['levels'][$this->Mcharacter->vars['level']]['payment_type'], $this->session->userdata(['user' => 'server']), $this->session->userdata(['user' => 'id']));
                                        if($status < $level_conf['levels'][$this->Mcharacter->vars['level']]['price']){
                                            json(['error' => sprintf(__('You have insufficient amount of %s'), $this->website->translate_credits($level_conf['levels'][$this->Mcharacter->vars['level']]['payment_type'], $this->session->userdata(['user' => 'server'])))]);
                                        } else{
                                            if($this->Mcharacter->update_level()){
                                                $this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $level_conf['levels'][$this->Mcharacter->vars['level']]['price'], $level_conf['levels'][$this->Mcharacter->vars['level']]['payment_type']);
                                                json(['success' => __('Character level updated.')]);
                                            } else{
                                                json(['error' => __('Unable to update character level.')]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        private function check_max_level_allowed($level_config, $levels_to_add, $char_level)
        {
            if($level_config != false){
                if(isset($level_config['max_level'])){
                    $new_char_level = $levels_to_add + $char_level;
                    if($new_char_level <= $level_config['max_level'])
                        return true;
                }
            }
            return false;
        }

        public function buy_points()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                $this->load->model('account');
                $this->load->model('character');
                foreach($_POST as $key => $value){
                    $this->Mcharacter->$key = trim($value);
                }
                if(!$this->Maccount->check_connect_stat())
                    json(['error' => __('Please logout from game.')]); else{
                    if(!isset($this->Mcharacter->vars['character']))
                        json(['error' => __('Invalid Character')]); else{
                        if(!isset($this->Mcharacter->vars['points']))
                            json(['error' => __('Please enter amount of points.')]); else{
                            if(!$this->Mcharacter->check_char())
                                json(['error' => __('Character not found.')]); else{
                                if($this->Mcharacter->vars['points'] < $this->config->config_entry('buypoints|points'))
                                    json(['error' => __('Minimal points value: %d points.', $this->config->config_entry('buypoints|points'))]); else{
                                    $status = $this->Maccount->get_amount_of_credits($this->session->userdata(['user' => 'username']), $this->config->config_entry('buypoints|price_type'), $this->session->userdata(['user' => 'server']), $this->session->userdata(['user' => 'id']));
                                    $price = ceil(($this->Mcharacter->vars['points'] * $this->config->config_entry('buypoints|price')) / $this->config->config_entry('buypoints|points'));
                                    if($status < $price){
                                        json(['error' => sprintf(__('You have insufficient amount of %s'), $this->website->translate_credits($this->config->config_entry('buypoints|price_type'), $this->session->userdata(['user' => 'server'])))]);
                                    } else{
                                        if($this->Mcharacter->update_points()){
                                            $this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $price, $this->config->config_entry('buypoints|price_type'));
                                            json(['success' => __('Character statpoints updated.')]);
                                        } else{
                                            json(['error' => __('Unable to update character statpoints.')]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function buy_gm()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                $this->load->model('account');
                $this->load->model('character');
                foreach($_POST as $key => $value){
                    $this->Mcharacter->$key = trim($value);
                }
                if(!$this->Maccount->check_connect_stat())
                    json(['error' => __('Please logout from game.')]); else{
                    if(!isset($this->Mcharacter->vars['character']))
                        json(['error' => __('Invalid Character')]); else{
                        if(!$this->Mcharacter->check_char())
                            json(['error' => __('Character not found.')]); else{
                            if($this->Mcharacter->char_info['CtlCode'] == $this->config->config_entry('buygm|gm_ctlcode'))
                                json(['error' => __('Your character already is GameMaster.')]); else{
                                $status = $this->Maccount->get_amount_of_credits($this->session->userdata(['user' => 'username']), $this->config->config_entry('buygm|price_t'), $this->session->userdata(['user' => 'server']), $this->session->userdata(['user' => 'id']));
                                if($status < $this->config->config_entry('buygm|price')){
                                    json(['error' => sprintf(__('You have insufficient amount of %s'), $this->website->translate_credits($this->config->config_entry('buygm|price_t'), $this->session->userdata(['user' => 'server'])))]);
                                } else{
                                    if($this->Mcharacter->update_gm()){
                                        $this->Maccount->add_account_log('Bought GM Status For ' . $this->website->translate_credits($this->config->config_entry('buygm|price_t'), $this->session->userdata(['user' => 'server'])), -$this->config->config_entry('buygm|price'), $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                        $this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $this->config->config_entry('buygm|price'), $this->config->config_entry('buygm|price_t'));
                                        json(['success' => __('Character successfully promoted to GameMaster.')]);
                                    } else{
                                        json(['error' => __('Unable to update character gm status.')]);
                                    }
                                }
                            }
                        }
                    }
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function load_class_list()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                $this->load->model('account');
                $this->load->model('character');
                foreach($_POST as $key => $value){
                    $this->Mcharacter->$key = trim($value);
                }
                if(!$this->Maccount->check_connect_stat())
                    json(['error' => __('Please logout from game.')]); else{
                    if(!isset($this->Mcharacter->vars['character']))
                        json(['error' => __('Invalid Character')]); else{
                        if(!$this->Mcharacter->check_char())
                            json(['error' => __('Character not found.')]); else{
                            if($select = $this->Mcharacter->gen_class_select_field($this->config->values('change_class_config', 'class_list'))){
                                json(['data' => $select]);
                            } else{
                                json(['error' => __('This character is not allowed to change class.')]);
                            }
                        }
                    }
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function buy_class()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                $this->load->model('account');
                $this->load->model('character');
                foreach($_POST as $key => $value){
                    $this->Mcharacter->$key = trim($value);
                }
                if(!$this->Maccount->check_connect_stat())
                    json(['error' => __('Please logout from game.')]); else{
                    if(!isset($this->Mcharacter->vars['character']))
                        json(['error' => __('Invalid Character')]); else{
                        if(!$this->Mcharacter->check_char())
                            json(['error' => __('Character not found.')]); else{
                            if(!isset($this->Mcharacter->vars['class_select']))
                                json(['error' => __('Invalid class selected')]); else{
                                if($this->Mcharacter->vars['class_select'] == $this->Mcharacter->char_info['Class'])
                                    json(['error' => __('You already have this class.')]); else{
                                    $this->vars['changeclass_config'] = $this->config->values('change_class_config');
                                    $status = $this->Maccount->get_amount_of_credits($this->session->userdata(['user' => 'username']), $this->vars['changeclass_config']['payment_type'], $this->session->userdata(['user' => 'server']), $this->session->userdata(['user' => 'id']));
                                    $price = $this->vars['changeclass_config']['price'];
                                    if($this->session->userdata('vip')){
                                        $price -= ($price / 100) * $this->session->userdata(['vip' => 'change_class_discount']);
                                    }
                                    if($status < $price){
                                        json(['error' => sprintf(__('You have insufficient amount of %s'), $this->website->translate_credits($this->vars['changeclass_config']['payment_type'], $this->session->userdata(['user' => 'server'])))]);
                                    } else{
                                        if($this->Mcharacter->check_equipment()){
                                            $this->Mcharacter->gen_class_select_field();
                                            if(isset($this->vars['changeclass_config']['class_list'][$this->Mcharacter->char_info['Class']]) && in_array($this->Mcharacter->vars['class_select'], $this->vars['changeclass_config']['class_list'][$this->Mcharacter->char_info['Class']])){
                                                if(isset($this->vars['changeclass_config']['skill_tree']['active']) && $this->vars['changeclass_config']['skill_tree']['active'] == 1){
                                                    if(in_array($this->Mcharacter->char_info['Class'], $this->resetSkillTreeClass)){
                                                        $this->Mcharacter->reset_skill_tree($this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|skill_tree_type'), isset($this->vars['changeclass_config']['skill_tree']['reset_level']) ? $this->vars['changeclass_config']['skill_tree']['reset_level'] : 0, isset($this->vars['changeclass_config']['skill_tree']['reset_points']) ? $this->vars['changeclass_config']['skill_tree']['reset_points'] : 0, isset($this->vars['changeclass_config']['skill_tree']['points_multiplier']) ? $this->vars['changeclass_config']['skill_tree']['points_multiplier'] : 0);
                                                    }
                                                }
                                                $this->Mcharacter->update_char_class();
                                                $this->Maccount->add_account_log('Changed Character ' . $this->Mcharacter->char_info['Name'] . ' class for ' . $this->website->translate_credits($this->vars['changeclass_config']['payment_type'], $this->session->userdata(['user' => 'server'])), -$price, $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                                $this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $price, $this->vars['changeclass_config']['payment_type']);
                                                json(['success' => __('Character class successfully changed.')]);
                                            } else{
                                                json(['error' => __('You are not allowed to use this class.')]);
                                            }
                                        } else{
                                            json(['error' => __('Before changing class please remove your equipped items.')]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function change_name()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                $this->load->model('account');
                $this->load->model('character');
                foreach($_POST as $key => $value){
                    $this->Mcharacter->$key = trim($value);
                }
                if(!$this->Maccount->check_connect_stat())
                    json(['error' => __('Please logout from game.')]); else{
                    if(!isset($this->Mcharacter->vars['old_name']) || $this->Mcharacter->vars['old_name'] == ''){
                        json(['error' => __('Old name can not be empty.')]);
                    } else{
                        if(!isset($this->Mcharacter->vars['new_name']) || $this->Mcharacter->vars['new_name'] == ''){
                            json(['error' => __('New name can not be empty.')]);
                        } else{
							//if(!preg_match('/^[\p{L}]+$/u', $this->Mcharacter->vars['new_name'])){
                            //if(!preg_match('/^[' . str_replace('/', '\/', $this->config->config_entry('changename|allowed_pattern')) . ']+$/u', $this->Mcharacter->vars['new_name'])){
                             //   json(['error' => __('You are using forbidden chars in your new name.')]);
                            //} else{
                                if(mb_strlen($this->Mcharacter->vars['new_name']) < 2 || mb_strlen($this->Mcharacter->vars['new_name']) > $this->config->config_entry('changename|max_length')){
                                    json(['error' => sprintf(__('Character Name can be 2-%d chars long!'), $this->config->config_entry('changename|max_length'))]);
                                } else{
                                    if($this->Mcharacter->vars['new_name'] === $this->website->hex2bin($this->Mcharacter->vars['old_name'])){
                                        json(['error' => __('New name can not be same as old.')]);
                                    } else{
                                        $old_char_data = $this->Mcharacter->check_if_char_exists($this->website->hex2bin($this->Mcharacter->vars['old_name']));
                                        $new_char_data = $this->Mcharacter->check_if_char_exists($this->Mcharacter->vars['new_name']);
                                        if(!$old_char_data){
                                            json(['error' => __('Old character not found on your account.')]);
                                        } else{
                                            if($old_char_data['AccountId'] != $this->session->userdata(['user' => 'username'])){
                                                json(['error' => __('You are not owner of this character.')]);
                                            } else{
                                                if($new_char_data){
                                                    json(['error' => __('Character with this name already exists.')]);
                                                } else{
                                                    if($this->config->config_entry('changename|check_guild') == 1 && $this->Mcharacter->has_guild($this->website->hex2bin($this->Mcharacter->vars['old_name']))){
                                                        json(['error' => __('You are not allowed to change name while you are in guild.')]);
                                                    } else{
                                                        $restricted_words = explode(',', $this->config->config_entry('changename|forbidden'));
                                                        $restrict = false;
                                                        foreach($restricted_words as $key => $words){
                                                            if(stripos($this->Mcharacter->vars['new_name'], $words) !== false){
                                                                $restrict = true;
                                                                break;
                                                            }
                                                        }
                                                        if($restrict != false){
                                                            json(['error' => __('Found forbidden word in new character name please fix it.')]);
                                                        } else{
                                                            $status = $this->Maccount->get_amount_of_credits($this->session->userdata(['user' => 'username']), $this->config->config_entry('changename|price_type'), $this->session->userdata(['user' => 'server']), $this->session->userdata(['user' => 'id']));
                                                            $price = $this->config->config_entry('changename|price');
                                                            if($this->session->userdata('vip')){
                                                                $price -= ($price / 100) * $this->session->userdata(['vip' => 'change_name_discount']);
                                                            }
                                                            if($status < $price){
                                                                json(['error' => sprintf(__('You have insufficient amount of %s'), $this->website->translate_credits($this->config->config_entry('changename|price_type'), $this->session->userdata(['user' => 'server'])))]);
                                                            } else{
                                                                $this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $price, $this->config->config_entry('changename|price_type'));
                                                                if($this->Mcharacter->update_account_character($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name'])){
                                                                    if($this->config->config_entry('changename|check_guild') == 0){
                                                                        $this->Mcharacter->update_guild($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                        $this->Mcharacter->update_guild_member($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    }
                                                                    $this->Mcharacter->update_character($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_option_data($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_t_friendlist($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_t_friendmail($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_t_cguid($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
																	$this->Mcharacter->update_T_CurCharName($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
																	$this->Mcharacter->update_T_Event_Inventory($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
																	if($this->config->config_entry('changename|user_master_level') == 1 && (strtolower($this->config->values('table_config', [$this->session->userdata(['user' => 'server']), 'master_level', 'table'])) != 'character' && trim($this->config->values('table_config', [$this->session->userdata(['user' => 'server']), 'master_level', 'table'])) != '')){
                                                                        $this->Mcharacter->update_master_level_table($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name'], $this->session->userdata(['user' => 'server']));
                                                                    }
                                                                    $this->Mcharacter->update_IGC_Gens($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_IGC_GensAbuse($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
																	$this->Mcharacter->update_IGC_HuntingRecord($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
																	$this->Mcharacter->update_IGC_HuntingRecordOption($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
																	$this->Mcharacter->update_IGC_LabyrinthClearLog($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
																	$this->Mcharacter->update_IGC_LabyrinthInfo($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
																	$this->Mcharacter->update_IGC_LabyrinthLeagueLog($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
																	$this->Mcharacter->update_IGC_LabyrinthLeagueUser($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
																	$this->Mcharacter->update_IGC_LabyrinthMissionInfo($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
																	$this->Mcharacter->update_IGC_MixLostItemInfo($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
																	$this->Mcharacter->update_IGC_Muun_Inventory($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
																	$this->Mcharacter->update_IGC_RestoreItem_Inventory($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_IGC_PeriodBuffInfo($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_IGC_PeriodExpiredItemInfo($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_IGC_PeriodItemInfo($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
																	$this->Mcharacter->update_IGC_PentagramInfo($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_T_3rd_Quest_Info($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_T_GMSystem($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_T_LUCKY_ITEM_INFO($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_T_PentagramInfo($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_T_QUEST_EXP_INFO($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_T_WaitFriend($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_T_WaitFriend($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_PetWarehouse($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_DmN_Ban_List($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_DmN_Gm_List($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_DmN_Market($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_DmN_Market_Logs($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Mcharacter->update_DmN_Votereward_Ranking($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    $this->Maccount->add_account_log('Changed Name To ' . $this->Mcharacter->vars['new_name'] . ' for ' . $this->website->translate_credits($this->config->config_entry('changename|price_type'), $this->session->userdata(['user' => 'server'])), -$price, $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                                                    $this->Mcharacter->add_to_change_name_history($this->website->hex2bin($this->Mcharacter->vars['old_name']), $this->Mcharacter->vars['new_name']);
                                                                    json(['success' => __('Character Name Successfully Changed.'), 'new_name' => bin2hex($this->Mcharacter->vars['new_name'])]);
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            //}
                        }
                    }
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function exchange_wcoins()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->load->model('account');
                $this->load->model('character');
                foreach($_POST as $key => $value){
                    $this->Mcharacter->$key = trim($value);
                }
                if(!$this->Maccount->check_connect_stat())
                    json(['error' => __('Please logout from game.')]); else{
                    if(!preg_match('/^[0-9]+$/', $this->Mcharacter->vars['credits']))
                        json(['error' => sprintf(__('Invalid amount of %s'), $this->website->translate_credits($this->vars['wcoin_config']['credits_type'], $this->session->userdata(['user' => 'server'])))]); else{
                        $this->vars['wcoin_config'] = $this->config->values('wcoin_exchange_config', $this->session->userdata(['user' => 'server']));
                        $this->vars['table_config'] = $this->config->values('table_config', $this->session->userdata(['user' => 'server']));
                        if(isset($this->vars['table_config']['wcoins']) && $this->vars['wcoin_config'] != false && $this->vars['wcoin_config']['active'] == 1){
                            if($this->Mcharacter->vars['credits'] < $this->vars['wcoin_config']['min_rate'])
                                json(['error' => vsprintf(__('Minimal exchange rate is %d %s'), [$this->vars['wcoin_config']['min_rate'], $this->website->translate_credits($this->vars['wcoin_config']['credits_type'], $this->session->userdata(['user' => 'server']))])]); else{
                                if($this->vars['wcoin_config']['reward_coin'] < 0)
                                    $total = floor($this->Mcharacter->vars['credits'] * abs($this->vars['wcoin_config']['reward_coin'])); else
                                    $total = floor($this->Mcharacter->vars['credits'] / $this->vars['wcoin_config']['reward_coin']);
                                if($this->vars['wcoin_config']['change_back'] == 1){
                                    if($this->Mcharacter->vars['exchange_type'] == 1){
                                        goto exchange_wcoins;
                                    } else{
                                        goto exchange_credits;
                                    }
                                }
                                exchange_wcoins:
                                $status = $this->Maccount->get_amount_of_credits($this->session->userdata(['user' => 'username']), $this->vars['wcoin_config']['credits_type'], $this->session->userdata(['user' => 'server']), $this->session->userdata(['user' => 'id']));
                                if($status < $this->Mcharacter->vars['credits'])
                                    json(['error' => sprintf(__('You have insufficient amount of %s'), $this->website->translate_credits($this->vars['wcoin_config']['credits_type'], $this->session->userdata(['user' => 'server'])))]); else{
                                    $this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $this->Mcharacter->vars['credits'], $this->vars['wcoin_config']['credits_type']);
                                    $this->Maccount->add_account_log('Exchange ' . $this->website->translate_credits($this->vars['wcoin_config']['credits_type'], $this->session->userdata(['user' => 'server'])) . ' to' . __('WCoins'), -$this->Mcharacter->vars['credits'], $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                    $this->Mcharacter->add_wcoins($total, $this->vars['table_config']['wcoins']);
                                    json(['success' => __('WCoins successfully exchanged.')]);
                                }
                                exchange_credits:
                                if($status = $this->Mcharacter->get_wcoins($this->vars['table_config']['wcoins'], $this->session->userdata(['user' => 'server']))){
                                    if($status < $this->Mcharacter->vars['credits'])
                                        json(['error' => sprintf(__('You have insufficient amount of %s'), __('WCoins'))]); else{
                                        $this->website->add_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $total, $this->vars['wcoin_config']['credits_type']);
                                        $this->Maccount->add_account_log('Exchange ' . __('WCoins') . ' to ' . $this->website->translate_credits($this->vars['wcoin_config']['credits_type'], $this->session->userdata(['user' => 'server'])), -$this->Mcharacter->vars['credits'], $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                        $this->Mcharacter->remove_wcoins($this->vars['table_config']['wcoins']);
                                        json(['success' => __('WCoins successfully exchanged.')]);
                                    }
                                } else{
                                    json(['error' => __('Unable to exchange Wcoins')]);
                                }
                            }
                        } else{
                            json(['error' => __('This module has been disabled.')]);
                        }
                    }
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function switch_language()
        {
            if(isset($_POST['lang'])){
                setcookie("dmn_language", $this->website->c($_POST['lang']), strtotime('+5 days', time()), "/");
				json(['success' => true]);																								
            }
			json(['error' => true]); 
        }

        public function paypal()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->load->model('donate');
                if(isset($_POST['proccess_paypal'])){
                    if($package_data = $this->Mdonate->get_paypal_package_data_by_id($_POST['proccess_paypal'])){
                        if($this->Mdonate->insert_paypal_order($package_data['reward'], $package_data['price'], $package_data['currency']))
                            json($this->Mdonate->get_paypal_data()); else
                            json(['error' => __('Unable to checkout please try again.')]);
                    } else{
                        json(['error' => __('Paypal package not found.')]);
                    }
                } else{
                    json(['error' => __('Unable to checkout please try again.')]);
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function paycall()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->load->model('donate');
                if(isset($_POST['proccess_paycall'])){
                    if($package_data = $this->Mdonate->get_paycall_package_data_by_id($_POST['proccess_paycall'])){
                        if($this->Mdonate->insert_paycall_order($package_data['reward'], $package_data['price']))
                            json($this->Mdonate->get_paycall_data($package_data['reward'], $package_data['price'])); else
                            json(['error' => __('Unable to checkout please try again.')]);
                    } else{
                        json(['error' => __('Paycall package not found.')]);
                    }
                } else{
                    json(['error' => __('Unable to checkout please try again.')]);
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function hide_chars()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->model('account');
                if($this->config->config_entry('account|hide_char_enabled') == 1){														
                    $status = $this->Maccount->get_amount_of_credits($this->session->userdata(['user' => 'username']), $this->config->config_entry('account|hide_char_price_type'), $this->session->userdata(['user' => 'server']), $this->session->userdata(['user' => 'id']));
                    $price = $this->config->config_entry('account|hide_char_price');
                    if($this->session->userdata('vip')){
                        $price -= ($price / 100) * $this->session->userdata(['vip' => 'hide_info_discount']);
                    }
                    if($status < $price){
                        json(['error' => sprintf(__('You have insufficient amount of %s'), $this->website->translate_credits($this->config->config_entry('account|hide_char_price_type'), $this->session->userdata(['user' => 'server'])))]);
                    } else{
                        $check_hide = $this->Maccount->check_hide_time();
                        if($check_hide == 'None'){
                            $this->Maccount->add_hide($price);
                            $this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $price, $this->config->config_entry('account|hide_char_price_type'));
                            json(['success' => __('You have successfully hidden your chars')]);
                        } else{   
                            $this->Maccount->extend_hide($check_hide, $price);
                            $this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $price, $this->config->config_entry('account|hide_char_price_type'));
                            json(['success' => __('You char hide time has been extended')]); 
                        }
                    }
                } else{
                    json(['error' => __('This module has been disabled.')]);
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function download()
        {
            $this->load->model('admin');
            if(!isset($_POST['image'])){
                exit;
            } else{
                if(!ctype_digit($_POST['image'])){
                    exit;
                }
                if(!$image = $this->Madmin->check_gallery_image($_POST['image'])){
                    exit;
                } else{
                    $file = BASEDIR . 'assets' . DS . 'uploads' . DS . 'normal' . DS . $image['name'];
                    if(file_exists($file)){
                        header('Pragma: public');
                        header('Cache-Control: public, no-cache');
                        header('Content-Type: application/octet-stream');
                        header('Content-Length: ' . filesize($file));
                        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
                        header('Content-Transfer-Encoding: binary');
                        readfile($file);
                    } else{
                        exit;
                    }
                }
            }
        }

        public function get_time()
        {
            if(isset($_GET['callback'])){
                echo htmlspecialchars($_GET['callback']) . '(' . "{'ServerTime' : '" . date('m/d/Y h:i:s A', time()) . "'}" . ')';
            } else{
                return false;
            }
        }

        public function click_ads()
        {
            if(defined('IS_GOOGLE_ADD_VOTE') && IS_GOOGLE_ADD_VOTE == true){
                if($this->session->userdata(['user' => 'logged_in'])){
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                    $this->load->model('account');
                    if($this->Maccount->get_last_ads_vote(GOOGLE_ADD_TIME) != false){
                        return false;
                    } else{
                        if($this->Maccount->log_ads_vote()){
                            $this->Maccount->reward_voter(GOOGLE_ADD_REWARD, 1, $this->session->userdata(['user' => 'server']));
                            return true;
                        } else{
                            return false;
                        }
                    }
                } else{
                    return false;
                }
            } else{
                return false;
            }
        }
    }