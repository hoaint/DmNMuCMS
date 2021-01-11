<?php
    in_file();

    class breadcrumbs
    {
        protected $request;
        protected $breadcrumbs = '';

        public function __construct($request)
        {
            $this->request = $request;
            $this->registry = controller::get_instance();
            $this->config = $this->registry->config;
        }

        public function make_breadcrumbs()
        {
            if(in_array($this->request->get_method(), ['index', 'category', 'buy'])){
                $this->breadcrumbs .= '<a href="base_url' . $this->request->get_controller() . '">' . ucfirst(str_replace('_', ' ', str_replace('-', ' ', htmlspecialchars($this->request->get_controller())))) . '</a>';
            } else{
                $this->breadcrumbs .= '<a href="base_url' . $this->request->get_controller() . '">' . ucfirst(str_replace('_', ' ', str_replace('-', ' ', htmlspecialchars($this->request->get_controller())))) . '</a> &raquo; <a href="base_url' . $this->request->get_controller() . '/' . $this->request->get_method() . '">' . ucfirst(str_replace('_', ' ', str_replace('-', ' ', htmlspecialchars($this->request->get_method())))) . '</a>';
            }
            return $this->breadcrumbs;
        }
    }