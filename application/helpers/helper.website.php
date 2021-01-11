<?php
    in_file();

    class website
    {
        private $registry, $config, $load, $feeds;
        protected $servers = [], $servers_list = [];
        protected $output = '';
        protected $cache_time = 720;
        public $cached = false;
        protected $last_cached = '';
        protected $memcached = false;
        public $top_players = [], $masterlevel = [], $online = [], $gens = [], $guilds = [];

        public function __construct()
        {
            $this->registry = controller::get_instance();
            $this->config = $this->registry->config;
            $this->load = $this->registry->load;
        }

		public function check_server_status_by_port($ip, $port, $gs_list, $name, $cache_time, $db, $cache_name = '')
        {
            $this->check_cache('serv_statuss#' . $cache_name, 'server', $cache_time);
            if(!$this->cached){
                $check = @fsockopen($ip, $port, $errno, $errmsg, 1.5);
                if(!$check){
                    $this->server = [
						'server' => $name, 
						'status' => _('Offline') . ': ', 
						'status_with_style' => '<span class="offline">' . _('Offline') . '</span>', 
						'image' => 'off', 
						'load' => 0, 
						'players' => '0', 
						'title' => $name
					];
                } else{
                    @fclose($check);
                    $server_load = $this->db($db)->cached_query('online_count_s_' . $cache_name, 'SELECT COUNT(memb___id) as count FROM MEMB_STAT WHERE ConnectStat = 1 ' . $this->server_code($gs_list) . '', [], $cache_time);
                    $percentage = floor(100 * $server_load[0]['count'] / (int)$servers['max_players']);
                    $this->server = [
						'server' => $name, 
						'status' => _('Online') . ': ', 
						'status_with_style' => '<span class="online">' . _('Online') . '</span>', 
						'image' => 'on', 
						'load' => $percentage, 
						'players' => $server_load[0]['count'], 
						'title' => $name
					];
                }
                $this->set_cache('serv_statuss#' . $cache_name, $this->server, $cache_time);
            }
            return $this->server;
        }
        public function check_server_status($cache_time = 120)
        {
            $serverlist = $this->server_list();
			$this->check_cache('servers_status', 'servers', $cache_time);
			
            if(!$this->cached){
				foreach($serverlist as $key => $servers){
					if($servers['visible'] == 1){
						$check = @fsockopen($servers['gs_ip'], $servers['gs_port'], $errno, $errmsg, 1.5);
						if(!$check){
							$this->servers[] = [
								'server' => $key, 
								'status' => __('Offline') . ': ', 
								'status_with_style' => '<span class="offline">' . __('Offline') . '</span>', 
								'image' => 'off', 
								'load' => 0, 
								'players' => '0', 
								'title' => $servers['title'], 
								'version' => $servers['version'], 
								'exp' => $servers['exp'], 
								'drop' => $servers['drop'], 
								'visible' => $servers['visible']
							];
						} else{
							@fclose($check);
							$server_load = $this->db($servers['db_acc'])->cached_query('online_count_' . $key, 'SELECT COUNT(memb___id) as count FROM MEMB_STAT WHERE ConnectStat = 1 ' . $this->server_code($servers['gs_list']) . '', [], $cache_time);
							$percentage = floor(100 * $server_load[0]['count'] / (int)$servers['max_players']);
							$this->servers[] = [
								'server' => $key, 
								'status' => __('Online') . ': ', 
								'status_with_style' => '<span class="online">' . __('Online') . '</span>', 
								'image' => 'on', 
								'load' => $percentage, 
								'players' => $server_load[0]['count'], 
								'title' => $servers['title'], 
								'version' => $servers['version'], 
								'exp' => $servers['exp'], 
								'drop' => $servers['drop'], 
								'visible' => $servers['visible']
							];
						}
					}
				}
				$this->set_cache('servers_status', $this->servers, $cache_time);
            }
            return $this->servers;
        }

        public function total_online($cached_query = 60)
        {
            $serverlist = $this->server_list();
            $max_online = 0;
            $online = 0;
            if($this->is_multiple_accounts()){
                foreach($serverlist as $servers){
                    $max_online += $servers['max_players'];
                    $server_load = $this->db($servers['db_acc'])->cached_query('total_online_' . $servers['db_acc'], 'SELECT COUNT(memb___id) as count FROM MEMB_STAT WHERE ConnectStat = 1', [], $cached_query);
                    $online += $server_load[0]['count'];
                }
            } else{
                foreach($serverlist as $servers){
                    $max_online += $servers['max_players'];
                }
                $server_load = $this->db($this->get_default_account_database())->cached_query('total_online_' . $servers['db_acc'], 'SELECT COUNT(memb___id) as count FROM MEMB_STAT WHERE ConnectStat = 1', [], $cached_query);
                $online += $server_load[0]['count'];
            }
            return ['online' => $online, 'percentage' => floor(100 * $online / $max_online)];
        }

        public function online_by_server($server, $cached_query = 60)
        {
            $db = $this->get_db_from_serverlist($server);
            if($db != ''){
                $online = $this->db($db)->cached_query('online_count_by_server_' . $server, 'SELECT COUNT(memb___id) as count FROM MEMB_STAT WHERE ConnectStat = 1 ' . $this->server_code($this->get_servercode($server)) . '', [], $cached_query);
                return (int)$online[0]['count'];
            }
            return 0;
        }

        public function active_by_server($server, $cached_query = 60)
        {
            $db = $this->get_db_from_serverlist($server);
            if($db != ''){
                $online = $this->db($db)->cached_query('active_count_by_server_' . $server, 'SELECT DISTINCT(COUNT(ip)) AS count FROM MEMB_STAT WHERE ConnectTM >= DATEADD(day, -1,CONVERT(datetime, CONVERT(varchar(10), GETDATE(), 101))) ' . $this->server_code($this->get_servercode($server)) . '', [], $cached_query);
                return (int)$online[0]['count'];
            }
            return 0;
        }

        public function status_by_server($sv)
        {
            $serverlist = $this->server_list($sv);
            if(is_array($serverlist)){
                $check = @fsockopen($serverlist['gs_ip'], $serverlist['gs_port'], $errno, $errmsg, 0.5);
                if(!$check)
                    return '<span class="offline">' . __('Offline') . '</span>'; else
                    return '<span class="online">' . __('Online') . '</span>';
            }
        }

        public function server_code($sv, $and = true)
        {
            if(strpos($sv, ',') !== false){
                $server_array = explode(',', $sv);
                $length = count($server_array);
                $serv = '';
                foreach($server_array AS $key => $s){
                    if($key == 0){
                        $serv .= ($and == true) ? 'AND (ServerName = \'' . $this->db('web')->sanitize_var($s) . '\'' : '(ServerName = \'' . $this->db('web')->sanitize_var($s) . '\'';
                    } else if($key == $length - 1){
                        $serv .= ' OR ServerName = \'' . $this->db('web')->sanitize_var($s) . '\')';
                    } else{
                        $serv .= ' OR ServerName = \'' . $this->db('web')->sanitize_var($s) . '\'';
                    }
                }
            } else{
                $serv = ($and == true) ? 'AND ServerName = \'' . $this->db('web')->sanitize_var($sv) . '\'' : 'ServerName = \'' . $this->db('web')->sanitize_var($sv) . '\'';
            }
            return $serv;
        }

        public function get_first_server_code($sv)
        {
            $serverlist = $this->server_list($sv);
            if(is_array($serverlist)){
                if(strpos($serverlist['gs_list'], ',') !== false){
                    $server_array = explode(',', $serverlist['gs_list']);
                    return $server_array[0];
                } else{
                    return $serverlist['gs_list'];
                }
            }
        }

        public function get_servercode($sv)
        {
            $serverlist = $this->server_list($sv);
            if(is_array($serverlist)){
                return $serverlist['gs_list'];
            }
        }

        public function get_db_from_serverlist($server = '', $acc_db = true)
        {
            $serverlist = $this->server_list($server);
            return ($acc_db == true) ? $serverlist['db_acc'] : $serverlist['db'];
        }

        public function get_default_account_database()
        {
            $serverlist = $this->server_list();
            $first = reset($serverlist);
            return $first['db_acc'];
        }
		
		public function get_char_id_col($server = '')
        {
            $serverlist = $this->server_list($server);
            return $serverlist['identity_column_character'];
        }

        public function is_multiple_accounts()
        {
            return (bool)$this->server_list('', true);
        }

        public function count_resets()
        {
            return $this->db($this->get_db_from_server($this->registry->session->userdata(['user' => 'server'])))->snumrows('SELECT SUM(' . $this->config->values('table_config', [$this->registry->session->userdata(['user' => 'server']), 'resets', 'column']) . ') AS count FROM Character WHERE AccountId = \'' . $this->registry->session->userdata(['user' => 'username']) . '\'');
        }
		
		public function check_chars(){
			return $this->db($this->get_db_from_server($this->registry->session->userdata(['user' => 'server'])))->query('SELECT Name FROM Character WHERE AccountId = \''.$this->registry->session->userdata(['user' => 'server']).'\'')->fetch();
		}
		
        public function stats($server = '', $cached_query = 60)
        {
            if(!$server)
                $server = array_keys($this->server_list($server))[0];
            $db1 = $this->db($this->get_db_from_server($server));
            $db2 = $this->db($this->get_db_from_serverlist($server));
            $queries = ['chars' => ['query' => 'SELECT COUNT(*) AS count FROM Character', 'db' => $db1], 'accounts' => ['query' => 'SELECT COUNT(*) AS count FROM MEMB_INFO', 'db' => $this->db('account', $server)], 'guilds' => ['query' => 'SELECT COUNT(*) AS count FROM Guild', 'db' => $db1], 'active' => ['query' => 'SELECT DISTINCT(COUNT(ip)) AS count FROM MEMB_STAT WHERE ConnectTM >= DATEADD(day, -1,CONVERT(datetime, CONVERT(varchar(10), GETDATE(), 101))) ' . $this->server_code($this->get_servercode($server)) . '', 'db' => $db2]];
            $result = [];
            foreach($queries as $key => $query){
                $qresult = $queries[$key]['db']->cached_query($key . $server, $queries[$key]['query'], [], $cached_query);
                $result[$key] = $qresult[0]['count'];
            }
            return $result;
        }

        public function get_cs_info($server = false)
        {
            if(!$server)
                $server = array_keys($this->server_list())[0];
            $this->load->model('stats');
            return $this->registry->Mstats->get_cs_info($server);
        }
				
				public function get_gens_info($server = false)
        {
            if(!$server)
                $server = array_keys($this->server_list())[0];
            $gensd = $this->db('game', $server)->query('SELECT COALESCE(SUM(points),1) AS score FROM IGC_Gens WHERE Influence = 1')->fetch();
						$gensv = $this->db('game', $server)->query('SELECT COALESCE(SUM(points),1) AS score FROM IGC_Gens WHERE Influence = 2')->fetch();
            return [$gensd['score'], $gensv['score']];
        }

        public function get_cs_guild_list($server = false)
        {
            if(!$server)
                $server = array_keys($this->server_list())[0];
            $this->load->model('stats');
            return $this->registry->Mstats->get_cs_guild_list($server);
        }

        public function module_disabled($config)
        {
            if($this->config->config_entry($config . '|module_status') == 1){
                return false;
            } else{
                if(is_ajax()){
                    json(['title' => __('Module Disabled'), 'callback' => false, 'template' => 'view_module_disabled.ejs']);
                } else{
                    $this->load->view($this->config->config_entry('main|template') . DS . 'view.header');
                    $this->load->view($this->config->config_entry('main|template') . DS . 'view.module_disabled');
                    return true;
                }
            }
        }

        public function server_select_box($id = '', $class = '', $show_label = true)
        {
            $this->output = '';
            if(count($this->server_list()) > 1){
                if(defined('DREAMMU') && DREAMMU == true)
                    $this->output = '<select style="padding:5px;width: 153px;height: 26px;line-height: 26px;font-size: 14px;color: #8c533c;border: 0px;background: url(' . $this->config->base_url . 'assets/' . $this->config->config_entry('main|template') . '/images/field_bg.gif) no-repeat;" name="server" ' . $id . '><option value="">Click To Select</option>' . "\n"; else{
                    if($show_label)
                        $this->output .= '<span style="color: gray;">' . __('Select Server:') . '</span>';
                    $this->output .= '<select name="server" ' . $id . ' ' . $class . '><option value="">' . __('Click To Select') . '</option>' . "\n";
                }
                foreach($this->server_list() as $key => $value){
                    if($value['visible'] == 1){
                        $this->output .= '<option value="' . $key . '">' . $value['title'] . "</option>\n";
                    }
                }
                $this->output .= '</select>';
                return $this->output;
            }
            return false;
        }

        public function server_list($sv = '', $check_multi_db_acc = false)
        {
            use_funcs("DmN", "cms", "DmN");
            return server_list($sv, $check_multi_db_acc);
        }

        public function ip()
        {
            return ip();
        }

        public function hex2bin($hexstr)
        {
            if(ctype_xdigit($hexstr) && strlen($hexstr) <= 128){
                $n = strlen($hexstr);
                $sbin = "";
                $i = 0;
                while($i < $n){
                    $a = substr($hexstr, $i, 2);
                    $c = pack("H*", $a);
                    if($i == 0){
                        $sbin = $c;
                    } else{
                        $sbin .= $c;
                    }
                    $i += 2;
                }
                $security = load_class('security');
                $sbin = $security->SanitizeStr($sbin);
                $sbin = $security->Xss($sbin);
                return $sbin;
            } else{
                throw new Exception('Invalid hex string.');
            }
        }

        public function set_limit($value, $limit, $return)
        {
            $simbol = (strlen($value) <= $limit ? "" : "$return");
            if(extension_loaded('mbstring')){
                mb_internal_encoding("UTF-8");
                return mb_substr($value, 0, $limit) . $simbol;
            } else{
                return substr($value, 0, $limit) . $simbol;
            }
        }

        public function strstr_alt($haystack, $needle, $before_needle = false)
        {
            if(!$before_needle)
                return strstr($haystack, $needle); else
                return substr($haystack, 0, strpos($haystack, $needle));
        }

        public function get_db_from_server($server, $acc_db = false)
        {
			if(!empty($server)){
				$servers = $this->server_list($server);
				return ($acc_db == true) ? $servers['db_acc'] : $servers['db'];
			}
			return false;
        }

        public function get_title_from_server($server)
        {
            $servers = $this->server_list($server);
            return isset($servers['title']) ? $servers['title'] : '';
        }

        public function get_value_from_server($server, $val = 'db')
        {
            $servers = $this->server_list($server);
            return $servers[$val];
        }

        public function check_cache($file, $return, $time = false, $delete_old_cache = true)
        {
            if($this->config->config_entry('main|cache_type') == 'file'){
                $this->load->lib('cache', ['File', ['cache_dir' => APP_PATH . DS . 'data' . DS . 'cache']]);
            } else{
                $this->load->lib('cache', ['MemCached', ['ip' => $this->config->config_entry('main|mem_cached_ip'), 'port' => $this->config->config_entry('main|mem_cached_port')]]);
            }
            $this->cached = true;
            $this->$return = $this->registry->cache->get($file, $delete_old_cache);
            $this->last_cached = $this->registry->cache->last_cached($file);
            if($this->$return == false){
                $this->cached = false;
            }
        }

        public function set_cache($file, $content, $time = false)
        {
            if($this->config->config_entry('main|cache_type') == 'file'){
                $this->load->lib('cache', ['File', ['cache_dir' => APP_PATH . DS . 'data' . DS . 'cache']]);
            } else{
                $this->load->lib('cache', ['MemCached', ['ip' => $this->config->config_entry('main|mem_cached_ip'), 'port' => $this->config->config_entry('main|mem_cached_port')]]);
            }
            $this->registry->cache->set($file, $content, $time);
        }

        public function get_cache_time()
        {
            return ($this->last_cached != '') ? sprintf(__('Next Cache Time %s'), date('d/m/Y H:i', ($this->last_cached))) : __('Cached Moment Ago');
        }

        public function check_if_cached()
        {
            return $this->cached;
        }

        public function translate_credits($credits, $server = 'DEFAULT')
        {
            switch($credits){
                case 1:
                    return $this->config->config_entry('credits_' . $server . '|title_1');
                    break;
                case 2:
                    return $this->config->config_entry('credits_' . $server . '|title_2');
                    break;
                case 3:
                    return $this->config->config_entry('credits_' . $server . '|title_3');
                    break;
            }
        }

        public function get_user_credits_balance($user, $server, $type = 1, $guid = false)
        {
            $db = $this->config->config_entry('credits_' . $server . '|db_' . $type);
            $table = $this->config->config_entry('credits_' . $server . '|table_' . $type);
            $column = $this->config->config_entry('credits_' . $server . '|credits_column_' . $type);
            $identifier_column = $this->config->config_entry('credits_' . $server . '|account_column_' . $type);
            $data = [':user' => $user, ':server' => $server];
            if(strtolower($table) == 'dmn_shop_credits'){
                $stmt = $this->db('web')->prepare('SELECT ' . $column . ' AS credits FROM ' . $table . ' WHERE ' . $identifier_column . ' = :user AND server = :server');
                $stmt->execute($data);
                if(!$info = $stmt->fetch()){
                    $stmt = $this->db('web')->prepare('INSERT INTO ' . $table . ' (' . $identifier_column . ', server) VALUES (:user, :server)');
                    $stmt->execute($data);
                    return ['credits' => 0];
                }
                return $info;
            } else{
                unset($data[':server']);
                if($guid != false){
                    $data[':user'] = (in_array($identifier_column, ['MemberGuid', 'memb_guid'])) ? $guid : $user;
                }
                $stmt = $this->db($db, $server)->prepare('SELECT ' . $column . ' AS credits FROM ' . $table . ' WHERE ' . $identifier_column . ' = :user');
                $stmt->execute($data);
                if(!$info = $stmt->fetch()){
                    $stmt = $this->db($db, $server)->prepare('INSERT INTO ' . $table . ' (' . $identifier_column . ') VALUES (:user)');
                    $stmt->execute($data);
                    return ['credits' => 0];
                }
                return $info;
            }
        }
        public function add_credits($user, $server, $credits, $type = 1, $decrease = false, $guid = false)
        {
            $db = $this->config->config_entry('credits_' . $server . '|db_' . $type);
            $table = $this->config->config_entry('credits_' . $server . '|table_' . $type);
            $column = $this->config->config_entry('credits_' . $server . '|credits_column_' . $type);
            $identifier_column = $this->config->config_entry('credits_' . $server . '|account_column_' . $type);
            if(!$decrease){
                $this->increase_credits($db, $table, $column, $identifier_column, $user, $guid, $server, $credits);
            } else{
                $this->decrease_credits($db, $table, $column, $identifier_column, $user, $guid, $server, $credits);
            }
        }

        public function charge_credits($account, $server, $credits, $decrease_type = 1, $guid = false)
        {
            $this->add_credits($account, $server, $credits, $decrease_type, true, $guid);
        }

        private function increase_credits($db, $table, $column, $identifier_column, $user, $guid, $server, $credits)
        {
            $data = [':credits' => $credits, ':user' => $user, ':server' => $server];
            if(strtolower($table) == 'dmn_shop_credits'){
                $stmt = $this->db('web')->prepare('UPDATE ' . $table . ' SET ' . $column . ' = ' . $column . ' + :credits WHERE ' . $identifier_column . ' = :user AND server = :server');
                $stmt->execute($data);
                if($stmt->rows_affected() == 0){
                    $stmt = $this->db('web')->prepare('INSERT INTO ' . $table . ' (' . $identifier_column . ', server) VALUES (:user, :server)');
                    $stmt->execute([':user' => $user, ':server' => $server]);
                    $stmt2 = $this->db('web')->prepare('UPDATE ' . $table . ' SET ' . $column . ' = ' . $column . ' + :credits WHERE ' . $identifier_column . ' = :user AND server = :server');
                    $stmt2->execute($data);
                }
            } else{
                unset($data[':server']);
                if($guid != false){
                    $data[':user'] = (in_array($identifier_column, ['MemberGuid', 'memb_guid'])) ? $guid : $user;
                }
                $stmt = $this->db($db, $server)->prepare('UPDATE ' . $table . ' SET ' . $column . ' = ' . $column . ' + :credits WHERE ' . $identifier_column . ' = :user');
                $stmt->execute($data);
                if($stmt->rows_affected() == 0){
                    $stmt = $this->db($db, $server)->prepare('INSERT INTO ' . $table . ' (' . $column . ', ' . $identifier_column . ') VALUES (:credits, :user)');
                    $stmt->execute($data);
                }
            }
        }

        private function decrease_credits($db, $table, $column, $identifier_column, $user, $guid, $server, $credits)
        {
            $data = [':credits' => $credits, ':user' => $user, ':server' => $server];
            if(strtolower($table) == 'dmn_shop_credits'){
                $stmt = $this->db('web')->prepare('UPDATE ' . $table . ' SET ' . $column . ' = CASE WHEN (' . $column . ' <= 0) THEN 0 WHEN (' . $column . ' - ' . $credits . ' <= 0) THEN 0 ELSE (' . $column . ' - :credits) END WHERE ' . $identifier_column . ' = :user AND server = :server');
                $stmt->execute($data);
            } else{
                unset($data[':server']);
                if($guid != false){
                    $data[':user'] = (in_array($identifier_column, ['MemberGuid', 'memb_guid'])) ? $guid : $user;
                }
                $stmt = $this->db($db, $server)->prepare('UPDATE ' . $table . ' SET ' . $column . ' = CASE WHEN (' . $column . ' <= 0) THEN 0 WHEN (' . $column . ' - ' . $credits . ' <= 0) THEN 0 ELSE (' . $column . ' - :credits) END WHERE ' . $identifier_column . ' = :user');
                $stmt->execute($data);
            }
        }

        public function get_account_wcoins_balance($server = '')
        {
            $this->vars['table_config'] = $this->config->values('table_config', $server);
            if(isset($this->vars['table_config']['wcoins'])){
                $this->load->model('character');
                return $this->registry->Mcharacter->get_wcoins($this->vars['table_config']['wcoins'], $server);
            }
            return 0;
        }

        public function db($db, $server = '')
        {
            switch($db){
                case 'web':
                    if(isset($this->registry->web_db))
                        return $this->registry->web_db; else{
                        $this->load->lib(['web_db', 'db'], [HOST, USER, PASS, WEB_DB], DRIVER);
                        return $this->registry->web_db;
                    }
                    break;
                case 'account':
                    if(isset($this->registry->account_db))
                        return $this->registry->account_db; else{
                        if($this->is_multiple_accounts() == true){
                            $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->get_db_from_server($server, true)], DRIVER);
                        } else{
                            $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->get_default_account_database()], DRIVER);
                        }
                        return $this->registry->account_db;
                    }
                    break;
                case 'game':
                    //if(isset($this->registry->game_db))
                    //    return $this->registry->game_db; 
					//else{
                        $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->get_db_from_server($server)], DRIVER);
                        return $this->registry->game_db;
                   // }
                    break;
                default:
                    if(isset($this->registry->$db))
                        return $this->registry->$db; 
					else{
                        $this->load->lib([$db, 'db'], [HOST, USER, PASS, $db], DRIVER);
                        return $this->registry->$db;
                    }
                    break;
            }
        }

        
        public function load_rss($url = '', $item_count = 5, $cache_time = 0, $rss_name = 'recent_on_forum')
        {
            if($url == ''){
                return false;
            } else{
                $this->check_cache($rss_name, 'feeds', $cache_time);
                if(!$this->cached){
                    if($rawFeed = $this->load_data_from_url($url)){
                        try{
                            $xml = @new SimpleXmlElement($rawFeed);
                        } catch(Exception $e){
                            $xml = false;
                        }
                        //pre($xml);die();
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
                            $this->feeds = $this->get_feed($dddata, $item_count);
                            $this->set_cache($rss_name, $this->feeds, $cache_time);
                            return $this->feeds;
                        }
                        return false;
                    }
                    return false;
                }
                return $this->feeds;
            }
        }

        public function load_data_from_url($url)
        {
            if(extension_loaded('curl')){
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_VERBOSE, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.75 Safari/537.1");
                //curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5000);
                curl_setopt($ch, CURLOPT_URL, $url);
                $response = curl_exec($ch);
                if(curl_errno($ch) != 0){
                    writelog('Can\'t connect to ' . $url . ':' . curl_error($ch), 'system_error');
                    return false;
                }
                curl_close($ch);
            } else{
                $opts = ['http' => ['header' => "User-Agent:Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.75 Safari/537.1\r\n", 'timeout' => 10]];
                $context = stream_context_create($opts);
                $response = file_get_contents($url, false, $context);
            }
            header('Content-Type: text/html; charset=utf-8');
            return $response;
        }

        private function multi_xml_rss($links = [])
        {
            $docList = new DOMDocument();
            $root = $docList->createElement('channel');
            $docList->appendChild($root);
            foreach($links as $filename){
                $doc = new DOMDocument();
                $doc->load($filename);
                $xpath = new DOMXPath($doc);
                $query = "//item";
                $nodelist = $xpath->evaluate($query, $doc->documentElement);
                if($nodelist->length > 0){
                    $node = $docList->importNode($nodelist->item(0), true);
                    $root->appendChild($node);
                }
            }
            return $docList->saveXML();
        }

        public function get_feed($data, $num)
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

        public function load_wallpapers_shoots($count = 10)
        {
            $gallery = $this->db('web')->query('SELECT Top ' . (int)$count . ' id, name FROM DmN_Gallery  WHERE section = 1 ORDER BY NEWID()')->fetch_all();
            return ($gallery) ? $gallery : false;
        }

        public function load_screen_shoots($count = 10)
        {
            $gallery = $this->db('web')->query('SELECT Top ' . (int)$count . ' id, name FROM DmN_Gallery  WHERE section = 2 ORDER BY NEWID()')->fetch_all();
            return ($gallery) ? $gallery : false;
        }

        public function load_random_galery($count = 10)
        {
            $gallery = $this->db('web')->query('SELECT Top ' . (int)$count . ' id, name FROM DmN_Gallery  WHERE section IN(1,2) ORDER BY NEWID()')->fetch_all();
            return ($gallery) ? $gallery : false;
        }

        public function load_sidebar_cs_info($server = '')
        {
            if($server == ''){
                $server = array_keys($this->server_list())[0];
            }
            $this->load->model('stats');
            return $this->registry->Mstats->get_cs_info($server);
        }

        public function zen_format($zen)
        {
            $zens = $zen;
            for($i = 0; $zen >= 1000; $i++){
                $zen = $zen / 1000;
            }
            return ($zens < 1000) ? (float)number_format($zen, 1, '.', '') : (float)number_format($zen, 1, '.', '') . " " . str_repeat("K", $i);
        }

        public function get_char_class($class, $short = false, $list = false)
        {
            $class_array = $this->config->values('class_config');
            if($list == true){
                return $class_array['class_codes'];
            } else{
                if(array_key_exists($class, $class_array['class_codes'])){
                    if($short == true)
                        return $class_array['class_codes'][$class]['short']; else
                        return $class_array['class_codes'][$class]['long'];
                } else
                    return __('Unknown Class').': '.$class;
            }
        }

        public function get_guild_status($status)
        {
            $status_array = [0 => __('Member'), 32 => '<span style="color: green;">' . __('BattleMaster') . '</span>', 64 => '<span style="color: blue;">' . __('Assistant Guild Master') . '</span>', 128 => '<span style="color: red;font-weight: bold;">' . __('Guild Master') . '</span>'];
            return str_replace(array_keys($status_array), array_values($status_array), $status);
        }

        public function get_gens_family($influence)
        {
            $family_array = [1 => __('Duprian'), 2 => __('Vanert')];
            return str_replace(array_keys($family_array), array_values($family_array), $influence);
        }

        public function get_map_name($map_id, $list = false)
        {
            $maps_array = $this->config->values('map_config');
            if($list){
                return $maps_array['map_codes'];
            } else{
                return array_key_exists($map_id, $maps_array['map_codes']) ? $maps_array['map_codes'][$map_id] : __('Unknown');
            }
        }

        public function pk_level($pklevel, $list = false)
        {
            $level = [0 => __('*Hero*'), 1 => __('Hero lvl 2'), 2 => __('Hero lvl 1'), 3 => __('Commoner'), 4 => __('PK lvl 1'), 5 => __('PK lvl 1'), 6 => __('Murder'), 7 => __('*Phonoman*')];
            if($list){
                return $level;
            } else{
                return array_key_exists($pklevel, $level) ? $level[$pklevel] : __('Unknown');
            }
        }

        public function show65kStats($stat_value)
        {
            return ($stat_value < 0) ? $stat_value += 65536 : $stat_value;
        }

        public function fb_login($type = '', $style = '')
        {
            $this->load->lib('fb');
            $this->registry->fb->get_fb_login_url($type, $style);
            return $this->registry->fb->redirect_url;
        }

        public function c($input)
        {
            return (!preg_match('/^\-?\d+(\.\d+)?$/D', $input) || preg_match('/^0\d+$/D', $input)) ? trim(preg_replace('/[^a-zA-Z0-9_@#$&amp;%[]()-,!<\/]/i', '', $input)) : $input;
        }

        public function get_country_code($ip)
        {
            return get_country_code($ip);
        }

        public function seconds2days($seconds, $text = true)
        {
            $days = intval(intval($seconds) / (3600 * 24));
            if($days == 1)
                return ($text) ? $days . ' ' . __('Day') : $days; else
                return ($text) ? $days . ' ' . __('Days') : $days;
        }

        private function no_more_event($times, $now)
        {
            $times = explode(',', $times);
            $lastevent = strtotime('Today ' . end($times));
            if($lastevent < $now){
                return false;
            } else{
                return true;
            }
        }

        private function find_next_day($event)
        {
            $today = date('N');
            $f = false;
            for($i = $today; $i <= 7; $i++){
                if(isset($event['days'][$i]) && $i != $today){
                    $f = true;
                    $day = $i;
                    break;
                }
            }
            if($f === false){
                reset($event['days']);
                $day = key($event['days']);
            }
            return $day;
        }

        public function load_event_timers()
        {
            $events = $this->config->values('event_config', ['events', 'event_timers']);
            $days = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday',];
            $ii = 0;
            $iii = 1;
            $timers = [];
            foreach($events as $event){
                $name = $event['name'];
                if(is_array($event['days'])){
                    $today = date('N');
                    if(isset($event['days'][$today]) === true){
                        if($this->no_more_event($event['days'][$today], time()) === true){
                            $day = "Today ";
                            $times = array_unique(explode(',', $event['days'][$today]));
                            asort($times);
                        } else{
                            $nxt = $this->find_next_day($event);
                            $times = array_unique(explode(',', $event['days'][$nxt]));
                            asort($times);
                            $day = 'Next ' . $days[$nxt];
                        }
                    } else{
                        $nxt = $this->find_next_day($event);
                        $times = array_unique(explode(',', $event['days'][$nxt]));
                        asort($times);
                        $day = 'Next ' . $days[$nxt];
                    }
                } else{
                    $times = array_unique(explode(',', $event['days']));
                    asort($times);
                    if($this->no_more_event($event['days'], time()) === false){
                        $day = "Tomorrow ";
                    } else{
                        $day = "Today ";
                    }
                }
                foreach($times as $t){
                    $nxttime = strtotime($day . ' ' . $t);
                    if(time() <= $nxttime){
                        $a = $nxttime - time();
                        $timers[$ii] = ['name' => $name, 'left' => $a, 'id' => $iii];
                        $ii++;
                        $iii++;
                        break;
                    }
                }
            }
            return $timers;
        }

        public function seo_string($title)
        {
            return seo_string($title);
        }

        public function lang_list()
        {
            $files = scandir(APP_PATH . DS . 'localization');
            $matches = preg_grep('/(.+)_([A-Z]+)$/', $files);
            $country_list = [];
            foreach($matches AS $key => $value){
                $country_list[$value] = explode('_', $value)[1];
            }
            return $country_list;
        }

        public function codeToCountryName($code, $list = false)
        {
            $code = strtoupper($code);
            $countryList = ['AF' => 'Afghanistan', 'AX' => 'Aland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AM' => 'Armenia', 'AW' => 'Aruba', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas the', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island (Bouvetoya)', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory (Chagos Archipelago)', 'VG' => 'British Virgin Islands', 'BN' => 'Brunei Darussalam', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos (Keeling) Islands', 'CO' => 'Colombia', 'KM' => 'Comoros the', 'CD' => 'Congo', 'CG' => 'Congo the', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'CI' => 'Cote d\'Ivoire', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FO' => 'Faroe Islands', 'FK' => 'Falkland Islands (Malvinas)', 'FJ' => 'Fiji the Fiji Islands', 'FI' => 'Finland', 'FR' => 'France, French Republic', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia the', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island and McDonald Islands', 'VA' => 'Holy See (Vatican City State)', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'Kenya', 'KI' => 'Kiribati', 'KP' => 'Korea', 'KR' => 'Korea', 'KW' => 'Kuwait', 'KG' => 'Kyrgyz Republic', 'LA' => 'Lao', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libyan Arab Jamahiriya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia', 'MD' => 'Moldova', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'AN' => 'Netherlands Antilles', 'NL' => 'Netherlands the', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestinian Territory', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn Islands', 'PL' => 'Poland', 'PT' => 'Portugal, Portuguese Republic', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'RE' => 'Reunion', 'RO' => 'Romania', 'RU' => 'Russian Federation', 'RW' => 'Rwanda', 'BL' => 'Saint Barthelemy', 'SH' => 'Saint Helena', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'MF' => 'Saint Martin', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent and the Grenadines', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SK' => 'Slovakia (Slovak Republic)', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia, Somali Republic', 'ZA' => 'South Africa', 'GS' => 'South Georgia and the South Sandwich Islands', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard & Jan Mayen Islands', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland, Swiss Confederation', 'SY' => 'Syrian Arab Republic', 'TW' => 'Taiwan', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania', 'TH' => 'Thailand', 'TL' => 'Timor-Leste', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States of America', 'UM' => 'United States Minor Outlying Islands', 'VI' => 'United States Virgin Islands', 'UY' => 'Uruguay, Eastern Republic of', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VE' => 'Venezuela', 'VN' => 'Vietnam', 'WF' => 'Wallis and Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe'];
            if($list)
                return $countryList;
            return isset($countryList[$code]) ? $countryList[$code] : false;
        }

        public function iso_to_lang($iso, $full_code)
        {
            if(extension_loaded('intl')){
                return Locale::getDisplayLanguage($full_code, $full_code);
            } else{
                $iso = strtolower($iso);
                $language_codes = ['en' => 'English', 'he' => 'Hebrew', 'aa' => 'Afar', 'ab' => 'Abkhazian', 'af' => 'Afrikaans', 'am' => 'Amharic', 'ar' => 'Arabic', 'as' => 'Assamese', 'ay' => 'Aymara', 'az' => 'Azerbaijani', 'ba' => 'Bashkir', 'be' => 'Byelorussian', 'bg' => 'Bulgarian', 'bh' => 'Bihari', 'bi' => 'Bislama', 'bn' => 'Bengali', 'bo' => 'Tibetan', 'br' => 'Breton', 'ca' => 'Catalan', 'co' => 'Corsican', 'cs' => 'Czech', 'cy' => 'Welsh', 'da' => 'Danish', 'de' => 'German', 'dz' => 'Bhutani', 'el' => 'Greek', 'eo' => 'Esperanto', 'es' => 'Spanish', 'et' => 'Estonian', 'eu' => 'Basque', 'fa' => 'Persian', 'fi' => 'Finnish', 'fj' => 'Fiji', 'fo' => 'Faeroese', 'fr' => 'French', 'fy' => 'Frisian', 'ga' => 'Irish', 'gd' => 'Scots', 'gl' => 'Galician', 'gn' => 'Guarani', 'gu' => 'Gujarati', 'ha' => 'Hausa', 'hi' => 'Hindi', 'hr' => 'Croatian', 'hu' => 'Hungarian', 'hy' => 'Armenian', 'ia' => 'Interlingua', 'ie' => 'Interlingue', 'ik' => 'Inupiak', 'in' => 'Indonesian', 'is' => 'Icelandic', 'it' => 'Italian', 'iw' => 'Hebrew', 'ja' => 'Japanese', 'ji' => 'Yiddish', 'jw' => 'Javanese', 'ka' => 'Georgian', 'kk' => 'Kazakh', 'kl' => 'Greenlandic', 'km' => 'Cambodian', 'kn' => 'Kannada', 'ko' => 'Korean', 'ks' => 'Kashmiri', 'ku' => 'Kurdish', 'ky' => 'Kirghiz', 'la' => 'Latin', 'ln' => 'Lingala', 'lo' => 'Laothian', 'lt' => 'Lithuanian', 'lv' => 'Latvian', 'mg' => 'Malagasy', 'mi' => 'Maori', 'mk' => 'Macedonian', 'ml' => 'Malayalam', 'mn' => 'Mongolian', 'mo' => 'Moldavian', 'mr' => 'Marathi', 'ms' => 'Malay', 'mt' => 'Maltese', 'my' => 'Burmese', 'na' => 'Nauru', 'ne' => 'Nepali', 'nl' => 'Dutch', 'no' => 'Norwegian', 'oc' => 'Occitan', 'om' => '(Afan)/Oromoor/Oriya', 'pa' => 'Punjabi', 'pl' => 'Polish', 'ps' => 'Pashto/Pushto', 'pt' => 'Portuguese', 'qu' => 'Quechua', 'rm' => 'Rhaeto-Romance', 'rn' => 'Kirundi', 'ro' => 'Romanian', 'ru' => 'Russian', 'rw' => 'Kinyarwanda', 'sa' => 'Sanskrit', 'sd' => 'Sindhi', 'sg' => 'Sangro', 'sh' => 'Serbo-Croatian', 'si' => 'Singhalese', 'sk' => 'Slovak', 'sl' => 'Slovenian', 'sm' => 'Samoan', 'sn' => 'Shona', 'so' => 'Somali', 'sq' => 'Albanian', 'sr' => 'Serbian', 'ss' => 'Siswati', 'st' => 'Sesotho', 'su' => 'Sundanese', 'sv' => 'Swedish', 'sw' => 'Swahili', 'ta' => 'Tamil', 'te' => 'Tegulu', 'tg' => 'Tajik', 'th' => 'Thai', 'ti' => 'Tigrinya', 'tk' => 'Turkmen', 'tl' => 'Tagalog', 'tn' => 'Setswana', 'to' => 'Tonga', 'tr' => 'Turkish', 'ts' => 'Tsonga', 'tt' => 'Tatar', 'tw' => 'Twi', 'uk' => 'Ukrainian', 'ur' => 'Urdu', 'uz' => 'Uzbek', 'vi' => 'Vietnamese', 'vo' => 'Volapuk', 'wo' => 'Wolof', 'xh' => 'Xhosa', 'yo' => 'Yoruba', 'zh' => 'Chinese', 'zu' => 'Zulu',];
                return array_key_exists($iso, $language_codes) ? $language_codes[$iso] : $iso;
            }
        }

        public function secret_questions($check = false)
        {
            $questions = [0 => __('What is your mother`s maiden name?'), 1 => __('What was the name of your first school?'), 2 => __('Who is your favorite super hero?'), 3 => __('What is the name of your first pet?'), 4 => __('What was your favorite place to visit as a child?'), 5 => __('Who is your favorite cartoon character?'), 6 => __('What was the first game you played?'), 7 => __('What was the name of your first teacher?'), 8 => __('What was your favorite TV show as a child?'), 9 => __('What city was your mother born in?'),];
            if($check != false){
                return array_key_exists($check, $questions) ? $questions[$check] : false;
            }
            return $questions;
        }

        function timezone_list()
        {
            static $timezones = null;
            if($timezones === null){
                $timezones = [];
                $offsets = [];
                $now = new DateTime();
                foreach(DateTimeZone::listIdentifiers() as $timezone){
                    $now->setTimezone(new DateTimeZone($timezone));
                    $offsets[] = $offset = $now->getOffset();
                    $timezones[$timezone] = '(' . $this->format_GMT_offset($offset) . ') ' . $this->format_timezone_name($timezone);
                }
                array_multisort($offsets, $timezones);
            }
            return $timezones;
        }

        private function format_GMT_offset($offset)
        {
            $hours = intval($offset / 3600);
            $minutes = abs(intval($offset % 3600 / 60));
            return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
        }

        private function format_timezone_name($name)
        {
            $name = str_replace('/', ', ', $name);
            $name = str_replace('_', ' ', $name);
            $name = str_replace('St ', 'St. ', $name);
            return $name;
        }
		
		
		public function date_diff($start_date, $end_date)
		{
			$diff = $end_date - $start_date;
			$seconds = 0;
			$hours = 0;
			$minutes = 0;

			if ($diff % 86400 <= 0)
				$days = $diff / 86400;
			if ($diff % 86400 > 0) {
				$rest = ($diff % 86400);
				$days = ($diff - $rest) / 86400;
				if ($rest % 3600 > 0) {
					$rest1 = ($rest % 3600);
					$hours = ($rest - $rest1) / 3600;
					if ($rest1 % 60 > 0) {
						$rest2 = ($rest1 % 60);
						$minutes = ($rest1 - $rest2) / 60;
						$seconds = $rest2;
					} else
						$minutes = $rest1 / 60;
				} else
					$hours = $rest / 3600;
			}

			$days = ($days > 0) ? (($days == 1) ? $days . ' day, ' : $days . ' days, ') : '';
			$hours = ($hours > 0) ? ($hours == 1 ? $hours . ' hour, ' : $hours . ' hours, ') : '';

			if ($minutes > 0) {
				$minutes = ($minutes == 1) ? $minutes . ' minute' : $minutes . ' minutes';
			} else
				$minutes = false;
			$seconds = $seconds . ' seconds';

			return $days . ' ' . $hours . ' ' . $minutes . ' ' . $seconds;
		}
		
		public function getNewsByType($type = NULL){
			$news_file = APP_PATH . DS . 'data' . DS . 'dmn_news.json';
			$news = [];
            if(file_exists($news_file)){
                $file = file_get_contents($news_file);
                if($file != false){
                    $json = json_decode($file, true);
					
                    if(is_array($json)){
                        krsort($json);
                        $per_page = (1 <= 1) ? 0 : (int)$this->config->config_entry('news|news_per_page') * (1 - 1);
                        foreach($json AS $k => $v){
							if(!isset($v['type'])){
								$json[$k]['type'] = 1;
								$v['type'] = 1;
							}
							
							if($type != NULL){
								if($v['type'] != $type){
									unset($json[$k]);
								}
							}
                            if($v['lang'] != $this->config->language()){
                                unset($json[$k]);
                            }
							
                        }
                        $news_data = array_slice($json, $per_page, (int)$this->config->config_entry('news|news_per_page'), true);
                        foreach($news_data AS $key => $row){
                            $news[] = ['title' => htmlspecialchars($row['title']), 'url' => $this->config->base_url . 'news/' . seo_string($row['title']) . '/' . $key, 'content' => $row['news_content'], 'time' => $row['time'], 'author' => $row['author'], 'icon' => $row['icon'], 'comments' => 0, 'views' => 0];
                        }
                        return $news;
                    }
                }
            }
            return false;
		}
    }