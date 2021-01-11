<?php
    in_file();

    class npc extends library
    {
        private $npc_list;
        private $name = 'Unknown';
        private $id = -1;

        public function __construct()
        {
            $this->load->lib('serverfile');
            $this->npc_list = $this->serverfile->npc_names()->get('npc_names');
        }

        public function name_by_id($id)
        {
            foreach($this->npc_list AS $key => $npc){
                if(isset($npc['id']) && $npc['id'] == (int)$id){
                    $this->name = $npc['name'];
                    break;
                }
            }
            return $this->name;
        }

        public function id_by_name($name)
        {
            foreach($this->npc_list AS $key => $npc){
                if(isset($npc['name']) && $npc['name'] === $name){
                    $this->id = $npc['id'];
                    break;
                }
            }
            return $this->id;
        }
    }
	