<?php
    in_file();

    class Mstats extends model
    {
        public $error = false, $vars = [];
        private $cs_info;

        public function __contruct()
        {
            parent::__construct();
        }

        public function server_stats($server, $cached_query = 60)
        {
            $queries = ['chars' => ['query' => 'SELECT COUNT(*) AS count FROM Character', 'db' => $this->website->db('game', $server)], 'accounts' => ['query' => 'SELECT COUNT(*) AS count FROM MEMB_INFO', 'db' => $this->website->db('account', $server)], 'guilds' => ['query' => 'SELECT COUNT(*) AS count FROM Guild', 'db' => $this->website->db('game', $server)], 'gms' => ['query' => 'SELECT COUNT(*) AS count FROM Character WHERE CtlCode = 32', 'db' => $this->website->db('game', $server)], 'online' => ['query' => 'SELECT COUNT(*) AS count FROM MEMB_STAT WHERE ConnectStat = 1 ' . $this->website->server_code($this->website->get_servercode($server)) . '', 'db' => $this->website->db('online_db', $server)], 'active' => ['query' => 'SELECT DISTINCT(COUNT(ip)) AS count FROM MEMB_STAT WHERE ConnectTM >= \'' . date('Ymd H:i:s', strtotime('-1 days', mktime(0, 0, 0))) . '\' ' . $this->website->server_code($this->website->get_servercode($server)) . '', 'db' => $this->website->db('online_db', $server)], 'market_items' => ['query' => 'SELECT COUNT(id) AS count FROM DmN_Market WHERE server = \'' . $this->website->db('web')->sanitize_var($server) . '\' AND sold != 1 AND removed != 1', 'db' => $this->website->db('web')], 'market_active' => ['query' => 'SELECT COUNT(id) AS count FROM DmN_Market WHERE active_till > GETDATE() AND active = 1 AND server = \'' . $this->website->db('web')->sanitize_var($server) . '\' AND removed != 1', 'db' => $this->website->db('web')], 'market_expired' => ['query' => 'SELECT COUNT(id) AS count FROM DmN_Market WHERE active_till <= GETDATE() AND active = 1 AND server = \'' . $this->website->db('web')->sanitize_var($server) . '\' AND removed != 1', 'db' => $this->website->db('web')], 'total_sold' => ['query' => 'SELECT COUNT(id) AS count FROM DmN_Market WHERE sold = 1 AND server = \'' . $this->website->db('web')->sanitize_var($server) . '\'', 'db' => $this->website->db('web')], 'sales_credits' => ['query' => 'SELECT SUM(price) AS count FROM DmN_Market WHERE sold = 1 AND server = \'' . $this->website->db('web')->sanitize_var($server) . '\' AND price_type = 1', 'db' => $this->website->db('web')], 'sales_gcredits' => ['query' => 'SELECT SUM(price) AS count FROM DmN_Market WHERE sold = 1 AND server = \'' . $this->website->db('web')->sanitize_var($server) . '\' AND price_type = 2', 'db' => $this->website->db('web')], 'sales_zen' => ['query' => 'SELECT SUM(price) AS count FROM DmN_Market WHERE sold = 1 AND server = \'' . $this->website->db('web')->sanitize_var($server) . '\' AND price_type = 3', 'db' => $this->website->db('web')]];
            $result = [];
            foreach($queries as $key => $query){
                $qresult = $queries[$key]['db']->cached_query($key . '_' . $server, $queries[$key]['query'], [], $cached_query);
                $result[$key] = (int)$qresult[0]['count'];
            }
            $result['version'] = $this->website->get_value_from_server($server, 'version');
            $result['exp'] = $this->website->get_value_from_server($server, 'exp');
            $result['drop'] = $this->website->get_value_from_server($server, 'drop');
            return $result;
        }

        public function get_crywolf_state($server)
        {
            if($this->website->db('game', $server)->check_if_table_exists('MuCrywolf_DATA')){
                $table = 'MuCrywolf_DATA';
            } else{
                $table = 'WZ_CW_INFO';
            }
            $state = $this->website->db('game', $server)->query('SELECT CRYWOLF_STATE FROM ' . $table)->fetch();
            return ($state['CRYWOLF_STATE'] == 0) ? __('Not Protected') : __('Protected');
        }

        public function get_cs_info($server)
        {
            $siege_periods = $this->siege_periods();
            $query = $this->website->db('game', $server)->query('SELECT c.owner_guild, c.siege_start_date, c.siege_end_date, c.money, c.tax_rate_chaos, c.tax_rate_store, c.tax_hunt_zone, g.G_Master, g.G_Mark FROM MuCastle_DATA AS c LEFT JOIN Guild AS g ON (c.owner_guild COLLATE Database_Default = g.G_Name COLLATE Database_Default)');
            while($row = $query->fetch()){
                $this->cs_info = ['guild' => htmlspecialchars($row['owner_guild']), 'owner' => htmlspecialchars($row['G_Master']), 'money' => $row['money'], 'tax_chaos' => $row['tax_rate_chaos'], 'tax_store' => $row['tax_rate_store'], 'tax_hunt' => $row['tax_hunt_zone'], 'mark' => urlencode(bin2hex($row['G_Mark'])), 'period' => $this->cs_period($row['siege_start_date'], $siege_periods), 'battle_start' => $this->siege_battle_start($row['siege_start_date'], $siege_periods)];
            }
            return $this->cs_info;
        }

        public function get_cs_guild_list($server)
        {
            return $this->website->db('game', $server)->query('SELECT r.SEQ_NUM, r.REG_SIEGE_GUILD, r.REG_MARKS, r.IS_GIVEUP, g.G_Master FROM MuCastle_REG_SIEGE AS r INNER JOIN Guild AS g ON(r.REG_SIEGE_GUILD Collate Database_Default = g.G_Name Collate Database_Default) ORDER BY r.SEQ_NUM DESC')->fetch_all();
        }

        private function siege_battle_start($time, $periods = [])
        {
            return strtotime($time) + $this->cstime_to_sec($periods[6]);
        }

        private function cs_period($time, $periods = [])
        {
            if(strtotime($time) > time()){
                return __('Siege Period Is Overs');
            } else if(strtotime($time) + $this->cstime_to_sec($periods[1]) > time()){
                return __('Guild Registration') . '  (<span style="font-size: 8px;color: red;">' . date('Y-m-d H:i', strtotime($time)) . ' - ' . date('Y-m-d H:i', strtotime($time) + $this->cstime_to_sec($periods[1])) . '</span>)';
            } else if(strtotime($time) + $this->cstime_to_sec($periods[2]) > time()){
                return __('Idle') . ' (<span style="font-size: 8px;color: red;">' . date('Y-m-d H:i', strtotime($time) + $this->cstime_to_sec($periods[1])) . ' - ' . date('Y-m-d H:i', strtotime($time) + $this->cstime_to_sec($periods[2])) . '</span>)';
            } else if(strtotime($time) + $this->cstime_to_sec($periods[3]) > time()){
                return __('Mark Registration') . ' (<span style="font-size: 8px;color: red;">' . date('Y-m-d H:i', strtotime($time) + $this->cstime_to_sec($periods[2])) . ' - ' . date('Y-m-d H:i', strtotime($time) + $this->cstime_to_sec($periods[3])) . '</span>)';
            } else if(strtotime($time) + $this->cstime_to_sec($periods[4]) > time()){
                return __('Idle') . ' (<span style="font-size: 8px;color: red;">' . date('Y-m-d H:i', strtotime($time) + $this->cstime_to_sec($periods[3])) . ' - ' . date('Y-m-d H:i', strtotime($time) + $this->cstime_to_sec($periods[4])) . '</span>)';
            } else if(strtotime($time) + $this->cstime_to_sec($periods[5]) > time()){
                return __('Announcement') . ' (<span style="font-size: 8px;color: red;">' . date('Y-m-d H:i', strtotime($time) + $this->cstime_to_sec($periods[4])) . ' - ' . date('Y-m-d H:i', strtotime($time) + $this->cstime_to_sec($periods[5])) . '</span>)';
            } else if(strtotime($time) + $this->cstime_to_sec($periods[6]) > time()){
                return __('Castle Preparation') . ' (<span style="font-size: 8px;color: red;">' . date('Y-m-d H:i', strtotime($time) + $this->cstime_to_sec($periods[5])) . ' - ' . date('Y-m-d H:i', strtotime($time) + $this->cstime_to_sec($periods[6])) . '</span>)';
            } else if(strtotime($time) + $this->cstime_to_sec($periods[7]) > time()){
                return __('Siege Warfare') . ' (<span style="font-size: 8px;color: red;">' . date('Y-m-d H:i', strtotime($time) + $this->cstime_to_sec($periods[6])) . ' - ' . date('Y-m-d H:i', strtotime($time) + $this->cstime_to_sec($periods[7])) . '</span>)';
            } else{
                return __('Truce Period');
            }
        }

        private function siege_periods()
        {
            $file = file(APP_PATH . DS . 'data' . DS . 'MuCastleData.dat', FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
            if($file){
                $new_file = '';
                foreach($file as $line){
                    if(substr($line, 0, 2) !== '//'){
                        if(preg_match('/([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]([\/\/]).+$/u', $line, $match)){
                            $new_file .= $line . "\n";
                        }
                    }
                }
                $periods = [];
                foreach(explode("\n", $new_file) as $line){
                    $periods[] = explode("	", $line);
                }
                return $periods;
            }
            return false;
        }

        private function cstime_to_sec($time)
        {
            $sec = ($time[2] != 0) ? $time[2] * 24 * 60 * 60 : 24 * 60 * 60;
            $sec += ($time[3] != 0) ? $time[3] * 60 * 60 : 0;
            $sec += ($time[4] != 0) ? $time[4] * 60 + 60 : 60;
            return $sec - 1;
        }
    }
	