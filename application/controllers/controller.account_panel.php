<?php
    in_file();

    class account_panel extends controller
    {
        protected $vars = [], $errors = [];

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
        }

        public function index()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.index', $this->vars);
            } else{
                $this->login();
            }
        }

        public function level_reward()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->vars['char_list'] = $this->Mcharacter->load_char_list();
                $this->vars['level_rewards'] = $this->config->values('level_rewards_config');
                $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.level_reward', $this->vars);
            } else{
                $this->login();
            }
        }

        public function exchange_lucky_coins()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->vars['coin_config'] = $this->config->values('luckycoin_config', $this->session->userdata(['user' => 'server']));
                if($this->vars['coin_config']['active'] == 1){
                    unset($this->vars['coin_config']['active']);
                    $this->load->model('account');
                    $this->load->lib('iteminfo');
                    $this->load->model('shop');
                    if(isset($_POST['exchange_coins'])){
                        foreach($_POST as $key => $value){
                            $this->Mcharacter->$key = trim($value);
                        }
                        if(!$this->Maccount->check_connect_stat())
                            $this->vars['error'] = __('Please logout from game.'); else{
                            if(!isset($this->Mcharacter->vars['character']))
                                $this->vars['error'] = __('Invalid Character'); else{
                                if(!$this->Mcharacter->check_char())
                                    $this->vars['error'] = __('Character not found.'); else{
                                    if(!isset($this->Mcharacter->vars['lucky_coin']))
                                        $this->vars['error'] = __('Please select option for exchange.'); else{
                                        if(!in_array($this->Mcharacter->vars['lucky_coin'], [10, 20, 30]))
                                            $this->vars['error'] = __('Invalid exchange option selected.'); else{
                                            $this->vars['coin_data'] = $this->Mcharacter->check_amount_of_coins();
                                            if(array_sum($this->vars['coin_data']) < $this->Mcharacter->vars['lucky_coin']){
                                                $this->vars['error'] = __('You have insufficient amount of coins.');
                                            } else{
                                                if(array_key_exists($this->Mcharacter->vars['lucky_coin'], $this->vars['coin_config'])){
                                                    $max_prob = $this->vars['coin_config'][$this->Mcharacter->vars['lucky_coin']]['max_probability'];
                                                    unset($this->vars['coin_config'][$this->Mcharacter->vars['lucky_coin']]['max_probability']);
                                                    $chance = $this->Mcharacter->draw_chance($this->vars['coin_config'][$this->Mcharacter->vars['lucky_coin']], $max_prob);
                                                    shuffle($this->vars['coin_config'][$this->Mcharacter->vars['lucky_coin']][$chance]);
                                                    $item_key = array_rand($this->vars['coin_config'][$this->Mcharacter->vars['lucky_coin']][$chance], 1);
                                                    $cat = $this->vars['coin_config'][$this->Mcharacter->vars['lucky_coin']][$chance][$item_key][1];
                                                    $id = $this->vars['coin_config'][$this->Mcharacter->vars['lucky_coin']][$chance][$item_key][0];
                                                    $new_coins = array_sum($this->vars['coin_data']) - $this->Mcharacter->vars['lucky_coin'];
                                                    $this->iteminfo->setItemData($id, $cat, $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
                                                    $data = $this->iteminfo->item_data;
                                                    if($vault = $this->Mshop->get_vault_content()){
                                                        $space = $this->Mshop->check_space($vault['Items'], $data['x'], $data['y'], $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_multiplier'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_hor_size'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_ver_size'));
                                                        if($space === null){
                                                            $this->vars['error'] = $this->Mshop->errors[0];
                                                        } else{
                                                            $item_data = $this->vars['coin_config'][$this->Mcharacter->vars['lucky_coin']][$chance][$item_key];
                                                            if(is_array($item_data[5])){
                                                                $lvl = rand($item_data[5][0], $item_data[5][1]);
                                                            } else{
                                                                $lvl = $item_data[5];
                                                            }
                                                            if($item_data[6] == -1){
                                                                $skill = rand(0, 1);
                                                            } else{
                                                                $skill = $item_data[6];
                                                            }
                                                            if($item_data[7] == -1){
                                                                $luck = rand(0, 1);
                                                            } else{
                                                                $luck = $item_data[7];
                                                            }
                                                            if(is_array($item_data[8])){
                                                                $opt = rand($item_data[8][0], $item_data[8][1]);
                                                            } else{
                                                                $opt = $item_data[8];
                                                            }
                                                            if($this->Mcharacter->remove_old_coins($this->vars['coin_data'])){
                                                                $this->load->lib("createitem", [MU_VERSION, SOCKET_LIBRARY]);
                                                                if($new_coins > 0){
                                                                    if($new_coins > 255){
                                                                        $coins_left = [];
                                                                        while($new_coins >= 255){
                                                                            $new_coins -= 255;
                                                                            if($new_coins >= 255){
                                                                                $coins_left[] = 255;
                                                                            } else{
                                                                                $coins_left[] = $new_coins;
                                                                            }
                                                                        }
                                                                        $coins_left[] = 255;
                                                                        $i = -1;
                                                                        if($this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size') == 64){
                                                                            $serial2 = true;
                                                                        }
                                                                        foreach($this->vars['coin_data'] AS $key => $value){
                                                                            $i++;
                                                                            if(array_key_exists($i, $coins_left)){
                                                                                $this->vars['coin_data'][$key] = $this->createitem->make(100, 14, false, [], $coins_left[$i], array_values($this->Mshop->generate_serial())[0], $serial2)->to_hex();
                                                                            } else{
                                                                                $this->vars['coin_data'][$key] = str_pad("", $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'), "F");
                                                                            }
                                                                        }
                                                                        $this->Mcharacter->add_multiple_new_coins($this->vars['coin_data']);
                                                                    } else{
                                                                        $new_coin_item = $this->createitem->make(100, 14, false, [], $new_coins, array_values($this->Mshop->generate_serial())[0], $serial2)->to_hex();
                                                                        $this->Mcharacter->add_new_coins(array_keys($this->vars['coin_data'])[0], $new_coin_item);
                                                                    }
                                                                }
																//$this->createitem->make($id, $cat, false, [], $new_coins, array_values($this->Mshop->generate_serial())[0], $serial2)->to_hex()
                                                                // $this->Mshop->generate_new_items($this->Mshop->generate_item_hex($id, $cat, $item_data[2], $item_data[3], $item_data[4], $lvl, $skill, $luck, $opt, $item_data[9], $item_data[10], $item_data[11], $item_data[12]), $space, $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_multiplier'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
                                                                $this->Maccount->add_account_log('Exchanged ' . $this->Mcharacter->vars['lucky_coin'] . ' Lucky coins to ' . $data['name'] . '', 0, $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                                                $this->Mshop->update_warehouse();
                                                                $this->vars['success'] = sprintf(__('Coins successfully exchanged To %s.'), $data['name']);
                                                            } else{
                                                                $this->vars['error'] = __('Unable to remove lucky coins from inventory.');
                                                            }
                                                        }
                                                    } else{
                                                        $this->vars['error'] = __('Please open your warehouse in game first.');
                                                    }
                                                } else{
                                                    $this->vars['error'] = __('Configuration element not found.');
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $this->vars['char_list'] = $this->Mcharacter->load_char_list();
                    $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.exchnage_lucky_coin', $this->vars);
                } else{
                    $this->disabled();
                }
            } else{
                $this->login();
            }
        }

        public function reset()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->model('account');
                $reset_config = $this->config->values('reset_config', $this->session->userdata(['user' => 'server']));
                if(!$reset_config){
                    $this->vars['error'] = __('Reset configuration for this server not found.');
                } else{
                    if($reset_config['allow_reset'] == 0){
                        $this->vars['error'] = __('Reset function is disabled for this server');
                    } else{
                        unset($reset_config['allow_reset']);
                        $this->vars['char_list'] = $this->Mcharacter->load_char_list();
                        $this->vars['chars'] = [];
                        $this->vars['res_info'] = [];
                        if($this->vars['char_list'] != false){
                            foreach($this->vars['char_list'] AS $char){
                                foreach($reset_config AS $key => $values){
                                    list($start_res, $end_res) = explode('-', $key);
                                    if($char['resets'] >= $start_res && $char['resets'] < $end_res){
                                        $this->vars['res_info'][$char['name']] = $values;
                                        break;
                                    }
                                }
                                $this->vars['chars'][$char['name']] = ['level' => $char['level'], 'Class' => $char['Class'], 'resets' => $char['resets'], 'gresets' => $char['gresets'], 'money' => $char['money'], 'res_info' => isset($this->vars['res_info'][$char['name']]) ? $this->vars['res_info'][$char['name']] : false];
                            }
                        } else{
                            $this->vars['error'] = __('Character not found.');
                        }
                    }
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.reset_character', $this->vars);
            } else{
                $this->login();
            }
        }

        public function grand_reset()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->model('account');
                $reset_config = $this->config->values('reset_config', $this->session->userdata(['user' => 'server']));
                $greset_config = $this->config->values('greset_config', $this->session->userdata(['user' => 'server']));
                if(!$greset_config){
                    $this->vars['error'] = __('Grand Reset configuration for this server not found.');
                } else{
                    if($greset_config['allow_greset'] == 0){
                        $this->vars['error'] = __('Grand Reset function is disabled for this server');
                    } else{
                        unset($greset_config['allow_greset']);
                        if(isset($reset_config)){
                            unset($reset_config['allow_reset']);
                        }
                        $this->vars['char_list'] = $this->Mcharacter->load_char_list();
                        $this->vars['chars'] = [];
                        $this->vars['gres_info'] = [];
                        if($this->vars['char_list'] != false){
                            foreach($this->vars['char_list'] AS $char){
                                foreach($greset_config AS $key => $values){
                                    list($start_gres, $end_gres) = explode('-', $key);
                                    if($char['gresets'] >= $start_gres && $char['gresets'] < $end_gres){
                                        $this->vars['gres_info'][$char['name']] = $values;
                                    }
                                }
                                $bonus_reset_stats = 0;
                                if(isset($this->vars['gres_info'][$char['name']])){
                                    if($this->vars['gres_info'][$char['name']]['bonus_reset_stats'] == 1){
                                        $reset_data = [];
                                        foreach($reset_config AS $key => $values){
                                            $reset_range = explode('-', $key);
                                            for($i = $reset_range[0]; $i < $reset_range[1]; $i++){
                                                $reset_data[$i] = $values['bonus_points'];
                                            }
                                        }
                                        foreach($reset_data AS $res => $data){
                                            if($char['resets'] <= $res)
                                                break;
                                            $bonus_reset_stats += $data[$this->Mcharacter->class_code_to_readable($char['Class'])];
                                        }
                                    }
                                }
                                $this->vars['chars'][$char['name']] = ['level' => $char['level'], 'Class' => $char['Class'], 'resets' => $char['resets'], 'gresets' => $char['gresets'], 'money' => $char['money'], 'gres_info' => isset($this->vars['gres_info'][$char['name']]) ? $this->vars['gres_info'][$char['name']] : false, 'bonus_reset_stats' => $bonus_reset_stats];
                            }
                        } else{
                            $this->vars['error'] = __('Character not found.');
                        }
                    }
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.greset_character', $this->vars);
            } else{
                $this->login();
            }
        }

        public function add_stats($char = '')
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->model('account');
                if(!$char){
                    $this->vars['char_list'] = $this->Mcharacter->load_char_list();
                    $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.add_stats_info', $this->vars);
                } else{
                    if(!$this->Mcharacter->check_char($this->website->hex2bin($char))){
                        $this->vars['not_found'] = __('Character not found.');
                    }
                    if(count($_POST) > 0){
                        foreach($_POST as $key => $value){
                            $this->Mcharacter->$key = trim($value);
                        }
                        if(!$this->Maccount->check_connect_stat())
                            $this->vars['error'] = __('Please logout from game.'); else{
                            $this->Mcharacter->check_stats();
                            if(!preg_match('/^(\s*|[0-9]+)$/', $this->Mcharacter->vars['str_stat']))
                                $this->vars['error'] = __('Only positive values allowed in') . ' ' . __('Strength') . '.'; else{
                                if(!preg_match('/^(\s*|[0-9]+)$/', $this->Mcharacter->vars['agi_stat']))
                                    $this->vars['error'] = __('Only positive values allowed in') . ' ' . __('Agility') . '.'; else{
                                    if(!preg_match('/^(\s*|[0-9]+)$/', $this->Mcharacter->vars['ene_stat']))
                                        $this->vars['error'] = __('Only positive values allowed in') . ' ' . __('Energy') . '.'; else{
                                        if(!preg_match('/^(\s*|[0-9]+)$/', $this->Mcharacter->vars['vit_stat']))
                                            $this->vars['error'] = __('Only positive values allowed in') . ' ' . __('Vitality') . '.'; else{
                                            if(!preg_match('/^(\s*|[0-9]+)$/', $this->Mcharacter->vars['com_stat']))
                                                $this->vars['error'] = __('Only positive values allowed in') . ' ' . __('Command') . '.'; else{
                                                $this->Mcharacter->set_new_stats();
                                                if(!$this->Mcharacter->check_max_stat_limit())
                                                    $this->vars['error'] = $this->Mcharacter->vars['error']; else{
                                                    if($this->Mcharacter->vars['new_lvlup'] < 0)
                                                        $this->vars['error'] = __('Only positive values allowed in') . ' ' . __('Level Up Points') . '.'; else{
                                                        $this->Mcharacter->add_stats($this->website->hex2bin($char));
                                                        $this->Mcharacter->check_char($this->website->hex2bin($char));
                                                        $this->vars['success'] = __('Stats Have Been Successfully Added.');
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.add_stats', $this->vars);
                }
            } else{
                $this->login();
            }
        }

        public function reset_stats()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['char_list'] = $this->Mcharacter->load_char_list();
                $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.reset_stats', $this->vars);
            } else{
                $this->login();
            }
        }

        public function hide_info()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->model('account');
                $this->vars['hide_time'] = $this->Maccount->check_hide_time();
                $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.hide_info', $this->vars);
            } else{
                $this->login();
            }
        }

        public function clear_skilltree()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['char_list'] = $this->Mcharacter->load_char_list();
                $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.clear_skill_tree', $this->vars);
            } else{
                $this->login();
            }
        }

        public function clear_inventory()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['char_list'] = $this->Mcharacter->load_char_list();
                $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.clear_inventory', $this->vars);
            } else{
                $this->login();
            }
        }

        public function buy_zen()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['char_list'] = $this->Mcharacter->load_char_list();
                $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.buy_zen', $this->vars);
            } else{
                $this->login();
            }
        }

        public function warp_char()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->model('account');
                $this->vars['char_list'] = $this->Mcharacter->load_char_list();
                if(isset($_POST['character'])){
                    foreach($_POST as $key => $value){
                        $this->Mcharacter->$key = trim($value);
                    }
                    if(!$this->Maccount->check_connect_stat())
                        $this->vars['error'] = __('Please logout from game.'); 
					else{
                        if(!$this->Mcharacter->check_char())
                            $this->vars['error'] = __('Character not found.'); 
						else{
                            if(!$this->Mcharacter->teleports($this->Mcharacter->vars['world']))
                                $this->vars['error'] = __('Invalid location selected.'); 
							else{
                                $this->Mcharacter->teleport_char();
                                $this->vars['success'] = __('Character successfully teleported.');
                            }
                        }
                    }
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.warp_char', $this->vars);
            } else{
                $this->login();
            }
        }

        public function recover_master()
        {
            if(defined('RES_CUSTOM_BACKUP_MASTER') && RES_CUSTOM_BACKUP_MASTER == true){
                if($this->session->userdata(['user' => 'logged_in'])){
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->model('account');
                    $this->vars['char_list'] = $this->Mcharacter->load_char_list();
                    if(isset($_POST['character'])){
                        foreach($_POST as $key => $value){
                            $this->Mcharacter->$key = trim($value);
                        }
                        if(!$this->Maccount->check_connect_stat())
                            $this->vars['error'] = __('Please logout from game.'); 
						else{
                            if(!$this->Mcharacter->check_char('', ', Master'))
                                $this->vars['error'] = __('Character not found.'); 
							else{
                                if(!in_array($this->Mcharacter->char_info['Class'], [2, 3, 7, 18, 19, 23, 34, 35, 39, 49, 50, 54, 65, 66, 70, 82, 83, 87, 97, 98, 102, 114, 118])){
                                    if($this->Mcharacter->char_info['cLevel'] < 400 && $this->Mcharacter->char_info[$this->config->values('table_config', [$this->session->userdata(['user' => 'server']), 'resets', 'column'])] == 0){
                                        $this->vars['error'] = __('Your lvl, or grand resets are too low.');
                                    } else{
                                        if($this->Mcharacter->char_info['Master'] >= 1){
                                            $this->Mcharacter->restore_master_level();
                                            $this->vars['success'] = __('Your master level and master class have been restored.');
                                        } else{
                                            $this->vars['error'] = __('You don\'t have master level points');
                                        }
                                    }
                                } else{
                                    $this->vars['error'] = __('You are not allowed to recover master level.');
                                }
                            }
                        }
                    }
                    $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.recover_char', $this->vars);
                } else{
                    $this->login();
                }
            } else{
                $this->disabled();
            }
        }

        public function pk_clear()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['char_list'] = $this->Mcharacter->load_char_list();
                $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.pk_clear', $this->vars);
            } else{
                $this->login();
            }
        }

        public function vote_reward()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['votereward_config'] = $this->config->values('votereward_config', $this->session->userdata(['user' => 'server']));
                if($this->vars['votereward_config']['active'] == 1){
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->model('account');
                    if($this->vars['votereward_config']['req_char'] == 1){
                        $this->vars['has_char'] = ($info = $this->Mcharacter->load_char_list()) ? $info : false;
                    }
                    if(!isset($this->vars['has_char']) || $this->vars['has_char'] != false){
                        $votelinks = $this->Maccount->load_vote_links();
                        $this->vars['content'] = [];
                        foreach($votelinks as $links){
                            if($links['api'] == 1){
                                $links['votelink'] = $links['votelink'] . '&amp;postback=' . $this->session->userdata(['user' => 'id']);
                                $countdown = $this->vars['votereward_config']['count_down'] + 20;
                            } else if($links['api'] == 3){
                                $links['votelink'] = $links['votelink'] . '&amp;pingUsername=' . $this->session->userdata(['user' => 'id']);
                                $countdown = $this->vars['votereward_config']['count_down'] + 20;
                            } else if($links['api'] == 4){
                                $links['votelink'] = $links['votelink'] . '-' . $this->session->userdata(['user' => 'id']);
                                $countdown = $this->vars['votereward_config']['count_down'] + 20;
                            } else if($links['api'] == 5){
                                $links['votelink'] = $links['votelink'] . '&amp;incentive=' . $this->session->userdata(['user' => 'id']);
                                $countdown = $this->vars['votereward_config']['count_down'] + 20;
                            } else if($links['api'] == 6){
                                $links['votelink'] = str_replace('[USER]', $this->session->userdata(['user' => 'id']), $links['votelink']);
                                $countdown = $this->vars['votereward_config']['count_down'];
                            } else if($links['api'] == 8){
                                $links['votelink'] = $links['votelink'] . '?postback=' . $this->session->userdata(['user' => 'id']);
                                $countdown = $this->vars['votereward_config']['count_down'] + 20;
                            } else if($links['api'] == 9){
                                $links['votelink'] = $links['votelink'] . '&amp;custom=' . $this->session->userdata(['user' => 'id']);
                                $countdown = $this->vars['votereward_config']['count_down'] + 20;
                            } else{
                                $countdown = $this->vars['votereward_config']['count_down'];
                            }
                            $check_last_vote = $this->Maccount->get_last_vote($links['id'], $links['hours'], $links['api'], $this->vars['votereward_config']['xtremetop_same_acc_vote'], $this->vars['votereward_config']['xtremetop_link_numbers']);
                            if($check_last_vote != false){
                                $this->vars['content'][] = ['id' => $links['id'], 'link' => $links['votelink'], 'name' => $links['name'], 'image' => $links['img_url'], 'voted' => 1, 'next_vote' => $this->Maccount->calculate_next_vote($check_last_vote, $links['hours']), 'api' => $links['api'], 'reward' => $links['reward'], 'reward_type' => $links['reward_type'], 'reward_sms' => ($links['api'] == 2) ? $links['mmotop_reward_sms'] : 0, 'countdown' => $countdown];
                            } else{
                                $this->vars['content'][] = ['id' => $links['id'], 'link' => $links['votelink'], 'name' => $links['name'], 'image' => $links['img_url'], 'voted' => 0, 'next_vote' => '', 'api' => $links['api'], 'reward' => $links['reward'], 'reward_type' => $links['reward_type'], 'reward_sms' => ($links['api'] == 2) ? $links['mmotop_reward_sms'] : 0, 'countdown' => $countdown];
                            }
                        }
                    }
                    if(defined('IS_GOOGLE_ADD_VOTE') && IS_GOOGLE_ADD_VOTE == true){
                        $this->vars['last_ads_vote'] = $this->Maccount->get_last_ads_vote(GOOGLE_ADD_TIME);
                    }
                    $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.votereward', $this->vars);
                } else{
                    $this->disabled();
                }
            } else{
                $this->login();
            }
        }

        public function settings()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['config'] = $this->config->values('registration_config');
                if(isset($_POST['recover_master_key'])){
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->model('account');
                    if(!$this->Maccount->check_connect_stat()){
                        $this->vars['error'] = __('Please logout from game.');
                    } else{
                        if(!$this->Maccount->recover_master_key_process()){
                            $this->vars['success'] = __('Your master key has been send to your email.');
                        } else{
                            if(isset($this->Maccount->error)){
                                $this->vars['error'] = $this->Maccount->error;
                            } else{
                                $this->vars['error'] = __('Unable to recover master key.');
                            }
                        }
                    }
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.settings', $this->vars);
            } else{
                $this->login();
            }
        }

        public function email_confirm($code)
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->model('account');
                $code = strtolower(trim(preg_replace('/[^0-9a-f]/i', '', $code)));
                $this->vars['set_new_email'] = false;
                if(strlen($code) <> 40){
                    $this->vars['error'] = __('Invalid email confirmation code');
                } else{
                    $data = $this->Maccount->load_email_confirmation_by_code($code);
                    if($data){
                        if($data['old_email'] == 0){
                            if($this->Maccount->update_email($data['account'], $data['email'])){
                                $this->Maccount->delete_old_confirmation_entries($data['account']);
                                $this->vars['success'] = __('Email address successfully updated.');
                            }
                        } else{
                            $this->vars['set_new_email'] = true;
                        }
                    } else{
                        $this->vars['error'] = __('Confirmation code does not exist in database.');
                    }
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.set_new_email', $this->vars);
            } else{
                $this->login();
            }
        }

        public function exchange_wcoins()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['wcoin_config'] = $this->config->values('wcoin_exchange_config', $this->session->userdata(['user' => 'server']));
                if($this->vars['wcoin_config'] != false && $this->vars['wcoin_config']['active'] == 1){
                    $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.exchange_wcoins', $this->vars);
                } else{
                    $this->disabled();
                }
            } else{
                $this->login();
            }
        }

        public function logs($page = 1)
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->model('account');
                $this->load->lib("pagination");
                $this->vars['logs'] = $this->Maccount->load_logs($page, $this->config->config_entry('account|account_logs_per_page'));
                $this->pagination->initialize($page, $this->config->config_entry('account|account_logs_per_page'), $this->Maccount->count_total_logs(), $this->config->base_url . 'account-panel/logs/%s');
                $this->vars['pagination'] = $this->pagination->create_links();
                $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.logs', $this->vars);
            } else{
                $this->login();
            }
        }

        public function zen_wallet()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->model('account');
                if(!$this->Maccount->check_connect_stat())
                    $this->vars['error'] = __('Please logout from game.'); else{
                    $this->load->model('warehouse');
                    if($this->Mwarehouse->get_vault_content()){
                        $this->vars['wh_zen'] = $this->Mwarehouse->vault_money;
                    }
                    $this->vars['char_list'] = $this->Mcharacter->load_char_list();
                    $this->vars['wallet_zen'] = $this->Maccount->load_wallet_zen();
					if(!$this->vars['wallet_zen']){
                        $this->vars['wallet_zen']['credits3'] = 0;
                    }							   
                    if(isset($_POST['transfer_zen'])){
                        $from = trim(isset($_POST['from']) ? $_POST['from'] : '');
                        $to = trim(isset($_POST['to']) ? $_POST['to'] : '');
                        $amount = trim(isset($_POST['zen']) ? $_POST['zen'] : '');
                        if($from == '')
                            $this->vars['error'] = __('You didn\'t select from where you want to send zen'); else{
                            if($to == '')
                                $this->vars['error'] = __('You didn\'t select to where you want to send zen'); else{
                                if(!preg_match('/^[0-9]+$/', $amount))
                                    $this->vars['error'] = __('Amount of zen you insert is invalid.'); else{
                                    if($from == $to){
                                        $this->vars['error'] = vsprintf(__('You can\'t send zen from %s to %s'), [$from, $to]);
                                    } else{
                                        if($from == 'webwallet'){
                                            if($this->vars['wallet_zen']['credits3'] < $amount)
                                                $this->vars['error'] = __('Amount of zen in your web wallet is too low.');
                                        } else if($from == 'warehouse'){
                                            if($this->vars['wh_zen'] < $amount)
                                                $this->vars['error'] = __('Amount of zen in your warehouse is too low.');
                                        } else{
                                            if($this->Mcharacter->check_char($from)){
                                                if($this->Mcharacter->char_info['Money'] < $amount)
                                                    $this->vars['error'] = sprintf(__('Amount of zen on %s is too low.'), $from);
                                            } else{
                                                $this->vars['error'] = __('Character not found.');
                                            }
                                        }
                                        if(!isset($this->vars['error'])){
                                            if($to == 'webwallet'){
                                                $this->website->add_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $amount, 3, false, $this->session->userdata(['user' => 'id']));
                                                if($from == 'warehouse'){
                                                    $this->Mwarehouse->decrease_zen($this->session->userdata(['user' => 'username']), $amount);
                                                } else{
                                                    $this->Mcharacter->decrease_zen($this->session->userdata(['user' => 'username']), $amount, $from);
                                                }
                                            } else if($to == 'warehouse'){
                                                if($amount > $this->config->config_entry('account|max_ware_zen'))
                                                    $this->vars['error'] = sprintf(__('Max zen than can be send to warehouse is %s'), $this->website->zen_format($this->config->config_entry('account|max_ware_zen'))); else{
                                                    if(((int)$amount + $this->vars['wh_zen']) > $this->config->config_entry('account|max_ware_zen'))
                                                        $this->vars['error'] = __('Your warehouse zen limit exceeded. Try to transfer lower amount.'); else{
                                                        $this->Mwarehouse->add_zen($this->session->userdata(['user' => 'username']), $amount);
                                                        if($from == 'webwallet'){
															$this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $amount, 3, $this->session->userdata(['user' => 'id']));
                                                        } else{
                                                            $this->Mcharacter->decrease_zen($this->session->userdata(['user' => 'username']), $amount, $from);
                                                        }
                                                    }
                                                }
                                            } else{
                                                if($amount > $this->config->config_entry('account|max_char_zen'))
                                                    $this->vars['error'] = sprint(__('Max zen than can be send to character is %s'), $this->website->zen_format($this->config->config_entry('account|max_char_zen'))); else{
                                                    $this->Mcharacter->check_char($to);
                                                    if(((int)$amount + $this->Mcharacter->char_info['Money']) > $this->config->config_entry('account|max_char_zen'))
                                                        $this->vars['error'] = __('Your character zen limit exceeded. Try to transfer lower amount.'); else{
                                                        $this->Mcharacter->add_zen($this->session->userdata(['user' => 'username']), $amount, $to);
                                                        if($from == 'webwallet'){
                                                            $this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $amount, 3, $this->session->userdata(['user' => 'id']));
                                                        } else if($from == 'warehouse'){
                                                            $this->Mwarehouse->decrease_zen($this->session->userdata(['user' => 'username']), $amount);
                                                        } else{
                                                            $this->Mcharacter->decrease_zen($this->session->userdata(['user' => 'username']), $amount, $from);
                                                        }
                                                    }
                                                }
                                            }
                                            if(!isset($this->vars['error'])){
                                                $this->vars['success'] = __('Zen was successfully transferred.');
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.zen_wallet', $this->vars);
            } else{
                $this->login();
            }
        }

        public function my_referral_list()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->model('account');
                $this->vars['my_referral_list'] = $this->Maccount->load_my_referrals();
                if(!empty($this->vars['my_referral_list'])){
                    foreach($this->vars['my_referral_list'] as $key => $referrals){
                        $this->vars['my_referral_list'][$key]['ref_chars'] = $this->Mcharacter->load_chars_from_ref($referrals['refferal'], $this->session->userdata(['user' => 'server']));
                    }
                    $this->vars['ref_rewards'] = $this->Maccount->load_referral_rewards();
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.my_referral_list', $this->vars);
            } else{
                $this->login();
            }
        }

        public function get_reward()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                if(isset($_POST['get_reward'])){
                    if(!$this->Mcharacter->check_reward()){
                        $this->Mcharacter->log_reward();
                        $this->Mcharacter->add_reward();
                        $this->vars['success'] = 'You have received 450 Wcoins for free.';
                    } else{
                        $this->vars['error'] = 'You have been already rewarded on this server';
                    }
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.get_reward', $this->vars);
            } else{
                $this->login();
            }
        }

        public function exchange_online()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($this->website->is_multiple_accounts() == true){
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                } else{
                    $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                }
                $this->load->model('account');
                $this->vars['online_time'] = $this->Maccount->load_online_hours();
                if($this->vars['online_time'] == false){
                    $this->vars['online_time'] = 0;
                    $this->vars['minutes_left'] = 0;
                } else{
                    $this->vars['OnlineMinutes'] = $this->vars['online_time']['OnlineMinutes'];
                    $this->vars['online_time'] = floor($this->vars['OnlineMinutes'] / 60);
                    $this->vars['minutes_left'] = $this->vars['OnlineMinutes'] - (floor($this->vars['OnlineMinutes'] / 60) * 60);
                }
                if(isset($_POST['trade_hours'])){
                    if(!$this->Maccount->check_connect_stat())
                        $this->vars['error'] = __('Please logout from game.'); else{
                        if($this->vars['online_time'] <= 0)
                            $this->vars['error'] = __('You don\'t have online time on this server'); else{
                            if($this->Maccount->exchange_online_hours($this->vars['online_time'], $this->vars['minutes_left'])){
                                $this->vars['success'] = 'Online time successfully exchanged';
                                $this->vars['online_time'] = 0;
                            } else{
                                $this->vars['error'] = __('Unable to exchange online time');
                            }
                        }
                    }
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.exchange_online', $this->vars);
            } else{
                $this->login();
            }
        }

        public function login()
        {
            $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.login');
        }

        public function login_with_facebook()
        {
            $this->load->lib('fb');
            $this->fb->check_fb_user();
            if(isset($_SESSION['fb_access_token'])){
                $email = $this->fb->getEmail();
                try{
                    if($this->website->is_multiple_accounts() == true){
                        if(isset($_POST['server'])){
                            $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($_POST['server'], true)]);
                            $this->load->model('account');
                            if($info = $this->Maccount->check_fb_user($email, $_POST['server'])){
                                $this->Maccount->clear_login_attemts();
                                header('Location: ' . $this->config->base_url . 'account-panel');
                            } else{
                                $this->fb_register($_POST['server'], $this->vars['user_profile']['email']);
                            }
                        }
                        $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.fb_login');
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                        $this->load->model('account');
                        $server_list = array_keys($this->website->server_list());
                        if($info = $this->Maccount->check_fb_user($this->fb->getEmail(), $server_list[0])){
                            $this->Maccount->clear_login_attemts();
                            header('Location: ' . $this->config->base_url . 'account-panel');
                        } else{
                            header('Location: ' . $this->config->base_url . 'registration/create-account-with-fb/' . $server_list[0] . '/' . urlencode($email));
                        }
                    }
                } catch(FacebookApiException $e){
                    unset($_SESSION['fb_access_token']);
                    throw new exception($e->getMessage());
                }
            }
        }

        public function logout()
        {
            $email = $this->session->userdata(['user' => 'email']);
            $id = $this->session->userdata(['user' => 'ipb_id']);
            $this->session->unset_session_key('user');
			$this->session->unset_session_key('vip');
            if(defined('IPS_CONNECT') && IPS_CONNECT == true){
                $this->load->lib('ipb');
                if($this->ipb->checkEmail($email) == true){
                    $this->ipb->crossLogout($id, $this->config->base_url);
                } else{
                    header('Location: ' . $this->config->base_url);
                }
            } else{
                header('Location: ' . $this->config->base_url);
            }
        }

        public function disabled()
        {
            $this->load->view($this->config->config_entry('main|template') . DS . 'view.module_disabled');
        }
    }