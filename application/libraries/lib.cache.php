<?php
    in_file();

    class cache
    {
        private $_Class;
        private $_cacheClass;

        public function __construct($type = 'File', $options = [])
        {
            $class_file = APP_PATH . DS . 'libraries' . DS . 'Cache' . DS . $type . 'Cache.php';
            try{
                if(!file_exists($class_file)){
                    throw new Exception('Cache class file not found: ' . $type . 'Cache.php');
                } else{
                    require_once $class_file;
                    $this->_Class = $type . 'Cache';
                    if(class_exists($this->_Class)){
                        $this->_cacheClass = new $this->_Class($options);
                    } else{
                        throw new Exception('Cache class not found: ' . $type . 'Cache');
                    }
                }
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }

        public function get($key, $delete_old_cache = true)
        {
            return $this->_cacheClass->get($key, $delete_old_cache);
        }

        public function set($key, $data, $lifetime)
        {
            return $this->_cacheClass->save($key, $data, $lifetime);
        }

        public function remove($key, $data, $lifetime)
        {
            return $this->_cacheClass->delete($key);
        }

        public function last_cached($key)
        {
            return $this->_cacheClass->last_cached($key);
        }
    }
