<?php
    in_file();

    class session extends library
    {
        protected $driver = 'files';
        protected $sessionDriverName;
        protected $sessionCookieName;
        protected $sessionExpiration = 43200;
        protected $sessionSavePath = null;
        protected $sessionMatchIP = false;
        protected $sessionTimeToUpdate = 43200;
        protected $sessionRegenerateDestroy = false;
        protected $cookieDomain = '';
        protected $cookiePath = '/';
        protected $cookieSecure = false;
        protected $sidRegexp;

        public function __construct($key, $name = 'dmncmssession', $params = [])
        {
            $this->sessionCookieName = $name;
            $this->sessionSavePath = APP_PATH . DS . 'data' . DS . 'sessions';
            $this->start();
        }

        public function start($id = '')
        {
            if(isCommandLineInterface()){
                return;
            } else if((bool)ini_get('session.auto_start')){
                writelog("session.auto_start is enabled in php.ini. Aborting.", "session_error");
                return;
            }
            $this->driver = new SessionFile(['cookiePrefix' => '', 'cookieDomain' => '', 'cookiePath' => $this->cookiePath, 'cookieSecure' => $this->cookieSecure, 'sessionCookieName' => $this->sessionCookieName, 'sessionMatchIP' => $this->sessionMatchIP, 'sessionSavePath' => $this->sessionSavePath, 'sidRegexp' => $this->sidRegexp]);
            $this->configure();
            if($this->driver instanceof SessionHandlerInterface){
                $this->setSaveHandler();
            } else{
                throw new Exception('Session doesn\'t implement SessionHandlerInterface');
            }
            if(isset($_COOKIE[$this->sessionCookieName]) && (!is_string($_COOKIE[$this->sessionCookieName]) || !preg_match('#\A' . $this->sidRegexp . '\z#', $_COOKIE[$this->sessionCookieName]))){
                unset($_COOKIE[$this->sessionCookieName]);
            }
			if($id != ''){
				session_id($id);
			}
            $this->startSession();
            if((empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest' || preg_match('/\/ajax\/login$/', $_SERVER["REQUEST_URI"])) && ($this->sessionTimeToUpdate > 0)){
                if(!isset($_SESSION['last_regenerate'])){
                    $_SESSION['last_regenerate'] = time();
                } else if($_SESSION['last_regenerate'] < (time() - $this->sessionTimeToUpdate)){
                    $this->regenerate($this->sessionRegenerateDestroy);
                }
            } else if(isset($_COOKIE[$this->sessionCookieName]) && $_COOKIE[$this->sessionCookieName] === session_id()){
                $this->setCookie();
            }
        }

        protected function configure()
        {
			if (session_status() !== PHP_SESSION_ACTIVE) {
				ini_set('session.name', $this->sessionCookieName);
				session_set_cookie_params($this->sessionExpiration, $this->cookiePath, $this->cookieDomain, $this->cookieSecure, true);
				ini_set('session.gc_maxlifetime', $this->sessionExpiration);
				ini_set('session.use_trans_sid', 0);
				ini_set('session.use_strict_mode', 1);
				ini_set('session.use_cookies', 1);
				ini_set('session.use_only_cookies', 1);
			}
            $this->configureSidLength();
        }

        protected function configureSidLength()
        {
            if(PHP_VERSION_ID < 70100){
                $bits = 160;
                $hash_function = ini_get('session.hash_function');
                if(ctype_digit($hash_function)){
                    if($hash_function !== '1'){
                        ini_set('session.hash_function', 1);
                        $bits = 160;
                    }
                } else if(!in_array($hash_function, hash_algos(), true)){
                    ini_set('session.hash_function', 1);
                    $bits = 160;
                } else if(($bits = strlen(hash($hash_function, 'dummy', false)) * 4) < 160){
                    ini_set('session.hash_function', 1);
                    $bits = 160;
                }
                $bits_per_character = (int)ini_get('session.hash_bits_per_character');
                $sid_length = (int)ceil($bits / $bits_per_character);
            } else{
                $bits_per_character = (int)ini_get('session.sid_bits_per_character');
                $sid_length = (int)ini_get('session.sid_length');
                if(($sid_length * $bits_per_character) < 160){
                    $bits = ($sid_length * $bits_per_character);
                    $sid_length += (int)ceil((160 % $bits) / $bits_per_character);
                    ini_set('session.sid_length', $sid_length);
                }
            }
            switch($bits_per_character){
                case 4:
                    $this->sidRegexp = '[0-9a-f]';
                    break;
                case 5:
                    $this->sidRegexp = '[0-9a-v]';
                    break;
                case 6:
                    $this->sidRegexp = '[0-9a-zA-Z,-]';
                    break;
            }
            $this->sidRegexp .= '{' . $sid_length . '}';
        }

        public function regenerate($destroy = false)
        {
            $_SESSION['last_regenerate'] = time();
            session_regenerate_id($destroy);
        }

        public function destroy()
        {
            session_destroy();
        }

        public function userdata($key)
        {
            if(is_array($key)){
                foreach($key as $k => $v){
                    if(isset($_SESSION[$k][$v]))
                        return $_SESSION[$k][$v];
                }
            } else{
                if(isset($_SESSION[$key]))
                    return $_SESSION[$key];
            }
            return false;
        }

        public function register($key, $val = null)
        {
            $_SESSION[$key] = $val;
        }

        public function session_key_overwrite($key, $value = [])
        {
            if(!empty($key) && is_array($value)){
                $_SESSION[$key][$value[0]] = $value[1];
            }
        }

        public function is_admin()
        {
            return $this->userdata(['admin' => 'is_admin']);
        }

        public function is_user()
        {
            return $this->userdata(['user' => 'logged_in']);
        }

        public function unset_session_key($key)
        {
            if(is_array($key)){
                foreach($key as $k){
                    unset($_SESSION[$k]);
                }
                return;
            }
            unset($_SESSION[$key]);
        }

        protected function setSaveHandler()
        {
			if (session_status() !== PHP_SESSION_ACTIVE) {
				session_set_save_handler($this->driver, true);
			}
        }

        protected function startSession()
        {
			if (session_status() !== PHP_SESSION_ACTIVE) {
				session_start();
			}
        }

        protected function setCookie()
        {
            setcookie($this->sessionCookieName, session_id(), (empty($this->sessionExpiration) ? 0 : time() + $this->sessionExpiration), $this->cookiePath, $this->cookieDomain, $this->cookieSecure, true);
        }
		
		public function getCookie(){
			return $_COOKIE[$this->sessionCookieName];
		}
    }

    abstract class SessionDriver implements SessionHandlerInterface
    {
        protected $fingerprint;
        protected $lock = false;
        protected $cookiePrefix = '';
        protected $cookieDomain = '';
        protected $cookiePath = '/';
        protected $cookieSecure = false;
        protected $cookieName;
        protected $matchIP = false;
        protected $sessionID;
        protected $savePath;
        protected $failure;
        protected $success;

        public function __construct($config = [])
        {
            $this->cookiePrefix = $config['cookiePrefix'];
            $this->cookieDomain = $config['cookieDomain'];
            $this->cookiePath = $config['cookiePath'];
            $this->cookieSecure = $config['cookieSecure'];
            $this->cookieName = $config['sessionCookieName'];
            $this->matchIP = $config['sessionMatchIP'];
            $this->savePath = $config['sessionSavePath'];
            if(version_compare(PHP_VERSION, '7', '>=')){
                $this->success = true;
                $this->failure = false;
            } else{
                $this->success = 0;
                $this->failure = -1;
            }
        }

        protected function destroyCookie()
        {
            return setcookie($this->cookieName, null, 1, $this->cookiePath, $this->cookieDomain, $this->cookieSecure, true);
        }
    }

    class SessionFile extends SessionDriver implements SessionHandlerInterface
    {
        protected $savePath;
        protected $fileHandle;
        protected $filePath;
        protected $fileNew;
        protected $sidRegexp;

        public function __construct($config = [])
        {
            parent::__construct($config);
            if(!empty($config['sessionSavePath'])){
                $this->savePath = rtrim($config['sessionSavePath'], '/\\');
				if (session_status() !== PHP_SESSION_ACTIVE) {
					ini_set('session.save_path', $config['sessionSavePath']);
				}
            } else{
                $this->savePath = rtrim(ini_get('session.save_path'), '/\\');
            }
            $this->sidRegexp = $config['sidRegexp'];
        }

        public function open($savePath, $name)
        {
            if(!is_dir($savePath)){
                if(!mkdir($savePath, 0700, true)){
                    throw new Exception("Session: Configured save path '" . $this->savePath . "' is not a directory, doesn't exist or cannot be created.");
                }
            } else if(!is_writable($savePath)){
                throw new Exception("Session: Configured save path '" . $this->savePath . "' is not writable by the PHP process.");
            }
            $this->savePath = $savePath;
            $this->filePath = $this->savePath . '/' . $name . ($this->matchIP ? md5($_SERVER['REMOTE_ADDR']) : '');
            return $this->success;
        }

        public function read($sessionID)
        {
            if($this->fileHandle === null){
                $this->fileNew = !file_exists($this->filePath . $sessionID);
                if(($this->fileHandle = fopen($this->filePath . $sessionID, 'c+b')) === false){
                    writelog("Unable to open file '" . $this->filePath . $session_id . "'.", "session_error");
                    return $this->failure;
                }
                if(flock($this->fileHandle, LOCK_EX) === false){
                    writelog("Unable to obtain lock for file '" . $this->filePath . $session_id . "'.", "session_error");
                    fclose($this->fileHandle);
                    $this->fileHandle = null;
                    return $this->failure;
                }
                $this->sessionID = $sessionID;
                if($this->fileNew){
                    chmod($this->filePath . $sessionID, 0600);
                    $this->fingerprint = md5('');
                    return '';
                }
            } else if($this->fileHandle === false){
                return $this->failure;
            } else{
                rewind($this->fileHandle);
            }
            $session_data = '';
            for($read = 0, $length = filesize($this->filePath . $sessionID); $read < $length; $read += mb_strlen($buffer, '8bit')){
                if(($buffer = fread($this->fileHandle, $length - $read)) === false){
                    break;
                }
                $session_data .= $buffer;
            }
            $this->fingerprint = md5($session_data);
            return $session_data;
        }

        public function write($sessionID, $sessionData)
        {
            if($sessionID !== $this->sessionID && ($this->close() === $this->failure || $this->read($sessionID) === $this->failure)){
                return $this->failure;
            }
            if(!is_resource($this->fileHandle)){
                return $this->failure;
            } else if($this->fingerprint === md5($sessionData)){
                return (!$this->fileNew && !touch($this->filePath . $sessionID)) ? $this->failure : $this->success;
            }
            if(!$this->fileNew){
                ftruncate($this->fileHandle, 0);
                rewind($this->fileHandle);
            }
            if(($length = strlen($sessionData)) > 0){
                for($written = 0; $written < $length; $written += $result){
                    if(($result = fwrite($this->fileHandle, substr($sessionData, $written))) === false){
                        break;
                    }
                }
                if(!is_int($result)){
                    $this->fingerprint = md5(substr($sessionData, 0, $written));
                    writelog("Session: Unable to write data.", "session_error");
                    return $this->failure;
                }
            }
            $this->fingerprint = md5($sessionData);
            return $this->success;
        }

        public function close()
        {
            if(is_resource($this->fileHandle)){
                flock($this->fileHandle, LOCK_UN);
                fclose($this->fileHandle);
                $this->fileHandle = $this->fileNew = $this->sessionID = null;
            }
            return $this->success;
        }

        public function destroy($session_id)
        {
            if($this->close() === $this->success){
                if(file_exists($this->filePath . $session_id)){
                    $this->destroyCookie();
                    return unlink($this->filePath . $session_id) ? $this->success : $this->failure;
                }
                return $this->success;
            } else if($this->filePath !== null){
                clearstatcache();
                if(file_exists($this->filePath . $session_id)){
                    $this->destroyCookie();
                    return unlink($this->filePath . $session_id) ? $this->success : $this->failure;
                }
                return $this->success;
            }
            return $this->failure;
        }

        public function gc($maxlifetime)
        {
            if(!is_dir($this->savePath) || ($directory = opendir($this->savePath)) === false){
                writelog("Session: Garbage collector couldn't list files under directory '" . $this->savePath . "'.", "session_error");
                return $this->failure;
            }
            $ts = time() - $maxlifetime;
            $pattern = ($this->matchIP === true) ? '[0-9a-f]{32}' : '';
            $pattern = sprintf('#\A%s' . $pattern . $this->sidRegexp . '\z#', preg_quote($this->cookieName));
            while(($file = readdir($directory)) !== false){
                if(!preg_match($pattern, $file) || !is_file($this->savePath . '/' . $file) || ($mtime = filemtime($this->savePath . '/' . $file)) === false || $mtime > $ts){
                    continue;
                }
                unlink($this->savePath . '/' . $file);
            }
            closedir($directory);
            return $this->success;
        }
    }
