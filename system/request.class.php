<?php
    in_file();

    class request
    {
        private $_controller, $_method, $_args;

        public function __construct()
        {
            if((empty($_GET['action'])) && (empty($_POST['action']))){
                $action = (preg_match('/setup/', $_SERVER['REQUEST_URI'])) ? 'setup' : 'home';
            } else
                $action = isset($_POST['action']) ? $_POST['action'] : $_GET['action'];
            $action = explode('/', trim(preg_replace('/[^a-zA-Z0-9_@#$&amp;%[]()-,<\/]/i', '', $action)));
            $this->_controller = (isset($action[0])) ? str_replace('-', '_', $action[0]) : 'index';
            $this->_method = (isset($action[1]) && $action[1] != '') ? str_replace('-', '_', $action[1]) : 'index';
            $this->_args = (isset($action[2])) ? array_slice($action, 2) : [];
        }

        public function get_controller()
        {
            return $this->_controller;
        }

        public function get_method()
        {
            return $this->_method;
        }

        public function get_args()
        {
            return $this->_args;
        }
    }