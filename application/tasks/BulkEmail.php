<?php

    class BulkEmail extends Job
    {
        private $registry, $config, $load, $email_list, $max_recipients = 75;

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
            $this->send_mail();
        }

        private function send_mail()
        {
            $this->get_email_list();
            if(!empty($this->email_list)){
                $i = 0;
                $sent_to = 0;
                $failed = 0;
                $success = 0;
                $is_finished = 0;
                foreach($this->email_list AS $emails){
                    $recipient_list = unserialize($this->get_recipient_list_from_file($emails['seo_subject']));
                    $sending_started = $emails['sending_started'] != null ? $emails['sending_started'] : time();
                    $count_recipients = count($recipient_list);
                    foreach($recipient_list AS $key => $val){
                        $i++;
                        $count_recipients--;
                        $this->registry->Maccount->sendmail($val['mail_addr'], $emails['subject'], str_replace(['{memb___id}', '{server_name}', '{site_url}'], [$val['memb___id'], $this->config->config_entry('main|servername'), $this->config->base_url . '../'], $emails['body']));
                        if($this->registry->Maccount->error != false){
                            unset($recipient_list[$key]);
                            $failed += 1;
                            writelog($this->registry->Maccount->error, 'scheduler');
                        } else{
                            unset($recipient_list[$key]);
                            $success += 1;
                        }
                        if($count_recipients == 0){
                            $sending_finished = time();
                            $is_finished = 1;
                            $this->update_email_list($emails['id'], $recipient_list, $sending_started, $sending_finished, $success, $failed, $is_finished, $emails['seo_subject']);
                            break;
                        }
                        if($i == $this->max_recipients){
                            $sending_finished = time();
                            $this->update_email_list($emails['id'], $recipient_list, $sending_started, $sending_finished, $success, $failed, $is_finished, $emails['seo_subject']);
                            break;
                        }
                    }
                }
            }
        }

        private function get_email_list()
        {
            $this->email_list = $this->registry->website->db('web')->query('SELECT id, subject, body, sending_started, sending_finished, sent_to, failed, seo_subject FROM DmN_Bulk_Emails WHERE is_finished = 0 ORDER BY sending_started ASC, id ASC')->fetch_all();
        }

        private function update_email_list($id, $recipient_list, $sending_started, $sending_finished, $success, $failed, $is_finished, $seo_subject)
        {
            $this->update_recipient_list($recipient_list, $seo_subject);
            $stmt = $this->registry->website->db('web')->prepare('UPDATE DmN_Bulk_Emails SET sending_started = :sending_started, sending_finished = :sending_finished, sent_to = sent_to + :sent_to, failed = failed + :failed, is_finished = :is_finished WHERE id = :id');
            $stmt->execute([':sending_started' => $sending_started, ':sending_finished' => $sending_finished, ':sent_to' => $success, ':failed' => $failed, ':is_finished' => $is_finished, ':id' => $id]);
        }

        private function get_recipient_list_from_file($subject)
        {
            $file = APP_PATH . DS . 'data' . DS . 'bulk_email_recipient_list' . DS . $subject . '.txt';
            if(file_exists($file)){
                return file_get_contents($file);
            }
            return serialize([]);
        }

        private function update_recipient_list($recipient_list, $seo_subject)
        {
            $file = APP_PATH . DS . 'data' . DS . 'bulk_email_recipient_list' . DS . $seo_subject . '.txt';
            $add_recipient_list = @file_put_contents($file, serialize($recipient_list));
            if($add_recipient_list != false){
                return true;
            }
            return false;
        }
    }