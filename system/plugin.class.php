<?php
    in_file();

    class plugin
    {
        private $registry;
        private $config;
        private $load;
        private $about_file;
        private $about = false;
        private $file_name = '';
        private $plugin_data = [];
        private $plugin_config;
        public $server_list;
        private $plugin_class = '';
        public $error = [];
        private $scheme;
        private $table_name;

        public function __construct()
        {
            $this->registry = controller::get_instance();
            $this->config = load_class('config');
            $this->load = load_class('load');
            $this->server_list = server_list();
            $this->load->helper('website');
            $this->load->lib('session', ['DmNCMS']);
            $this->load->lib('csrf');
            $this->load->lib("pagination");
            $this->load->model('home');
            $this->load->helper('breadcrumbs', [$this->request]);
            $this->load->helper('meta');
            $this->load->lib('fb');
            $this->load->lib(['database', 'db'], [HOST, USER, PASS, WEB_DB]);
        }

        public function set_plugin_class($class)
        {
            $this->plugin_class = $class;
        }

        public function get_plugin_class()
        {
            return $this->plugin_class;
        }

        public function set_about()
        {
            $this->about_file = APP_PATH . DS . 'plugins' . DS . $this->plugin_class . DS . 'about.json';
            if(file_exists($this->about_file)){
                $this->about = $this->jsond(file_get_contents($this->about_file), true);
            } else{
                $this->about = ['name' => $this->plugin_class, 'version' => false, 'description' => false, 'developed_by' => false, 'website' => false, 'update_url' => false];
            }
            return $this;
        }

        public function get_about()
        {
            $this->about_file = APP_PATH . DS . 'plugins' . DS . $this->plugin_class . DS . 'about.json';
            if(file_exists($this->about_file)){
                return $this->jsond(file_get_contents($this->about_file), true);
            }
            return false;
        }

        public function add_plugin($data = [])
        {
            try{
                if(is_array($data)){
                    if(!empty($data)){
                        $config = $this->config->values('plugin_config');
                        if(!isset($config[$this->plugin_class])){
                            $config[$this->plugin_class] = $data;
                            $config[$this->plugin_class]['about'] = $this->about;
                            if($this->config->save_config_data($config, 'plugin_config', false)){
                                return true;
                            }
                        } else{
                            throw new Exception('Plugin data already exists. Skipping...');
                        }
                    } else{
                        throw new Exception('Plugin data is empty');
                    }
                } else{
                    throw new Exception('Plugin data should be array');
                }
            } catch(Exception $e){
                $this->error[] = $e->getMessage();
            }
        }

        public function remove_plugin()
        {
            try{
                $config = $this->config->values('plugin_config');
                if(isset($config[$this->plugin_class])){
                    unset($config[$this->plugin_class]);
                    if($this->config->save_config_data($config, 'plugin_config', false)){
                        return true;
                    }
                } else{
                    throw new Exception('Plugin data not found. Skipping...');
                }
            } catch(Exception $e){
                $this->error[] = $e->getMessage();
            }
        }

        public function enable_plugin()
        {
            try{
                $config = $this->config->values('plugin_config');
                if(isset($config[$this->plugin_class])){
                    if($config[$this->plugin_class]['installed'] == 1){
                        throw new Exception('Plugin already installed.');
                    } else{
                        $config[$this->plugin_class]['installed'] = 1;
                        if($this->config->save_config_data($config, 'plugin_config', false)){
                            return true;
                        }
                    }
                } else{
                    throw new Exception('Plugin data not found.');
                }
            } catch(Exception $e){
                $this->error[] = $e->getMessage();
            }
        }

        public function disable_plugin()
        {
            try{
                $config = $this->config->values('plugin_config');
                if(isset($config[$this->plugin_class])){
                    if($config[$this->plugin_class]['installed'] == 0){
                        throw new Exception('Plugin already disabled.');
                    } else{
                        $config[$this->plugin_class]['installed'] = 0;
                        if($this->config->save_config_data($config, 'plugin_config', false)){
                            return true;
                        }
                    }
                } else{
                    throw new Exception('Plugin data not found.');
                }
            } catch(Exception $e){
                $this->error[] = $e->getMessage();
            }
        }

        public function add_sql_scheme($scheme_file)
        {
            $file = APP_PATH . DS . 'plugins' . DS . $this->plugin_class . DS . 'sql_schemes' . DS . $scheme_file . '.json';
            if(file_exists($file)){
                $this->scheme = $this->jsond(file_get_contents($file), true);
                $this->table_name = key($this->scheme);
                if(is_array($this->scheme) && !empty($this->scheme)){
                    $this->create_table()->create_columns();
                }
            }
        }

        public function remove_sql_scheme($scheme_file)
        {
            $file = APP_PATH . DS . 'plugins' . DS . $this->plugin_class . DS . 'sql_schemes' . DS . $scheme_file . '.json';
            if(file_exists($file)){
                $this->scheme = $this->jsond(file_get_contents($file), true);
                $this->table_name = key($this->scheme);
                if(is_array($this->scheme) && !empty($this->scheme)){
                    $this->remove_table();
                }
            }
            return $this;
        }

        private function create_table()
        {
            try{
                if(!$this->database->check_if_table_exists($this->table_name)){
                    if(isset($this->scheme[$this->table_name]['create_query'])){
                        if($this->database->query($this->scheme[$this->table_name]['create_query'])){
                            unset($this->scheme[$this->table_name]['create_query']);
                        } else{
                            throw new Exception('Unable to create sql table ' . $this->table_name);
                        }
                    } else{
                        throw new Exception('Unable to find table create query. Please correct your sql scheme file.');
                    }
                } else{
                    throw new Exception('Sql table ' . $this->table_name . ' already exists. Skipping...');
                }
            } catch(Exception $e){
                $this->error[] = $e->getMessage();
            }
            return $this;
        }

        private function remove_table()
        {
            try{
                if($this->database->check_if_table_exists($this->table_name)){
                    if(!$this->database->remove_table($this->table_name)){
                        throw new Exception('Unable to remove table ' . $this->table_name . '. Skipping...');
                    }
                } else{
                    throw new Exception('Table ' . $this->table_name . ' not found. Skipping...');
                }
            } catch(Exception $e){
                $this->error[] = $e->getMessage();
            }
            return $this;
        }

        private function create_columns()
        {
            try{
                if(isset($this->scheme[$this->table_name]['create_query'])){
                    unset($this->scheme[$this->table_name]['create_query']);
                }
                foreach($this->scheme[$this->table_name] AS $key => $column_data){
                    if($this->database->check_if_column_exists($key, $this->table_name) == null){
                        $this->database->add_column($key, $this->table_name, $column_data);
                    }
                }
            } catch(Exception $e){
                $this->error[] = $e->getMessage();
            }
        }

        public function create_config($data = [])
        {
            try{
                if(is_array($data)){
                    if(!empty($data)){
                        $this->plugin_config = $this->config->values($this->plugin_class);
                        if(empty($this->plugin_config)){
                            $this->data();
                            if($this->plugin_data[$this->plugin_class]['is_multi_server']){
                                foreach($this->server_list AS $key => $value){
                                    $this->plugin_config[$key] = $data;
                                }
                                if($this->config->save_config_data($this->plugin_config, $this->plugin_class, false)){
                                    return true;
                                }
                            } else{
                                if($this->config->save_config_data($data, $this->plugin_class, false)){
                                    return true;
                                }
                            }
                        } else{
                            throw new Exception('Plugin config data already exists. Skipping...');
                        }
                    } else{
                        throw new Exception('Plugin config data is empty');
                    }
                } else{
                    throw new Exception('Plugin config data should be array');
                }
            } catch(Exception $e){
                $this->error[] = $e->getMessage();
            }
        }

        public function delete_config()
        {
            try{
                $config_file = APP_PATH . DS . 'config' . DS . $this->plugin_class . '.json';
                if(file_exists($config_file)){
                    if(!unlink($config_file)){
                        throw new Exception('Unable to remove plugin config file. Skipping...');
                    }
                } else{
                    throw new Exception('Config file not found. Skipping...');
                }
            } catch(Exception $e){
                $this->error[] = $e->getMessage();
            }
            return $this;
        }

        public function data()
        {
            if(isset($this->plugin_data[$this->plugin_class])){
                return $this;
            } else{
                $this->plugin_data[$this->plugin_class] = $this->config->values('plugin_config', $this->plugin_class);
                return $this;
            }
        }

        public function value($value)
        {
            if($this->plugin_data[$this->plugin_class] != false){
                if(in_array($value, $this->plugin_data[$this->plugin_class])){
                    return $this->plugin_data[$this->plugin_class][$value];
                }
            }
            return false;
        }

        public function values()
        {
            if($this->plugin_data[$this->plugin_class] != false){
                //if(in_array($value, $this->plugin_data[$this->plugin_class])){
                return $this->plugin_data[$this->plugin_class];
                //}
            }
            return false;
        }

        public function plugin_config()
        {
            if(isset($this->plugin_config)){
                return $this->plugin_config;
            } else{
                $this->plugin_config = $this->config->values($this->plugin_class);
                return $this->plugin_config;
            }
        }

        public function save_config($config)
        {
            try{
                $config_file = APP_PATH . DS . 'config' . DS . $this->plugin_class . '.json';
                if(file_exists($config_file)){
                    if($this->config->save_config_data($config, $this->plugin_class, false)){
                        return true;
                    }
                } else{
                    throw new Exception('Config file not found.');
                }
            } catch(Exception $e){
                $this->error[] = $e->getMessage();
            }
        }

        public function redirect($module)
        {
            header('Location: ' . $module);
        }

        public function jsond($data, $array = true)
        {
            $json_data = json_decode($data, (bool)$array);
            if($json_data == null){
                $this->handle_json_error(json_last_error());
            } else{
                return $json_data;
            }
        }

        public function jsone($data, $pretty_print = JSON_PRETTY_PRINT)
        {
			header('Content-Type: application/json');
            $json_data = json_encode($data, $pretty_print);
            if($json_data == null){
                $this->handle_json_error(json_last_error());
            } else{
                return $json_data;
            }
        }

        private function handle_json_error($errno)
        {
            $messages = [JSON_ERROR_NONE => 'JSON - No errors', JSON_ERROR_DEPTH => 'JSON - Maximum stack depth exceeded', JSON_ERROR_STATE_MISMATCH => 'JSON - Underflow or the modes mismatch', JSON_ERROR_CTRL_CHAR => 'JSON - Unexpected control character found', JSON_ERROR_SYNTAX => 'JSON - Syntax error, malformed JSON', JSON_ERROR_UTF8 => 'JSON - Malformed UTF-8 characters, possibly incorrectly encoded'];
            throw new Exception(isset($messages[$errno]) ? $messages[$errno] : 'Unknown JSON error: ' . $errno);
        }

        public function __get($var)
        {
            if(isset($this->registry->$var))
                return $this->registry->$var;
        }

        public function __set($key, $val)
        {
            $this->$key = $val;
        }
    }

