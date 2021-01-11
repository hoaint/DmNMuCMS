<?php
    in_file();

    class interkassa extends library
    {
        const METHOD_GET = 'GET';
        const METHOD_POST = 'POST';
        const METHOD_LINK = 'LINK';
        const METHOD_OFF = 'OFF';
        const STATE_SUCCESS = 'success';
        const STATE_FAIL = 'fail';
        const FEES_PAYER_SHOP = 0;
        const FEES_PAYER_BUYER = 1;
        const FEES_PAYER_EQUAL = 2;

        public function __construct()
        {
            ini_set('unserialize_callback_func', 'spl_autoload_call');
            spl_autoload_register(['Interkassa', 'autoload']);
        }

        public static function autoload($class)
        {
            if(class_exists($class, false) || interface_exists($class, false))
                return true;
            if(strpos($class, 'Interkassa_') !== 0)
                return false;
            $dir = dirname(__FILE__);
            $bits = explode('_', $class);
            if(!function_exists('lcfirst'))
                foreach($bits as $i => $bit)
                    $bits[$i] = strtolower($bit[0]) . substr($bit, 1); else
                $bits = array_map('lcfirst', $bits);
            $file = $dir . DS . implode(DS, $bits) . '.php';
            if(file_exists($file)){
                require $file;
                return class_exists($class, false) || interface_exists($class, false);
            }
            return false;
        }
    }