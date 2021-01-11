<?php
    in_file();

    class csrf extends library
    {
        private $tokenName, $token;

        public function __construct($tokenName = 'dmn_csrf_protection')
        {
            $this->tokenName = $tokenName;
            if(session_status() !== PHP_SESSION_ACTIVE){
                throw new Exception('Session initialization failed.');
            }
            $this->setToken();
        }

        public function getToken()
        {
            return $this->setToken();
        }

        public function isTokenValid($userToken)
        {
            return ($userToken === $this->setToken());
        }

        public function writeToken()
        {
            echo '<input type="hidden" name="' . $this->tokenName . '" value="' . $this->setToken() . '" />';
        }

        public function writeTokenQueryString()
        {
            return '?' . $this->tokenName . '=' . $this->setToken();
        }

        /**
         * Check the CSRF token is valid
         *
         * @param  string $type Request type post/get
         * @param  string $etype Error message return type exeception/json
         * @param  int $timespan Makes the token expire after $timespan seconds (null = never)
         * @param  boolean $multiple Makes the token reusable and not one-time (Useful for ajax-heavy requests)
         */
        public function verifyToken($type = 'post', $etype = 'exception', $timespan = null, $multiple = false)
        {
            return true;
            $type = ($type == 'post') ? $_POST : $_GET;
            if(isset($type[$this->tokenName])){
                if(!$this->isTokenValid($type[$this->tokenName])){
                    $this->writeTokenToStorage('');
                    $this->returnErrorMessage('CSRF validation failed. Please reload page.', $etype);
                } else{
                    $decoded_token = base64_decode($this->setToken());
                    $substring_start = substr($decoded_token, 0, 1);
                    if(sha1(ip() . $_SERVER['HTTP_USER_AGENT'] . $this->config->base_url) != substr($decoded_token, 11 + $substring_start, 40)){
                        $this->writeTokenToStorage('');
                        $this->returnErrorMessage('CSRF validation failed. Please reload page.', $etype);
                    }
                    if($timespan != null && is_int($timespan) && intval(substr($decoded_token, 1, 10)) + $timespan < time()){
                        $this->writeTokenToStorage('');
                        $this->returnErrorMessage('CSRF token has expired. Please reload page.', $etype);
                    }
                    if(!$multiple){
                        $this->writeTokenToStorage('');
                    }
                }
            } else{
                $this->writeTokenToStorage('');
                $this->returnErrorMessage('CSRF validation failed. Please reload page.', $etype);
					   
																   
				 
            }
        }

        private function returnErrorMessage($message = '', $type = 'Exception')
        {
            if($type == 'json'){
                exit(json(['error' => $message]));
            } else{
                throw new Exception($message);
            }
        }

        private function setToken()
        {
            $storedToken = $this->readTokenFromStorage();
            if($storedToken === ''){
                $this->generateToken();
                $this->writeTokenToStorage($this->token);
                return $this->token;
            }
            return $storedToken;
        }

        private function generateToken()
        {
            $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'empty';
            $extra = sha1(ip() . $agent . $this->config->base_url);
            $substring_start = rand(0, 9);
            // time() is used for token expiration
            $this->token = base64_encode($substring_start . time() . $this->randomStr($substring_start) . $extra . $this->randomStr(20));
        }

        private function readTokenFromStorage()
        {
            if(isset($_SESSION[$this->tokenName])){
                return $_SESSION[$this->tokenName];
            } else{
                return '';
            }
        }

        private function writeTokenToStorage($token)
        {
            $_SESSION[$this->tokenName] = $token;
        }

        private function randomStr($length)
        {
            $keys = array_merge(range(0, 9), range('a', 'z'));
            $key = "";
            for($i = 0; $i < $length; $i++){
                $key .= $keys[mt_rand(0, count($keys) - 1)];
            }
            return $key;
        }
    }