<?php

class _plugin_unitpay extends controller implements pluginInterface{
	private $pluginaizer;
	private $vars = [];
	
	/**
	 *
	 * Plugin constructor
	 * Initialize plugin class
	 *
	 */
	 
	public function __construct(){
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
	 
	public function index(){
		if($this->pluginaizer->data()->value('installed') == false){
			throw new Exception('Plugin has not yet been installed.');
		}
		else{
			if($this->pluginaizer->data()->value('installed') == 1){
				if($this->pluginaizer->data()->value('is_public') == 0){
					$this->user_module();
				}
				else{
					$this->public_module();
				}
			}
			else{
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
	
	private function user_module(){
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
					}
					else{
						$this->vars['config_not_found'] = __('Plugin configuration not found.');
					}
				}
				
				if($this->vars['plugin_config']['active'] == 0){
					$this->vars['module_disabled'] =  __('This module has been disabled.');
				}
				else{
					$this->load->model('application/plugins/unitpay/models/unitpay');
					$this->vars['packages_unitpay'] = $this->pluginaizer->Munitpay->load_packages(true);
				}
			}
			else{
				$this->vars['config_not_found'] = __('Plugin configuration not found.');
			}
			//set js
			$this->vars['js'] = $this->config->base_url . 'assets/plugins/js/unitpay.js?v1';
			//load template
			$this->load->view('plugins' . DS . $this->pluginaizer->get_plugin_class() . DS . 'views' . DS . $this->config->config_entry('main|template') . DS . 'view.unitpay', $this->vars);
		}
		else{
			$this->pluginaizer->redirect($this->config->base_url . 'account-panel/login?return=' . str_replace('_', '-', $this->pluginaizer->get_plugin_class()));
		}
	}
	
	/**
	 *
	 * Generate checkout data and checkout
	 * 
	 * return mixed
	 *
	 */
	
