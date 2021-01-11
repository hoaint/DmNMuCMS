<?php
    in_file();

    class guides extends controller
    {
        protected $vars = [], $errors = [];

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('website');
            $this->load->lib('session', ['DmNCMS']);
			$this->load->lib('csrf');
            $this->load->model('guides');
            $this->load->helper('breadcrumbs', [$this->request]);
            $this->load->helper('meta');
            $this->load->lib('fb');
        }

        public function index()
        {
            $this->vars['guides'] = $this->Mguides->load_guides();
            $this->load->view($this->config->config_entry('main|template') . DS . 'guides' . DS . 'view.guides', $this->vars);
        }

        public function read($title, $id)
        {
            if(ctype_digit($id)){
                $this->vars['guide'] = $this->Mguides->load_guide_by_id($id);
                if(!$this->vars['guide']){
                    $this->vars['error'] = __('News article not found.');
                }
            } else{
                $this->vars['error'] = __('News article not found.');
            }
            $this->load->view($this->config->config_entry('main|template') . DS . 'guides' . DS . 'view.read_guide', $this->vars);
        }
    }