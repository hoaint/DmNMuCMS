<?php

    class tos extends controller
    {
        protected $vars = [], $errors = [];

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('website');
            $this->load->lib('session', ['DmNCMS']);
            $this->load->helper('meta');
            $this->load->helper('breadcrumbs', [$this->request]);
        }

        public function index()
        {
            $this->load->view($this->config->config_entry('main|template') . DS . 'tos' . DS . 'view.tos', $this->vars);
        }
    }