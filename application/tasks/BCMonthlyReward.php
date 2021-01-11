<?php

    class BCMonthlyReward extends Job
    {
        private $registry, $rankings_config, $load, $config, $table_config, $last_month, $reward, $year, $formula = '', $players = [];

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
                if($this->rankings_config != false && isset($this->rankings_config['bc'])){
                    if(isset($this->rankings_config['bc']['is_monthly_reward']) && $this->rankings_config['bc']['is_monthly_reward'] == 1){
                        if($this->get_ranking($key, $this->rankings_config['bc']['amount_of_players_to_reward']) != false && !empty($this->players[$key])){
                            $i = 0;
                            foreach($this->players[$key] AS $player){
                                $i++;
                                $userdata = $this->find_user_data($player['name'], $key);
                                $this->reward = (int)floor(arithmetic(str_replace(['{position}', '{score}'], [$i, $player['score']], $this->rankings_config['bc']['reward_formula'])));
                                $this->registry->website->add_credits($userdata['memb___id'], $key, $this->reward, $this->rankings_config['bc']['reward_type'], false, $userdata['memb_guid']);
                                $this->registry->Maccount->add_account_log('BC  ' . $this->last_month . ', ' . $this->year . ' reward ' . $this->registry->website->translate_credits($this->rankings_config['bc']['reward_type'], $key), +$this->reward, $userdata['memb___id'], $key);
                            }
                            $this->reset_ranking($key);
                        }
                    }
                }
            }
        }

        private function get_ranking($server, $player_count = 1)
        {
            if($this->table_config == false || $this->table_config['bc']['table'] == '')
                return false;
			unset($this->registry->game_db);
            $query = $this->registry->website->db($this->table_config['bc']['db'], $server)->query('SELECT TOP ' . $player_count . ' ' . $this->table_config['bc']['identifier_column'] . ', SUM(' . $this->table_config['bc']['column'] . ') AS ' . $this->table_config['bc']['column'] . ' FROM ' . $this->table_config['bc']['table'] . ' GROUP BY ' . $this->table_config['bc']['identifier_column'] . ' HAVING SUM(' . $this->table_config['bc']['column'] . ') > 0 ORDER BY SUM(' . $this->table_config['bc']['column'] . ') DESC');
            if($query){
                $i = 0;
                while($row = $query->fetch()){
                    $this->players[$server][] = ['name' => htmlspecialchars($row[$this->table_config['bc']['identifier_column']]), 'score' => $row[$this->table_config['bc']['column']]];
                    $i++;
                }
                if($i > 0){
                    return true;
                }
            }
            return false;
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
            if($this->table_config == false || $this->table_config['bc']['table'] == '')
                return false;
            $this->registry->website->db($this->table_config['bc']['db'], $server)->query('UPDATE ' . $this->table_config['bc']['table'] . ' SET ' . $this->table_config['bc']['column'] . ' = 0');
        }
    }