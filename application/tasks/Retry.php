<?php

    class Retry extends Job
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
            if($this->registry->license->check_local_license()){
                $this->registry->website->set_cache('license_information', $this->registry->license->get_local_license_data(), (3600 * 24) * 14);
                $this->remove_cron_task('Retry');
            }
            return true;
        }

        private function remove_cron_task($task)
        {
            $file = BASEDIR . 'application' . DS . 'config' . DS . 'scheduler_config.json';
            $data = file_get_contents($file);
            $tasks = json_decode($data, true);
            if(array_key_exists($task, $tasks['tasks'])){
                unset($tasks['tasks'][$task]);
            }
            file_put_contents($file, json_encode($tasks));
        }
    }