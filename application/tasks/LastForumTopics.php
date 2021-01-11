<?php

    class LastForumTopics extends Job
    {
        private $registry, $config, $load, $feeds = [];

        public function __construct()
        {
            $this->registry = controller::get_instance();
            $this->config = $this->registry->config;
            $this->load = $this->registry->load;
            $this->load->helper('website');
        }

        public function execute()
        {
            if($this->config->config_entry('modules|recent_forum_module') == 1 && $this->config->config_entry('modules|recent_forum_rss_url') != ''){
                if($rawFeed = $this->load_data_from_url($this->config->config_entry('modules|recent_forum_rss_url'))){
                    try{
                        $xml = @new SimpleXmlElement($rawFeed);
                    } catch(Exception $e){
                        $xml = false;
                    }
                    if($xml !== false){
                        $data = isset($xml->channel) ? $xml->channel->item : $xml;
                        foreach($data as $item){
                            $data = [];
                            $data['title'] = isset($item->subject) ? (string)$item->subject : (string)$item->title;
                            $data['description'] = isset($item->body) ? (string)$item->body : (string)$item->description;
                            $data['pubDate'] = isset($item->time) ? (string)$item->time : (string)$item->pubDate;
                            $data['timestamp'] = isset($item->time) ? strtotime((string)$item->time) : strtotime((string)$item->pubDate);
                            $data['link'] = (string)$item->link;
                            $dddata[] = $data;
                        }
                        $this->feeds = $this->get_feed($dddata, $this->config->config_entry('modules|recent_forum_rss_count'));
                        $this->registry->website->set_cache('recent_on_forum', $this->feeds, 3600);
									
                    }
					else{
						$this->registry->website->set_cache('recent_on_forum', [], 3600);
					}
                }
            }
            return true;
        }

        private function load_data_from_url($url)
        {
            if(extension_loaded('curl')){
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_VERBOSE, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
                curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000);
                curl_setopt($ch, CURLOPT_URL, $url);
                $response = curl_exec($ch);
                curl_close($ch);
            } else{
                $opts = ['http' => ['header' => "User-Agent:Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.75 Safari/537.1\r\n", 'timeout' => 10]];
                $context = stream_context_create($opts);
                $response = file_get_contents($url, false, $context);
            }
            header('Content-Type: text/html; charset=utf-8');
            return $response;
        }

        private function get_feed($data, $num)
        {
            $c = 0;
            $return = [];
            $this->sort_by_column($data, 'timestamp');
            foreach($data AS $item){
                $return[] = $item;
                $c++;
                if($c == $num)
                    break;
            }
            return $return;
        }

        private function sort_by_column(&$arr, $col, $dir = SORT_DESC)
        {
            $sort_col = [];
            foreach($arr as $key => $row){
                $sort_col[$key] = $row[$col];
            }
            array_multisort($sort_col, $dir, $arr);
        }
    }