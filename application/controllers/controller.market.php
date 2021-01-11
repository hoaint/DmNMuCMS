<?php
    in_file();

    class market extends controller
    {
        public $vars = [], $errors = [], $charge_credits = false, $updated_vault = null, $price_type = 0, $status = 0, $price_with_tax = 0;

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('website');
            $this->load->lib('session', ['DmNCMS']);
            $this->load->helper('breadcrumbs', [$this->request]);
            $this->load->helper('webshop');
            $this->load->helper('meta');
            $this->load->lib("pagination");
            $this->load->lib("itemimage");
            $this->load->lib("iteminfo");
            $this->load->model('shop');
            $this->load->model('market');
        }

        public function index($page = 1, $server = '')
        {
            if(!$this->website->module_disabled('market')){
                if($server == ''){
                    if($this->session->userdata(['user' => 'logged_in'])){
                        $this->vars['def_server'] = $this->session->userdata(['user' => 'server']);
                    } else{
                        $server = array_keys($this->website->server_list());
                        $this->vars['def_server'] = $server[0];
                    }
                } else{
                    $this->serv = $this->website->server_list();
                    if(!array_key_exists($server, $this->serv)){
                        throw new exception('Invalid server selected');
                    }
                    $this->vars['def_server'] = $server;
                }
                $this->vars['item_title_list'] = $this->Mmarket->load_all_items_names($this->vars['def_server']);
                if(isset($_POST['search_item'])){
                    $this->vars['items'] = $this->Mmarket->load_search_items($_POST['item'], $this->vars['def_server']);
                    $this->load->view($this->config->config_entry('main|template') . DS . 'market' . DS . 'view.items_search', $this->vars);
                } else{
                    if(count($_POST) > 0){
                        foreach($_POST as $key => $value){
                            $this->Mmarket->$key = $value;
                        }
                        header('Location: ' . $this->config->base_url . 'market');
                    }
                    $this->Mmarket->generate_query_post();
                    $this->Mmarket->count_total_items($this->vars['def_server']);
                    $this->pagination->initialize($page, $this->config->config_entry('market|items_per_page'), $this->Mmarket->total_items, $this->config->base_url . 'market/index/%s/' . $this->vars['def_server']);
                    $this->vars['items'] = $this->Mmarket->load_items($page, $this->vars['def_server']);
                    $this->vars['pagination'] = $this->pagination->create_links();
                    $this->load->view($this->config->config_entry('main|template') . DS . 'market' . DS . 'view.items', $this->vars);
                }
            }
        }

        public function update()
        {
            $this->Mmarket->update_item_names();
        }

        public function buy($id = '')
        {
            if(!$this->website->module_disabled('market')){
                if($this->session->userdata(['user' => 'logged_in'])){
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                    $this->load->model('account');
                    if($id == ''){
                        $this->vars['error'] = __('Invalid item.');
                    } else{
                        if(!$this->Maccount->check_connect_stat()){
                            $this->vars['error'] = __('Please logout from game.');
                        } else{
                            if($this->Mmarket->load_item_from_market($id)){
                                if(isset($_POST['buy_item'])){
									usleep(mt_rand(1000000, 5000000));
									if($this->Mmarket->check_item_in_maket($id)){				  
										if($this->Mmarket->item_info['seller'] == $this->session->userdata(['user' => 'username'])){
											$this->vars['error'] = __('You can not purchase own item.') . ' <a href="' . $this->config->base_url . 'market/remove/' . $id . '">' . __('Want to remove it?') . '</a>';
										} else{
											if($vault = $this->Mshop->get_vault_content()){
												if($this->Mmarket->item_info['price_jewel'] != 0 && $this->Mmarket->item_info['jewel_type'] != 0){
													$this->price_with_tax = $this->Mmarket->item_info['price_jewel'];
													$jewel_data = $this->Mmarket->check_amount_of_jewels($this->Mmarket->item_info['price_jewel'], $this->Mmarket->item_info['jewel_type'], $vault['Items']);
													if(!$jewel_data){
														$this->vars['error'] = sprintf(__('You have insufficient amount of %s'), 'jewels');
													} else{
														$this->updated_vault = $this->Mmarket->charge_jewels($jewel_data, $vault['Items']);
														$this->price_type = $this->Mmarket->price_to_jewels($this->Mmarket->item_info['jewel_type']);
													}
												} else{
													$this->status = $this->Maccount->get_amount_of_credits($this->session->userdata(['user' => 'username']), $this->Mmarket->item_info['price_type'], $this->session->userdata(['user' => 'server']), $this->session->userdata(['user' => 'id']));
													$this->price_with_tax = round($this->Mmarket->item_info['price'] + ($this->Mmarket->item_info['price'] / 100) * $this->config->config_entry('market|sell_tax'), 0);
													switch($this->Mmarket->item_info['price_type']){
														case 1:
															if($this->status < $this->price_with_tax){
																$this->vars['error'] = sprintf(__('You have insufficient amount of %s'), $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_1'));
															}
															break;
														case 2:
															if($this->status < $this->price_with_tax){
																$this->vars['error'] = sprintf(__('You have insufficient amount of %s'), $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_2'));
															}
															break;
														case 3:
															if($this->status < $this->price_with_tax){
																$this->vars['error'] = sprintf(__('You have insufficient amount of %d in your WebWallet.'), $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_3'));
															}
															break;
													}
													$this->charge_credits = true;
													$this->updated_vault = $vault['Items'];
													$this->price_type = $this->website->translate_credits($this->Mmarket->item_info['price_type'], $this->session->userdata(['user' => 'server']));
												}
												if(!isset($this->vars['error'])){
													$this->iteminfo->itemData($this->Mmarket->item_info['item']);
													$space = $this->Mshop->check_space($this->updated_vault, $this->iteminfo->GetX(), $this->iteminfo->GetY(), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_multiplier'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_hor_size'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_ver_size'));
													if($space === null){
														$this->vars['error'] = $this->Mshop->errors[0];
													} else{
														if(!isset($this->vars['error'])){
															if($this->charge_credits){
																$this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $this->price_with_tax, $this->Mmarket->item_info['price_type']);
																$this->website->add_credits($this->Mmarket->item_info['seller'], $this->Mmarket->item_info['server'], $this->Mmarket->item_info['price'], $this->Mmarket->item_info['price_type'], false, $this->Maccount->get_guid($this->Mmarket->item_info['seller']));
															} else{
																$this->load->lib("createitem", [MU_VERSION, SOCKET_LIBRARY]);
																$jewel = $this->Mmarket->get_jewel_by_type($this->Mmarket->item_info['jewel_type']);																					
																$last_serial = array_values($this->Mshop->generate_serial2($this->Mmarket->item_info['price_jewel']));
																$serial2 = false;
																if($this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size') == 64){
																	$serial2 = true;
																}
																for($i = 0; $i < $this->Mmarket->item_info['price_jewel']; $i++){
																	$new_jewels[] = $this->createitem->make($jewel[1], $jewel[0], false, [], 1, $last_serial[0], $serial2)->to_hex();
																	$last_serial[0] -= 1;
																}
																$this->Mmarket->add_jewels_to_web_wh($new_jewels, $this->Mmarket->item_info['seller'], $this->Mmarket->item_info['server']);
															}
															$this->Maccount->add_account_log('Bought Market Item For ' . $this->price_type, -$this->price_with_tax, $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
															$this->Maccount->add_account_log('Sold Market Item For ' . $this->price_type, $this->Mmarket->item_info['price'], $this->Mmarket->item_info['seller'], $this->Mmarket->item_info['server']);
															$this->Mshop->generate_new_items($this->Mmarket->item_info['item'], $space, $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_multiplier'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'), $this->updated_vault);
															$this->Mshop->update_warehouse();
															$this->Mmarket->log_purchase($this->session->userdata(['user' => 'username']), $this->price_with_tax, $id);
															header('Location: ' . $this->config->base_url . 'market/success');
														}
													}
												}
											} else{
												$this->vars['error'] = __('Please open your warehouse in game first.');
											}
										}
									}	
									else{
										$this->vars['error'] = __('Item not found in our database.');
									}
								}	
                                $this->iteminfo->itemData($this->Mmarket->item_info['item']);
                            } else{
                                $this->vars['error'] = __('Item not found in our database.');
                            }
                        }
                    }
                    $this->load->view($this->config->config_entry('main|template') . DS . 'market' . DS . 'view.buyitem', $this->vars);
                } else{
                    $this->login();
                }
            }
        }

        public function success()
        {
            if(!$this->website->module_disabled('market')){
                if($this->session->userdata(['user' => 'logged_in'])){
                    $this->vars['success'] = __('You have bought new item successfully.');
                    $this->load->view($this->config->config_entry('main|template') . DS . 'market' . DS . 'view.success', $this->vars);
                } else{
                    $this->login();
                }
            }
        }

        public function history($page = 1)
        {
            if(!$this->website->module_disabled('market')){
                if($this->session->userdata(['user' => 'logged_in'])){
                    $this->Mmarket->count_total_history_items();
                    $this->pagination->initialize($page, $this->config->config_entry('market|items_per_page'), $this->Mmarket->total_items, $this->config->base_url . 'market/history/%s');
                    $this->vars['items'] = $this->Mmarket->load_history_items($page);
                    $this->vars['pagination'] = $this->pagination->create_links();
                    $this->load->view($this->config->config_entry('main|template') . DS . 'market' . DS . 'view.history', $this->vars);
                } else{
                    $this->login();
                }
            }
        }

        public function remove($id = '')
        {
            if(!$this->website->module_disabled('market')){
                if($this->session->userdata(['user' => 'logged_in'])){
                    if($this->website->is_multiple_accounts() == true){
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                    } else{
                        $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_default_account_database()]);
                    }
                    $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
                    $this->load->model('account');
					
					usleep(mt_rand(1000000, 5000000));
					
                    if($id == ''){
                        $this->vars['error'] = __('Invalid item.');
                    } else{
                        if(!$this->Maccount->check_connect_stat()){
                            $this->vars['error'] = __('Please logout from game.');
                        } else{
							if($vault = $this->Mshop->get_vault_content()){
								$this->iteminfo->itemData($this->Mmarket->item_info['item']);
								$space = $this->Mshop->check_space($vault['Items'], $this->iteminfo->getX(), $this->iteminfo->getY(), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_multiplier'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_hor_size'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_ver_size'));
								if($space === null){
									$this->vars['error'] = $this->Mshop->errors[0];
								} else{
									if(!$this->Mmarket->load_item_from_market_for_history($id)){
										$this->vars['error'] = __('Item not found in our database.');
									} 
									else{
										if($this->Mmarket->item_info['sold'] == 1){
											$this->vars['error'] = __('This item is already sold.');
										}
										else{
											if($this->Mmarket->item_info['removed'] == 1){
												$this->vars['error'] = __('This item is already removed.');
											} 
											else{
												if($this->Mmarket->item_info['seller'] != $this->session->userdata(['user' => 'username'])){
													$this->vars['error'] = __('This item doesn\'t belong to you.');
												} 
												else{
													if($this->config->config_entry('market|allow_remove_only_when_expired') == 1 && (strtotime($this->Mmarket->item_info['active_till']) > time() + (10 * 60))){
														$this->vars['error'] = __('You will be allowed to remove this item after it will expire.');
													}
													else{
														$this->Mmarket->change_item_status($id);
														$this->Mshop->generate_new_items($this->Mmarket->item_info['item'], $space, $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_multiplier'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
														$this->Mshop->update_warehouse();
																								
														$this->vars['success'] = __('Item has been successfully removed from market.');
													}
												}
											}
										}
									}	
								}
							} else{
								$this->vars['error'] = __('Please open your warehouse in game first.');
							}
							
							
							
                            /*if(!$this->Mmarket->load_item_from_market_for_history($id)){
                                $this->vars['error'] = __('Item not found in our database.');
                            } else{
                                if($this->Mmarket->item_info['sold'] == 1){
                                    $this->vars['error'] = __('This item is already sold.');
                                } else{
                                    if($this->Mmarket->item_info['removed'] == 1){
                                        $this->vars['error'] = __('This item is already removed.');
                                    } else{
                                        if($this->Mmarket->item_info['seller'] != $this->session->userdata(['user' => 'username'])){
                                            $this->vars['error'] = __('This item doesn\'t belong to you.');
                                        } else{
                                            if($this->config->config_entry('market|allow_remove_only_when_expired') == 1 && (strtotime($this->Mmarket->item_info['active_till']) > time() + (10 * 60))){
                                                $this->vars['error'] = __('You will be allowed to remove this item after it will expire.');
                                            } else{
												usleep(mt_rand(1000000, 5000000));
                                                if($vault = $this->Mshop->get_vault_content()){
                                                    $this->iteminfo->itemData($this->Mmarket->item_info['item']);
                                                    $space = $this->Mshop->check_space($vault['Items'], $this->iteminfo->getX(), $this->iteminfo->getY(), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_multiplier'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_hor_size'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_ver_size'));
                                                    if($space === null){
                                                        $this->vars['error'] = $this->Mshop->errors[0];
                                                    } else{
														$this->Mmarket->change_item_status($id);
                                                        $this->Mshop->generate_new_items($this->Mmarket->item_info['item'], $space, $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_multiplier'), $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
                                                        $this->Mshop->update_warehouse();
																								
                                                        $this->vars['success'] = __('Item has been successfully removed from market.');
                                                    }
                                                } else{
                                                    $this->vars['error'] = __('Please open your warehouse in game first.');
                                                }
                                            }
                                        }
                                    }
                                }
                            }*/
                        }
                    }
                    $this->load->view($this->config->config_entry('main|template') . DS . 'market' . DS . 'view.history_remove', $this->vars);
                } else{
                    $this->login();
                }
            }
        }

        public function latest_items()
        {
            if(isset($_POST['server'], $_POST['item_count'], $_POST['text_limit'])){
                $server = $_POST['server'];
                $item_count = $_POST['item_count'];
                $text_limit = $_POST['text_limit'];
                if(!is_numeric($item_count))
                    $item_count = $this->config->config_entry('modules|last_market_items_count');
                if(!is_numeric($text_limit))
                    $text_limit = 20;
                if(!array_key_exists($server, $this->website->server_list())){
                    json(['items' => false]);
                } else{
                    json(['items' => $this->Mmarket->get_lattest_items($server, $item_count, $text_limit), 'base_url' => $this->config->base_url]);
                }
            }
        }

        public function login()
        {
            $this->load->view($this->config->config_entry('main|template') . DS . 'account_panel' . DS . 'view.login');
        }
    }