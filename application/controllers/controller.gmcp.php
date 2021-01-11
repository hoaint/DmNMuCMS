<?php
    in_file();

    class gmcp extends controller
    {
        protected $vars = [], $errors = [];

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('website');
            $this->load->lib('session', ['DmNCMS']);
			$this->load->lib('csrf');						 
            $this->load->model('gm');
        }

        public function index()
        {
            if($this->session->userdata(['user' => 'is_gm'])){
                $this->load->view('gmcp' . DS . 'view.header');
                $this->load->view('gmcp' . DS . 'view.sidebar');
                $this->vars['announcement'] = $this->Mgm->load_announcement();
                $this->load->view('gmcp' . DS . 'view.index', $this->vars);
                $this->load->view('gmcp' . DS . 'view.footer');
            } else{
                $this->login();
            }
        }

        public function login()
        {
            if(count($_POST) > 0){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($_POST['server'], true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->model('account');
                foreach($_POST as $key => $value){
                    $this->Mgm->$key = trim($value);
                }
                if(!isset($this->Mgm->vars['username']))
                    $this->vars['error'] = 'Please enter username1.'; else{
                    if($this->Mgm->vars['username'] == '')
                        $this->vars['error'] = 'Please enter username.'; else{
                        if(!isset($this->Mgm->vars['password']))
                            $this->vars['error'] = 'Please enter password.'; else{
                            if($this->Mgm->vars['password'] == '')
                                $this->vars['error'] = 'Please enter password.'; else{
                                if(!$this->Mgm->valid_username($this->Mgm->vars['username']))
                                    $this->vars['error'] = 'Invalid Username.'; else{
                                    if(!$this->Mgm->valid_username($this->Mgm->vars['password']))
                                        $this->vars['error'] = 'Invalid Password.'; else{
                                        if(!isset($this->Mgm->vars['server']))
                                            $this->vars['error'] = 'Please select server.'; else{
                                            if($this->Mgm->check_gm_in_list()){
                                                if($this->Mgm->login_gm()){
                                                    header('Location: ' . $this->config->base_url . 'gmcp');
                                                } else{
                                                    $this->vars['error'] = 'Wrong username and/or password.';
                                                }
                                            } else{
                                                $this->vars['error'] = 'Gm account nof found.';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $this->load->view('gmcp' . DS . 'view.login', $this->vars);
        }

        public function logout()
        {
            $this->session->destroy();
            header('Location: ' . $this->config->base_url . 'gmcp');
        }

        public function search()
        {
            if($this->session->userdata(['user' => 'is_gm'])){
                $this->load->view('gmcp' . DS . 'view.header');
                $this->load->view('gmcp' . DS . 'view.sidebar');
                if($this->session->userdata(['user' => 'can_search_acc']) == 1){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                    if(isset($_POST['search_acc'])){
                        foreach($_POST as $key => $value){
                            $this->Mgm->$key = trim($value);
                        }
                        switch($this->Mgm->vars['type']){
                            case 1:
                                $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                                if(!$this->Mgm->valid_username($this->Mgm->vars['name']))
                                    $this->vars['error'] = 'Invalid account name.'; else{
                                    if(!$this->vars['account'] = $this->Mgm->search_acc()){
                                        $this->vars['acc_not_found'] = 'Account not found';
                                    } else{
                                        $this->vars['ip'] = $this->Mgm->find_ip($this->vars['account']['AccountId']);
                                    }
                                }
                                break;
                            case 2:
                                $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                                if(!$this->Mgm->valid_username($this->Mgm->vars['name']))
                                    $this->vars['error'] = 'Invalid char name.'; else{
                                    if(!$this->vars['account'] = $this->Mgm->search_char()){
                                        $this->vars['acc_not_found'] = 'Character not found';
                                    } else{
                                        $this->vars['ip'] = $this->Mgm->find_ip($this->vars['account']['AccountId']);
                                    }
                                }
                                break;
                        }
                    }
                } else{
                    $this->vars['not_allowed'] = 'Your access level is too low to use this action';
                }
                $this->load->view('gmcp' . DS . 'view.search', $this->vars);
                $this->load->view('gmcp' . DS . 'view.footer');
            } else{
                $this->login();
            }
        }

        public function ban()
        {
            if($this->session->userdata(['user' => 'is_gm'])){
                $this->load->view('gmcp' . DS . 'view.header');
                $this->load->view('gmcp' . DS . 'view.sidebar');
                if($this->session->userdata(['user' => 'can_ban_acc']) == 1){
                    if(isset($_POST['ban'])){
                        foreach($_POST as $key => $value){
                            $this->Mgm->$key = trim($value);
                        }
                        switch($this->Mgm->vars['type']){
                            case 1:
                                $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                                if(!$this->Mgm->valid_username($this->Mgm->vars['name']))
                                    $this->vars['error'] = 'Invalid account name.'; else{
                                    if(strtotime($this->Mgm->vars['time']) < time() && !isset($this->Mgm->vars['permanent_ban'])){
                                        $this->vars['error'] = 'Wrong ban time.';
                                    } else{
                                        if($check = $this->Mgm->check_account()){
                                            if($check['bloc_code'] != 1){
                                                $this->Mgm->ban_account();
                                                $this->Mgm->add_to_banlist();
                                                $this->Mgm->add_gm_log('Blocked account: ' . $this->Mgm->vars['name'], $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                                $this->vars['success'] = 'Account successfully banned.';
                                            } else{
                                                $this->vars['error'] = 'Account already banned.';
                                            }
                                        } else{
                                            $this->vars['error'] = 'Account not found.';
                                        }
                                    }
                                }
                                break;
                            case 2:
                                $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                                $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                                if(!$this->Mgm->valid_username($this->Mgm->vars['name']))
                                    $this->vars['error'] = 'Invalid char name.'; else{
                                    if(strtotime($this->Mgm->vars['time']) < time() && !isset($this->Mgm->vars['permanent_ban'])){
                                        $this->vars['error'] = 'Wrong ban time.';
                                    } else{
                                        if($check = $this->Mgm->check_char()){
                                            if($check['CtlCode'] != 1){
                                                $this->Mgm->ban_char();
                                                $this->Mgm->add_to_banlist();
                                                $this->Mgm->add_gm_log('Blocked character: ' . $this->Mgm->vars['name'], $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                                $this->vars['success'] = 'Character banned.';
                                            } else{
                                                $this->vars['error'] = 'Character aleady banned.';
                                            }
                                        } else{
                                            $this->vars['error'] = 'Character not found.';
                                        }
                                    }
                                }
                                break;
                        }
                    }
                } else{
                    $this->vars['not_allowed'] = 'Your access level is too low to use this action';
                }
                $this->vars['ban_list'] = $this->Mgm->load_ban_list();
                $this->load->view('gmcp' . DS . 'view.ban', $this->vars);
                $this->load->view('gmcp' . DS . 'view.footer');
            } else{
                $this->login();
            }
        }

        public function unban($type = '', $name = '')
        {
            if($this->session->userdata(['user' => 'is_gm'])){
                $this->load->view('gmcp' . DS . 'view.header');
                $this->load->view('gmcp' . DS . 'view.sidebar');
                if($this->session->userdata(['user' => 'can_ban_acc']) == 1){
                    if($type == '')
                        $this->vars['errors'] = 'Invalid ban type.'; else{
                        if($name == '')
                            $this->vars['errors'] = 'Invalid name.'; else{
                            switch($type){
                                case 'account':
                                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                                    $this->Mgm->unban_account($name);
                                    $this->Mgm->remove_ban_list_account($name);
                                    $this->Mgm->add_gm_log('Unblocked account: ' . $name, $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                    $this->vars['success'] = 'Account unbanned.';
                                    break;
                                case 'character':
                                    $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                                    $this->Mgm->unban_character($name);
                                    $this->Mgm->remove_ban_list_character($name);
                                    $this->Mgm->add_gm_log('Unblocked character: ' . $name, $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                    $this->vars['success'] = 'Character unbanned.';
                                    break;
                            }
                        }
                    }
                } else{
                    $this->vars['not_allowed'] = 'Your access level is too low to use this action';
                }
                $this->load->view('gmcp' . DS . 'view.info', $this->vars);
                $this->load->view('gmcp' . DS . 'view.footer');
            } else{
                $this->login();
            }
        }

        public function credits_adder()
        {
            if($this->session->userdata(['user' => 'is_gm'])){
                $this->load->view('gmcp' . DS . 'view.header');
                $this->load->view('gmcp' . DS . 'view.sidebar');
                $this->vars['credits_limit'] = $this->Mgm->get_gm_credits_limit($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $this->session->userdata(['user' => 'credits_limit']));
                if($this->session->userdata(['user' => 'credits_limit']) > 0){
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                    if(isset($_POST['add_credits'])){
                        foreach($_POST as $key => $value){
                            $this->Mgm->$key = trim($value);
                        }
                        if(!isset($this->Mgm->name)){
                            $this->vars['error'] = 'Please enter character name.';
                        } else{
                            if($this->vars['account_info'] = $this->Mgm->check_char()){
                                if(!isset($_POST['c_type']) || $_POST['c_type'] == ''){
                                    $this->vars['error'] = 'Please select credits type.';
                                } else{
                                    if(!isset($_POST['amount']) || !ctype_digit($_POST['amount'])){
                                        $this->vars['error'] = 'Please enter credits amount.';
                                    } else{
                                        if($_POST['amount'] > $this->vars['credits_limit']){
                                            $this->vars['error'] = 'Amount entered is bigger than your credits limit.';
                                        } else{
                                            $this->Mgm->update_credits_limit($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), ($this->vars['credits_limit'] - $_POST['amount']));
                                            $this->website->add_credits($this->vars['account_info']['AccountId'], $this->session->userdata(['user' => 'server']), $_POST['amount'], $_POST['c_type'], false, $this->vars['account_info']['memb_guid']);
                                            $this->Mgm->add_gm_log('Added ' . $_POST['amount'] . ' ' . $this->website->translate_credits($_POST['c_type'], $this->session->userdata(['user' => 'server'])) . ' to account: ' . $this->vars['account_info']['AccountId'], $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                            $this->vars['success'] = 'Credits Added';
                                        }
                                    }
                                }
                            } else{
                                $this->vars['error'] = 'Character not found';
                            }
                        }
                    }
                } else{
                    $this->vars['not_allowed'] = 'Your access level is too low to use this action';
                }
                $this->load->view('gmcp' . DS . 'view.credits_adder', $this->vars);
                $this->load->view('gmcp' . DS . 'view.footer');
            } else{
                $this->login();
            }
        }
    }