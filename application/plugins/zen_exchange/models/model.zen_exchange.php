<?php

    class Mzen_exchange extends model
    {
        private $characters = [];

        public function __contruct()
        {
            parent::__construct();
        }

        /**
         * Load required character data from database on current account
         *
         * @param string $account
         * @param string $server
         *
         * @return mixed
         */
        public function load_char_list($account, $server)
        {
            $stmt = $this->website->db('game', $server)->prepare('SELECT Name, '.$this->website->get_char_id_col($server).' AS id FROM Character WHERE AccountId = :account');
            $stmt->execute([':account' => $account]);
            $i = 0;
            while($row = $stmt->fetch()){
                $this->characters[] = ['id' => $row['id'], 'Name' => utf8_encode($row['Name'])];
                $i++;
            }
            if($i > 0){
                return $this->characters;
            } else{
                return false;
            }
        }

        /**
         * Check if character exists
         *
         * @param string $account
         * @param string $server
         * @param int $id
         *
         * @return mixed
         */
        public function check_char($account, $server, $id)
        {
            $stmt = $this->website->db('game', $server)->prepare('SELECT TOP 1 Name, Money, '.$this->website->get_char_id_col($server).' AS id FROM Character WHERE AccountId = :account AND '.$this->website->get_char_id_col($server).' = :id');
            $stmt->execute([':account' => $account, ':id' => $id]);
            return $stmt->fetch();
        }

        /**
         * Check if money in warehouse exists
         *
         * @param string $account
         * @param string $server
         * @param int $id
         *
         * @return mixed
         */
        public function check_warehouse($account, $server)
        {
            $stmt = $this->website->db('game', $server)->prepare('SELECT TOP 1 Money FROM Warehouse WHERE AccountId = :account');
            $stmt->execute([':account' => $account]);
            return $stmt->fetch();
        }

        /**
         * Check if money in zen wallet exists
         *
         * @param string $account
         * @param string $server
         * @param int $id
         *
         * @return mixed
         */
        public function check_zen_wallet($account, $server)
        {
            $stmt = $this->website->db('web')->prepare('SELECT TOP 1 credits3 AS Money FROM DmN_Shop_Credits WHERE memb___id = :account AND server = :server');
            $stmt->execute([':account' => $account, ':server' => $server]);
            return $stmt->fetch();
        }

        /**
         * Update Character zen
         *
         * @param string $account
         * @param string $server
         * @param int $id
         * @param int $money
         *
         * @return bool
         */
        public function update_zen($account, $server, $id, $money)
        {
            if($id == -1){
                return $this->update_zen_warehouse($account, $server, $money);
            }
            if($id == -2){
                return $this->update_zen_wallet($account, $server, $money);
            }
            $stmt = $this->website->db('game', $server)->prepare('UPDATE Character SET Money = Money - :money WHERE AccountId = :account AND '.$this->website->get_char_id_col($server).' = :id');
            return $stmt->execute([':money' => $money, ':account' => $account, ':id' => $id]);
        }

        /**
         * Update Warehouse zen
         *
         * @param string $account
         * @param string $server
         * @param int $money
         *
         * @return bool
         */
        private function update_zen_warehouse($account, $server, $money)
        {
            $stmt = $this->website->db('game', $server)->prepare('UPDATE Warehouse SET Money = Money - :money WHERE AccountId = :account');
            return $stmt->execute([':money' => $money, ':account' => $account]);
        }

        /**
         * Update Web Wallet zen
         *
         * @param string $account
         * @param string $server
         * @param int $money
         *
         * @return bool
         */
        private function update_zen_wallet($account, $server, $money)
        {
            $stmt = $this->website->db('web')->prepare('UPDATE DmN_Shop_Credits SET credits3 = credits3 - :money WHERE memb___id = :account AND server = :server');
            return $stmt->execute([':money' => $money, ':account' => $account, ':server' => $server]);
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
