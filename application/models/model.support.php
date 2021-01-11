<?php
    in_file();

    class Msupport extends model
    {
        public $error = false, $vars = [];
        protected $replies = [], $times = [], $pos = 1;

        public function __contruct()
        {
            parent::__construct();
        }

        public function __set($key, $val)
        {
            $this->vars[$key] = $val;
        }

        public function __isset($name)
        {
            return isset($this->vars[$name]);
        }

        public function load_department_list()
        {
            $stmt = $this->website->db('web')->prepare('SELECT id, department_name, pay_per_incident, payment_type FROM DmN_Support_Departments WHERE server = :server AND is_active = 1 ORDER BY id DESC');
            $stmt->execute([':server' => $this->session->userdata(['user' => 'server'])]);
            return $stmt->fetch_all();
        }

        public function get_department_name($id)
        {
            $stmt = $this->website->db('web')->prepare('SELECT department_name FROM DmN_Support_Departments WHERE id = :id');
            $stmt->execute([':id' => $id]);
            if($name = $stmt->fetch()){
                return $name['department_name'];
            }
            return 'Unknown';
        }

        public function check_department_payment($id)
        {
            $stmt = $this->website->db('web')->prepare('SELECT pay_per_incident, payment_type FROM DmN_Support_Departments WHERE id = :id');
            $stmt->execute([':id' => $id]);
            if($name = $stmt->fetch()){
                return $name;
            }
            return false;
        }

        public function generate_priority($pr = 1, $list = false, $style = false)
        {
            $priority = [1 => ['<div class="PriorityZero">' . __('Low') . '</div>', __('Low')], 2 => ['<div class="PriorityOne">' . __('Medium') . '</div>', __('Medium')], 3 => ['<div class="PriorityTwo">' . __('High') . '</div>', __('High')], 4 => ['<div class="PriorityThree">' . __('Urgent') . '</div>', __('Urgent')],];
            if($list){
                return $priority;
            } else{
                if(array_key_exists($pr, $priority)){
                    return ($style == true) ? $priority[$pr][0] : $priority[$pr][1];
                } else{
                    return 'unknown';
                }
            }
        }

        public function create_ticket($subject, $character, $department, $priority, $text, $files)
        {
            $data = [':subject' => $subject, ':message' => $text, ':dept' => $department, ':prior' => $priority, ':time' => time(), ':account' => $this->session->userdata(['user' => 'username']), ':character' => $character, ':server' => $this->session->userdata(['user' => 'server'])];
            $sql = ['', ''];
            if(count($files) > 0){
                $data[':attachment'] = serialize($files);
                $sql = [', attachment', ', :attachment'];
            }
            $stmt = $this->website->db('web')->prepare('INSERT INTO DmN_Support_Tickets(subject, message, department, priority, create_time, creator_account, creator_character, server' . $sql[0] . ') VALUES (:subject, :message, :dept, :prior, :time, :account, :character, :server' . $sql[1] . ')');
            $stmt->execute($data);
			return $this->website->db('web')->last_insert_id();
        }

        public function check_last_ticket_time()
        {
            $stmt = $this->website->db('web')->prepare('SELECT TOP 1 create_time FROM DmN_Support_Tickets WHERE creator_account = :account AND server = :server ORDER BY create_time DESC');
            $stmt->execute([':account' => $this->session->userdata(['user' => 'username']), ':server' => $this->session->userdata(['user' => 'server'])]);
            return $stmt->fetch();
        }

        public function load_my_ticket_list()
        {
            $stmt = $this->website->db('web')->prepare('SELECT id, subject, department, create_time, status, replied_by_user FROM DmN_Support_Tickets WHERE creator_account = :account AND server = :server ORDER BY create_time DESC');
            $stmt->execute([':account' => $this->session->userdata(['user' => 'username']), ':server' => $this->session->userdata(['user' => 'server'])]);
            return $stmt->fetch_all();
        }

        public function get_last_reply_time($id)
        {
            $stmt = $this->website->db('web')->prepare('SELECT TOP 1 reply_time FROM DmN_Support_Replies WHERE ticket_id = :id ORDER BY reply_time DESC');
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        }

        public function readable_status($status)
        {
            switch($status){
                default:
                case 0:
                    $s = __('Open');
                    break;
                case 1:
                    $s = __('Closed');
                    break;
                case 2:
                    $s = __('Hold');
                    break;
                case 3:
                    $s = __('Resolved');
                    break;
                case 4:
                    $s = __('Spam');
                    break;
                case 5:
                    $s = __('Working');
                    break;
            }
            return $s;
        }

        public function check_ticket($id)
        {
            $stmt = $this->website->db('web')->prepare('SELECT TOP 1 id, subject, message, department, priority, create_time, status, creator_character, attachment FROM DmN_Support_Tickets WHERE id = :id AND creator_account = :account AND server = :server');
            $stmt->execute([':id' => $id, ':account' => $this->session->userdata(['user' => 'username']), ':server' => $this->session->userdata(['user' => 'server'])]);
            return $stmt->fetch();
        }

        public function resolve_ticket($id)
        {
            $stmt = $this->website->db('web')->prepare('UPDATE DmN_Support_Tickets SET status = 3 WHERE id = :id AND creator_account = :account AND server = :server');
            return $stmt->execute([':id' => $id, ':account' => $this->session->userdata(['user' => 'username']), ':server' => $this->session->userdata(['user' => 'server'])]);
        }

        public function check_unreplied_tickets()
        {
            $stmt = $this->website->db('web')->prepare('SELECT id FROM DmN_Support_Tickets WHERE creator_account = :account AND server = :server AND replied_by_user = 0');
            $stmt->execute([':account' => $this->session->userdata(['user' => 'username']), ':server' => $this->session->userdata(['user' => 'server'])]);
            return $stmt->fetch_all();
        }

        public function get_ticket_create_time($id)
        {
            $stmt = $this->website->db('web')->prepare('SELECT create_time FROM DmN_Support_Tickets WHERE id = :id');
            $stmt->execute([':id' => $id]);
            if($time = $stmt->fetch()){
                return $time['create_time'];
            }
            return 0;
        }

        public function load_ticket_replies($id)
        {
            $ticket_create_date = $this->get_ticket_create_time($id);
            $stmt = $this->website->db('web')->prepare('SELECT id, ticket_id, reply, reply_time, reply_by FROM DmN_Support_Replies WHERE ticket_id = :id ORDER BY reply_time ASC');
            $stmt->execute([':id' => $id]);
            $rows = $stmt->fetch_all();
            $count_rows = count($rows);
            foreach($rows AS $row){
                $this->times[$this->pos] = $row['reply_time'];
                $this->replies[] = ['reply' => $row['reply'], 'sender' => htmlspecialchars($row['reply_by']), 'create_time' => date('d/m/Y g:i a', $row['reply_time']), 'time_between' => ($this->pos == 1) ? $this->date_diff($ticket_create_date, $this->times[$this->pos]) : $this->date_diff($this->times[$this->pos - 1], $this->times[$this->pos])];
                $this->pos++;
            }
            return $this->replies;
        }

        public function check_my_last_reply_time($id)
        {
            $stmt = $this->website->db('web')->prepare('SELECT Top 1 reply_time FROM DmN_Support_Replies WHERE ticket_id = :id AND reply_by = :account ORDER BY reply_time DESC');
            $stmt->execute([':id' => $id, ':account' => $this->session->userdata(['user' => 'username'])]);
            return $stmt->fetch();
        }

        public function add_reply($id, $text)
        {
            $stmt = $this->website->db('web')->prepare('INSERT INTO DmN_Support_Replies(ticket_id, reply, reply_time, reply_by) VALUES (:id, :reply, :time, :account)');
            return $stmt->execute([':id' => $id, ':reply' => $text, ':time' => time(), ':account' => $this->session->userdata(['user' => 'username'])]);
        }

        public function log_reply_time($id)
        {
            $stmt = $this->website->db('web')->prepare('UPDATE DmN_Support_Tickets SET last_reply_time = :time WHERE id = :id');
            return $stmt->execute([':time' => time(), ':id' => $id]);
        }

        public function set_replied_by_user($id)
        {
            $stmt = $this->website->db('web')->prepare('UPDATE DmN_Support_Tickets SET replied_by_admin = 0, replied_by_user = 1 WHERE id = :id');
            return $stmt->execute([':id' => $id]);
        }

        public function set_replied_by_admin_and_user($id)
        {
            $stmt = $this->website->db('web')->prepare('UPDATE DmN_Support_Tickets SET replied_by_admin = 1, replied_by_user = 1 WHERE id = :id');
            return $stmt->execute([':id' => $id]);
        }

        public function date_diff($start_date, $end_date)
        {
            $diff = $end_date - $start_date;
            $seconds = 0;
            $hours = 0;
            $minutes = 0;
            if($diff % 86400 <= 0)
                $days = $diff / 86400;
            if($diff % 86400 > 0){
                $rest = ($diff % 86400);
                $days = ($diff - $rest) / 86400;
                if($rest % 3600 > 0){
                    $rest1 = ($rest % 3600);
                    $hours = ($rest - $rest1) / 3600;
                    if($rest1 % 60 > 0){
                        $rest2 = ($rest1 % 60);
                        $minutes = ($rest1 - $rest2) / 60;
                        $seconds = $rest2;
                    } else
                        $minutes = $rest1 / 60;
                } else
                    $hours = $rest / 3600;
            }
            $days = ($days > 0) ? (($days == 1) ? $days . ' day, ' : $days . ' days, ') : '';
            $hours = ($hours > 0) ? ($hours == 1 ? $hours . ' hour, ' : $hours . ' hours, ') : '';
            if($minutes > 0){
                $minutes = ($minutes == 1) ? $minutes . ' minute' : $minutes . ' minutes';
            } else
                $minutes = false;
            $seconds = $seconds . ' seconds';
            return $days . ' ' . $hours . ' ' . $minutes . ' ' . $seconds;
        }

        public function human_filesize($bytes, $decimals = 2)
        {
            $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
            $factor = floor((strlen($bytes) - 1) / 3);
            return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
        }

        public function reArrayFiles(&$files)
        {
            $file_array = [];
            $file_count = count($files['name']);
            $file_keys = array_keys($files);
            for($i = 0; $i < $file_count; $i++){
                foreach($file_keys as $key){
                    $file_array[$i][$key] = $files[$key][$i];
                }
            }
            return $file_array;
        }
		
		public function sent_ticket_email_admin($user, $server, $uemail, $aemail, $subject, $id)
        {
            $body = @file_get_contents(APP_PATH . DS . 'data' . DS . 'email_patterns' . DS . 'support_email_admin_pattern.html');
            $body = str_replace('###USERNAME###', $user, $body);
            $body = str_replace('###SERVERNAME###', $this->config->config_entry('main|servername'), $body);
            $body = str_replace('###LINK###', $this->config->base_url, $body);
			$body = str_replace('###TICKET_LINK###', $this->config->base_url.'admincp/view-request/'.$id, $body);
            $body = str_replace('###SUBJECT###', $subject, $body);

            $this->sendmail($aemail, 'New Support ticket created.', $body, [$uemail => $user]);
            if($this->error == false){
                return true;
            } else{
                return false;
            }
        }
		
		public function sent_ticket_reply_email_admin($user, $server, $uemail, $aemail, $subject, $id)
        {
            $body = @file_get_contents(APP_PATH . DS . 'data' . DS . 'email_patterns' . DS . 'support_email_reply_admin_pattern.html');
            $body = str_replace('###USERNAME###', $user, $body);
            $body = str_replace('###SERVERNAME###', $this->config->config_entry('main|servername'), $body);
            $body = str_replace('###LINK###', $this->config->base_url, $body);
			$body = str_replace('###TICKET_LINK###', $this->config->base_url.'admincp/view-request/'.$id, $body);
            $body = str_replace('###SUBJECT###', $subject, $body);

            $this->sendmail($aemail, 'New reply on '.$subject.'.', $body, [$uemail => $user]);
            if($this->error == false){
                return true;
            } else{
                return false;
            }
        }

        public function sendmail($recipients, $subject, $message, $from = [])
        {
            $this->vars['email_config'] = $this->config->values('email_config');
            if(!$this->vars['email_config'])
                throw new Exception('Email settings not configured.');
            if(!isset($this->vars['email_config']['server_email']) || $this->vars['email_config']['server_email'] == '')
                throw new Exception('Server email is not set.');
			
			if(!empty($from)){
				$ff = $from;
			}
			else{
				$ff = [$this->vars['email_config']['server_email'] => $this->config->config_entry('main|servername')];
			}
			
            switch($this->vars['email_config']['mail_mode']){
                case 0:
                    try{
                        if(!isset($this->vars['email_config']['smtp_server']) || $this->vars['email_config']['smtp_server'] == '')
                            throw new Exception('SMTP Server is not set.');
                        if(!isset($this->vars['email_config']['smtp_port']) || $this->vars['email_config']['smtp_port'] == '' || !is_numeric($this->vars['email_config']['smtp_port']))
                            throw new Exception('SMTP Port is not set.');
                        $transport = Swift_SmtpTransport::newInstance($this->vars['email_config']['smtp_server'], (int)$this->vars['email_config']['smtp_port']);
                        if($this->vars['email_config']['smtp_use_ssl'] == 1){
                            $transport->setEncryption('ssl');
                        }
                        if($this->vars['email_config']['smtp_use_ssl'] == 2){
                            $transport->setEncryption('tls');
                        }
                        if($this->vars['email_config']['smtp_username'] != ''){
                            $transport->setUsername($this->vars['email_config']['smtp_username']);
                        }
                        if($this->vars['email_config']['smtp_password'] != ''){
                            $transport->setPassword($this->vars['email_config']['smtp_password']);
                        }
                        $mailer = Swift_Mailer::newInstance($transport);
                        $message = Swift_Message::newInstance()->setSubject($subject)->setFrom($ff)->setTo([$recipients])->setBody($message)->setContentType('text/html');
                        if(!$mailer->send($message, $failures)){
                            $this->error = 'Failed sending email to ' . print_r($failures, 1);
                            return false;
                        }
                        return true;
                    } catch(Exception $e){
                        $this->error = $e->getMessage();
                    } catch(Swift_ConnectionException $e){
                        $this->error = 'There was a problem communicating with the SMTP-Server. Error-Text: ' . $e->getMessage();
                    } catch(Swift_Message_MimeException $e){
                        $this->error = 'There was an unexpected problem building the email. Error-Text: ' . $e->getMessage();
                    } catch(Swift_TransportException $e){
                        $this->error = $e->getMessage();
                    }
                    break;
                case 1:
                    try{
                        $transport = Swift_MailTransport::newInstance();
                        $mailer = Swift_Mailer::newInstance($transport);
                        $message = Swift_Message::newInstance()->setSubject($subject)->setFrom($ff)->setTo([$recipients])->setBody($message)->setContentType('text/html');
                        if(!$mailer->send($message, $failures)){
                            $this->error = 'Failed sending email to ' . print_r($failures, 1);
                            return false;
                        }
                        return true;
                    } catch(Swift_ConnectionException $e){
                        $this->error = 'There was a problem communicating with the SMTP-Server. Error-Text: ' . $e->getMessage();
                    } catch(Swift_Message_MimeException $e){
                        $this->error = 'There was an unexpected problem building the email. Error-Text: ' . $e->getMessage();
                    } catch(Swift_TransportException $e){
                        $this->error = $e->getMessage();
                    }
                    break;
                case 2:
                    try{
                        $transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
                        $mailer = Swift_Mailer::newInstance($transport);
                        $message = Swift_Message::newInstance()->setSubject($subject)->setFrom($ff)->setTo([$recipients])->setBody($message)->setContentType('text/html');
                        if(!$mailer->send($message, $failures)){
                            $this->error = 'Failed sending email to ' . print_r($failures, 1);
                            return false;
                        }
                        return true;
                    } catch(Swift_ConnectionException $e){
                        $this->error = 'There was a problem communicating with the SMTP-Server. Error-Text: ' . $e->getMessage();
                    } catch(Swift_Message_MimeException $e){
                        $this->error = 'There was an unexpected problem building the email. Error-Text: ' . $e->getMessage();
                    } catch(Swift_TransportException $e){
                        $this->error = $e->getMessage();
                    }
                    break;
                case 3:
                    try{
                        $transport = SwiftSparkPost\Transport::newInstance($this->vars['email_config']['smtp_password']);
                        $mailer = Swift_Mailer::newInstance($transport);
						
                        $message = Swift_Message::newInstance()->setSubject($subject)->setFrom($ff)->setTo([$recipients])->setBody($message)->setContentType('text/html');
                        if(!$mailer->send($message, $failures)){
                            $this->error = 'Failed sending email to ' . print_r($failures, 1);
                            return false;
                        }
                        return true;
                    } catch(Swift_ConnectionException $e){
                        $this->error = 'There was a problem communicating with the SMTP-Server. Error-Text: ' . $e->getMessage();
                    } catch(Swift_Message_MimeException $e){
                        $this->error = 'There was an unexpected problem building the email. Error-Text: ' . $e->getMessage();
                    } catch(Swift_TransportException $e){
                        $this->error = $e->getMessage();
                    }
                    break;
            }
        }
    }