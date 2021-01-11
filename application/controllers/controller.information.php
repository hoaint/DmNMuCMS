<?php
    class information extends controller
    {
        protected $vars = [], $errors = [];

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('website');
            $this->load->lib('session', ['DmNCMS']);
			$this->load->lib('csrf');
            $this->load->helper('meta');
            $this->load->helper('breadcrumbs', [$this->request]);
            $this->load->model('downloads');
        }

        public function index()
        {
            $this->load->view($this->config->config_entry('main|template') . DS . 'information' . DS . 'view.information', $this->vars);
        }
    }