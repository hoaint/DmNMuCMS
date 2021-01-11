<?php

    class VoteMonthlyReward extends Job
    {
        private $registry, $config, $load, $vote_config, $last_month, $reward, $year, $formula = '';

        public function __construct()
        {
            $this->registry = controller::get_instance();
            $this->config = $this->registry->config;
            $this->load = $this->registry->load;
            $this->vote_config = $this->config->values('votereward_config');
        }

        public function execute()
        {
            $this->load->helper('website');
            $this->load->model('account');
            foreach($this->vote_config AS $key => $data){
                if($key != 'api_key'){
                    if($data['is_monthly_reward'] == 1 && $data['amount_of_players_to_reward'] > 0 && $data['reward_formula'] != ''){
                        $this->year = date('Y');
                        $this->last_month = date('F', strtotime(date('F') . " last month"));
                        if($this->last_month == 'December')
                            $this->year = date('Y', strtotime($this->year . " last year"));
                        $list = $this->registry->website->db('web')->query('SELECT TOP ' . (int)$data['amount_of_players_to_reward'] . ' account, totalvotes FROM DmN_Votereward_Ranking WHERE server = \'' . $key . '\' AND year = ' . $this->year . ' AND month = \'' . $this->last_month . '\' ORDER BY totalvotes DESC, lastvote ASC')->fetch_all();
                        if(!empty($list)){
                            $i = 0;
                            foreach($list AS $players){
                                $i++;
                                $this->reward = (int)floor(arithmetic(str_replace(['{position}', '{totalvotes}'], [$i, $players['totalvotes']], $data['reward_formula'])));
                                $this->registry->website->add_credits($players['account'], $key, $this->reward, $data['reward_type']);
                                $this->registry->Maccount->add_account_log('VoteReward  ' . $this->last_month . ', ' . $this->year . ' reward ' . $this->registry->website->translate_credits($data['reward_type'], $key), +$this->reward, $players['account'], $key);
                            }
                        }
                    }
                }
            }
        }
    }