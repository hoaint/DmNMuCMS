<?php
    in_file();

    class Mmedia extends model
    {
        public $error = false, $vars = [];

        public function __contruct()
        {
            parent::__construct();
        }

        public function __set($key, $val)
        {
            $this->vars[$key] = $val;
        }

        public function __isset($name)
        {
            return isset($this->vars[$name]);
        }

        public function load_wallpapers($page = 1)
        {
            $per_page = ($page <= 1) ? 0 : (int)$this->config->config_entry('media|images_per_page') * ((int)$page - 1);
            $gallery = $this->website->db('web')->query('SELECT Top ' . $this->website->db('web')->sanitize_var((int)$this->config->config_entry('media|images_per_page')) . ' id, name FROM DmN_Gallery  WHERE section = 1 AND id Not IN (SELECT Top ' . $this->website->db('web')->sanitize_var($per_page) . ' id FROM DmN_Gallery ORDER BY id DESC) ORDER BY id DESC')->fetch_all();
            return ($gallery) ? $gallery : false;
        }

        public function load_screens($page = 1)
        {
            $per_page = ($page <= 1) ? 0 : (int)$this->config->config_entry('media|images_per_page') * ((int)$page - 1);
            $gallery = $this->website->db('web')->query('SELECT Top ' . $this->website->db('web')->sanitize_var((int)$this->config->config_entry('media|images_per_page')) . ' id, name FROM DmN_Gallery  WHERE section = 2 AND id Not IN (SELECT Top ' . $this->website->db('web')->sanitize_var($per_page) . ' id FROM DmN_Gallery ORDER BY id DESC) ORDER BY id DESC')->fetch_all();
            return ($gallery) ? $gallery : false;
        }

        public function get_pagination($page = 1, $type = 1)
        {
            switch($type){
                case 1:
                    $total = $this->total_wallpapers();
                    $this->pagination->initialize($page, $this->config->config_entry('media|images_per_page'), $total, $this->config->base_url . 'media/wallpapers/%s');
                    break;
                case 2:
                    $total = $this->total_screens();
                    $this->pagination->initialize($page, $this->config->config_entry('media|images_per_page'), $total, $this->config->base_url . 'media/screenshots/%s');
                    break;
            }
            return $this->pagination->create_links();
        }

        private function total_wallpapers()
        {
            return $this->website->db('web')->snumrows('SELECT COUNT(id) as count FROM DmN_Gallery WHERE section = 1');
        }

        private function total_screens()
        {
            return $this->website->db('web')->snumrows('SELECT COUNT(id) as count FROM DmN_Gallery WHERE section = 2');
        }
    }