<?php

    class ParseMMOTOPVotes extends Job
    {
        private $registry, $config, $load;

        public function __construct()
        {
            $this->registry = controller::get_instance();
            $this->config = $this->registry->config;
            $this->load = $this->registry->load;
        }

        public function execute()
        {
            $this->load->helper('website');
            $this->load->model('account');
            $votelinks = $this->load_mmotop_vote_links();
            if(!empty($votelinks)){
                foreach($votelinks as $links){
                    if(($stats = $this->registry->Maccount->check_mmotop_stats($links['mmotop_stats_url'], $links['server'])) != false){
                        $this->registry->Maccount->insert_mmotop_stats($stats, $links['server']);
                    }
                    $this->registry->Maccount->check_mmotop_voters([$links['reward'], $links['mmotop_reward_sms']], $links['reward_type'], $links['server']);
                }
            }
        }

        private function load_mmotop_vote_links()
        {
            return $this->registry->website->db('web')->query('SELECT id, votelink, name, img_url, hours, reward, reward_type, mmotop_stats_url, mmotop_reward_sms, api, server FROM DmN_Votereward WHERE api = 2 ORDER BY id')->fetch_all();
        }
    }