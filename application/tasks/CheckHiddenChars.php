<?php

    class CheckBans extends Job
    {
        private $registry, $config, $load, $vars = [], $ban_list = [], $character_bans = [], $account_bans = [];

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
            $this->load->model('character');
            foreach($this->registry->website->server_list() AS $key => $server){
                $this->ban_list($key);
                $this->character_bans($key);
                $this->account_bans($key);
                foreach($this->character_bans[$key] AS $bans){
                    if(!$this->check_in_banlist($bans['Name'], $key, 2)){
                        $this->add_to_banlist($bans['Name'], $key, 2);
                    }
                }
                foreach($this->account_bans[$key] AS $bans){
                    if(!$this->check_in_banlist($bans['memb___id'], $key, 1)){
                        $this->add_to_banlist($bans['memb___id'], $key, 1);
                    }
                }
                foreach($this->ban_list[$key] AS $bans){
                    if($bans['type'] == 1){
                        if(!$this->account_ban($bans['name'], $key)){
                            $this->remove_from_ban_list($bans['name'], $key, 1);
                        }
                    } else{
                        if(!$this->char_ban($bans['name'], $key)){
                            $this->remove_from_ban_list($bans['name'], $key, 2);
                        }
                    }
                }
            }
        }

        private function account_ban($name, $server)
        {
            $stmt = $this->registry->website->db('account', $server)->prepare('SELECT memb___id FROM MEMB_INFO WHERE memb___id = :name AND bloc_code = 1');
            $stmt->execute([':name' => $name,]);
            return $stmt->fetch();
        }

        private function char_ban($name, $server)
        {
            $stmt = $this->registry->website->db('game', $server)->prepare('SELECT Name FROM Character WHERE Name = :name AND CtlCode = 1');
            $stmt->execute([':name' => $name,]);
            return $stmt->fetch();
        }

        private function remove_from_ban_list($name, $server, $type)
        {
            $stmt = $this->registry->website->db('web')->prepare('DELETE FROM DmN_Ban_List WHERE name = :name AND type = :type AND server = :server');
            $stmt->execute([':name' => $name, ':type' => $type, ':server' => $server]);
        }

        private function ban_list($server)
        {
            $this->ban_list[$server] = $this->registry->website->db('web')->query('SELECT name, type FROM DmN_Ban_List WHERE server = \'' . $this->registry->website->db('web')->sanitize_var($server) . '\'')->fetch_all();
        }

        private function check_in_banlist($name, $server, $type)
        {
            $stmt = $this->registry->website->db('web')->prepare('SELECT name FROM DmN_Ban_List WHERE name = :name AND type = :type AND server = :server');
            $stmt->execute([':name' => $name, ':type' => $type, ':server' => $server]);
            return $stmt->fetch();
        }

        private function add_to_banlist($name, $server, $type)
        {
            $stmt = $this->registry->website->db('web')->prepare('INSERT INTO DmN_Ban_List (name, type, server, time, is_permanent, reason) VALUES (:name, :type, :server, :time, :is_permanent, :reason)');
            return $stmt->execute([':name' => $name, ':type' => $type, ':server' => $server, ':time' => 0, ':is_permanent' => 1, ':reason' => 'Ban from system']);
        }

        private function character_bans($server)
        {
            $this->character_bans[$server] = $this->registry->website->db('game', $server)->query('SELECT Name FROM Character WHERE CtlCode = 1')->fetch_all();
        }

        private function account_bans($server)
        {
            $this->account_bans[$server] = $this->registry->website->db('account', $server)->query('SELECT memb___id FROM MEMB_INFO WHERE bloc_code = 1')->fetch_all();
        }
    }