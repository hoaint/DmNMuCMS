<?php

    class maintenance extends controller
    {
        protected $vars = [];

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('meta');
        }

        public function index($type = '503')
        {
            if($this->config->config_entry('main|maintenance') == 0){
                header('Location: ' . $this->config->base_url);
            }
            switch($type){
                default:
                case '503':
                    $this->load->view($this->config->config_entry('main|template') . DS . 'errors' . DS . 'view.503');
                    break;
            }
        }
    }