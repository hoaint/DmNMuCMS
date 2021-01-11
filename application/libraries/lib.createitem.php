<?php
    in_file();

    /**
     * Copyright 2016 DmN CMS.
     *
     * Usage example
     * $this->load->lib('createitem'); //initialize library
     * 1) echo $this->createitem->make(1,1, false, [], 100, '00000000', false, 5, 1, 1, 5, [])->to_hex(); // generate and return all item at once
     * 2) $this->createitem->make(1,3, false, [], 500, '00000000', false, 5, 1, 1, 5, []); // set item parameters
     * -- echo $this->createitem->to_hex(); / generate and return item hex
     * 3) echo $this->createitem->id(2)->cat(5)->serial('12345678')->to_hex(); set item id, category, serial after generate and return item hex
     */
    class createitem extends library
    {
        private $version = 2;
        private $id = false, $cat = false, $ref = false, $harmony = [], $dur = 0, $serial = 0, $serial2 = false, $lvl = 0, $skill = false, $luck = false, $opt = 0, $exe = [], $exe_socket = 0, $fenrir = 0, $ancient = 0, $socket = [], $element_type = false, $lement_ranks = [], $element_level = [], $element_count = 0, $sockets = [], $exe_in_socket_slot = [];
        private $exe_opts = [0 => 0, 1 => 1, 2 => 2, 3 => 4, 4 => 8, 5 => 16, 6 => 32, 7 => 6, 8 => 7, 9 => 8, 10 => 9, 11 => 10];
        public $is_socket_ancient = false;
        public $is_socket_exe = false;
        private $ancient_opts = [0 => 0, 1 => 9, 2 => 10, 5 => 5, 6 => 6, 9 => 9, 10 => 10];
        private $fenrir_opts = [0 => 0, 1 => 1, 2 => 2, 4 => 4];
        private $socket_type = 0;
        private $no_socket;
        private $empty_socket;
        private $errtel_ids = [221, 231, 241, 251, 261];
        private $pentagram_ids = [200, 201, 202, 203, 204, 205, 206, 207, 208, 209, 210, 211, 212, 213, 214, 217, 306, 307, 308];
        private $item_data;
        private $serials_from_hex = false;
        public $hex_serial = [];

        /**
         * Class constructor
         *
         * @param int $version
         * @param int $socket_type
         *
         * @return void
         */
        public function __construct($version = 2, $socket_type = 0)
        {
            $this->muVersion($version);
            $this->socket_type = $socket_type;
            $this->no_socket = ($socket_type == 1) ? 255 : 0;
            $this->empty_socket = ($socket_type == 1) ? 254 : 255;
        }

        /**
         * Set MuServer version
         *
         * @param int $version between 0 and 12
         * - version 0 - below season 1
         * - version 1 - season 1
         * - version 2 - season 2 and higher
         * - version 3 - ex700 and higher
         * - version 4 - season 8 and higher
         * - version 5 - season 10 and higher
         *
         * @return void
         */
        public function muVersion($version)
        {
            try{
                if(!is_numeric($version))
                    throw new Exception('Parameter $version should be numeric value');
                if(!in_array($version, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]))
                    throw new Exception('Parameter $version should be between 0 - 10');
                $this->version = $version;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }

        /**
         * Set item data from server file
         *
         * @param array $data
         *
         * @return void
         */
        public function setItemData($data = [])
        {
            try{
                if(!is_array($data))
                    throw new Exception('Parameter $data should be array');
                $this->item_data = $data;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }

        /**
         * Set Item id
         *
         * @param int $id
         *
         * @return createitem object
         */
        public function id($id)
        {
            try{
                if(!is_numeric($id))
                    throw new Exception('Parameter $id should be numeric value');
                $this->id = $id;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set Item category
         *
         * @param int $cat
         *
         * @return createitem object
         */
        public function cat($cat)
        {
            try{
                if(!is_numeric($cat))
                    throw new Exception('Parameter $cat should be numeric value');
                $this->cat = $cat;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set Refinery Option
         *
         * @param bool $ref
         *
         * @return createitem object
         */
        public function refinery($ref = false)
        {
            try{
                if(!is_bool($ref))
                    throw new Exception('Parameter $ref should be true or false');
                $this->ref = $ref;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set Harmony Option
         *
         * @param array $hoption
         *
         * @return createitem object
         */
        public function harmony($hoption = [])
        {
            try{
                if(!is_array($hoption))
                    throw new Exception('Parameter $hoption should be array');
                $this->harmony = $hoption;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set Durability
         *
         * @param int $dur
         *
         * @return createitem object
         */
        public function dur($dur)
        {
            try{
                if(!is_numeric($dur))
                    throw new Exception('Parameter $dur should be numeric value');
                $this->dur = $dur;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        public function serialsFromHex($data)
        {
            try{
                if(!is_bool($data))
                    throw new Exception('Parameter $data should be boolean');
                $this->serials_from_hex = $data;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set Serial
         *
         * @param int $serial
         *
         * @return createitem object
         */
        public function serial($serial)
        {
            try{
                if(!is_numeric($serial))
                    throw new Exception('Parameter $serial should be number');
                $this->serial = $serial;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set Serial 2
         *
         * @param bool $serial
         *
         * @return createitem object
         */
        public function serial2($serial = false)
        {
            try{
                if(!is_bool($serial))
                    throw new Exception('Parameter $serial should be true or false');
                $this->serial2 = $serial;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set Level
         *
         * @param int $lvl
         *
         * @return createitem object
         */
        public function lvl($lvl)
        {
            try{
                if(!is_numeric($lvl))
                    throw new Exception('Parameter $lvl should be numeric value');
                $this->lvl = $lvl;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set Stick Level
         *
         * @param int $lvl
         *
         * @return createitem object
         */
        public function stickLvl($lvl)
        {
            try{
                if(!is_numeric($lvl))
                    throw new Exception('Parameter $lvl should be numeric value');
                $this->lvl = $lvl;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set Skill
         *
         * @param bool $skill
         *
         * @return createitem object
         */
        public function skill($skill)
        {
            try{
                if(!is_bool($skill))
                    throw new Exception('Parameter $skill should be true or false');
                $this->skill = $skill;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set Luck
         *
         * @param bool $luck
         *
         * @return createitem object
         */
        public function luck($luck)
        {
            try{
                if(!is_bool($luck))
                    throw new Exception('Parameter $luck should be true or false');
                $this->luck = $luck;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set Option
         *
         * @param int $option
         *
         * @return createitem object
         */
        public function opt($option)
        {
            try{
                if(!is_numeric($option))
                    throw new Exception('Parameter $option should be numeric value');
                $this->opt = $option;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set Exe Options
         *
         * @param array $exe
         *
         * @return createitem object
         */
        public function exe($exe = [])
        {
            try{
                if(!is_array($exe))
                    throw new Exception('Parameter $exe should be array');
                $this->exe = $exe;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Add Exe Options
         *
         * @param int $exe
         *
         * @return createitem object
         */
        public function addExe($exe)
        {
            try{
                if(!is_numeric($exe))
                    throw new Exception('Parameter $exe should be integer');
                $this->exe_socket += $exe;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        public function removeExe($exe)
        {
            try{
                if(!is_numeric($exe))
                    throw new Exception('Parameter $exe should be integer');
                $this->exe_socket -= $exe;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set Fenrir Option
         *
         * @param int $fenrir
         *
         * @return createitem object
         */
        public function fenrir($fenrir)
        {
            try{
                if(!is_numeric($fenrir))
                    throw new Exception('Parameter $fenrir should be numeric value');
                $this->fenrir = $fenrir;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set Ancient Option
         *
         * @param int $ancient
         *
         * @return createitem object
         */
        public function ancient($ancient)
        {
            try{
                if(!is_numeric($ancient))
                    throw new Exception('Parameter $ancient should be numeric value');
                $this->ancient = $ancient;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set Ancient Option
         *
         * @param int $ancient
         *
         * @return createitem object
         */
        public function addAncient($ancient)
        {
            try{
                if(!is_numeric($ancient))
                    throw new Exception('Parameter $ancient should be numeric value');
                $this->ancient += $ancient;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set Socket Options
         *
         * @param array $sockets
         *
         * @return createitem object
         */
        public function socket($sockets = [])
        {
            try{
                if(!is_array($sockets))
                    throw new Exception('Parameter $sockets should be array');
                $this->socket = $sockets;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set Element Type
         *
         * @param int $element
         *
         * @return createitem object
         */
        public function elementType($element = false)
        {
            try{
                if($element != false && !is_numeric($element))
                    throw new Exception('Parameter $element should be numeric value');
                $this->element_type = $element;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set ElementRanks
         *
         * @param int $ranks
         *
         * @return createitem object
         */
        public function elementRanks($ranks = [])
        {
            try{
                if(!is_array($ranks))
                    throw new Exception('Parameter $ranks should be array');
                $this->element_ranks = $ranks;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set ElementCount
         *
         * @param int $count
         *
         * @return void
         */
        public function elementCount($count = 1)
        {
            try{
                if(!is_numeric($count))
                    throw new Exception('Parameter $count should be numeric value');
                $this->element_count += $count;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }

        /**
         * Set ElementLevels
         *
         * @param int $count
         *
         * @return createitem object
         */
        public function elementLevels($levels = [])
        {
            try{
                if(!is_array($levels))
                    throw new Exception('Parameter $levels should be array');
                $this->element_levels = $levels;
            } catch(Exception $e){
                throw new Exception($e->getMessage());
            }
            return $this;
        }

        /**
         * Set All Item Options
         *
         * @param int $id
         * @param int $cat
         * @param bool $ref
         * @param array $hoption
         * @param int $dur
         * @param int $lvl
         * @param int $skill
         * @param int $luck
         * @param int $option
         * @param array $exe
         * @param int $fenrir
         * @param int $ancient
         * @param array $sockets
         * @param mixed $element_type
         * @param array $element_ranks
         * @param array $element_levels
         *
         * @return createitem object
         */
        public function make($id, $cat, $ref = false, $hoption = [], $dur = 0, $serial = 0, $serial2 = false, $lvl = 0, $skill = false, $luck = false, $option = 0, $exellent = [], $fenrir = 0, $ancient = 0, $sockets = [], $element_type = false, $element_ranks = [], $element_levels = [])
        {
            $this->id($id)->cat($cat)->refinery($ref)->harmony($hoption)->dur($dur)->serial($serial)->serial2($serial2)->lvl($lvl)->skill($skill)->luck($luck)->opt($option)->exe($exellent)->fenrir($fenrir)->ancient($ancient)->socket($sockets)->elementType($element_type)->elementRanks($element_ranks)->elementLevels($element_levels);
            return $this;
        }

        /**
         * Set Item Id
         *
         * @return string
         */
        private function setId()
        {
            $id = $this->id;
            if(in_array($this->version, [0, 1]))
                $id = ((($this->id & 31) | (($this->cat << 5) & 224)) & 255); else{
                if($this->id > 255){
                    $id -= 256;
                }
            }
            return sprintf("%02X", $id, 00);
        }

        /**
         * Set Item Category
         *
         * @return string
         */
        private function setCategory()
        {
            if(in_array($this->version, [0, 1]))
                return '';
            return sprintf("%01X", $this->cat, 0);
        }

        /**
         * Set Item Option, Level, Skill, Luck
         *
         * @return string
         */
        private function setOptions()
        {
            $opt = 0;
            if($this->lvl > 0){
                $opt += $this->lvl * 8;
            }
            if($this->skill == true){
                $opt += 128;
            }
            if($this->luck == true){
                $opt += 4;
            }
            if($this->opt >= 4){
                $opt += $this->opt - 4;
            } else{
                $opt += $this->opt;
            }
            return sprintf("%02X", $opt, 00);
        }

        /**
         * Set Item Durability
         *
         * @return string
         */
        private function setDurability()
        {
            if($this->dur != false){
                $dur = $this->dur;
            } else{
                if(array_key_exists('dur', $this->item_data)){
                    $dur = ($this->cat == 5) ? $this->item_data['magdur'] : $this->item_data['dur'];
                    if(!in_array($this->id, [200, 201, 202, 203, 204, 205, 206, 207, 208, 209, 210, 211, 212, 213, 214, 221, 231, 241, 251, 261])){
                        if($this->lvl < 5){
                            $dur += $this->lvl;
                        } else{
                            switch($this->lvl){
                                default:
                                    $dur += ($this->lvl * 2) - 4;
                                    break;
                                case 10:
                                    $dur += ($this->lvl * 2) - 3;
                                    break;
                                case 11:
                                    $dur += ($this->lvl * 2) - 1;
                                    break;
                                case 12:
                                    $dur += ($this->lvl * 2) + 2;
                                    break;
                                case 13:
                                    $dur += ($this->lvl * 2) + 6;
                                    break;
                                case 14:
                                    $dur += ($this->lvl * 2) + 11;
                                    break;
                                case 15:
                                    $dur += ($this->lvl * 2) + 17;
                                    break;
                            }
                        }
                    }
                    if(!empty($this->exe)){
                        if($this->ancient == 0){
                            $dur += 15;
                        }
                    }
                    if($this->ancient > 0){
                        $dur += 20;
                    }
                    if($dur > 255){
                        $dur = 255;
                    }
                } else{
                    $dur = 0;
                }
            }
            return sprintf("%02X", $dur, 00);
        }

        /**
         * Set Item Serial
         *
         * @return string
         */
        public function setSerial()
        {
            if($this->serials_from_hex == true){
                if(isset($this->hex_serial[0])){
                    return $this->hex_serial[0];
                } else{
                    throw new Exception('setSerial(): serial not defined.');
                }
            } else{
                if($this->serial2 != false){
                    return sprintf("%08X", 0, 00000000);
                }
                return sprintf("%08X", $this->serial, 00000000);
            }
        }

        /**
         * Set Item Serial (IGCN)
         *
         * @return string
         */
        private function setSerial2()
        {
            if($this->serials_from_hex == true){
                if(isset($this->hex_serial[1])){
                    return $this->hex_serial[1];
                } else{
                    return '';
                }
            } else{
                if($this->serial2 != false){
                    return sprintf("%08X", $this->serial, 00000000);
                }
                return '';
            }
        }

        /**
         * Set Item Excellent Options
         *
         * @return string
         */
        private function setExe()
        {
            if($this->is_socket_exe){
                if($this->opt >= 4){
                    $exe += 64;
                }
            } else{
                $exe = 0;
                if(in_array($this->version, [0, 1]) && (int)(($this->cat * 32) > 255)){
                    $exe += 128;
                }
                if($this->opt >= 4){
                    $exe += 64;
                }
                foreach($this->exe as $exe_opt){
                    if($this->version >= 5 && in_array($exe_opt, [7, 8, 9, 10, 11])){
                        $this->exe_in_socket_slot[] = $this->exe_opts[$exe_opt];
                    } else{
                        $exe += $this->exe_opts[$exe_opt];
                    }
                }
                if($this->fenrir != 0){
                    $exe = $this->fenrir_opts[$this->fenrir];
                }
                if($this->version >= 3 && $this->id > 255){
                    $exe += 128;
                }
            }
            return sprintf("%02X", $exe, 00);
        }

        /**
         * Set Item Ancient Options
         *
         * @return string
         */
        private function setAncient()
        {
            if($this->version == 0)
                $anc_data = 0; else{
                if($this->is_socket_ancient)
                    $anc_data = $this->ancient; else
                    $anc_data = $this->ancient_opts[$this->ancient];
            }
            return sprintf("%02X", $anc_data, 00);
        }

        /**
         * Set Item Refinery Options
         *
         * @return string
         */
        private function setRefinery()
        {
            if(in_array($this->version, [0, 1]))
                $refinery_data = '00'; else
                $refinery_data = ($this->ref == true) ? 8 : 0;
            return $refinery_data;
        }

        /**
         * Set Item Harmony Options
         *
         * @return string
         */
        private function setHarmony()
        {
            if(in_array($this->version, [0, 1]))
                return ''; else{
                if($this->version >= 4 && $this->element_type != false && !empty($this->element_ranks)){
                    return $this->setElementOnHarmony();
                }
            }
            return (!empty($this->harmony) && count($this->harmony) > 0) ? sprintf("%01X", $this->harmony[0], 0) . sprintf("%01X", $this->harmony[1], 0) : sprintf("%02X", 0, 00);
        }

        /**
         * Set Elemental Item Options since Season 8
         *
         * @return string
         */
        private function setElementOnHarmony()
        {
            if($this->element_ranks['rank1'] != 0)
                $this->elementCount(1);
            if($this->element_ranks['rank2'] != 0)
                $this->elementCount(1);
            if($this->element_ranks['rank3'] != 0)
                $this->elementCount(1);
            if($this->element_ranks['rank4'] != 0)
                $this->elementCount(1);
            if($this->element_ranks['rank5'] != 0)
                $this->elementCount(1);
            return sprintf("%01X", $this->element_count, 0) . sprintf("%01X", $this->element_type, 0);
        }

        /**
         * Set Item Socket Options / Can be Elemental system options since Season 8 / Can be Excellent options since Season 10
         *
         * @return string
         */
        private function setSocket()
        {
            if(in_array($this->version, [0, 1]))
                return ''; else{
                $this->sockets = [$this->no_socket, $this->no_socket, $this->no_socket, $this->no_socket, $this->no_socket];
                if($this->version >= 4 && $this->cat == 12 && in_array($this->id, $this->errtel_ids)){
                    for($i = 0; $i <= 4; $i++){
                        if($this->element_ranks['rank' . ($i + 1)] != 0){
                            $this->sockets[$i] = $this->element_ranks['rank' . ($i + 1)];
                            $this->sockets[$i] += $this->element_levels['rank' . ($i + 1)] * 16;
                        }
                    }
                } else if($this->version >= 4 && $this->cat == 12 && in_array($this->id, $this->pentagram_ids)){
                    for($i = 0; $i <= 4; $i++){
                        if($this->element_ranks['rank' . ($i + 1)] != 0){
                            $this->sockets[$i] = $this->empty_socket;
                        }
                    }
                } else{
                    if($this->version >= 5 && !empty($this->exe_in_socket_slot)){
                        for($i = 0; $i <= 4; $i++){
                            if(isset($this->exe_in_socket_slot[$i])){
                                $this->sockets[$i] = $this->exe_in_socket_slot[$i];
                            } else{
                                $this->sockets[$i] = $this->no_socket;
                            }
                        }
                    } else{
                        if(!empty($this->socket)){
                            for($i = 0; $i <= 4; $i++){
                                if(isset($this->socket[$i]) && $this->socket[$i] !== ''){
                                    if($this->socket[$i] == 254){
                                        $this->sockets[$i] = $this->empty_socket;
                                    } else{
                                        $this->sockets[$i] = ($this->socket_type == 1) ? $this->socket[$i] : $this->socket[$i] + 1;
                                    }
                                } else{
                                    $this->sockets[$i] = $this->no_socket;
                                }
                            }
                        }
                    }
                }
                return sprintf("%02X%02X%02X%02X%02X", $this->sockets[0], $this->sockets[1], $this->sockets[2], $this->sockets[3], $this->sockets[4]);
            }
        }

        /**
         * Set empty hex string used on IGCN Server Files idk why :)
         *
         * @return string
         */
        private function setEmpty()
        {
            if($this->serials_from_hex == true){
                if(isset($this->hex_serial[1])){
                    return str_repeat('F', 24);
                }
            } else{
                if($this->serial2 != false){
                    return str_repeat('F', 24);
                }
            }
            return '';
        }

        /**
         * Generate item hex String
         *
         * @return hex string
         */
        public function to_hex()
        {
            return $this->setId() . $this->setOptions() . $this->setDurability() . $this->setSerial() . $this->setExe() . $this->setAncient() . $this->setCategory() . $this->setRefinery() . $this->setHarmony() . $this->setSocket() . $this->setSerial2() . $this->setEmpty();
        }
    }