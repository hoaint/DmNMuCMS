<?php
    in_file();

    class ipb extends library
    {
        public function __construct()
        {
        }

        private function buildParameters($parameters)
        {
            $default = ['key' => IPS_CONNECT_MASTER_KEY, 'url' => $this->config->base_url,];
            return http_build_query(array_merge($default, $parameters));
        }

        private function process($query)
        {
            $curl_url = IPS_CONNECT_BASE_URL . 'applications/core/interface/ipsconnect/ipsconnect.php?' . $query;
            $ch = curl_init($curl_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            $decoded = json_decode($result, true);
            if(is_array($decoded)){
                return $decoded;
            } else{
                $status = curl_getinfo($ch);
                if($status['http_code'] == 404){
                    throw new Exception('The CURL request <strong>' . $curl_url . '</strong> was not successful. Status Code: ' . $status['http_code']);
                }
            }
        }

        public function login($idType, $id, $password)
        {
            $result = $this->process($this->buildParameters(['do' => 'login', 'idType' => $idType, 'id' => $id, 'password' => $password, 'key' => md5(IPS_CONNECT_MASTER_KEY . $id)]));
            if($result['status'] != 'SUCCESS'){
                throw new Exception($result['status']);
            } else{
                return $result;
            }
        }

        public function crossLogin($id, $returnTo)
        {
            $query = $this->buildParameters(['do' => 'crossLogin', 'id' => $id, 'returnTo' => $returnTo, 'key' => md5(IPS_CONNECT_MASTER_KEY . $id)]);
            return IPS_CONNECT_BASE_URL . 'applications/core/interface/ipsconnect/ipsconnect.php?' . $query;
        }

        public function crossLogout($id, $returnTo)
        {
            $query = $this->buildParameters(['do' => 'logout', 'id' => $id, 'returnTo' => $returnTo, 'key' => md5(IPS_CONNECT_MASTER_KEY . $id)]);
            header('Location: ' . IPS_CONNECT_BASE_URL . 'applications/core/interface/ipsconnect/ipsconnect.php?' . $query);
        }

        public function register($name, $email, $pass_hash, $pass_salt, $revalidateUrl = null)
        {
            $result = $this->process($this->buildParameters(['do' => 'register', 'name' => $name, 'email' => $email, 'pass_hash' => $pass_hash, 'pass_salt' => $pass_salt, 'revalidateUrl' => $revalidateUrl]));
            if(isset($result['status']) && $result['status'] != 'SUCCESS'){
                throw new Exception($result['status']);
            } else if(isset($result['connect_id'])){
                return $result['connect_id'];
            } else{
                throw new Exception(var_export($result, true));
            }
        }

        public function checkEmail($email)
        {
            $result = $this->process($this->buildParameters(['do' => 'checkEmail', 'email' => $email]));
            if($result['status'] != 'SUCCESS'){
                throw new Exception($result['status']);
            } else{
                return boolval($result['used']);
            }
        }

        public function fetchSalt($idType, $id)
        {
            $result = $this->process($this->buildParameters(['do' => 'fetchSalt', 'idType' => $idType, 'id' => $id, 'key' => md5(IPS_CONNECT_MASTER_KEY . $id)]));
            if($result['status'] != 'SUCCESS'){
                return $result['status'];
            } else{
                return $result['pass_salt'];
            }
        }

        public function generateSalt()
        {
            $salt = '';
            for($i = 0; $i < 22; $i++){
                do{
                    $chr = rand(48, 122);
                } while(in_array($chr, range(58, 64)) or in_array($chr, range(91, 96)));
                $salt .= chr($chr);
            }
            return $salt;
        }

        public function encrypt_password($password, $salt)
        {
            return crypt($password, '$2a$13$' . $salt);
        }
    }