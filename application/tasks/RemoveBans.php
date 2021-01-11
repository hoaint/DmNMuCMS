<?php

    class RemoveBans extends Job
    {
        private $registry, $config, $load, $vars = [];

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
            $list = $this->registry->website->db('web')->query('SELECT name, type, server FROM DmN_Ban_List WHERE time <= ' . time() . ' AND is_permanent = 0')->fetch_all();
            if(!empty($list)){
                foreach($list AS $bans){
                    if($bans['type'] == 1){
                        $this->remove_account_ban($bans['name'], $bans['server']);
                    } else{
                        $this->remove_char_ban($bans['name'], $bans['server']);
                    }
                    $this->remove_from_ban_list($bans['name'], $bans['server'], $bans['type']);
                }
            }
        }

        private function remove_account_ban($acc, $server)
        {
            $stmt = $this->registry->website->db('account', $server)->prepare('UPDATE MEMB_INFO SET bloc_code = 0 WHERE memb___id = :account');
            $stmt->execute([':account' => $acc]);
        }

        private function remove_char_ban($char, $server)
        {
            $stmt = $this->registry->website->db('game', $server)->prepare('UPDATE Character SET CtlCode = 0 WHERE Name = :char');
            $stmt->execute([':char' => $char]);
        }

        private function remove_from_ban_list($name, $server, $type)
        {
            $stmt = $this->registry->website->db('web')->prepare('DELETE FROM DmN_Ban_List WHERE name = :name AND server = :server AND type = :type');
            $stmt->execute([':name' => $name, ':server' => $server, ':type' => $type]);
        }
    }