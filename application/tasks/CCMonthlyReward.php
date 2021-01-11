<?php

    class CCMonthlyReward extends Job
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
                if($this->rankings_config != false && isset($this->rankings_config['cc'])){
                    if(isset($this->rankings_config['cc']['is_monthly_reward']) && $this->rankings_config['cc']['is_monthly_reward'] == 1){
                        if($this->get_ranking($key, $this->rankings_config['cc']['amount_of_players_to_reward'], $this->table_config) != false && !empty($this->players[$key])){
                            $i = 0;
                            foreach($this->players[$key] AS $player){
                                $i++;
                                $userdata = $this->find_user_data($player['name'], $key);
                                $this->reward = (int)floor(arithmetic(str_replace(['{position}', '{score}'], [$i, $player['score']], $this->rankings_config['cc']['reward_formula'])));
                                $this->registry->website->add_credits($userdata['memb___id'], $key, $this->reward, $this->rankings_config['cc']['reward_type'], false, $userdata['memb_guid']);
                                $this->registry->Maccount->add_account_log('CC  ' . $this->last_month . ', ' . $this->year . ' reward ' . $this->registry->website->translate_credits($this->rankings_config['cc']['reward_type'], $key), +$this->reward, $userdata['memb___id'], $key);
                            }
                            $this->reset_ranking($key, $this->table_config);
                        }
                    }
                }
            }
        }

        private function get_ranking($server, $player_count = 1, $table_config)
        {
            if($table_config == false || $table_config['cc']['table'] == '')
                return false;
			unset($this->registry->game_db);
            $query = $this->registry->website->db($table_config['cc']['db'], $server)->query('SELECT TOP ' . $player_count . ' ' . $table_config['cc']['identifier_column'] . ', SUM(' . $table_config['cc']['column'] . ') AS ' . $table_config['cc']['column'] . ' FROM ' . $table_config['cc']['table'] . ' GROUP BY ' . $table_config['cc']['identifier_column'] . ' HAVING SUM(' . $table_config['cc']['column'] . ') > 0 ORDER BY SUM(' . $table_config['cc']['column'] . ') DESC');
            if($query){
                $i = 0;
                while($row = $query->fetch()){
                    $this->players[$server][] = ['name' => htmlspecialchars($row[$table_config['cc']['identifier_column']]), 'score' => $row[$table_config['cc']['column']]];
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

        private function reset_ranking($server, $table_config)
        {
            if($table_config == false || $table_config['cc']['table'] == '')
                return false;
            $this->registry->website->db($table_config['cc']['db'], $server)->query('UPDATE ' . $table_config['cc']['table'] . ' SET ' . $table_config['cc']['column'] . ' = 0');
        }
    }