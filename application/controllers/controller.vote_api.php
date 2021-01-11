<?php
    in_file();

    class vote_api extends controller
    {
        protected $vars = [], $errors = [];

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('website');
            $this->load->model('account');
        }

        public function index()
        {
            throw new exception('Nothing to see here!');
        }

        public function xtremetop($key = '')
        {
            if($this->checkApiKey($key, 'Xtremetop100')){
                if(isset($_GET['custom'], $_GET['votingip'])){
                    if($_GET['custom'] != ''){
                        if($this->Maccount->add_xtremetop_vote($_GET['custom'], $_GET['votingip'])){
                            echo 'VOTE LOGGED';
                        } else{
                            writelog('Xtremetop100 error - unable to log vote custom: ' . $_GET['custom'] . ', ip: ' . $_GET['votingip'], 'vote_api');
                        }
                    } else{
                        writelog('Xtremetop100 error - variable custom is empty, ip: ' . $_GET['votingip'], 'vote_api');
                    }
                } else{
                    writelog('Xtremetop100 error - missing some voting variable', 'vote_api');
                }
            }
        }

        public function ultratop($key = '')
        {
            if($this->checkApiKey($key, 'UltraTop100')){
                if(isset($_GET['custom'], $_GET['votingip'])){
                    if($_GET['custom'] != ''){
                        if($this->Maccount->add_ultratop_vote($_GET['custom'], $_GET['votingip'])){
                            echo 'VOTE LOGGED';
                        } else{
                            writelog('UltraTop100 error - unable to log vote custom: ' . $_GET['custom'] . ', ip: ' . $_GET['votingip'], 'vote_api');
                        }
                    } else{
                        writelog('UltraTop100 error - variable custom is empty, ip: ' . $_GET['votingip'], 'vote_api');
                    }
                } else{
                    writelog('UltraTop100 error - missing some voting variable', 'vote_api');
                }
            }
        }

        public function gametop100($key = '')
        {
            if($this->checkApiKey($key, 'GameTop100')){
                if(isset($_POST['custom'], $_POST['ip'])){
                    if($_POST['custom'] != ''){
                        if($this->Maccount->add_gametop100_vote($_POST['custom'], $_POST['ip'])){
                            echo 'VOTE LOGGED';
                        } else{
                            writelog('GameTop100 error - unable to log vote custom: ' . $_POST['custom'] . ', ip: ' . $_POST['ip'], 'vote_api');
                        }
                    } else{
                        writelog('GameTop100 error - variable custom is empty, ip: ' . $_POST['ip'], 'vote_api');
                    }
                } else{
                    writelog('GameTop100 error - missing some voting variable', 'vote_api');
                }
            }
        }

        public function gtop100($key = '')
        {
            if($this->checkApiKey($key, 'Gtop100')){
                if(isset($_POST['Successful']) && abs($_POST['Successful']) == 0){
                    if(isset($_POST['pingUsername'], $_POST['VoterIP'])){
                        if($this->Maccount->add_gtop100_vote($_POST['pingUsername'], $_POST['VoterIP'])){
                            echo 'VOTE LOGGED';
                        } else{
                            writelog('Gtop100 error - unable to log vote', 'vote_api');
                        }
                    } else{
                        writelog('Gtop100 error - missing some voting variable', 'vote_api');
                    }
                } else{
                    writelog('Gtop100 error - vote was not successfull reason: ' . $_POST['Reason'], 'vote_api');
                }
            }
        }

        public function topg($key = '')
        {
            if($this->checkApiKey($key, 'Topg')){
                if(isset($_GET['p_resp'], $_GET['ip'])){
                    if($this->Maccount->add_topg_vote($_GET['p_resp'], $_GET['ip'])){
                        echo 'VOTE LOGGED';
                    } else{
                        writelog('Topg error - unable to log vote', 'vote_api');
                    }
                } else{
                    writelog('Topg error - missing some voting variable', 'vote_api');
                }
            }
        }

        public function top100arena($key = '')
        {
            if($this->checkApiKey($key, 'Top100arena')){
                if(isset($_GET['postback'])){
                    if($this->Maccount->add_top100arena_vote($_GET['postback'])){
                        echo 'VOTE LOGGED';
                    } else{
                        writelog('Top100arena error - unable to log vote', 'vote_api');
                    }
                } else{
                    writelog('Top100arena error - missing some voting variable', 'vote_api');
                }
            }
        }

        public function mmoserver($key = '')
        {
            if($this->checkApiKey($key, 'Mmoserver')){
                if(isset($_POST['mod']) && $_POST['mod'] == 'reward'){
                    if($this->Maccount->add_mmoserver_vote($_POST['user'])){
                        echo 'OK';
                    } else{
                        writelog('Mmoserver error - unable to log vote', 'vote_api');
                    }
                } else{
                    writelog('Mmoserver error - missing some voting variable', 'vote_api');
                }
            }
        }

        private function checkApiKey($key = '', $site = '')
        {
            $vote_site = ($site != '') ? $site : 'Undefined';
            if($key == ''){
                writelog($vote_site . ' error - empty api key', 'vote_api');
            } else{
                $this->vars['config'] = $this->config->values('votereward_config');
                if($this->vars['config'] != false){
                    if(isset($this->vars['config']['api_key'])){
                        if($this->vars['config']['api_key'] == $key){
                            return true;
                        } else{
                            writelog($vote_site . ' error - api keys does not match.', 'vote_api');
                        }
                    } else{
                        writelog($vote_site . ' error - api key in votereward configuration not definded.', 'vote_api');
                    }
                } else{
                    writelog($vote_site . ' error - votereward configuration not found. ', 'vote_api');
                }
            }
            header("HTTP/1.1 403 Forbidden");
            return false;
        }
    }