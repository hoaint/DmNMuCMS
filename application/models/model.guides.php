<?php
    in_file();

    class Mguides extends model
    {
        protected $guides = [];

        public function __contruct()
        {
            parent::__construct();
        }

        public function load_guides()
        {
            return $this->website->db('web')->query('SELECT id, title FROM DmN_Guides WHERE lang = \'' . $this->website->db('web')->sanitize_var($this->config->language()) . '\' ORDER BY date DESC')->fetch_all();
        }

        public function load_guide_by_id($id)
        {
            $stmt = $this->website->db('web')->prepare('SELECT id, title, text, date FROM DmN_Guides WHERE id = :id');
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        }
    }