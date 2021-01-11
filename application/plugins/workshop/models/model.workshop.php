<?php
    in_file();

    class Mworkshop extends model
    {
        public $error = false, $vars = [], $characters = [], $char_info = [], $price = 0, $socket_price = 0, $items_array = [];
        private $logs = [];

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
		
		public function setPriceForUpgrade($price)
        {
            $this->price += $price;
        }

        public function setPriceForLevel($current, $new, $price)
        {
            $total = $new - $current;
            $this->price += $price * $total;
        }

        public function setPriceForOption($current, $new, $price)
        {
            $total = $new - $current;
            $this->price += $price * $total;
        }

        public function setPriceForLuck($price)
        {
            $this->price += $price;
        }

        public function setPriceForSkill($price)
        {
            $this->price += $price;
        }

        public function setPriceForExe($price, $count)
        {
            $this->price += $price * $count;
        }
		
		public function setPriceForRemoveExe($price)
        {
            $this->price += $price;
        }

        public function setPriceForSocket($price)
        {
            $this->price += $price;
            $this->socket_price += $price;
        }

        public function load_char_list($account, $server)
        {
            $stmt = $this->website->db('game', $server)->prepare('SELECT Name, Class, '.$this->website->get_char_id_col($server).' AS id FROM Character WHERE AccountId = :account');
            $stmt->execute([':account' => $account]);
            $i = 0;
            while($row = $stmt->fetch()){
                $this->characters[] = ['name' => $row['Name'], 'Class' => $row['Class'], 'id' => $row['id']];
                $i++;
            }
            if($i > 0){
                return $this->characters;
            } else{
                return false;
            }
        }

        public function char_info($char, $account, $server)
        {
            $stmt = $this->website->db('game', $server)->prepare('SELECT Name, Class, '.$this->website->get_char_id_col($server).' AS id FROM Character WHERE AccountId = :user AND '.$this->website->get_char_id_col($server).' = :char');
            $stmt->execute([':user' => $account, ':char' => $char]);
            if($this->char_info = $stmt->fetch()){
                $this->inventory($this->char_info['Name'], $server);
            }
        }

        private function inventory($char, $server)
        {
            if(DRIVER == 'pdo_dblib'){
                $items_sql = '';
                for($i = 0; $i < ($this->website->get_value_from_server($server, 'inv_size') / $this->website->get_value_from_server($server, 'inv_multiplier')); ++$i){
                    $multiplier = ($i == 0) ? 1 : ($i * $this->website->get_value_from_server($server, 'inv_multiplier')) + 1;
                    $items_sql .= 'SUBSTRING(Inventory, ' . $multiplier . ', ' . $this->website->get_value_from_server($server, 'inv_multiplier') . ') AS item' . $i . ', ';
                }
                $stmt = $this->website->db('game', $server)->prepare('SELECT ' . substr($items_sql, 0, -2) . ' FROM Character WHERE Name = :char');
                $stmt->execute([':char' => $char]);
                $items = unpack('H*', implode('', $stmt->fetch()));
                $this->char_info['Inventory'] = $this->clean_hex($items[1]);
            } else{
                $sql = (DRIVER == 'pdo_odbc') ? 'Inventory' : 'CONVERT(IMAGE, Inventory) AS Inventory';
                $stmt = $this->website->db('game', $server)->prepare('SELECT ' . $sql . ' FROM Character WHERE Name = :char');
                $stmt->execute([':char' => $char]);
                if($inv = $stmt->fetch()){
					if(in_array(DRIVER, ['sqlsrv', 'pdo_sqlsrv'])){
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
            $items_array = array_chunk(str_split($this->char_info['Inventory'], $this->website->get_value_from_server($server, 'item_size')), 12);
            $equipment = [];
            foreach($items_array[0] as $key => $item){
                if($item != str_pad("", $this->website->get_value_from_server($server, 'item_size'), "F")){
                    $this->iteminfo->itemData($item);
                    $equipment[$key]['item_id'] = $this->iteminfo->id;
                    $equipment[$key]['item_cat'] = $this->iteminfo->type;
                    $equipment[$key]['serial'] = $this->iteminfo->serial;
                    $equipment[$key]['serial2'] = ($this->iteminfo->serial2 != null) ? $this->iteminfo->serial2 : '0';
                    $equipment[$key]['name'] = $this->iteminfo->realName();
                    $equipment[$key]['level'] = (int)substr($this->iteminfo->getLevel(), 1);
                    $equipment[$key]['hex'] = $item;
                } else{
                    $equipment[$key] = 0;
                }
            }
            return $equipment;
        }

        public function load_inventory($inv = 1, $server = '')
        {
            $items_array = str_split($this->char_info['Inventory'], $this->website->get_value_from_server($server, 'item_size'));
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
                    $items[$i]['serial'] = $this->iteminfo->serial;
                    $items[$i]['serial2'] = ($this->iteminfo->serial2 != null) ? $this->iteminfo->serial2 : '0';
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

        public function find_item($serial, $server)
        {
            $found_item = [];
            $this->items_array = str_split($this->char_info['Inventory'], $this->website->get_value_from_server($server, 'item_size'));
            foreach($this->items_array as $key => $item){
                if($serial[1] != '0'){
                    if((substr($item, 32, 8) == strtoupper($serial[1]))){
                        $found_item[$key] = $item;
                        break;
                    }
                } else{
                    if(substr($item, 6, 8) == strtoupper($serial[0])){
                        $found_item[$key] = $item;
                        break;
                    }
                }
            }
            //var_dump($found_item);
            return $found_item;
        }

        public function get_item_shop_info($id = '', $cat = '', $check_socket_part)
        {
            if($id === '' || $cat === '')
                return false;
            $stmt = $this->website->db('web')->prepare('SELECT TOP 1 id, item_id, item_cat, exetype, name, luck, price, max_item_lvl, max_item_opt, use_sockets, use_harmony, use_refinary, stick_level, allow_upgrade, upgrade_price FROM DmN_Shopp WHERE item_id = :id AND original_item_cat = :cat');
            $stmt->execute([':id' => $id, ':cat' => $cat]);
            if($item = $stmt->fetch()){
                $item['socket_info'] = $this->socket_list($item['use_sockets'], $check_socket_part, $cat);
                return $item;
            }
            return false;
        }

        public function socket_list($use_sockets = 1, $check_part = 1, $cat)
        {
            $exe_type = ($cat <= 5) ? 1 : 0;
            if($use_sockets == 1){
                if($check_part == 1){
                    $sockets = $this->website->db('web')->query('SELECT seed, socket_id, socket_name, value FROM DmN_Shop_Sockets WHERE status != 0  AND socket_part_type IN (-1, ' . $exe_type . ') ORDER BY orders ASC')->fetch_all();
                } else{
                    $sockets = $this->website->db('web')->query('SELECT seed, socket_id, socket_name, value FROM DmN_Shop_Sockets WHERE status != 0 ORDER BY orders ASC')->fetch_all();
                }
                return $sockets;
            }
        }

        public function check_sockets_part_type($socket, $cat, $seed)
        {
            $exe_type = ($cat <= 5) ? 1 : 0;
            return $this->website->db('web')->query('SELECT seed, socket_id, socket_price, value FROM DmN_Shop_Sockets WHERE socket_id = ' . $this->website->db('web')->sanitize_var($socket) . ' AND seed = ' . $this->website->db('web')->sanitize_var($seed) . ' AND status != 0 AND socket_part_type IN (-1, ' . $exe_type . ')')->fetch();
        }

        public function check_sockets($socket, $seed)
        {
            return $this->website->db('web')->query('SELECT seed, socket_id, socket_price, value FROM DmN_Shop_Sockets WHERE socket_id = ' . $this->website->db('web')->sanitize_var($socket) . ' AND seed = ' . $this->website->db('web')->sanitize_var($seed) . ' AND status != 0')->fetch();
        }

        public function logUpgrade($oldHex, $newHex, $price, $payment_method, $char, $account, $server)
        {
            $stmt = $this->website->db('web')->prepare('INSERT INTO DmN_ItemUpgradeLog (mu_id, upgrade_date, hex_before, hex_after, price, payment_method, account, server) VALUES (:id, :date, :hex_before, :hex_after, :price, :payment_method, :account, :server)');
            $stmt->execute([':id' => $char, ':date' => time(), ':hex_before' => $oldHex, ':hex_after' => $newHex, ':price' => $price, ':payment_method' => $payment_method, ':account' => $account, ':server' => $server]);
            return true;
        }

        private function findCharName($id, $server)
        {
            return $this->website->db('game', $server)->query('SELECT Name FROM Character WHERE '.$this->website->get_char_id_col($server).' = ' . $id . '')->fetch()['Name'];
        }

        public function load_logs($page = 1, $per_page = 25, $acc = '', $server = 'All')
        {
            if(($acc == '' || $acc == '-') && $server == 'All')
                $items = $this->website->db('web')->query('SELECT Top ' . $this->website->db('web')->sanitize_var($per_page) . ' mu_id, upgrade_date, hex_before, hex_after, price, payment_method, account, server FROM DmN_ItemUpgradeLog WHERE price > 0 AND id Not IN (SELECT Top ' . $this->website->db('web')->sanitize_var($per_page * ($page - 1)) . ' id FROM DmN_ItemUpgradeLog ORDER BY id DESC) ORDER BY id DESC'); else{
                if(($acc != '' && $acc != '-') && $server == 'All')
                    $items = $this->website->db('web')->query('SELECT Top ' . $this->website->db('web')->sanitize_var($per_page) . ' mu_id, upgrade_date, hex_before, hex_after, price, payment_method, account, server FROM DmN_ItemUpgradeLog WHERE price > 0 AND account like \'%' . $this->website->db('web')->sanitize_var($acc) . '%\' AND id Not IN (SELECT Top ' . $this->website->db('web')->sanitize_var($per_page * ($page - 1)) . ' id FROM DmN_ItemUpgradeLog WHERE price > 0 AND account like \'%' . $this->website->db('web')->sanitize_var($acc) . '%\' ORDER BY id DESC) ORDER BY id DESC'); else
                    $items = $this->website->db('web')->query('SELECT Top ' . $this->website->db('web')->sanitize_var($per_page) . ' mu_id, upgrade_date, hex_before, hex_after, price, payment_method, account, server FROM DmN_ItemUpgradeLog WHERE price > 0 AND account like \'%' . $this->website->db('web')->sanitize_var($acc) . '%\' AND server = \'' . $this->website->db('web')->sanitize_var($server) . '\' AND id Not IN (SELECT Top ' . $this->website->db('web')->sanitize_var($per_page * ($page - 1)) . ' id DmN_ItemUpgradeLog WHERE price > 0 AND account like \'%' . $this->website->db('web')->sanitize_var($acc) . '%\' AND server = \'' . $this->website->db('web')->sanitize_var($server) . '\' ORDER BY id DESC) ORDER BY id DESC');
            }
            foreach($items->fetch_all() as $value){
                $this->logs[] = ['char' => $this->findCharName($value['mu_id'], $value['server']), 'hex_before' => $value['hex_before'], 'hex_after' => $value['hex_after'], 'price' => $value['price'], 'payment_method' => $value['payment_method'], 'account' => htmlspecialchars($value['account']), 'server' => htmlspecialchars($value['server']), 'upgrade_date' => date('Y/m/d H:i', $value['upgrade_date'])];
            }
            return $this->logs;
        }

        public function count_total_logs($acc = '', $server = 'All')
        {
            $sql = '';
            if($acc != '' && $acc != '-'){
                $sql .= 'AND account like \'%' . $this->website->db('web')->sanitize_var($acc) . '%\'';
                if($server != 'All'){
                    $sql .= ' AND server = \'' . $this->website->db('web')->sanitize_var($server) . '\'';
                }
            }
            $count = $this->website->db('web')->snumrows('SELECT COUNT(account) AS count FROM DmN_ItemUpgradeLog WHERE price > 0 ' . $sql . '');
            return $count;
        }

        public function upgradeItem($char, $account, $server)
        {
            $stmt = $this->website->db('game', $server)->prepare('UPDATE Character SET Inventory = 0x' . implode('', $this->items_array) . ' WHERE AccountId = :user AND '.$this->website->get_char_id_col($server).' = :char');
            $stmt->execute([':user' => $account, ':char' => $char]);
            return true;
        }

        public function check_connect_stat($account, $server)
        {
            $stmt = $this->website->db('account', $server)->prepare('SELECT ConnectStat FROM MEMB_STAT WHERE memb___id = :user');
            $stmt->execute([':user' => $account]);
            if($status = $stmt->fetch()){
                return ($status['ConnectStat'] == 0);
            }
            return true;
        }

        public function get_guid($user = '', $server)
        {
            $stmt = $this->website->db('account', $server)->prepare('SELECT memb_guid FROM MEMB_INFO WHERE memb___id = :user');
            $stmt->execute([':user' => $user]);
            $info = $stmt->fetch();
            return $info['memb_guid'];
        }

        private function clean_hex($data)
        {
            if(substr_count($data, "\0")){
                $data = str_replace("\0", '', $data);
            }
            return strtoupper($data);
        }
    }
