<?php
    in_file();

    class serverfile extends library
    {
        private $lang;
        private $info = [];

        public function __construct()
        {
            if($this->config->config_entry('main|cache_type') == 'file'){
                $this->load->lib(['cacher', 'cache'], ['File', ['cache_dir' => APP_PATH . DS . 'data' . DS . 'shop']]);
            } else{
                $this->load->lib(['cacher', 'cache'], ['MemCached', ['ip' => $this->config->config_entry('main|mem_cached_ip'), 'port' => $this->config->config_entry('main|mem_cached_port')]]);
            }
            $this->set_language();
        }

        public function __isset($key)
        {
            return isset($this->info[$key]);
        }

        public function get($key)
        {
            return isset($this->info[$key]) ? $this->info[$key] : false;
        }

        public function __set($key, $val)
        {
            $this->info[$key] = $val;
        }

        public function item_list($cat, $size = 32)
        {
            $cached_data = $this->cacher->get('item_list[' . $size . '][' . $cat . ']#' . $this->lang, false);
            if($cached_data != false)
                $this->items = $cached_data;
            return $this;
        }

        public function item_tooltip()
        {
            $cached_data = $this->cacher->get('item_tooltip#' . $this->lang, false);
            if($cached_data != false)
                $this->item_tooltip = $cached_data;
            return $this;
        }

        public function item_tooltip_text()
        {
            $cached_data = $this->cacher->get('item_tooltip_text#' . $this->lang, false);
            if($cached_data != false)
                $this->item_tooltip_text = $cached_data;
            return $this;
        }

        public function jewel_of_harmony_option()
        {
            $cached_data = $this->cacher->get('jewel_of_harmony_option#' . $this->lang, false);
            if($cached_data != false)
                $this->jewel_of_harmony_option = $cached_data;
            return $this;
        }

        public function npc_names()
        {
            $cached_data = $this->cacher->get('npc_names#' . $this->lang, false);
            if($cached_data != false)
                $this->npc_names = $cached_data;
            return $this;
        }

        public function pentagram_jewel_option_value($version = 4)
        {
            if($version >= 5)
                $cached_data = $this->cacher->get('pentagram_jewel_option_value[5]#' . $this->lang, false); else
                $cached_data = $this->cacher->get('pentagram_jewel_option_value#' . $this->lang, false);
            if($cached_data != false)
                $this->pentagram_jewel_option_value = $cached_data;
            return $this;
        }

        public function pentagram_option_1()
        {
            $this->website->check_cache('pentagram_option_1#' . $this->lang, 'pentagram_option_1', $this->cache_time, false);
            if($cached_data != false)
                $this->pentagram_option_1 = $cached_data;
        }

        public function pentagram_option_2()
        {
            $cached_data = $this->cacher->get('pentagram_option_2#' . $this->lang, false);
            if($cached_data != false)
                $this->pentagram_option_2 = $cached_data;
        }

        public function skill()
        {
            $cached_data = $this->cacher->get('skill#' . $this->lang, false);
            if($cached_data != false)
                $this->skill = $cached_data;
            return $this;
        }

        public function socket_item($version = 5)
        {
            if($version > 5)
                $cached_data = $this->cacher->get('socket_item[6]#' . $this->lang, false); 
			else
                $cached_data = $this->cacher->get('socket_item#' . $this->lang, false);
            if($cached_data != false)
                $this->socket_item = $cached_data;
            return $this;
        }

		// @ioncube.dk use_funcs2("DmN ","cms", "DmN") -> "DmNDmNCMS110Stable" RANDOM
        public function exe_common()
        {
            $cached_data = $this->cacher->get('exe_common#' . $this->lang, false);
            if($cached_data != false)
                $this->exe_common = $cached_data;
            return $this;
        }

        public function exe_wing()
        {
            $cached_data = $this->cacher->get('exe_wing#' . $this->lang, false);
            if($cached_data != false)
                $this->exe_wing = $cached_data;
            return $this;
        }

        public function item_add_option()
        {
            $cached_data = $this->cacher->get('item_add_option#' . $this->lang, false);
            if($cached_data != false)
                $this->item_add_option = $cached_data;
        }

        public function item_level_tooltip()
        {
            $cached_data = $this->cacher->get('item_level_tooltip#' . $this->lang, false);
            if($cached_data != false)
                $this->item_level_tooltip = $cached_data;
            return $this;
        }

        public function item_set_option()
        {
            $cached_data = $this->cacher->get('item_set_option#' . $this->lang, false);
            if($cached_data != false)
                $this->item_set_option = $cached_data;
            return $this;
        }

        public function item_set_option_text()
        {
            $cached_data = $this->cacher->get('item_set_option_text#' . $this->lang, false);
            if($cached_data != false)
                $this->item_set_option_text = $cached_data;
            return $this;
        }

        public function item_set_type()
        {
            $cached_data = $this->cacher->get('item_set_type#' . $this->lang, false);
            if($cached_data != false)
                $this->item_set_type = $cached_data;
            return $this;
        }
		
		public function item_grade_option()
        {
            $cached_data = $this->cacher->get('item_grade_option#' . $this->lang, false);
            if($cached_data != false)
                $this->item_grade_option = $cached_data;
            return $this;
        }

        private function set_language()
        {
            $this->lang = 'en_GB';//htmlspecialchars($_COOKIE['dmn_language']);
        }
    }