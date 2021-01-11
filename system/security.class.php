<?php
    in_file();

    class security
    {
        private $regex = '/[\000\010\011\012\015\032\047\134]/';
        protected $_xss_hash = '';
        protected $_never_allowed_str = ['document.cookie' => '[removed]', 'document.write' => '[removed]', '.parentNode' => '[removed]', '.innerHTML' => '[removed]', '-moz-binding' => '[removed]', '<!--' => '&lt;!--', '-->' => '--&gt;', '<![CDATA[' => '&lt;![CDATA[', '<comment>' => '&lt;comment&gt;'];
        protected $_never_allowed_regex = ['javascript\s*:', '(document|(document\.)?window)\.(location|on\w*)', 'expression\s*(\(|&\#40;)', 'vbscript\s*:', 'wscript\s*:', 'jscript\s*:', 'vbs\s*:', 'Redirect\s+30\d', "([\"'])?data\s*:[^\\1]*?base64[^\\1]*?,[^\\1]*?\\1?"];
        private $charset = 'UTF-8';

        public function __construct()
        {
            if(!isCommandLineInterface()){
                if(!preg_match('/\/edit-news\/(.*)|\/news-composer|\/view-request\/(.*)|\/support|\/read-ticket\/(.*)|\/gm-announcement|\/add-vip|\/edit-vip\/(.*)|\/add-guide|\/edit-guide\/(.*)|\/create-bulk-email|\/edit-bulk-email\/(.*)$/', $_SERVER["REQUEST_URI"], $match)){
                    $this->Xss($_GET);
                    $this->Xss($_POST);
                    $this->Xss($_COOKIE);
                    $this->Xss($_SERVER);
                }
            }
            $this->SanitizeStr($_GET);
            $this->SanitizeStr($_POST);
            $this->SanitizeStr($_COOKIE);
            $this->SanitizeStr($_SERVER);
			
            $this->isIPBanned();
        }

        public function SanitizeStr(&$str)
        {
            if(is_array($str)){
                foreach($str AS $id => $value){
                    $str[$id] = $this->SanitizeStr($value);
                }
            } else{
                if(!preg_match('/^\-?\d+(\.\d+)?$/D', $str) || preg_match('/^0\d+$/D', $str)){
                    $str = preg_replace($this->regex, '', $str);
                }
            }
            return $str;
        }

        public function Xss(&$str)
        {
            if(is_array($str)){
                foreach($str AS $id => $value){
                    if($id == 'fb_script'){
                        return $str;
                    }
                    $str[$id] = $this->Xss($value);
                }
				return $str;
            }

            $str = $this->remove_invisible_characters($str);
            do{
                $str = rawurldecode($str);
            } while(preg_match('/%[0-9a-f]{2,}/i', $str));
            $str = preg_replace_callback("/[^a-z0-9>]+[a-z0-9]+=([\'\"]).*?\\1/si", [$this, '_convert_attribute'], $str);
            $str = preg_replace_callback('/<\w+.*/si', [$this, '_decode_entity'], $str);
            $str = $this->remove_invisible_characters($str);
            $str = str_replace("\t", ' ', $str);
            $str = $this->_do_never_allowed($str);
            $str = str_replace(['<?', '?' . '>'], ['&lt;?', '?&gt;'], $str);
            $words = ['javascript', 'expression', 'vbscript', 'jscript', 'wscript', 'vbs', 'script', 'base64', 'applet', 'alert', 'document', 'write', 'cookie', 'window', 'confirm', 'prompt', 'eval'];
            foreach($words as $word){
                $word = implode('\s*', str_split($word)) . '\s*';
                $str = preg_replace_callback('#(' . substr($word, 0, -3) . ')(\W)#is', [$this, '_compact_exploded_words'], $str);
            }
            do{
                $original = $str;
                if(preg_match('/<a/i', $str)){
                    $str = preg_replace_callback('#<a[^a-z0-9>]+([^>]*?)(?:>|$)#si', [$this, '_js_link_removal'], $str);
                }
                if(preg_match('/<img/i', $str)){
                    $str = preg_replace_callback('#<img[^a-z0-9]+([^>]*?)(?:\s?/?>|$)#si', [$this, '_js_img_removal'], $str);
                }
                if(preg_match('/script|xss/i', $str)){
                    $str = preg_replace('#</*(?:script|xss).*?>#si', '[removed]', $str);
                }
            } while($original !== $str);
            unset($original);
            $pattern = '#<((?<slash>/*\s*)(?<tagName>[a-z0-9]+)(?=[^a-z0-9]|$)[^\s\042\047a-z0-9>/=]*(?<attributes>(?:[\s\042\047/=]*[^\s\042\047>/=]+(?:\s*=(?:[^\s\042\047=><`]+|\s*\042[^\042]*\042|\s*\047[^\047]*\047|\s*(?U:[^\s\042\047=><`]*)))?)*)[^>]*)(?<closeTag>\>)?#isS';
            do{
                $old_str = $str;
                $str = preg_replace_callback($pattern, [$this, '_sanitize_naughty_html'], $str);
            } while($old_str !== $str);
            unset($old_str);
            $str = preg_replace('#(alert|prompt|confirm|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', '\\1\\2&#40;\\3&#41;', $str);
            $str = $this->_do_never_allowed($str);
            return $str;
        }

        protected function _convert_attribute($match)
        {
            return str_replace(['>', '<', '\\'], ['&gt;', '&lt;', '\\\\'], $match[0]);
        }

        protected function _decode_entity($match)
        {
            $match = preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-/]+)|i', $this->xss_hash() . '\\1=\\2', $match[0]);
            return str_replace($this->xss_hash(), '&', $this->entity_decode($match, $this->charset));
        }

        public function entity_decode($str, $charset = null)
        {
            if(strpos($str, '&') === false){
                return $str;
            }
            static $_entities;
            isset($charset) OR $charset = $this->charset;
            $flag = version_compare(PHP_VERSION, '5.4', '>=') ? ENT_COMPAT | ENT_HTML5 : ENT_COMPAT;
            do{
                $str_compare = $str;
                if(preg_match_all('/&[a-z]{2,}(?![a-z;])/i', $str, $matches)){
                    if(!isset($_entities)){
                        $_entities = array_map('strtolower', version_compare(PHP_VERSION, '5.3.4', '>=') ? get_html_translation_table(HTML_ENTITIES, $flag, $charset) : get_html_translation_table(HTML_ENTITIES, $flag));
                        if($flag === ENT_COMPAT){
                            $_entities[':'] = '&colon;';
                            $_entities['('] = '&lpar;';
                            $_entities[')'] = '&rpar;';
                            $_entities["\n"] = '&newline;';
                            $_entities["\t"] = '&tab;';
                        }
                    }
                    $replace = [];
                    $matches = array_unique(array_map('strtolower', $matches[0]));
                    foreach($matches as &$match){
                        if(($char = array_search($match . ';', $_entities, true)) !== false){
                            $replace[$match] = $char;
                        }
                    }
                    $str = str_ireplace(array_keys($replace), array_values($replace), $str);
                }
                $str = html_entity_decode(preg_replace('/(&#(?:x0*[0-9a-f]{2,5}(?![0-9a-f;])|(?:0*\d{2,4}(?![0-9;]))))/iS', '$1;', $str), $flag, $charset);
            } while($str_compare !== $str);
            return $str;
        }

        public function remove_invisible_characters($str, $url_encoded = true)
        {
            $non_displayables = [];
            if($url_encoded){
                $non_displayables[] = '/%0[0-8bcef]/';    // url encoded 00-08, 11, 12, 14, 15
                $non_displayables[] = '/%1[0-9a-f]/';    // url encoded 16-31
            }
            $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';    // 00-08, 11, 12, 14-31, 127
            do{
                $str = preg_replace($non_displayables, '', $str, -1, $count);
            } while($count);
            return $str;
        }

        protected function _compact_exploded_words($matches)
        {
            return preg_replace('/\s+/s', '', $matches[1]) . $matches[2];
        }

        protected function _js_link_removal($match)
        {
            return str_replace($match[1], preg_replace('#href=.*?(?:(?:alert|prompt|confirm)(?:\(|&\#40;)|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|data\s*:)#si', '', $this->_filter_attributes($match[1])), $match[0]);
        }

        protected function _js_img_removal($match)
        {
            return str_replace($match[1], preg_replace('#src=.*?(?:(?:alert|prompt|confirm|eval)(?:\(|&\#40;)|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si', '', $this->_filter_attributes($match[1])), $match[0]);
        }

        protected function _filter_attributes($str)
        {
            $out = '';
            if(preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is', $str, $matches)){
                foreach($matches[0] as $match){
                    $out .= preg_replace('#/\*.*?\*/#s', '', $match);
                }
            }
            return $out;
        }

        protected function _sanitize_naughty_html($matches)
        {
            static $naughty_tags = ['alert', 'prompt', 'confirm', 'applet', 'audio', 'basefont', 'base', 'behavior', 'bgsound', 'blink', 'body', 'embed', 'expression', 'form', 'frameset', 'frame', 'head', 'html', 'ilayer', 'iframe', 'input', 'button', 'select', 'isindex', 'layer', 'link', 'meta', 'keygen', 'object', 'plaintext', 'style', 'script', 'textarea', 'title', 'math', 'video', 'svg', 'xml', 'xss'];
            static $evil_attributes = ['on\w+', 'style', 'xmlns', 'formaction', 'form', 'xlink:href', 'FSCommand', 'seekSegmentTime'];
            if(empty($matches['closeTag'])){
                return '&lt;' . $matches[1];
            } else if(in_array(strtolower($matches['tagName']), $naughty_tags, true)){
                return '&lt;' . $matches[1] . '&gt;';
            } else if(isset($matches['attributes'])){
                $attributes = [];
                $attributes_pattern = '#(?<name>[^\s\042\047>/=]+)(?:\s*=(?<value>[^\s\042\047=><`]+|\s*\042[^\042]*\042|\s*\047[^\047]*\047|\s*(?U:[^\s\042\047=><`]*)))#i';
                $is_evil_pattern = '#^(' . implode('|', $evil_attributes) . ')$#i';
                do{
                    $matches['attributes'] = preg_replace('#^[^a-z]+#i', '', $matches['attributes']);
                    if(!preg_match($attributes_pattern, $matches['attributes'], $attribute, PREG_OFFSET_CAPTURE)){
                        break;
                    }
                    if(preg_match($is_evil_pattern, $attribute['name'][0]) || (trim($attribute['value'][0]) === '')){
                        $attributes[] = 'xss=removed';
                    } else{
                        $attributes[] = $attribute[0][0];
                    }
                    $matches['attributes'] = substr($matches['attributes'], $attribute[0][1] + strlen($attribute[0][0]));
                } while($matches['attributes'] !== '');
                $attributes = empty($attributes) ? '' : ' ' . implode(' ', $attributes);
                return '<' . $matches['slash'] . $matches['tagName'] . $attributes . '>';
            }
            return $matches[0];
        }

        public function xss_hash()
        {
            if($this->_xss_hash === null){
                $rand = $this->get_random_bytes(16);
                $this->_xss_hash = ($rand === false) ? md5(uniqid(mt_rand(), true)) : bin2hex($rand);
            }
            return $this->_xss_hash;
        }

        public function get_random_bytes($length)
        {
            if(empty($length) || !ctype_digit((string)$length)){
                return false;
            }
            if(function_exists('random_bytes')){
                try{
                    return random_bytes((int)$length);
                } catch(Exception $e){
                    writelog($e->getMessage(), 'system_error');
                    return false;
                }
            }
            if(defined('MCRYPT_DEV_URANDOM') && ($output = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM)) !== false){
                return $output;
            }
            if(is_readable('/dev/urandom') && ($fp = fopen('/dev/urandom', 'rb')) !== false){
                version_compare(PHP_VERSION, '5.4', '>=') && stream_set_chunk_size($fp, $length);
                $output = fread($fp, $length);
                fclose($fp);
                if($output !== false){
                    return $output;
                }
            }
            if(function_exists('openssl_random_pseudo_bytes')){
                return openssl_random_pseudo_bytes($length);
            }
            return false;
        }

        protected function _do_never_allowed($str)
        {
            $str = str_replace(array_keys($this->_never_allowed_str), $this->_never_allowed_str, $str);
            foreach($this->_never_allowed_regex as $regex){
                $str = preg_replace('#' . $regex . '#is', '[removed]', $str);
            }
            return $str;
        }  

        public function isIPBanned()
        {
            $file = APP_PATH . DS . 'data' . DS . 'ban.txt';
            if(file_exists($file)){
                $ips = file($file);
                if($ips !== false){
                    $parts = explode('.', ip());
                    if(count($parts) == 4){
                        $client = (int)(ltrim($parts[0], '0')) . '.' . (int)(ltrim($parts[1], '0')) . '.' . (int)(ltrim($parts[2], '0')) . '.' . (int)(ltrim($parts[3], '0'));
                        $ipss = '';
                        foreach($ips as $curip){
                            $curip = trim($curip);
                            if((substr($curip, 0, 2) !== '//') && ($curip != '')){
                                $no_ips = false;
                                $parts = explode('.', $curip);
                                if(count($parts) == 4){
                                    if($client === $curip){
                                        $ipss = $curip;
                                        break;
                                    } else{
                                        $ipss .= $curip;
                                    }
                                }
                            } else{
                                $no_ips = true;
                            }
                        }
                        if($no_ips != true){
                            return $this->inIPRange($client, $ipss);
                        }
                    }
                }
            }
            return false;
        }

        private function inIPRange($ip, $range)
        {
            $range = str_replace(' ', '', $range);
            $range = str_replace('[', '', $range);
            $range = str_replace(']', '', $range);
            $range = str_replace('*', '0-255', $range);
            $ratoms = explode('.', $range);
            foreach($ratoms as $key => $value){
                if(strpos($value, '-') === false)
                    $ratoms[$key] = $value . '-' . $value;
            }
            $iatoms = explode('.', $ip);
            $matches = 0;
            for($i = 0; $i <= 3; $i++){
                list($from, $to) = explode('-', $ratoms[$i]);
                if(((int)(ltrim($iatoms[$i], '0')) >= $from) && ((int)(ltrim($iatoms[$i], '0')) <= $to))
                    $matches++;
            }
            return ($matches == 4);
        }
    }
