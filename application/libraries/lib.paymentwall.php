<?php
    in_file();

    class paymentwall
    {
        private $registry;
        public $data;

        public function __construct()
        {
            $this->registry = controller::get_instance();
            require_once(APP_PATH . DS . 'libraries' . DS . 'PaymentWall' . DS . 'paymentwall.php');
        }

        public function setup($key, $secret)
        {
            \Paymentwall_Config::getInstance()->set(array(
				'api_type' => \Paymentwall_Config::API_VC,
				'public_key' => $key,
				'private_key' => $secret
			));
        }

        
    }