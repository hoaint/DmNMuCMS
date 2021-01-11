<?php
    in_file();

    class upload
    {
        public $out_file_name = '';
        public $out_file_dir = './assets/uploads/attachment';
        public $max_file_size = 0;
        public $make_script_safe = 1;
        public $force_data_ext = '';
        public $allowed_file_ext = [];
        public $check_file_ext = true;
        public $image_ext = ['gif', 'jpeg', 'jpg', 'jpe', 'png'];
        public $image_check = true;
        public $file_extension = '';
        public $real_file_extension = '';
        public $error_no = 0;
        public $is_image = 0;
        public $original_file_name = "";
        public $parsed_file_name = "";
        public $saved_upload_name = "";

        public function process($file)
        {
            $this->_cleanPaths();
            if(!function_exists('getimagesize')){
                $this->image_check = 0;
            }
            $FILE_NAME = $this->parseCleanValue(str_replace(['<', '>'], '-', isset($file['name']) ? $file['name'] : ''));
            $FILE_SIZE = isset($file['size']) ? $file['size'] : '';
            $FILE_TYPE = isset($file['type']) ? $file['type'] : '';
            $FILE_TYPE = preg_replace("/^(.+?);.*$/", "\\1", $FILE_TYPE);
            if(!isset($file['name']) || $file['name'] == "" || !$file['name'] || !$file['size'] || $file['name'] == "none"){
                if($file['error'] == 2){
                    $this->error_no = 3;
                } else if($file['error'] == 1){
                    $this->error_no = 3;
                } else{
                    $this->error_no = 1;
                }
                return false;
            }
            if(ini_get('file_uploads') == false){
                $this->error_no = 6;
                return false;
            }
            if(!is_uploaded_file($file['tmp_name'])){
                $this->error_no = 1;
                return false;
            }
            if($this->check_file_ext){
                if(!is_array($this->allowed_file_ext) || !count($this->allowed_file_ext)){
                    $this->error_no = 2;
                    return false;
                }
            }
            $this->allowed_file_ext = array_map('strtolower', $this->allowed_file_ext);
            $this->file_extension = $this->_getFileExtension($FILE_NAME);
            if(!$this->file_extension){
                $this->error_no = 2;
                return false;
            }
            $this->real_file_extension = $this->file_extension;
            if($this->check_file_ext && !in_array($this->file_extension, $this->allowed_file_ext)){
                $this->error_no = 2;
                return false;
            }
            if(($this->max_file_size) && ($FILE_SIZE > $this->max_file_size)){
                $this->error_no = 3;
                return false;
            }
            $this->original_file_name = $FILE_NAME;
            $FILE_NAME = preg_replace('/[^\w\.]/', "_", $FILE_NAME);
            if($this->out_file_name){
                $this->parsed_file_name = $this->out_file_name;
            } else{
                $this->parsed_file_name = str_replace('.' . $this->file_extension, "", $FILE_NAME);
            }
            $renamed = 0;
            if($this->make_script_safe){
                if(preg_match('/\.(cgi|pl|js|asp|php|html|htm|jsp|jar)(\.|$)/i', $FILE_NAME)){
                    $FILE_TYPE = 'text/plain';
                    $this->file_extension = 'txt';
                    $this->parsed_file_name = preg_replace('/\.(cgi|pl|js|asp|php|html|htm|jsp|jar)(\.|$)/i', "$2", $this->parsed_file_name);
                    $renamed = 1;
                }
            }
            if(is_array($this->image_ext) && count($this->image_ext)){
                if(in_array($this->real_file_extension, $this->image_ext)){
                    $this->is_image = 1;
                } else{
                    $this->is_image = 0;
                }
            }
            if($this->force_data_ext && !$this->is_image){
                $this->file_extension = str_replace(".", "", $this->force_data_ext);
            }
            $this->parsed_file_name .= '.' . $this->file_extension;
            $this->saved_upload_name = $this->out_file_dir . '/' . $this->parsed_file_name;
            if(!@move_uploaded_file($file['tmp_name'], $this->saved_upload_name)){
                $this->error_no = 4;
                return;
            } else{
                @chmod($this->saved_upload_name, 0777);
            }
            if(!$renamed && $this->file_extension != 'txt'){
                $this->_checkXSSInfile();
                if($this->error_no){
                    return false;
                }
            }
            if($this->is_image){
                if($this->image_check){
                    $img_attributes = @getimagesize($this->saved_upload_name);
                    if(!is_array($img_attributes) || !count($img_attributes)){
                        @unlink($this->saved_upload_name);
                        $this->error_no = 5;
                        return false;
                    } else if(!$img_attributes[2]){
                        @unlink($this->saved_upload_name);
                        $this->error_no = 5;
                        return false;
                    } else if($img_attributes[2] == 1 && ($this->file_extension == 'jpg' || $this->file_extension == 'jpeg')){
                        @unlink($this->saved_upload_name);
                        $this->error_no = 5;
                        return false;
                    }
                }
            }
            if(filesize($this->saved_upload_name) != $file['size']){
                @unlink($this->saved_upload_name);
                $this->error_no = 1;
                return false;
            }
        }

        protected function _checkXSSInfile()
        {
            $fh = fopen($this->saved_upload_name, 'rb');
            $file_check = fread($fh, 512);
            fclose($fh);
            if(!$file_check){
                @unlink($this->saved_upload_name);
                $this->error_no = 5;
                return false;
            } else if(preg_match('#(<script|<html|<head|<title|<body|<pre|<table|<a\s+href|<img|<plaintext|<cross\-domain\-policy)(\s|=|>)#si', $file_check)){
                @unlink($this->saved_upload_name);
                $this->error_no = 5;
                return false;
            }
            return true;
        }

        public function _getFileExtension($file)
        {
            return (strstr($file, '.')) ? strtolower(str_replace(".", "", substr($file, strrpos($file, '.')))) : strtolower($file);
        }

        protected function _cleanPaths()
        {
            $this->out_file_dir = rtrim($this->out_file_dir, '/');
        }

        private function parseCleanValue($val)
        {
            if($val == ""){
                return "";
            }
            $val = str_replace(["\r\n", "\n\r", "\r"], "\n", $val);
            $val = str_replace("&", "&amp;", $val);
            $val = str_replace("<!--", "&#60;&#33;--", $val);
            $val = str_replace("-->", "--&#62;", $val);
            $val = str_ireplace("<script", "&#60;script", $val);
            $val = str_replace(">", "&gt;", $val);
            $val = str_replace("<", "&lt;", $val);
            $val = str_replace('"', "&quot;", $val);
            $val = str_replace("\n", "<br />", $val);
            $val = str_replace("$", "&#036;", $val);
            $val = str_replace("!", "&#33;", $val);
            $val = str_replace("'", "&#39;", $val);
            return $val;
        }
    }
