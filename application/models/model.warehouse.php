<?php
    in_file();

    class Mwarehouse extends model
    {
        public $vault_items, $vault_money, $item = '', $items = [], $total_items, $exe_opt_count = 0;
        private $new_hex;

        public function __contruct()
        {
            parent::__construct();
        }

        public function get_vault_content($user = '')
        {
            $user = ($user != '') ? $user : $this->session->userdata(['user' => 'username']);
            if(DRIVER == 'pdo_dblibs'){
                $items_sql = '';
                for($i = 0; $i < ($this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_size') / $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_multiplier')); ++$i){
                    $multiplier = ($i == 0) ? 1 : ($i * $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_multiplier')) + 1;
                    $items_sql .= 'SUBSTRING(Items, ' . $multiplier . ', ' . $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_multiplier') . ') AS item' . $i . ', ';
                }
                $stmt = $this->game_db->prepare('SELECT ' . substr($items_sql, 0, -2) . ', Money FROM Warehouse WHERE AccountId = :user');
                $stmt->execute([':user' => $user]);
                if($this->vault_items = $stmt->fetch()){
                    $items = unpack('H*', implode('', $this->vault_items));
                    $this->vault_items['Items'] = $this->clean_hex($items[1]);							
                    $this->vault_money = $this->vault_items['Money'];
                    return true;
                } else{
                    return false;
                }
            } else{
                $sql = (DRIVER == 'pdo_odbc') ? 'Items' : 'CONVERT(IMAGE, Items) AS Items';
                $stmt = $this->game_db->prepare('SELECT ' . $sql . ', Money FROM Warehouse WHERE AccountId = :user');
                $stmt->execute([':user' => $user]);
                if($this->vault_items = $stmt->fetch()){ 
					if(in_array(DRIVER, ['sqlsrv', 'pdo_sqlsrv', 'pdo_dblib'])){
						$unpack = unpack('H*', $this->vault_items['Items']);
						$this->vault_items['Items'] = $this->clean_hex($unpack[1]);
					}
					else{
						$this->vault_items['Items'] = $this->clean_hex($this->vault_items['Items']);
					}
                    $this->vault_money = $this->vault_items['Money'];
                    return true;
                } else{
                    return false;
                }
            }
        }

        public function load_items()
        {
            $hex = str_split($this->vault_items['Items'], $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
            $items = [];
            $i = 0;
            $x = 0;
            $y = 0;
             foreach($hex as $item){
                $i++;
                if($item != str_pad("", $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'), "F")){
                    $this->iteminfo->itemData($item);
												  
                    $items[$i]['item_id'] = $this->iteminfo->id;
                    $items[$i]['item_cat'] = $this->iteminfo->type;
                    $items[$i]['level'] = (int)substr($this->iteminfo->getLevel(), 1);
                    $items[$i]['name'] = $this->iteminfo->realName();
                    $items[$i]['x'] = $this->iteminfo->getX();
                    $items[$i]['y'] = $this->iteminfo->getY();
                    $items[$i]['xx'] = $x;
                    $items[$i]['yy'] = $y;
                    $items[$i]['hex'] = $this->iteminfo->hex;
                }
                $x++;
                if($x >= 8){
                    $x = 0;
                    $y++;
                    if($y >= 15){
                        $y = 0;
                    }
                }
            }
            $this->set_total_items(count($hex));
            return $items;
        }

        private function set_total_items($count = 120)
        {
            $this->total_items = $count;
        }

        public function find_item_by_slot($slot)
        {
            $hex = str_split($this->vault_items['Items'], $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
            $found = false;
            if(isset($hex[$slot - 1]) && $hex[$slot - 1] != str_pad("", $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'), "F")){
                $found = true;
                $this->item = $hex[$slot - 1];
            }
            return $found;
        }

        public function generate_new_item_by_slot($slot)
        {
            $hex = str_split($this->vault_items['Items'], $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'));
            if(isset($hex[$slot - 1])){
                $hex[$slot - 1] = str_pad("", $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'), "F");
            }
            $this->new_hex = implode('', $hex);
        }

        public function insert_web_item()
        {
            $stmt = $this->website->db('web')->prepare('INSERT INTO DmN_Web_Storage (item, account, server, expires_on) VALUES (:item, :account, :server, :expires_on)');
            return $stmt->execute([':item' => $this->item, ':account' => $this->session->userdata(['user' => 'username']), ':server' => $this->session->userdata(['user' => 'server']), ':expires_on' => strtotime('+' . $this->config->config_entry('warehouse|web_wh_item_expires_after'))]);
        }

        public function check_web_wh_item($id)
        {
            return $this->website->db('web')->query('SELECT item FROM DmN_Web_Storage WHERE account = \'' . $this->website->db('web')->sanitize_var($this->session->userdata(['user' => 'username'])) . '\' AND server = \'' . $this->website->db('web')->sanitize_var($this->session->userdata(['user' => 'server'])) . '\' AND is_removed = 0 AND expires_on > ' . time() . ' AND id = ' . $this->website->db('web')->sanitize_var($id) . '')->fetch();
        }

        public function set_removed_web_item($id)
        {
            $stmt = $this->website->db('web')->prepare('UPDATE DmN_Web_Storage SET is_removed = 1 WHERE id = :id');
            return $stmt->execute([':id' => $id]);
        }

        public function load_web_items($page = 1)
        {
            $per_page = ($page <= 1) ? 0 : (int)$this->config->config_entry('warehouse|web_items_per_page') * ((int)$page - 1);
            $items = $this->website->db('web')->query('SELECT Top ' . $this->website->db('web')->sanitize_var((int)$this->config->config_entry('warehouse|web_items_per_page')) . ' id, item, expires_on FROM DmN_Web_Storage WHERE account = \'' . $this->website->db('web')->sanitize_var($this->session->userdata(['user' => 'username'])) . '\' AND server = \'' . $this->website->db('web')->sanitize_var($this->session->userdata(['user' => 'server'])) . '\' AND is_removed = 0 AND expires_on > ' . time() . ' AND id Not IN (SELECT Top ' . $this->website->db('web')->sanitize_var($per_page) . ' id FROM DmN_Web_Storage WHERE account = \'' . $this->website->db('web')->sanitize_var($this->session->userdata(['user' => 'username'])) . '\' AND server = \'' . $this->website->db('web')->sanitize_var($this->session->userdata(['user' => 'server'])) . '\' AND is_removed = 0 AND expires_on > ' . time() . ' ORDER BY id DESC) ORDER BY id DESC');
            $pos = ($page == 1) ? 1 : (int)(($page - 1) * $this->config->config_entry('warehouse|web_items_per_page')) + 1;
            foreach($items->fetch_all() as $value){
                $this->iteminfo->itemData($value['item']);
                $this->items[] = [
					'item' => $value['item'],
					'name' => $this->iteminfo->getNameStyle(true), 
					'namenostyle' => $this->iteminfo->realName(), 
					'id' => $value['id'], 
					'expires_on' => $value['expires_on'], 
					'pos' => $pos
				];
                $pos++;
            }
            return $this->items;
        }

        public function count_total_web_items()
        {
            $this->total_items = $this->website->db('web')->snumrows('SELECT COUNT(item) AS count FROM DmN_Web_Storage WHERE account = \'' . $this->website->db('web')->sanitize_var($this->session->userdata(['user' => 'username'])) . '\' AND server = \'' . $this->website->db('web')->sanitize_var($this->session->userdata(['user' => 'server'])) . '\' AND is_removed = 0 AND expires_on > ' . time() . '');
        }

        public function update_warehouse($user = '')
        {
            $user = ($user != '') ? $user : $this->session->userdata(['user' => 'username']);
            $stmt = $this->game_db->prepare('UPDATE Warehouse SET Items = 0x' . $this->new_hex . ' WHERE AccountId = :user');
            return $stmt->execute([':user' => $user]);
        }

        public function load_item_info()
        {
            $this->iteminfo->itemData($this->item);
            $option = $this->iteminfo->option;
            $info = [
				'id' => $this->iteminfo->id, 
				'name' => $this->iteminfo->realName(), 
				'cat' => $this->iteminfo->type, 
				'serial' => $this->iteminfo->serial, 
				'serial2' => ($this->iteminfo->serial2 != null) ? $this->iteminfo->serial2 : 'FFFFFFFF', 
				'skill' => ($option >= 128) ? 1 : 0, 
				'lvl' => ($option >= 128) ? floor(($option - 128) / 8) : floor($option / 8), 
				'exe' => $this->iteminfo->exe, 
				'socket' => $this->iteminfo->socket, 
				'class' => $this->iteminfo->getClass(), 
				'anc' => ($this->iteminfo->ancient > 0) ? 1 : 0
			];
            $luck = (($this->iteminfo->option - ($info['lvl'] * 8)) >= 4) ? 1 : 0;
            $exe_opts = [0, 0, 0, 0, 0, 0, 0, 0, 0];
            if($info['exe'] >= 64){
                $info['exe'] -= 64;
            }
            if($info['exe'] >= 32){
                $info['exe'] -= 32;
                $exe_opts[5] = 1;
                $this->exe_opt_count += 1;
            }
            if($info['exe'] >= 16){
                $info['exe'] -= 16;
                $exe_opts[4] = 1;
                $this->exe_opt_count += 1;
            }
            if($info['exe'] >= 8){
                $info['exe'] -= 8;
                $exe_opts[3] = 1;
                $this->exe_opt_count += 1;
            }
            if($info['exe'] >= 4){
                $info['exe'] -= 4;
                $exe_opts[2] = 1;
                $this->exe_opt_count += 1;
            }
            if($info['exe'] >= 2){
                $info['exe'] -= 2;
                $exe_opts[1] = 1;
                $this->exe_opt_count += 1;
            }
            if($info['exe'] >= 1){
                $info['exe'] -= 1;
                $exe_opts[0] = 1;
                $this->exe_opt_count += 1;
            }
            if(defined('MU_VERSION') && MU_VERSION >= 5){
                if(in_array($info['socket'][1], [6, 7, 8])){
                    $this->exe_opt_count += 1;
                    $exe_opts[6] = 1;
                }
                if(in_array($info['socket'][2], [6, 7, 8])){
                    $this->exe_opt_count += 1;
                    $exe_opts[7] = 1;
                }
                if(in_array($info['socket'][3], [6, 7, 8])){
                    $this->exe_opt_count += 1;
                    $exe_opts[8] = 1;
                }
            }
            return ['info' => $info, 'luck' => $luck, 'exe_opts' => $exe_opts];
        }

        public function check_shop_item()
        {
            return $this->website->db('web')->query('SELECT id FROM DmN_Shop_Logs WHERE SUBSTRING(item_hex ,7 ,8) = \'' . $this->game_db->sanitize_var(substr($this->item, 6, 8)) . '\'')->fetch();
        }

        public function add_market_item($info, $price, $ptype, $time, $char, $highlight)
        {
            if(in_array($ptype, [4, 5, 6, 7, 8, 9])){
                $price_jewels = $price;
                $price_type = $ptype;
            } else{
                $price_jewels = 0;
                $price_type = 0;
            }
            $stmt = $this->website->db('web')->prepare('INSERT INTO DmN_Market (cat, item, price_type, price, seller, add_date, active_till, serial, serial2, has_luck, has_skill, lvl, highlighted, char, server, has_ancient, has_exe_1, has_exe_2, has_exe_3, has_exe_4, has_exe_5, has_exe_6, has_exe_7, has_exe_8, has_exe_9, is_sm, is_bk, is_me, is_mg, is_dl, is_sum, is_rf, is_gl, is_rw, is_sl, price_jewel, jewel_type, item_name)
										VALUES 
										(:cat, :item, :ptype, :price, :user, GETDATE(), \'' . $this->website->db('web')->sanitize_var(date('Ymd H:i:s', strtotime('+' . $time . ' days', time()))) . '\', :serial, :seriall, :luck, :skill, :lvl, :higlight, :char, :server, :has_ancient, :has_exe_a, :has_exe_b, :has_exe_c, :has_exe_d, :has_exe_e, :has_exe_f, :has_exe_h, :has_exe_i, :has_exe_j, :is_sm, :is_bk, :is_me, :is_mg, :is_dl, :is_sum, :is_rf, :is_gl, :is_rw, :is_sl, :price_jewel, :jewel_type, :item_name)');
            $stmt->execute([
				':cat' => $info['info']['cat'], 
				':item' => $this->item, 
				':ptype' => $ptype, 
				':price' => $price, 
				':user' => $this->session->userdata(['user' => 'username']), 
				':serial' => $info['info']['serial'], 
				':seriall' => $info['info']['serial2'], 
				':luck' => $info['luck'], 
				':skill' => $info['info']['skill'], 
				':lvl' => $info['info']['lvl'], 
				':higlight' => $highlight, 
				':char' => $char,
				':server' => $this->session->userdata(['user' => 'server']), 
				':has_ancient' => $info['info']['anc'], 
				':has_exe_a' => $info['exe_opts'][0], 
				':has_exe_b' => $info['exe_opts'][1], 
				':has_exe_c' => $info['exe_opts'][2], 
				':has_exe_d' => $info['exe_opts'][3], 
				':has_exe_e' => $info['exe_opts'][4], 
				':has_exe_f' => $info['exe_opts'][5],
				':has_exe_h' => $info['exe_opts'][6], 
				':has_exe_i' => $info['exe_opts'][7], 
				':has_exe_j' => $info['exe_opts'][8], 
				':is_sm' => $info['info']['class']['sm'], 
				':is_bk' => $info['info']['class']['bk'], 
				':is_me' => $info['info']['class']['me'], 
				':is_mg' => $info['info']['class']['mg'], 
				':is_dl' => $info['info']['class']['dl'], 
				':is_sum' => $info['info']['class']['bs'], 
				':is_rf' => $info['info']['class']['rf'], 
				':is_gl' => $info['info']['class']['gl'], 
				':is_rw' => $info['info']['class']['rw'], 
				':is_sl' => $info['info']['class']['sl'], 
				':price_jewel' => $price_jewels, 
				':jewel_type' => $price_type, 
				':item_name' => $info['info']['name']
			]);
        }

        public function check_existing_item()
        {
            $stmt = $this->website->db('web')->prepare('SELECT id FROM DmN_Market WHERE item = :item AND active = 1 AND sold != 1 AND removed != 1');
            $stmt->execute([':item' => $this->item]);
            return $stmt->fetch();
        }

        public function log_deleted_item($user = '', $server = '', $by_admin = 0)
        {
            $user = ($user != '') ? $user : $this->session->userdata(['user' => 'username']);
            $server = ($user != '') ? $server : $this->session->userdata(['user' => 'server']);
            $stmt = $this->website->db('web')->prepare('INSERT INTO DmN_Warehouse_Delete_Log (account, server, item, date, deleted_by_admin) VALUES (:account, :server, :item, GETDATE(), :by_admin)');
            $stmt->execute([':account' => $user, ':server' => $server, ':item' => $this->item, ':by_admin' => $by_admin]);
        }

        public function get_market_item_count()
        {
            return $this->website->db('web')->query('SELECT COUNT(*) AS count FROM DmN_Market WHERE seller = \'' . $this->website->db('web')->sanitize_var($this->session->userdata(['user' => 'username'])) . '\' AND DATEDIFF(day, add_date, GETDATE()) = 0')->fetch();
        }

        public function decrease_zen($account, $money)
        {
            $stmt = $this->game_db->prepare('UPDATE Warehouse SET Money = Money - :money WHERE AccountId = :account');
            return $stmt->execute([':money' => $money, ':account' => $account]);
        }

        public function add_zen($account, $money)
        {
            $stmt = $this->game_db->prepare('UPDATE Warehouse SET Money = Money + :money WHERE AccountId = :account');
            return $stmt->execute([':money' => $money, ':account' => $account]);
        }

        public function create_vault($user = '')
        {
            if($user != ''){
                $stmt = $this->game_db->prepare('INSERT INTO warehouse (AccountID, Items, Money, EndUseDate) VALUES (:user, cast(REPLICATE(char(0xff),' . $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_size') . ') as varbinary(' . $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'wh_size') . ')), 0, getdate())');
                $stmt->execute([':user' => $user]);
            } else{
                throw new Exception('Vault creation failed, user is not defined.');
            }
        }
		
		private function clean_hex($data)
        {
            if(substr_count($data, "\0")){
                $data = str_replace("\0", '', $data);
            }
            return strtoupper($data);
        } 
    }