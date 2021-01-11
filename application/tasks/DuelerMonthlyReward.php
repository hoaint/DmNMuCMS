<?php

    class DuelerMonthlyReward extends Job
    {
        private $registry, $rankings_config, $config, $load, $table_config, $last_month, $reward, $year, $formula = '', $players = [];

        public function __construct()
        {
            $this->registry = controller::get_instance();
            $this->config = $this->registry->config;
            $this->load = $this->registry->load;
        }

        public function execute()
        {
            $this->load->helper('website');
            $this->load->model('account');
            $this->year = date('Y');
            $this->last_month = date('F', strtotime(date('F') . " last month"));
            if($this->last_month == 'December')
                $this->year = date('Y', strtotime($this->year . " last year"));
            foreach($this->registry->website->server_list() AS $key => $server){
                $this->table_config = $this->config->values('table_config', $key);
                $this->rankings_config = $this->config->values('rankings_config', $key);
                if($this->rankings_config != false && isset($this->rankings_config['duels'])){
                    if(isset($this->rankings_config['duels']['is_monthly_reward']) && $this->rankings_config['duels']['is_monthly_reward'] == 1){
                        if($this->get_ranking($key, $this->rankings_config) != false && !empty($this->players[$key])){
                            $i = 0;
                            foreach($this->players[$key] AS $player){
                                $i++;
                                $userdata = $this->find_user_data($player['name'], $key);
                                $this->reward = (int)floor(arithmetic(str_replace('{position}', $i, $this->rankings_config['duels']['reward_formula'])));
                                $this->registry->website->add_credits($userdata['memb___id'], $key, $this->reward, $this->rankings_config['duels']['reward_type'], false, $userdata['memb_guid']);
                                $this->registry->Maccount->add_account_log('Dueler  ' . $this->last_month . ', ' . $this->year . ' reward ' . $this->registry->website->translate_credits($this->rankings_config['duels']['reward_type'], $key), +$this->reward, $userdata['memb___id'], $key);
                            }
                            $this->reset_ranking($key);
                        }
                    }
                }
            }
        }

        private function get_ranking($server, $config)
        {
            if($this->table_config == false || $this->table_config['duels']['table'] == '')
                return false;
            $exclude_list = '';
            if(isset($config['duels']['excluded_list'])){
                $exclude_list = $this->exclude_list($config['duels']['excluded_list'], $this->table_config['duels']['identifier_column']);
            }
            if($exclude_list != ''){
                $exclude_list = 'WHERE ' . str_replace(' AND', '', $exclude_list);
            }
			unset($this->registry->game_db);
            $query = $this->registry->website->db($this->table_config['duels']['db'], $server)->query('SELECT TOP ' . $config['duels']['amount_of_players_to_reward'] . ' ' . $this->table_config['duels']['identifier_column'] . ' FROM ' . $this->table_config['duels']['table'] . '  WHERE ' . $this->table_config['duels']['column'] . ' > 0 OR ' . $this->table_config['duels']['column2'] . ' > 0 ' . $exclude_list . ' ORDER BY ' . $this->table_config['duels']['column'] . ' DESC, ' . $this->table_config['duels']['column2'] . ' ASC');
            if($query){
                $i = 0;
                while($row = $query->fetch()){
                    $this->players[$server][] = ['name' => htmlspecialchars($row[$this->table_config['duels']['identifier_column']])];
                    $i++;
                }
                if($i > 0){
                    return true;
                }
            }
            return false;
        }

        private function exclude_list($list, $bound = 'c.Name', $quote = true, $stmt = 'NOT IN')
        {
            $data = implode(',', array_map(function($value) use ($quote){
                return ($quote) ? "'" . $this->registry->website->db('web')->sanitize_var($value) . "'" : $this->registry->website->db('web')->sanitize_var($value);
            }, explode(',', $list)));
            return ($list != '') ? ' AND ' . $bound . ' ' . $stmt . ' (' . $data . ')' : '';
        }

        private function find_user_data($name, $server)
        {
            $accountDb = ($this->registry->website->is_multiple_accounts() == true) ? $this->registry->website->get_db_from_server($server, true) : $this->registry->website->get_default_account_database();
            $stmt = $this->registry->website->db('game', $server)->prepare('SELECT TOP 1 c.AccountId, m.memb___id, m.memb_guid FROM Character AS c INNER JOIN [' . $accountDb . '].dbo.MEMB_INFO AS m ON (c.AccountId COLLATE Database_Default = m.memb___id) WHERE c.Name = :name');
            $stmt->execute([':name' => $name]);
            return $stmt->fetch();
        }

        private function reset_ranking($server)
        {
            if($this->table_config == false || $this->table_config['duels']['table'] == '')
                return false;
            $this->registry->website->db($this->table_config['duels']['db'], $server)->query('UPDATE ' . $this->table_config['duels']['table'] . ' SET ' . $this->table_config['duels']['column'] . ' = 0, ' . $this->table_config['duels']['column2'] . ' = 0');
        }
    }