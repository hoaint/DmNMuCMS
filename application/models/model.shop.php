<?php
    in_file();

    class Mshop extends model
    {
        public $items = [], $items_sql = [], $count_items = 0;
        public $pos = 1;
        public $errors = [];
        public $new_vault;
        private $vault_items, $category = '';
        private $exe_opts = [0 => 0, 1 => 1, 2 => 2, 3 => 4, 4 => 8, 5 => 16, 6 => 32];
        private $ancient_opts = [0 => 0, 1 => 9, 2 => 10];
        private $fenrir_opts = [0 => 0, 1 => 1, 2 => 2, 4 => 4];

        public function __contruct()
        {
            parent::__construct();
        }

        public function load_items($page = 1, $per_page = 20, $columns = 4, $category = '')
        {
            if($category != '')
                $this->category = 'AND item_cat = ' . $this->website->db('web')->sanitize_var($this->website->c($category)) . ''; else{
                $load_cat_list = file(APP_PATH . DS . 'data' . DS . 'shop' . DS . 'shop_cat_list.dmn');
                $cat_list = '';
                foreach($load_cat_list as $key => $category){
                    $category_data = explode('|', $category);
                    if($category_data[3] != 0){
                        $cat_list .= $category_data[0] . ',';
                    }
                }
                $this->category = 'AND item_cat IN (' . $this->website->db('web')->sanitize_var($this->website->c(substr($cat_list, 0, -1))) . ')';
            }
            $items = $this->website->db('web')->query('SELECT id, item_id, item_cat, name, original_item_cat, stick_level FROM DmN_Shopp WHERE price >= 1 ' . $this->category . ' ORDER BY  item_cat ASC, item_id ASC')->fetch_all();
            $this->count_items = count($items);
            $this->items_sql = array_slice($items, (int)(($page - 1) * $per_page), $per_page);
            foreach($this->items_sql as $value){
				if($this->iteminfo->setItemData($value['item_id'], $value['original_item_cat'], $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'))){
					$item_for = '';
					$sm4th = '';
					$bk4th = '';
					$me4th = '';
					$mg4th = '';
					$dl4th = '';
					$sum4th = '';
					$rf4th = '';
					$gl4th = '';
					//$rw4th = '';
					if(defined('MU_VERSION') && MU_VERSION >= 7){
						$sm4th = '7,';
						$bk4th = '23,';
						$me4th = '39,';
						$mg4th = '54,';
						$dl4th = '70,';
						$sum4th = '87,';
						$rf4th = '102,';
						$gl4th = '118,';
						//$rw4th = '135,';
					}
					if(array_key_exists('dw/sm', $this->iteminfo->item_data)){
						if($this->iteminfo->item_data['dw/sm'] == 1){
							$item_for .= '0,1,2,'.$sm4th;
						}
						if($this->iteminfo->item_data['dw/sm'] == 2){
							$item_for .= '1,2,'.$sm4th;
						}
						if($this->iteminfo->item_data['dw/sm'] == 3){
							$item_for .= '2,'.$sm4th;
						}
						if($this->iteminfo->item_data['dw/sm'] == 4){
							$item_for .= '7,';
						}
					}
					if(array_key_exists('dk/bk', $this->iteminfo->item_data)){
						if($this->iteminfo->item_data['dk/bk'] == 1){
							$item_for .= '16,17,18,'.$bk4th;
						}
						if($this->iteminfo->item_data['dk/bk'] == 2){
							$item_for .= '17,18,'.$bk4th;
						}
						if($this->iteminfo->item_data['dk/bk'] == 3){
							$item_for .= '18,'.$bk4th;
						}
						if($this->iteminfo->item_data['dk/bk'] == 4){
							$item_for .= '23,';
						}
					}
					if(array_key_exists('elf/me', $this->iteminfo->item_data)){
						if($this->iteminfo->item_data['elf/me'] == 1){
							$item_for .= '32,33,34,'.$me4th;
						}
						if($this->iteminfo->item_data['elf/me'] == 2){
							$item_for .= '33,34,'.$me4th;
						}
						if($this->iteminfo->item_data['elf/me'] == 3){
							$item_for .= '34,'.$me4th;
						}
						if($this->iteminfo->item_data['elf/me'] == 4){
							$item_for .= '39,';
						}
					}
					if(array_key_exists('mg', $this->iteminfo->item_data)){
						if($this->iteminfo->item_data['mg'] == 1){
							$item_for .= '48,49,50,'.$mg4th;
						}
						if($this->iteminfo->item_data['mg'] == 2){
							$item_for .= '49,50,'.$mg4th;
						}
						if($this->iteminfo->item_data['mg'] == 3){
							$item_for .= '49,50,'.$mg4th;
						}
						if($this->iteminfo->item_data['mg'] == 4){
							$item_for .= '54,';
						}
					}
					if(array_key_exists('dl', $this->iteminfo->item_data)){
						if($this->iteminfo->item_data['dl'] == 1){
							$item_for .= '64,65,66,'.$dl4th;
						}
						if($this->iteminfo->item_data['dl'] == 2){
							$item_for .= '65,66,'.$dl4th;
						}
						if($this->iteminfo->item_data['dl'] == 3){
							$item_for .= '65,66,'.$dl4th;
						}
						if($this->iteminfo->item_data['dl'] == 4){
							$item_for .= '70,';
						}
					}
					if(array_key_exists('sum', $this->iteminfo->item_data)){
						if($this->iteminfo->item_data['sum'] == 1){
							$item_for .= '80,81,82,'.$sum4th;
						}
						if($this->iteminfo->item_data['sum'] == 2){
							$item_for .= '81,82,'.$sum4th;
						}
						if($this->iteminfo->item_data['sum'] == 3){
							$item_for .= '82,'.$sum4th;
						}
						if($this->iteminfo->item_data['sum'] == 4){
							$item_for .= '87,';
						}
					}
					if(array_key_exists('rf', $this->iteminfo->item_data)){
						if($this->iteminfo->item_data['rf'] == 1){
							$item_for .= '96,98,'.$rf4th;
						}
						if($this->iteminfo->item_data['rf'] == 2){
							$item_for .= '98,'.$rf4th;
						}
						if($this->iteminfo->item_data['rf'] == 3){
							$item_for .= '102,';
						}
						//if($this->iteminfo->item_data['rf'] == 4){
						//	$item_for .= '102,';
						//}
					}
					if(array_key_exists('gl', $this->iteminfo->item_data)){
						if($this->iteminfo->item_data['gl'] == 1){
							$item_for .= '112,114,'.$gl4th;
						}
						if($this->iteminfo->item_data['gl'] == 2){
							$item_for .= '114,'.$gl4th;
						}
						if($this->iteminfo->item_data['gl'] == 3){
							$item_for .= '118,';
						}
						//if($this->iteminfo->item_data['gl'] == 4){
						//	$item_for .= '118,';
						//}
					}
					if(array_key_exists('rw', $this->iteminfo->item_data)){
						if($this->iteminfo->item_data['rw'] == 1){
							$item_for .= '128,129,131,135,';
						}
						if($this->iteminfo->item_data['rw'] == 2){
							$item_for .= '129,131,135,';
						}
						if($this->iteminfo->item_data['rw'] == 3){
							$item_for .= '131,135,';
						}
						if($this->iteminfo->item_data['rw'] == 4){
							$item_for .= '135,';
						}
					}
					if(array_key_exists('sl', $this->iteminfo->item_data)){
						if($this->iteminfo->item_data['sl'] == 1){
							$item_for .= '144,145,147,151,';
						}
						if($this->iteminfo->item_data['sl'] == 2){
							$item_for .= '145,147,151,';
						}
						if($this->iteminfo->item_data['sl'] == 3){
							$item_for .= '147,151,';
						}
						if($this->iteminfo->item_data['sl'] == 4){
							$item_for .= '151,';
						}
					}
					if($item_for != ''){
						$item_for = preg_replace('/,$/', '', preg_replace('/[,,]/', ',', $item_for));
						$item_for = (strstr($item_for, ',')) ? explode(',', $item_for) : [$item_for];
						$class = __('Can be equipped by ');

						foreach($item_for as $class_code){
							$class .= $this->website->get_char_class($class_code, true) . ',';
						}
					} else{
						$class = __('Can be used by all classes');
					}
				} else{
					$class = __('Undefined');
				}
                
                $this->items[] = [
					'id' => $value['id'], 
					'item_id' => $value['item_id'], 
					'name' => htmlspecialchars($value['name']), 
					'class' => preg_replace('~(.*)' . preg_quote(',', '~') . '~', '$1' . '', $class, 1), 
					'image' => ($value['stick_level'] > 0) ? $this->itemimage->load($value['item_id'], $value['item_cat'], $value['stick_level']) : $this->itemimage->load($value['item_id'], $value['item_cat'], 0), 
					'pos' => $this->pos
				];
                if($this->pos == $columns){
                    $this->pos = 0;
                }
                $this->pos++;
            }
            return $this->items;
        }

        public function get_item_info($id = '')
        {
            if($id == '')
                return false;
            $stmt = $this->website->db('web')->prepare('SELECT TOP 1 id, item_id, item_cat, exetype, name, luck, price, max_item_lvl, max_item_opt, use_sockets, use_harmony, use_refinary, payment_type, original_item_cat, total_bought, stick_level FROM DmN_Shopp WHERE id = :id AND price >= 1');
            $stmt->execute([':id' => (int)$id]);
            if($item = $stmt->fetch()){
				if($this->iteminfo->setItemData($item['item_id'], $item['original_item_cat'], $this->website->get_value_from_server($this->session->userdata(['user' => 'server']), 'item_size'))){
					$item['data'] = $this->iteminfo->item_data;
					$item['image'] = $this->itemimage->load($item['item_id'], $item['item_cat'], $item['stick_level']);
					$item['ancient_info'] = $this->webshop->load_ancient_settings();
					$item['socket_info'] = $this->socket_list($item['use_sockets'], $this->config->config_entry('shop_' . $this->session->userdata(['user' => 'server']) . '|check_socket_part_type'), $item['exetype'], $item['original_item_cat']);
					$item['price_p'] = $this->discount($item['price']);
					$price_info = $this->load_custom_item_price($item['item_id'], $item['original_item_cat']);
					if($price_info != false){
						if(array_key_exists($this->session->userdata(['user' => 'server']), $price_info)){
							if($price_info[$this->session->userdata(['user' => 'server'])] <= 0){
								return 'disabled';
							} else{
								$item['price'] = $price_info[$this->session->userdata(['user' => 'server'])];
								$item['price_p'] = $this->discount($price_info[$this->session->userdata(['user' => 'server'])]);
							}
						}
					}
					return $item;
				}
            }
            return false;
        }

        public function load_custom_item_price($id, $cat)
        {
            $stmt = $this->website->db('web')->prepare('SELECT price FROM DmN_Shop_Custom_Price_List WHERE item_id = :id AND item_cat = :cat');
            $stmt->execute([':id' => $id, ':cat' => $cat]);
            $info = $stmt->fetch();
            if($info){
                if(substr_count($info['price'], "\0")){
                    $info['price'] = strtoupper(str_replace("\0", '', $info['price']));
                }
                return unserialize($info['price']);
            }
            return false;
        }

        public function load_harmony_values($cat = 0, $hopt = 0)
        {
            return $this->website->db('web')->query('SELECT hvalue, hname FROM DmN_Shop_Harmony WHERE itemtype = ' . $this->website->db('web')->sanitize_var($this->get_type($cat)) . ' AND hoption = ' . $this->website->db('web')->sanitize_var($hopt) . ' AND status = 1')->fetch_all();
        }

        public function check_harmony($use = 0, $harmony = [])
        {
            if($use == 1){
                if(count($harmony) == 2){
                    $check_harmony = $this->website->db('web')->snumrows('SELECT COUNT(id) AS count FROM DmN_Shop_Harmony WHERE hoption = ' . $this->website->db('web')->sanitize_var($harmony[0]) . ' AND hvalue = ' . $this->website->db('web')->sanitize_var($harmony[1]) . ' AND status = 1');
                    return $check_harmony > 0;
                } else{
                    return false;
                }
            }
            return [];
        }

        public function get_harmony_price($cat = 0, $hopt = 0, $hval = 0)
        {
            $info = $this->website->db('web')->query('SELECT TOP 1 price FROM DmN_Shop_Harmony WHERE itemtype = ' . $this->website->db('web')->sanitize_var($this->get_type($cat)) . ' AND hoption = ' . $this->website->db('web')->sanitize_var($hopt) . ' AND hvalue = ' . $this->website->db('web')->sanitize_var($hval) . ' AND status = 1')->fetch();
            return $this->discount($info['price']);
        }

        public function get_socket_price($socket)
        {
            $info = $this->website->db('web')->query('SELECT TOP 1 socket_price FROM DmN_Shop_Sockets WHERE socket_id = ' . $this->website->db('web')->sanitize_var($socket) . ' AND status != 0')->fetch();
            return $this->discount($info['socket_price']);
        }

        public function socket_list($use_sockets = 1, $check_part = 1, $exe_type = 1, $cat)
        {
            $exe_type = ($cat <= 5) ? 1 : 0;
            if($use_sockets == 1){
                if($check_part == 1){
                    $sockets = $this->website->db('web')->query('SELECT seed, socket_id, socket_name FROM DmN_Shop_Sockets WHERE status != 0  AND socket_part_type IN (-1, ' . $exe_type . ') ORDER BY orders ASC')->fetch_all();
                } else{
                    $sockets = $this->website->db('web')->query('SELECT seed, socket_id, socket_name FROM DmN_Shop_Sockets WHERE status != 0 ORDER BY orders ASC')->fetch_all();
                }
                return $sockets;
            }
        }

        public function check_sockets_part_type($exe_type, $socket, $seed, $cat)
        {
            $exe_type = ($cat <= 5) ? 1 : 0;
            return $this->website->db('web')->query('SELECT seed, socket_id, value FROM DmN_Shop_Sockets WHERE socket_id = ' . $this->website->db('web')->sanitize_var($socket) . ' AND seed = ' . $this->website->db('web')->sanitize_var($seed) . ' AND status != 0 AND socket_part_type IN (-1, ' . $exe_type . ')')->fetch();
        }

        public function check_sockets($socket, $seed)
        {
            return $this->website->db('web')->query('SELECT seed, socket_id, value FROM DmN_Shop_Sockets WHERE socket_id = ' . $this->website->db('web')->sanitize_var($socket) . ' AND seed = ' . $this->website->db('web')->sanitize_var($seed) . ' AND status != 0')->fetch();
        }

        public function is_socket_item($id, $cat)
        {
            return $this->website->db('web')->snumrows('SELECT COUNT(id) AS count FROM DmN_Shopp WHERE item_id = ' . $this->website->db('web')->sanitize_var($id) . ' AND original_item_cat = ' . $this->website->db('web')->sanitize_var($cat) . ' AND use_sockets = 1');
        }

        public function generate_serial()
        {
            $query = $this->game_db->query('EXEC WZ_GetItemSerial');
            $data = $query->fetch();
            $query->close_cursor();
            return $data;
        }

        public function generate_serial2($count = 1)
        {
            $query = $this->game_db->query('EXEC WZ_GetItemSerial2 ' . $this->game_db->sanitize_var($count) . '');
            $data = $query->fetch();
            $query->close_cursor();
            return $data;
        }

        public function discount($price)
        {
            $disc = (strtotime($this->config->config_entry('shop_' . $this->session->userdata(['user' => 'server']) . '|discount_time')) >= time()) ? $this->config->config_entry('shop_' . $this->session->userdata(['user' => 'server']) . '|discount') : 0;
            return ($disc == 1) ? floor($price - (($price / 100) * $this->config->config_entry('shop_' . $this->session->userdata(['user' => 'server']) . '|discount_perc'))) : $price;
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
                $stmt = $this->game_db->prepare('SELECT ' . substr($items_sql, 0, -2) . ' FROM Warehouse WHERE AccountId = :user');
                $stmt->execute([':user' => $user]);
                if($this->vault_items = $stmt->fetch()){
                    $items = unpack('H*', implode('', $this->vault_items));
                    $this->vault_items['Items'] = $this->clean_hex($items[1]);																	  
                    return $this->vault_items;
                } else{
                    return false;
                }
            } else{
                $sql = (DRIVER == 'pdo_odbc') ? 'Items' : 'CONVERT(IMAGE, Items) AS Items';
                $stmt = $this->game_db->prepare('SELECT ' . $sql . ' FROM Warehouse WHERE AccountId = :user');
                $stmt->execute([':user' => $user]);
                if($this->vault_items = $stmt->fetch()){ 
					if(in_array(DRIVER, ['sqlsrv', 'pdo_sqlsrv', 'pdo_dblib'])){
						$unpack = unpack('H*', $this->vault_items['Items']);
						$this->vault_items['Items'] = $this->clean_hex($unpack[1]);
					}
					else{
						$this->vault_items['Items'] = $this->clean_hex($this->vault_items['Items']);
					}
                    return $this->vault_items;
                } else{
                    return false;
                }
            }
        }

        private function clean_hex($data)
        {
            if(substr_count($data, "\0")){
                $data = str_replace("\0", '', $data);
            }
            return strtoupper($data);
        }
        public function check_space($items, $item_x, $item_y, $multiplier = 120, $size = 32, $hor = 8, $ver = 15, $add_to_slot = false)
        {
            $spots = str_repeat('0', $multiplier);
            $items_array = str_split($items, $size);
            for($i = 0; $i < $multiplier; ++$i){
                if($items_array[$i] != str_repeat('F', $size) && !empty($items_array[$i])){
                    $this->iteminfo->itemData($items_array[$i]);
                    if($this->iteminfo->getX() == false || $this->iteminfo->getY() == false){
                        $this->errors[] = sprintf(__('Found unknown item in warehouse please remove it first. Slot: %d') . $i);
                        return null;
                    }
                    $y = 0;
                    while($y < $this->iteminfo->getY()){
                        $y++;
                        $x = 0;
                        while($x < $this->iteminfo->getX()){
                            $spots = substr_replace($spots, '1', ($i + $x) + (($y - 1) * $hor), 1);
                            $x++;
                        }
                    }
                }
            }
            for($y2 = 0; $y2 <= $ver - $item_y; $y2++){
                for($x2 = 0; $x2 <= $hor - $item_x; $x2++){
                    if($this->search($x2, $y2, $item_x, $item_y, $spots, $hor)){
                        if(!$add_to_slot){
                            return $x2 + ($y2 * $hor);
                        } else{
                            if($add_to_slot == ($x2 + ($y2 * $hor)))
                                return ($x2 + ($y2 * $hor));
                        }
                    }
                }
            }
            $this->errors[] = __('Please free up space in your warehouse.');
            return null;
        }

        public function search($x, $y, $item_w, $item_h, &$spots, $vault_w)
        {
            for($yy = 0; $yy < $item_h; $yy++){
                for($xx = 0; $xx < $item_w; $xx++){
                    if($spots[$x + $xx + (($y + $yy) * $vault_w)] != '0')
                        return false;
                }
            }
            return true;
        }

        public function generate_new_items($new_item, $slot, $multiplier = 120, $size = 32, $items = false, $return = false)
        {
            $items = ($items != false) ? $items : $this->vault_items['Items'];
            for($x = 0; $x < $multiplier; ++$x){
                $ware_array[$x] = substr($items, $x * $size, $size);
            }
            $ware_array[$slot] = $new_item;
            $this->new_vault = implode('', $ware_array);
            if($return)
                return $this->new_vault;
        }

        public function update_warehouse($user = '')
        {
            $user = ($user != '') ? $user : $this->session->userdata(['user' => 'username']);
            $stmt = $this->game_db->prepare('UPDATE Warehouse SET Items = 0x' . $this->new_vault . ' WHERE AccountId = :user');
            $stmt->execute([':user' => $this->website->c($user)]);
        }

        public function set_total_bought($id, $cat)
        {
            $stmt = $this->website->db('web')->prepare('UPDATE DmN_Shopp SET total_bought = total_bought + 1 WHERE item_id = :id AND item_cat = :cat');
            $stmt->execute([':id' => $id, ':cat' => $cat]);
        }

        public function log_purchase($hex, $price, $method)
        {
            $stmt = $this->website->db('web')->prepare('INSERT INTO DmN_Shop_Logs (memb___id, server, item_hex, date, price, price_type, ip) VALUES (:user, :server, :hex, GETDATE(), :price, :price_text, :ip)');
            $stmt->execute([':user' => $this->session->userdata(['user' => 'username']), ':server' => $this->session->userdata(['user' => 'server']), ':hex' => $hex, ':price' => $price, ':price_text' => $method, ':ip' => $this->website->ip()]);
        }

        private function get_type($cat)
        {
            if($cat < 5)
                return 1; else if($cat == 5)
                return 2;
            else if($cat > 5)
                return 3;
            else
                return 1;
        }

        public function add_item_to_card($hex, $price, $payment_type)
        {
            $stmt = $this->website->db('web')->prepare('INSERT INTO DmN_Shop_Card (account, item_hex, price, price_type, server, time_added) VALUES (:account, :item_hex, :price, :price_type, :server, :time_added)');
            $stmt->execute([':account' => $this->session->userdata(['user' => 'username']), ':item_hex' => $hex, ':price' => $price, ':price_type' => $payment_type, ':server' => $this->session->userdata(['user' => 'server']), ':time_added' => time()]);
        }

        public function load_card_items($type = 1)
        {
            $stmt = $this->website->db('web')->prepare('SELECT id, item_hex, price FROM DmN_Shop_Card WHERE account = :account AND price_type = :type AND server = :server AND bought = 0 AND time_added >= :time_until_expires');
            $stmt->execute([':account' => $this->session->userdata(['user' => 'username']), ':type' => $type, ':server' => $this->session->userdata(['user' => 'server']), ':time_until_expires' => time() - $this->config->config_entry('shop_' . $this->session->userdata(['user' => 'server']) . '|card_item_expires')]);
            return $stmt->fetch_all();
        }

        public function item_exist_in_cart($id)
        {
            $stmt = $this->website->db('web')->prepare('SELECT TOP 1 id, item_hex, price, price_type FROM DmN_Shop_Card WHERE account = :account AND id = :id AND server = :server AND bought = 0 AND time_added >= :time_until_expires');
            $stmt->execute([':account' => $this->session->userdata(['user' => 'username']), ':id' => $id, ':server' => $this->session->userdata(['user' => 'server']), ':time_until_expires' => time() - $this->config->config_entry('shop_' . $this->session->userdata(['user' => 'server']) . '|card_item_expires')]);
            return $stmt->fetch();
        }

        public function remove_item_from_cart($id)
        {
            $stmt = $this->website->db('web')->prepare('DELETE FROM DmN_Shop_Card WHERE account = :account AND id = :id AND server = :server AND bought = 0');
            $stmt->execute([':account' => $this->session->userdata(['user' => 'username']), ':id' => $id, ':server' => $this->session->userdata(['user' => 'server'])]);
        }

        public function change_cart_item_status($hex)
        {
            $stmt = $this->website->db('web')->prepare('UPDATE DmN_Shop_Card SET bought = 1 WHERE account = :account AND item_hex = :hex AND server = :server AND bought = 0');
            $stmt->execute([':account' => $this->session->userdata(['user' => 'username']), ':hex' => $hex, ':server' => $this->session->userdata(['user' => 'server'])]);
        }

        public function get_not_added_item_price($hex)
        {
            $stmt = $this->website->db('web')->prepare('SELECT TOP 1 price FROM DmN_Shop_Card WHERE account = :account AND item_hex = :hex AND server = :server AND bought = 0');
            $stmt->execute([':account' => $this->session->userdata(['user' => 'username']), ':hex' => $hex, ':server' => $this->session->userdata(['user' => 'server'])]);
            return $stmt->fetch();
        }

        public function change_name_history()
        {
            $stmt = $this->website->db('web')->prepare('SELECT old_name, new_name, change_date FROM DmN_ChangeName_History WHERE account = :account AND server = :server ORDER BY change_date DESC');
            $stmt->execute([':account' => $this->session->userdata(['user' => 'username']), ':server' => $this->session->userdata(['user' => 'server'])]);
            return $stmt->fetch_all();
        }

        public function check_vip($id, $server)
        {
            $stmt = $this->website->db('web')->prepare('SELECT [id]
                                              ,[package_title]
                                              ,[price]
                                              ,[payment_type]
                                              ,[server]
                                              ,[status]
                                              ,[vip_time]
                                              ,[reset_price_decrease]
                                              ,[reset_level_decrease]
											  ,[reset_bonus_points]
                                              ,[grand_reset_bonus_credits]
                                              ,[hide_info_discount]
                                              ,[pk_clear_discount]
                                              ,[clear_skilltree_discount]
                                              ,[online_hour_exchange_bonus]
                                              ,[change_name_discount]
											  ,[change_class_discount]
                                              ,[bonus_credits_for_donate]
                                              ,[shop_discount]
											  ,[wcoins]
                                              ,[connect_member_load]
                                              ,[server_vip_package]
                                              ,[server_bonus_info] FROM DmN_Vip_Packages WHERE id = :id AND server = :server AND status = 1');
            $stmt->execute([':id' => $id, ':server' => $server]);
            return $stmt->fetch();
        }

        public function load_vip_packages($server = false)
        {
			if(!$server)
				$server = $this->session->userdata(['user' => 'server']);
            $stmt = $this->website->db('web')->prepare('SELECT [id]
                                              ,[package_title]
                                              ,[price]
                                              ,[payment_type]
                                              ,[server]
                                              ,[status]
                                              ,[vip_time]
                                              ,[reset_price_decrease]
                                              ,[reset_level_decrease]
											  ,[reset_bonus_points]
                                              ,[grand_reset_bonus_credits]
                                              ,[hide_info_discount]
                                              ,[pk_clear_discount]
                                              ,[clear_skilltree_discount]
                                              ,[online_hour_exchange_bonus]
                                              ,[change_name_discount]
											  ,[change_class_discount]
                                              ,[bonus_credits_for_donate]
                                              ,[shop_discount]
											  ,[wcoins]
                                              ,[connect_member_load]
                                              ,[server_vip_package]
                                              ,[server_bonus_info] FROM DmN_Vip_Packages WHERE server = :server  AND status = 1 AND is_registration_package != 1 ORDER BY id ASC');
            $stmt->execute([':server' => $server]);
            return $stmt->fetch_all();
        }

        public function load_registration_vip_packages($server = '')
        {
            $srv = ($server != '') ? ' AND server = \'' . $this->website->db('web')->sanitize_var($server) . '\'' : '';
            return $this->website->db('web')->query('SELECT [id]
                                              ,[package_title]
                                              ,[price]
                                              ,[payment_type]
                                              ,[server]
                                              ,[status]
                                              ,[vip_time]
                                              ,[reset_price_decrease]
                                              ,[reset_level_decrease]
                                              ,[grand_reset_bonus_credits]
                                              ,[hide_info_discount]
                                              ,[pk_clear_discount]
                                              ,[clear_skilltree_discount]
                                              ,[online_hour_exchange_bonus]
                                              ,[change_name_discount]
											  ,[change_class_discount]
                                              ,[bonus_credits_for_donate]
                                              ,[shop_discount]
                                              ,[connect_member_load]
                                              ,[server_vip_package]
                                              ,[server_bonus_info] FROM DmN_Vip_Packages WHERE is_registration_package = 1 ' . $srv . '')->fetch_all();
        }

        public function check_existing_vip_package($user = false, $server = false)
        {
			if(!$user)
				$user = $this->session->userdata(['user' => 'username']);
			if(!$server)
				$server = $this->session->userdata(['user' => 'server']);
            $stmt = $this->website->db('web')->prepare('SELECT viptype, viptime FROM DmN_Vip_Users WHERE memb___id = :account AND server = :server');
            $stmt->execute([':account' => $user, ':server' => $server]);
            return $stmt->fetch();
        }

        public function update_vip_package($id, $viptime, $user = false, $server = false)
        {
			if(!$user)
				$user = $this->session->userdata(['user' => 'username']);
			if(!$server)
				$server = $this->session->userdata(['user' => 'server']);
            $stmt = $this->website->db('web')->prepare('UPDATE DmN_Vip_Users SET viptype = :id, viptime = :viptime WHERE memb___id = :account AND server = :server');
            return $stmt->execute([':id' => $id, ':viptime' => $viptime, ':account' => $user, ':server' => $server]);
        }

        public function insert_vip_package($id, $viptime, $user = '', $server = '')
        {
            $user = ($user != '') ? $user : $this->session->userdata(['user' => 'username']);
            $server = ($server != '') ? $server : $this->session->userdata(['user' => 'server']);
            $stmt = $this->website->db('web')->prepare('INSERT INTO DmN_Vip_Users (viptype, viptime, memb___id, server) VALUES (:id, :viptime, :account, :server)');
            return $stmt->execute([':id' => $id, ':viptime' => $viptime, ':account' => $user, ':server' => $server]);
        }
		
		public function remove_vip_package($user, $server){
			$stmt = $this->website->db('web')->prepare('DELETE FROM DmN_Vip_Users WHERE memb___id = :account AND server = :server');
            $stmt->execute([':account' => $user, ':server' => $server]);
		}

        public function add_server_vip($viptime, $viptype, $connect_member_load, $query_config, $user = false)
        {
            $user = ($user != false) ? $user : $this->session->userdata(['user' => 'username']);
            if($viptype != null){
                if(substr_count($viptype, '|') > 0){
                    $vip = explode('|', $viptype);
                    $package = $vip[0];
                    $paycode = $query_config['quearies'][$package]['vip_codes'][$vip[1]]['code'];
                } else{
                    $package = $viptype;
                    $paycode = -1;
                }
                if($this->check_server_vip($query_config['quearies'][$package]['check'], $user)){
                    $this->update_server_vip($viptime, $paycode, $query_config['quearies'][$package]['update'], $user);
                } else{
                    if(!empty($query_config['quearies'][$package]['insert'])){
                        $this->insert_server_vip($viptime, $paycode, $query_config['quearies'][$package]['insert'], $user);
                    }
                }
                if($connect_member_load != null){
                    $this->add_to_connect_member($connect_member_load);
                }
            }
        }

        private function check_server_vip($query, $user = false)
        {
            $stmt = $this->account_db->prepare($query);
            $stmt->execute([':account' => $user]);
            return $stmt->fetch();
        }

        private function update_server_vip($viptime, $paycode, $query, $user = false)
        {
            $stmt = $this->account_db->prepare($query);
            $data = [':until_date' => ($paycode != -1) ? date('Y-m-d H:i:s', $viptime) : $viptime];
            if(preg_match('/:type/', $query)){
                $data[':type'] = $paycode;
            }
            $data[':account'] = $user;
            return $stmt->execute($data);
        }

        private function insert_server_vip($viptime, $paycode, $query, $user = false)
        {
            $stmt = $this->account_db->prepare($query);
            $data = [':account' => $user, ':until_date' => ($paycode != -1) ? date('Y-m-d H:i:s', $viptime) : $viptime];
            if(preg_match('/:type/', $query)){
                $data[':type'] = $paycode;
            }
            return $stmt->execute($data);
        }

        private function add_to_connect_member($connect_member_load, $user = false)
        {
            $info = pathinfo($connect_member_load);
            if(isset($info['extension']) && $info['extension'] == 'txt'){
                $this->write_to_txt_member($connect_member_load, $user);
            }
            if(isset($info['extension']) && $info['extension'] == 'xml'){
                $this->write_to_xml_member($connect_member_load, $user);
            }
        }

        private function write_to_txt_member($connect_member_load, $user = false)
        {
            if(is_writable($connect_member_load)){
                $acc_exists = false;
                $file = file($connect_member_load);
                foreach($file AS $line){
                    if((substr($line, 0, 2) !== '//')){
                        if(trim($line) == '"' . $user . '"'){
                            $acc_exists = true;
                            break;
                        }
                    }
                }
                if(!$acc_exists){
                    file_put_contents($connect_member_load, preg_replace('/^\h*\v+/m', '', implode(PHP_EOL, $file)));
                    file_put_contents($connect_member_load, '"' . $user . '"' . PHP_EOL, FILE_APPEND);
                }
            }
        }

        private function write_to_xml_member($connect_member_load, $user = false)
        {
            if(is_writable($connect_member_load)){
                $data = simplexml_load_file($connect_member_load);
                $acc_exists = false;
                foreach($data->Account AS $accounts){
                    if($accounts->attributes()->Name == $user){
                        $acc_exists = true;
                        break;
                    }
                }
                if(!$acc_exists){
                    $doc = new DomDocument();
                    $doc->formatOutput = true;
                    if($xml = file_get_contents($connect_member_load)){
                        $doc->loadXML($xml, LIBXML_NOBLANKS);
                        $ConnectMember = $doc->getElementsByTagName('ConnectMember')->item(0);
                        $Account = $doc->createElement('Account');
                        $NameAttribute = $doc->createAttribute("Name");
                        $NameAttribute->value = $user;
                        $Account->appendChild($NameAttribute);
                        $ConnectMember->appendChild($Account);
                        $doc->save($connect_member_load);
                    }
                }
            }
        }
    }