<?php
    in_file();

    class parse_server_file extends library
    {
        private $lang;
        private $file;
        private $info = [];
		private $ancinfo = [];
		private $gradeinfo = [];
        private $cache_days = 7;
        private $cache_time;
		private $dom;

        public function __construct($cache_time = '')
        {
            if($this->config->config_entry('main|cache_type') == 'file'){
                $this->load->lib('cache', ['File', ['cache_dir' => APP_PATH . DS . 'data' . DS . 'shop']]);
            } else{
                $this->load->lib('cache', ['MemCached', ['ip' => $this->config->config_entry('main|mem_cached_ip'), 'port' => $this->config->config_entry('main|mem_cached_port')]]);
            }
            $this->set_language();
            if($cache_time != '')
                $this->cache_time = $cache_time; 
			else
                $this->cache_time = (3600 * 24) * $this->cache_days;
        }

        public function parse_txt($type = ''){
			$file_list = [
				'exe_common' => 'ExcellentCommonOption.txt', 
				'exe_wing' => 'ExcellentWingOption.txt', 
				'item_add_option' => 'ItemAddOption.txt', 
				'item_level_tooltip' => 'ItemLevelTooltip.txt', 
				'item_set_option_text' => 'ItemSetOptionText.csv', 
				'item_set_type' => 'itemsettype.txt', 
				'item_tooltip' => 'ItemTooltip.csv', 
				'item_tooltip_text' => 'ItemTooltipText.csv', 
				'jewel_of_harmony_option' => 'JewelOfHarmonyOption.txt',
				'npc_names' => 'NpcName.txt', 
				'pentagram_jewel_option_value' => 'PentagramJewelOptionValue.txt', 
				'pentagram_jewel_option_value[5]' => 'PentagramJewelOptionValue[5].txt', 
				'pentagram_option_1' => 'PentagramOptionGroup1.txt', 
				'pentagram_option_2' => 'PentagramOptionGroup2.txt', 
				'skill' => 'skill.txt', 
				'socket_item' => 'SocketItem.txt', 
				'socket_item[6]' => 'SocketItem[6].txt'
			];
			$patter_list = [
				'exe_common' => '[\s]?([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([\p{L}\-\(\)\[\]\\\'\&\%\:\~\#\.\,\?\!\$\*\+\=\/ ?0-9]+)[\s]' . substr(str_repeat("{0,}([0-9]{0,})[\s]", 12), 0, -4), 
				'exe_wing' => '[\s]?([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([\p{L}\-\(\)\[\]\\\'\&\%\:\~\#\.\,\?\!\$\*\+\=\/ ?0-9]+)[\s]' . substr(str_repeat("{0,}([0-9]{0,})[\s]", 12), 0, -4), 
				'item_add_option' => '[\s]?([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+(-?[0-9]+)', 
				'item_level_tooltip' => '[\s]?([0-9]+)[\s]+([0-9]+)[\s]+([\p{L}\-\(\)\[\]\\\'\&\%\:\~\#\.\,\?\!\$\*\+\=\/ ?0-9]+)[\s]' . substr(str_repeat("{0,}(-?[0-9]{0,})[\s]", 26), 0, -4), 
				'item_set_option_text' => '([0-9]+)[\s]+([\p{L}\-\(\)\[\]\\\'\&\%\:\~\#\.\,\?\!\$\*\+\=\/ ?0-9]+)[\s]' . substr(str_repeat("{0,}(-?[0-9]{0,})[\s]", 1), 0, -4), 
				'item_set_type' => '[\s]?([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)', 
				'item_tooltip' => '([0-9]+)[\s]+([0-9]+)[\s]+([\p{L}\-\(\)\[\]\\\'\&\%\:\~\#\.\,\?\!\$\*\+\=\/ ?0-9]+)[\s]+' . substr(str_repeat("(-?[0-9]+)[\s]+", 28), 0, -5), 
				'item_tooltip_text' => '([0-9]+)[\s]+([\p{L}\-\(\)\[\]\\\'\&\%\:\~\#\.\,\?\!\$\*\+\=\/ ?0-9]+)[\s]' . substr(str_repeat("{0,}(-?[0-9]{0,})[\s]", 1), 0, -4), 
				'jewel_of_harmony_option' => '[\s]?([0-9]+)[\s]+([0-9]+)[\s]+([\p{L}\-\(\)\[\]\\\'\&\%\:\~\#\.\,\?\!\$\*\+\=\/ ?0-9]+)[\s]+' . substr(str_repeat("([0-9]+)[\s]+", 28), 0, -5), 
				'npc_names' => '[\s]?([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([0-9]+)[\s]+([\p{L}\-\(\)\[\]\\\'\&\%\:\~\#\.\,\?\!\$\*\+\=\/ ?0-9]+)[\s]+([0-9]+)', 
				'pentagram_jewel_option_value' => '[\s]?' . str_repeat("(-?[0-9]+)[\s]+", 32) . '([\p{L}\-\(\)\[\]\\\'\&\%\:\~\#\.\,\?\!\$\*\+\=\/ ?0-9]+)[\s]+([0-9]+)', 
				'pentagram_jewel_option_value[5]' => '[\s]?' . str_repeat("(-?[0-9]+)[\s]+", 32) . '([\p{L}\-\(\)\[\]\\\'\&\%\:\~\#\.\,\?\!\$\*\+\=\/ ?0-9]+)[\s]+([0-9]+)', 
				'pentagram_option_1' => '[\s]?' . str_repeat("(-?[0-9]+)[\s]+", 10) . '([\p{L}\-\(\)\[\]\\\'\&\%\:\~\#\.\,\?\!\$\*\+\=\/ ?0-9]+)[\s]+' . substr(str_repeat("(-?[0-9]+)[\s]+", 9), 0, -5), 
				'pentagram_option_2' => '[\s]?' . str_repeat("(-?[0-9]+)[\s]+", 2) . '([\p{L}\-\(\)\[\]\\\'\&\%\:\~\#\.\,\?\!\$\*\+\=\/ ?0-9]+)[\s]+' . substr(str_repeat("(-?[0-9]+)[\s]+", 10), 0, -5), 
				'skill' => '[\s]?' . str_repeat("(-?[0-9]+)[\s]+", 1) . '([\p{L}\-\(\)\[\]\\\'\&\%\:\~\#\.\,\?\!\$\*\+\=\/ ?0-9]+)[\s]+' . substr(str_repeat("(-?[0-9]+)[\s]+", 54), 0, -5), 
				'socket_item' => '[\s]?' . str_repeat("(-?[0-9]+)[\s]+", 4) . '([\p{L}\-\(\)\[\]\\\'\&\%\:\~\#\.\,\?\!\$\*\+\=\/ ?0-9]+)[\s]+' . substr(str_repeat("(-?[0-9]+)[\s]+", 14), 0, -5), 
				'socket_item[6]' => '[\s]?' . str_repeat("(-?[0-9]+)[\s]+", 4) . '([\p{L}\-\(\)\[\]\\\'\&\%\:\~\#\.\,\?\!\$\*\+\=\/ ?0-9]+)[\s]+' . substr(str_repeat("(-?[0-9]+)[\s]+", 29), 0, -5)
			];
			$key_list = [
				'item_add_option' => ['group', 'index', 'type1', 'rise1', 'type2', 'rise2', 'time'], 
				'item_set_type' => ['index', 'set', 'typeA', 'set2', 'typeB', 'set3', 'typeC'], 
				'item_tooltip' => ['', 'Group', 'Index', 'Name', 'Color', 'Unk1', 'Unk2', 'Unk3', 'iInfoLine_1', 'Unk4', 'iInfoLine_2', 'Unk5', 'iInfoLine_3', 'Unk6', 'iInfoLine_4', 'Unk7', 'iInfoLine_5', 'Unk8', 'iInfoLine_6', 'Unk9', 'iInfoLine_7', 'Unk10', 'iInfoLine_8', 'Unk11', 'iInfoLine_9', 'Unk12', 'iInfoLine_10', 'Unk13', 'iInfoLine_11', 'Unk14', 'iInfoLine_12', 'Unk15'], 
				'npc_names' => ['', 'LineIndex', 'id', 'unk1', 'unk2', 'name', 'unk3']
			];
			if($type != ''){
				if(array_key_exists($type, $file_list)){
					if($this->check_file($file_list[$type])){
						ini_set("auto_detect_line_endings", true);
						$data = file($this->file, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
						$i = 0;
						foreach($data AS $line){
							if($type == 'item_set_type'){
								if(is_numeric(trim(substr($line, 0, 1))) && strlen(trim($line)) <= 15){
									$cat = (int)trim($line);
									continue;
								}
							}
							if(preg_match('/' . $patter_list[$type] . '$/u', $line, $match)){
								$i++;
								unset($match[0]);
								if($type == 'item_tooltip'){
									unset($match[3]);
									$item_cat = $match[1];
									$id = $match[2];
									unset($match[1]);
									unset($match[2]);
										
								}

								if($type == 'exe_common' || $type == 'exe_wing'){
									unset($match[1]);
								}
								if(array_key_exists($type, $key_list)){
									foreach($match AS $k => $v){
										if(isset($cat)){
											if(isset($key_list[$type][$k])){
												$this->info[$cat][$match[1]][$key_list[$type][$k]] = $v;
											}
										} else{
											if(isset($key_list[$type][$k])){
												if($type == 'item_tooltip'){
													$this->info[$item_cat][$id][$key_list[$type][$k]] = $v;
												} else{
													$this->info[$i][$key_list[$type][$k]] = $v;
												}
											}
										}
									}
								} else{
									if($type == 'skill'){
										$this->info[$match[1]] = $match;
									} 
									else if($type == 'item_tooltip_text'){
										$this->info[$match[1]] = $match;
									} else if($type == 'item_level_tooltip'){
										if(empty($match[3])){
											continue;
										}
										$this->info[$match[2]] = $match;
									} else{
										$this->info[] = $match;
									}
								}
							}
						}
					} else{
						writelog('[Server File Parser] File not found: ' . $this->file, 'system_error');
						return false;
					}
				} else{
					writelog('[Server File Parser] type not found: ' . $type, 'system_error');
					return false;
				}
			} else{
				writelog('[Server File Parser] type is empty', 'system_error');
				return false;
			}
			return true;
		}
		
		public function parse_xml_item_grade(){
			if($this->check_file('ItemGradeOption.xml')){
				$this->dom = new \DomDocument();
				$this->dom->load($this->file);
				$xp = new \DomXPath($this->dom);
				$res = $xp->query('List/Option');
				foreach($res AS $s => $v){
					$this->gradeinfo[$v->getAttribute('Index')] = [
						'name' => $v->getAttribute('Name'),
						'Grade0Val' => $v->getAttribute('Grade0Val'),
						'Grade1Val' => $v->getAttribute('Grade1Val'),
						'Grade2Val' => $v->getAttribute('Grade2Val'),
						'Grade3Val' => $v->getAttribute('Grade3Val'),
						'Grade4Val' => $v->getAttribute('Grade4Val'),
						'Grade5Val' => $v->getAttribute('Grade5Val'),
						'Grade6Val' => $v->getAttribute('Grade6Val'),
						'Grade7Val' => $v->getAttribute('Grade7Val'),
						'Grade8Val' => $v->getAttribute('Grade8Val'),
						'Grade9Val' => $v->getAttribute('Grade9Val')
					];
				}
				return true;
			}
			return false;
		}
		
		public function parse_xml_set_options(){
			if($this->check_file('ItemSetOption.xml')){
				$this->dom = new \DomDocument();
				$this->dom->load($this->file);
				$xp = new \DomXPath($this->dom);
				$res = $xp->query('SetItem');
				foreach($res AS $s => $v){
					$this->ancinfo[$v->getAttribute('Index')] = [
						'name' => $v->getAttribute('Name'),
						'opt_1_1' => $v->getAttribute('OptIdx1_1'),
						'opt_1_1_val' => $v->getAttribute('OptVal1_1'),
						'opt_2_1' => $v->getAttribute('OptIdx2_1'),
						'opt_2_1_val' => $v->getAttribute('OptVal2_1'),
						'opt_1_2' => $v->getAttribute('OptIdx1_2'),
						'opt_1_2_val' => $v->getAttribute('OptVal1_2'),
						'opt_2_2' => $v->getAttribute('OptIdx2_2'),
						'opt_2_2_val' => $v->getAttribute('OptVal2_2'),
						'opt_1_3' => $v->getAttribute('OptIdx1_3'),
						'opt_1_3_val' => $v->getAttribute('OptVal1_3'),
						'opt_2_3' => $v->getAttribute('OptIdx2_3'),
						'opt_2_3_val' => $v->getAttribute('OptVal2_3'),
						'opt_1_4' => $v->getAttribute('OptIdx1_4'),
						'opt_1_4_val' => $v->getAttribute('OptVal1_4'),
						'opt_2_4' => $v->getAttribute('OptIdx2_4'),
						'opt_2_4_val' => $v->getAttribute('OptVal2_4'),
						'opt_1_5' => $v->getAttribute('OptIdx1_5'),
						'opt_1_5_val' => $v->getAttribute('OptVal1_5'),
						'opt_2_5' => $v->getAttribute('OptIdx2_5'),
						'opt_2_5_val' => $v->getAttribute('OptVal2_5'),
						'opt_1_6' => $v->getAttribute('OptIdx1_6'),
						'opt_1_6_val' => $v->getAttribute('OptVal1_6'),
						'opt_2_6' => $v->getAttribute('OptIdx2_6'),
						'opt_2_6_val' => $v->getAttribute('OptVal2_6'),
						'fopt_1' => $v->getAttribute('FullOptIdx1'),
						'fopt_val1' => $v->getAttribute('FullOptVal1'),
						'fopt_2' => $v->getAttribute('FullOptIdx2'),
						'fopt_val2' => $v->getAttribute('FullOptVal2'),
						'fopt_3' => $v->getAttribute('FullOptIdx3'),
						'fopt_val3' => $v->getAttribute('FullOptVal3'),
						'fopt_4' => $v->getAttribute('FullOptIdx4'),
						'fopt_val4' => $v->getAttribute('FullOptVal4'),
						'fopt_5' => $v->getAttribute('FullOptIdx5'),
						'fopt_val5' => $v->getAttribute('FullOptVal5'),
						'fopt_6' => $v->getAttribute('FullOptIdx6'),
						'fopt_val6' => $v->getAttribute('FullOptVal6'),
						'fopt_7' => $v->getAttribute('FullOptIdx7'),
						'fopt_val7' => $v->getAttribute('FullOptVal7'),
					];
				}
				return true;
			}
			return false;
		}

        public function parse_xml($cat = [])
        {
            static $ItemList = null;
            if($this->check_file('ItemList.xml')){
                libxml_use_internal_errors(true);
                if($ItemList == null)
                    $ItemList = simplexml_load_file($this->file);
                if($ItemList === false){
                    $error_line = '';
                    foreach(libxml_get_errors() as $error){
                        $error_line .= $error->message . ', file ' . $error->file . ', line: ' . $error->line . '<br>';
                    }
                    writelog('[Server File Parser] Unable to parse xml: ' . $error_line, 'system_error');
                    return false;
                }
                if(!empty($cat)){
                    foreach($cat AS $category){
                        $list = $ItemList->xpath("//ItemList/Section[@Index='" . $category . "']/Item");
                        if(!empty($list)){
                            foreach($list AS $item){
                                $this->info[$category][(string)$item->attributes()->Index] = $this->load_item_attributes($item);
                            }
                        }
                    }
                }
                return true;
            }
            return false;
        }

        public function parse_item_txt()
        {
            static $file_data = null;
            static $items = [];
            if($this->check_file('Item.txt')){
                $keys = [];
                $keys[0] = ['id', 'slot', 'skill', 'x', 'y', 'serial', 'option', 'drop', 'name', 'lvldrop', 'mindmg', 'maxdmg', 'attspeed', 'dur', 'magdur', 'magpower', 'lvlreq', 'strreq', 'agireq', 'enereq', 'vitreq', 'cmdreq', 'setattribute', 'dw/sm', 'dk/bk', 'elf/me', 'mg', 'dl', 'sum', 'rf', 'gl', 'rw', 'sl'];
                $keys[1] = $keys[0];
                $keys[2] = $keys[0];
                $keys[3] = $keys[0];
                $keys[4] = $keys[0];
                $keys[5] = $keys[0];
                $keys[6] = ['id', 'slot', 'skill', 'x', 'y', 'serial', 'option', 'drop', 'name', 'lvldrop', 'def', 'successblock', 'dur', 'lvlreq', 'strreq', 'agireq', 'enereq', 'vitreq', 'cmdreq', 'setattribute', 'dw/sm', 'dk/bk', 'elf/me', 'mg', 'dl', 'sum', 'rf', 'gl', 'rw', 'sl'];
                $keys[7] = ['id', 'slot', 'skill', 'x', 'y', 'serial', 'option', 'drop', 'name', 'lvldrop', 'def', 'magdef', 'dur', 'lvlreq', 'strreq', 'agireq', 'enereq', 'vitreq', 'cmdreq', 'setattribute', 'dw/sm', 'dk/bk', 'elf/me', 'mg', 'dl', 'sum', 'rf', 'gl', 'rw', 'sl'];
                $keys[8] = $keys[7];
                $keys[9] = $keys[7];
                $keys[10] = $keys[7];
                $keys[11] = $keys[7];
                $keys[12] = ['id', 'slot', 'skill', 'x', 'y', 'serial', 'option', 'drop', 'name', 'lvldrop', 'def', 'dur', 'lvlreq', 'enereq', 'strreq', 'dexreq', 'comreq', 'buymoney', 'dw/sm', 'dk/bk', 'elf/me', 'mg', 'dl', 'sum', 'rf', 'gl', 'rw', 'sl'];
                $keys[13] = ['id', 'slot', 'skill', 'x', 'y', 'serial', 'option', 'drop', 'name', 'lvlreq', 'dur', 'res1', 'res2', 'res3', 'res4', 'res5', 'res6', 'res7', 'setattribute', 'dw/sm', 'dk/bk', 'elf/me', 'mg', 'dl', 'sum', 'rf', 'gl', 'rw', 'sl'];
                $keys[14] = ['id', 'slot', 'skill', 'x', 'y', 'serial', 'option', 'drop', 'name', 'value', 'lvldrop'];
                $keys[15] = ['id', 'slot', 'skill', 'x', 'y', 'serial', 'option', 'drop', 'name', 'lvldrop', 'lvlreq', 'enereq', 'buymoney', 'dw/sm', 'dk/bk', 'elf/me', 'mg', 'dl', 'sum', 'rf', 'gl', 'rw', 'sl'];
                if($file_data == null){
                    ini_set("auto_detect_line_endings", true);
                    $file_data = file($this->file, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
                }
                if(empty($items)){
                    foreach($file_data AS $line){
                        if(is_numeric(trim(substr($line, 0, 1))) && strlen(trim($line)) <= 15){
                            $type = (int)trim($line);
                            continue;
                        }
                        if(preg_match('/([0-9\*]+)[\s]+([0-9\-\*]+)[\s]+([0-9\*]+)[\s]+([0-9\*]+)[\s]+([0-9\*]+)[\s]+([0-9\*]+)[\s]+([0-9\*]+)[\s]+([0-9\*]+)[\s]+"([\p{L}\-\(\)\[\]\'\& ?0-9]+)"[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})[\s]{0,}([0-9\*]{0,})$/u', $line, $match)){
                            unset($match[0]);
                            foreach($match AS $k => $v){
                                if(isset($keys[$type][$k - 1])){
                                    $items[$type][$match[1]][$keys[$type][$k - 1]] = $v;
                                }
                            }
                        }
                    }
                }
                $this->info = $items;
                return true;
            }
            return false;
        }

        private function load_item_attributes($item)
		{
			$items = [
				'id' => (string)$item->attributes()->Index, 
				'slot' => (string)$item->attributes()->Slot, 
				'skill' => (string)$item->attributes()->SkillIndex, 
				'x' => (string)$item->attributes()->Width, 
				'y' => (string)$item->attributes()->Height, 
				'option' => (string)$item->attributes()->Option, 
				'name' => (string)$item->attributes()->Name, 
				'lvldrop' => (string)$item->attributes()->DropLevel, 
				'kindA' => (string)$item->attributes()->KindA, 
				'kindB' => (string)$item->attributes()->KindB,
				'type' => (string)$item->attributes()->Type
			];
			if(isset($item->attributes()->Durability))
				$items['dur'] = (string)$item->attributes()->Durability;
			if(isset($item->attributes()->DamageMin))
				$items['mindmg'] = (string)$item->attributes()->DamageMin;
			if(isset($item->attributes()->DamageMin))
				$items['mindmg'] = (string)$item->attributes()->DamageMin;
			if(isset($item->attributes()->DamageMax))
				$items['maxdmg'] = (string)$item->attributes()->DamageMax;
			if(isset($item->attributes()->AttackSpeed))
				$items['attspeed'] = (string)$item->attributes()->AttackSpeed;
			if(isset($item->attributes()->MagicDurability))
				$items['magdur'] = (string)$item->attributes()->MagicDurability;
			if(isset($item->attributes()->WalkSpeed))
				$items['attspeed'] = (string)$item->attributes()->WalkSpeed;
			if(isset($item->attributes()->Defense))
				$items['def'] = (string)$item->attributes()->Defense;
			if(isset($item->attributes()->SuccessfulBlocking))
				$items['successblock'] = (string)$item->attributes()->SuccessfulBlocking;
			if(isset($item->attributes()->MagicPower))
				$items['magpow'] = (string)$item->attributes()->MagicPower;
			if(isset($item->attributes()->MagicDefense))
				$items['magdef'] = (string)$item->attributes()->MagicDefense;
			if(isset($item->attributes()->ReqLevel))
				$items['lvlreq'] = (string)$item->attributes()->ReqLevel;
			if(isset($item->attributes()->ReqStrength))
				$items['strreq'] = (string)$item->attributes()->ReqStrength;
			if(isset($item->attributes()->ReqDexterity))
				$items['agireq'] = (string)$item->attributes()->ReqDexterity;
			if(isset($item->attributes()->ReqVitality))
				$items['vitreq'] = (string)$item->attributes()->ReqVitality;
			if(isset($item->attributes()->ReqCommand))
				$items['cmdreq'] = (string)$item->attributes()->ReqCommand;
			if(isset($item->attributes()->ReqEnergy))
				$items['enereq'] = (string)$item->attributes()->ReqEnergy;
			if(isset($item->attributes()->IceRes))
				$items['iceres'] = (string)$item->attributes()->IceRes;
			if(isset($item->attributes()->PoisonRes))
				$items['poisonres'] = (string)$item->attributes()->PoisonRes;
			if(isset($item->attributes()->LightRes))
				$items['lightres'] = (string)$item->attributes()->LightRes;
			if(isset($item->attributes()->FireRes))
				$items['fireres'] = (string)$item->attributes()->FireRes;
			if(isset($item->attributes()->EarthRes))
				$items['earthres'] = (string)$item->attributes()->EarthRes;
			if(isset($item->attributes()->WindRes))
				$items['windres'] = (string)$item->attributes()->WindRes;
			if(isset($item->attributes()->WaterRes))
				$items['waterres'] = (string)$item->attributes()->WaterRes;
			if(isset($item->attributes()->SetAttrib))
				$items['setattrib'] = (string)$item->attributes()->SetAttrib;
			if(isset($item->attributes()->DarkWizard))
				$items['dw/sm'] = (string)$item->attributes()->DarkWizard;
			if(isset($item->attributes()->DarkKnight))
				$items['dk/bk'] = (string)$item->attributes()->DarkKnight;
			if(isset($item->attributes()->FairyElf))
				$items['elf/me'] = (string)$item->attributes()->FairyElf;
			if(isset($item->attributes()->MagicGladiator))
				$items['mg'] = (string)$item->attributes()->MagicGladiator;
			if(isset($item->attributes()->DarkLord))
				$items['dl'] = (string)$item->attributes()->DarkLord;
			if(isset($item->attributes()->Summoner))
				$items['sum'] = (string)$item->attributes()->Summoner;
			if(isset($item->attributes()->RageFighter))
				$items['rf'] = (string)$item->attributes()->RageFighter;
			if(isset($item->attributes()->GrowLancer))
				$items['gl'] = (string)$item->attributes()->GrowLancer;
			if(isset($item->attributes()->RuneWizard))
				$items['rw'] = (string)$item->attributes()->RuneWizard;
			if(isset($item->attributes()->Slayer))
				$items['sl'] = (string)$item->attributes()->Slayer;	
			if(isset($item->attributes()->SetAttrib))
				$items['setattribute'] = (string)$item->attributes()->SetAttrib;			
			return $items;
		}

        private function set_language()
        {
            $this->lang = 'en_GB';//htmlspecialchars($_COOKIE['dmn_language']);
        }

        private function check_file($file)
        {
            $this->file = APP_PATH . DS . 'data' . DS . 'ServerData/' . $this->lang . '/' . $file;
            if(is_file($this->file))
                return true; else{
                $this->file = APP_PATH . DS . 'data' . DS . 'ServerData/en_GB/' . $file;
                if(is_file($this->file))
                    return true;
            }
            return false;
        }

        public function parse_all()
        {
            $file_list_txt = ['exe_common', 'exe_wing', 'item_add_option', 'item_level_tooltip', 'item_set_option_text', 'item_set_type', 'item_tooltip', 'item_tooltip_text', 'jewel_of_harmony_option', 'npc_names', 'pentagram_jewel_option_value', 'pentagram_jewel_option_value[5]', 'pentagram_option_1', 'pentagram_option_2', 'skill', 'socket_item', 'socket_item[6]'];
            foreach($file_list_txt AS $type){
                $cache_data = $this->cache->get($type . '#' . $this->lang);
                if(!$cache_data){
                    if($this->parse_txt($type) != false){
                        $this->cache->set($type . '#' . $this->lang, $this->info, $this->cache_time);
                        $this->info = [];
                    }
                }
            }
            $item_xml = $this->parse_xml([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]);
            for($i = 0; $i <= 15; $i++){
                if($item_xml != false){
                    $cache_data = $this->cache->get('item_list[64][' . $i . ']#' . $this->lang);
                    if(!$cache_data){
                        $this->cache->set('item_list[64][' . $i . ']' . '#' . $this->lang, $this->info[$i], $this->cache_time);
                    }
                }
            }
            $item_txt = $this->parse_item_txt();
            for($i = 0; $i <= 15; $i++){
                if($item_txt != false){
                    $cache_data = $this->cache->get('item_list[32][' . $i . ']#' . $this->lang);
                    if(!$cache_data){
                        $this->cache->set('item_list[32][' . $i . ']' . '#' . $this->lang, $this->info[$i], $this->cache_time);
                    }
                }
            }
			$item_anc_opt = $this->parse_xml_set_options();
			if($item_anc_opt != false){
				$cache_data = $this->cache->get('item_set_option#' . $this->lang);
				if(!$cache_data){
					$this->cache->set('item_set_option#' . $this->lang, $this->ancinfo, $this->cache_time);
				}
			}
			
			$item_grade_opt = $this->parse_xml_item_grade();
			if($item_grade_opt != false){
				$cache_data = $this->cache->get('item_grade_option#' . $this->lang);
				if(!$cache_data){
					$this->cache->set('item_grade_option#' . $this->lang, $this->gradeinfo, $this->cache_time);
				}
			}
        }
    }