	public function checkout($id = -1){
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
					}
					else{
						echo $this->pluginaizer->jsone(['error' => __('Plugin configuration not found.')]);
					}
				}
				if($this->vars['plugin_config']['active'] == 0){
					echo $this->pluginaizer->jsone(['error' => __('This module has been disabled.')]);
				}
				else{
					$this->load->model('application/plugins/unitpay/models/unitpay');
					$id = ($id != -1) ? (int)$id : '';
					if($id == '')
						echo $this->pluginaizer->jsone(['error' => __('Invalid unitpay package.')]);
					else{
						if($this->vars['package'] = $this->pluginaizer->Munitpay->check_package($id)){
							require_once(APP_PATH . DS . 'plugins' . DS . 'unitpay' . DS . 'libraries' . DS . 'UnitPay.php');
							
							$unitPay = new UnitPay($this->vars['plugin_config']['domain'], $this->vars['plugin_config']['secretkey']);

							$item_number = md5($this->pluginaizer->session->userdata(['user' => 'username']) . $this->vars['package']['price'] . $this->vars['package']['currency'] . uniqid(microtime(), 1));
							
							if($this->pluginaizer->Munitpay->insert_order(
								number_format($this->vars['package']['price'], 2, '.', ','),
								$this->vars['package']['currency'], 
								$this->vars['package']['reward'],
								$item_number,
								$this->pluginaizer->session->userdata(['user' => 'username']),
								$this->pluginaizer->session->userdata(['user' => 'server'])
							)){
								$unitPay->setCustomerEmail($this->pluginaizer->session->userdata(array('user' => 'email')));
								$unitPay->setBackUrl($this->config->base_url . 'unitpay');
								$unitPay->setCashItems(array(
								   new CashItem($this->vars['package']['reward'], 1, $this->vars['package']['price']) 
								));
								$redirectUrl = $unitPay->form(
									$this->vars['plugin_config']['publickey'],
									$this->vars['package']['price'],
									$item_number,
									'Buy Credits '.$this->pluginaizer->session->userdata(array('user' => 'username'))." x".$this->vars['package']['reward'],
									$this->vars['package']['currency']
								);
								
								header("Location: " . $redirectUrl);
							}
							else{
								echo $this->pluginaizer->jsone([__('Unable to checkout please try again.')]);	
							}
						} 
						else{
							echo $this->pluginaizer->jsone(['error' => 'Invalid package']);
						}
					}
				}
			}
			else{
				echo $this->pluginaizer->jsone(['error' => __('Plugin configuration not found.')]);
			}
		}
		else{
			echo $this->pluginaizer->jsone(['error' => __('Please login into website.')]);
		}
	}
	
	
	public function pingback(){
		$this->vars['plugin_config'] = $this->pluginaizer->plugin_config();
		if($this->vars['plugin_config'] != false && !empty($this->vars['plugin_config'])){
			if(empty($_GET['method']) || empty($_GET['params']) || !is_array($_GET['params'])){
				 $this->writelog('Invalid request', 'unitpay');
				 echo $this->pluginaizer->jsone(['error' => ['message' => __('Invalid request'), 'code' => -32000]]);
			}
			else{
				require_once(APP_PATH . DS . 'plugins' . DS . 'unitpay' . DS . 'libraries' . DS . 'UnitPay.php');
				$this->load->model('account');	
				$this->load->model('application/plugins/unitpay/models/unitpay');	
				if (!$this->pluginaizer->Munitpay->check_order_number($_GET['params']['account'])) {
					$this->writelog('Order not found: '. $_GET['params']['account'], 'unitpay');
					echo $this->pluginaizer->jsone(['error' => ['message' => 'Order not found: '. $_GET['params']['account']]]);
				}
				else{
					if($this->pluginaizer->data()->value('is_multi_server') == 1){
						if(array_key_exists($this->pluginaizer->Munitpay->order_details['server'], $this->vars['plugin_config'])){
							$this->vars['plugin_config'] = $this->vars['plugin_config'][$this->pluginaizer->Munitpay->order_details['server']];
						}
						else{
							$this->writelog('Plugin configuration not found.', 'unitpay');
							echo $this->pluginaizer->jsone(['error' => ['message' => __('Plugin configuration not found.')]]);
						}
					}
					if($this->vars['plugin_config']['active'] == 0){
						$this->writelog('This module has been disabled.', 'unitpay');
						echo $this->pluginaizer->jsone(['error' => ['message' => __('This module has been disabled.')]]);
					}
					else{
						$unitPay = new UnitPay($this->vars['plugin_config']['domain'], $this->vars['plugin_config']['secretkey']);
						try {
							$unitPay->checkHandlerRequest();

							list($method, $params) = array($_GET['method'], $_GET['params']);

							if (
								$params['orderSum'] != $this->pluginaizer->Munitpay->order_details['amount'] ||
								$params['orderCurrency'] != $this->pluginaizer->Munitpay->order_details['currency']
							) {
								throw new InvalidArgumentException('Order validation Error! SUM: [client: '.$params['orderSum'].', server: '.$this->pluginaizer->Munitpay->order_details['amount'].'] CURRENCY: [client: '.$params['orderCurrency'].', server: '.$this->pluginaizer->Munitpay->order_details['currency'].']');
							}
							switch ($method) {
								case 'check':
									echo $unitPay->getSuccessHandlerResponse('Check Success. Ready to pay.');
									break;
								case 'pay':
									$complete_transaction = $this->pluginaizer->Munitpay->check_completed_transaction($this->pluginaizer->Munitpay->order_details['hash']);
									if($complete_transaction == false){
										$this->pluginaizer->Munitpay->insert_transaction_status($this->pluginaizer->Munitpay->order_details['hash'], $this->pluginaizer->Munitpay->order_details['hash'], 'Completed');
										$this->pluginaizer->Maccount->add_account_log(
											'Reward ' . $this->pluginaizer->website->translate_credits($this->vars['plugin_config']['reward_type'], 
											$this->pluginaizer->Munitpay->order_details['server']) . ' unitpay', 
											$this->pluginaizer->Munitpay->order_details['credits'], 
											$this->pluginaizer->Munitpay->order_details['account'], 
											$this->pluginaizer->Munitpay->order_details['server']
										);
										$this->pluginaizer->website->add_credits(
											$this->pluginaizer->Munitpay->order_details['account'], 
											$this->pluginaizer->Munitpay->order_details['server'], 
											$this->pluginaizer->Munitpay->order_details['credits'],
											$this->vars['plugin_config']['reward_type'], 
											false,
											$this->pluginaizer->Munitpay->get_guid($this->pluginaizer->Munitpay->order_details['account'], $this->pluginaizer->Munitpay->order_details['server'])
										);
									}
									else{
										$this->writelog('Order '.$this->pluginaizer->Munitpay->order_details['hash'].' already credited', 'unitpay');
									}
									echo $unitPay->getSuccessHandlerResponse('Pay Success');
									break;
								case 'error':
									echo $unitPay->getSuccessHandlerResponse('Error logged');
									break;
							}
						} catch (Exception $e) {
							$this->writelog('Error: '. $e->getMessage(), 'unitpay');
							echo $unitPay->getErrorHandlerResponse($e->getMessage());
						}
					}
				}
			}
		}
		else{
			$this->writelog('Plugin configuration not found.', 'unitpay');
			die(__('Plugin configuration not found.'));
		}
	}
	
	/**
	 *
	 * Write payment log
	 *
	 * @param string $logentry
	 * @param string $logname
	 * 
	 *
	 */
	
	private function writelog($logentry, $logname){
        $log = '[' . $this->pluginaizer->website->ip() . '] ' . $logentry;
        $logfile = @fopen(APP_PATH . DS . 'logs' . DS . $logname . '_' . date("m-d-y") . '.txt', "a+");
        if ($logfile){
            fwrite($logfile, "[" . date("h:iA") . "] $log\r\n");
            fclose($logfile);
        }
    }
	
	/**
	 *
	 * Load public module data
	 * 
	 * return mixed
	 *
	 */
	
	private function public_module(){
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
	 
	public function admin(){
		//check if visitor has administrator privilleges
		if($this->pluginaizer->session->is_admin()){
			//load website helper
			$this->load->helper('website');
			
			$this->load->model('application/plugins/unitpay/models/unitpay');
			
			$this->vars['is_multi_server'] = $this->pluginaizer->data()->value('is_multi_server');
			$this->vars['plugin_config'] = $this->pluginaizer->plugin_config();
			$this->vars['packages_unitpay'] = $this->pluginaizer->Munitpay->load_packages();
			//load any js, css files if required
			$this->vars['js'] = $this->config->base_url . 'assets/plugins/js/unitpay.js';
			//load template
			$this->load->view('plugins' . DS . $this->pluginaizer->get_plugin_class() . DS . 'views' . DS . 'admin' . DS . 'view.index', $this->vars);
		
		}
		else{
			$this->pluginaizer->redirect($this->config->base_url . 'admincp/login?return=' . str_replace('_', '-', $this->pluginaizer->get_plugin_class()) . '/admin');
		}
	}
	
	/**
	 *
	 * Add unitpay package
	 * 
	 *
	 * Return mixed
	 */
	
	public function add_package(){
        //check if visitor has administrator privilleges
		if($this->pluginaizer->session->is_admin()){
			//load website helper
			$this->load->helper('website');
			
			$this->load->model('application/plugins/unitpay/models/unitpay');
			
            $title = !empty($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
            $price = (isset($_POST['price']) && is_numeric($_POST['price'])) ? $_POST['price'] : '';
            $currency = !empty($_POST['currency']) ? htmlspecialchars($_POST['currency']) : '';
            $reward = (isset($_POST['reward']) && preg_match('/^\d*$/', $_POST['reward'])) ? $_POST['reward'] : '';
            $server = !empty($_POST['server']) ? htmlspecialchars($_POST['server']) : '';
            if($title == '')
                echo $this->pluginaizer->jsone(['error' => 'Invalid package title']);
            else{
                if($price == '')
                    echo $this->pluginaizer->jsone(['error' => 'Invalid package price']);
                else{
                    if($currency == '')
                        echo $this->pluginaizer->jsone(['error' => 'Invalid package currency']);
                    else{
                        if($server == '')
                            echo $this->pluginaizer->jsone(['error' => 'Invalid server selected']);
                        else{
                            if($reward == '')
                               echo  $this->pluginaizer->jsone(['error' => 'Invalid package reward']);
                            else{
                                if($id = $this->pluginaizer->Munitpay->add_package($title, $price, $currency, $reward, $server)) {
                                    echo $this->pluginaizer->jsone(['success' => 'Package successfully added', 'id' => $id, 'server' => $server, 'servers' => $this->pluginaizer->website->server_list()]);
                                } 
								else{
                                   echo $this->pluginaizer->jsone(['error' => 'Unable to add new package']);
                                }
                            }
                        }
                    }
                }
            }
        } 
		else{
            $this->pluginaizer->jsone(['error' => 'Please login first!']);
        }
    }
	
	/**
	 *
	 * Edit unitpay package
	 * 
	 *
	 * Return mixed
	 */
	
	public function edit_package(){
        //check if visitor has administrator privilleges
		if($this->pluginaizer->session->is_admin()){
			//load website helper
			$this->load->helper('website');
			
			$this->load->model('application/plugins/unitpay/models/unitpay');
			
            $id = (isset($_POST['id']) && preg_match('/^\d*$/', $_POST['id'])) ? $_POST['id'] : '';
            $title = !empty($_POST['title']) ? htmlspecialchars($_POST['title']) : '';
            $price = (isset($_POST['price']) && is_numeric($_POST['price'])) ? $_POST['price'] : '';
            $currency = !empty($_POST['currency']) ? htmlspecialchars($_POST['currency']) : '';
            $reward = (isset($_POST['reward']) && preg_match('/^\d*$/', $_POST['reward'])) ? $_POST['reward'] : '';
            $server = !empty($_POST['server']) ? htmlspecialchars($_POST['server']) : '';
            if($id == '')
                echo $this->pluginaizer->jsone(['error' => 'Invalid package id']);
            else{
                if($title == '')
                    echo $this->pluginaizer->jsone(['error' => 'Invalid package title']);
                else{
                    if($price == '')
                        echo $this->pluginaizer->jsone(['error' => 'Invalid package price']);
                    else{
                        if($currency == '')
                            echo $this->pluginaizer->jsone(['error' => 'Invalid package currency']);
                        else{
                            if($server == '')
                                echo $this->pluginaizer->jsone(['error' => 'Invalid server selected']);
                            else{
                                if($reward == '')
                                    echo $this->pluginaizer->jsone(['error' => 'Invalid package reward']);
                                else{
                                    if($this->pluginaizer->Munitpay->check_package($id)) {
                                         $this->pluginaizer->Munitpay->edit_package($id, $title, $price, $currency, $reward, $server);
                                        echo $this->pluginaizer->jsone(['success' => 'Package successfully edited']);
                                    } 
									else{
                                        echo $this->pluginaizer->jsone(['error' => 'Invalid package']);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } 
		else{
            echo $this->pluginaizer->jsone(['error' => 'Please login first!']);
        }
    }
	
	/**
	 *
	 * Delete unitpay package
	 * 
	 *
	 * Return mixed
	 */
	
	public function delete_package(){
        //check if visitor has administrator privilleges
		if($this->pluginaizer->session->is_admin()){
			//load website helper
			$this->load->helper('website');
			
			$this->load->model('application/plugins/unitpay/models/unitpay');
			
            $id = (isset($_POST['id']) && preg_match('/^\d*$/', $_POST['id'])) ? $_POST['id'] : '';
            if($id == '')
                echo $this->pluginaizer->jsone(['error' => 'Invalid package id']);
            else{
                if($this->pluginaizer->Munitpay->check_package($id)) {
                    $this->pluginaizer->Munitpay->delete_package($id);
                    echo $this->pluginaizer->jsone(['success' => 'Package successfully removed']);
                } 
				else{
                    echo $this->pluginaizer->jsone(['error' => 'Invalid package']);
                }
            }
        } 
		else{
            echo $this->pluginaizer->jsone(['error' => 'Please login first!']);
        }
    }
	
	/**
	 *
	 * Enable / Disable unitpay package
	 * 
	 *
	 * Return mixed
	 */
	
	public function change_status(){
         //check if visitor has administrator privilleges
		if($this->pluginaizer->session->is_admin()){
			//load website helper
			$this->load->helper('website');
			
			$this->load->model('application/plugins/unitpay/models/unitpay');
			
            $id = (isset($_POST['id']) && preg_match('/^\d*$/', $_POST['id'])) ? $_POST['id'] : '';
            $status = (isset($_POST['status']) && preg_match('/^\d*$/', $_POST['status'])) ? $_POST['status'] : '';
            if($id == '')
                echo $this->pluginaizer->jsone(['error' => 'Invalid package id']);
            else{
                if($status == '')
                    echo $this->pluginaizer->jsone(['error' => 'Invalid package status']);
                else{
                    if($this->pluginaizer->Munitpay->check_package($id)) {
                        $this->pluginaizer->Munitpay->change_status($id, $status);
                        echo $this->pluginaizer->jsone(['success' => 'Package status changed']);
                    } 
					else{
                        echo $this->pluginaizer->jsone(['error' => 'Invalid package']);
                    }
                }
            }
        } 
		else{
            echo $this->pluginaizer->jsone(['error' => 'Please login first!']);
        }
    }
	
	/**
	 *
	 * Save unitpay package order
	 * 
	 *
	 * Return mixed
	 */
	 
	public function save_order(){
         //check if visitor has administrator privilleges
		if($this->pluginaizer->session->is_admin()){
			//load website helper
			$this->load->helper('website');			
			$this->load->model('application/plugins/unitpay/models/unitpay');	
            $this->pluginaizer->Munitpay->save_order($_POST['order']);
        } 
		else{
             echo $this->pluginaizer->jsone(['error' => 'Please login first!']);
        }
    }
	
	/**
	 *
	 * Generate unitpay logs
	 * 
	 * @param int $page
	 * @param string $acc
	 * @param string $server
	 *
	 * Return mixed
	 */
	
	public function logs($page = 1, $acc = '-', $server = 'All'){
		  //check if visitor has administrator privilleges
		if($this->pluginaizer->session->is_admin()){
			//load website helper
			$this->load->helper('website');	
			//load paginator
			$this->load->lib('pagination');	
			
			$this->load->model('application/plugins/unitpay/models/unitpay');	
			
			if(isset($_POST['search_unitpay_transactions'])){
                $server = (isset($_POST['server']) && $_POST['server'] != 'All') ? $_POST['server'] : 'All';
                $acc = isset($_POST['account']) ? $_POST['account'] : '';
                if($acc == '') {
                    $this->vars['error'] = 'Invalid account';
                } 
				else{
                    $this->vars['logs'] = $this->pluginaizer->Munitpay->load_transactions(1, 25, $acc, $server);
                    $this->pluginaizer->pagination->initialize(1, 25, $this->pluginaizer->Munitpay->count_total_transactions($acc, $server), $this->config->base_url . 'unitpay/logs/%s/' . $acc . '/' . $server . '');
                    $this->vars['pagination'] =  $this->pluginaizer->pagination->create_links();
                }
            } 
			else{
                $this->vars['logs'] = $this->pluginaizer->Munitpay->load_transactions($page, 25, $acc, $server);
                $lk = '';
                if($acc != '')
                    $lk .= '/' . $acc;
                $lk .= '/' . $server;
                $this->pluginaizer->pagination->initialize($page, 25,  $this->pluginaizer->Munitpay->count_total_transactions($acc, $server), $this->config->base_url . 'unitpay/logs/%s' . $lk);
                $this->vars['pagination'] =  $this->pluginaizer->pagination->create_links();
            }
			$this->load->view('plugins' . DS . $this->pluginaizer->get_plugin_class() . DS . 'views' . DS . 'admin' . DS . 'view.logs', $this->vars);
		} 
		else{
             $this->pluginaizer->redirect($this->config->base_url . 'admincp/login?return=' . str_replace('_', '-', $this->pluginaizer->get_plugin_class()) . '/logs');
        }
	}
	
	/**
	 *
	 * Save plugin settings
	 * 
	 *
	 * Return mixed
	 */
	
	public function save_settings(){
		//check if visitor has administrator privilleges
		if($this->pluginaizer->session->is_admin()){
			$this->vars['plugin_config'] = $this->pluginaizer->plugin_config();
			if(isset($_POST['server']) && $_POST['server'] != 'all'){
				foreach($_POST AS $key => $val){
					if($key != 'server'){
						$this->vars['plugin_config'][$_POST['server']][$key] = $val;
					}
				}
			}
			else{
				foreach($_POST AS $key => $val){
					if($key != 'server'){
						$this->vars['plugin_config'][$key] = $val;
					}
				}
			}
			if($this->pluginaizer->save_config($this->vars['plugin_config'])){
				echo $this->pluginaizer->jsone(['success' => 'Plugin configuration successfully saved']);
			}
			else{
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
	
	public function install(){
		//check if visitor has administrator privilleges
		if($this->pluginaizer->session->is_admin()){
			//create plugin info
			$this->pluginaizer->set_about()->add_plugin([
				'installed' => 1,
				'module_url' => $this->config->base_url . str_replace('_', '-', $this->pluginaizer->get_plugin_class()), //link to module
				'admin_module_url' => $this->config->base_url . str_replace('_', '-', $this->pluginaizer->get_plugin_class()) . '/admin', //link to admincp module
				'is_public' => 0, //if is public module or requires to login
				'is_multi_server' => 1, //will this plugin have different config for each server, multi server is supported only by not user modules
				'main_menu_item' => 0, //add link to module in main website menu,
				'sidebar_user_item' => 0, //add link to module in user sidebar
				'sidebar_public_item' => 0, //add link to module in public sidebar menu, if template supports
				'account_panel_item' => 0, //add link in user account panel
				'donation_panel_item' => 1, //add link in donation page
				'description' => 'Donate with UnitPay' //description which will see user
			]);
			
			//create plugin config template
			$this->pluginaizer->create_config([
				'active' => 0,
				'domain' =>  'unitpay.ru',
				'publickey' =>  '',
				'secretkey' => '',
				'reward_type' => 0
			]);
			//add sql scheme if there is any into website database
			//all schemes should be located in plugin_folder/sql_schemes
			$this->pluginaizer->add_sql_scheme('unitpay_packages');
			$this->pluginaizer->add_sql_scheme('unitpay_orders');
			$this->pluginaizer->add_sql_scheme('unitpay_transactions');
			//check for errors
			if(count($this->pluginaizer->error) > 0){
				$data['error'] = $this->pluginaizer->error;
			}
			$data['success'] = 'Plugin installed successfully';
			echo $this->pluginaizer->jsone($data);
		}
		else{
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
	
	public function uninstall(){
		//check if visitor has administrator privilleges
		if($this->pluginaizer->session->is_admin()){
			//delete plugin config and remove plugin data
			$this->pluginaizer->delete_config()
			->remove_sql_scheme('unitpay_packages')
			->remove_sql_scheme('unitpay_orders')
			->remove_sql_scheme('unitpay_transactions')
			->remove_plugin();
			//check for errors
			if(count($this->pluginaizer->error) > 0){
				echo $this->pluginaizer->jsone(['error' => $this->pluginaizer->error]);
			}
			echo $this->pluginaizer->jsone(['success' => 'Plugin uninstalled successfully']);
		}
		else{
			echo $this->pluginaizer->jsone(['error' => 'Please login first!']);
		}
	}
	
	public function enable(){
		//check if visitor has administrator privilleges
		if($this->pluginaizer->session->is_admin()){
			//enable plugin
			$this->pluginaizer->enable_plugin();
			//check for errors
			if(count($this->pluginaizer->error) > 0){
				echo $this->pluginaizer->jsone(['error' => $this->pluginaizer->error]);
			}
			else{		
				echo $this->pluginaizer->jsone(['success' => 'Plugin successfully enabled.']);
			}
		}
		else{
			echo $this->pluginaizer->jsone(['error' => 'Please login first!']);
		}
	}
	
	public function disable(){
		//check if visitor has administrator privilleges
		if($this->pluginaizer->session->is_admin()){
			//disable plugin
			$this->pluginaizer->disable_plugin();
			//check for errors
			if(count($this->pluginaizer->error) > 0){
				echo $this->pluginaizer->jsone(['error' => $this->pluginaizer->error]);
			}
			else{		
				echo $this->pluginaizer->jsone(['success' => 'Plugin successfully disabled.']);
			}
		}
		else{
			echo $this->pluginaizer->jsone(['error' => 'Please login first!']);
		}
	}
	
	public function about(){
		//check if visitor has administrator privilleges
		if($this->pluginaizer->session->is_admin()){
			//create plugin info
			$about = $this->pluginaizer->get_about();
			if($about != false){
				$description = '<div class="box-content">
								<dl>
								  <dt>Plugin Name</dt>
								  <dd>'.$about['name'].'</dd>
								  <dt>Version</dt>
								  <dd>'.$about['version'].'</dd>
								  <dt>Description</dt>
								  <dd>'.$about['description'].'</dd>
								  <dt>Developed By</dt>
								  <dd>'.$about['developed_by'].' <a href="'.$about['website'].'" target="_blank">'.$about['website'].'</a></dd>
								</dl>            
							</div>';
			}
			else{
				$description = '<div class="alert alert-info">Unable to find plugin description.</div>';
			}
			echo $this->pluginaizer->jsone(['about' => $description]);
		}
		else{
			echo $this->pluginaizer->jsone(['error' => 'Please login first!']);
		}
	}
}