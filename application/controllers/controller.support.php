<?php
    in_file();

    class support extends controller
    {
        protected $vars = [], $errors = [];

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('website');
            $this->load->lib('session', ['DmNCMS']);
            $this->load->lib('upload');
            $this->load->model('account');
            $this->load->model('character');
            $this->load->model('support');
            $this->load->helper('breadcrumbs', [$this->request]);
            $this->load->helper('meta');
            if($this->session->userdata(['user' => 'server'])){
                $this->load->lib(['account_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']), true)]);
                $this->load->lib(['game_db', 'db'], [HOST, USER, PASS, $this->website->get_db_from_server($this->session->userdata(['user' => 'server']))]);
            }
        }

        public function index()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if(isset($_POST['submit_ticket'])){
                    $this->upload->out_file_dir = BASEDIR . 'assets' . DS . 'uploads' . DS . 'attachment';
                    $this->upload->max_file_size = 2 * (1024 * 1024);
                    $this->upload->make_script_safe = 1;
                    $this->upload->allowed_file_ext = ['gif', 'jpeg', 'jpg', 'jpe', 'png', 'txt', 'pdf'];
                    $subject = isset($_POST['title']) ? $_POST['title'] : '';
                    $character = isset($_POST['character']) ? $_POST['character'] : '';
                    $department = isset($_POST['department']) ? $_POST['department'] : '';
                    $priority = isset($_POST['priority']) ? $_POST['priority'] : '';
                    $text = isset($_POST['text']) ? $_POST['text'] : '';
                    $files = (isset($_FILES['files']) && !empty($_FILES['files'])) ? $_FILES['files'] : false;
                    $file_names = [];
                    if($subject == ''){
                        $this->vars['error'] = __('Please enter ticket subject.');
                    } else{
                        if($department == '' || !is_numeric($department)){
                            $this->vars['error'] = __('Please select ticket department.');
                        } else{
                            $pay_per_incident = $this->Msupport->check_department_payment($department);
                            if($pay_per_incident != false){
                                if($pay_per_incident['pay_per_incident'] != 0){
                                    $status = $this->Maccount->get_amount_of_credits($this->session->userdata(['user' => 'username']), $pay_per_incident['payment_type'], $this->session->userdata(['user' => 'server']), $this->session->userdata(['user' => 'id']));
                                    if($status < $pay_per_incident['pay_per_incident']){
                                        $this->vars['error'] = vsprintf(__('This department requires payment of %d %s'), [$pay_per_incident['pay_per_incident'], $this->website->translate_credits($pay_per_incident['payment_type'], $this->session->userdata(['user' => 'server']))]);
                                    }
                                }
                            }
                            if($priority == ''){
                                $this->vars['error'] = __('Please select ticket priority.');
                            } else{
                                if($text == ''){
                                    $this->vars['error'] = __('Please enter ticket text.');
                                } else{
                                    if($character == '')
                                        $this->vars['error'] = __('Please select character'); else{
                                        if(!$this->Mcharacter->check_char($character))
                                            $this->vars['error'] = __('Character not found.'); else{
                                            if($files != false){
                                                if(count($files['name']) > 3){
                                                    $this->vars['error'] = __('Max 3 files allowed');
                                                } else{
                                                    if($files['name'][0] != ''){
                                                        $files = $this->Msupport->reArrayFiles($files);
                                                        foreach($files AS $file){
                                                            $this->upload->out_file_name = $this->session->userdata(['user' => 'id']) . '-' . $this->session->userdata(['user' => 'server']) . '-' . str_replace(['.', ' '], '-', microtime());
                                                            $this->upload->process($file);
                                                            if($this->upload->error_no){
                                                                switch($this->upload->error_no){
                                                                    case 1:
                                                                        $this->vars['error'] = __('No upload file specified.');
                                                                        break;
                                                                    case 2:
                                                                        $this->vars['error'] = __('Invalid file mime type allowed: gif, jpeg, jpg, jpe, png, txt, pdf');
                                                                        break;
                                                                    case 3:
                                                                        $this->vars['error'] = sprintf(__('One of files was too big allowed: %s'), $this->Msupport->human_filesize($this->upload->max_file_size));
                                                                        break;
                                                                    case 4:
                                                                        $this->vars['error'] = __('Upload failed.');
                                                                        break;
                                                                    case 5:
                                                                        $this->vars['error'] = __('Upload failed.');
                                                                        break;
                                                                    case 6:
                                                                        $this->vars['error'] = __('File upload disabled.');
                                                                        break;
                                                                }
                                                            }
                                                            $file_names[] = $this->upload->parsed_file_name;
                                                            if(isset($this->vars['error']) && count($file_names) > 0){
                                                                foreach($file_names AS $key => $fl){
                                                                    if(file_exists($this->upload->out_file_dir . DS . $fl)){
                                                                        @unlink($this->upload->out_file_dir . DS . $fl);
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            if($time = $this->Msupport->check_last_ticket_time()){
                                                if((time() - $time['create_time']) < 60 * 15){
                                                    $this->vars['error'] = __('You are not allowed to create more than one ticket per 15 minutes.');
                                                } else{
                                                    if(!isset($this->vars['error'])){
                                                        $id = $this->Msupport->create_ticket($subject, $character, $department, $priority, $text, $file_names);
                                                        if($pay_per_incident != false){
                                                            if($pay_per_incident['pay_per_incident'] != 0){
                                                                $this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $pay_per_incident['pay_per_incident'], $pay_per_incident['payment_type']);
                                                                $this->Maccount->add_account_log('Payment for support request ' . $this->website->translate_credits($pay_per_incident['payment_type'], $this->session->userdata(['user' => 'server'])), -$pay_per_incident['payment_type'], $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                                            }
                                                        }
														if($this->config->values('email_config', 'support_email_admin') == 1){
															$this->Msupport->sent_ticket_email_admin($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $this->session->userdata(['user' => 'email']), $this->config->values('email_config', 'server_email'), $subject, $id);
														}
                                                        $this->vars['success'] = __('Ticket was successfully submited.');
                                                    }
                                                }
                                            } else{
                                                if(!isset($this->vars['error'])){
                                                    $id = $this->Msupport->create_ticket($subject, $character, $department, $priority, $text, $file_names);
                                                    if($pay_per_incident != false){
                                                        if($pay_per_incident['pay_per_incident'] != 0){
                                                            $this->website->charge_credits($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $pay_per_incident['pay_per_incident'], $pay_per_incident['payment_type']);
                                                            $this->Maccount->add_account_log('Payment for support request ' . $this->website->translate_credits($pay_per_incident['payment_type'], $this->session->userdata(['user' => 'server'])), -$pay_per_incident['payment_type'], $this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']));
                                                        }
                                                    }
													if($this->config->values('email_config', 'support_email_admin') == 1){
														$this->Msupport->sent_ticket_email_admin($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $this->session->userdata(['user' => 'email']), $this->config->values('email_config', 'server_email'), $subject, $id);
													}
                                                    $this->vars['success'] = __('Ticket was successfully submited.');
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $this->vars['css'] = '<link rel="stylesheet" href="' . $this->config->base_url . 'assets/' . $this->config->config_entry("main|template") . '/css/jquery.cleditor.css" type="text/css" />';
                $this->vars['js'] = '<script src="' . $this->config->base_url . 'assets/' . $this->config->config_entry("main|template") . '/js/jquery.cleditor.min.js"></script>';
                $this->vars['char_list'] = $this->Mcharacter->load_char_list();
                $this->vars['department_list'] = $this->Msupport->load_department_list();
                $this->vars['priority_list'] = $this->Msupport->generate_priority(1, true);
                $this->load->view($this->config->config_entry('main|template') . DS . 'support' . DS . 'view.index', $this->vars);
            } else{
                $this->login();
            }
        }

        public function my_tickets()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $this->vars['ticket_list'] = $this->Msupport->load_my_ticket_list();
                $this->load->view($this->config->config_entry('main|template') . DS . 'support' . DS . 'view.my_tickets', $this->vars);
            } else{
                $this->login();
            }
        }

        public function read_ticket($id = '')
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if($id == ''){
                    $this->vars['error'] = __('Ticket not found.');
                } else{
                    if($this->vars['ticket_data'] = $this->Msupport->check_ticket($id)){
                        if(isset($_POST['submit_reply'])){
                            $text = isset($_POST['text']) ? $_POST['text'] : '';
                            if($text == ''){
                                $this->vars['errors'] = __('Please enter reply text.');
                            } else{
                                if($time = $this->Msupport->check_my_last_reply_time($id)){
                                    if((time() - $time['reply_time']) < 60 * 5){
                                        $this->vars['errors'] = __('You are not allowed reply more than once per 5 minutes.');
                                    } else{
                                        $this->add_reply($id, $text);
                                        $this->Msupport->log_reply_time($id);
                                    }
                                } else{
                                    $this->add_reply($id, $text);
                                    $this->Msupport->log_reply_time($id);
                                }
                            }
                        }
                        $this->vars['css'] = '<link rel="stylesheet" href="' . $this->config->base_url . 'assets/' . $this->config->config_entry("main|template") . '/css/jquery.cleditor.css" type="text/css" />';
                        $this->vars['js'] = '<script src="' . $this->config->base_url . 'assets/' . $this->config->config_entry("main|template") . '/js/jquery.cleditor.min.js"></script>';
                        $this->vars['ticket_replies'] = $this->Msupport->load_ticket_replies($id);
                    } else{
                        $this->vars['error'] = __('Ticket not found.');
                    }
                }
                $this->load->view($this->config->config_entry('main|template') . DS . 'support' . DS . 'view.read_ticket', $this->vars);
            } else{
                $this->login();
            }
        }

        private function add_reply($id, $text)
        {
			
            if($this->Msupport->add_reply($id, $text)){
                if($this->Msupport->set_replied_by_user($id)){
					if($this->config->values('email_config', 'support_email_admin') == 1){
						$ticket = $this->Msupport->check_ticket($id);
						$this->Msupport->sent_ticket_reply_email_admin($this->session->userdata(['user' => 'username']), $this->session->userdata(['user' => 'server']), $this->session->userdata(['user' => 'email']), $this->config->values('email_config', 'server_email'), $ticket['subject'], $id);
					}
                    $this->vars['success'] = __('You have successfully replied your ticket.');
                }
            }
        }

        public function mark_resolved()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                if(isset($_POST['id'])){
                    $id = $_POST['id'];
                    if($data = $this->Msupport->check_ticket($id)){
                        if($data['status'] == 3){
                            json(['error' => __('This ticket is already resolved.')]);
                        } else{
                            $this->Msupport->resolve_ticket($id);
                            $this->Msupport->set_replied_by_admin_and_user($id);
                            json(['success' => __('Ticket resolved successfully.')]);
                        }
                    } else{
                        json(['error' => __('Ticket not found.')]);
                    }
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function check_unreplied_tickets()
        {
            if($this->session->userdata(['user' => 'logged_in'])){
                $tickets = $this->Msupport->check_unreplied_tickets();
                if(!empty($tickets)){
                    json(['success' => vsprintf(__('You have %d new ticket replies. <a href="%s">Click To View</a>'), [count($tickets), $this->config->base_url . 'support/my-tickets'])]);
                }
            } else{
                json(['error' => __('Please login into website.')]);
            }
        }

        public function login()
        {
            $this->load->view($this->config->config_entry('main|template') . DS . 'support' . DS . 'view.login');
        }
    }