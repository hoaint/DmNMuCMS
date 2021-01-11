<?php
    in_file();

    class iteminfo extends library
    {
        public $hex = null;
        public $item_data;
        public $info = '';
        public $id = null;
        public $option = null;
        public $dur = null;
        public $serial = null;
        public $serial2 = null;
        public $exe = null;
        public $ancient = null;
        public $cat = null;
        public $ref = 0;
        public $type = null;
        public $harmony = [0, 0];
        public $socket = [];
        public $level = 0;
        public $skill = '';
        public $skill_exists = null;
        public $luck = '';
        public $name = '';
        public $item_for = '';
        public $class = '';
        private $exe_option_list;
        private $exe_options;
		private $winggradeopt;
        private $exe_count = 0;
        private $elementtype = '';
        private $elementopt = '';
        private $errtel_rank;
        private $pentagram_option_info;
        private $skill_list;
        private $harmony_list;
        private $socket_list = '';
        private $socket_data;
        private $item_tooltip;
        private $item_tooltip_text;
        private $item_level_tooltip;
        public $addopt = '';
        public $refopt = '';
        public $haropt = '';
        public $sockopt = '';
        public $stamina = '';
        public $ancopt = '';
		public $anc_prefix = '';
        private $set_options;
        private $set_options_text;
        private $no_socket;
        private $empty_socket;
        private $seedopt = '';
        private $mountable_slots = 0;
        private $index;
		private $name_from_tooltip = false;
		private $isSocketItem = false;

        public function __construct()
        {
            $this->load->lib('serverfile');
            $this->no_socket = (SOCKET_LIBRARY == 1) ? 255 : 0;
            $this->empty_socket = (SOCKET_LIBRARY == 1) ? 254 : 255;
            $this->socket = (SOCKET_LIBRARY == 1) ? [255, 255, 255, 255, 255, 255] : [0, 0, 0, 0, 0, 0];
        }

        public function itemData($item = '', $load_item_settings = true)
        {
            if(preg_match('/[a-fA-F0-9]{20,64}/', $item)){
                $this->hex = $item;
                $this->calculateItemVariables();
                $this->option = hexdec(substr($this->hex, 2, 2));
                $this->dur = hexdec(substr($this->hex, 4, 2));
                $this->serial = substr($this->hex, 6, 8);
                $this->ancient = hexdec(substr($this->hex, 16, 2));
                $this->index = $this->itemIndex($this->type, $this->id);
                if(strlen($this->hex) >= 32){
                    $this->ref = hexdec(substr($this->hex, 19, 1));
                    $this->harmony[0] = hexdec(substr($this->hex, 20, 1));
                    $this->harmony[1] = hexdec(substr($this->hex, 21, 1));
                    $this->socket[1] = hexdec(substr($this->hex, 22, 2));
                    $this->socket[2] = hexdec(substr($this->hex, 24, 2));
                    $this->socket[3] = hexdec(substr($this->hex, 26, 2));
                    $this->socket[4] = hexdec(substr($this->hex, 28, 2));
                    $this->socket[5] = hexdec(substr($this->hex, 30, 2));
                    if(strlen($this->hex) == 64){
                        $this->serial2 = substr($this->hex, 32, 8);
                    }
                }
                if($load_item_settings){
                    $this->setItemData($this->id, $this->type, strlen($this->hex));
                }
            } else{
                writelog('Invalid item hex value. Value: ' . $item, 'system_error');
                return 'Invalid item hex value. Value: ' . $item;
            }
        }

        private function calculateItemVariables()
        {
            $this->exe = hexdec(substr($this->hex, 14, 2));
            if(strlen($this->hex) == 20){
                $temp_id = hexdec(substr($this->hex, 0, 2));
                $this->id = ($temp_id & 31);
                $this->type = (($temp_id & 224) >> 5);
                if(($this->exe & 128) == 128){
                    $this->type += 8;
                    $this->exe -= 128;
                }
            } else{
                $this->id = hexdec(substr($this->hex, 0, 2));
                $this->type = hexdec(substr($this->hex, 18, 1));
                if($this->exe >= 128){
                    $this->id += 256;
                    $this->exe -= 128;
                }
            }
        }

        public function itemIndex($type, $id)
        {
            return ($type * 512 + $id);
        }

        // @ioncube.dk use_funcs("DmN ","cms", "DmN") -> "DmNDmNCMS110Stable" RANDOM
        public function setItemData($id = false, $type = false, $size = 32)
        {
            static $data = [];
            if(!isset($data[$type]))
                $data[$type] = $this->serverfile->item_list($type, $size)->get('items');
            if($data[$type] !== false){
                if($id !== false){
                    if(array_key_exists($id, $data[$type])){
                        $this->item_data = $data[$type][$id];
                        return true;
                    } else{
                        writelog('Item file load error - item with id: ' . $id . ' not found in category: ' . $type, 'system_error');
                        throw new Exception('Item file load error - item with id: ' . $id . ' not found in category: ' . $type);
                    }
                } else{
                    $this->item_data = $data[$type];
                    return true;
                }
            } else{
                writelog('Item file load - error category with id: ' . $id . ' not found', 'system_error');
                throw new Exception('Item file load error - category with id: ' . $id . ' not found');
            }
            return false;
        }

        private function setItemTooltip()
        {
            static $data = [];
            if(empty($data))
                $data = $this->serverfile->item_tooltip()->get('item_tooltip');
            $this->item_tooltip = $data;
            if($this->item_tooltip != false){
                if(array_key_exists($this->type, $this->item_tooltip)){
                    if(array_key_exists($this->id, $this->item_tooltip[$this->type])){
                        $this->item_tooltip = $this->item_tooltip[$this->type][$this->id];
                        return true;
                    }
                }
            }
            return false;
        }

        private function getItemTooltip()
        {
            $this->setItemTooltip();
            return $this->item_tooltip[$this->type][$this->id];
        }

        private function setItemTooltipText()
        {
            static $data = [];
            if(empty($data))
                $data = $this->serverfile->item_tooltip_text()->get('item_tooltip_text');
            $this->item_tooltip_text = $data;
        }

        private function getItemTooltipText()
        {
            $this->setItemTooltipText();
            return $this->item_tooltip_text;
        }

        private function setItemLevelTooltip()
        {
            static $data = [];
            if(empty($data))
                $data = $this->serverfile->item_level_tooltip()->get('item_level_tooltip');
            $this->item_level_tooltip = $data;
        }

        private function getItemLevelTooltip()
        {
            $this->setItemLevelTooltip();
            return $this->item_level_tooltip;
        }

        private function setTooltipOptions()
        {
            if($this->setItemTooltip()){
                $this->getItemTooltipText();
                $this->tooltip_options = '';
                if($this->item_tooltip['iInfoLine_1'] != -1)
                    $this->tooltip_options .= $this->findTooltipOption($this->item_tooltip['iInfoLine_1']);
                if($this->item_tooltip['iInfoLine_2'] != -1)
                    $this->tooltip_options .= $this->findTooltipOption($this->item_tooltip['iInfoLine_2']);
                if($this->item_tooltip['iInfoLine_3'] != -1)
                    $this->tooltip_options .= $this->findTooltipOption($this->item_tooltip['iInfoLine_3']);
                if($this->item_tooltip['iInfoLine_4'] != -1)
                    $this->tooltip_options .= $this->findTooltipOption($this->item_tooltip['iInfoLine_4']);
                if($this->item_tooltip['iInfoLine_5'] != -1)
                    $this->tooltip_options .= $this->findTooltipOption($this->item_tooltip['iInfoLine_5']);
                if($this->item_tooltip['iInfoLine_6'] != -1)
                    $this->tooltip_options .= $this->findTooltipOption($this->item_tooltip['iInfoLine_6']);
                if($this->item_tooltip['iInfoLine_7'] != -1)
                    $this->tooltip_options .= $this->findTooltipOption($this->item_tooltip['iInfoLine_7']);
                if($this->item_tooltip['iInfoLine_8'] != -1)
                    $this->tooltip_options .= $this->findTooltipOption($this->item_tooltip['iInfoLine_8']);
                if($this->item_tooltip['iInfoLine_9'] != -1)
                    $this->tooltip_options .= $this->findTooltipOption($this->item_tooltip['iInfoLine_9']);
                if($this->item_tooltip['iInfoLine_10'] != -1)
                    $this->tooltip_options .= $this->findTooltipOption($this->item_tooltip['iInfoLine_10']);
                if($this->item_tooltip['iInfoLine_11'] != -1)
                    $this->tooltip_options .= $this->findTooltipOption($this->item_tooltip['iInfoLine_11']);
                if($this->item_tooltip['iInfoLine_12'] != -1)
                    $this->tooltip_options .= $this->findTooltipOption($this->item_tooltip['iInfoLine_12']);
                if($this->item_tooltip['Unk3'] != -1){
                    $this->checkItemLevelTooltip();
                }
				
                $this->tooltip_options = '<div class="item_light_blue item_size_12 item_font_family">' . preg_replace('/([0-9]{1,})+(%%)/i', '$1%', $this->tooltip_options) . '</div>';
            }
        }

        private function checkItemLevelTooltip()
        {
            $this->getItemLevelTooltip();
            if(array_key_exists($this->item_tooltip['Unk3'] + (int)substr($this->getLevel(), 1), $this->item_level_tooltip)){
                $value = $this->item_level_tooltip[$this->item_tooltip['Unk3'] + (int)substr($this->getLevel(), 1)];
                if($value[8] != -1){
                    $this->tooltip_options .= $this->findTooltipOption($value[8]);
                }
                if($value[10] != -1){
                    $this->tooltip_options .= $this->findTooltipOption($value[10]);
                }
                if($value[12] != -1){
                    $this->tooltip_options .= $this->findTooltipOption($value[12]);
                }
                if($value[14] != -1){
                    $this->tooltip_options .= $this->findTooltipOption($value[14]);
                }
                if($value[16] != -1){
                    $this->tooltip_options .= $this->findTooltipOption($value[16]);
                }
                if($value[18] != -1){
                    $this->tooltip_options .= $this->findTooltipOption($value[18]);
                }
                if($value[20] != -1){
                    $this->tooltip_options .= $this->findTooltipOption($value[20]);
                }
                if($value[22] != -1){
                    $this->tooltip_options .= $this->findTooltipOption($value[22]);
                }
                if($value[24] != -1){
                    $this->tooltip_options .= $this->findTooltipOption($value[24]);
                }
                if($value[26] != -1){
                    $this->tooltip_options .= $this->findTooltipOption($value[26]);
                }
                if($value[28] != -1){
                    $this->tooltip_options .= $this->findTooltipOption($value[28]);
                }
            }
        }

        private function findTooltipOption($id)
        {
            if(!in_array($id, [10, 12, 14, 16, 18, 20, 174, 357])){
                if(array_key_exists($id, $this->item_tooltip_text)){
                    if(array_key_exists(3, $this->item_tooltip_text[$id])){
                        return $this->setTooltipOptionValues($id, $this->item_tooltip_text[$id][2]) . '<br />';
                    }
                }
            }
        }

        private function setTooltipOptionValues($id, $value)
        {
            switch($id){
                case 0:
				case 1:
					return preg_replace('/(%[d](\s?)\~(\s?)%[d])/', $this->damage(), $value);
					break;
				case 2:
				case 72:
				case 71:
				case 56:
				case 86:
				case 198:
				case 359:
				case 395:
					return preg_replace('/(%[d])/', $this->dur, $value);
					break;
				case 260:
					return preg_replace('/(%[d])/', 5 - $this->dur, $value);
					break;		
				case 3:
				case 358:
					return preg_replace('/(%[d])/', $this->defense(), $value);
					break;
				case 5:
					return preg_replace('/(%[d])/', $this->successBlock(), $value);
					break;
				case 6:
				case 7:
					return preg_replace('/(%[d])/', $this->speed(), $value);
					break;
				case 8:
					return preg_replace('/(%[d]\/%[d])/', $this->dur . '/' . $this->durability(), $value);
					break;
				case 9:
					return preg_replace('/(%[d])/', $this->levelRequired(), $value);
					break;
				case 11:
					return preg_replace('/(%[d])/', $this->reqStr(), $value);
					break;
				case 13:
					return preg_replace('/(%[d])/', $this->reqAgi(), $value);
					break;
				case 17:
					return preg_replace('/(%[d])/', $this->reqEne(), $value);
					break;
				case 19:
					return preg_replace('/(%[d])/', $this->reqCom(), $value);
					break;
				case 21:
				case 22:
				case 23:
					return preg_replace('/(%[d]%)/', $this->magicPower(), $value);
					break;
				case 26:
				case 27:
				case 28:
				case 29:
				case 409:
				case 410:
				case 411:
				case 496:
				case 498:
				case 1022:
					return preg_replace('/(%[d]%)/', $this->increaseDamageWings(), $value);
					break;
				case 30:
				case 31:
				case 32:
				case 33:
				case 34:
				case 35:
				case 36:
				case 412:
				case 413:
				case 414:
				case 497:
				case 499:
				case 1023:
				case 1041:
					return preg_replace('/(%[d]%)/', $this->absorbDamageWings(), $value);
					break;
				case 37:
					return preg_replace('/(%[d]\s%[s])/', (((int)substr($this->getLevel(), 1) + 1) * 10) . ' ' . str_replace(' Bundle', '', $this->item_data['name']), $value);
					break;
				case 47:
					return preg_replace('/(%[s])/', $this->socketOptionName(), $value);
					break;
				case 53:
					return preg_replace('/(%[s])/', $this->socketOptionValue(), $value);
					break;
				case 337:
					return preg_replace('/(%[d])/', $this->iceRes(), $value);
					break;
				case 338:
					return preg_replace('/(%[d])/', $this->poisonRes(), $value);
					break;
				case 339:
					return preg_replace('/(%[d])/', $this->lightRes(), $value);
					break;
				case 340:
					return preg_replace('/(%[d])/', $this->fireRes(), $value);
					break;
				case 341:
					return preg_replace('/(%[d])/', $this->earthRes(), $value);
					break;
				case 342:
					return preg_replace('/(%[d])/', $this->windRes(), $value);
					break;
				case 343:
					return preg_replace('/(%[d])/', $this->waterRes(), $value);
					break;
				case 360:
					return preg_replace('/(%[d])/', $this->countMountableSlots(), $value);
					break;				
				default:
					return $value;
					break;
            }
        }

        public function getItemSkill()
        {
            static $data = [];
            if(empty($data))
                $data = $this->serverfile->skill()->get('skill');
            if($this->item_data['skill'] > 0){
                $option = $this->option;
                if($option >= 128){
                    $this->skill_list = $data;
                    if(array_key_exists($this->item_data['skill'], $this->skill_list)){
                        $this->skill = '<div class="item_light_blue item_size_12 item_font_family">' . $this->skill_list[$this->item_data['skill']][2] . ' ' . __('skill') . ' (' . __('Mana') . ':' . $this->skill_list[$this->item_data['skill']][5] . ')</div>';
                    }
                }
            }
        }

        public function hasSkill()
        {
            $skill = 0;
            if($this->item_data['skill'] > 0){
                $option = $this->option;
                if($option >= 128){
                    $skill = 1;
                }
            }
            return $skill;
        }

        public function getLevel()
        {
            $level = 0;
            $option = $this->option;
            if($option >= 128){
                $option -= 128;
            }
            while($option - 8 >= 0){
                $level++;
                $option -= 8;
            }
            if($option - 4 >= 0){
                $option -= $option;
            }
            return '+' . $level;
        }

        public function getOption()
        {
            $option = $this->option;
            if($option >= 128)
                $option -= 128;
            $option = $option - floor($option / 8) * 8;
            if($option >= 4)
                $option -= 4;
            if($this->exe >= 64)
                $option += 4;
            return $option;
        }

        public function getLuck()
        {
            $luck = 0;
			$option = $this->option;
			if($option >= 128)
				$option -= 128;
			$option = $option - floor($option / 8) * 8;
			if($option >= 4){
				$this->luck = '<div class="item_size_12 item_font_family item_luck">'.str_replace('(L', 'L', __('(Luck(success rate of Jewel of Soul +25%)')).'<br />'.__('Luck(critical damage rate +5%)').'</div>';
				$luck = 1;
			}
			return $luck;
        }

        public function additionalOption()
        {
            $this->addopt = '';
            $option = $this->getOption();
			
            if($option > 0){
                if($this->type == 6){
                    $this->addopt = __('Additional Defense Rate') . ' +' . ($option * 5);
                } else if($this->item_data['slot'] == 9 || $this->item_data['slot'] == 10 || $this->item_data['slot'] == 11){
                    $this->addopt = __('Automatic Hp Recovery') . ' ' . $option . '%';
                } else{
					if($this->item_data['slot'] == 7){
						if(array_key_exists('dw/sm', $this->item_data) && in_array($this->item_data['dw/sm'], [1, 2, 3])){
							$this->addopt = __('Additional Wizardy Dmg') . ' +' . $option * 4;
						}
						elseif(array_key_exists('elf/me', $this->item_data) && in_array($this->item_data['elf/me'], [1, 2, 3])){
							$this->addopt = __('Automatic HP recovery') . ' ' . $option . '%';
						}
						else{
							$this->addopt = __('Additional Dmg') . ' +' . $option * 4;
						}
					}
					else{
						$exe_type = $this->getExeType($this->item_data['slot'], $this->id, $this->type);
						if($exe_type == 1){
							$this->addopt = __('Additional Damage') . ' +' . $option * 4;
						}
						if($exe_type == 2){
							$this->addopt = __('Additional Defense') . ' +' . $option * 4;
						}
					}
                }
                $this->addopt = '<div class="item_light_blue item_size_12 item_font_family">' . $this->addopt . '</div>';
            }
        }

        public function exeOpts()
        {
            $exe = $this->exe;
            if($exe >= 64){
                $exe -= 64;
            }
            $exe_opts = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            if($exe >= 32){
                $exe_opts[6] = 6;
                $exe -= 32;
            }
            if($exe >= 16){
                $exe_opts[5] = 5;
                $exe -= 16;
            }
            if($exe >= 8){
                $exe_opts[4] = 4;
                $exe -= 8;
            }
            if($exe >= 4){
                $exe_opts[3] = 3;
                $exe -= 4;
            }
            if($exe >= 2){
                $exe_opts[2] = 2;
                $exe -= 2;
            }
            if($exe >= 1){
                $exe_opts[1] = 1;
                $exe -= 1;
            }
            if(MU_VERSION <= 5){
                for($i = 1; $i <= 3; $i++){
                    if(in_array($this->socket[$i], [6, 7, 8, 9])){
                        if($this->socket[$i] == 6){
                            $exe_opts[7] = 7;
                        }
                        if($this->socket[$i] == 7){
                            $exe_opts[8] = 8;
                        }
                        if($this->socket[$i] == 8){
                            $exe_opts[9] = 9;
                        }
                        if($this->socket[$i] == 9){
                            $exe_opts[10] = 10;
                        }
                    }
                }
            }
            return $exe_opts;
        }

        public function getExe()
        {
            $this->exe_options = '';
            $exe = $this->exe;
            if($exe >= 64){
                $exe -= 64;
            }
            if($exe >= 32){
                $this->exe_options .= $this->findExeOption(0) . '<br />';
                $exe -= 32;
                $this->exe_count += 1;
            }
            if($exe >= 16){
                $this->exe_options .= $this->findExeOption(1) . '<br />';
                $exe -= 16;
                $this->exe_count += 1;
            }
            if($exe >= 8){
                $this->exe_options .= $this->findExeOption(2) . '<br />';
                $exe -= 8;
                $this->exe_count += 1;
            }
            if($exe >= 4){
                if(!$this->isFenrir($exe)){
                    $this->exe_options .= $this->findExeOption(3) . '<br />';
                }
                $exe -= 4;
                $this->exe_count += 1;
            }
            if($exe >= 2){
                if(!$this->isFenrir($exe)){
                    $this->exe_options .= $this->findExeOption(4) . '<br />';
                }
                $exe -= 2;
                $this->exe_count += 1;
            }
            if($exe >= 1){
                if(!$this->isFenrir($exe)){
                    $this->exe_options .= $this->findExeOption(5) . '<br />';
                }
                $exe -= 1;
                $this->exe_count += 1;
            }
            $this->exe_options = '<div class="item_light_blue item_size_12 item_font_family">' . $this->exe_options . '</div>';
        }

        private function findExeOption($opt_number)
        {
            static $data = [];
            $kind = $this->getExeType($this->item_data['slot'], $this->id, $this->type);
            if(in_array($kind, [1, 2])){
                if(!isset($data['common']))
                    $data['common'] = $this->serverfile->exe_common()->get('exe_common');
                $this->exe_option_list = $data['common'];
            }
            if(in_array($kind, [24, 25, 26, 27, 28, 60, 62])){
                if(!isset($data['wings']))
                    $data['wings'] = $this->serverfile->exe_wing()->get('exe_wing');
                $this->exe_option_list = $data['wings'];
            }
			
            foreach($this->exe_option_list AS $key => $option){
                if($option[2] == $kind){
                    if($option[3] == $opt_number){
						if($opt_number == 2 && $this->type == 13 && in_array($this->id, [12, 25, 27])){
							$option = __('Increase Wizardy Dmg +2%');
						}
						else{
							$option = preg_replace('/(%[d])/', '+' . $option[7], preg_replace('/(%[d]%)/', $option[7], $option[4]));
						}
						
						$type = ($kind == 1) ? __('Attack') : __('Defense');
						$option = preg_replace('/(%[s])/', $type, $option);
						return $option;
						break;
						
                    }
                }
            }
        }

        private function isFenrir($exe)
        {
            if($this->type == 13 && $this->id == 37){
                if($exe == 1)
                    $this->exe_options = __('Increases final damage by 10%') . '<br />';
                if($exe == 2)
                    $this->exe_options = __('Absorbs 10% of final damage') . '<br />';
                if($exe == 4)
                    $this->exe_options = __('Fenrir +Illusion') . '<br />';
                return true;
            }
            return false;
        }

        public function getRefinery()
        {
            if(!in_array($this->type, [12, 13, 14, 15])){
                if($this->ref > 0){
                    $load_ref_file = file(APP_PATH . DS . 'data' . DS . 'shop' . DS . 'shop_ref_type.dmn');
                    foreach($load_ref_file as $loaded_ref_file){
                        $ref_opt = explode("|", $loaded_ref_file);
                        if($this->type == $ref_opt[0]){
                            $this->refopt = '<div class="item_pink item_size_12 item_font_family">' . $ref_opt[1] . '</div>';
                        }
                    }
                }
            }
        }

        public function getHarmony()
        {
            static $data = [];
			if($this->getExeType($this->item_data['slot'], $this->id, $this->type) == 76){
				$this->getWingsGradeOptions();
			}
			else{
				if($this->type <= 11){
					if($this->harmony[0] > 0){
						if(empty($data))
							$data = $this->serverfile->jewel_of_harmony_option()->get('jewel_of_harmony_option');
						$this->harmony_list = $data;
						$offset = 0;
						if($this->type < 5)
							$offset = -1;
						if($this->type == 5)
							$offset = 249;
						if($this->type > 5)
							$offset = 499;
						foreach($this->harmony_list AS $key => $value){
							if($value[1] == ($offset + $this->harmony[0])){
								$this->haropt = '<div class="item_yellow item_size_12 item_font_family">' . $value[3] . ' +' . $value[$this->harmony[1] + 4] . '</div>';
								break;
							}
						}
					}
				}
			}
        }
		
		public function getWingsGradeOptions(){
			static $data = [];
			if(empty($data))
				$data = $this->serverfile->item_grade_option()->get('item_grade_option');
			$opt = hexdec(substr($this->hex, 20, 2));
			if($opt != 255){
				$mvalues = [25, 35, 45, 55, 65, 75, 85, 96, 107, 118, 130, 142, 154, 167, 180, 193];
				$this->sockopt = '<div class="item_grey item_size_12 item_font_family">Elemental DEF (Lv. '.$opt.'): Increase by '.$mvalues[$opt].'</div>';
			}
			if($this->socket[5] != 255){
				$id = 0;
				if($this->socket[5] >= 96){
					$id = 6;
					$this->socket[5] -= 96;
				}
				if($this->socket[5] >= 80){
					$id = 5;
					$this->socket[5] -= 80;
				}
				if($this->socket[5] >= 64){
					$id = 4;
					$this->socket[5] -= 64;
				}
				if($this->socket[5] >= 48){
					$id = 3;
					$this->socket[5] -= 48;
				}
				if($this->socket[5] >= 32){
					$id = 2;
					$this->socket[5] -= 32;
				}
				if($this->socket[5] >= 16){
					$id = 1;
					$this->socket[5] -= 16;
				}
				$options = [
					0 => [20, 23, 27, 32, 38, 45, 53, 62, 72, 83, 95, 108, 122, 137, 153, 170, 'Elemental Damage Increase'],
					1 => [5, 10, 15, 20, 26, 32, 38, 45, 52, 59, 67, 75, 84, 93, 104, 125, 'Elemental Attack Success Rate Increase'],
					2 => [5, 10, 15, 20, 26, 32, 38, 45, 52, 59, 67, 75, 84, 93, 104, 125, 'Elemental Defense Success Rate Increase'],
					3 => [30, 34, 39, 45, 52, 60, 69, 79, 90, 102, 115, 129, 144, 160, 177, 195, 'Elemental Damage (II) Increase'],
					4 => [4, 6, 8, 10, 13, 16, 19, 23, 27, 31, 36, 41, 46, 52, 58, 64, 'Elemental Defense (II) Increase'],
					5 => [10, 15, 25, 35, 46, 57, 68, 80, 92, 104, 117, 130, 144, 158, 174, 200, 'Elemental Attack Success Rate (II) Increase'],
					6 => [10, 15, 25, 35, 46, 57, 68, 80, 92, 104, 117, 130, 144, 158, 174, 200, 'Elemental Defense Success Rate (II) Increase'],
				];
				if(isset($options[$id])){
					$this->sockopt .= '<div class="item_grey item_size_12 item_font_family">'.$options[$id][16].' (Lv. '.$this->socket[5].'): Increase by '.$options[$id][$this->socket[5]].'</div>';
				}
			}
			$this->winggradeopt = '';
			for($i = 1; $i <= 4; $i++){
				if($this->socket[$i] != 255){
					$id = 0;
					if($this->socket[$i] >= 160){
						$id = 10;
						$this->socket[$i] -= 160;
					}
					if($this->socket[$i] >= 144){
						$id = 9;
						$this->socket[$i] -= 144;
					}
					if($this->socket[$i] >= 128){
						$id = 8;
						$this->socket[$i] -= 128;
					}
					if($this->socket[$i] >= 112){
						$id = 7;
						$this->socket[$i] -= 112;
					}
					if($this->socket[$i] >= 96){
						$id = 6;
						$this->socket[$i] -= 96;
					}
					if($this->socket[$i] >= 80){
						$id = 5;
						$this->socket[$i] -= 80;
					}
					if($this->socket[$i] >= 64){
						$id = 4;
						$this->socket[$i] -= 64;
					}
					if($this->socket[$i] >= 48){
						$id = 3;
						$this->socket[$i] -= 48;
					}
					if($this->socket[$i] >= 32){
						$id = 2;
						$this->socket[$i] -= 32;
					}
					if($this->socket[$i] >= 16){
						$id = 1;
						$this->socket[$i] -= 16;
					}

					if(isset($data[$id])){
						$replace = preg_replace('/(%[d]%)/', $data[$id]['Grade'.$this->socket[$i].'Val'], $data[$id]['name']);
						$this->winggradeopt .= '<div class="item_light_blue item_size_12 item_font_family">' . preg_replace('/(%[d])/', $data[$id]['Grade'.$this->socket[$i].'Val'], $replace) . '</div>';
					}
				}
			}
		}

        public function elementType()
        {
            if($this->isPentagramItem() || $this->isErrtelItem()){
                if($this->harmony[1] == 1){
                    $this->elementtype = '<div class="item_red">(' . __('Fire Element') . ')</div>';
                } else if($this->harmony[1] == 2){
                    $this->elementtype = '<div class="item_blue">(' . __('Water Element') . ')</div>';
                } else if($this->harmony[1] == 3){
                    $this->elementtype = '<div class="item_yellow_2">(' . __('Earth Element') . ')</div>';
                } else if($this->harmony[1] == 4){
                    $this->elementtype = '<div class="item_light_green">(' . __('Wind Element') . ')</div>';
                } else if($this->harmony[1] == 5){
                    $this->elementtype = '<div class="item_purple">(' . __('Darkness Element') . ')</div>';
                } else{
                    $this->elementtype = '<div class="item_dark_red">' . __('Invalid Elements') . '</div>';
                }
            }
            return $this->elementopt;
        }

        public function elementInfo()
        {
            $this->elementopt = '';
            if($this->isPentagramItem() || $this->isErrtelItem()){
                for($i = 1; $i <= 5; $i++){
                    if($this->socket[$i] != $this->no_socket){
                        if($i == 1){
                            $this->elementopt .= '<div class="item_white">' . __('Slot of Anger') . ' (' . $i . ')</div>';
                            if($this->socket[$i] == $this->empty_socket){
                                $this->elementopt .= '<div class="item_light_blue_2">' . __('None') . '</div>';
                            } else{
                                $this->elementopt .= '<div class="item_light_blue_2">' . __('Errtel of Anger') . '</div>';
                            }
                        }
                        if($i == 2){
                            $this->elementopt .= '<div class="item_white">' . __('Slot of Blessing') . ' (' . $i . ')</div>';
                            if($this->socket[$i] == $this->empty_socket){
                                $this->elementopt .= '<div class="item_light_blue_2">' . __('None') . '</div>';
                            } else{
                                $this->elementopt .= '<div class="item_light_blue_2">' . __('Errtel of Blessing') . '</div>';
                            }
                        }
                        if($i == 3){
                            $this->elementopt .= '<div class="item_white">' . __('Slot of Integrity') . ' (' . $i . ')</div>';
                            if($this->socket[$i] == $this->empty_socket){
                                $this->elementopt .= '<divclass="item_light_blue_2">' . __('None') . '</div>';
                            } else{
                                $this->elementopt .= '<div class="item_light_blue_2">' . __('Errtel of Integrity') . '</div>';
                            }
                        }
                        if($i == 4){
                            $this->elementopt .= '<div class="item_white">' . __('Slot of Divinity') . ' (' . $i . ')</div>';
                            if($this->socket[$i] == $this->empty_socket){
                                $this->elementopt .= '<div class="item_light_blue_2">' . __('None') . '</div>';
                            } else{
                                $this->elementopt .= '<div class="item_light_blue_2">' . __('Errtel of Divinity') . '</div>';
                            }
                        }
                        if($i == 5){
                            $this->elementopt .= '<div class="item_white">' . __('Slot of Gale') . ' (' . $i . ')</div>';
                            if($this->socket[$i] == $this->empty_socket){
                                $this->elementopt .= '<div class="item_light_blue_2">' . __('None') . '</div>';
                            } else{
                                $this->elementopt .= '<div class="item_light_blue_2">' . __('Errtel of Gale') . '</div>';
                            }
                        }
                    }
                }
            }
            if($this->isErrtelItem()){
                $this->pentagram_option_info = $this->serverfile->pentagram_jewel_option_value(MU_VERSION)->get('pentagram_jewel_option_value');
                $s_data = [];
                for($i = 1; $i <= 5; $i++){
                    if($this->socket[$i] != 255){
						$this->errtel_rank += 1;
                        if($this->socket[$i] <= 5){
                            $s_data[$i]['lvl'] = 0;
                            $s_data[$i]['rank'] = $this->socket[$i];
                        } else{
                            $s_data[$i]['lvl'] = round($this->socket[$i] / 16, 0, PHP_ROUND_HALF_UP);
                            $s_data[$i]['rank'] = $this->socket[$i] - ($s_data[$i]['lvl'] * 16);
                        }
                        $s_data[$i]['contents'] = '<div>' . ($i) . ' ' . __('Rank Option') . ' ' . '+' . $s_data[$i]['lvl'] . '</div>';
                        $s_data[$i]['contents'] .= '<div class="item_light_blue_2">' . $this->loadElementName(($i), $s_data[$i]['rank'], $s_data[$i]['lvl']) . '</div>';
                    } else{
                        $s_data[$i]['contents'] = '';
                    }
                }
                $this->elementopt = $s_data[1]['contents'] . $s_data[2]['contents'] . $s_data[3]['contents'] . $s_data[4]['contents'] . $s_data[5]['contents'];
            }
        }

        private function loadElementName($element, $rank, $lvl)
        {
            foreach($this->pentagram_option_info AS $key => $value){
                if($value[4] == $this->type){
                    if($value[5] == $this->id){
                        if($value[6] == $element){
                            if($value[7] == $rank){
                                return preg_replace('/(%[d])/', $value[11 + $lvl], preg_replace('/(%[d]%)/', $value[11 + $lvl], $value[33]));
                            }
                        }
                    }
                }
            }
            return __('Unknown');
        }

        public function countMountableSlots()
        {
            $slots = array_count_values($this->socket);
            unset($slots[0]);
            $mountable_slots = 5;
            if(isset($slots[$this->no_socket])){
                $mountable_slots -= $slots[$this->no_socket];
            }
            return $mountable_slots;
        }

        private function socketOptionName()
        {
            $level = (int)substr($this->getLevel(), 1);
            $element_type = $this->socketElementType();
            foreach($this->getSocketData() AS $key => $value){
                if($value[3] == $element_type){
                    if($value[4] == $level){
                        return $value[5];
                        break;
                    }
                }
            }
            return __('Unknown');
        }

        private function socketOptionValue()
        {
            $level = (int)substr($this->getLevel(), 1);
            $element_type = $this->socketElementType();
            foreach($this->getSocketData() AS $key => $value){
                if($value[3] == $element_type){
                    if($value[4] == $level){
                        $add = ($level == 0) ? $level + 1 : $level;
                        return $this->addSocketBonusType($value[6 + $add], $value[6]);
                        break;
                    }
                }
            }
        }
		
        public function getSockets()
        {
			$armorSockets = [2,4,6];
		  
			if(!isset($this->item_data['type']) || $this->item_data['type'] == ''){
				$itemType = (($this->type != 12) && ($this->socket[1] != $this->no_socket && !in_array($this->socket[1], [6, 7, 8, 9])) || ($this->socket[2] != $this->no_socket && !in_array($this->socket[2], [6, 7, 8, 9])) || ($this->socket[3] != $this->no_socket && !in_array($this->socket[3], [6, 7, 8, 9])) || $this->socket[4] != $this->no_socket || $this->socket[5] != $this->no_socket) ? 2 : 1;
			}
			else{
				$itemType = $this->item_data['type'];
			}
            if($itemType == 2){		   
                if($this->socket[1] != $this->no_socket || $this->socket[2] != $this->no_socket || $this->socket[3] != $this->no_socket || $this->socket[4] != $this->no_socket || $this->socket[5] != $this->no_socket){
                    $this->sockopt = '<div class="item_socket item_size_12 item_font_family">';
                    for($i = 1; $i <= 5; $i++){
						if(SOCKET_LIBRARY != 1){
							if($this->socket[$i] != 255 && $this->socket[$i] != 0)
								$this->socket[$i] -= 1;
						}
						foreach($this->getSocketData() as $key => $value){
							if($this->getExeType($this->item_data['slot'], $this->id, $this->type) == 1 && !in_array($value[3], $armorSockets)){
								if($value[1] == $this->realSocketId($this->socket[$i]) && !in_array($value[3], $armorSockets)){
									$this->sockopt .= __('Socket') . ' ' . ($i) . ': ' . $this->socketElementTypeName($value[3], $value[5] . ' ' . $this->addSocketBonusType($value[6 + $this->findSocketValue($this->socket[$i])], $value[6])) . '<br>';
									break;
								} else{
									if($this->getExeType($this->item_data['slot'], $this->id, $this->type) == 1){
										if($this->realSocketId($this->socket[$i]) < 36){
											$search = $this->realSocketId($this->socket[$i]) + 4;
											$valueAdd = 5;
										}
										else{
											$search = $this->realSocketId($this->socket[$i]) - 46;
											$valueAdd = 5;
										}
										if($value[1] == $search){
											$this->sockopt .= __('Socket') . ' ' . ($i) . ': ' . $this->socketElementTypeName($value[3], $value[5] . ' ' . $this->addSocketBonusType($value[6 + ($this->findSocketValue($this->socket[$i])+$valueAdd)], $value[6])) . '<br>';
											break;
										}
									}
									else{
										if($this->realSocketId($this->socket[$i]) < 36){
											$search = $this->realSocketId($this->socket[$i]) + 4;
											$valueAdd = 5;
										}
										else{
											$search = $this->realSocketId($this->socket[$i]) - 46;
											$valueAdd = 6;
										}
										if($value[1] == $search){
											$this->sockopt .= __('Socket') . ' ' . ($i) . ': ' . $this->socketElementTypeName($value[3], $value[5] . ' ' . $this->addSocketBonusType($value[6 + ($this->findSocketValue($this->socket[$i])+$valueAdd)], $value[6])) . '<br>';
											break;
										} 
									}
									if($this->socket[$i] == $this->empty_socket){
										$this->sockopt .= __('Socket') . ' ' . ($i) . ': ' . __('No item application') . '<br>';
										break;
									}
								}
							}
							else{
								if($value[1] == $this->realSocketId($this->socket[$i]) && in_array($value[3], $armorSockets)){
									$this->sockopt .= __('Socket') . ' ' . ($i) . ': ' . $this->socketElementTypeName($value[3], $value[5] . ' ' . $this->addSocketBonusType($value[6 + $this->findSocketValue($this->socket[$i])], $value[6])) . '<br>';
									break;
								} else{
									if($this->getExeType($this->item_data['slot'], $this->id, $this->type) == 1){
										if($this->realSocketId($this->socket[$i]) < 36){
											$search = $this->realSocketId($this->socket[$i]) + 4;
											$valueAdd = 5;
										}
										else{
											$search = $this->realSocketId($this->socket[$i]) - 46;
											$valueAdd = 5;
										}
										if($value[1] == $search){
											$this->sockopt .= __('Socket') . ' ' . ($i) . ': ' . $this->socketElementTypeName($value[3], $value[5] . ' ' . $this->addSocketBonusType($value[6 + ($this->findSocketValue($this->socket[$i])+$valueAdd)], $value[6])) . '<br>';
											break;
										}
									}
									else{
										if($this->realSocketId($this->socket[$i]) < 36){
											$search = $this->realSocketId($this->socket[$i]) + 4;
											$valueAdd = 5;
										}
										else{
											$search = $this->realSocketId($this->socket[$i]) - 46;
											$valueAdd = 6;
										}
										if($value[1] == $search){
											$this->sockopt .= __('Socket') . ' ' . ($i) . ': ' . $this->socketElementTypeName($value[3], $value[5] . ' ' . $this->addSocketBonusType($value[6 + ($this->findSocketValue($this->socket[$i])+$valueAdd)], $value[6])) . '<br>';
											break;
										} 
									}
									if($this->socket[$i] == $this->empty_socket){
										$this->sockopt .= __('Socket') . ' ' . ($i) . ': ' . __('No item application') . '<br>';
										break;
									}
								}
							}
						}
                        
                    }
                    $this->sockopt .= '</div>';
                }
            }
			else{
				if($this->socket[1] != $this->no_socket || $this->socket[2] != $this->no_socket || $this->socket[3] != $this->no_socket || $this->socket[4] != $this->no_socket || $this->socket[5] != $this->no_socket){
					for($i = 1; $i <= 5; $i++){
						if(in_array($this->socket[$i], [6, 7, 8, 9, 10]) && in_array($i, [1, 2, 3]) && MU_VERSION >= 5){
							$this->exe_options .= '<div class="item_light_blue item_size_12 item_font_family">' . $this->findExeOption($this->socket[$i]) . '</div>';
						}
					}
				}
				$kindA = isset($this->item_data['kindA']) ? $this->item_data['kindA'] : false;
				if($kindA != false && $this->socket[5] != $this->no_socket){
					if($kindA == 15 || $kindA == 18){
						$this->sockopt = '<div class="item_socket item_size_12 item_font_family">'.__('Mastery Bonus Options').'</div><div class="item_light_blue item_size_12 item_font_family">'.__('Damage Decrease').' '.(25*$this->socket[5]).'</div>';
					}
					if($kindA == 14){
						$values = [1 => 10, 2 => 25, 3 => 40];
						$this->sockopt = '<div class="item_socket item_size_12 item_font_family">'.__('Mastery Bonus Options').'</div><div class="item_light_blue item_size_12 item_font_family">'.__('Increase all stats +').''.$values[$this->socket[5]].'</div>';
					}
				}
			}
        }
		
		
		
		
        private function realSocketId($id)
        {
            if($id == 254 || $id == 255)
                return $id;
            if($id > 36){
                return ($id % 50);
            }
            return $id;
        }
		
		public function seedsIndex()
        {
            $seed_index = [];
            $ancient = $this->ancient;
            if(($this->ancient >= 64)){
                $index = $this->realSocketId($this->socket[1]);
                if($index < 6){
                    $seed_index[1] = $index + 5;
                }
                $this->ancient -= 64;
            }
            if(($this->ancient >= 16)){
                $index = $this->realSocketId($this->socket[2]);
                if($index < 6){
                    $seed_index[2] = $index + 5;
                }
                $this->ancient -= 16;
            }
            if(($this->ancient >= 4)){
                $index = $this->realSocketId($this->socket[3]);
                if($index < 6){
                    $seed_index[3] = $index + 5;
                }
                $this->ancient -= 4;
            }
            if(($this->ancient >= 1)){
                $index = $this->realSocketId($this->socket[4]);
                if($index < 6){
                    $seed_index[4] = $index + 5;
                }
                $this->ancient -= 1;
            }
            if(($this->exe == 16)){
                $index = $this->realSocketId($this->socket[5]);
                if($index < 6){
                    $seed_index[5] = $index + 5;
                }
                $this->exe -= 16;
            }
            return $seed_index;
        }

        private function findSocketValue($id)
        {
            if($id == 254 || $id == 255)
                return 0;
            if($id > 36){
                $real_id = ($id % 50);
                return (($id - $real_id) / 50) + 1;
            } else{
                return 1;
            }
        }

        private function addSocketBonusType($val, $type)
        {
            switch($type){
                default:
                    return '+' . $val;
                    break;
                case 2:
                    return $val . '%';
                    break;
            }
        }

        private function socketElementType()
        {
            $element_type = 1;
            if(in_array($this->id, [60, 100, 106, 112, 118, 124])){
                $element_type = 1;
            }
            if(in_array($this->id, [61, 101, 107, 113, 119, 125])){
                $element_type = 2;
            }
            if(in_array($this->id, [62, 102, 108, 114, 120, 126])){
                $element_type = 3;
            }
            if(in_array($this->id, [63, 103, 109, 115, 121, 127])){
                $element_type = 4;
            }
            if(in_array($this->id, [64, 104, 110, 116, 122, 128])){
                $element_type = 5;
            }
            if(in_array($this->id, [65, 105, 111, 117, 123, 129])){
                $element_type = 6;
            }
            return $element_type;
        }

        private function socketElementTypeName($type, $value)
        {
            $name = '';
            if($type == 1){
                $name .= __('Fire') . ' (' . $value . ')';
            }
            if($type == 2){
                $name .= __('Water') . ' (' . $value . ')';
            }
            if($type == 3){
                $name .= __('Ice') . ' (' . $value . ')';
            }
            if($type == 4){
                $name .= __('Wind') . ' (' . $value . ')';
            }
            if($type == 5){
                $name .= __('Lightning') . ' (' . $value . ')';
            }
            if($type == 6){
                $name .= __('Earth') . ' (' . $value . ')';
            }
            return $name;
        }

        private function setSocketData()
        {
            static $data = [];
            if(empty($data))
                $data = $this->serverfile->socket_item(MU_VERSION)->get('socket_item');
            $this->socket_data = $data;
        }

        private function getSocketData()
        {
            if(!is_array($this->socket_data))
                $this->setSocketData();
            //pre($this->socket_data);	die();
            return $this->socket_data;
        }

        public function getAncient()
        {
            if($this->ancient > 0){
                if(!in_array($this->ancient, [5,6,20])){
					$stamina = 10;
				} else{
					$stamina = 5;
				}
                if($this->type < 5){
                    $this->stamina = '<div class="item_light_blue item_size_12 item_font_family">' . __('Increase Strength') . ' +' . $stamina . '</div>';
                } else{
                    $this->stamina = '<div class="item_light_blue item_size_12 item_font_family">' . __('Increase Stamina') . ' +' . $stamina . '</div>';
                }
                $options = $this->ancientOptions();
                if($options != false){
                    $this->ancopt = '<div class="item_yellow">'.__('Set Item Option Info').'</div><br /><div class="item_grey">';
                    $this->ancopt .= $options . '<br />';
                    $this->ancopt .= '</div>';
                }
            }
        }

        private function ancientOptions()
        {
            $set_type = $this->serverfile->item_set_type()->get('item_set_type');
            if(is_array($set_type)){
                $this->set_options = $this->serverfile->item_set_option()->get('item_set_option');
                $this->set_options_text = $this->serverfile->item_set_option_text()->get('item_set_option_text');
                if(array_key_exists($this->type, $set_type)){
                    if(array_key_exists($this->id, $set_type[$this->type])){
                        if($this->ancient == 5 || $this->ancient == 9){
							$set = 'typeA';
						}
						if($this->ancient == 6 || $this->ancient == 10){
							$set = 'set2';
							if($set_type[$this->type][$this->id][$set] == 0){
								$set = 'typeA';
							}
						}
						if($this->ancient == 20|| $this->ancient == 24){
							$set = 'typeB';
							if($set_type[$this->type][$this->id][$set] == 0){
								$set = 'set2';
							}
						}
	  
                        return (isset($set)) ? $this->findAncientOption($set_type[$this->type][$this->id][$set]) : '';
                    }
                }
            }
            return false;
        }

        private function findAncientOption($set)
        {
            if(isset($this->set_options[$set])){
				$this->anc_prefix = $this->set_options[$set]['name'];
				$options = '<div class="item_light_green item_size_12 item_font_family">'.__('2Set Effect').'</div>';
				if($this->set_options[$set]['opt_1_1'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['opt_1_1'], $this->set_options[$set]['opt_1_1_val']);
				}
				if($this->set_options[$set]['opt_2_1'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['opt_2_1'], $this->set_options[$set]['opt_2_1_val']);
				}
				$options .= '<div class="item_light_green item_size_12 item_font_family">'.__('3Set Effect').'</div>';
				if($this->set_options[$set]['opt_1_2'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['opt_1_2'], $this->set_options[$set]['opt_1_2_val']);
				}
				if($this->set_options[$set]['opt_2_2'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['opt_2_2'], $this->set_options[$set]['opt_2_2_val']);
				}
				$options .= '<div class="item_light_green item_size_12 item_font_family">'.__('4Set Effect').'</div>';
				if($this->set_options[$set]['opt_1_3'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['opt_1_3'], $this->set_options[$set]['opt_1_3_val']);
				}
				if($this->set_options[$set]['opt_2_3'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['opt_2_3'], $this->set_options[$set]['opt_2_3_val']);
				}
				if($this->set_options[$set]['opt_1_4'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['opt_1_4'], $this->set_options[$set]['opt_1_4_val']);
				}
				if($this->set_options[$set]['opt_2_4'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['opt_2_4'], $this->set_options[$set]['opt_2_4_val']);
				}
				if($this->set_options[$set]['opt_1_5'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['opt_1_5'], $this->set_options[$set]['opt_1_5_val']);
				}
				if($this->set_options[$set]['opt_2_5'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['opt_2_5'], $this->set_options[$set]['opt_2_5_val']);
				}
				if($this->set_options[$set]['opt_1_6'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['opt_1_6'], $this->set_options[$set]['opt_1_6_val']);
				}
				if($this->set_options[$set]['opt_2_6'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['opt_2_6'], $this->set_options[$set]['opt_2_6_val']);
				}
				if($this->set_options[$set]['fopt_1'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['fopt_1'], $this->set_options[$set]['fopt_val1']);
				}
				if($this->set_options[$set]['fopt_2'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['fopt_2'], $this->set_options[$set]['fopt_val2']);
				}
				if($this->set_options[$set]['fopt_3'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['fopt_3'], $this->set_options[$set]['fopt_val3']);
				}
				if($this->set_options[$set]['fopt_4'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['fopt_4'], $this->set_options[$set]['fopt_val4']);
				}
				if($this->set_options[$set]['fopt_5'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['fopt_5'], $this->set_options[$set]['fopt_val5']);
				}
				if($this->set_options[$set]['fopt_6'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['fopt_6'], $this->set_options[$set]['fopt_val6']);
				}
				if($this->set_options[$set]['fopt_7'] != -1){
					$options .= $this->findAncientOptionText($this->set_options[$set]['fopt_7'], $this->set_options[$set]['fopt_val7']);
				}
			}
			return $options;
        }

        private function findAncientOptionText($index, $val)
        {
            foreach($this->set_options_text AS $key => $value){
                if($value[1] == $index){
                    $sign = '';
                    if($value[3] == 2){
                        $sign = '%';
                    }
                    return $value[2] . ' +' . $val . $sign . '<br />';
                    break;
                }
            }
        }

        public function getX()
        {
            if(array_key_exists('x', $this->item_data)){
                return $this->item_data['x'];
            }
            return 1;
        }

        public function getY()
        {
            if(array_key_exists('y', $this->item_data)){
                return $this->item_data['y'];
            }
            return 1;
        }

        public function speed()
        {
            if(array_key_exists('attspeed', $this->item_data)){
                return $this->item_data['attspeed'];
            }
            return 0;
        }

        public function levelRequired()
        {
            if($this->item_data['lvlreq'] != 0){
                $level = (int)substr($this->getLevel(), 1);
                if($this->index >= $this->itemIndex(0, 0) && $this->index < $this->itemIndex(12, 0)){
                    $this->item_data['lvlreq'] = $this->item_data['lvlreq'];
                } else if(($this->index >= $this->itemIndex(12, 3) && $this->index <= $this->itemIndex(12, 6)) || $this->index == $this->itemIndex(12, 42)){
                    $this->item_data['lvlreq'] = $this->item_data['lvlreq'] + ($level * 5);
                } else if(($this->index >= $this->itemIndex(12, 7) && $this->index <= $this->itemIndex(12, 24) && $this->index != $this->itemIndex(12, 15)) || ($this->index >= $this->itemIndex(12, 44) && $this->index <= $this->itemIndex(12, 48))){
                    $this->item_data['lvlreq'] = $this->item_data['lvlreq'];
                } else if(($this->index >= $this->itemIndex(12, 36) && $this->index <= $this->itemIndex(12, 40)) || $this->index == $this->itemIndex(12, 43) || $this->index == $this->itemIndex(12, 50)){
                    $this->item_data['lvlreq'] = $this->item_data['lvlreq'];
                } else if($this->index >= $this->itemIndex(12, 130) && $this->index <= $this->itemIndex(12, 135)){
                    $this->item_data['lvlreq'] = $this->item_data['lvlreq'];
                } else if($this->index >= $this->itemIndex(12, 262) && $this->index <= $this->itemIndex(12, 265)){
                    $this->item_data['lvlreq'] = $this->item_data['lvlreq'] + ($level * 4);
                } else if($this->index >= $this->itemIndex(12, 266) && $this->index <= $this->itemIndex(12, 267)){
                    $this->item_data['lvlreq'] = $this->item_data['lvlreq'];
                } else if($this->index == $this->itemIndex(13, 4)){
                    $this->item_data['lvlreq'] = 218 + (1 * 2);
                } else{
                    $this->item_data['lvlreq'] = $this->item_data['lvlreq'] + ($level * 4);
                }
            }
            if($this->index == $this->itemIndex(13, 10)){
                if($level <= 2){
                    $this->item_data['lvlreq'] = 20;
                } else{
                    $this->item_data['lvlreq'] = 50;
                }
            }
            if($this->exe != 0 && $this->item_data['lvlreq'] > 0){
                if($this->index <= $this->itemIndex(12, 0)){
                    $this->item_data['lvlreq'] += 20;
                }
            }
            return $this->item_data['lvlreq'];
        }

        public function getClass()
        {
            $class = ['sm' => 0, 'bk' => 0, 'me' => 0, 'mg' => 0, 'dl' => 0, 'bs' => 0, 'rf' => 0, 'gl' => 0, 'rw' => 0, 'sl' => 0];
            if(array_key_exists('dw/sm', $this->item_data)){
                if(in_array($this->item_data['dw/sm'], [1,2,3,4])){
                    $class['sm'] = 1;
                }
            }
            if(array_key_exists('dk/bk', $this->item_data)){
                if(in_array($this->item_data['dk/bk'], [1,2,3,4])){
                    $class['bk'] = 1;
                }
            }
            if(array_key_exists('elf/me', $this->item_data)){
                if(in_array($this->item_data['elf/me'], [1,2,3,4])){
                    $class['me'] = 1;
                }
            }
            if(array_key_exists('mg', $this->item_data)){
                if(in_array($this->item_data['mg'], [1,2,3,4])){
                    $class['mg'] = 1;
                }
            }
            if(array_key_exists('dl', $this->item_data)){
                if(in_array($this->item_data['dl'], [1,2,3,4])){
                    $class['dl'] = 1;
                }
            }
            if(array_key_exists('sum', $this->item_data)){
                if(in_array($this->item_data['sum'], [1,2,3,4])){
                    $class['bs'] = 1;
                }
            }
            if(array_key_exists('rf', $this->item_data)){
                if(in_array($this->item_data['rf'], [1,2,3,4])){
                    $class['rf'] = 1;
                }
            }
            if(array_key_exists('gl', $this->item_data)){
                if(in_array($this->item_data['gl'], [1,2,3,4])){
                    $class['gl'] = 1;
                }
            }
            if(array_key_exists('rw', $this->item_data)){
                if(in_array($this->item_data['rw'], [1,2,3,4])){
                    $class['rw'] = 1;
                }
            }
			if(array_key_exists('sl', $this->item_data)){
				if(in_array($this->item_data['sl'], [1,2,3,4])){
					$class['sl'] = 1;
				}
			}
            return $class;
        }

        public function canEquip()
        {
            if(is_array($this->item_data)){
				if(array_key_exists('dw/sm', $this->item_data)){
					if($this->item_data['dw/sm'] == 1){
						$this->item_for .= '0,1,2,';
					}
					if($this->item_data['dw/sm'] == 2){
						$this->item_for .= '1,2,';
					}
					if($this->item_data['dw/sm'] == 3){
						$this->item_for .= '2,';
					}
				}
				if(array_key_exists('dk/bk', $this->item_data)){
					if($this->item_data['dk/bk'] == 1){
						$this->item_for .= '16,17,18,';
					}
					if($this->item_data['dk/bk'] == 2){
						$this->item_for .= '17,18,';
					}
					if($this->item_data['dk/bk'] == 3){
						$this->item_for .= '18,';
					}
				}
				if(array_key_exists('elf/me', $this->item_data)){
					if($this->item_data['elf/me'] == 1){
						$this->item_for .= '32,33,34,';
					}
					if($this->item_data['elf/me'] == 2){
						$this->item_for .= '33,34,';
					}
					if($this->item_data['elf/me'] == 3){
						$this->item_for .= '34,';
					}
				}
				if(array_key_exists('mg', $this->item_data)){
					if($this->item_data['mg'] == 1){
						$this->item_for .= '48,49,';
					}
					if($this->item_data['mg'] == 2){
						$this->item_for .= '49,';
					}
					if($this->item_data['mg'] == 3){
						$this->item_for .= '49,';
					}
				}
				if(array_key_exists('dl', $this->item_data)){
					if($this->item_data['dl'] == 1){
						$this->item_for .= '64,65,';
					}
					if($this->item_data['dl'] == 2){
						$this->item_for .= '65,';
					}
					if($this->item_data['dl'] == 3){
						$this->item_for .= '65,';
					}
				}
				if(array_key_exists('sum', $this->item_data)){
					if($this->item_data['sum'] == 1){
						$this->item_for .= '80,81,82,';
					}
					if($this->item_data['sum'] == 2){
						$this->item_for .= '81,82,';
					}
					if($this->item_data['sum'] == 3){
						$this->item_for .= '82,';
					}
				}
				if(array_key_exists('rf', $this->item_data)){
					if($this->item_data['rf'] == 1){
						$this->item_for .= '96,98,';
					}
					if($this->item_data['rf'] == 2){
						$this->item_for .= '98,';
					}
					if($this->item_data['rf'] == 3){
						$this->item_for .= '98,';
					}
				}
				if(array_key_exists('gl', $this->item_data)){
					if($this->item_data['gl'] == 1){
						$this->item_for .= '112,114,118,';
					}
					if($this->item_data['gl'] == 2){
						$this->item_for .= '114,118,';
					}
					if($this->item_data['gl'] == 3){
						$this->item_for .= '118,';
					}
				}
				if(array_key_exists('rw', $this->item_data)){
					if($this->item_data['rw'] == 1){
						$this->item_for .= '128,129,131,';
					}
					if($this->item_data['rw'] == 2){
						$this->item_for .= '129,131,';
					}
					if($this->item_data['rw'] == 3){
						$this->item_for .= '131,';
					}
				}
				if(array_key_exists('sl', $this->item_data)){
					if($this->item_data['sl'] == 1){
						$this->item_for .= '144,145,147,';
					}
					if($this->item_data['sl'] == 2){
						$this->item_for .= '145,147,';
					}
					if($this->item_data['sl'] == 3){
						$this->item_for .= '147,';
					}
				}
			}
            if($this->item_for != ''){
                $gl = '';
                $rw = '';
				$sl = '';
                if(defined('MU_VERSION') && MU_VERSION >= 5){
                    $gl = '112,114,118,';
                }
                if(defined('MU_VERSION') && MU_VERSION >= 9){
                    $rw = '128,129,131,';
                }
				if(defined('MU_VERSION') && MU_VERSION >= 10){
					$sl = '144,145,147,';
				}
                if($this->item_for == '0,1,2,16,17,18,32,33,34,48,49,64,65,80,81,82,96,98,' . $gl . $rw . $sl){
                    $this->class = '';
                } else{
                    $this->item_for = preg_replace('/,$/', '', preg_replace('/[,,]/', ',', $this->item_for));
                    $this->item_for = (strstr($this->item_for, ',')) ? explode(',', $this->item_for) : [$this->item_for];
                    if(in_array(0, $this->item_for))
                        $this->item_for = array_diff($this->item_for, [1, 2]);
                    if(in_array(1, $this->item_for))
                        $this->item_for = array_diff($this->item_for, [0, 2]);
                    if(in_array(2, $this->item_for))
                        $this->item_for = array_diff($this->item_for, [0, 1]);
                    if(in_array(16, $this->item_for))
                        $this->item_for = array_diff($this->item_for, [17, 18]);
                    if(in_array(17, $this->item_for))
                        $this->item_for = array_diff($this->item_for, [16, 18]);
                    if(in_array(18, $this->item_for))
                        $this->item_for = array_diff($this->item_for, [16, 17]);
                    if(in_array(32, $this->item_for))
                        $this->item_for = array_diff($this->item_for, [33, 34]);
                    if(in_array(33, $this->item_for))
                        $this->item_for = array_diff($this->item_for, [32, 34]);
                    if(in_array(34, $this->item_for))
                        $this->item_for = array_diff($this->item_for, [32, 33]);
                    if(in_array(48, $this->item_for))
                        $this->item_for = array_diff($this->item_for, [49]);
                    if(in_array(49, $this->item_for))
                        $this->item_for = array_diff($this->item_for, [48]);
                    if(in_array(64, $this->item_for))
                        $this->item_for = array_diff($this->item_for, [65]);
                    if(in_array(65, $this->item_for))
                        $this->item_for = array_diff($this->item_for, [64]);
                    if(in_array(80, $this->item_for))
                        $this->item_for = array_diff($this->item_for, [81, 82]);
                    if(in_array(81, $this->item_for))
                        $this->item_for = array_diff($this->item_for, [80, 82]);
                    if(in_array(82, $this->item_for))
                        $this->item_for = array_diff($this->item_for, [80, 81]);
                    if(in_array(96, $this->item_for))
                        $this->item_for = array_diff($this->item_for, [98]);
                    if(in_array(98, $this->item_for))
                        $this->item_for = array_diff($this->item_for, [96]);
                    if(defined('MU_VERSION') && MU_VERSION >= 5){
                        if(in_array(112, $this->item_for))
                            $this->item_for = array_diff($this->item_for, [114, 118]);
                        if(in_array(114, $this->item_for))
                            $this->item_for = array_diff($this->item_for, [112, 118]);
                        if(in_array(118, $this->item_for))
                            $this->item_for = array_diff($this->item_for, [112, 114]);
                    }
                    if(defined('MU_VERSION') && MU_VERSION >= 9){
                        if(in_array(128, $this->item_for))
                            $this->item_for = array_diff($this->item_for, [129, 131]);
                        if(in_array(129, $this->item_for))
                            $this->item_for = array_diff($this->item_for, [128, 131]);
                        if(in_array(131, $this->item_for))
                            $this->item_for = array_diff($this->item_for, [128, 129]);
                    }
					if(defined('MU_VERSION') && MU_VERSION >= 10){
						if(in_array(144, $this->item_for))
							$this->item_for = array_diff($this->item_for, [145, 147]);
						if(in_array(145, $this->item_for))
							$this->item_for = array_diff($this->item_for, [144, 147]);
						if(in_array(147, $this->item_for))
							$this->item_for = array_diff($this->item_for, [144, 145]);
					}
                    foreach($this->item_for as $class_code){
                        $this->class .= '<div class="item_white item_dark_red_background">'.__('Can be equipped by ') . __($this->website->get_char_class($class_code, false)) . '</div>';
                    }
                }
            }
        }

        private function isPentagramItem()
		{
			if(($this->index >= $this->itemIndex(12, 200) && $this->index <= $this->itemIndex(12, 220)) || ($this->index >= $this->itemIndex(12, 306) && $this->index <= $this->itemIndex(12, 308))){
				return true;
			}
			return false;
		}
		
		private function isErrtelItem()
		{
			if(($this->index >= $this->itemIndex(12, 221) && $this->index <= $this->itemIndex(12, 261))){
				return true;
			}
			return false;
		}
		
		private function additionalValue()
		{
			if($this->exe > 0 || $this->ancient > 0){
				return 25;
			}
			return 0;
		}

        private function chaosItem()
        {
            if($this->index == $this->itemIndex(2, 6)){
                return 15;
            } else if($this->index == $this->itemIndex(5, 7)){
                return 25;
            }
            if($this->index == $this->itemIndex(4, 6)){
                return 30;
            }
            return 0;
        }

        public function damage()
        {
            $level = (int)substr($this->getLevel(), 1);
            $chaos_item = $this->chaosItem();
            $min_damage = 0;
            $max_damage = 0;
            if(array_key_exists('maxdmg', $this->item_data)){
                if($this->item_data['maxdmg'] > 0){
                    $max_damage = $this->item_data['maxdmg'];
                    if($this->ancient != 0 && $level != 0){
                        $max_damage += (($this->item_data['mindmg'] * 25) / $level) + 5;
                        $max_damage += ($this->additionalValue() / 40) + 5;
                    } else if($this->exe != 0){
                        if($chaos_item != 0){
                            $max_damage += $chaos_item;
                        } else if($level != 0){
                            $max_damage += (($this->item_data['mindmg'] * 25) / $level) + 5;
                        }
                    }
                    if($this->isPentagramItem()){
                        $max_damage += ($level * 3);
                    } else{
                        $max_damage += ($level * 4);
                    }
                    if($level >= 10){
                        $max_damage += (($level - 9) * ($level - 8)) / 2;
                    }
                }
            }
            if(array_key_exists('mindmg', $this->item_data)){
                if($this->item_data['mindmg'] > 0){
                    $min_damage = $this->item_data['mindmg'];
                    if($this->ancient != 0 && $level != 0){
                        $min_damage += (($this->item_data['mindmg'] * 25) / $level) + 5;
                        $min_damage += ($this->additionalValue() / 40) + 5;
                    } else if($this->exe != 0){
                        if($chaos_item != 0){
                            $min_damage += $chaos_item;
                        } else if($level != 0){
                            $min_damage += (($this->item_data['mindmg'] * 25) / $level) + 5;
                        }
                    }
                    if($this->isPentagramItem()){
                        $min_damage += ($level * 3);
                    } else{
                        $min_damage += ($level * 3);
                    }
                    if($level >= 10){
                        $min_damage += (($level - 9) * ($level - 8)) / 2;
                    }
                }
            }
            return round($min_damage) . '~' . round($max_damage);
					   
        }

        private function increaseDamageWings()
        {
            $dmg = 0;
            $level = (int)substr($this->getLevel(), 1);
            if(($this->index >= $this->itemIndex(12, 0) && $this->index <= $this->itemIndex(12, 2)) || $this->index == $this->itemIndex(12, 41)){ // 1st wings
                $dmg = (12 + ($level * 2));
            } else if(($this->index >= $this->itemIndex(12, 3) && $this->index <= $this->itemIndex(12, 6)) || $this->index == $this->itemIndex(12, 42)){ // 2nd wings
                $dmg = (32 + ($level));
            } else if(($this->index >= $this->itemIndex(12, 36) && $this->index <= $this->itemIndex(12, 39)) || $this->index == $this->itemIndex(12, 43) || $this->index == $this->itemIndex(12, 50) || $this->index == $this->itemIndex(12, 467)){ // 3rd wings
                $dmg = (39 + ($level * 2));
            } else if($this->index == $this->itemIndex(13, 30) || $this->index == $this->itemIndex(12, 49)){ // Cape of lord, Cape of fighter
                $dmg = (20 + ($level * 2));
            } else if($this->index == $this->itemIndex(12, 40) || $this->index == $this->itemIndex(12, 50)){ // Mantle of Monarch, Cape of Overrule
                $dmg = (39 + ($level * 2));
            } else if(($this->index >= $this->itemIndex(12, 130) && $this->index <= $this->itemIndex(12, 135)) || $this->index == $this->itemIndex(12, 278)){ // Mini Wings
                $dmg = (10 + ($level * 2));
            } else if($this->index == $this->itemIndex(12, 262)){ // Cloak of Death
                $dmg = (21 + ($level * 2));
            } else if($this->index == $this->itemIndex(12, 263)){ // Wings of Chaos
                $dmg = (33 + ($level * 2));
            } else if($this->index == $this->itemIndex(12, 265) || $this->index == $this->itemIndex(12, 264)){ // Wings of Life, Wings of Magic
                $dmg = (35 + ($level * 2));
            } else if($this->index == $this->itemIndex(12, 266)){ // Wings of Conqueror
                $dmg = (71 + ($level * 2));
            } else if($this->index == $this->itemIndex(12, 267)){ // Wings of Angel And Devil
                $dmg = (75 + ($level * 2));
            } else if($this->index == $this->itemIndex(12, 269)){ // Cloak of Limit
                $dmg = (20 + ($level * 2));
            } else if($this->index == $this->itemIndex(12, 270)){ // Cloak of Transcendence
                $dmg = (39 + ($level * 2));
            }
			else if(in_array($this->index, [$this->itemIndex(12, 414), $this->itemIndex(12, 415), $this->itemIndex(12, 416), $this->itemIndex(12, 417), $this->itemIndex(12, 418), $this->itemIndex(12, 419), $this->itemIndex(12, 420), $this->itemIndex(12, 421), $this->itemIndex(12, 469)])){ // new wings
				$dmg = (55 + ($level));
			}
            return $dmg;
        }

        private function absorbDamageWings()
        {
            $dmg = 0;
            $level = (int)substr($this->getLevel(), 1);
            if(($this->index >= $this->itemIndex(12, 0) && $this->index <= $this->itemIndex(12, 2)) || $this->index == $this->itemIndex(12, 41)){ // 1st wings
                $dmg = (12 + ($level * 2));
            } else if(($this->index >= $this->itemIndex(12, 3) && $this->index <= $this->itemIndex(12, 6)) || $this->index == $this->itemIndex(12, 42)){ // 2nd wings
                $dmg = (25 + ($level));
            } else if(($this->index >= $this->itemIndex(12, 36) && $this->index <= $this->itemIndex(12, 39)) || $this->index == $this->itemIndex(12, 43) || $this->index == $this->itemIndex(12, 50) || $this->index == $this->itemIndex(12, 467)){ // 3rd wings
                $dmg = (39 + ($level * 2));
            } else if($this->index == $this->itemIndex(13, 30) || $this->index == $this->itemIndex(12, 49)){ // Cape of lord, Cape of fighter
                $dmg = (20 + ($level * 2));
            } else if($this->index == $this->itemIndex(12, 40) || $this->index == $this->itemIndex(12, 50)){ // Mantle of Monarch, Cape of Overrule
                $dmg = (24 + ($level * 2));
            } else if(($this->index >= $this->itemIndex(12, 130) && $this->index <= $this->itemIndex(12, 135)) || $this->index == $this->itemIndex(12, 278)){ // Mini Wings
                $dmg = (10 + ($level * 2));
            } else if($this->index == $this->itemIndex(12, 262)){ // Cloak of Death
                $dmg = (13 + ($level * 2));
            } else if($this->index == $this->itemIndex(12, 263)){ // Wings of Chaos
                $dmg = (30 + ($level * 2));
            } else if($this->index == $this->itemIndex(12, 265) || $this->index == $this->itemIndex(12, 264)){ // Wings of Life, Wings of Magic
                $dmg = (29 + ($level * 2));
            } else if($this->index == $this->itemIndex(12, 266)){ // Wings of Conqueror
                $dmg = (71 + ($level * 2));
            } else if($this->index == $this->itemIndex(12, 267)){ // Wings of Angel And Devil
                $dmg = (75 + ($level * 2));
            } else if($this->index == $this->itemIndex(12, 269)){ // Cloak of Limit
                $dmg = (10 + ($level * 2));
            } else if($this->index == $this->itemIndex(12, 270)){ // Cloak of Transcendence
                $dmg = (39 + ($level * 2));
            } else if(in_array($this->index, [$this->itemIndex(12, 414), $this->itemIndex(12, 415), $this->itemIndex(12, 416), $this->itemIndex(12, 417), $this->itemIndex(12, 418), $this->itemIndex(12, 419), $this->itemIndex(12, 420), $this->itemIndex(12, 421), $this->itemIndex(12, 469)])){ // new wings
				if($this->index == $this->itemIndex(12, 418)){
					$dmg = (37 + ($level * 2));
				}
				else{
					$dmg = (43 + ($level * 2));
				}
			}
            return $dmg;
        }

        public function magicPower()
        {
            if(array_key_exists('magpow', $this->item_data)){
                $level = (int)substr($this->getLevel(), 1);
                $magic_power = $this->item_data['magpow'];
                if($this->exe > 0 || $this->ancient > 0){
                    $magic_power += 25;
                }
                $magic_power += $level * 3;
                if($level >= 10)
                    $magic_power += (($level - 9) * ($level - 8)) / 2;
                if($this->type == 2 && $this->id != 16 && $this->id >= 8)
                    $magic_power = ($magic_power / 2); else
                    $magic_power = round((($magic_power / 2) + ($level * 2)));
                return $magic_power;
            }
            return 0;
        }

        public function defense()
        {
            if(array_key_exists('def', $this->item_data)){
                $def = $this->item_data['def'];
                $level = (int)substr($this->getLevel(), 1);
                $exe = $this->exe;
                $drop_level = $this->item_data['lvldrop'];
                if($exe >= 64){
                    $exe -= 64;
                }
                $drop_level += $this->additionalValue();
                if($this->index == $this->itemIndex(13, 30) || $this->index == $this->itemIndex(12, 49) || $this->index == $this->itemIndex(12, 269)){ // Cloak of Lord,Cloak of Fighter, Cloak Of Limit
                    $def = 15;
                }
                if($def > 0){
                    if($this->index >= $this->itemIndex(6, 0) && $this->index < $this->itemIndex(7, 0)){ // Shields
                        $def += $level;
                        if($this->ancient != 0 && $drop_level != 0){
                            $def += (($def * 20) / $drop_level) + 2;
                        }
                    } else{
                        if($this->ancient != 0 && $this->item_data['lvldrop'] != 0 && $drop_level != 0){
                            $def += ((($def * 12) / $this->item_data['lvldrop']) + ($this->item_data['lvldrop'] / 5)) + 4;
                            $def += ((($def * 3) / $drop_level) + ($drop_level / 30)) + 2;
                        } else if($exe != 0 && $this->item_data['lvldrop'] != 0){
                            $def += ((($def * 12) / $this->item_data['lvldrop']) + ($this->item_data['lvldrop'] / 5)) + 4;
                        }
                        if(($this->index >= $this->itemIndex(12, 3) && $this->index <= $this->itemIndex(12, 6)) || $this->index == $this->itemIndex(12, 42) || $this->index == $this->itemIndex(13, 4)){ // 2sd Wings,Dark Horse
                            $def += $level * 2;
                        } else if(($this->index >= $this->itemIndex(12, 36) && $this->index <= $this->itemIndex(12, 40)) || $this->index == $this->itemIndex(12, 43) || $this->index == $this->itemIndex(12, 50) || $this->index == $this->itemIndex(12, 270) || $this->index == $this->itemIndex(12, 467)){ // 3rd Wings
                            $def += $level * 4;
                        } else if($this->index >= $this->itemIndex(12, 130) && $this->index <= $this->itemIndex(12, 135)){ // Mini Wings
                            $def += $level * 2;
                        } else if($this->index >= $this->itemIndex(12, 262) && $this->index <= $this->itemIndex(12, 265)){ // Monster Wings
                            $def += $level * 3;
                        } else if($this->index >= $this->itemIndex(12, 266) && $this->index <= $this->itemIndex(12, 267)){ // Special Wings
                            $def += $level * 3;
                        } else{
                            $def += $level * 3;
                        }
                        if($level >= 10){
                            $def += (($level - 9) * ($level - 8)) / 2;
                        }
                    }
                }
                return floor($def);
            }
            return 0;
        }

        public function successBlock()
        {
            if(array_key_exists('successblock', $this->item_data)){
                $level = (int)substr($this->getLevel(), 1);
                $success_block = $this->item_data['successblock'] + ($level * 3);
                if($level == 10)
                    $success_block += $level - 9;
                if($level == 11)
                    $success_block += $level - 8;
                if($level == 12)
                    $success_block += $level - 6;
                if($level == 13)
                    $success_block += $level - 3;
                if($level == 14)
                    $success_block += $level + 1;
                if($level == 15)
                    $success_block += $level + 6;
                if($this->exe > 0 && $this->ancient != 0){
                    $success_block += 30;
                } else if($this->exe <= 0 && $this->ancient != 0){
                    $success_block += 30;
                } else if($this->exe > 0 && $this->ancient == 0){
                    $success_block += 30;
                }
                return $success_block;
            }
            return 0;
        }

        public function iceRes()
        {
            if(array_key_exists('iceres', $this->item_data)){
                return $this->item_data['iceres'] + (int)substr($this->getLevel(), 1);
            }
            return 0;
        }

        public function poisonRes()
        {
            if(array_key_exists('poisonres', $this->item_data)){
                return $this->item_data['poisonres'] + (int)substr($this->getLevel(), 1);
            }
            return 0;
        }

        public function lightRes()
        {
            if(array_key_exists('lightres', $this->item_data)){
                return $this->item_data['lightres'] + (int)substr($this->getLevel(), 1);
            }
            return 0;
        }

        public function fireRes()
        {
            if(array_key_exists('fireres', $this->item_data)){
                return $this->item_data['fireres'] + (int)substr($this->getLevel(), 1);
            }
            return 0;
        }

        public function earthRes()
        {
            if(array_key_exists('earthres', $this->item_data)){
                return $this->item_data['earthres'] + (int)substr($this->getLevel(), 1);
            }
            return 0;
        }

        public function windRes()
        {
            if(array_key_exists('windres', $this->item_data)){
                return $this->item_data['windres'] + (int)substr($this->getLevel(), 1);
            }
            return 0;
        }

        public function waterRes()
        {
            if(array_key_exists('waterres', $this->item_data)){
                return $this->item_data['waterres'] + (int)substr($this->getLevel(), 1);
            }
            return 0;
        }

        public function reqStr()
        {
            if(array_key_exists('strreq', $this->item_data)){
                if($this->item_data['strreq'] > 0){
                    return floor(((($this->item_data['strreq'] * (((int)substr($this->getLevel(), 1) * 3) + ($this->item_data['lvldrop'] + $this->additionalValue()))) * 3) / 100) + 20);
                }
            }
            return 0;
        }

        public function reqAgi()
        {
            if(array_key_exists('agireq', $this->item_data)){
                if($this->item_data['agireq'] > 0){
                    return floor(((($this->item_data['agireq'] * (((int)substr($this->getLevel(), 1) * 3) + ($this->item_data['lvldrop'] + $this->additionalValue()))) * 3) / 100) + 20);
                }
            }
            return 0;
        }

        public function reqEne()
        {
            if(array_key_exists('enereq', $this->item_data)){
                if($this->item_data['enereq'] > 0){
                    $multiplier = ($this->type != 5 && $this->item_data['slot'] != 1) ? 4 : 3;
                    return floor(((($this->item_data['enereq'] * (((int)substr($this->getLevel(), 1) * 3) + ($this->item_data['lvldrop'] + $this->additionalValue()))) * $multiplier) / 100) + 20);
                }
            }
            return 0;
        }

        public function reqCom()
        {
            if(array_key_exists('cmdreq', $this->item_data)){
                if($this->item_data['cmdreq'] > 0){
                    if($this->index == $this->itemIndex(13, 5)){
                        return 185 + (1 * 15);
                    }
                    return floor(((($this->item_data['cmdreq'] * (((int)substr($this->getLevel(), 1) * 3) + ($this->item_data['lvldrop'] + $this->additionalValue()))) * 3) / 100) + 20);
                }
            }
            return 0;
        }

        public function durability()
        {
            if(array_key_exists('dur', $this->item_data)){
                if($this->type == 5)
                    $dur = $this->item_data['magdur']; else
                    $dur = $this->item_data['dur'];
                $level = (int)substr($this->getLevel(), 1);
                if($level <= 4)
                    $dur = $dur + ($level * 1);
                if($level < 10 && $level > 4)
                    $dur = $dur + (($level - 2) * 2);
                if($level == 10)
                    $dur = $dur + (($level * 2) - 3);
                if($level == 11)
                    $dur = $dur + (($level * 2) - 1);
                if($level == 12)
                    $dur = $dur + (($level + 1) * 2);
                if($level == 13)
                    $dur = $dur + (($level + 3) * 2);
                if($level == 14)
                    $dur = $dur + (($level * 2) + 11);
                if($level == 15)
                    $dur = $dur + (($level * 2) + 17);
                if($this->exe > 0 && $this->ancient != 0){
                    $dur += 20;
                } else if($this->exe <= 0 && $this->ancient != 0){
                    $dur += 20;
                } else if($this->exe > 0 && $this->ancient == 0){
                    $dur += 15;
                }
                return $dur;
            }
            return 0;
        }

        public function realName()
        {
            if($this->setItemTooltip()){
                if($this->item_tooltip['Unk3'] != -1){
                    $level = (int)substr($this->getLevel(), 1);
                    if($level > 0){
                        $this->getItemLevelTooltip();
                        foreach($this->item_level_tooltip AS $key => $value){
                            if($value[2] == $this->item_tooltip['Unk3'] + $level){
                                return $value[3];
                            }
                        }
                    }
                }
            }
            return $this->item_data['name'];
        }

        public function getNameStyle($return = false, $limit_text = 50)
        {
            $this->getName($return, $limit_text);
            return $this->name;
        }

        public function getName($return = false, $limit_text = 50)
        {
            $this->name = '';
            $class = ($return) ? '' : 'item_white';
            $exe = $this->exe;
			$realName = $this->realName();
            if($this->type == 12 && in_array($this->id, [60, 100, 106, 112, 118, 124, 61, 101, 107, 113, 118, 125, 62, 102, 108, 114, 119, 126, 63, 103, 109, 115, 120, 127, 64, 104, 110, 116, 121, 128, 65, 105, 111, 117, 122, 123, 129])){
                $level = '';
                if(in_array($this->id, [100, 101, 102, 103, 104, 105])){
                    $level = '[Level: 1]';
                }
                if(in_array($this->id, [106, 107, 108, 109, 110, 111])){
                    $level = '[Level: 2]';
                }
                if(in_array($this->id, [112, 113, 114, 115, 116, 117])){
                    $level = '[Level: 3]';
                }
                if(in_array($this->id, [118, 119, 120, 121, 122, 123])){
                    $level = '[Level: 4]';
                }
                if(in_array($this->id, [124, 125, 126, 127, 128, 129])){
                    $level = '[Level: 5]';
                }
                $this->name = '<div class="item_size_12 item_font_family item_socket_title">' . $this->website->set_limit($realName, $limit_text, '.') . $level . '</div>';
            } else{
                if($exe >= 64){
                    $exe -= 64;
                }
				$span = '';
                $prefix = ($exe > 0) ? __('Exc. ') : '';
                $level = ((int)substr($this->getlevel(), 1) > 0) ? $this->getlevel() : '';
                if(in_array($this->ancient, [5, 6, 9, 10, 20, 24])){
					$this->getAncient();
					$span = '</span>';
					if($this->anc_prefix != ''){
						$this->name = '<div class="item_size_12 item_font_family"><span class="item_ancient_background item_ancient_title">' . $prefix . $this->anc_prefix.' ';
					}
					else{
						$this->name = '<div class="item_size_12 item_font_family"><span class="item_ancient_background item_ancient_title">' . $prefix . __('Ancient').' ';
					} 
                } else{
					if(!isset($this->item_data['type']) || $this->item_data['type'] == ''){
						$itemType = (($this->type != 12) && ($this->socket[1] != $this->no_socket && !in_array($this->socket[1], [6, 7, 8, 9, 10])) || ($this->socket[2] != $this->no_socket && !in_array($this->socket[2], [6, 7, 8, 9, 10])) || ($this->socket[3] != $this->no_socket && !in_array($this->socket[3], [6, 7, 8, 9, 10])) || $this->socket[4] != $this->no_socket || $this->socket[5] != $this->no_socket) ? 2 : 1;
					}
					else{
						$itemType = $this->item_data['type'];
					}
                    if($itemType == 2){
						if($this->type == 12){
                            $class = ($level > 6) ? 'item_yellow_title' : $class;
                        } else{
                            $class = ($exe > 0) ? 'item_socket_exe_title' : 'item_socket_title';
                        }
                        $this->name = '<div class="item_size_12 item_font_family ' . $class . '">' . $prefix;
                    } else{
                        $class = ($level > 6) ? 'item_yellow_title' : $class;
                        $class = ($exe > 0) ? 'item_exe_title' : $class;
                        $this->name = '<div class="item_size_12 item_font_family ' . $class . '">' . $prefix . '';
                    }
                }
                $this->name .= $this->website->set_limit($realName, $limit_text, '.') . ' ' . $level . $span .'</div>';
            }
        }

        public function allInfo()
        {
            $this->canEquip();
			$this->getItemSkill();
			$this->additionalOption();
			$this->getLuck();
			//$this->getAncient();
			$this->getName();
			$this->getRefinery();
			$this->getHarmony();
			$this->getExe();
			$this->getSockets();
			$this->elementType();
			$this->elementInfo();
			$this->setTooltipOptions();
			$this->info = $this->name . '<br>';
			$this->info .= !empty($this->elementtype) ? $this->elementtype : '';
			$this->info .= !empty($this->tooltip_options) ? $this->tooltip_options : '';
			$this->info .= !empty($this->class) ? '<br>' . $this->class . '<br>' : '';
			$this->info .= !empty($this->stamina) ? $this->stamina . '<br>' : '';
			$this->info .= !empty($this->refopt) ? $this->refopt . '<br>' : '';
			$this->info .= !empty($this->haropt) ? $this->haropt . '<br>' : '';
			$this->info .= !empty($this->skill) ? $this->skill : '';
			$this->info .= !empty($this->luck) ? $this->luck : '';
			$this->info .= !empty($this->addopt) ? $this->addopt : '';
			$this->info .= !empty($this->exe_options) ? $this->exe_options . '<br>' : '';
			$this->info .= !empty($this->winggradeopt) ? $this->winggradeopt . '<br>' : '';
			$this->info .= !empty($this->ancopt) ? $this->ancopt . '<br>' : '';
			if($this->isErrtelItem()){
				$this->info .= '<div>' . $this->errtel_rank . ' Rank Errtel</div>';
			}
			$this->info .= !empty($this->sockopt) ? $this->sockopt . '<br>' : '';
			$this->info .= !empty($this->elementopt) ? $this->elementopt . '<br>' : '';
			$this->info .= !empty($this->serial) ? __('Serial').': ' . $this->serial . '<br>' : '';
			$this->info .= !empty($this->serial2) ? __('Serial').' 2: ' . $this->serial2 . '<br>' : '';
			return $this->info;
        }

        private function getExeType($slot, $id, $cat)
        {
            switch($slot){
				default:
				case -1:
				case 8:
					$exetype = -1;
					break;
				case 0:
				case 1:
				case 9:
					if($cat == 6){
						$exetype = 2;
					} else{
						$exetype = 1;
					}
					break;
				case 2:
				case 3:
				case 4:
				case 5:
				case 6:
				case 10:
				case 11:
					$exetype = 2;
					break;
				case 7:
					if($cat == 12 && in_array($id, [0, 1, 2, 3, 4, 5, 6, 41, 42, 130, 131, 132, 133, 134, 135, 278, 422, 423, 424, 425, 427]))
						$exetype = 24; 
					else if($cat == 12 && in_array($id, [36, 37, 38, 39, 40, 43, 50, 268, 467, 472, 430, 431, 432, 433, 434, 180, 181, 190, 191, 192, 193, 194, 195, 196]))
						$exetype = 25;
					else if($cat == 13 && in_array($id, [30]))
						$exetype = 26;
					else if($cat == 12 && in_array($id, [426]))
						$exetype = 26;
					else if($cat == 12 && in_array($id, [49, 428]))
						$exetype = 27;
					else if($cat == 12 && in_array($id, [262, 263, 264, 265]))
						$exetype = 28;
					else if($cat == 12 && in_array($id, [266, 269, 270, 429]))
						$exetype = 60;
					else if($cat == 12 && in_array($id, [267, 480]))
						$exetype = 62;
					else if($cat == 12 && in_array($id, [414, 415, 416, 417, 418, 419, 420, 421, 469]))
						$exetype = 76;	
					break;
			}
            if(!isset($exetype)){
				writelog('Invalid item exe type. Slot: ' . $slot . ', id: ' . $id . ', cat: ' . $cat . '', 'system_error');
			}
            return isset($exetype) ? $exetype : -1;
        }
    }