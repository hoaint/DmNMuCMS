<?php
    in_file();

    class rankings extends controller
    {
        protected $vars = [], $errors = [];

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('website');
            $this->load->lib('session', ['DmNCMS']);
			$this->load->lib('csrf');						 
            $this->load->helper('breadcrumbs', [$this->request]);
            $this->load->helper('meta');
            $this->load->model('rankings');
        }

        public function index($server = '')
        {
            if($server == ''){
                $server = array_keys($this->website->server_list());
                $this->vars['server'] = $server[0];
            } else{
                $this->serv = $this->website->server_list();
                if(!array_key_exists($server, $this->serv)){
                    throw new exception('Invalid server selected');
                }
                $this->vars['server'] = $server;
            }
            $this->vars['config'] = $this->config->values('rankings_config', $this->vars['server']);
            if($this->vars['config'] && $this->vars['config']['active'] == 1){
                $this->load->view($this->config->config_entry('main|template') . DS . 'rankings' . DS . 'view.index', $this->vars);
            } else{
                $this->disabled();
            }
        }

        public function online_players($server = '')
        {
            if($server == ''){
                $server = array_keys($this->website->server_list());
                $this->vars['server'] = $server[0];
            } else{
                $this->serv = $this->website->server_list();
                if(!array_key_exists($server, $this->serv)){
                    throw new exception('Invalid server selected');
                }
                $this->vars['server'] = $server;
            }
            $this->vars['config'] = $this->config->values('rankings_config', $this->vars['server']);
            $this->vars['table_config'] = $this->config->values('table_config', $this->vars['server']);
            if(isset($this->vars['config']['online_list']['active']) && $this->vars['config']['online_list']['active'] == 1){
                $this->vars['online'] = $this->Mrankings->load_online_players($this->vars['config']['online_list'], $this->vars['table_config'], $this->vars['server']);
				$this->load->view($this->config->config_entry('main|template') . DS . 'rankings' . DS . 'view.online', $this->vars);
            } else{
                $this->disabled();
            }
        }

        public function gm_list($server = '')
        {
            if($server == ''){
                $server = array_keys($this->website->server_list());
                $this->vars['def_server'] = $server[0];
            } else{
                $this->serv = $this->website->server_list();
                if(!array_key_exists($server, $this->serv)){
                    throw new exception('Invalid server selected');
                }
                $this->vars['def_server'] = $server;
            }
            $this->vars['gm_list'] = $this->Mrankings->load_gm_list($this->vars['def_server']);
            $this->load->view($this->config->config_entry('main|template') . DS . 'rankings' . DS . 'view.gm_list', $this->vars);
        }

        public function ban_list($type = 'chars', $server = '')
        {
            if(!in_array($type, ['chars', 'accounts'])){
                $this->vars['error'] = __('Invalid BanList Selected');
            } else{
                if($server == ''){
                    $server = array_keys($this->website->server_list());
                    $this->vars['def_server'] = $server[0];
                } else{
                    $this->serv = $this->website->server_list();
                    if(!array_key_exists($server, $this->serv)){
                        throw new exception('Invalid server selected');
                    }
                    $this->vars['def_server'] = $server;
                }
                $this->vars['def_type'] = $type;
                $this->vars['ban_list'] = $this->Mrankings->load_ban_list($type, $this->vars['def_server']);
            }
            $this->load->view($this->config->config_entry('main|template') . DS . 'rankings' . DS . 'view.ban_list', $this->vars);
        }

        public function load_ranking_data()
        {
            if(!isset($_POST['type'], $_POST['server'])){
                json(['error' => __('Unable to load ranking data.')]);
            } else{
                if(trim($_POST['type']) == '' || trim($_POST['server']) == ''){
                    json(['error' => __('Unable to load ranking data.')]);
                } else{
                    if((!in_array($_POST['type'], ['players', 'guilds', 'votereward', 'killer', 'online', 'gens', 'bc', 'ds', 'cc', 'cs', 'duels'])))
                        json(['error' => __('Invalid ranking selected.')]); 
					else{
                        if(!array_key_exists($_POST['server'], $this->website->server_list()))
                            json(['error' => __('Invalid server selected.')]); 
						else{
                            $this->load->model('character');
                            if(isset($_POST['class'])){
                                $this->Mrankings->class_filter($_POST['class']);
                            }
                            $this->vars['config'] = $this->config->values('rankings_config', $_POST['server']);
                            $this->vars['top'] = (isset($_POST['top']) && is_numeric($_POST['top'])) ? (int)$_POST['top'] : false;
                            json([$_POST['type'] => $this->Mrankings->get_ranking_data($_POST['type'], $_POST['server'], $this->vars['config'], $this->config->values('table_config', $_POST['server']), $this->vars['top']), 'config' => $this->vars['config'], 'server_selected' => $_POST['server'], 'cache_time' => $this->website->get_cache_time(), 'base_url' => $this->config->base_url, 'tmp_dir' => $this->config->config_entry('main|template')]);
                        }
                    }
                }
            }
        }

        public function top_player()
        {
            if(!isset($_POST['server'])){
                json(['error' => __('Unable to load ranking data.')]);
            } else{
                if(trim($_POST['server']) == ''){
                    json(['error' => __('Unable to load ranking data.')]);
                } else{
                    if(!array_key_exists($_POST['server'], $this->website->server_list()))
                        json(['error' => __('Invalid server.')]); else{
                        $this->load->model('character');
                        header('Content-type: application/json');
                        json($this->Mrankings->get_ranking_data('players', $_POST['server'], $this->config->values('rankings_config', $_POST['server']), $this->config->values('table_config', $_POST['server']), 1));
                    }
                }
            }
        }

        public function top_guild()
        {
            if(!isset($_POST['server'])){
                json(['error' => __('Unable to load ranking data.')]);
            } else{
                if(trim($_POST['server']) == ''){
                    json(['error' => __('Unable to load ranking data.')]);
                } else{
                    if(!array_key_exists($_POST['server'], $this->website->server_list()))
                        json(['error' => __('Invalid server.')]); else{
                        $this->load->model('rankings');
                        header('Content-type: application/json');
                        json($this->Mrankings->get_ranking_data('guilds', $_POST['server'], $this->config->values('rankings_config', $_POST['server']), $this->config->values('table_config', $_POST['server']), 1));
                    }
                }
            }
        }

        public function search($server)
        {
            if($server == ''){
                $this->vars['error'] = __('Invalid server');
            } else{
                if(isset($_POST['name'])){
                    if($_POST['name'] == '')
                        $this->vars['error'] = __('Please enter search string'); else{
                        if(strlen($_POST['name']) < 2)
                            $this->vars['error'] = __('Search string should be atleast 2 characters long'); else{
                            $this->vars['list_players'] = $this->Mrankings->load_found_chars($_POST['name'], $server);
                            $this->vars['list_guilds'] = $this->Mrankings->load_found_guilds($_POST['name'], $server);
                        }
                    }
                }
            }
            $this->load->view($this->config->config_entry('main|template') . DS . 'rankings' . DS . 'view.search', $this->vars);
        }

        public function get_mark($mark = '', $size = 24)
        {
			if($size > 256){
				$size = 24;
			}
            $mark = (strlen($mark) > 64) ? $this->website->hex2bin($mark) : $mark;
            $this->Mrankings->load_mark($mark, $size);
        }

        public function disabled()
        {
            $this->load->view($this->config->config_entry('main|template') . DS . 'view.module_disabled');
        }
    }