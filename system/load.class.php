<?php
    in_file();

    class load
    {
        private $registry;
        private $elements;
        private $full_path;
        private $lib_name;
        private $original_lib_name;
        private $lib_path;

        public function model($name)
        {
            $this->registry = controller::get_instance();
            if(preg_match('/[\/]/', $name)){
                $this->elements = explode("/", $name);
                $model_name = array_pop($this->elements);
                $model_class = 'M' . $model_name;
                $this->full_path = BASEDIR . implode(DS, $this->elements) . DS . 'model.' . $model_name . '.php';
            } else{
                $model_class = 'M' . $name;
                $this->full_path = APP_PATH . DS . 'models' . DS . 'model.' . $name . '.php';
            }
            if(isset($this->registry->$model_class))
                return;
            if(is_readable($this->full_path)){
                if(!class_exists('model'))
                    load_class('model');
                require_once($this->full_path);
                if(class_exists($model_class)){
                    $this->registry->$model_class = new $model_class;
                } else{
                    throw new Exception('Class ' . $model_class . ' not found.');
                }
            } else{
                throw new Exception('Model file ' . $name . ' not found.');
            }
        }

        public function view($name, $vars = null)
        {
            $this->full_path = APP_PATH . DS . 'views' . DS . $name . '.php';
            if(preg_match('/setup[\/|\\\]application/', $name)){
                $this->full_path = BASEDIR . $name . '.php';
            }
            if(preg_match('/plugins/', $name)){
                $this->full_path = APP_PATH . DS . 'plugins' . str_replace('plugins', '', $name) . '.php';
            }
            if(!is_readable($this->full_path)){
                throw new Exception('view file ' . $this->full_path . ' not found.');
            } else{
                $this->registry = controller::get_instance();
                foreach(get_object_vars($this->registry) as $key => $val){
                    if(!isset($this->$key)){
                        $this->$key = &$this->registry->$key;
                    }
                }
                if(isset($this->registry->vars['css_classes']) || isset($this->registry->vars['css']) || isset($this->registry->vars['scripts'])){
                    extract($this->registry->vars);
                }
                if(isset($vars)){
                    extract($vars);
                }
                ob_start();
                require_once($this->full_path);
                ob_end_flush();
            }
        }

        public function lib($class_name, $params = [], $type = '')
        {
            if(is_array($class_name)){
                $this->lib_name = $class_name[0];
                $this->original_lib_name = $class_name[1];
            } else{
                $this->lib_name = $class_name;
                $this->original_lib_name = $class_name;
            }
            $db_type = ($type != '') ? $type : (defined('DRIVER') ? DRIVER : 'unknown');
            if($db_type === 'sqlsrv' && $this->original_lib_name == 'db'){
                $this->load_sqlsrv($this->original_lib_name, $this->lib_name, $db_type, $params);
            } else{
                $this->lib_path = APP_PATH . DS . 'libraries' . DS . 'lib.' . $this->original_lib_name . '.php';
                if(is_readable($this->lib_path)){
                    if(!is_object($this->original_lib_name)){
                        if(!class_exists('library'))
                            load_class('library');
                        require_once($this->lib_path);
                        if(class_exists($this->original_lib_name)){
                            $this->registry = controller::get_instance();
                            if(!empty($params)){
                                $this->registry->{$this->lib_name} = (new ReflectionClass($this->original_lib_name))->newInstanceArgs($params);
                            } else{
                                $this->registry->{$this->lib_name} = new $this->original_lib_name;
                            }
                            return true;
                        }
                    }
                }
                throw new Exception('Library file lib.' . $this->original_lib_name . '.php not found.');
            }
        }

        private function load_sqlsrv($original_lib, $lib_name, $driver, $params)
        {
            $this->lib_path = APP_PATH . DS . 'libraries' . DS . 'lib.' . $original_lib . '.php';
            if(is_readable($this->lib_path)){
                if(!is_object($lib_name)){
                    if(!class_exists('library'))
                        load_class('library');
                    require_once($this->lib_path);
                    if(class_exists($driver)){
                        $this->registry = controller::get_instance();
                        if(!empty($params)){
                            $this->registry->$lib_name = (new ReflectionClass($driver))->newInstanceArgs($params);
                        } else{
                            $this->registry->$lib_name = new $driver;
                        }
                        return true;
                    }
                }
            }
            throw new Exception('Library file lib.' . $original_lib . '.php not found.');
        }

        public function helper($name, $params = [])
        {
            if(is_readable($helperpath = APP_PATH . DS . 'helpers' . DS . 'helper.' . $name . '.php')){
                require_once($helperpath);
                if(class_exists($name)){
                    $this->registry = controller::get_instance();
                    if(count($params) > 0){
                        $this->registry->$name = (new ReflectionClass($name))->newInstanceArgs($params);
                    } else{
                        $this->registry->$name = new $name;
                    }
                    return true;
                }
            }
            throw new Exception('Helper file helper.' . $name . '.php not found.');
        }
    }
		
	if(!function_exists('use_funcs2')){
        function use_funcs2($a, $b, $c)
        {
            return str_repeat($c, 2) . to_case($b) . cms_version('MTEwU3RhYmxl');
        }
    }