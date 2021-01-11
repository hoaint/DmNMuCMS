<?php

    class Mmonster_kill extends model
    {
        private $npc_filter = false;
		private $cache_name = '';
        private $c_npc = '';
		private $monsters = [];
		private $top = false;

        public function __contruct()
        {
            parent::__construct();
        }

        public function npc_filter($class)
        {
            $this->npc_filter = true;
            $this->c_npc = $class;
        }
		
		public function load_monster_rankings($server, $config)
        {
            $this->top = $config['top'];
            if($this->top == 0)
                return false;
            $this->check_cache('monster', 'monster', $server, $config['cache_time']);
            if(!$this->website->cached){
                $npc = ($this->npc_filter == true) ? 'WHERE MonsterId = ' . $this->website->db('game', $server)->sanitize_var($this->c_npc) . '' : '';
                $query = $this->website->db('game', $server)->query('SELECT TOP ' . $this->top . ' name, SUM([count]) AS count FROM C_Monster_KillCount ' . $npc . ' GROUP BY name ORDER BY SUM(count) DESC');
                if($query){
                    $i = 0;
                    while($row = $query->fetch()){
                        $this->monsters[] = [
							'name' => htmlspecialchars($row['name']), 
							'count' => $row['count']
						];
                        $i++;
                    }
                    if($i > 0){
                        $this->website->set_cache($this->cache_name, $this->monsters, $config['cache_time']);
                        return $this->monsters;
                    }
                }
                return false;
            }
            return $this->website->monster;
        }
		
		private function check_cache($name, $identifier, $server, $time = 360)
        {
            if($this->npc_filter == true){
                $this->cache_name = $name . '#' . $server . '#' . $this->c_npc . '#' . $this->top;
            } else{
                $this->cache_name = $name . '#' . $server . '#' . $this->top;
            }
            $this->website->check_cache($this->cache_name, $identifier, $time);
        }
    }
