<?php

    class RemoveNotVerifiedAccounts extends Job
    {
        private $registry, $config, $load, $vars = [];

        public function __construct()
        {
            $this->registry = controller::get_instance();
            $this->config = $this->registry->config;
            $this->load = $this->registry->load;
        }

        public function execute()
        {
            $this->load->helper('website');
            foreach($this->registry->website->server_list() AS $key => $val){
                $this->registry->website->db('account', $key)->query('DELETE FROM MEMB_INFO WHERE activated = 0 AND appl_days < DATEADD(DAY, -15, GETDATE())');
            }
        }
    }