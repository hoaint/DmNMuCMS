<?php
    in_file();

    class rules extends controller
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
            $this->vars['rules'] = file_get_contents(BASEDIR . 'assets' . DS . 'rules.html');
            $this->load->view($this->config->config_entry('main|template') . DS . 'rules' . DS . 'view.rules', $this->vars);
        }
    }