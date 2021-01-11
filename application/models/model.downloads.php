<?php
    in_file();

    class Mdownloads extends model
    {
        protected $downloads = [];

        public function __contruct()
        {
            parent::__construct();
        }

        public function load_downloads($type = 0)
        {
            $stmt = $this->website->db('web')->prepare('SELECT id, link_name, link_desc, link_size, link_type, link_url FROM DmN_Downloads  WHERE type = :type ORDER BY orders ASC');
            $stmt->execute([':type' => $type]);
            return $stmt->fetch_all();
        }
    }