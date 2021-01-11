<?php
    in_file();

    class _plugin_character_market extends controller implements pluginInterface
    {
        private $pluginaizer;
        private $vars = [];

        /**
         *
         * Plugin constructor
         * Initialize plugin class
         *
         */
        public function __construct()
        {
            //initialize parent constructor
            parent::__construct();
            //initialize pluginaizer
            $this->pluginaizer = $this->load_class('plugin');
            //set plugin class name
            $this->pluginaizer->set_plugin_class(substr(get_class($this), 8));
        }

        /**
         *
         * Main module body
         * All main things related to user side
         *
         *
         * Return mixed
         */
        public function index($page = 1)
        {
            if($this->pluginaizer->data()->value('installed') == false){
                throw new Exception('Plugin has not yet been installed.');
            } else{
                if($this->pluginaizer->data()->value('installed') == 1){
                    if($this->pluginaizer->data()->value('is_public') == 0){
                        $this->user_module($page);
                    } else{
                        $this->public_module();
                    }
                } else{
                    throw new Exception('Plugin has been disabled.');
                }
            }
        }

        /**
         *
         * Load user module data
         *
         * return mixed
         *
         */
        private function user_module($page = 1)
        {
            //check if visitor has user privilleges
            if($this->pluginaizer->session->is_user()){
                //load website helper
                $this->load->helper('website');
                //load paginator
                $this->load->lib('pagination');
                //load plugin config
                $this->vars['plugin_config'] = $this->pluginaizer->plugin_config();
                if($this->vars['plugin_config'] != false && !empty($this->vars['plugin_config'])){
                    if($this->pluginaizer->data()->value('is_multi_server') == 1){
                        if(array_key_exists($this->pluginaizer->session->userdata(['user' => 'server']), $this->vars['plugin_config'])){
                            $this->vars['plugin_config'] = $this->vars['plugin_config'][$this->pluginaizer->session->userdata(['user' => 'server'])];
                            $this->vars['about'] = $this->pluginaizer->get_about();
                            $this->vars['about']['user_description'] = $this->pluginaizer->data()->value('description');
                        } else{
                            $this->vars['config_not_found'] = __('Plugin configuration not found.');
                        }
                    }
                    if($this->vars['plugin_config']['active'] == 0){
                        $this->vars['module_disabled'] = __('This module has been disabled.');
                    } else{
                        $this->load->model('application/plugins/character_market/models/character_market');
                        $this->pluginaizer->Mcharacter_market->count_total_chars($this->pluginaizer->session->userdata(['user' => 'server']));
                        $this->pluginaizer->pagination->initialize($page, $this->vars['plugin_config']['characters_per_page'], $this->pluginaizer->Mcharacter_market->total_characters, $this->config->base_url . str_replace('_', '-', $this->pluginaizer->get_plugin_class()) . '/index/%s');
                        $this->vars['chars'] = $this->pluginaizer->Mcharacter_market->load_market_chars($page, $this->vars['plugin_config']['characters_per_page'], $this->pluginaizer->session->userdata(['user' => 'server']), $this->vars['plugin_config']['sale_tax']);
                        $this->vars['pagination'] = $this->pluginaizer->pagination->create_links();
                    }
                } else{
                    $this->vars['config_not_found'] = __('Plugin configuration not found.');
                }
                //set js
                $this->vars['js'] = $this->config->base_url . 'assets/plugins/js/character_market.js';
                //load template
                $this->load->view('plugins' . DS . $this->pluginaizer->get_plugin_class() . DS . 'views' . DS . $this->config->config_entry('main|template') . DS . 'view.character_market', $this->vars);
            } else{
                $this->pluginaizer->redirect($this->config->base_url . 'account-panel/login?return=' . str_replace('_', '-', $this->pluginaizer->get_plugin_class()));
            }
        }

        public function sell_character()
        {
            //check if visitor has user privilleges
            if($this->pluginaizer->session->is_user()){
                //load website helper
                $this->load->helper('website');
                //load plugin config
                $this->vars['plugin_config'] = $this->pluginaizer->plugin_config();
                if($this->vars['plugin_config'] != false && !empty($this->vars['plugin_config'])){
                    if($this->pluginaizer->data()->value('is_multi_server') == 1){
                        if(array_key_exists($this->pluginaizer->session->userdata(['user' => 'server']), $this->vars['plugin_config'])){
                            $this->vars['plugin_config'] = $this->vars['plugin_config'][$this->pluginaizer->session->userdata(['user' => 'server'])];
                            $this->vars['about'] = $this->pluginaizer->get_about();
                            $this->vars['about']['user_description'] = $this->pluginaizer->data()->value('description');
                        } else{
                            $this->vars['config_not_found'] = __('Plugin configuration not found.');
                        }
                    }
                    if($this->vars['plugin_config']['active'] == 0){
                        $this->vars['module_disabled'] = __('This module has been disabled.');
                    } else{
                        $this->load->model('application/plugins/character_market/models/character_market');
                        if(isset($_POST['sell_character'])){
                            foreach($_POST as $key => $value){
                                $this->pluginaizer->Mcharacter_market->$key = trim($value);
                            }
                            if(!$this->pluginaizer->Mcharacter_market->check_connect_stat($this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server'])))
                                $this->vars['error'] = __('Please logout from game.'); else{
                                if(!isset($this->pluginaizer->Mcharacter_market->vars['mcharacter']))
                                    $this->vars['error'] = __('Please select merchant character.'); else{
                                    if(!$this->pluginaizer->Mcharacter_market->check_char($this->pluginaizer->Mcharacter_market->vars['mcharacter'], $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server'])))
                                        $this->vars['error'] = __('Merchant character not found.'); else{
                                        if(!isset($this->pluginaizer->Mcharacter_market->vars['scharacter']))
                                            $this->vars['error'] = __('Please select sale character.'); else{
                                            if(!$this->pluginaizer->Mcharacter_market->check_char($this->pluginaizer->Mcharacter_market->vars['scharacter'], $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server'])))
                                                $this->vars['error'] = __('Sale character not found.'); else{
                                                if($this->pluginaizer->Mcharacter_market->vars['mcharacter'] == $this->pluginaizer->Mcharacter_market->vars['scharacter'])
                                                    $this->vars['error'] = __('Please select different merchant and sale characters.'); else{
                                                    if(!in_array($this->pluginaizer->Mcharacter_market->vars['payment_method'], [1, 2]))
                                                        $this->vars['error'] = __('Please select valid payment method.'); else{
                                                        if($this->pluginaizer->Mcharacter_market->vars['time'] == '' || !in_array($this->pluginaizer->Mcharacter_market->vars['time'], [1, 2, 3, 4, 5, 7, 14]))
                                                            $this->vars['error'] = __('Please select time.'); else{
                                                            if(!isset($this->pluginaizer->Mcharacter_market->vars['price']))
                                                                $this->vars['error'] = __('Please enter price.'); else{
                                                                if($this->pluginaizer->Mcharacter_market->vars['price'] > $this->vars['plugin_config']['sale_price_maximum'])
                                                                    $this->vars['error'] = vsprintf(__('Max price can be %d %s'), [$this->vars['plugin_config']['sale_price_maximum'], $this->pluginaizer->website->translate_credits($this->pluginaizer->Mcharacter_market->vars['payment_method'], $this->pluginaizer->session->userdata(['user' => 'server']))]); else{
                                                                    $this->pluginaizer->Mcharacter_market->char_info($this->pluginaizer->Mcharacter_market->vars['scharacter'], $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                                    if($this->pluginaizer->Mcharacter_market->char_info['CtlCode'] == 1)
                                                                        $this->vars['error'] = __('You can not sell banned character.'); else{
                                                                        if($this->pluginaizer->Mcharacter_market->char_info['CtlCode'] == 32)
                                                                            $this->vars['error'] = __('You can not sell gm character.'); else{
                                                                            if($this->pluginaizer->Mcharacter_market->vars['price'] < $this->vars['plugin_config']['sale_price_minimum'])
                                                                                $this->vars['error'] = vsprintf(__('Min price can be %d %s'), [$this->vars['plugin_config']['sale_price_minimum'], $this->pluginaizer->website->translate_credits($this->pluginaizer->Mcharacter_market->vars['payment_method'], $this->pluginaizer->session->userdata(['user' => 'server']))]); else{
                                                                                if($this->vars['plugin_config']['allow_sell_with_gens'] == 0 && $this->pluginaizer->Mcharacter_market->get_gens_info($this->pluginaizer->Mcharacter_market->vars['scharacter'], $this->pluginaizer->session->userdata(['user' => 'server'])))
                                                                                    $this->vars['error'] = __('You are not allowed to sell character with gens.'); else{
                                                                                    if($this->vars['plugin_config']['allow_sell_with_guild'] == 0){
                                                                                        if($this->pluginaizer->Mcharacter_market->has_guild($this->pluginaizer->Mcharacter_market->vars['scharacter'], $this->pluginaizer->session->userdata(['user' => 'server'])))
                                                                                            $this->vars['error'] = __('You are not allowed to sell character while sale character is in guild.');
                                                                                    }
                                                                                    if(!isset($this->vars['error'])){
                                                                                        $this->pluginaizer->Mcharacter_market->insert_new_sale($this->pluginaizer->Mcharacter_market->char_info['id'], $this->pluginaizer->Mcharacter_market->char_info['Class'], $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                                                        $this->pluginaizer->Mcharacter_market->update_account_character($this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                                                        $this->pluginaizer->Mcharacter_market->update_character($this->pluginaizer->Mcharacter_market->char_info['id'], false, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                                                        $this->pluginaizer->Mcharacter_market->update_IGC_PeriodExpiredItemInfo('', $this->pluginaizer->session->userdata(['user' => 'server']));
                                                                                        $this->pluginaizer->Mcharacter_market->update_IGC_PeriodItemInfo('', $this->pluginaizer->session->userdata(['user' => 'server']));
                                                                                        $this->pluginaizer->Mcharacter_market->update_T_LUCKY_ITEM_INFO('', $this->pluginaizer->session->userdata(['user' => 'server']));
                                                                                        $this->pluginaizer->Mcharacter_market->update_T_MuRummy(false, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                                                        $this->pluginaizer->Mcharacter_market->update_T_MuRummyInfo(false, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                                                        $this->pluginaizer->Mcharacter_market->update_T_MuRummyLog(false, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                                                        $this->pluginaizer->Mcharacter_market->update_T_PentagramInfo(false, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                                                        $this->pluginaizer->Mcharacter_market->update_T_PSHOP_ITEMVALUE_INFO(false, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                                                        $this->pluginaizer->Mcharacter_market->update_PetWarehouse(false, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                                                        $this->vars['success'] = __('Character successfully sold.');
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $this->vars['char_list'] = $this->pluginaizer->Mcharacter_market->load_char_list($this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                    }
                } else{
                    $this->vars['config_not_found'] = __('Plugin configuration not found.');
                }
                //set js
                $this->vars['js'] = $this->config->base_url . 'assets/plugins/js/character_market.js';
                //load template
                $this->load->view('plugins' . DS . $this->pluginaizer->get_plugin_class() . DS . 'views' . DS . $this->config->config_entry('main|template') . DS . 'view.sell_character', $this->vars);
            } else{
                $this->pluginaizer->redirect($this->config->base_url . 'account-panel/login?return=' . str_replace('_', '-', $this->pluginaizer->get_plugin_class()) . '/sell-character');
            }
        }

        public function buy($id = '')
        {
            //check if visitor has user privilleges
            if($this->pluginaizer->session->is_user()){
                //load website helper
                $this->load->helper('website');
                //load plugin config
                $this->vars['plugin_config'] = $this->pluginaizer->plugin_config();
                if($this->vars['plugin_config'] != false && !empty($this->vars['plugin_config'])){
                    if($this->pluginaizer->data()->value('is_multi_server') == 1){
                        if(array_key_exists($this->pluginaizer->session->userdata(['user' => 'server']), $this->vars['plugin_config'])){
                            $this->vars['plugin_config'] = $this->vars['plugin_config'][$this->pluginaizer->session->userdata(['user' => 'server'])];
                            $this->vars['about'] = $this->pluginaizer->get_about();
                            $this->vars['about']['user_description'] = $this->pluginaizer->data()->value('description');
                        } else{
                            $this->vars['config_not_found'] = __('Plugin configuration not found.');
                        }
                    }
                    if($this->vars['plugin_config']['active'] == 0){
                        $this->vars['module_disabled'] = __('This module has been disabled.');
                    } else{
                        $this->load->model('application/plugins/character_market/models/character_market');
                        if($this->vars['character_info'] = $this->pluginaizer->Mcharacter_market->check_char_in_market($id, $this->pluginaizer->session->userdata(['user' => 'server']))){
                            if($this->vars['character_info']['is_sold'] == 1)
                                $this->vars['error'] = __('This character has been sold already.'); else{
                                if($this->vars['character_info']['removed'] == 1)
                                    $this->vars['error'] = __('This character has been removed.'); else{
                                    if($this->vars['character_info']['end_date'] < time())
                                        $this->vars['error'] = __('This sale has expire.'); else{
                                        $this->load->lib("itemimage");
                                        $this->load->lib("iteminfo");
                                        $this->pluginaizer->Mcharacter_market->char_info($this->vars['character_info']['mu_id'], 'dmnmark987', $this->vars['character_info']['server'], true);
                                        if($this->pluginaizer->Mcharacter_market->char_info != false){
                                            $this->pluginaizer->Mcharacter_market->scharacter = $this->pluginaizer->Mcharacter_market->char_info['Name'];
                                            $this->vars['equipment'] = $this->pluginaizer->Mcharacter_market->load_equipment($this->vars['character_info']['server']);
                                            $this->vars['inventory'] = $this->pluginaizer->Mcharacter_market->load_inventory(1, $this->vars['character_info']['server']);
                                            $this->vars['store'] = $this->pluginaizer->Mcharacter_market->load_inventory(2, $this->vars['character_info']['server']);
                                            $this->vars['guild_info'] = $this->pluginaizer->Mcharacter_market->get_guild_info($this->pluginaizer->Mcharacter_market->char_info['Name'], $this->vars['character_info']['server']);
                                            $this->vars['gens_info'] = $this->pluginaizer->Mcharacter_market->get_gens_info($this->pluginaizer->Mcharacter_market->char_info['Name'], $this->vars['character_info']['server']);
                                            if($this->pluginaizer->website->get_value_from_server($this->pluginaizer->session->userdata(['user' => 'server']), 'wh_size') > 1920){
                                                $this->vars['inventory2'] = $this->pluginaizer->Mcharacter_market->load_inventory(3, $this->vars['character_info']['server']);
                                                $this->vars['inventory3'] = $this->pluginaizer->Mcharacter_market->load_inventory(4, $this->vars['character_info']['server']);
                                            }
                                            if(isset($_POST['buy_character'])){
                                                if(!$this->pluginaizer->Mcharacter_market->check_connect_stat($this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']))){
                                                    $this->vars['error'] = __('Please logout from game.');
                                                } else{
													if($this->vars['character_info']['seller_acc'] == $this->pluginaizer->session->userdata(['user' => 'username']))
                                                        $this->vars['purchase_error'] = __('You can not purchase own character.'); 
													else{
                                                        if($space = $this->pluginaizer->Mcharacter_market->check_free_slot($this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']))){
                                                            $this->status = $this->pluginaizer->website->get_user_credits_balance($this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']), $this->vars['character_info']['price_type'], $this->pluginaizer->session->userdata(['user' => 'id']));
                                                            $this->price_with_tax = round($this->vars['character_info']['price'] + ($this->vars['character_info']['price'] / 100) * $this->vars['plugin_config']['sale_tax'], 0);
                                                            if($this->status['credits'] < $this->price_with_tax){
                                                                $this->vars['purchase_error'] = sprintf(__('You have insufficient amount of %s'), $this->pluginaizer->website->translate_credits($this->vars['character_info']['price_type'], $this->pluginaizer->session->userdata(['user' => 'server'])));
                                                            }
                                                            if(!isset($this->vars['purchase_error'])){
																if($this->pluginaizer->Mcharacter_market->add_to_account_character($space, $this->pluginaizer->Mcharacter_market->char_info['Name'], $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server'])) != false){                                                              
																	$this->pluginaizer->website->charge_credits($this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']), $this->price_with_tax, $this->vars['character_info']['price_type'], $this->pluginaizer->session->userdata(['user' => 'id']));
																	$this->pluginaizer->website->add_credits($this->vars['character_info']['seller_acc'], $this->vars['character_info']['server'], $this->vars['character_info']['price'], $this->vars['character_info']['price_type'], false, $this->pluginaizer->Mcharacter_market->get_guid($this->vars['character_info']['seller_acc'], $this->vars['character_info']['server']));
																	$this->pluginaizer->Mcharacter_market->add_account_log('Bought Market Character For ' . $this->pluginaizer->website->translate_credits($this->vars['character_info']['price_type'], $this->pluginaizer->session->userdata(['user' => 'server'])), -$this->price_with_tax, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
																	$this->pluginaizer->Mcharacter_market->add_account_log('Sold Market Character For ' . $this->pluginaizer->website->translate_credits($this->vars['character_info']['price_type'], $this->pluginaizer->session->userdata(['user' => 'server'])), $this->vars['character_info']['price'], $this->vars['character_info']['seller_acc'], $this->vars['character_info']['server']);
																	//$this->pluginaizer->Mcharacter_market->addRenameToken();
																	$this->pluginaizer->Mcharacter_market->update_sale_set_purchased($id, $this->pluginaizer->session->userdata(['user' => 'username']));
																																																																															  
																	$this->pluginaizer->Mcharacter_market->update_character($this->pluginaizer->Mcharacter_market->char_info['id'], true, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
																	$this->pluginaizer->Mcharacter_market->update_IGC_PeriodExpiredItemInfo($this->pluginaizer->session->userdata(['user' => 'id']), $this->pluginaizer->session->userdata(['user' => 'server']));
																	$this->pluginaizer->Mcharacter_market->update_IGC_PeriodItemInfo($this->pluginaizer->session->userdata(['user' => 'id']), $this->pluginaizer->session->userdata(['user' => 'server']));
																	$this->pluginaizer->Mcharacter_market->update_T_LUCKY_ITEM_INFO($this->pluginaizer->session->userdata(['user' => 'id']), $this->pluginaizer->session->userdata(['user' => 'server']));
																	$this->pluginaizer->Mcharacter_market->update_T_MuRummy(true, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
																	$this->pluginaizer->Mcharacter_market->update_T_MuRummyInfo(true, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
																	$this->pluginaizer->Mcharacter_market->update_T_MuRummyLog(true, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
																	$this->pluginaizer->Mcharacter_market->update_T_PentagramInfo(true, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
																	$this->pluginaizer->Mcharacter_market->update_T_PSHOP_ITEMVALUE_INFO(true, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
																	$this->pluginaizer->Mcharacter_market->update_PetWarehouse(true, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
																	header('Location: ' . $this->config->base_url . 'character-market/success');
																}
																else{
																	$this->vars['purchase_error'] = __('Please create atleast 1 character in your account before purchase.');
																}
														   }
                                                        } else{
                                                            $this->vars['purchase_error'] = __('All character slots are used. Please remove some character.');
                                                        }
                                                    }
                                                }
                                            }
                                        } else{
                                            $this->vars['error'] = __('Character not found.');
                                        }
                                    }
                                }
                            }
                        } else{
                            $this->vars['error'] = __('Character not found.');
                        }
                    }
                } else{
                    $this->vars['config_not_found'] = __('Plugin configuration not found.');
                }
                //load template
                $this->load->view('plugins' . DS . $this->pluginaizer->get_plugin_class() . DS . 'views' . DS . $this->config->config_entry('main|template') . DS . 'view.buy', $this->vars);
            } else{
                $this->pluginaizer->redirect($this->config->base_url . 'account-panel/login?return=' . str_replace('_', '-', $this->pluginaizer->get_plugin_class()) . '/buy/' . $id);
            }
        }

        public function sale_history()
        {
            //check if visitor has user privilleges
            if($this->pluginaizer->session->is_user()){
                //load website helper
                $this->load->helper('website');
                //load plugin config
                $this->vars['plugin_config'] = $this->pluginaizer->plugin_config();
                if($this->vars['plugin_config'] != false && !empty($this->vars['plugin_config'])){
                    if($this->pluginaizer->data()->value('is_multi_server') == 1){
                        if(array_key_exists($this->pluginaizer->session->userdata(['user' => 'server']), $this->vars['plugin_config'])){
                            $this->vars['plugin_config'] = $this->vars['plugin_config'][$this->pluginaizer->session->userdata(['user' => 'server'])];
                            $this->vars['about'] = $this->pluginaizer->get_about();
                            $this->vars['about']['user_description'] = $this->pluginaizer->data()->value('description');
                        } else{
                            $this->vars['config_not_found'] = __('Plugin configuration not found.');
                        }
                    }
                    if($this->vars['plugin_config']['active'] == 0){
                        $this->vars['module_disabled'] = __('This module has been disabled.');
                    } else{
                        $this->load->model('application/plugins/character_market/models/character_market');
                        $this->vars['chars'] = $this->pluginaizer->Mcharacter_market->load_market_history_chars($this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                    }
                } else{
                    $this->vars['config_not_found'] = __('Plugin configuration not found.');
                }
                //load template
                $this->load->view('plugins' . DS . $this->pluginaizer->get_plugin_class() . DS . 'views' . DS . $this->config->config_entry('main|template') . DS . 'view.history', $this->vars);
            } else{
                $this->pluginaizer->redirect($this->config->base_url . 'account-panel/login?return=' . str_replace('_', '-', $this->pluginaizer->get_plugin_class()) . '/sale-history');
            }
        }

        public function remove($id)
        {
            //check if visitor has user privilleges
            if($this->pluginaizer->session->is_user()){
                //load website helper
                $this->load->helper('website');
                //load plugin config
                $this->vars['plugin_config'] = $this->pluginaizer->plugin_config();
                if($this->vars['plugin_config'] != false && !empty($this->vars['plugin_config'])){
                    if($this->pluginaizer->data()->value('is_multi_server') == 1){
                        if(array_key_exists($this->pluginaizer->session->userdata(['user' => 'server']), $this->vars['plugin_config'])){
                            $this->vars['plugin_config'] = $this->vars['plugin_config'][$this->pluginaizer->session->userdata(['user' => 'server'])];
                            $this->vars['about'] = $this->pluginaizer->get_about();
                            $this->vars['about']['user_description'] = $this->pluginaizer->data()->value('description');
                        } else{
                            $this->vars['config_not_found'] = __('Plugin configuration not found.');
                        }
                    }
                    if($this->vars['plugin_config']['active'] == 0){
                        $this->vars['module_disabled'] = __('This module has been disabled.');
                    } else{
                        $this->load->model('application/plugins/character_market/models/character_market');
                        if($this->vars['character_info'] = $this->pluginaizer->Mcharacter_market->check_char_in_market($id, $this->pluginaizer->session->userdata(['user' => 'server']))){
                            if($this->vars['character_info']['is_sold'] == 1)
                                $this->vars['error'] = __('This character has been sold already.'); 
							else{
                                if($this->vars['character_info']['removed'] == 1)
                                    $this->vars['error'] = __('This character has been removed.'); 
								else{
                                    if($this->vars['character_info']['seller_acc'] != $this->pluginaizer->session->userdata(['user' => 'username']))
                                        $this->vars['error'] = __('This character is not yours.'); 
									else{
                                        if($this->vars['plugin_config']['allow_remove_before_expires'] == 0 && $this->vars['character_info']['end_date'] > time())
                                            $this->vars['error'] = __('Your not allowed to remove this character while it is not expired.'); 
										else{
                                            $this->pluginaizer->Mcharacter_market->char_info($this->vars['character_info']['mu_id'], 'dmnmark987', $this->pluginaizer->session->userdata(['user' => 'server']), true);
                                            if($this->pluginaizer->Mcharacter_market->char_info != false){
                                                $this->pluginaizer->Mcharacter_market->scharacter = $this->pluginaizer->Mcharacter_market->char_info['Name'];
                                                if(!$this->pluginaizer->Mcharacter_market->check_connect_stat($this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server'])))
                                                    $this->vars['error'] = __('Please logout from game.'); 
												else{
                                                    if($space = $this->pluginaizer->Mcharacter_market->check_free_slot($this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']))){
                                                        //$guid = $this->Maccount->get_guid($this->pluginaizer->session->userdata(array('user' => 'username')));
                                                        $this->pluginaizer->Mcharacter_market->update_sale_set_removed($id, $this->pluginaizer->session->userdata(['user' => 'username']));
                                                        $this->pluginaizer->Mcharacter_market->add_to_account_character($space, $this->pluginaizer->Mcharacter_market->char_info['Name'], $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                        $this->pluginaizer->Mcharacter_market->update_character($this->pluginaizer->Mcharacter_market->char_info['id'], true, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                        $this->pluginaizer->Mcharacter_market->update_IGC_PeriodExpiredItemInfo($this->pluginaizer->session->userdata(['user' => 'id']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                        $this->pluginaizer->Mcharacter_market->update_IGC_PeriodItemInfo($this->pluginaizer->session->userdata(['user' => 'id']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                        $this->pluginaizer->Mcharacter_market->update_T_LUCKY_ITEM_INFO($this->pluginaizer->session->userdata(['user' => 'id']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                        $this->pluginaizer->Mcharacter_market->update_T_MuRummy(true, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                        $this->pluginaizer->Mcharacter_market->update_T_MuRummyInfo(true, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                        $this->pluginaizer->Mcharacter_market->update_T_MuRummyLog(true, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                        $this->pluginaizer->Mcharacter_market->update_T_PentagramInfo(true, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                        $this->pluginaizer->Mcharacter_market->update_T_PSHOP_ITEMVALUE_INFO(true, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                                        $this->vars['success'] = __('Character successfully restored.');
                                                    } else{
                                                        $this->vars['error'] = __('All character slots are used. Please remove some character.');
                                                    }
                                                }
                                            } else{
                                                $this->vars['error'] = __('Character not found.');
                                            }
                                        }
                                    }
                                }
                            }
                        } else{
                            $this->vars['error'] = __('Character not found.');
                        }
                    }
                } else{
                    $this->vars['config_not_found'] = __('Plugin configuration not found.');
                }
                //load template
                $this->load->view('plugins' . DS . $this->pluginaizer->get_plugin_class() . DS . 'views' . DS . $this->config->config_entry('main|template') . DS . 'view.remove', $this->vars);
            } else{
                $this->pluginaizer->redirect($this->config->base_url . 'account-panel/login?return=' . str_replace('_', '-', $this->pluginaizer->get_plugin_class()) . '/sale-history');
            }
        }

        public function success()
        {
            //check if visitor has user privilleges
            if($this->pluginaizer->session->is_user()){
                //load website helper
                $this->load->helper('website');
                //load plugin config
                $this->vars['plugin_config'] = $this->pluginaizer->plugin_config();
                if($this->vars['plugin_config'] != false && !empty($this->vars['plugin_config'])){
                    if($this->pluginaizer->data()->value('is_multi_server') == 1){
                        if(array_key_exists($this->pluginaizer->session->userdata(['user' => 'server']), $this->vars['plugin_config'])){
                            $this->vars['plugin_config'] = $this->vars['plugin_config'][$this->pluginaizer->session->userdata(['user' => 'server'])];
                            $this->vars['about'] = $this->pluginaizer->get_about();
                            $this->vars['about']['user_description'] = $this->pluginaizer->data()->value('description');
                        } else{
                            $this->vars['config_not_found'] = __('Plugin configuration not found.');
                        }
                    }
                    if($this->vars['plugin_config']['active'] == 0){
                        $this->vars['module_disabled'] = __('This module has been disabled.');
                    } else{
                        $this->vars['success'] = __('You have bought new character successfully.');
                    }
                } else{
                    $this->vars['config_not_found'] = __('Plugin configuration not found.');
                }
                //load template
                $this->load->view('plugins' . DS . $this->pluginaizer->get_plugin_class() . DS . 'views' . DS . $this->config->config_entry('main|template') . DS . 'view.success', $this->vars);
            } else{
                $this->pluginaizer->redirect($this->config->base_url . 'account-panel/login?return=' . str_replace('_', '-', $this->pluginaizer->get_plugin_class()) . '/success');
            }
        }

        /**
         *
         * Load public module data
         *
         * return mixed
         *
         */
        private function public_module()
        {
            // public module not used in this plugin
        }

        /**
         *
         * Main admin module body
         * All main things related to admincp
         *
         *
         * Return mixed
         */
        public function admin()
        {
            //check if visitor has administrator privilleges
            if($this->pluginaizer->session->is_admin()){
                //load website helper
                $this->load->helper('website');
                $this->load->model('application/plugins/character_market/models/character_market');
                $this->vars['is_multi_server'] = $this->pluginaizer->data()->value('is_multi_server');
                $this->vars['plugin_config'] = $this->pluginaizer->plugin_config();
                //load any js, css files if required
                $this->vars['js'] = $this->config->base_url . 'assets/plugins/js/character_market.js';
                //load template
                $this->load->view('plugins' . DS . $this->pluginaizer->get_plugin_class() . DS . 'views' . DS . 'admin' . DS . 'view.index', $this->vars);
            } else{
                $this->pluginaizer->redirect($this->config->base_url . 'admincp/login?return=' . str_replace('_', '-', $this->pluginaizer->get_plugin_class()) . '/admin');
            }
        }

        /**
         *
         * Save plugin settings
         *
         *
         * Return mixed
         */
        public function save_settings()
        {
            //check if visitor has administrator privilleges
            if($this->pluginaizer->session->is_admin()){
                $this->vars['plugin_config'] = $this->pluginaizer->plugin_config();
                if(isset($_POST['server']) && $_POST['server'] != 'all'){
                    foreach($_POST AS $key => $val){
                        if($key != 'server'){
                            $this->vars['plugin_config'][$_POST['server']][$key] = $val;
                        }
                    }
                } else{
                    foreach($_POST AS $key => $val){
                        if($key != 'server'){
                            $this->vars['plugin_config'][$key] = $val;
                        }
                    }
                }
                if($this->pluginaizer->save_config($this->vars['plugin_config'])){
                    echo $this->pluginaizer->jsone(['success' => 'Plugin configuration successfully saved']);
                } else{
                    echo $this->pluginaizer->jsone(['error' => $this->pluginaizer->error]);
                }
            }
        }

        /**
         *
         * Plugin installer
         * Admin module for plugin installation
         * Set plugin data, create plugin config template, create sql schemes
         *
         */
        public function install()
        {
            //check if visitor has administrator privilleges
            if($this->pluginaizer->session->is_admin()){
                //create plugin info
                $this->pluginaizer->set_about()->add_plugin(['installed' => 1, 'module_url' => $this->config->base_url . str_replace('_', '-', $this->pluginaizer->get_plugin_class()), //link to module
                    'admin_module_url' => $this->config->base_url . str_replace('_', '-', $this->pluginaizer->get_plugin_class()) . '/admin', //link to admincp module
                    'is_public' => 0, //if is public module or requires to login
                    'is_multi_server' => 1, //will this plugin have different config for each server, multi server is supported only by not user modules
                    'main_menu_item' => 0, //add link to module in main website menu,
                    'sidebar_user_item' => 0, //add link to module in user sidebar
                    'sidebar_public_item' => 1, //add link to module in public sidebar menu, if template supports
                    'account_panel_item' => 1, //add link in user account panel
                    'donation_panel_item' => 0, //add link in donation page
                    'description' => 'Sell & Buy Characters' //description which will see user
                ]);
                //create plugin config template
                $this->pluginaizer->create_config(['active' => 0, 'characters_per_page' => 25, 'sale_tax' => 0, 'sale_price_minimum' => 5000, 'sale_price_maximum' => 15000, 'allow_sell_with_guild' => 1, 'allow_sell_with_gens' => 1, 'allow_remove_before_expires' => 0]);
                //add sql scheme if there is any into website database
                //all schemes should be located in plugin_folder/sql_schemes
                $this->pluginaizer->add_sql_scheme('character_market');
                //check for errors
                if(count($this->pluginaizer->error) > 0){
                    $data['error'] = $this->pluginaizer->error;
                }
                $data['success'] = 'Plugin installed successfully';
                echo $this->pluginaizer->jsone($data);
            } else{
                echo $this->pluginaizer->jsone(['error' => 'Please login first!']);
            }
        }

        /**
         *
         * Plugin uninstaller
         * Admin module for plugin uninstall
         * Remove plugin data, delete plugin config, delete sql schemes
         *
         */
        public function uninstall()
        {
            //check if visitor has administrator privilleges
            if($this->pluginaizer->session->is_admin()){
                //delete plugin config and remove plugin data
                $this->pluginaizer->delete_config()->remove_sql_scheme('character_market')->remove_plugin();
                //check for errors
                if(count($this->pluginaizer->error) > 0){
                    echo $this->pluginaizer->jsone(['error' => $this->pluginaizer->error]);
                }
                echo $this->pluginaizer->jsone(['success' => 'Plugin uninstalled successfully']);
            } else{
                echo $this->pluginaizer->jsone(['error' => 'Please login first!']);
            }
        }

        public function enable()
        {
            //check if visitor has administrator privilleges
            if($this->pluginaizer->session->is_admin()){
                //enable plugin
                $this->pluginaizer->enable_plugin();
                //check for errors
                if(count($this->pluginaizer->error) > 0){
                    echo $this->pluginaizer->jsone(['error' => $this->pluginaizer->error]);
                } else{
                    echo $this->pluginaizer->jsone(['success' => 'Plugin successfully enabled.']);
                }
            } else{
                echo $this->pluginaizer->jsone(['error' => 'Please login first!']);
            }
        }

        public function disable()
        {
            //check if visitor has administrator privilleges
            if($this->pluginaizer->session->is_admin()){
                //disable plugin
                $this->pluginaizer->disable_plugin();
                //check for errors
                if(count($this->pluginaizer->error) > 0){
                    echo $this->pluginaizer->jsone(['error' => $this->pluginaizer->error]);
                } else{
                    echo $this->pluginaizer->jsone(['success' => 'Plugin successfully disabled.']);
                }
            } else{
                echo $this->pluginaizer->jsone(['error' => 'Please login first!']);
            }
        }

        public function about()
        {
            //check if visitor has administrator privilleges
            if($this->pluginaizer->session->is_admin()){
                //create plugin info
                $about = $this->pluginaizer->get_about();
                if($about != false){
                    $description = '<div class="box-content">
								<dl>
								  <dt>Plugin Name</dt>
								  <dd>' . $about['name'] . '</dd>
								  <dt>Version</dt>
								  <dd>' . $about['version'] . '</dd>
								  <dt>Description</dt>
								  <dd>' . $about['description'] . '</dd>
								  <dt>Developed By</dt>
								  <dd>' . $about['developed_by'] . ' <a href="' . $about['website'] . '" target="_blank">' . $about['website'] . '</a></dd>
								</dl>            
							</div>';
                } else{
                    $description = '<div class="alert alert-info">Unable to find plugin description.</div>';
                }
                echo $this->pluginaizer->jsone(['about' => $description]);
            } else{
                echo $this->pluginaizer->jsone(['error' => 'Please login first!']);
            }
        }
    }