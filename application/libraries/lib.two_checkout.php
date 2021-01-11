<?php
    in_file();

    class two_checkout
    {
        private $registry;
        public $data;

        public function __construct()
        {
            $this->registry = controller::get_instance();
            require_once(APP_PATH . DS . 'libraries' . DS . '2checkout' . DS . 'Twocheckout.php');
        }

        public function setup($id, $key)
        {
            Twocheckout::privateKey($key);
            Twocheckout::sellerId($id);
            Twocheckout::verifySSL(false);
            Twocheckout::sandbox(false);
        }

        public function form($params = [])
        {
            return Twocheckout_Charge::form($params, 'auto');
        }

        public function direct($params = [])
        {
            return Twocheckout_Charge::direct($params, 'auto');
        }

        public function redirect($params = [])
        {
            return Twocheckout_Charge::redirect($params);
        }

        public function product_list($id = '')
        {
            if($id != ''){
                return Twocheckout_Product::retrieve(['product_id' => $id]);
            } else{
                return Twocheckout_Product::retrieve();
            }
        }

        public function check($params, $secret)
        {
            return Twocheckout_Return::check($params, $secret);
        }
    }