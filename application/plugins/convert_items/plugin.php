<?php

    class _plugin_convert_items extends controller implements pluginInterface
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
            // user module not used in this plugin
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
                $this->vars['js'] = $this->config->base_url . 'assets/plugins/js/convert_items.js';
                //load template
                $this->load->view('plugins' . DS . $this->pluginaizer->get_plugin_class() . DS . 'views' . DS . 'admin' . DS . 'view.index', $this->vars);
            } else{
                $this->pluginaizer->redirect($this->config->base_url . 'admincp/login?return=' . str_replace('_', '-', $this->pluginaizer->get_plugin_class()) . '/admin');
            }
        }

        public function run($page = 1)
        {
            //check if visitor has administrator privilleges
            if($this->pluginaizer->session->is_admin()){
                $this->vars['plugin_config'] = $this->pluginaizer->plugin_config();
                $server = isset($_POST['server']) ? $_POST['server'] : '';
                if($server == '')
                    echo $this->pluginaizer->jsone(['error' => __('Invalid server selected.')]); else{
                    if(!array_key_exists($server, $this->vars['plugin_config'])){
                        echo $this->pluginaizer->jsone(['error' => __('Plugin configuration not found.')]);
                    } else{
                        $this->vars['plugin_config'] = $this->vars['plugin_config'][$server];
                        $this->load->model('application/plugins/convert_items/models/convert_items');
                        if($page == 1){
                            $this->vars['inventory_size'] = $this->pluginaizer->Mconvert_items->get_inv_size($server);
                            if($this->vars['inventory_size']['length'] != 7584){
                                $this->pluginaizer->Mconvert_items->update_inv_size($server);
                            }
                        }
                        $this->pluginaizer->Mconvert_items->load_char_list($page, 50, $server, $this->vars['plugin_config']);
                        $this->pluginaizer->load->lib('pagination');
                        $this->pluginaizer->pagination->initialize($page, 50, $this->pluginaizer->Mconvert_items->count_total_chars($server), '');
                        $this->pluginaizer->pagination->getLimit();
                        $this->pluginaizer->Mconvert_items->get_serial($server);
                        $this->pluginaizer->Mconvert_items->convert_inventory($server, $this->vars['plugin_config']);
                        $this->pluginaizer->Mconvert_items->set_serial($server);
                        if($this->pluginaizer->pagination->lastpage == $page){
                            echo $this->pluginaizer->jsone(['next_page' => -1, 'converted' => 'Converted all Inventories. Moving to Warehouses.']);
                        } else{
                            echo $this->pluginaizer->jsone(['next_page' => $page + 1, 'converted' => 'Converted ' . ($page * 50) . ' Inventories. Moving to next.']);
                        }
                    }
                }
            } else{
                echo $this->pluginaizer->jsone(['error' => __('Please login into website.')]);
            }
        }

        public function run_warehouses($page = 1)
        {
            //check if visitor has administrator privilleges
            if($this->pluginaizer->session->is_admin()){
                $this->vars['plugin_config'] = $this->pluginaizer->plugin_config();
                $server = isset($_POST['server']) ? $_POST['server'] : '';
                if($server == '')
                    echo $this->pluginaizer->jsone(['error' => __('Invalid server selected.')]); else{
                    if(!array_key_exists($server, $this->vars['plugin_config'])){
                        echo $this->pluginaizer->jsone(['error' => __('Plugin configuration not found.')]);
                    } else{
                        $this->vars['plugin_config'] = $this->vars['plugin_config'][$server];
                        $this->load->model('application/plugins/convert_items/models/convert_items');
                        if($page == 1){
                            $this->vars['inventory_size'] = $this->pluginaizer->Mconvert_items->get_wh_size($server);
                            if($this->vars['inventory_size']['length'] != 7680){
                                $this->pluginaizer->Mconvert_items->update_warehouse_size($server);
                            }
                            $check_id = $this->pluginaizer->Mconvert_items->check_if_column_exists('warehouse_id', 'Warehouse', $server);
                            if($check_id == null || $check_id == false){
                                $constrait = $this->pluginaizer->Mconvert_items->get_primary_key_constrait('Warehouse', $server);
                                if($constrait != false){
                                    $this->pluginaizer->Mconvert_items->drop_constrait('Warehouse', $constrait['CONSTRAINT_NAME'], $server);
                                    $_SESSION['constriat'] = $constrait['CONSTRAINT_NAME'];
                                }
                                $this->pluginaizer->Mconvert_items->add_column('warehouse_id', 'Warehouse', ['type' => 'int', 'is_primary_key' => 1, 'null' => 0, 'identity' => 1, 'default' => ''], $server);
                            }
                        }
                        $this->pluginaizer->Mconvert_items->load_account_list($page, 50, $server, $this->vars['plugin_config']);
                        $this->pluginaizer->load->lib('pagination');
                        $this->pluginaizer->pagination->initialize($page, 50, $this->pluginaizer->Mconvert_items->count_total_accounts($server), '');
                        $this->pluginaizer->pagination->getLimit();
                        $this->pluginaizer->Mconvert_items->get_serial($server);
                        $this->pluginaizer->Mconvert_items->convert_warehouse($server, $this->vars['plugin_config']);
                        $this->pluginaizer->Mconvert_items->set_serial($server);
                        if($this->pluginaizer->pagination->lastpage == $page){
                            $constrait = $this->pluginaizer->Mconvert_items->get_primary_key_constrait('Warehouse', $server);
                            if($constrait != false){
                                $this->pluginaizer->Mconvert_items->drop_constrait('Warehouse', $constrait['CONSTRAINT_NAME'], $server);
                                $this->pluginaizer->Mconvert_items->drop_column('warehouse_id', 'Warehouse', $server);
                                $this->pluginaizer->Mconvert_items->add_constrait('Warehouse', 'PK_Warehouse', $server);
                                unset($_SESSION['constriat']);
                            }
                            echo $this->pluginaizer->jsone(['next_page' => -1, 'converted' => 'All conversions have been done successfully.']);
                        } else{
                            echo $this->pluginaizer->jsone(['next_page' => $page + 1, 'converted' => 'Converted ' . ($page * 50) . ' Warehouses. Moving to next.']);
                        }
                    }
                }
            } else{
                echo $this->pluginaizer->jsone(['error' => __('Please login into website.')]);
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
                    'account_panel_item' => 0, //add link in user account panel
                    'donation_panel_item' => 0, //add link in donation page
                    'description' => '' //description which will see user
                ]);
                $this->pluginaizer->create_config(['inventory_size' => 3776, 'warehouse_size' => 3840]);
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