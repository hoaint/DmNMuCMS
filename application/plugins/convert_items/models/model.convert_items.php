<?php

    class Mconvert_items extends model
    {
        public $error = false, $vars = [], $char_info = [], $account_info = [], $char_data = [], $account_data = [];
        private $item_serial, $expanded_inventory = '', $expanded_warehouse = '', $empty_hex = '', $inventory = [], $warehouse = [], $serial = 0, $serial_count = 0;

        public function __contruct()
        {
            parent::__construct();
        }

        /**
         * Load required character data from database
         *
         * @param string $server
         *
         * @return mixed
         */
        public function load_char_list($page = 1, $per_page = 50, $server, $config)
        {
            $this->char_info = $this->website->db('game', $server)->query('SELECT TOP ' . $per_page . ' Name, '.$this->website->get_char_id_col($server).' FROM Character WHERE '.$this->website->get_char_id_col($server).' NOT IN (SELECT TOP ' . $this->website->db('game', $server)->sanitize_var($per_page * ($page - 1)) . ' '.$this->website->get_char_id_col($server).' FROM Character ORDER BY Name ASC)  ORDER BY Name ASC')->fetch_all();
            $i = 0;
            if(!empty($this->char_info)){
                foreach($this->char_info AS $data){
                    $this->get_inventory_content($data['Name'], $server, $config);
                }
                return true;
            }
            return false;
        }

        public function load_account_list($page = 1, $per_page = 50, $server, $config)
        {
            $this->account_info = $this->website->db('game', $server)->query('SELECT TOP ' . $per_page . ' AccountId, warehouse_id FROM Warehouse WHERE warehouse_id NOT IN (SELECT TOP ' . $this->website->db('game', $server)->sanitize_var($per_page * ($page - 1)) . ' warehouse_id FROM Warehouse ORDER BY AccountId ASC)  ORDER BY AccountId ASC')->fetch_all();
            $i = 0;
            if(!empty($this->account_info)){
                foreach($this->account_info AS $data){
                    $this->get_warehouse_content($data['AccountId'], $server, $config);
                }
                return true;
            }
            return false;
        }

        public function count_total_chars($server)
        {
            $count = $this->website->db('game', $server)->snumrows('SELECT COUNT(Name) AS count FROM Character');
            return $count;
        }

        public function count_total_accounts($server)
        {
            $count = $this->website->db('game', $server)->snumrows('SELECT COUNT(AccountId) AS count FROM Warehouse');
            return $count;
        }

        private function get_inventory_content($char, $server, $config)
        {
            if(DRIVER == 'pdo_dblib'){
                $items_sql = '';
                for($i = 0; $i < ($config['inventory_size'] / ($config['inventory_size'] / 16)); ++$i){
                    $multiplier = ($i == 0) ? 1 : ($i * ($config['inventory_size'] / 16)) + 1;
                    $items_sql .= 'SUBSTRING(Inventory, ' . $multiplier . ', ' . ($config['inventory_size'] / 16) . ') AS item' . $i . ', ';
                }
                $stmt = $this->website->db('game', $server)->prepare('SELECT ' . substr($items_sql, 0, -2) . ' FROM Character WHERE Name = :char');
                $stmt->execute([':char' => $char]);
                $items = unpack('H*', implode('', $stmt->fetch()));
                $this->char_data[] = ['Name' => $char, 'Inventory' => $this->clean_hex($items[1])];
            } else{
                $sql = (DRIVER == 'pdo_odbc') ? 'Inventory' : 'CONVERT(IMAGE, Inventory) AS Inventory';
                $stmt = $this->website->db('game', $server)->prepare('SELECT ' . $sql . ' FROM Character WHERE Name = :char');
                $stmt->execute([':char' => $char]);
                if($inv = $stmt->fetch()){
					if(DRIVER == 'pdo_sqlsrv' && version_compare(PHP_VERSION, '7.0', '>=')){
						$unpack = unpack('H*', $inv['Inventory']);
						$inv['Inventory'] = $this->clean_hex($unpack[1]);
					}
					else{
						$inv['Inventory'] = $this->clean_hex($inv['Inventory']);
					}
                    $this->char_data[] = ['Name' => $char, 'Inventory' => $inv['Inventory']];
                }
            }
        }

        public function get_warehouse_content($user, $server, $config)
        {
            if(DRIVER == 'pdo_dblib'){
                $items_sql = '';
                for($i = 0; $i < ($config['warehouse_size'] / ($config['warehouse_size'] / 16)); ++$i){
                    $multiplier = ($i == 0) ? 1 : ($i * ($config['warehouse_size'] / 16)) + 1;
                    $items_sql .= 'SUBSTRING(Items, ' . $multiplier . ', ' . ($config['warehouse_size'] / 16) . ') AS item' . $i . ', ';
                }
                $stmt = $this->website->db('game', $server)->prepare('SELECT ' . substr($items_sql, 0, -2) . ' FROM Warehouse WHERE AccountId = :user');
                $stmt->execute([':user' => $user]);
                $items = unpack('H*', implode('', $stmt->fetch()));
                $this->account_data[] = ['AccountId' => $user, 'Items' => $this->clean_hex($items[1])];
            } else{
                $sql = (DRIVER == 'pdo_odbc') ? 'Items' : 'CONVERT(IMAGE, Items) AS Items';
                $stmt = $this->website->db('game', $server)->prepare('SELECT ' . $sql . ' FROM Warehouse WHERE AccountId = :user');
                $stmt->execute([':user' => $user]);
                if($ware = $stmt->fetch()){
					if(DRIVER == 'pdo_sqlsrv' && version_compare(PHP_VERSION, '7.0', '>=')){
						$unpack = unpack('H*', $ware['Items']);
						$ware['Items'] = $this->clean_hex($unpack[1]);
					}
					else{
						$ware['Items'] = $this->clean_hex($ware['Items']);
					}
                    $this->account_data[] = ['AccountId' => $user, 'Items' => $ware['Items']];
                }
            }
        }

        public function get_serial($server)
        {
            $this->serial = $this->website->db('game', $server)->query('SELECT ItemCount FROM GameServerInfo')->fetch();
        }

        public function set_serial($server)
        {
            $this->website->db('game', $server)->query('UPDATE GameServerInfo SET ItemCount = ' . (int)$this->serial['ItemCount'] . '');
        }

        public function convert_inventory($server, $config)
        {
            $this->empty_hex = str_pad("", 24, "F");
            foreach($this->char_data AS $key => $data){
                if(strlen($data['Inventory']) < 15104){
                    $this->inventory[$data['Name']] = str_split($data['Inventory'], 32);
                }
            }
            foreach($this->inventory AS $key => $value){
                $items = [];
                foreach($value AS $id => $item){
                    if($item != str_pad("", 32, "F")){
                        $this->item_serial = sprintf("%08X", $this->serial['ItemCount'], 00000000);
                        $this->serial['ItemCount'] += 1;
                    } else{
                        $this->item_serial = 'FFFFFFFF';
                    }
                    $items[$id] = $item . $this->item_serial . $this->empty_hex;
                }
                $this->update_inventory($key, $items, $server, $config);
            }
        }

        public function convert_warehouse($server, $config)
        {
            $this->empty_hex = str_pad("", 24, "F");
            foreach($this->account_data AS $key => $data){
                if(strlen($data['Items']) < 15360){
                    $this->warehouse[$data['AccountId']] = str_split($data['Items'], 32);
                }
            }
            foreach($this->warehouse AS $key => $value){
                $items = [];
                foreach($value AS $id => $item){
                    if($item != str_pad("", 32, "F")){
                        $this->item_serial = sprintf("%08X", $this->serial['ItemCount'], 00000000);
                        $this->serial['ItemCount'] += 1;
                    } else{
                        $this->item_serial = 'FFFFFFFF';
                    }
                    $items[$id] = $item . $this->item_serial . $this->empty_hex;
                }
                $this->update_warehouse($key, $items, $server, $config);
            }
        }

        private function update_inventory($name, $items = [], $server, $config)
        {
            if(($config['inventory_size'] / 16) == 118){
                $this->expanded_inventory = str_pad("", (118 * 64), "F");
            }
            $new_items = implode('', $items) . $this->expanded_inventory;
            $additional = '';
            if((strlen($new_items) / 2) < 7584){
                $additional = str_pad("", (7584 - (strlen($new_items) / 2)) * 2, "F");
            }
            $new_items .= $additional;
            $this->website->db('game', $server)->query('UPDATE Character SET Inventory = 0x' . $new_items . ' WHERE Name = \'' . $this->website->db('game', $server)->sanitize_var($name) . '\'');
        }

        private function update_warehouse($name, $items = [], $server, $config)
        {
            if(($config['warehouse_size'] / 16) == 120){
                $this->expanded_warehouse = str_pad("", (120 * 64), "F");
            }
            $new_items = implode('', $items) . $this->expanded_warehouse;
            $this->website->db('game', $server)->query('UPDATE Warehouse SET Items = 0x' . $new_items . ' WHERE AccountId = \'' . $this->website->db('game', $server)->sanitize_var($name) . '\'');
        }

        private function clean_hex($data)
        {
            if(DRIVER == 'mssql'){
                $data = bin2hex($data);
            }
            if(substr_count($data, "\0")){
                $data = str_replace("\0", '', $data);
            }
            return strtoupper($data);
        }

        public function get_wh_size($server)
        {
            return $this->website->db('game', $server)->query('SELECT character_maximum_length AS length FROM information_schema.columns WHERE table_name = \'Warehouse\' AND column_name = \'Items\'')->fetch();
        }

        public function update_warehouse_size($server)
        {
            $this->website->db('game', $server)->query('ALTER TABLE Warehouse ALTER COLUMN Items varbinary(7680)');
        }

        public function get_inv_size($server)
        {
            return $this->website->db('game', $server)->query('SELECT character_maximum_length AS length FROM information_schema.columns WHERE table_name = \'Character\' AND column_name = \'Inventory\'')->fetch();
        }

        public function update_inv_size($server)
        {
            $this->website->db('game', $server)->query('ALTER TABLE Character ALTER COLUMN Inventory varbinary(7584)');
        }

        public function check_if_column_exists($column, $table, $server)
        {
            return $this->website->db('game', $server)->query('SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = \'' . $table . '\'  AND COLUMN_NAME = \'' . $column . '\'')->fetch();
        }

        public function add_column($column, $table, $info, $server)
        {
            $query = 'ALTER TABLE ' . $table . ' ADD ' . $column . ' ' . $info['type'];
            if($info['identity'] == 1){
                $query .= ' IDENTITY(1,1)';
            }
            if($info['is_primary_key'] == 1){
                $query .= ' PRIMARY KEY';
            }
            $query .= ($info['null'] == 1) ? ' NULL' : ' NOT NULL';
            if($info['default'] != ''){
                $query .= ' DEFAULT ' . $info['default'] . '';
            }
            return $this->website->db('game', $server)->query($query);
        }

        public function drop_constrait($table, $constrait, $server)
        {
            return $this->website->db('game', $server)->query('ALTER TABLE ' . $table . ' DROP CONSTRAINT ' . $constrait . '');
        }

        public function add_constrait($table, $constrait, $server)
        {
            return $this->website->db('game', $server)->query('ALTER TABLE ' . $table . '  ADD CONSTRAINT ' . $constrait . ' PRIMARY KEY NONCLUSTERED (AccountId)');
        }

        public function get_primary_key_constrait($table, $server)
        {
            return $this->website->db('game', $server)->query('SELECT A.CONSTRAINT_NAME FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS A, INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE B WHERE CONSTRAINT_TYPE = \'PRIMARY KEY\' AND A.CONSTRAINT_NAME = B.CONSTRAINT_NAME AND A.TABLE_NAME = \'' . $table . '\' ORDER BY A.TABLE_NAME')->fetch();
        }

        public function drop_column($col, $table, $server)
        {
            return $this->website->db('game', $server)->query('ALTER TABLE ' . $table . ' DROP COLUMN ' . $col . '');
        }
    }
