<?php

    class DeleteOldSessions extends Job
    {
        private $path, $ts;

        public function __construct()
        {
            $this->path = APP_PATH . DS . 'data' . DS . 'sessions';
            $this->ts = time() - 3600 * 12;
        }

        public function execute()
        {
            if(!is_dir($this->path) || ($directory = opendir($this->path)) === false){
                return false;
            }
            while(($file = readdir($directory)) !== false){
                if(!is_file($this->path . DS . $file) || ($mtime = filemtime($this->path . DS . $file)) === false || $mtime > $this->ts){
                    continue;
                }
                unlink($this->path . DS . $file);
            }
            closedir($directory);
            return true;
        }
    }