<?php

    class _plugin_credits_to_zen extends controller implements pluginInterface
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
        public function index()
        {
            if($this->pluginaizer->data()->value('installed') == false){
                throw new Exception('Plugin has not yet been installed.');
            } else{
                if($this->pluginaizer->data()->value('installed') == 1){
                    if($this->pluginaizer->data()->value('is_public') == 0){
                        $this->user_module();
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
        private function user_module()
        {
            //check if visitor has user privilleges
            if($this->pluginaizer->session->is_user()){
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
                        $this->load->model('application/plugins/' . $this->pluginaizer->get_plugin_class() . '/models/' . $this->pluginaizer->get_plugin_class());
                        $this->vars['char_list'] = $this->pluginaizer->{'M' . $this->pluginaizer->get_plugin_class()}->load_char_list($this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                    }
                } else{
                    $this->vars['config_not_found'] = __('Plugin configuration not found.');
                }
                //set js
                $this->vars['js'] = $this->config->base_url . 'assets/plugins/js/' . $this->pluginaizer->get_plugin_class() . '.js';
                //load template
                $this->load->view('plugins' . DS . $this->pluginaizer->get_plugin_class() . DS . 'views' . DS . $this->config->config_entry('main|template') . DS . 'view.zen_exchange', $this->vars);
            } else{
                $this->pluginaizer->redirect($this->config->base_url . 'account-panel/login?return=' . str_replace('_', '-', $this->pluginaizer->get_plugin_class()));
            }
        }

        /**
         *
         * Change zen
         *
         * return mixed
         *
         */
        public function change_credits()
        {
            //check if visitor has user privilleges
            if($this->pluginaizer->session->is_user()){
                //load website helper
                $this->load->model('account');
                $this->load->model('application/plugins/' . $this->pluginaizer->get_plugin_class() . '/models/' . $this->pluginaizer->get_plugin_class());
                $this->vars['plugin_config'] = $this->pluginaizer->plugin_config();
                if($this->vars['plugin_config'] != false && !empty($this->vars['plugin_config'])){
                    if($this->pluginaizer->data()->value('is_multi_server') == 1){
                        if(array_key_exists($this->pluginaizer->session->userdata(['user' => 'server']), $this->vars['plugin_config'])){
                            $this->vars['plugin_config'] = $this->vars['plugin_config'][$this->pluginaizer->session->userdata(['user' => 'server'])];
                            $this->vars['about'] = $this->pluginaizer->get_about();
                            $this->vars['about']['user_description'] = $this->pluginaizer->data()->value('description');
                        } else{
                            $this->pluginaizer->jsone(['error' => __('Plugin configuration not found.')]);
                        }
                    }
                    if($this->vars['plugin_config']['active'] == 0){
                        echo $this->pluginaizer->jsone(['error' => __('This module has been disabled.')]);
                    } else{
                        if(count($_POST) > 0){
                            $char = isset($_POST['character']) ? (int)$_POST['character'] : '';
                            $game_currency = isset($_POST['game_currency']) ? (int)$_POST['game_currency'] : '';
                            list($c, $z) = explode('/', $this->vars['plugin_config']['ratio']);
                            $this->vars['times'] = floor($game_currency / $c);
                            $this->vars['reward'] = $this->vars['times'] * $z;
                            $this->vars['total'] = $c * $this->vars['times'];
                            if(!$this->pluginaizer->{'M' . $this->pluginaizer->get_plugin_class()}->check_connect_stat($this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server'])))
                                echo $this->pluginaizer->jsone(['error' => __('Please logout from game.')]); else{
                                if($this->check_valid_id($char)){
                                    if($game_currency > $this->vars['plugin_config']['max_credits']){
                                        echo $this->pluginaizer->jsone(['error' => sprintf(__('Maximum Exchange At Once: %s %s'), $this->vars['plugin_config']['max_credits'], $this->pluginaizer->website->translate_credits($this->vars['plugin_config']['exchange_type'], $this->session->userdata(['user' => 'server'])))]);
                                    } else{
                                        if($game_currency < $c){
                                            echo $this->pluginaizer->jsone(['error' => sprintf(__('Minimum Exchange: %s %s'), $c, $this->pluginaizer->website->translate_credits($this->vars['plugin_config']['exchange_type'], $this->session->userdata(['user' => 'server'])))]);
                                        } else{
                                            if(isset($this->vars['char_data']['Money'])){
                                                if(2000000000 <= ($this->vars['char_data']['Money'] + $this->vars['reward'])){
                                                    echo $this->pluginaizer->jsone(['error' => __('Maximu zen on character 2kkk. Please use zen wallet for transfer.')]);
                                                    return;
                                                }
                                            }
                                            if(isset($this->vars['warehouse_data']['Money'])){
                                                if(2000000000 <= ($this->vars['warehouse_data']['Money'] + $this->vars['reward'])){
                                                    echo $this->pluginaizer->jsone(['error' => __('Maximum zen in warehouse 2kkk. Please use zen wallet for transfer.')]);
                                                    return;
                                                }
                                            }
                                            $status = $this->pluginaizer->website->get_user_credits_balance($this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']), $this->vars['plugin_config']['exchange_type'], $this->pluginaizer->session->userdata(['user' => 'id']));
                                            if($status['credits'] < $game_currency){
                                                echo $this->pluginaizer->jsone(['error' => sprintf(__('You have insufficient amount of %s'), $this->pluginaizer->website->translate_credits($this->vars['plugin_config']['exchange_type'], $this->pluginaizer->session->userdata(['user' => 'server'])))]);
                                                return;
                                            }
                                            $this->pluginaizer->{'M' . $this->pluginaizer->get_plugin_class()}->update_zen($this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']), $char, $this->vars['reward']);
                                            $this->pluginaizer->Maccount->add_account_log('Exchanged ' . $this->pluginaizer->website->translate_credits($this->vars['plugin_config']['exchange_type'], $this->pluginaizer->session->userdata(['user' => 'server'])) . ' In Zen Exchange', -$game_currency, $this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                                            $this->pluginaizer->website->charge_credits($this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']), $game_currency, $this->vars['plugin_config']['exchange_type'], $this->pluginaizer->session->userdata(['user' => 'id']));
                                            echo $this->pluginaizer->jsone(['success' => __('Zen exchanged successfully.')]);
                                        }
                                    }
                                } else{
                                    if(isset($this->vars['char_error']))
                                        echo $this->pluginaizer->jsone(['error' => $this->vars['char_error']]);
                                    if(isset($this->vars['warehouse_error']))
                                        echo $this->pluginaizer->jsone(['error' => $this->vars['warehouse_error']]);
                                    if(isset($this->vars['wallet_error']))
                                        echo $this->pluginaizer->jsone(['error' => $this->vars['wallet_error']]);
                                }
                            }
                        }
                    }
                } else{
                    $this->pluginaizer->jsone(['error' => __('Plugin configuration not found.')]);
                }
            } else{
                echo $this->pluginaizer->jsone(['error' => __('Please login into website.')]);
            }
        }

        /**
         *
         * Check if character, warehouse, zen wallet is valid and exists in database
         *
         * return bool
         *
         */
        private function check_valid_id($char = '')
        {
            if(is_numeric($char)){
                if(in_array($char, [-1, -2])){
                    if($char == -1){
                        $this->vars['warehouse_data'] = $this->pluginaizer->{'M' . $this->pluginaizer->get_plugin_class()}->check_warehouse($this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                        if(!$this->vars['warehouse_data'])
                            $this->vars['warehouse_error'] = __('Warehouse not created yet.'); else{
                            return true;
                        }
                    }
                    if($char == -2){
                        $this->vars['wallet_data'] = $this->pluginaizer->{'M' . $this->pluginaizer->get_plugin_class()}->check_zen_wallet($this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']));
                        if(!$this->vars['wallet_data'])
                            $this->vars['wallet_error'] = __('Zen Wallet not created yet.'); else{
                            return true;
                        }
                    }
                } else{
                    if(!$this->vars['char_data'] = $this->pluginaizer->{'M' . $this->pluginaizer->get_plugin_class()}->check_char($this->pluginaizer->session->userdata(['user' => 'username']), $this->pluginaizer->session->userdata(['user' => 'server']), $char)){
                        $this->vars['char_error'] = __('Character not found.');
                    } else{
                        return true;
                    }
                }
            } else{
                $this->vars['char_error'] = __('Invalid id');
            }
            return false;
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
                $this->vars['is_multi_server'] = $this->pluginaizer->data()->value('is_multi_server');
                $this->vars['plugin_config'] = $this->pluginaizer->plugin_config();
                //load any js, css files if required
                $this->vars['js'] = $this->config->base_url . 'assets/plugins/js/' . $this->pluginaizer->get_plugin_class() . '.js';
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
                    'sidebar_public_item' => 0, //add link to module in public sidebar menu, if template supports
                    'account_panel_item' => 1, //add link in user account panel
                    'donation_panel_item' => 0, //add link in donation page
                    'description' => 'Here you can exchange web/game currency for zen.' //description which will see user
                ]);
                //create plugin config template
                $this->pluginaizer->create_config(['active' => 0, 'max_credits' => 1000, 'ratio' => "5/2000", 'exchange_type' => 1]);
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
                $this->pluginaizer->delete_config()->remove_plugin();
                //check for errors
                if(count($this->pluginaizer->error) > 0){
                    $data['error'] = $this->pluginaizer->error;
                }
                $data['success'] = 'Plugin uninstalled successfully';
                echo $this->pluginaizer->jsone($data);
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