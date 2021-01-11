<?php
    in_file();

    class warehouse extends controller
    {
        public $vars = [], $errors = [];
        private $serial;

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('website');
            $this->load->lib('session', ['DmNCMS']);
            $this->load->lib('csrf');
            $this->load->model('character');
            $this->load->helper('breadcrumbs', [$this->request]);
            $this->load->helper('meta');
            if($this->session->userdata(['user' => 'server'])){
                $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
            }
            $this->load->lib("itemimage");
            $this->load->lib("iteminfo");
            $this->load->model('warehouse');
        }

        public function index()
        {
            if(!$this->website->module_disabled('warehouse')){
                if($this->session->userdata(['user' => 'logged_in'])){
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->model('account');
                    if(!$this->Maccount->check_connect_stat()){
                        $this->vars['error'] = __('Please logout from game.');
                    } else{
                        if($this->Mwarehouse->get_vault_content()){
                            $this->vars['char_list'] = $this->Mcharacter->load_char_list();
                            $this->vars['items'] = $this->Mwarehouse->load_items();
                        } else{
                            $this->vars['error'] = __('Please open your warehouse in game first.');
                        }
                    }
                    $this->load->view($this->config->config_entry('main|template') . DS . 'warehouse' . DS . 'view.index', $this->vars);
                } else{
                    $this->login();
                }
            }
        }

        public function web($page = 1)
        {
            if(!$this->website->module_disabled('warehouse')){
                if($this->session->userdata(['user' => 'logged_in'])){
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->model('account');
                    $this->load->lib("pagination");
                    $this->Mwarehouse->count_total_web_items();
                    $this->pagination->initialize($page, $this->config->config_entry('warehouse|web_items_per_page'), $this->Mwarehouse->total_items, $this->config->base_url . 'warehouse/web/%s');
                    $this->vars['items'] = $this->Mwarehouse->load_web_items($page);
                    $this->vars['pagination'] = $this->pagination->create_links();
                    $this->load->view($this->config->config_entry('main|template') . DS . 'warehouse' . DS . 'view.web', $this->vars);
                } else{
                    $this->login();
                }
            }
        }

        public function sell_item()
        {
            if(is_ajax()){
                if($this->session->userdata(['user' => 'logged_in'])){
                    //$this->csrf->isTokenValid($this->csrf->verifyRequest('post', 'json'));
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->model('account');
                    $price = (isset($_POST['price']) ? ctype_digit($_POST['price']) ? $_POST['price'] : '' : '');
                    $ptype = (isset($_POST['payment_method']) ? ctype_digit($_POST['payment_method']) ? $_POST['payment_method'] : '' : '');
                    $time = (isset($_POST['time']) ? ctype_digit($_POST['time']) ? $_POST['time'] : '' : '');
                    $char = isset($_POST['char']) ? $_POST['char'] : '';
                    $highlighted = isset($_POST['highlight']) ? 1 : 0;
                    $slot = (isset($_POST['slot']) ? ctype_digit($_POST['slot']) ? $_POST['slot'] : '' : '');
                    $item_count = $this->Mwarehouse->get_market_item_count();
                    $start_pos = ($this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size') == 64) ? [6, 32] : 6;
                    if($price == '' || $price <= 0)
                        $this->errors[] = __('Please enter price.');
                    if($ptype == '')
                        $this->errors[] = __('Please select payment method.');
                    if(!in_array($ptype, [1, 2, 3, 4, 5, 6, 7, 8, 9]))
                        $this->errors[] = __('Invalid payment type.');
                    if($ptype == 1){
                        if($price > $this->config->config_entry('market|price_limit_credits'))
                            $this->errors[] = vsprintf(__('Max price can be %d %s'), [$this->config->config_entry('market|price_limit_credits'), $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_1')]); else{
                            if($this->config->config_entry('warehouse|allow_sell_for_credits') == 0)
                                $this->errors[] = __('Your not allowed to use this payment type');
                        }
                    }
                    if($ptype == 2){
                        if($price > $this->config->config_entry('market|price_limit_gcredits'))
                            $this->errors[] = vsprintf(__('Max price can be %d %s'), [$this->config->config_entry('market|price_limit_gcredits'), $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_2')]); else{
                            if($this->config->config_entry('warehouse|allow_sell_for_gcredits') == 0)
                                $this->errors[] = __('Your not allowed to use this payment type');
                        }
                    }
                    if($ptype == 3){
                        if($price > $this->config->config_entry('market|price_limit_zen'))
                            $this->errors[] = vsprintf(__('Max price can be %d %s'), [$this->config->config_entry('market|price_limit_zen'), $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_3')]); else{
                            if($this->config->config_entry('warehouse|allow_sell_for_zen') == 0)
                                $this->errors[] = __('Your not allowed to use this payment type');
                        }
                    }
                    if($ptype == 4){
                        if($price > $this->config->config_entry('market|price_limit_jewels'))
                            $this->errors[] = vsprintf(__('Max price can be %d %s'), [$this->config->config_entry('market|price_limit_yewels'), __('Jewel Of Chaos')]); else{
                            if($this->config->config_entry('warehouse|allow_sell_for_chaos') == 0)
                                $this->errors[] = __('Your not allowed to use this payment type');
                        }
                    }
                    if($ptype == 5){
                        if($price > $this->config->config_entry('market|price_limit_jewels'))
                            $this->errors[] = vsprintf(__('Max price can be %d %s'), [$this->config->config_entry('market|price_limit_yewels'), __('Jewel Of Bless')]); else{
                            if($this->config->config_entry('warehouse|allow_sell_for_bless') == 0)
                                $this->errors[] = __('Your not allowed to use this payment type');
                        }
                    }
                    if($ptype == 6){
                        if($price > $this->config->config_entry('market|price_limit_jewels'))
                            $this->errors[] = vsprintf(__('Max price can be %d %s'), [$this->config->config_entry('market|price_limit_yewels'), __('Jewel Of Soul')]); else{
                            if($this->config->config_entry('warehouse|allow_sell_for_soul') == 0)
                                $this->errors[] = __('Your not allowed to use this payment type');
                        }
                    }
                    if($ptype == 7){
                        if($price > $this->config->config_entry('market|price_limit_jewels'))
                            $this->errors[] = vsprintf(__('Max price can be %d %s'), [$this->config->config_entry('market|price_limit_yewels'), __('Jewel Of Life')]); else{
                            if($this->config->config_entry('warehouse|allow_sell_for_life') == 0)
                                $this->errors[] = __('Your not allowed to use this payment type');
                        }
                    }
                    if($ptype == 8){
                        if($price > $this->config->config_entry('market|price_limit_jewels'))
                            $this->errors[] = vsprintf(__('Max price can be %d %s'), [$this->config->config_entry('market|price_limit_yewels'), __('Jewel Of Creation')]); else{
                            if($this->config->config_entry('warehouse|allow_sell_for_creation') == 0)
                                $this->errors[] = __('Your not allowed to use this payment type');
                        }
                    }
                    if($ptype == 9){
                        if($price > $this->config->config_entry('market|price_limit_jewels'))
                            $this->errors[] = vsprintf(__('Max price can be %d %s'), [$this->config->config_entry('market|price_limit_yewels'), __('Jewel Of Harmony')]); else{
                            if($this->config->config_entry('warehouse|allow_sell_for_harmony') == 0)
                                $this->errors[] = __('Your not allowed to use this payment type');
                        }
                    }
                    if($time == '' || !in_array($time, [1, 2, 3, 4, 5, 7, 14]))
                        $this->errors[] = __('Please select time.');
                    if($char == '')
                        $this->errors[] = __('Please select valid character.'); 
					else{
                        if(!$this->Mcharacter->check_char($char))
                            $this->errors[] = __('Character not found.');
                    }
                    if($slot == '')
                        $this->errors[] = __('Invalid item.');
                    if($item_count['count'] >= $this->config->config_entry('market|sell_item_limit'))
                        $this->errors[] = sprintf(__('You can\'t sell more than %d items in one day.'), $this->config->config_entry('market|sell_item_limit'));
                    if($highlighted == 1){
                        if($this->Maccount->get_amount_of_credits($this->session->userdata(['user' => 'username']), 1, $this->session->userdata(['user' => 'server']), $this->session->userdata(['user' => 'id'])) < $this->config->config_entry('market|price_highlight'))
                            $this->errors[] = vsprintf(__('For highlighting item you need %d %s'), [$this->config->config_entry('market|price_highlight'), $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_1')]);
                    }
                    if(!$this->Maccount->check_connect_stat())
                        $this->errors[] = __('Please logout from game.');
                    if(count($this->errors) > 0){
                        foreach($this->errors as $error){
                            json(['error' => $error]);
                        }
                    } else{
						usleep(mt_rand(1000000, 5000000));
                        if($this->Mwarehouse->get_vault_content()){
                            if($this->Mwarehouse->find_item_by_slot($slot)){
                                if(!$this->check_serial($start_pos)){
                                    json(['error' => sprintf(__('You are not allowed to sell items without serial. Item Serial: %s'), $this->serial)]);
                                } else{
                                    $allow_sell = true;
                                    if($this->config->config_entry('market|allow_sell_shop_items') == 0){
                                        if($this->Mwarehouse->check_shop_item() != false){
                                            $allow_sell = false;
                                            json(['error' => __('You are not allowed to sell items purchased in webshop.')]);
                                        }
                                    }
                                    if($allow_sell){
                                        $this->Mwarehouse->generate_new_item_by_slot($slot);
                                        $info = $this->Mwarehouse->load_item_info();
                                        if($info != false){
                                            if($this->Mwarehouse->exe_opt_count > $this->config->config_entry('market|max_exe'))
                                                json(['error' => sprintf(__('You are only allowed to sell items with max %d exe options.'), $this->config->config_entry('market|max_exe'))]); else{
                                                if($this->Mwarehouse->update_warehouse()){
                                                    $this->Mwarehouse->add_market_item($info, $price, $ptype, $time, $char, $highlighted);
                                                    if($highlighted == 1){
                                                        $this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $this->config->config_entry('market|price_highlight'), 1);
                                                        $this->Maccount->add_account_log('Item Higlighting Fee ' . $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_1') . '', -(int)$this->config->config_entry('market|price_highlight'), $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                                    }
                                                    json(['success' => __('Item successfully sold.')]);
                                                } else{
                                                    json(['error' => __('Unable to sell item.')]);
                                                }
                                            }
                                        } else{
                                            json(['error' => __('Unable to sell item.')]);
                                        }
                                    }
                                }
                            } else{
                                json(['error' => __('Item not found.')]);
                            }
                        } else{
                            json(['error' => __('Please open your warehouse in game first.')]);
                        }
                    }
                } else{
                    json(['error' => __('Please login into website.')]);
                }
            }
        }

        private function check_serial($pos)
        {
            $blocked1 = '00000000';
            $blocked2 = 'FFFFFFFF';
            if(is_array($pos)){
                $serial1 = strtoupper(substr($this->Mwarehouse->item, $pos[0], 8));
                $serial2 = strtoupper(substr($this->Mwarehouse->item, $pos[1], 8));
                if(($serial1 === $blocked1 || $serial1 === $blocked2) && ($serial2 === $blocked1 || $serial2 === $blocked2)){
                    $this->serial = $serial1 . $serial2;
                    return false;
                }
            } else{
                $serial = strtoupper(substr($this->Mwarehouse->item, $pos, 8));
                if($serial === $blocked1 || $serial === $blocked2){
                    $this->serial = $serial;
                    return false;
                }
            }
            return true;
        }

        public function transfer_item($type = 'game')
        {
            if(is_ajax()){
                if($this->session->userdata(['user' => 'logged_in'])){
                    $slot = (isset($_POST['slot']) ? ctype_digit($_POST['slot']) ? $_POST['slot'] : '' : '');
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->model('account');
                    $this->load->model('shop');
                    if(!$this->Maccount->check_connect_stat())
                        json(['error' => __('Please logout from game.')]); 
					else{
                        switch($type){
                            case 'game':
                                if($item = $this->Mwarehouse->check_web_wh_item($slot)){
									usleep(mt_rand(1000000, 5000000));
                                    if($vault = $this->Mshop->get_vault_content()){
                                        $this->iteminfo->itemData($item['item']);
                                        $space = $this->Mshop->check_space($vault['Items'], $this->iteminfo->getX(), $this->iteminfo->getY(), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_multiplier'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_hor_size'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_ver_size'));
                                        if($space === null){
                                            json(['error' => $this->Mshop->errors[0]]);
                                        } else{
                                            $this->Mshop->generate_new_items($item['item'], $space, $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_multiplier'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
                                            $this->Mshop->update_warehouse();
                                            $this->Mwarehouse->set_removed_web_item($slot);
                                            json(['success' => __('Item successfully transfered to game warehouse.')]);
                                        }
                                    } else{
                                        json(['error' => __('Please open your warehouse in game first.')]);
                                    }
                                } else{
                                    json(['error' => __('Item not found.')]);
                                }
                                break;
                            case 'web':
                                if($this->config->config_entry('warehouse|allow_move_to_web_warehouse') == 1){
									usleep(mt_rand(1000000, 5000000));
                                    if($this->Mwarehouse->get_vault_content()){
                                        if($this->Mwarehouse->find_item_by_slot($slot)){
                                            $this->Mwarehouse->insert_web_item();
                                            $this->Mwarehouse->generate_new_item_by_slot($slot);
                                            $this->Mwarehouse->update_warehouse();
                                            json(['success' => __('Item successfully transfered to web warehouse.')]);
                                        } else{
                                            json(['error' => __('Item not found.')]);
                                        }
                                    } else{
                                        json(['error' => __('Please open your warehouse in game first.')]);
                                    }
                                } else{
                                    json(['error' => __('Transfer of items to web warehouse not allowed.')]);
                                }
                                break;
                        }
                    }
                } else{
                    json(['error' => __('Please login into website.')]);
                }
            }
        }

        public function del_item()
        {
            if(is_ajax()){
                if($this->session->userdata(['user' => 'logged_in'])){
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->model('account');
                    $slot = (isset($_POST['slot']) ? ctype_digit($_POST['slot']) ? $_POST['slot'] : '' : '');
                    if(!$this->Maccount->check_connect_stat())
                        json(['error' => __('Please logout from game.')]); else{
                        if($this->config->config_entry('warehouse|allow_delete_item') == 1){
                            if($this->Mwarehouse->get_vault_content()){
                                if($this->Mwarehouse->find_item_by_slot($slot)){
                                    $this->Mwarehouse->log_deleted_item();
                                    $this->Mwarehouse->generate_new_item_by_slot($slot);
                                    $this->Mwarehouse->update_warehouse();
                                    json(['success' => __('Item successfully removed from warehouse.')]);
                                } else{
                                    json(['error' => __('Item not found.')]);
                                }
                            } else{
                                json(['error' => __('Please open your warehouse in game first.')]);
                            }
                        } else{
                            json(['error' => __('Item deletion disabled.')]);
                        }
                    }
                } else{
                    json(['error' => __('Please login into website.')]);
                }
            }
        }

        public function item_info()
        {
            if(is_ajax()){
                if(!isset($_POST['item_hex']))
                    json(['error' => true]); else{
                    $this->iteminfo->itemData($_POST['item_hex']);
                    json(['info' => $this->iteminfo->allInfo()]);
                }
            }
        }

        public function item_info_image()
        {
            if(is_ajax()){
                if(!isset($_POST['item_hex']))
                    json(['error' => true]); else{
                    $this->load->lib("itemimage");
					$this->iteminfo->itemData($_POST['item_hex']);
                    json(['info' => $this->itemimage->load($this->iteminfo->id, $this->iteminfo->type, (int)substr($this->iteminfo->getLevel(), 1)) . '<br />' . $this->iteminfo->allInfo()]);
                }
            }
        }

        public function login()
        {
            $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.login');
        }
    }