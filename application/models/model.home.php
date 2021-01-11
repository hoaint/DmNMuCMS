<?php
    in_file();

    class Mhome extends model
    {
        protected $news = [];
        public $count_news = 0;

        public function __contruct()
        {
            parent::__construct();
        }

        public function load_news($page)
        {
            if($this->config->config_entry('news|storage') == 'ipb'){
                return $this->load_from_ipb($page);
            } else if($this->config->config_entry('news|storage') == 'ipb4'){
                return $this->load_from_ipb4($page);
            } else if($this->config->config_entry('news|storage') == 'rss'){
                return $this->load_from_rss($page);
            } else if($this->config->config_entry('news|storage') == 'facebook'){
                return $this->load_from_fb();
            } else{
                return $this->load_from_db($page);
            }
        }

        protected function load_from_db($page = 1)
        {
            $news_file = APP_PATH . DS . 'data' . DS . 'dmn_news.json';
            if(file_exists($news_file)){
                $file = file_get_contents($news_file);
                if($file != false){
                    $json = json_decode($file, true);
                    if(is_array($json)){
                        krsort($json);
                        $per_page = ($page <= 1) ? 0 : (int)$this->config->config_entry('news|news_per_page') * ((int)$page - 1);
                        foreach($json AS $k => $v){
                            if($v['lang'] != $this->config->language()){
                                unset($json[$k]);
                            }
                        }
                        $this->count_news = count($json);
                        $news_data = array_slice($json, $per_page, (int)$this->config->config_entry('news|news_per_page'), true);
                        foreach($news_data AS $key => $row){
                            $this->news[] = ['title' => htmlspecialchars($row['title']), 'url' => $this->config->base_url . 'news/' . seo_string($row['title']) . '/' . $key, 'content' => $row['news_content'], 'time' => $row['time'], 'author' => $row['author'], 'icon' => $row['icon'], 'comments' => 0, 'views' => 0];
                        }
                        return $this->news;
                    }
                }
            }
            return false;
        }

        protected function load_from_ipb($page = 1)
        {
            $this->website->check_cache('news#' . (int)$page, 'news', $this->config->config_entry('news|cache_time'));
            if(!$this->website->cached){
                if(trim($this->config->config_entry('news|ipb_host')) != ''){
                    $this->load->lib(['mysql', 'db'], [$this->config->config_entry('news|ipb_host'), $this->config->config_entry('news|ipb_user'), $this->config->config_entry('news|ipb_pass'), $this->config->config_entry('news|ipb_db'), 'pdo_mysql'], 'pdo_mysql');
                }
                if(strpos(trim($this->config->config_entry('news|ipb_forum_ids')), ',') !== false){
                    $ids = explode(',', trim($this->config->config_entry('news|ipb_forum_ids')));
                } else{
                    $ids = [0 => $this->config->config_entry('news|ipb_forum_ids')];
                }
                $where = '';
                foreach($ids as $key => $id){
                    if($key == 0){
                        $where .= 't.forum_id = ' . $id;
                    } else{
                        $where .= ' OR t.forum_id = ' . $id;
                    }
                }
                $offset = ($page - 1) * $this->config->config_entry('news|news_per_page');
                $query = $this->mysql->query('SELECT t.tid, t.title, t.posts, t.starter_id, t.start_date, t.starter_name, t.forum_id, t.views, t.title_seo, p.post, pr.pp_thumb_photo FROM ' . $this->config->config_entry('news|ipb_tb_prefix') . 'topics AS t INNER JOIN ' . $this->config->config_entry('news|ipb_tb_prefix') . 'posts AS p ON(t.tid = p.topic_id) INNER JOIN ' . $this->config->config_entry('news|ipb_tb_prefix') . 'profile_portal AS pr ON(t.starter_id = pr.pp_member_id) WHERE t.tdelete_time = 0 AND (' . $where . ') AND p.new_topic = 1 ORDER BY t.start_date DESC LIMIT ' . $this->mysql->sanitize_var($offset) . ', ' . $this->config->config_entry('news|news_per_page') . '');
                while($row = $query->fetch()){
                    $this->news[] = ['title' => html_entity_decode($row['title']), 'url' => $this->config->config_entry('main|forum_url') . '/index.php/topic/' . $row['tid'] . '-' . $row['title_seo'] . '/', 'content' => (defined('DREAMMU') && DREAMMU == true) ? $row['post'] : strip_tags($row['post'], '<a><img><b><p><br><li><ul><ol><span><div><font><strong>'), 'time' => $row['start_date'], 'type' => $this->set_topic_type($row['forum_id']), 'author' => $row['starter_name'], 'author_id' => $row['starter_id'], 'avatar' => $this->config->config_entry('main|forum_url') . '/uploads/' . $row['pp_thumb_photo'], 'comments' => $row['posts'], 'views' => $row['views']];
                }
                $this->website->set_cache('news#' . (int)$page, $this->news, $this->config->config_entry('news|cache_time'));
                return $this->news;
            }
            return isset($this->website->news) ? $this->website->news : false;
        }

        protected function load_from_ipb4($page = 1)
        {
            $this->website->check_cache('news#' . (int)$page, 'news', $this->config->config_entry('news|cache_time'));
            if(!$this->website->cached){
                if(trim($this->config->config_entry('news|ipb_host')) != ''){
                    $this->load->lib(['mysql', 'db'], [$this->config->config_entry('news|ipb_host'), $this->config->config_entry('news|ipb_user'), $this->config->config_entry('news|ipb_pass'), $this->config->config_entry('news|ipb_db'), 'pdo_mysql'], 'pdo_mysql');
                }
                if(strpos(trim($this->config->config_entry('news|ipb_forum_ids')), ',') !== false){
                    $ids = explode(',', trim($this->config->config_entry('news|ipb_forum_ids')));
                } else{
                    $ids = [0 => $this->config->config_entry('news|ipb_forum_ids')];
                }
                $where = '';
                foreach($ids as $key => $id){
                    if($key == 0){
                        $where .= 't.forum_id = ' . $id;
                    } else{
                        $where .= ' OR t.forum_id = ' . $id;
                    }
                }
                $offset = ($page - 1) * $this->config->config_entry('news|news_per_page');
                $query = $this->mysql->query('SELECT t.tid, t.title, t.posts, t.starter_id, t.start_date, t.starter_name, t.forum_id, t.views, t.title_seo, p.post, pr.pp_thumb_photo FROM ' . $this->config->config_entry('news|ipb_tb_prefix') . 'forums_topics AS t INNER JOIN ' . $this->config->config_entry('news|ipb_tb_prefix') . 'forums_posts AS p ON(t.tid = p.topic_id) INNER JOIN ' . $this->config->config_entry('news|ipb_tb_prefix') . 'core_members AS pr ON(t.starter_id = pr.member_id) WHERE (' . $where . ') AND p.new_topic = 1 ORDER BY t.start_date DESC LIMIT ' . $this->mysql->sanitize_var($offset) . ', ' . $this->config->config_entry('news|news_per_page') . '');
                while($row = $query->fetch()){
                    $this->news[] = ['title' => html_entity_decode($row['title']), 'url' => $this->config->config_entry('main|forum_url') . '/index.php/topic/' . $row['tid'] . '-' . $row['title_seo'] . '/', 'content' => (defined('DREAMMU') && DREAMMU == true) ? $row['post'] : strip_tags($row['post'], '<a><img><b><p><br><li><ul><ol><span><div><font><strong>'), 'time' => $row['start_date'], 'type' => $this->set_topic_type($row['forum_id']), 'author' => $row['starter_name'], 'author_id' => $row['starter_id'], 'avatar' => $this->config->config_entry('main|forum_url') . '/uploads/' . $row['pp_thumb_photo'], 'comments' => $row['posts'], 'views' => $row['views']];
                }
                $this->website->set_cache('news#' . (int)$page, $this->news, $this->config->config_entry('news|cache_time'));
                return $this->news;
            }
            return isset($this->website->news) ? $this->website->news : false;
        }

        private function load_from_rss($page = 1)
        {
            $this->website->check_cache('news#' . (int)$page, 'news', $this->config->config_entry('news|cache_time'));
            if(!$this->website->cached){
                if($feed = $this->website->load_data_from_url($this->config->config_entry('news|rss_feed_url'))){
                    $xml = $this->xml2array(new SimpleXmlElement($feed, LIBXML_NOCDATA));
                    if(!empty($xml['channel'][0]['item'])){
                        $per_page = ($page <= 1) ? 0 : (int)$this->config->config_entry('news|news_per_page') * ((int)$page - 1);
                        $this->count_news = count($xml['channel'][0]['item']);
                        $news_data = array_slice($xml['channel'][0]['item'], $per_page, (int)$this->config->config_entry('news|news_per_page'), true);
                        foreach($news_data as $entry){
                            $this->news[] = ['title' => $entry['title'], 'url' => $entry['link'], 'content' => $entry['description'], 'time' => strtotime($entry['pubDate']), 'author' => 'Website', 'icon' => '', 'comments' => 0, 'views' => 0];
                        }
                        $this->news = array_slice($this->news, 0, 5);
                        $this->website->set_cache('news#' . (int)$page, $this->news, $this->config->config_entry('news|cache_time'));
                        return $this->news;
                    } else{
                        return false;
                    }
                } else{
                    return false;
                }
            }
            return isset($this->website->news) ? $this->website->news : false;
        }

        private function xml2array($xml)
        {
            $arr = [];
            foreach($xml->children() as $r){
                $t = [];
                if(count($r->children()) == 0){
                    $arr[$r->getName()] = strval($r);
                } else{
                    $arr[$r->getName()][] = $this->xml2array($r);
                }
            }
            return $arr;
        }

        private function load_from_fb()
        {
            $this->news = ['contents' => $this->config->config_entry('news|fb_script')];
            return $this->news;
        }

        public function count_total_news()
        {
            if($this->config->config_entry('news|storage') == 'ipb'){
                if(trim($this->config->config_entry('news|ipb_host')) != ''){
                    $this->load->lib(['mysql', 'db'], [$this->config->config_entry('news|ipb_host'), $this->config->config_entry('news|ipb_user'), $this->config->config_entry('news|ipb_pass'), $this->config->config_entry('news|ipb_db'), 'pdo_mysql'], 'pdo_mysql');
                }
                if(strpos(trim($this->config->config_entry('news|ipb_forum_ids')), ',') !== false){
                    $ids = explode(',', trim($this->config->config_entry('news|ipb_forum_ids')));
                } else{
                    $ids = [0 => $this->config->config_entry('news|ipb_forum_ids')];
                }
                $where = '';
                foreach($ids as $key => $id){
                    if($key == 0){
                        $where .= 't.forum_id = ' . $id;
                    } else{
                        $where .= ' OR t.forum_id = ' . $id;
                    }
                }
                return $this->mysql->snumrows('SELECT COUNT(t.tid) as count FROM ' . $this->config->config_entry('news|ipb_tb_prefix') . 'topics AS t INNER JOIN ' . $this->config->config_entry('news|ipb_tb_prefix') . 'posts AS p ON(t.tid = p.topic_id) INNER JOIN ' . $this->config->config_entry('news|ipb_tb_prefix') . 'profile_portal AS pr ON(t.starter_id = pr.pp_member_id) WHERE t.tdelete_time = 0 AND (' . $where . ') AND p.new_topic = 1');
            } else
                return $this->count_news;
        }

        public function load_news_by_id($id)
        {
            $file = file_get_contents(APP_PATH . DS . 'data' . DS . 'dmn_news.json');
            $json = json_decode($file, true);
            if(is_array($json)){
                if(array_key_exists($id, $json)){
                    return $json[$id];
                }
            }
            return false;
        }

        public function update_views($id)
        {
            return true;
        }

        protected function set_topic_type($type)
        {
            switch($type){
                case 3:
                    return 'news';
                    break;
                case 4:
                    return 'update';
                    break;
                case 5:
                    return 'event';
                    break;
                default:
                    return 'news';
                    break;
            }
        }
    }