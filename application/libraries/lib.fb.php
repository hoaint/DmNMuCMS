<?php
    in_file();

    class fb extends library
    {
        private $data;
        private $fbconfig;
        public $redirect_url = '';

        public function __construct()
        {
            $this->fbconfig = $this->config->values('social_config', 'providers');
            if((bool)$this->fbconfig['Facebook']['enabled'] == true){
                require_once APP_PATH . DS . 'libraries' . DS . 'Facebook' . DS . 'autoload.php';
                $this->data = new Facebook\Facebook(['app_id' => $this->fbconfig['Facebook']['keys']['id'], 'app_secret' => $this->fbconfig['Facebook']['keys']['secret'], 'default_graph_version' => 'v2.4',]);
            }
        }

        public function get_fb_login_url($type = '', $style = '')
        {
            if((bool)$this->fbconfig['Facebook']['enabled'] == true){
                $helper = $this->data->getRedirectLoginHelper();
                if(!isset($_SESSION['fb_access_token']) || !$this->session->userdata(['user' => 'logged_in'])){
                    $loginUrl = $helper->getLoginUrl($this->config->base_url . 'account-panel/login-with-facebook', ['email']);
                    if($type == 'button'){
                        $this->redirect_url = '<button type="button" class="' . $style . '" onClick="window.location=\'' . $loginUrl . '\';">' . __('Facebook') . '</button>';
                    } else if($type == 'input'){
                        $this->redirect_url = '<input type="button" class="' . $style . '" onClick="window.location=\'' . $loginUrl . '\';" value="' . __('Facebook') . '" />';
                    } else{
                        $this->redirect_url = '<a href="' . $loginUrl . '"><img src="' . $this->config->base_url . 'assets/' . $this->config->config_entry('main|template') . '/images/facebook-login-button.png" width="154" height="22"></a>';
                    }
                }
            }
        }

        public function check_fb_user()
        {
            if((bool)$this->fbconfig['Facebook']['enabled'] == true){
                $helper = $this->data->getRedirectLoginHelper();
                try{
                    $accessToken = $helper->getAccessToken();
                } catch(Facebook\Exceptions\FacebookResponseException $e){
                    echo 'Graph returned an error: ' . $e->getMessage();
                    exit;
                } catch(Facebook\Exceptions\FacebookSDKException $e){
                    echo 'Facebook SDK returned an error: ' . $e->getMessage();
                    exit;
                }
                if(!isset($accessToken)){
                    if($helper->getError()){
                        header('HTTP/1.0 401 Unauthorized');
                        echo "Error: " . $helper->getError() . "\n";
                        echo "Error Code: " . $helper->getErrorCode() . "\n";
                        echo "Error Reason: " . $helper->getErrorReason() . "\n";
                        echo "Error Description: " . $helper->getErrorDescription() . "\n";
                    } else{
                        header('HTTP/1.0 400 Bad Request');
                        echo 'Bad request';
                    }
                    exit;
                }
                $oAuth2Client = $this->data->getOAuth2Client();
                $tokenMetadata = $oAuth2Client->debugToken($accessToken);
                $tokenMetadata->validateAppId($this->fbconfig['Facebook']['keys']['id']);
                $tokenMetadata->validateExpiration();
                if(!$accessToken->isLongLived()){
                    try{
                        $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                    } catch(Facebook\Exceptions\FacebookSDKException $e){
                        echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>";
                        exit;
                    }
                }
                $_SESSION['fb_access_token'] = (string)$accessToken;
            }
        }

        public function getEmail()
        {
            if((bool)$this->fbconfig['Facebook']['enabled'] == true){
                $this->data->setDefaultAccessToken((string)$_SESSION['fb_access_token']);
                $response = $this->data->get('/me?locale=en_US&fields=name,email');
                $userNode = $response->getGraphUser();
                return $userNode->getField('email');
            }
        }
    }