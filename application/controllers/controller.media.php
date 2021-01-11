<?php
    in_file();

    class media extends controller
    {
        public $vars = [], $errors = [];

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('website');
            $this->load->lib('session', ['DmNCMS']);
            $this->load->helper('meta');
            $this->load->helper('breadcrumbs', [$this->request]);
            $this->load->lib("pagination");
            $this->load->model('media');
        }

        public function index()
        {
            throw new Exception('Nothing to see in here.');
        }

        public function wallpapers($page = 1)
        {
            if(!$this->website->module_disabled('media')){
                $this->vars['css'] = ['<link rel="stylesheet" href="' . $this->config->base_url . 'assets/' . $this->config->config_entry('main|template') . '/css/colorbox.css" type="text/css" />' . "\n"];
                $this->vars['scripts'] = ['<script src="' . $this->config->base_url . 'assets/' . $this->config->config_entry('main|template') . '/js/jquery.colorbox.min.js"></script>'];
                $this->load->view($this->config->config_entry('main|template') . DS . 'view.header', $this->vars);
                if($gallery = $this->Mmedia->load_wallpapers($page)){
                    $this->vars['gallery'] = $gallery;
                    $this->vars['pagination'] = $this->Mmedia->get_pagination($page, 1);
                } else{
                    $this->vars['error'] = __('No images found');
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'media' . DS . 'view.wallpapers', $this->vars);
            }
        }

        public function screenshots($page = 1)
        {
            if(!$this->website->module_disabled('media')){
                $this->vars['css'] = ['<link rel="stylesheet" href="' . $this->config->base_url . 'assets/' . $this->config->config_entry('main|template') . '/css/colorbox.css" type="text/css" />' . "\n"];
                $this->vars['scripts'] = ['<script src="' . $this->config->base_url . 'assets/' . $this->config->config_entry('main|template') . '/js/jquery.colorbox.min.js"></script>'];
                $this->load->view($this->config->config_entry('main|template') . DS . 'view.header', $this->vars);
                if($gallery = $this->Mmedia->load_screens($page)){
                    $this->vars['gallery'] = $gallery;
                    $this->vars['pagination'] = $this->Mmedia->get_pagination($page, 2);
                } else{
                    $this->vars['error'] = __('No images found');
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'media' . DS . 'view.screens', $this->vars);
            }
        }
    }