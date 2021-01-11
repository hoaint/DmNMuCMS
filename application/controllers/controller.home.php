<?php
    in_file();

    class home extends controller
    {
        protected $vars = [], $errors = [];

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('website');
            $this->load->lib('session', ['DmNCMS']);
			$this->load->lib('csrf');
            $this->load->lib("pagination");
            $this->load->model('home');
            $this->load->helper('breadcrumbs', [$this->request]);
            $this->load->helper('meta');
            $this->load->lib('fb');
        }

        public function index($page = 1)
        {
            if(!$this->website->module_disabled('news')){
                $this->vars['news'] = $this->Mhome->load_news($page);
                $this->pagination->initialize($page, $this->config->config_entry('news|news_per_page'), $this->Mhome->count_total_news(), $this->config->base_url . 'home/index/%s');
                $this->vars['pagination'] = $this->pagination->create_links();
                $this->load->view($this->config->config_entry('main|template') . DS . 'home' . DS . 'view.home', $this->vars);
            }
        }

        public function read_news($title, $id)
        {
            if(!$this->website->module_disabled('news')){
                if(ctype_digit($id)){
                    if($this->vars['news'] = $this->Mhome->load_news_by_id($id)){
                        $this->Mhome->update_views($id);
                    } else{
                        $this->vars['error'] = __('News article not found.');
                    }
                } else{
                    $this->vars['error'] = __('News article not found.');
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'home' . DS . 'view.read_news', $this->vars);
            }
        }
    }