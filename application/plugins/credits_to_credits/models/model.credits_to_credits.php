<?php

    class Mcredits_to_credits extends model
    {
        private $characters = [];

        public function __contruct()
        {
            parent::__construct();
        }

        /**
         * Check if account is connected to game
         *
         * @param string $account
         * @param string $server
         *
         * @return bool
         */
        public function check_connect_stat($account, $server)
        {
            $stmt = $this->website->db('account', $server)->prepare('SELECT ConnectStat FROM MEMB_STAT WHERE memb___id = :user ' . $this->website->server_code($this->website->get_servercode($server)) . '');
            $stmt->execute([':user' => $account]);
            if($status = $stmt->fetch()){
                return ($status['ConnectStat'] == 0);
            }
            return true;
        }
    }
