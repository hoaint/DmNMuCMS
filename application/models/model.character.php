<?php
    in_file();

    class Mcharacter extends model
    {
        private $characters = [], $guild_info = [], $ml_points = 0, $ml_level = 0, $skillTreeTable, $defaultStats = [], $charge_from_zen_wallet = 0;
        public $error = false, $vars = [], $char_info = [];

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

        public function load_char_list()
        {
            $stmt = $this->game_db->prepare('SELECT Name, cLevel, Class, ' . $this->reset_column($this->session->userdata(['user' => 'server'])) . $this->greset_column($this->session->userdata(['user' => 'server'])) . ' Money, LevelUpPoint, CtlCode, PkCount, PkLevel FROM Character WHERE AccountId = :account');
            $stmt->execute([':account' => $this->session->userdata(['user' => 'username'])]);
            $i = 0;
            while($row = $stmt->fetch()){
                $this->characters[] = ['name' => $row['Name'], 'level' => $row['cLevel'], 'Class' => $row['Class'], 'resets' => $row['resets'], 'gresets' => $row['grand_resets'], 'money' => $row['Money'], 'points' => $row['LevelUpPoint'], 'ctlcode' => $row['CtlCode'], 'pkcount' => $row['PkCount'], 'pklevel' => $row['PkLevel'], 'CtlCode' => $row['CtlCode']];
                $i++;
            }
            if($i > 0){
                return $this->characters;
            } else{
                return false;
            }
        }

        private function reset_column($server = '')
        {
            $resets = $this->config->values('table_config', [$server, 'resets', 'column']);
            if($resets && $resets != ''){
                return $resets . ' AS resets,';
            }
            return '0 AS resets,';
        }

        private function greset_column($server = '')
        {
            $grand_resets = $this->config->values('table_config', [$server, 'grand_resets', 'column']);
            if($grand_resets && $grand_resets != ''){
                return $grand_resets . ' AS grand_resets,';
            }
            return '0 AS grand_resets,';
        }

        public function check_char($char = '', $custom_field = '')
        {
            $c = ($char != '') ? $char : $this->vars['character'];
            $stmt = $this->game_db->prepare('SELECT Name, Money, Class, cLevel, ' . $this->reset_column($this->session->userdata(['user' => 'server'])) . $this->greset_column($this->session->userdata(['user' => 'server'])) . ' LevelUpPoint, Strength, Dexterity, Vitality, Energy, Leadership, PkLevel, PkCount, CtlCode, MagicList, last_reset_time' . $custom_field . ' FROM Character WHERE AccountId = :user AND Name = :char');
            $stmt->execute([':user' => $this->session->userdata(['user' => 'username']), ':char' => $this->website->c($c)]);
            if($this->char_info = $stmt->fetch()){
                $this->char_info['mlevel'] = $this->load_master_level($this->char_info['Name'], $this->session->userdata(['user' => 'server']));
                $this->get_inventory_content($this->char_info['Name']);
                $this->getQuest($this->char_info['Name'], $this->session->userdata(['user' => 'server']));
                return true;
            }
            return false;
        }

        private function getQuest($char, $server)
        {
            $sql = (DRIVER == 'pdo_odbc') ? 'Quest' : 'CONVERT(IMAGE, Quest) AS Quest';
            $stmt = $this->website->db('game', $server)->prepare('SELECT ' . $sql . ' FROM Character WHERE Name = :char');
            $stmt->execute([':char' => $this->website->c($char)]);
			if($quest = $stmt->fetch()){
				$this->char_info['Quest'] = $this->clean_hex($quest['Quest']);
			}
        }

        public function check_char_no_account($char = '', $server)
        {
            $stmt = $this->game_db->prepare('SELECT AccountId, Name, Money, Class, cLevel, ' . $this->reset_column($server) . $this->greset_column($server) . ' LevelUpPoint, Strength, Dexterity, Vitality, Energy, Leadership, PkLevel, PkCount, CtlCode, MagicList, last_reset_time FROM Character WHERE Name = :char');
            $stmt->execute([':char' => $this->website->c($char)]);
            if($this->char_info = $stmt->fetch()){
                $this->char_info['mlevel'] = $this->load_master_level($this->char_info['Name'], $server);
                $this->get_inventory_content($char);
                return true;
            }
            return false;
        }

        public function check_zen($required = 0, $multiply = 0, $col = 'resets')
        {
            if($this->session->userdata('vip')){
                $required -= $this->session->userdata(['vip' => 'reset_price_decrease']);
            }
            if($multiply == 1){
                $this->char_info['res_money'] = $required * ($this->char_info[$col] + 1);
            } else{
                $this->char_info['res_money'] = $required;
            }
            return ($this->char_info['Money'] >= $this->char_info['res_money']) ? true : $this->char_info['res_money'];
        }

        public function check_zen_wallet($required = 0, $multiply = 0, $col = 'resets')
        {
            $this->charge_from_zen_wallet = 1;
            $status = $this->website->get_user_credits_balance($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), 3, $this->session->userdata(['user' => 'id']));
            return ($status['credits'] >= $this->char_info['res_money']) ? true : $this->char_info['res_money'];
        }

        public function check_lvl($req_lvl = 400)
        {
            if($this->session->userdata('vip')){
                $req_lvl -= $this->session->userdata(['vip' => 'reset_level_decrease']);
            }
            return ($this->char_info['cLevel'] >= $req_lvl) ? true : $req_lvl;
        }

        public function check_resets($req_resets = 100)
        {
            return ($this->char_info['resets'] >= $req_resets);
        }

        public function check_gresets($req_gresets = 0)
        {
            return ($this->char_info['grand_resets'] >= $req_gresets);
        }

        public function check_stats()
        {
            $this->vars['str_stat'] = isset($this->vars['str_stat']) ? (int)$this->vars['str_stat'] : 0;
            $this->vars['agi_stat'] = isset($this->vars['agi_stat']) ? (int)$this->vars['agi_stat'] : 0;
            $this->vars['ene_stat'] = isset($this->vars['ene_stat']) ? (int)$this->vars['ene_stat'] : 0;
            $this->vars['vit_stat'] = isset($this->vars['vit_stat']) ? (int)$this->vars['vit_stat'] : 0;
            $this->vars['com_stat'] = isset($this->vars['com_stat']) ? (int)$this->vars['com_stat'] : 0;
            $this->vars['allstats'] = in_array($this->char_info['Class'], [64, 65, 66, 67, 70]) ? ($this->vars['str_stat'] + $this->vars['agi_stat'] + $this->vars['ene_stat'] + $this->vars['vit_stat'] + $this->vars['com_stat']) : ($this->vars['str_stat'] + $this->vars['agi_stat'] + $this->vars['ene_stat'] + $this->vars['vit_stat']);
        }

        public function set_new_stats()
        {
            $this->vars['new_str'] = $this->show65kStats($this->char_info['Strength']) + $this->vars['str_stat'];
            $this->vars['new_agi'] = $this->show65kStats($this->char_info['Dexterity']) + $this->vars['agi_stat'];
            $this->vars['new_ene'] = $this->show65kStats($this->char_info['Energy']) + $this->vars['ene_stat'];
            $this->vars['new_vit'] = $this->show65kStats($this->char_info['Vitality']) + $this->vars['vit_stat'];
            $this->vars['new_com'] = in_array($this->char_info['Class'], [64, 65, 66, 67, 70]) ? $this->show65kStats($this->char_info['Leadership']) + $this->vars['com_stat'] : 0;
            $this->vars['new_lvlup'] = $this->char_info['LevelUpPoint'] - $this->vars['allstats'];
        }

        public function check_max_stat_limit()
        {
            if($this->vars['new_str'] > $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|max_stats')){
                $this->vars['error'] = 'Max Strength: ' . $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|max_stats');
            }
            if($this->vars['new_agi'] > $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|max_stats')){
                $this->vars['error'] = 'Max Agility: ' . $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|max_stats');
            }
            if($this->vars['new_ene'] > $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|max_stats')){
                $this->vars['error'] = 'Max Energy: ' . $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|max_stats');
            }
            if($this->vars['new_vit'] > $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|max_stats')){
                $this->vars['error'] = 'Max Vitality: ' . $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|max_stats');
            }
            if(in_array($this->char_info['Class'], [64, 65, 66, 67, 70])){
                if($this->vars['new_com'] > $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|max_stats')){
                    $this->vars['error'] = 'Max Command: ' . $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|max_stats');
                }
            }
            if(isset($this->vars['error']))
                return false;
            return true;
        }

        public function add_stats($char = '')
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET LevelUpPoint = :new_lvlup, Strength = :new_str, Dexterity = :new_agi, Energy = :new_ene, Vitality = :new_vit, Leadership = :new_com WHERE Name = :char AND AccountId = :user');
            $stmt->execute([':new_lvlup' => $this->vars['new_lvlup'], ':new_str' => $this->vars['new_str'], ':new_agi' => $this->vars['new_agi'], ':new_ene' => $this->vars['new_ene'], ':new_vit' => $this->vars['new_vit'], ':new_com' => $this->vars['new_com'], ':char' => $char, ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        public function reset_character()
        {
			$location = 'MapNumber = 0, MapPosX = 123, MapPosY = 130,';
			if(in_array($this->char_info['Class'], [32,33,34,35,39])){
				$location = 'MapNumber = 3, MapPosX = 175, MapPosY = 114,';
			}
			if(in_array($this->char_info['Class'], [80,81,82,83,84,87])){
				$location = 'MapNumber = 51, MapPosX = 52, MapPosY = 226,';
			}

			$level_after_reset = isset($this->char_info['res_info']['level_after_reset']) ? (int)$this->char_info['res_info']['level_after_reset'] : 1;
			
            if($this->charge_from_zen_wallet == 0){
                $query = 'UPDATE Character SET ' . $this->config->values('table_config', [$this->session->userdata(['user' => 'server']), 'resets', 'column']) . ' = ' . $this->config->values('table_config', [$this->session->userdata(['user' => 'server']), 'resets', 'column']) . ' + 1, clevel = '.$level_after_reset.', Money = Money - :reset_money, '.$location.' Experience = 0, last_reset_time = :time WHERE Name = :char AND AccountId = :user';
                $data = [':reset_money' => $this->char_info['res_money'], ':time' => time(), ':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])];
            } else{
                $this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $this->char_info['res_money'], 3);
                $query = 'UPDATE Character SET ' . $this->config->values('table_config', [$this->session->userdata(['user' => 'server']), 'resets', 'column']) . ' = ' . $this->config->values('table_config', [$this->session->userdata(['user' => 'server']), 'resets', 'column']) . ' + 1, clevel = '.$level_after_reset.', '.$location.' Experience = 0, last_reset_time = :time WHERE Name = :char AND AccountId = :user';
                $data = [':time' => time(), ':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])];
            }
            $stmt = $this->game_db->prepare($query);
            $stmt->execute($data);
            if($this->char_info['res_info']['clear_magic'] != 0){
                $this->clear_magic_list();
            }
            $this->clear_inventory($this->char_info['res_info']);
            if($this->char_info['res_info']['clear_stats'] != 0){
                $this->clear_reset_stats();
            }
            if($this->char_info['res_info']['clear_level_up'] != 0){
                $this->clear_reset_levelup();
            }
            $this->add_bonus_reset_points();
            if($this->char_info['res_info']['bonus_gr_points'] == 1 && $this->char_info['bonus_greset_stats_points'] > 0){
                $this->add_bonus_stats_for_gresets();
            }
            if(defined('RES_CUSTOM_BACKUP_MASTER') && RES_CUSTOM_BACKUP_MASTER == true){
                if(in_array($this->char_info['Class'], [2, 3, 18, 19, 34, 35, 49, 50, 65, 66, 82, 83, 97, 98])){
                    $this->backup_master_level();
                    $this->change_reset_class();
                }
            }
            if($this->char_info['res_info']['bonus_credits'] != 0){
                $this->add_account_log('Reward ' . $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_1') . ' for reset: ' . $this->vars['character'] . '', $this->char_info['res_info']['bonus_credits'], $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                $this->add_bonus_credits(1, $this->char_info['res_info']['bonus_credits']);
            }
            if($this->char_info['res_info']['bonus_gcredits'] != 0){
                $this->add_account_log('Reward ' . $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_2') . ' for reset: ' . $this->vars['character'] . '', $this->char_info['res_info']['bonus_gcredits'], $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                $this->add_bonus_credits(2, $this->char_info['res_info']['bonus_gcredits']);
            }
			if($this->char_info['res_info']['bonus_credits'] == 0 && $this->char_info['res_info']['bonus_gcredits'] == 0){
                $this->add_account_log('Character ' . $this->vars['character'] . ' made reset', 0, $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
            }
            
			if(isset($this->char_info['res_info']['clear_masterlevel']) && $this->char_info['res_info']['clear_masterlevel'] == 1){
				$skill_tree = $this->reset_skill_tree($this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|skill_tree_type'), $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|skilltree_reset_level'), $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|skilltree_reset_points'), $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|skilltree_points_multiplier'));
			}
            return true;
        }

        public function greset_character()
        {
            if(isset($this->char_info['gres_info']['clear_all_resets']) && $this->char_info['gres_info']['clear_all_resets'] == 0){
                $resets = $this->config->values('table_config', [$this->session->userdata(['user' => 'server']), 'resets', 'column']) . '-' . $this->char_info['gres_info']['reset'];
            } else{
                $resets = 0;
            }
			$location = 'MapNumber = 0, MapPosX = 123, MapPosY = 130,';
			if(in_array($this->char_info['Class'], [32,33,34,35,39])){
				$location = 'MapNumber = 3, MapPosX = 175, MapPosY = 114,';
			}
			if(in_array($this->char_info['Class'], [80,81,82,83,84,87])){
				$location = 'MapNumber = 51, MapPosX = 52, MapPosY = 226,';
			}
            if($this->charge_from_zen_wallet == 0){
                $query = 'UPDATE Character SET ' . $this->config->values('table_config', [$this->session->userdata(['user' => 'server']), 'resets', 'column']) . ' = ' . $resets . ', ' . $this->config->values('table_config', [$this->session->userdata(['user' => 'server']), 'grand_resets', 'column']) . ' = ' . $this->config->values('table_config', [$this->session->userdata(['user' => 'server']), 'grand_resets', 'column']) . ' + 1, clevel = 1, '.$location.' Experience = 0, Money = Money - :money, last_greset_time = :time WHERE Name = :char AND AccountId = :user';
                $data = [':money' => $this->char_info['res_money'], ':time' => time(), ':char' => $this->vars['character'], ':user' => $this->session->userdata(['user' => 'username'])];
            } else{
                $this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $this->char_info['res_money'], 3);
                $query = 'UPDATE Character SET ' . $this->config->values('table_config', [$this->session->userdata(['user' => 'server']), 'resets', 'column']) . ' = ' . $resets . ', ' . $this->config->values('table_config', [$this->session->userdata(['user' => 'server']), 'grand_resets', 'column']) . ' = ' . $this->config->values('table_config', [$this->session->userdata(['user' => 'server']), 'grand_resets', 'column']) . ' + 1, clevel = 1, '.$location.' Experience = 0, last_greset_time = :time WHERE Name = :char AND AccountId = :user';
                $data = [':time' => time(), ':char' => $this->vars['character'], ':user' => $this->session->userdata(['user' => 'username'])];
            }
            $stmt = $this->game_db->prepare($query);
            $stmt->execute($data);
            if($this->char_info['gres_info']['clear_magic'] != 0){
                $this->clear_magic_list();
            }
            if($this->char_info['gres_info']['clear_inventory'] != 0){
                $this->clear_inventory();
            }
            if($this->char_info['gres_info']['clear_stats'] != 0){
                $this->clear_greset_stats();
            }
            if($this->char_info['gres_info']['clear_level_up'] != 0){
                $this->clear_greset_levelup();
            }
            $this->add_bonus_greset_points();
            if($this->char_info['gres_info']['bonus_reset_stats'] == 1 && $this->char_info['bonus_reset_stats_points'] > 0){
                $this->add_bonus_stats_for_resets();
            }
            if($this->session->userdata('vip')){
                $this->char_info['gres_info']['bonus_credits'] += $this->session->userdata(['vip' => 'grand_reset_bonus_credits']);
                $this->char_info['gres_info']['bonus_gcredits'] += $this->session->userdata(['vip' => 'grand_reset_bonus_credits']);
            }
            if($this->char_info['gres_info']['bonus_credits'] != 0){
                $this->add_account_log('Reward ' . $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_1') . ' for grand reset: ' . $this->vars['character'] . '', $this->char_info['gres_info']['bonus_credits'], $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                $this->add_bonus_credits(1, $this->char_info['gres_info']['bonus_credits']);
            }
            if($this->char_info['gres_info']['bonus_gcredits'] != 0){
                $this->add_account_log('Reward ' . $this->config->config_entry('credits_' . $this->session->userdata(['user' => 'server']) . '|title_2') . ' for grand reset: ' . $this->vars['character'] . '', $this->char_info['gres_info']['bonus_gcredits'], $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                $this->add_bonus_credits(2, $this->char_info['gres_info']['bonus_gcredits']);
            }
			if($this->char_info['gres_info']['bonus_credits'] == 0 && $this->char_info['gres_info']['bonus_gcredits'] == 0){
                $this->add_account_log('Character ' . $this->vars['character'] . ' made grand reset', 0, $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
            }

			if(isset($this->char_info['gres_info']['clear_masterlevel']) && $this->char_info['gres_info']['clear_masterlevel'] == 1){
				$skill_tree = $this->reset_skill_tree($this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|skill_tree_type'), $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|skilltree_reset_level'), $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|skilltree_reset_points'), $this->config->config_entry('character_' . $this->session->userdata(['user' => 'server']) . '|skilltree_points_multiplier'));
			}
            return true;
        }

        private function clear_reset_stats()
        {
            if(!defined('RES_DECREASE_STATS_BY_PERC') || RES_DECREASE_STATS_BY_PERC == false){
                $data = [':str' => $this->Mcharacter->char_info['res_info']['new_stat_points'], ':agi' => $this->Mcharacter->char_info['res_info']['new_stat_points'], ':vit' => $this->Mcharacter->char_info['res_info']['new_stat_points'], ':ene' => $this->Mcharacter->char_info['res_info']['new_stat_points']];
                if(in_array($this->char_info['Class'], [64, 65, 66, 67, 70])){
                    $dl = ', Leadership = :com';
                    $data[':com'] = $this->Mcharacter->char_info['res_info']['new_stat_points'];
                } else{
                    $dl = '';
                }
            } else{
                $data = [':str' => $this->show65kStats($this->char_info['Strength']) - ((RES_DECREASE_PERC / 100) * $this->show65kStats($this->char_info['Strength'])), ':agi' => $this->show65kStats($this->char_info['Dexterity']) - ((RES_DECREASE_PERC / 100) * $this->show65kStats($this->char_info['Dexterity'])), ':vit' => $this->show65kStats($this->char_info['Vitality']) - ((RES_DECREASE_PERC / 100) * $this->show65kStats($this->char_info['Vitality'])), ':ene' => $this->show65kStats($this->char_info['Energy']) - ((RES_DECREASE_PERC / 100) * $this->show65kStats($this->char_info['Energy']))];
                if(in_array($this->char_info['Class'], [64, 65, 66, 67, 70])){
                    $dl = ', Leadership = :com';
                    $data[':com'] = $this->show65kStats($this->char_info['Leadership']) - ((RES_DECREASE_PERC / 100) * $this->show65kStats($this->char_info['Leadership']));
                } else{
                    $dl = '';
                }
            }
            $data[':char'] = $this->vars['character'];
            $data[':user'] = $this->session->userdata(['user' => 'username']);
            $stmt = $this->game_db->prepare('UPDATE Character SET Strength = :str, Dexterity = :agi, Vitality = :vit, Energy = :ene' . $dl . ' WHERE Name = :char AND AccountId = :user');
            return $stmt->execute($data);
        }

        private function clear_greset_stats()
        {
            $data = [':str' => $this->Mcharacter->char_info['gres_info']['new_stat_points'], ':agi' => $this->Mcharacter->char_info['gres_info']['new_stat_points'], ':vit' => $this->Mcharacter->char_info['gres_info']['new_stat_points'], ':ene' => $this->Mcharacter->char_info['gres_info']['new_stat_points']];
            if(in_array($this->char_info['Class'], [64, 65, 66, 67, 70])){
                $dl = ', Leadership = :com';
                $data[':com'] = $this->Mcharacter->char_info['gres_info']['new_stat_points'];
            } else{
                $dl = '';
            }
            $data[':char'] = $this->vars['character'];
            $data[':user'] = $this->session->userdata(['user' => 'username']);
            $stmt = $this->game_db->prepare('UPDATE Character SET Strength = :str, Dexterity = :agi, Vitality = :vit, Energy = :ene' . $dl . ' WHERE Name = :char AND AccountId = :user');
            return $stmt->execute($data);
        }

        private function clear_reset_levelup()
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET LevelUpPoint = :freepoints WHERE Name = :character AND AccountId = :user');
            return $stmt->execute([':freepoints' => $this->Mcharacter->char_info['res_info']['new_free_points'], ':character' => $this->vars['character'], ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        private function clear_greset_levelup()
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET LevelUpPoint = :freepoints WHERE Name = :character AND AccountId = :user');
            return $stmt->execute([':freepoints' => $this->Mcharacter->char_info['gres_info']['new_free_points'], ':character' => $this->vars['character'], ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        public function clear_magic_list()
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET MagicList = CAST(REPLICATE(char(0xff), 180) as varbinary(180)) WHERE Name = :char AND AccountId = :user');
            $stmt->execute([':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        private function clear_inventory($res_config = false)
        {
//            $items_array = str_split($this->Mcharacter->char_info['Inventory'], $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
//            if($res_config != false){
//                if(isset($res_config['clear_inventory']) && $res_config['clear_inventory'] == 1){
//                    for($a = 12; $a < 76; $a++){
//                        $items_array[$a] = str_repeat('F', $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
//                    }
//                }
//                if(isset($res_config['clear_equipment']) && $res_config['clear_equipment'] == 1){
//                    for($a = 0; $a < 12; $a++){
//                        $items_array[$a] = str_repeat('F', $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
//                    }
//                    if(isset($items_array[236])){
//                        $items_array[236] = str_repeat('F', $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
//                    }
//                }
//                if($this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'inv_multiplier') == 236){
//                    if(isset($res_config['clear_store']) && $res_config['clear_store'] == 1){
//                        for($a = 204; $a < 236; $a++){
//                            $items_array[$a] = str_repeat('F', $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
//                        }
//                    }
//                    if(isset($res_config['clear_exp_inventory']) && $res_config['clear_exp_inventory'] == 1){
//                        for($a = 76; $a < 140; $a++){
//                            $items_array[$a] = str_repeat('F', $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
//                        }
//                    }
//                } else{
//                    if(isset($res_config['clear_store']) && $res_config['clear_store'] == 1){
//                        for($a = 76; $a < 108; $a++){
//                            $items_array[$a] = str_repeat('F', $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
//                        }
//                    }
//                }
//            } else{
//                for($a = 0; $a <= $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'inv_multiplier'); $a++){
//                    $items_array[$a] = str_repeat('F', $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
//                }
//            }
//            $stmt = $this->game_db->prepare('UPDATE Character SET Inventory = 0x' . implode('', $items_array) . " WHERE Name = :char AND AccountId = :user");
//            $stmt->execute([':char' =>  $this->vars['character'], ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        private function add_bonus_reset_points()
        {
            $bonus = ($this->char_info['resets'] + 1) * $this->bonus_points_by_class($this->char_info['Class']);
			if($this->session->userdata('vip')){
                $bonus += $this->session->userdata(['vip' => 'reset_bonus_points']);
            }
            $stmt = $this->game_db->prepare('UPDATE Character SET LevelUpPoint = LevelUpPoint + :lvlup WHERE Name = :char AND AccountId = :user');
            return $stmt->execute([':lvlup' => $bonus, ':char' => $this->vars['character'], ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        private function add_bonus_greset_points()
        {
            if($this->char_info['gres_info']['bonus_points_save'] == 1){
                $bonus = ($this->char_info['grand_resets'] + 1) * $this->bonus_points_by_class($this->char_info['Class'], 'gres_info');
            } else{
                $bonus = $this->bonus_points_by_class($this->char_info['Class'], 'gres_info');
            }
            $stmt = $this->game_db->prepare('UPDATE Character SET LevelUpPoint = LevelUpPoint + :lvlup WHERE Name = :char AND AccountId = :user');
            return $stmt->execute([':lvlup' => $bonus, ':char' => $this->vars['character'], ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        private function add_bonus_stats_for_resets()
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET LevelUpPoint = LevelUpPoint + :lvlup WHERE Name = :char AND AccountId = :user');
            return $stmt->execute([':lvlup' => $this->char_info['bonus_reset_stats_points'], ':char' => $this->vars['character'], ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        private function add_bonus_stats_for_gresets()
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET LevelUpPoint = LevelUpPoint + :lvlup WHERE Name = :char AND AccountId = :user');
            return $stmt->execute([':lvlup' => $this->char_info['bonus_greset_stats_points'], ':char' => $this->vars['character'], ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        public function bonus_points_by_class($class, $type = 'res_info', $data = false)
        {
            $char_info = ($data != false) ? $data : $this->Mcharacter->char_info;
            switch($class){
                case 0:
                    return $char_info[$type]['bonus_points']['dw'];
                    break;
                case 1:
                    return $char_info[$type]['bonus_points']['sm'];
                    break;
                case 2:
                case 3:
                    return $char_info[$type]['bonus_points']['gm'];
                    break;
                case 7:
                    return $char_info[$type]['bonus_points']['sw'];
                    break;
                case 16:
                    return $char_info[$type]['bonus_points']['dk'];
                    break;
                case 17:
                    return $char_info[$type]['bonus_points']['bk'];
                    break;
                case 18:
                case 19:
                    return $char_info[$type]['bonus_points']['bm'];
                    break;
                case 23:
                    return $char_info[$type]['bonus_points']['drk'];
                    break;
                case 32:
                    return $char_info[$type]['bonus_points']['fe'];
                    break;
                case 33:
                    return $char_info[$type]['bonus_points']['me'];
                    break;
                case 34:
                case 35:
                    return $char_info[$type]['bonus_points']['he'];
                    break;
                case 39:
                    return $char_info[$type]['bonus_points']['ne'];
                    break;
                case 48:
                    return $char_info[$type]['bonus_points']['mg'];
                    break;
                case 49:
                case 50:
                    return $char_info[$type]['bonus_points']['dm'];
                    break;
				case 51:	
                case 54:
                    return $char_info[$type]['bonus_points']['mk'];
                    break;
                case 64:
                    return $char_info[$type]['bonus_points']['dl'];
                    break;
                case 65:
                case 66:
                    return $char_info[$type]['bonus_points']['le'];
                    break;
				case 67:	
                case 70:
                    return $char_info[$type]['bonus_points']['er'];
                    break;
                case 80:
                    return $char_info[$type]['bonus_points']['su'];
                    break;
                case 81:
                    return $char_info[$type]['bonus_points']['bs'];
                    break;
                case 82:
                case 83:
                    return $char_info[$type]['bonus_points']['dim'];
                    break;
				case 84:	
                case 87:
                    return $char_info[$type]['bonus_points']['ds'];
                    break;
                case 96:
                    return $char_info[$type]['bonus_points']['rf'];
                    break;
                case 97:
                case 98:
                    return $char_info[$type]['bonus_points']['fm'];
                    break;
				case 99:	
                case 102:
                    return $char_info[$type]['bonus_points']['fb'];
                    break;
                case 112:
                    return $char_info[$type]['bonus_points']['gl'];
                    break;
                case 114:
                    return $char_info[$type]['bonus_points']['ml'];
                    break;
				case 115:			  
                case 118:
                    return $char_info[$type]['bonus_points']['sl'];
                    break;
                case 128:
                    return $char_info[$type]['bonus_points']['rw'];
                    break;
                case 129:
                    return $char_info[$type]['bonus_points']['rsm'];
                    break;
				case 130:													
                case 131:
                    return $char_info[$type]['bonus_points']['grm'];
                    break;
                case 135:
                    return $char_info[$type]['bonus_points']['rw4'];
                    break;
				case 144:
                    return $char_info[$type]['bonus_points']['slr'];
                    break;
				case 145:
                    return $char_info[$type]['bonus_points']['rsl'];
                    break;
				case 147:
                    return $char_info[$type]['bonus_points']['msl'];
                    break;
				case 151:
                    return $char_info[$type]['bonus_points']['slt'];
                    break;			
                default:
                    return 0;
                    break;
            }
        }

        public function class_code_to_readable($class = 0)
        {
            switch($class){
                case 0:
                    return 'dw';
                    break;
                case 1:
                    return 'sm';
                    break;
                case 2:
                case 3:
                    return 'gm';
                    break;
                case 7:
                    return 'sw';
                    break;
                case 16:
                    return 'dk';
                    break;
                case 17:
                    return 'bk';
                    break;
                case 18:
                case 19:
                    return 'bm';
                    break;
                case 23:
                    return 'drk';
                    break;
                case 32:
                    return 'fe';
                    break;
                case 33:
                    return 'me';
                    break;
                case 34:
                case 35:
                    return 'he';
                    break;
                case 39:
                    return 'ne';
                    break;
                case 48:
                    return 'mg';
                    break;
                case 49:
                case 50:
                    return 'dm';
                    break;
				case 51:	
                case 54:
                    return 'mk';
                    break;
                case 64:
                    return 'dl';
                    break;
                case 65:
                case 66:
                    return 'le';
                    break;
				case 67:	
                case 70:
                    return 'er';
                    break;
                case 80:
                    return 'su';
                    break;
                case 81:
                    return 'bs';
                    break;
                case 82:
                case 83:
                    return 'dim';
                    break;
				case 84:	
                case 87:
                    return 'ds';
                    break;
                case 96:
                    return 'rf';
                    break;
                case 97:
                case 98:
                    return 'fm';
                    break;
				case 99:	
                case 102:
                    return 'fb';
                    break;
                case 112:
                    return 'gl';
                    break;
                case 114:
                    return 'ml';
                    break;
                case 118:
                    return 'sl';
                    break;
                case 128:
                    return 'rw';
                    break;
                case 129:
                    return 'rsm';
                    break;
				case 130:			 
                case 131:
                    return 'grm';
                    break;
                case 135:
                    return 'rw4';
                    break;
				case 144:
                    return 'slr';
                    break;
				case 145:
                    return 'rsl';
                    break;
				case 147:
                    return 'msl';
                    break;
				case 151:
                    return 'slt';
                    break;	
                default:
                    return 0;
                    break;
            }
        }

        private function backup_master_level()
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET Master = mLevel WHERE Name = :char AND AccountId = :user');
            $stmt->execute([':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        private function change_reset_class()
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET Class = CASE WHEN Class IN(50, 66, 98) THEN Class - 2 ELSE Class - 1 END WHERE Name = :char AND AccountId = :user');
            $stmt->execute([':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        public function restore_master_level()
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET mLevel = Master, Class = CASE WHEN Class IN(48, 64, 96) THEN Class + 2 ELSE Class + 1 END, mlPoint = Master + (' . $this->config->values('table_config', [$this->session->userdata(['user' => 'server']), 'resets', 'column']) . '*150) WHERE Name = :char AND AccountId = :user');
            $stmt->execute([':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        private function questToReadable($quest)
        {
            $quest = substr($quest, 0, 100);
            if($quest == 'FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF0000FF00FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF'){
                return 0; //No quest
            } else if($quest == 'FAFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF000000FF00FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF'){
                return 1; //2ndQuestFinished
            } else if($quest == 'EAFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF000000FF00FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF'){
                return 2; //MarlonQuestFinished
            } else if($quest == 'AAFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF000000FF00FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF'){
                return 3; //DarkStoneFinished
            } else if($quest == 'AAEAFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF000000000000FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF'){
                return 4; //3rdQuestFinishedA
            } else if($quest == 'FFEAFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF000000000000FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF'){
                return 5; //3rdQuestFinishedB
            } else{
                return -1;
            }
        }

        public function getBaseStats($class, $server)
        {
            switch($class){
                case 0:
                case 1:
                case 2:
                case 3:
                case 7:
                    return $this->website->db('game', $server)->query('SELECT TOP 1 Strength, Dexterity, Vitality, Energy, Leadership FROM DefaultClassType WHERE Class = 0')->fetch();
                    break;
                case 16:
                case 17:
                case 18:
                case 19:
                case 23:
                    return $this->website->db('game', $server)->query('SELECT TOP 1 Strength, Dexterity, Vitality, Energy, Leadership FROM DefaultClassType WHERE Class = 16')->fetch();
                    break;
                case 32:
                case 33:
                case 34:
                case 35:
                case 39:
                    return $this->website->db('game', $server)->query('SELECT TOP 1 Strength, Dexterity, Vitality, Energy, Leadership FROM DefaultClassType WHERE Class = 32')->fetch();
                    break;
                case 48:
                case 49:
                case 50:
				case 51:
                case 54:
                    return $this->website->db('game', $server)->query('SELECT TOP 1 Strength, Dexterity, Vitality, Energy, Leadership FROM DefaultClassType WHERE Class = 48')->fetch();
                    break;
                case 64:
                case 65:
                case 66:
				case 67:
                case 70:
                    return $this->website->db('game', $server)->query('SELECT TOP 1 Strength, Dexterity, Vitality, Energy, Leadership FROM DefaultClassType WHERE Class = 64')->fetch();
                    break;
                case 80:
                case 81:
                case 82:
                case 83:
				case 84:
                case 87:
                    return $this->website->db('game', $server)->query('SELECT TOP 1 Strength, Dexterity, Vitality, Energy, Leadership FROM DefaultClassType WHERE Class = 80')->fetch();
                    break;
                case 96:
                case 97:
                case 98:
				case 99:
                case 102:
                    return $this->website->db('game', $server)->query('SELECT TOP 1 Strength, Dexterity, Vitality, Energy, Leadership FROM DefaultClassType WHERE Class = 96')->fetch();
                    break;
                case 112:
                case 114:
				case 115:
                case 118:
                    return $this->website->db('game', $server)->query('SELECT TOP 1 Strength, Dexterity, Vitality, Energy, Leadership FROM DefaultClassType WHERE Class = 112')->fetch();
                    break;
                case 128:
                case 129:
				case 130:
                case 131:
                case 135:
                    return $this->website->db('game', $server)->query('SELECT TOP 1 Strength, Dexterity, Vitality, Energy, Leadership FROM DefaultClassType WHERE Class = 128')->fetch();
                    break;
				case 144:	
                case 145:
                case 147:
				case 151:
                    return $this->website->db('game', $server)->query('SELECT TOP 1 Strength, Dexterity, Vitality, Energy, Leadership FROM DefaultClassType WHERE Class = 144')->fetch();
                    break;
            }
        }

        public function calculateNewStats()
        {
            $new_stats = 0;
            $this->defaultStats = $this->getBaseStats($this->char_info['Class'], $this->session->userdata(['user' => 'server']));
            $quest = $this->questToReadable($this->char_info['Quest']);
            if(defined('CUSTOM_RESET_STATS') && CUSTOM_RESET_STATS == true){
                if(in_array($this->char_info['Class'], [0, 1, 2, 3, 7, 16, 17, 18, 19, 23, 32, 33, 34, 35, 39, 80, 81, 82, 83, 87])){
                    if($this->char_info['resets'] < 1){
                        $new_stats = 5 * ($this->char_info['cLevel'] - 1);
                        if($quest == 1)
                            $new_stats += 20;
                        if($quest == 2 || $quest == 3)
                            $new_stats = 5 * 219 + 6 * ($this->char_info['cLevel'] - 220) + 20;
                        if(in_array($this->char_info['Class'], [2, 3, 7, 18, 19, 23, 34, 35, 39, 82, 83, 87]))
                            $new_stats = 5 * 219 + 6 * ($this->char_info['cLevel'] - 220) + 90;
                    } else if($this->char_info['resets'] >= 1){
                        $new_stats = $this->char_info['resets'] * 1995;
                        if($quest == 1)
                            $new_stats += 20;
                        if($quest == 2 || $quest == 3)
                            $new_stats = 2175 + ($this->char_info['resets'] - 1) * 2394 + 20 + $this->char_info['cLevel'] * 6;
                        if(in_array($this->char_info['Class'], [2, 3, 8, 18, 19, 23, 34, 35, 39, 82, 83, 87]))
                            $new_stats = 2175 + ($this->char_info['resets'] - 1) * 2394 + 90 + $this->char_info['cLevel'] * 6;
                    } else{
                        $new_stats = 404;
                    }
                } else{
                    if($this->char_info['resets'] < 1){
                        $new_stats = 7 * ($this->char_info['cLevel'] - 1);
                        if(in_array($this->char_info['Class'], [49, 50, 54, 65, 66, 70, 97, 98, 102, 114, 118]))
                            $new_stats = 7 * ($this->char_info['cLevel'] - 1) + 70;
                    } else if($this->char_info['resets'] >= 1){
                        $new_stats = $this->char_info['resets'] * 2793;
                        if(in_array($this->char_info['Class'], [49, 50, 54, 65, 66, 70, 97, 98, 102, 114, 118]))
                            $new_stats = $this->char_info['resets'] * 2793 + 70 + $this->char_info['cLevel'] * 7;
                    } else{
                        $new_stats = 404;
                    }
                }
            } else{
                if($this->char_info['Strength'] > $this->defaultStats['Strength']){
                    $new_stats += $this->char_info['Strength'] - $this->defaultStats['Strength'];
                }
                if($this->char_info['Dexterity'] > $this->defaultStats['Dexterity']){
                    $new_stats += $this->char_info['Dexterity'] - $this->defaultStats['Dexterity'];
                }
                if($this->char_info['Energy'] > $this->defaultStats['Energy']){
                    $new_stats += $this->char_info['Energy'] - $this->defaultStats['Energy'];
                }
                if($this->char_info['Vitality'] > $this->defaultStats['Vitality']){
                    $new_stats += $this->char_info['Vitality'] - $this->defaultStats['Vitality'];
                }
                if(in_array($this->char_info['Class'], [64, 65, 66, 67, 70]) && $this->char_info['Leadership'] > $this->defaultStats['Leadership']){
                    $new_stats += $this->char_info['Leadership'] - $this->defaultStats['Leadership'];
                }
            }
            return $new_stats;
        }

        public function reset_stats()
        {
            $stats = $this->calculateNewStats();
            if(defined('CUSTOM_RESET_STATS') && CUSTOM_RESET_STATS == true){
                $lvl_up = ':lvlUp';
            } else{
                $lvl_up = 'LevelUpPoint + :lvlUp';
            }
            if(in_array($this->char_info['Class'], [64, 65, 66, 67, 70])){
                $stmt = $this->website->db('game', $this->session->userdata(['user' => 'server']))->prepare('UPDATE Character SET LevelupPoint = ' . $lvl_up . ', Strength = ' . $this->defaultStats['Strength'] . ', Dexterity = ' . $this->defaultStats['Dexterity'] . ', Vitality = ' . $this->defaultStats['Vitality'] . ', Energy = ' . $this->defaultStats['Energy'] . ', Leadership = ' . $this->defaultStats['Leadership'] . ' WHERE Name = :char AND AccountId = :user');
            } else{
                $stmt = $this->website->db('game', $this->session->userdata(['user' => 'server']))->prepare('UPDATE Character SET LevelupPoint = ' . $lvl_up . ', Strength = ' . $this->defaultStats['Strength'] . ', Dexterity = ' . $this->defaultStats['Dexterity'] . ', Vitality = ' . $this->defaultStats['Vitality'] . ', Energy = ' . $this->defaultStats['Energy'] . ' WHERE Name = :char AND AccountId = :user');
            }
            $stmt->execute([':lvlUp' => $stats, ':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        public function reset_skill_tree($type, $reset_level = 1, $reset_points = 1, $points_multiplier = 1)
        {
            switch($type){
                default:
                    return false;
                    break;
                case 'scf':
                    return $this->reset_skill_tree_scf($reset_level, $reset_points, $points_multiplier);
                    break;
                case 'igcn':
                    return $this->reset_skill_tree_igcn($reset_level, $reset_points, $points_multiplier);
                    break;
                case 'muengine':
                    return $this->reset_skill_tree_muengine($reset_level, $reset_points, $points_multiplier);
                    break;
                case 'xteam':
                    return $this->reset_skill_tree_xteam($reset_level, $reset_points, $points_multiplier);
                    break;
                case 'zteam':
                    return $this->reset_skill_tree_zteam($reset_level, $reset_points, $points_multiplier);
                    break;
            }
        }

        private function reset_skill_tree_scf($reset_level = 1, $reset_points = 1, $points_multiplier = 1)
        {
            if($reset_level == 0){
                $this->ml_level = $this->get_master_level_scf();
            }
            if($reset_points == 0){
                $this->ml_points = ($this->get_master_level_scf() * $points_multiplier);
            }
            $stmt = $this->game_db->prepare('UPDATE Character SET SCFMasterPoints = ' . $this->ml_points . ', SCFMasterLevel = ' . $this->ml_level . ', SCFMasterSkill = NULL WHERE Name = :char AND AccountId = :user');
            return $stmt->execute([':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        public function reset_skill_tree_igcn($reset_level = 1, $reset_points = 1, $points_multiplier = 1)
        {
            if($reset_points == 0){
                $this->ml_points = ($this->get_master_level_igcn() * $points_multiplier);
            }
            if($reset_level == 0){
                $this->ml_level = $this->get_master_level_igcn();
                $ml_exp = '';
            } else{
                $ml_exp = ',  mlExperience = 0, mlNextExp = 35507050';
            }
			
			$query_enchancement = '';
			
			$skill_size = 6;
			$empty = 'ff0000';
			if(MU_VERSION >= 9){
				$enchancement_points = 0;
				if($this->ml_points > 400){
					$enchancement_points = $this->ml_points - 400;
					$this->ml_points = 400;
				}
				$query_enchancement = ', i4thSkillPoint = '.$enchancement_points.'';
				$skill_size = 10;
				$empty = 'ff00000000';
			}
			
            $this->get_skill_list();
            $skills_array = str_split($this->char_info['MagicList'], $skill_size);
            foreach($skills_array AS $key => $skill){
                $index = $this->skill_index($skill);
                if($this->is_master_skill($index)){
                    $skills_array[$key] = $empty;
                }
            }
            $stmt = $this->game_db->prepare('UPDATE Character SET mLevel = ' . $this->ml_level . $ml_exp . ', mlPoint = ' . $this->ml_points . ', MagicList = 0x' . implode('', $skills_array) . ' '.$query_enchancement.' WHERE Name = :char AND AccountId = :user');
            return $stmt->execute([':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        public function reset_skill_tree_zteam($reset_level = 1, $reset_points = 1, $points_multiplier = 1)
        {
            $this->set_zteam_skilltree_table();
            $this->get_skill_list();
            $skills_array = str_split($this->char_info['MagicList'], 6);
            foreach($skills_array AS $key => $skill){
                $index = $this->skill_index($skill);
                if($this->is_master_skill($index)){
                    $skills_array[$key] = 'ff0000';
                }
            }
            $stmt = $this->game_db->prepare('UPDATE Character SET MagicList = 0x' . implode('', $skills_array) . ' WHERE Name = :char');
            $stmt->execute([':char' => $this->website->c($this->vars['character'])]);
            if($reset_level == 0){
                $this->ml_level = $this->get_master_level_zteam();
                $ml_exp = '';
            } else{
                $ml_exp = ', ML_EXP = 0,  ML_NEXTEXP = 35507050';
            }
            if($reset_points == 0){
                $this->ml_points = ($this->get_master_level_zteam() * $points_multiplier);
            }
            $stmt = $this->game_db->prepare('UPDATE ' . $this->skillTreeTable . ' SET MASTER_LEVEL = ' . $this->ml_level . $ml_exp . ', ML_POINT = ' . $this->ml_points . ' WHERE CHAR_NAME = :char');
            return $stmt->execute([':char' => $this->website->c($this->vars['character'])]);
        }

        public function reset_skill_tree_muengine($reset_level = 1, $reset_points = 1, $points_multiplier = 1)
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET MagicList = CAST(REPLICATE(char(0xff), 180) AS varbinary(180)) WHERE Name = :char');
            $stmt->execute([':char' => $this->website->c($this->vars['character'])]);
            if($reset_points == 0){
                $this->ml_points = ($this->get_master_level_muengine() * $points_multiplier);
            }
            if($reset_level == 0){
                $this->ml_level = $this->get_master_level_muengine();
                $ml_exp = '';
            } else{
                $ml_exp = ', ML_EXP = 0,  ML_NEXTEXP = 35507050';
            }
            $stmt = $this->game_db->prepare('UPDATE T_MasterLevelSystem SET MASTER_LEVEL = ' . $this->ml_level . $ml_exp . ', ML_POINT = ' . $this->ml_points . ' WHERE CHAR_NAME = :char');
            return $stmt->execute([':char' => $this->website->c($this->vars['character'])]);
        }

        public function reset_skill_tree_xteam($reset_level = 1, $reset_points = 1, $points_multiplier = 1)
        {
            $this->get_skill_list();
            $this->get_skill_list_xteam();
            //$normal_skills_array = str_split($this->char_info['MagicList'], 6);
            //foreach($normal_skills_array AS $key => $skill){
           //     $normal_skills_array[$key] = 'FF0000';
           // }
           // $stmt = $this->game_db->prepare('UPDATE Character SET MagicList = 0x' . implode('', $normal_skills_array) . '  WHERE Name = :char');
            //$stmt->execute([':char' => $this->website->c($this->vars['character'])]);
            $skills_array = str_split($this->char_info['MasterSkill'], 6);
            foreach($skills_array AS $key => $skill){
                $skills_array[$key] = 'FF0000';
            }
            if($reset_points == 0){
                $this->ml_points = ($this->get_master_level_xteam() * $points_multiplier);
            }
            if($reset_level == 0){
                $this->ml_level = $this->get_master_level_xteam();
                $ml_exp = '';
            } else{
                $ml_exp = ', MasterExperience = 0';
            }
            $stmt = $this->game_db->prepare('UPDATE MasterSkillTree SET MasterLevel = ' . $this->ml_level . $ml_exp . ', MasterPoint = ' . $this->ml_points . ', MasterSkill =  0x' . implode('', $skills_array) . ' WHERE Name = :char');
            return $stmt->execute([':char' => $this->website->c($this->vars['character'])]);
        }

        private function get_master_level_scf()
        {
            $stmt = $this->game_db->prepare('SELECT SCFMasterLevel FROM Character WHERE Name = :char AND AccountId = :user');
            $stmt->execute([':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])]);
            $points = $stmt->fetch();
            return $points['SCFMasterLevel'];
        }

        private function set_zteam_skilltree_table()
        {
            $check = $this->game_db->snumrows('SELECT COUNT(*) AS count FROM information_schema.tables WHERE table_name = \'T_MasterLevelSystem\'');
            $this->skillTreeTable = ($check > 0) ? 'T_MasterLevelSystem' : 'T_SkillTree_Info';
        }

        private function get_master_level_zteam()
        {
            $stmt = $this->game_db->prepare('SELECT MASTER_LEVEL FROM ' . $this->skillTreeTable . ' WHERE CHAR_NAME = :char');
            $stmt->execute([':char' => $this->website->c($this->vars['character'])]);
            $points = $stmt->fetch();
            return $points['MASTER_LEVEL'];
        }

        private function get_master_level_muengine()
        {
            $stmt = $this->game_db->prepare('SELECT MASTER_LEVEL FROM T_MasterLevelSystem WHERE CHAR_NAME = :char');
            $stmt->execute([':char' => $this->website->c($this->vars['character'])]);
            $points = $stmt->fetch();
            return $points['MASTER_LEVEL'];
        }

        private function get_master_level_igcn()
        {
            $stmt = $this->game_db->prepare('SELECT mLevel FROM Character WHERE AccountId = :user AND Name = :char');
            $stmt->execute([':user' => $this->session->userdata(['user' => 'username']), ':char' => $this->website->c($this->vars['character'])]);
            $points = $stmt->fetch();
            if($points != false){
                return $points['mLevel'];
            }
            return 0;
        }

        private function get_master_level_xteam()
        {
            $this->skillTreeTable = 'MasterSkillTree';
            $stmt = $this->game_db->prepare('SELECT MasterLevel FROM ' . $this->skillTreeTable . ' WHERE Name = :char');
            $stmt->execute([':char' => $this->website->c($this->vars['character'])]);
            $points = $stmt->fetch();
            return $points['MasterLevel'];
        }

        private function is_master_skill($skill_id)
        {
            static $SkillList = null;
            $is_master_skill = false;
            libxml_use_internal_errors(true);
            if($SkillList == null)
                $SkillList = simplexml_load_file(APP_PATH . DS . 'data' . DS . 'SkillList.xml');
            if($SkillList === false){
                $error = 'Failed loading XML<br>';
                foreach(libxml_get_errors() as $error){
                    $error .= $error->message . '<br>';
                }
                writelog('[Server File Parser] Unable to parse xml file: ' . $error, 'system_error');
            }
            $skill_data = $SkillList->xpath("//SkillList/Skill[@Index='" . $skill_id . "']");
            if(!empty($skill_data)){
                if(in_array((string)$skill_data[0]->attributes()->UseType, [3,4,7,8,9,10,11])){
                    $is_master_skill = true;
                }
            }
            return $is_master_skill;
        }

        private function skill_index($hex)
        {
            $id = hexdec(substr($hex, 0, 2));
            $id2 = hexdec(substr($hex, 2, 2));
            $id3 = hexdec(substr($hex, 4, 2));
            if(($id2 & 7) > 0){
                $id = $id * ($id2 & 7) + $id3;
            }
            return $id;
        }


        private function get_skill_list()
        {
            $sql = (DRIVER == 'pdo_odbc') ? 'MagicList' : 'CONVERT(IMAGE, MagicList) AS MagicList';
            $stmt = $this->game_db->prepare('SELECT ' . $sql . ' FROM Character WHERE Name = :char AND AccountId = :user');
            $stmt->execute([':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])]);
            if(DRIVER == 'pdo_dblib'){
                $skills = unpack('H*', implode('', $stmt->fetch()));
                $this->char_info['MagicList'] = $this->clean_hex($skills[1]);
            } else{
                if($skills = $stmt->fetch()){
                    $this->char_info['MagicList'] = $this->clean_hex($skills['MagicList']);
                }
            }
        }

        private function get_skill_list_xteam()
        {
            $sql = (DRIVER == 'pdo_odbc') ? 'MasterSkill' : 'CONVERT(IMAGE, MasterSkill) AS MasterSkill';
            $stmt = $this->game_db->prepare('SELECT ' . $sql . ' FROM MasterSkillTree WHERE Name = :char');
            $stmt->execute([':char' => $this->website->c($this->vars['character'])]);
            if(DRIVER == 'pdo_dblib'){
                $skills = unpack('H*', implode('', $stmt->fetch()));
                $this->char_info['MasterSkill'] = $this->clean_hex($skills[1]);
            } else{
                if($skills = $stmt->fetch()){
                    $this->char_info['MasterSkill'] = $this->clean_hex($skills['MasterSkill']);
                }
            }
        }

        public function check_pk()
        {
            if($this->char_info['PkLevel'] <= 3){
                return false;
            }
            return true;
        }

        public function clear_pk($money = 0)
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET PkLevel = 3, PkCount = 0, Money = Money - :money WHERE Name = :char AND AccountId = :user');
            $stmt->execute([':money' => $money, ':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        public function teleport_char()
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET MapNumber = :world, MapPosX = :x, MapPosY = :y WHERE Name = :char AND AccountId = :user');
            $stmt->execute([':world' => $this->vars['world'], ':x' => $this->vars['teleport'][0], ':y' => $this->vars['teleport'][1], ':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        public function update_level()
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET cLevel = :new_level WHERE Name = :char AND AccountId = :user');
            $stmt->execute([':new_level' => $this->char_info['cLevel'] + (int)$this->vars['level'], ':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])]);
            return true;
        }

        public function update_points()
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET LevelUpPoint = LevelUpPoint + :new_point WHERE Name = :char AND AccountId = :user');
            $stmt->execute([':new_point' => (int)$this->vars['points'], ':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])]);
            return true;
        }

        public function update_gm()
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET CtlCode = :ctlcode WHERE Name = :char AND AccountId = :user');
            $stmt->execute([':ctlcode' => $this->config->config_entry('buygm|gm_ctlcode'), ':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])]);
            return true;
        }

        public function teleports($id)
        {
            $teleports = [0 => [125, 125], 1 => [232, 126], 2 => [211, 40], 3 => [175, 112], 4 => [209, 71], 6 => [64, 116], 7 => [24, 19], 8 => [187, 65], 10 => [15, 13], 30 => [93, 37], 33 => [82, 8], 34 => [120, 8]];
            if(array_key_exists($id, $teleports)){
                $this->vars['teleport'] = $teleports[$id];
                return true;
            }
            return false;
        }

        public function add_wcoins($amount = 0, $config = [])
        {
            $acc = (in_array($config['identifier_column'], ['MemberGuid', 'memb_guid'])) ? $this->session->userdata(['user' => 'id']) : $this->session->userdata(['user' => 'username']);
            $stmt = $this->website->db($config['db'], $this->session->userdata(['user' => 'server']))->prepare('UPDATE ' . $config['table'] . ' SET ' . $config['column'] . ' = ' . $config['column'] . ' + :wcoins WHERE ' . $config['identifier_column'] . ' = :account');
            $stmt->execute([':wcoins' => $amount, ':account' => $acc]);
            if($stmt->rows_affected() == 0){
                $stmt = $this->website->db($config['db'], $this->session->userdata(['user' => 'server']))->prepare('INSERT INTO ' . $config['table'] . ' (' . $config['identifier_column'] . ', ' . $config['column'] . ') values (:user, :wcoins)');
                $stmt->execute([':user' => $acc, ':wcoins' => $amount]);
            }
        }

        public function remove_wcoins($config = [], $amount = false)
        {
			if($amount != false){
				$this->vars['credits'] = $amount;
			}
            $acc = (in_array($config['identifier_column'], ['MemberGuid', 'memb_guid'])) ? $this->session->userdata(['user' => 'id']) : $this->session->userdata(['user' => 'username']);
            $stmt = $this->website->db($config['db'], $this->session->userdata(['user' => 'server']))->prepare('UPDATE ' . $config['table'] . ' SET ' . $config['column'] . ' = ' . $config['column'] . ' - :wcoins WHERE ' . $config['identifier_column'] . ' = :account');
            $stmt->execute([':wcoins' => $this->vars['credits'], ':account' => $acc]);
        }

        public function get_wcoins($config = [], $server)
        {
            $acc = (in_array($config['identifier_column'], ['MemberGuid', 'memb_guid'])) ? $this->session->userdata(['user' => 'id']) : $this->session->userdata(['user' => 'username']);
            $stmt = $this->website->db($config['db'], $server)->prepare('SELECT ' . $config['column'] . ' FROM ' . $config['table'] . ' WHERE ' . $config['identifier_column'] . ' = :account');
            $stmt->execute([':account' => $acc]);
            if($wcoins = $stmt->fetch()){
                return $wcoins[$config['column']];
            }
            return false;
        }

        public function check_reward()
        {
            $stmt = $this->website->db('web')->prepare('SELECT account FROM DmN_Rewards WHERE account = :account AND server = :server');
            $stmt->execute([':account' => $this->session->userdata(['user' => 'username']), ':server' => $this->session->userdata(['user' => 'server'])]);
            if($stmt->fetch()){
                return true;
            }
            return false;
        }

        public function log_reward()
        {
            $stmt = $this->website->db('web')->prepare('INSERT INTO DmN_Rewards (account, server) VALUES (:account, :server)');
            $stmt->execute([':account' => $this->session->userdata(['user' => 'username']), ':server' => $this->session->userdata(['user' => 'server'])]);
        }

        public function add_reward()
        {
            $db = ($this->config->config_entry('account|wcoin_db') == 'account') ? $this->account_db : $this->game_db;
            $acc = (in_array($this->config->config_entry('account|wcoin_account_column'), ['MemberGuid', 'memb_guid'])) ? $this->session->userdata(['user' => 'id']) : $this->session->userdata(['user' => 'username']);
            $stmt = $db->prepare('UPDATE ' . $this->config->config_entry('account|wcoin_table') . ' SET ' . $this->config->config_entry('account|wcoin_coin_column') . ' = ' . $this->config->config_entry('account|wcoin_coin_column') . ' + :wcoins WHERE ' . $this->config->config_entry('account|wcoin_account_column') . ' = :account');
            $stmt->execute([':wcoins' => 450, ':account' => $acc]);
        }

        private function add_bonus_credits($type, $amount)
        {
            $this->website->add_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $amount, $type);
        }

        private function show65kStats($stat_value)
        {
            return ($stat_value < 0) ? $stat_value += 65536 : $stat_value;
        }

        public function load_character_info($char, $server = '', $by_id = false)
        {
            $where = ($by_id == true) ? $this->website->get_char_id_col($server) .'  = :char' : 'Name = :char';
            $stmt = $this->game_db->prepare('SELECT TOP 1 AccountId, Name, Money, Class, cLevel, ' . $this->reset_column($server) . $this->greset_column($server) . ' LevelUpPoint, Strength, Dexterity, Vitality, Energy, Leadership, MapNumber, MapPosX, MapPosY, PkLevel, PkCount, CtlCode FROM Character WHERE ' . $where . '');
            $stmt->execute([':char' => $this->website->c($char)]);
            if($this->char_info = $stmt->fetch()){
                $this->char_info['mlevel'] = $this->load_master_level($this->char_info['Name'], $server);
                $this->get_inventory_content($char, $server);
                return true;
            }
            return false;
        }

        public function get_inventory_content($char, $server = '')
        {
            $server = ($server == '') ? $this->session->userdata(['user' => 'server']) : $server;
            if(DRIVER == 'pdo_dblibs'){
                $items_sql = '';
                for($i = 0; $i < ($this->website->get_value_from_server($server, 'inv_size') / $this->website->get_value_from_server($server, 'inv_multiplier')); ++$i){
                    $multiplier = ($i == 0) ? 1 : ($i * $this->website->get_value_from_server($server, 'inv_multiplier')) + 1;
                    $items_sql .= 'SUBSTRING(Inventory, ' . $multiplier . ', ' . $this->website->get_value_from_server($server, 'inv_multiplier') . ') AS item' . $i . ', ';
                }
                $stmt = $this->website->db('game', $server)->prepare('SELECT ' . substr($items_sql, 0, -2) . ' FROM Character WHERE Name = :char');
                $stmt->execute([':char' => $this->website->c($char)]);
                $items = unpack('H*', implode('', $stmt->fetch()));
                $this->char_info['Inventory'] = $this->clean_hex($items[1]);
            } else{
                $sql = (DRIVER == 'pdo_odbc') ? 'Inventory' : 'CONVERT(IMAGE, Inventory) AS Inventory';
                $stmt = $this->website->db('game', $server)->prepare('SELECT ' . $sql . ' FROM Character WHERE Name = :char');
                $stmt->execute([':char' => $this->website->c($char)]);
                if($inv = $stmt->fetch()){
                    if(in_array(DRIVER, ['sqlsrv', 'pdo_sqlsrv', 'pdo_dblib'])){
						$unpack = unpack('H*', $inv['Inventory']);
						$this->char_info['Inventory'] = $this->clean_hex($unpack[1]);
					}
					else{
						$this->char_info['Inventory'] = $this->clean_hex($inv['Inventory']);
					}
                }
            }
        }

        public function load_equipment($server = '')
        {
            $items_array = str_split($this->Mcharacter->char_info['Inventory'], $this->website->get_value_from_server($server, 'item_size'));
            $eq = array_chunk($items_array, 12);
            $equipment = [];
            foreach($eq[0] as $key => $item){
                if($item != str_pad("", $this->website->get_value_from_server($server, 'item_size'), "F")){
                    $this->iteminfo->itemData($item);
                    $equipment[$key]['item_id'] = $this->iteminfo->id;
                    $equipment[$key]['item_cat'] = $this->iteminfo->type;
                    $equipment[$key]['name'] = $this->iteminfo->realName();
                    $equipment[$key]['level'] = (int)substr($this->iteminfo->getLevel(), 1);
                    $equipment[$key]['hex'] = $item;
                } else{
                    $equipment[$key] = 0;
                }
            }
			if(isset($items_array[236]) && $items_array[236] != str_pad("", $this->website->get_value_from_server($server, 'item_size'), "F")){
                $this->iteminfo->itemData($items_array[236]);
                $equipment[12]['item_id'] = $this->iteminfo->id;
                $equipment[12]['item_cat'] = $this->iteminfo->type;
                $equipment[12]['name'] = $this->iteminfo->realName();
                $equipment[12]['level'] = (int)substr($this->iteminfo->getLevel(), 1);
                $equipment[12]['hex'] = $items_array[236];
            }
            if(isset($items_array[237]) && $items_array[237] != str_pad("", $this->website->get_value_from_server($server, 'item_size'), "F")){
                $this->iteminfo->itemData($items_array[237]);
                $equipment[13]['item_id'] = $this->iteminfo->id;
                $equipment[13]['item_cat'] = $this->iteminfo->type;
                $equipment[13]['name'] = $this->iteminfo->realName();
                $equipment[13]['level'] = (int)substr($this->iteminfo->getLevel(), 1);
                $equipment[13]['hex'] = $items_array[237];
            }
            if(isset($items_array[238]) && $items_array[238] != str_pad("", $this->website->get_value_from_server($server, 'item_size'), "F")){
                $this->iteminfo->itemData($items_array[238]);
                $equipment[14]['item_id'] = $this->iteminfo->id;
                $equipment[14]['item_cat'] = $this->iteminfo->type;
                $equipment[14]['name'] = $this->iteminfo->realName();
                $equipment[14]['level'] = (int)substr($this->iteminfo->getLevel(), 1);
                $equipment[14]['hex'] = $items_array[238];
            }													   
            return $equipment;
        }

        public function load_inventory($inv = 1, $server = '')
        {
            $server = ($server == '') ? $this->session->userdata(['user' => 'server']) : $server;
            $items_array = str_split($this->Mcharacter->char_info['Inventory'], $this->website->get_value_from_server($server, 'item_size'));
            $inventory = [];
            $items = [];
            $loop = [12, 76]; //default inv
            if($inv == 2)
                $loop = [76, 108]; //store
            if($inv == 3)
                $loop = [108, 140]; //exp inv 1
            if($inv == 4)
                $loop = [204, 236]; //exp inv 2
            for($a = $loop[0]; $a < $loop[1]; $a++){
                $inventory[$a] = !empty($items_array[$a]) ? $items_array[$a] : str_pad("", $this->website->get_value_from_server($server, 'item_size'), "F");
            }
            $i = 0;
            $x = 0;
            $y = 0;
            foreach($inventory as $item){
                $i++;
                if($item != str_pad("", $this->website->get_value_from_server($server, 'item_size'), "F")){
                    $this->iteminfo->itemData($item);
                    $items[$i]['item_id'] = $this->iteminfo->id;
                    $items[$i]['item_cat'] = $this->iteminfo->type;
                    $items[$i]['name'] = $this->iteminfo->realName();
                    $items[$i]['level'] = (int)substr($this->iteminfo->getLevel(), 1);
                    $items[$i]['x'] = $this->iteminfo->getX();
                    $items[$i]['y'] = $this->iteminfo->getY();
                    $items[$i]['xx'] = $x;
                    $items[$i]['yy'] = $y;
                    $items[$i]['hex'] = $item;
                } else{
                    $items[$i]['xx'] = $x;
                    $items[$i]['yy'] = $y;
                }
                $x++;
                if($x >= 8){
                    $x = 0;
                    $y++;
                }
            }
            return $items;
        }

        public function clear_inv()
        {
            $items_array = str_split($this->Mcharacter->char_info['Inventory'], $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
            if(isset($this->vars['inventory'])){
                for($a = 12; $a < 76; $a++){
                    $items_array[$a] = str_repeat('F', $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
                }
            }
            if(isset($this->vars['equipment'])){
                for($a = 0; $a < 12; $a++){
                    $items_array[$a] = str_repeat('F', $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
                }
            }
            if(isset($this->vars['store'])){
                for($a = 204; $a < 236; $a++){
                    $items_array[$a] = str_repeat('F', $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
                }
            }
            if(isset($this->vars['exp_inv_1'])){
                for($a = 76; $a < 108; $a++){
                    $items_array[$a] = str_repeat('F', $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
                }
            }
            if(isset($this->vars['exp_inv_2'])){
                for($a = 108; $a < 140; $a++){
                    $items_array[$a] = str_repeat('F', $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
                }
            }
            $stmt = $this->game_db->prepare('UPDATE Character SET Inventory = 0x' . implode('', $items_array) . ' WHERE Name = :char AND AccountId = :user');
            $stmt->execute([':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        public function check_equipment()
        {
            return (strtoupper(substr($this->Mcharacter->char_info['Inventory'], 0, $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size') * 12)) === str_repeat('F', $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size') * 12));
        }

        public function gen_class_select_field($config = false)
        {
            if($config){
                if(isset($config[$this->char_info['Class']]) && array_key_exists($this->char_info['Class'], $config)){
                    $select = '<option disabled="disabled" selected="selected" value="">--SELECT--</option>';
                    foreach($config[$this->char_info['Class']] AS $class){
                        $select .= '<option value="' . $class . '">' . $this->website->get_char_class($class) . '</option>';
                    }
                    return $select;
                }
            }
            return false;
        }

        public function update_char_class()
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET Class = :class WHERE Name = :char AND AccountId = :user');
            $stmt->execute([':class' => $this->website->c($this->vars['class_select']), ':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])]);
            return true;
        }

        public function get_status($name, $server)
        {
            $accountDb = ($this->website->is_multiple_accounts() == true) ? $this->website->get_db_from_server($server, true) : $this->website->get_default_account_database();
            $stmt = $this->website->db('game', $server)->prepare('SELECT TOP 1 a.Id, a.GameIDC, m.ConnectStat, m.ConnectTM, m.DisConnectTM, m.IP FROM AccountCharacter AS a RIGHT JOIN [' . $accountDb . '].dbo.MEMB_STAT AS m ON (a.Id Collate Database_Default = m.memb___id) WHERE m.memb___id = :user ' . $this->website->server_code($this->website->get_servercode($server)) . '');
            $stmt->execute([':user' => $this->website->c($name)]);
            return $stmt->fetch();
        }

        public function load_chars($name)
        {
            $stmt = $this->game_db->prepare('SELECT Name FROM Character WHERE AccountId = :user');
            $stmt->execute([':user' => $this->website->c($name)]);
            return $stmt->fetch_all();
        }

        public function check_guild($name)
        {
            $stmt = $this->game_db->prepare('SELECT G_Name FROM GuildMember WHERE Name = :char');
            $stmt->execute([':char' => $this->website->c($name)]);
            return $stmt->fetch();
        }

        public function load_guild_info($g_name)
        {
            $stmt = $this->game_db->prepare('SELECT G_Mark, G_Master FROM Guild WHERE G_Name = :g_name');
            $stmt->execute([':g_name' => $this->website->c($g_name)]);
            return $stmt->fetch();
        }

        public function guild_member_count($g_name)
        {
            $stmt = $this->game_db->prepare('SELECT COUNT(Name) AS count FROM GuildMember WHERE G_Name = :g_name');
            $stmt->execute([':g_name' => $this->website->c($g_name)]);
            return $stmt->fetch();
        }

        public function check_hidden_char($name, $server)
        {
            $stmt = $this->website->db('web')->prepare('SELECT until_date FROM DmN_Hidden_Chars WHERE account = :name AND server = :server');
            $stmt->execute([':name' => $this->website->c($name), ':server' => $this->website->c($server)]);
            if($info = $stmt->fetch()){
                if($info['until_date'] > time()){
                    return true;
                } else{
                    $this->delete_expired_hide($name, $server);
                    return false;
                }
            } else{
                return false;
            }
        }

        public function delete_expired_hide($name, $server)
        {
            $stmt = $this->website->db('web')->prepare('DELETE FROM DmN_Hidden_Chars WHERE account = :name AND server = :server');
            $stmt->execute([':name' => $this->website->c($name), ':server' => $this->website->c($server)]);
        }

        public function add_account_log($log, $credits, $acc, $server)
        {
            $stmt = $this->website->db('web')->prepare('INSERT INTO DmN_Account_Logs (text, amount, date, account, server, ip) VALUES (:text, :amount, GETDATE(), :acc, :server, :ip)');
            $stmt->execute([':text' => $log, ':amount' => round($credits), ':acc' => $acc, ':server' => $server, ':ip' => $this->website->ip()]);
            $stmt->close_cursor();
        }

        public function get_guild_info($guild, $server)
        {
            $stmt = $this->game_db->prepare('SELECT G_Name, G_Master, G_Mark, G_Score, Number, G_Union FROM Guild WHERE G_Name = :name');
            $stmt->execute([':name' => $this->website->c($guild)]);
            if($row = $stmt->fetch()){
                $membercount = $this->game_db->snumrows('SELECT COUNT(Name) as count FROM GuildMember WHERE G_Name = \'' . $this->game_db->sanitize_var($this->website->c($guild)) . '\'');
                $union = '';
                $hostility = '';
                if($row['G_Union'] != 0){
                    $stmt = $this->game_db->prepare('SELECT G_Name FROM Guild WHERE G_Union = :number AND G_Name != :name');
                    $stmt->execute([':number' => $row['G_Union'], ':name' => $guild]);
                    while($row2 = $stmt->fetch()){
                        $union .= '<a href="' . $this->config->base_url . 'info/guild/' . bin2hex($row2['G_Name']) . '/' . $server . '">' . $row2['G_Name'] . '</a>,';
                    }
                }
                return [
					'G_Name' => $row['G_Name'], 
					'G_Master' => $row['G_Master'], 
					'G_Mark' => urlencode(bin2hex($row['G_Mark'])), 
					'G_Score' => (int)$row['G_Score'], 
					'MemberCount' => $membercount, 
					'aliance_guilds' => ($union != '') ? substr($union, 0, -1) : 'N/A'
				];
            }
            return false;
        }
		
		private function checkStatus($acc, $server)
        {
			$stmt = $this->website->db('account', $server)->prepare('SELECT ConnectStat FROM MEMB_STAT WHERE memb___id = :user');
			$stmt->execute([':user' => $acc]);
			if($status = $stmt->fetch()){
				return ($status['ConnectStat'] == 0);
			}
			return true;
        }

        public function get_guild_members($guild, $server)
        {
			
            $stmt = $this->game_db->prepare('SELECT g.Name, g.G_Status, c.Class, c.cLevel, c.AccountId, ' . $this->reset_column($server) . ' ' . substr_replace($this->greset_column($server), '', -1) . ' FROM GuildMember AS g INNER JOIN Character AS c ON (g.Name Collate Database_Default = c.Name Collate Database_Default) WHERE g.G_Name = :name ORDER BY g.G_Status DESC');
            $stmt->execute([':name' => $this->website->c($guild)]);
            while($row = $stmt->fetch()){
				$status = $this->checkStatus($row['AccountId'], $server);
                $this->guild_info[] = [
					'name' => $row['Name'], 
					'position' => $this->website->get_guild_status($row['G_Status']), 
					'level' => $row['cLevel'], 
					'resets' => $row['resets'], 
					'gresets' => $row['grand_resets'], 
					'class' => $this->website->get_char_class($row['Class'], true),
					'status' => ($status == false) ? 1 : 0
					
				];
            }
            return $this->guild_info;
        }

        public function decrease_zen($account, $money, $char)
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET Money = Money - :money WHERE AccountId = :account AND Name = :char');
            return $stmt->execute([':money' => (int)$money, ':account' => $this->website->c($account), ':char' => $this->website->c($char)]);
        }

        public function add_zen($account, $money, $char)
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET Money = Money + :money WHERE AccountId = :account AND Name = :char');
            return $stmt->execute([':money' => (int)$money, ':account' => $this->website->c($account), ':char' => $this->website->c($char)]);
        }

        public function load_chars_from_ref($ref_acc, $server)
        {
            $stmt = $this->website->db('game', $server)->prepare('SELECT Name, cLevel, ' . $this->reset_column($server) . substr_replace($this->greset_column($server), '', -1) . ' FROM Character WHERE AccountId = :ref_acc');
            $stmt->execute([':ref_acc' => $this->website->c($ref_acc)]);
            $char_list = [];
            while($row = $stmt->fetch()){
                $char_list[] = ['Name' => $row['Name'], 'cLevel' => $row['cLevel'], 'resets' => $row['resets'], 'grand_resets' => $row['grand_resets'], 'mlevel' => $this->load_master_level($row['Name'], $server)];
            }
            return $char_list;
        }

        public function load_master_level($char, $server)
        {
            if($this->config->values('table_config', [$server, 'master_level', 'column']) != false){
                $stmt = $this->website->db('game', $server)->prepare('SELECT ' . $this->config->values('table_config', [$server, 'master_level', 'column']) . ' AS mlevel FROM ' . $this->config->values('table_config', [$server, 'master_level', 'table']) . ' WHERE ' . $this->config->values('table_config', [$server, 'master_level', 'identifier_column']) . ' = :char');
                $stmt->execute([':char' => $char]);
                $mlevel = $stmt->fetch();
                $stmt->close_cursor();
                if($mlevel){
                    return $mlevel['mlevel'];
                }
            }
            return 0;
        }

        public function check_if_char_exists($char)
        {
            $stmt = $this->game_db->prepare('SELECT Name, AccountId FROM Character WHERE Name = :name');
            $stmt->execute([':name' => $char]);
            return $stmt->fetch();
        }

        public function has_guild($char)
        {
            $stmt = $this->game_db->prepare('SELECT Name FROM GuildMember WHERE Name = :name');
            $stmt->execute([':name' => $char]);
            return $stmt->fetch();
        }

        public function update_account_character($old, $new)
        {
            return $this->game_db->query('
									UPDATE AccountCharacter SET 
									GameIDC = CASE WHEN (GameIDC = \'' . $this->game_db->sanitize_var($old) . '\') THEN \'' . $this->game_db->sanitize_var($new) . '\' ELSE GameIDC END,
									GameId1 = CASE WHEN (GameId1 = \'' . $this->game_db->sanitize_var($old) . '\') THEN \'' . $this->game_db->sanitize_var($new) . '\' ELSE GameId1 END,
									GameId2 = CASE WHEN (GameId2 = \'' . $this->game_db->sanitize_var($old) . '\') THEN \'' . $this->game_db->sanitize_var($new) . '\' ELSE GameId2 END,
									GameId3 = CASE WHEN (GameId3 = \'' . $this->game_db->sanitize_var($old) . '\') THEN \'' . $this->game_db->sanitize_var($new) . '\' ELSE GameId3 END,
									GameId4 = CASE WHEN (GameId4 = \'' . $this->game_db->sanitize_var($old) . '\') THEN \'' . $this->game_db->sanitize_var($new) . '\' ELSE GameId4 END,
									GameId5 = CASE WHEN (GameId5 = \'' . $this->game_db->sanitize_var($old) . '\') THEN \'' . $this->game_db->sanitize_var($new) . '\' ELSE GameId5 END
								');
        }

        public function update_guild($old, $new)
        {
            $stmt = $this->game_db->prepare('UPDATE Guild SET G_Master = :name WHERE G_Master = :old_name');
            return $stmt->execute([':name' => $new, ':old_name' => $old]);
        }

        public function update_guild_member($old, $new)
        {
            $stmt = $this->game_db->prepare('UPDATE GuildMember SET Name = :name WHERE Name = :old_name');
            return $stmt->execute([':name' => $new, ':old_name' => $old]);
        }

        public function update_character($old, $new)
        {
            $stmt = $this->game_db->prepare('UPDATE Character SET Name = :name WHERE Name = :old_name');
            return $stmt->execute([':name' => $new, ':old_name' => $old]);
        }

        public function update_option_data($old, $new)
        {
            $stmt = $this->game_db->prepare('UPDATE OptionData SET Name = :name WHERE Name = :old_name');
            return $stmt->execute([':name' => $new, ':old_name' => $old]);
        }

        public function update_t_friendlist($old, $new)
        {
            $stmt = $this->game_db->prepare('UPDATE T_FriendList SET FriendName = :name WHERE FriendName = :old_name');
            return $stmt->execute([':name' => $new, ':old_name' => $old]);
        }

        public function update_t_friendmail($old, $new)
        {
            $stmt = $this->game_db->prepare('UPDATE T_FriendMail SET FriendName = :name WHERE FriendName = :old_name');
            return $stmt->execute([':name' => $new, ':old_name' => $old]);
        }

        public function update_t_cguid($old, $new)
        {
            $stmt = $this->game_db->prepare('UPDATE T_CGuid SET Name = :name WHERE Name = :old_name');
            return $stmt->execute([':name' => $new, ':old_name' => $old]);
        }
		
		public function update_T_CurCharName($old, $new)
        {
			if($this->game_db->check_table('T_CurCharName') > 0){
				$stmt = $this->game_db->prepare('UPDATE T_CurCharName SET Name = :name WHERE Name = :old_name');
				return $stmt->execute([':name' => $new, ':old_name' => $old]);
			}
			else{
                return;
            }
        }
		
		public function update_T_Event_Inventory($old, $new)
        {
			if($this->game_db->check_table('T_Event_Inventory') > 0){
				$stmt = $this->game_db->prepare('UPDATE T_Event_Inventory SET Name = :name WHERE Name = :old_name');
				return $stmt->execute([':name' => $new, ':old_name' => $old]);
			}
			else{
                return;
            }
        }

        public function update_master_level_table($old, $new, $server)
        {
            $stmt = $this->game_db->prepare('UPDATE ' . $this->config->values('table_config', [$server, 'master_level', 'table']) . ' SET ' . $this->config->values('table_config', [$server, 'master_level', 'identifier_column']) . ' = :name WHERE ' . $this->config->values('table_config', [$server, 'master_level', 'identifier_column']) . ' = :old_name');
            return $stmt->execute([':name' => $new, ':old_name' => $old]);
        }

        public function update_IGC_Gens($old, $new)
        {
            if($this->game_db->check_table('IGC_Gens') > 0){
                $stmt = $this->game_db->prepare('UPDATE IGC_Gens SET Name = :name WHERE Name = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }

        public function update_IGC_GensAbuse($old, $new)
        {
            if($this->game_db->check_table('IGC_GensAbuse') > 0){
                $stmt = $this->game_db->prepare('UPDATE IGC_GensAbuse SET Name = :name WHERE Name = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }
		
		public function update_IGC_GremoryCase($old, $new)
        {
            if($this->game_db->check_table('IGC_GremoryCase') > 0){
                $stmt = $this->game_db->prepare('UPDATE IGC_GremoryCase SET Name = :name WHERE Name = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }
		
		public function update_IGC_HuntingRecord($old, $new)
        {
            if($this->game_db->check_table('IGC_HuntingRecord') > 0){
                $stmt = $this->game_db->prepare('UPDATE IGC_HuntingRecord SET Name = :name WHERE Name = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }
		
		public function update_IGC_HuntingRecordOption($old, $new)
        {
            if($this->game_db->check_table('IGC_HuntingRecordOption') > 0){
                $stmt = $this->game_db->prepare('UPDATE IGC_HuntingRecordOption SET Name = :name WHERE Name = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }
		
		public function update_IGC_LabyrinthClearLog($old, $new)
        {
            if($this->game_db->check_table('IGC_LabyrinthClearLog') > 0){
                $stmt = $this->game_db->prepare('UPDATE IGC_LabyrinthClearLog SET Name = :name WHERE Name = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }
		
		public function update_IGC_LabyrinthInfo($old, $new)
        {
            if($this->game_db->check_table('IGC_LabyrinthInfo') > 0){
                $stmt = $this->game_db->prepare('UPDATE IGC_LabyrinthInfo SET Name = :name WHERE Name = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }
		
		public function update_IGC_LabyrinthLeagueLog($old, $new)
        {
            if($this->game_db->check_table('IGC_LabyrinthLeagueLog') > 0){
                $stmt = $this->game_db->prepare('UPDATE IGC_LabyrinthLeagueLog SET Name = :name WHERE Name = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }
		
		public function update_IGC_LabyrinthLeagueUser($old, $new)
        {
            if($this->game_db->check_table('IGC_LabyrinthLeagueUser') > 0){
                $stmt = $this->game_db->prepare('UPDATE IGC_LabyrinthLeagueUser SET Name = :name WHERE Name = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }
		
		public function update_IGC_LabyrinthMissionInfo($old, $new)
        {
            if($this->game_db->check_table('IGC_LabyrinthMissionInfo') > 0){
                $stmt = $this->game_db->prepare('UPDATE IGC_LabyrinthMissionInfo SET Name = :name WHERE Name = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }
		
		public function update_IGC_MixLostItemInfo($old, $new)
        {
            if($this->game_db->check_table('IGC_MixLostItemInfo') > 0){
                $stmt = $this->game_db->prepare('UPDATE IGC_MixLostItemInfo SET Name = :name WHERE Name = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }
		
		public function update_IGC_Muun_Inventory($old, $new)
        {
            if($this->game_db->check_table('IGC_Muun_Inventory') > 0){
                $stmt = $this->game_db->prepare('UPDATE IGC_Muun_Inventory SET Name = :name WHERE Name = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }
		
		public function update_IGC_RestoreItem_Inventory($old, $new)
        {
            if($this->game_db->check_table('IGC_RestoreItem_Inventory') > 0){
                $stmt = $this->game_db->prepare('UPDATE IGC_RestoreItem_Inventory SET Name = :name WHERE Name = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }
		
        public function update_IGC_PeriodBuffInfo($old, $new)
        {
            if($this->game_db->check_table('IGC_PeriodBuffInfo') > 0){
                $stmt = $this->game_db->prepare('UPDATE IGC_PeriodBuffInfo SET CharacterName = :name WHERE CharacterName = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }

        public function update_IGC_PeriodExpiredItemInfo($old, $new)
        {
            if($this->game_db->check_table('IGC_PeriodExpiredItemInfo') > 0){
                $stmt = $this->game_db->prepare('UPDATE IGC_PeriodExpiredItemInfo SET CharacterName = :name WHERE CharacterName = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }

        public function update_IGC_PeriodItemInfo($old, $new)
        {
            if($this->game_db->check_table('IGC_PeriodItemInfo') > 0){
                $stmt = $this->game_db->prepare('UPDATE IGC_PeriodItemInfo SET CharacterName = :name WHERE CharacterName = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }
		
		 public function update_IGC_PentagramInfo($old, $new)
        {
            if($this->game_db->check_table('IGC_PentagramInfo') > 0){
                $stmt = $this->game_db->prepare('UPDATE IGC_PentagramInfo SET Name = :name WHERE Name = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }

        public function update_T_3rd_Quest_Info($old, $new)
        {
            if($this->game_db->check_table('T_3rd_Quest_Info') > 0){
                $stmt = $this->game_db->prepare('UPDATE T_3rd_Quest_Info SET CHAR_NAME = :name WHERE CHAR_NAME = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }

        public function update_T_GMSystem($old, $new)
        {
            if($this->game_db->check_table('T_GMSystem') > 0){
                $stmt = $this->game_db->prepare('UPDATE T_GMSystem SET Name = :name WHERE Name = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }

        public function update_T_LUCKY_ITEM_INFO($old, $new)
        {
            if($this->game_db->check_table('T_LUCKY_ITEM_INFO') > 0){
                $stmt = $this->game_db->prepare('UPDATE T_LUCKY_ITEM_INFO SET CharName = :name WHERE CharName = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }

        public function update_T_PentagramInfo($old, $new)
        {
            if($this->game_db->check_table('T_PentagramInfo') > 0){
                $stmt = $this->game_db->prepare('UPDATE T_PentagramInfo SET Name = :name WHERE Name = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }

        public function update_T_QUEST_EXP_INFO($old, $new)
        {
            if($this->game_db->check_table('T_QUEST_EXP_INFO') > 0){
                $stmt = $this->game_db->prepare('UPDATE T_QUEST_EXP_INFO SET CHAR_NAME = :name WHERE CHAR_NAME = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }

        public function update_PetWarehouse($old, $new)
        {
            if($this->game_db->check_table('PetWarehouse') > 0){
                $stmt = $this->game_db->prepare('UPDATE PetWarehouse SET Name = :name WHERE Name = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }

        public function update_T_WaitFriend($old, $new)
        {
            if($this->game_db->check_table('T_WaitFriend') > 0){
                $stmt = $this->game_db->prepare('UPDATE T_WaitFriend SET FriendName = :name WHERE FriendName = :old_name');
                return $stmt->execute([':name' => $new, ':old_name' => $old]);
            } else{
                return;
            }
        }

        public function update_DmN_Ban_List($old, $new)
        {
            $stmt = $this->website->db('web')->prepare('UPDATE DmN_Ban_List SET name = :name WHERE name = :old_name AND type = 2 AND server = :server');
            return $stmt->execute([':name' => $new, ':old_name' => $old, ':server' => $this->session->userdata(['user' => 'server'])]);
        }

        public function update_DmN_Gm_List($old, $new)
        {
            $stmt = $this->website->db('web')->prepare('UPDATE DmN_Gm_List SET character = :name WHERE character = :old_name AND server = :server');
            return $stmt->execute([':name' => $new, ':old_name' => $old, ':server' => $this->session->userdata(['user' => 'server'])]);
        }

        public function update_DmN_Market($old, $new)
        {
            $stmt = $this->website->db('web')->prepare('UPDATE DmN_Market SET char = :name WHERE char = :old_name AND server = :server');
            return $stmt->execute([':name' => $new, ':old_name' => $old, ':server' => $this->session->userdata(['user' => 'server'])]);
        }

        public function update_DmN_Market_Logs($old, $new)
        {
            $stmt = $this->website->db('web')->prepare('UPDATE DmN_Market_Logs SET char = :name WHERE char = :old_name AND server = :server');
            return $stmt->execute([':name' => $new, ':old_name' => $old, ':server' => $this->session->userdata(['user' => 'server'])]);
        }

        public function update_DmN_Votereward_Ranking($old, $new)
        {
            $stmt = $this->website->db('web')->prepare('UPDATE DmN_Votereward_Ranking SET character = :name WHERE character = :old_name AND server = :server');
            return $stmt->execute([':name' => $new, ':old_name' => $old, ':server' => $this->session->userdata(['user' => 'server'])]);
        }

        public function add_to_change_name_history($old, $new)
        {
            $stmt = $this->website->db('web')->prepare('INSERT INTO DmN_ChangeName_History (account, old_name, new_name, change_date, server) VALUES (:acc, :old, :new, GETDATE(), :server)');
            return $stmt->execute([':acc' => $this->session->userdata(['user' => 'username']), ':old' => $old, ':new' => $new, ':server' => $this->session->userdata(['user' => 'server'])]);
        }

        public function check_amount_of_coins()
        {
            $items_array = str_split($this->Mcharacter->char_info['Inventory'], $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
            $check_in = ['inv' => [12, 76], 'inv2' => [76, 107], 'inv3' => [108, 140],];
            $items = [];
            $coins = [];
            foreach($check_in AS $name => $loops){
                for($a = $loops[0]; $a < $loops[1]; $a++){
                    $items[$a] = strtoupper($items_array[$a]);
                }
            }
            $items = array_diff($items, [str_pad("", $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'), "F")]);
            foreach($items as $key => $item){
                $this->iteminfo->itemData($item);
                if($this->iteminfo->id == 100 && $this->iteminfo->type == 14){
                    $coins[$key] = $this->iteminfo->dur;
                }
            }
            return $coins;
        }

        public function remove_old_coins($data)
        {
            $items_array = str_split($this->Mcharacter->char_info['Inventory'], $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
            foreach($data AS $key => $val){
                if(array_key_exists($key, $items_array)){
                    $items_array[$key] = str_pad("", $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'), "F");
                }
            }
            $stmt = $this->game_db->prepare('UPDATE Character SET Inventory = 0x' . implode('', $items_array) . ' WHERE Name = :char AND AccountId = :user');
            $stmt->execute([':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        public function add_new_coins($where_to_add, $hex)
        {
            $this->get_inventory_content($this->vars['character'], $this->session->userdata(['user' => 'server']));
            $items_array = str_split($this->Mcharacter->char_info['Inventory'], $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
            if(array_key_exists($where_to_add, $items_array)){
                $items_array[$where_to_add] = $hex;
            }
            $stmt = $this->game_db->prepare('UPDATE Character SET Inventory = 0x' . implode('', $items_array) . ' WHERE Name = :char AND AccountId = :user');
            $stmt->execute([':char' => $this->website->c($this->vars['character']), ':user' => $this->session->userdata(['user' => 'username'])]);
        }

        public function draw_chance($probabilities, $max_propability = 1000)
        {
            $rand = rand(0, $max_propability);
            $keys = array_keys($probabilities);
            arsort($keys);
            do{
                $sum = array_sum($keys);
                if($rand <= $sum && $rand >= $sum - end($keys)){
                    return $keys[key($keys)];
                }
            } while(array_pop($keys));
        }

        public function check_claimed_level_rewards($id, $char, $server)
        {
            $stmt = $this->website->db('web')->prepare('SELECT TOP 1 id FROM DmN_Level_Claimed_Rewards WHERE reward_id = :id AND account = :account AND character = :char AND server = :server');
            $stmt->execute([':id' => $id, ':account' => $this->session->userdata(['user' => 'username']), ':char' => $char, ':server' => $server]);
            return $stmt->fetch();
        }

        public function log_level_reward($id, $char, $server)
        {
            $stmt = $this->website->db('web')->prepare('INSERT INTO DmN_Level_Claimed_Rewards (reward_id, account, character, server) VALUES (:id, :account, :char, :server)');
            return $stmt->execute([':id' => $id, ':account' => $this->session->userdata(['user' => 'username']), ':char' => $char, ':server' => $server]);
        }

        private function is_hex($hex_code) {
			return @preg_match("/^[a-f0-9]{2,}$/i", $hex_code) && !(strlen($hex_code) & 1);
		}
		
        private function clean_hex($data)
        {
			
            if(!$this->is_hex($data)){
                $data = bin2hex($data);
            }
            if(substr_count($data, "\0")){
                $data = str_replace("\0", '', $data);
            }
            return strtoupper($data);
        }
    }