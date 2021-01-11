<?php
    in_file();

    class db extends library
    {
        private $db_conn = null;
        private $host = '';
        private $user = '';
        private $pass = '';
        private $con_type = '';
        private $file;
        private $query = '';
        private $db = '';
        private $queries = [];
        private $querycount = 0;
        private $fields = [];
        private $values = [];
        private $error = [];
        private $driver;

        public function __construct($host, $user, $pass, $db, $con_type = '')
        {
            $this->host = $host;
            $this->user = $user;
            $this->pass = $pass;
            $this->db = $db;
            $this->con_type = $con_type;
            if($this->host == '' || $this->user == '' || $this->db == ''){
                throw new Exception('DmN CMS: Missing One Of Connection Parameters');
            } else{
                $this->driver = ($this->con_type != '') ? strtolower($this->con_type) : strtolower(DRIVER);
                $this->make_connection();
            }
        }

        private function make_connection()
        {
            switch($this->driver){
                case 'pdo_sqlsrv':
                case 'pdo_sqlserv':
                    if(!extension_loaded('pdo_sqlsrv') && !extension_loaded('pdo_sqlserv')){
                        throw new Exception('Please enable PDO SQL_SERV extensions in your php.ini');
                    } else{
                        $this->db_conn = new PDO("sqlsrv:Server=" . $this->host . ";Database=" . $this->db . "", "" . $this->user . "", "" . $this->pass . "");
                        //$this->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_SYSTEM);
                        $this->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
                    }
                    break;
                case 'pdo_odbc':
                    if(!extension_loaded('pdo_odbc')){
                        throw new Exception('Please enable PDO ODBC extensions in your php.ini');
                    } else{
                        $this->db_conn = new PDO("odbc:Driver={SQL Server};Server=" . $this->host . ";Database=" . $this->db . ";Uid=" . $this->user . ";Pwd=" . $this->pass . ";charset=UTF-8;MARS_Connection=Yes");
                    }
                    break;
                case 'pdo_dblib':
                    if(!extension_loaded('pdo_dblib')){
						//$this->setAttribute(PDO::ATTR_PERSISTENT, true);
                        throw new Exception('Please enable PDO DBLIB extensions in your php.ini');
													  
                    } 
					else{
                        $this->db_conn = new PDO("dblib:host=" . $this->host . ";dbname=" . $this->db . ";", "" . $this->user . "", "" . $this->pass . "");
						
                    }
                    break;
                case 'pdo_mysql':
                    if(!extension_loaded('pdo_mysql')){
                        throw new Exception('Please enable PDO MYSQL extensions in your php.ini');
                    } else{
                        $this->db_conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=utf8", "" . $this->user . "", "" . $this->pass . "");
                    }
                    break;
                default:
                    throw new Exception('Invalid driver configuration check you constants.php');
                    break;
            }
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            if(!$this->db_conn){
                die($this->db_conn->errorCode());
            }
        }

        public function get_connection()
        {
            return $this->db_conn;
        }

        public function setAttribute($attr, $attr2)
        {
            $this->db_conn->setAttribute($attr, $attr2);
        }

        public function query($query)
        {
            try{
                $this->query = $this->db_conn->query($query);
                if(defined('LOG_SQL')){
                    if(LOG_SQL == true){
                        $this->log($this->query->queryString, 'database_log_' . date('Y-m-d', time()) . '.txt');
                    }
                }
                return $this;
            } catch(Exception $e){
                $this->log($query, 'database_error_log_' . date('Y-m-d', time()) . '.txt');
                $this->log($e, 'database_error_log_' . date('Y-m-d', time()) . '.txt');
                throw new Exception('Sql sintax error. Please check application/logs/database_error_log_' . date('Y-m-d', time()) . '.txt for errors.');
            }
        }

        public function cached_query($name, $query, $data = [], $cache_time = 60)
        {
            if($this->config->config_entry('main|cache_type') == 'file'){
                $this->load->lib('cache', ['File', ['cache_dir' => APP_PATH . DS . 'data' . DS . 'cache']]);
            } else{
                $this->load->lib('cache', ['MemCached', ['ip' => $this->config->config_entry('main|mem_cached_ip'), 'port' => $this->config->config_entry('main|mem_cached_port')]]);
            }
            $cached_data = $this->cache->get($name, true);
            if(!$cached_data){
                $stmt = $this->prepare($query);
                $stmt->execute($data);
                $result = $stmt->fetch_all();
                if(count($result) > 0){
                    $this->cache->set($name, $result, $cache_time);
                    return $result;
                } else{
                    return false;
                }
            }
            return $cached_data;
        }

        public function prepare($query)
        {
            $this->query = $this->db_conn->prepare($query);
            return $this;
        }

		// @ioncube.dk use_funcs2("DmN ","cms", "DmN") -> "DmNDmNCMS110Stable" RANDOM
        public function execute($params = [])
        {
            if(defined('LOG_SQL')){
                if(LOG_SQL == true){
                    $this->log($this->debug_pdo_query($this->query->queryString, $params), 'database_log_' . date('Y-m-d', time()) . '.txt');
                }
            }
            try{
                return (is_array($params) && count($params) > 0) ? $this->query->execute($params) : $this->query->execute();
            } catch(PDOException $e){
                $this->log($e, 'database_error_log_' . date('Y-m-d', time()) . '.txt');
                throw new Exception('Sql sintax error. Please check application/logs/database_error_log_' . date('Y-m-d', time()) . '.txt for errors.');
            }
        }

        public function fetch()
        {
            return $this->query->fetch();
        }

        public function fetch_all()
        {
            return $this->query->fetchAll();
        }

        public function numrows()
        {
            return $this->query->rowCount();
        }

        public function snumrows($query)
        {
            $query = $this->query($query)->fetch();
            return $query['count'];
        }

        public function check_table($table = '')
        {
            return $this->snumrows('SELECT COUNT(*) AS count FROM information_schema.tables WHERE table_name = \'' . $this->sanitize_var($table) . '\'');
        }

        public function rows_affected()
        {
            return $this->query->rowCount();
        }

        public function escape($string, $param_type = PDO::PARAM_STR)
        {
            if(is_int($string) || is_float($string))
                return $string;
            if(($value = $this->db_conn->quote($string, $param_type)) !== false)
                return $value; 
			else
                return "'" . addcslashes(str_replace("'", "''", $string), "\000\n\r\\\032") . "'";
        }

        public function sanitize_var($var)
        {
            return (!preg_match('/^\-?\d+(\.\d+)?$/D', $var) || preg_match('/^0\d+$/D', $var)) ? preg_replace('/[\000\010\011\012\015\032\047\134]/', '', $var) : $var;
        }

        public function next_row_set()
        {
            return $this->query->nextRowset();
        }

        public function close_cursor()
        {
            return $this->query->closeCursor();
        }

        public function bind_parameters($parameter, $variable, $data_type, $length = null)
        {
            return $this->query->bindParam($parameter, $variable, $data_type, $length);
        }

        public function last_insert_id()
        {
            if(DRIVER == 'pdo_odbc'){
                $q = $this->query('SELECT @@IDENTITY AS id')->fetch();
                return $q['id'];
            }
            return $this->db_conn->lastInsertId();
        }

        public function error()
        {
            $this->error = $this->query->errorInfo();
            return $this->error[2];
        }

        private function log($query, $file = 'database_log.txt')
        {
            $this->file = @fopen(APP_PATH . DS . 'logs' . DS . $file, 'a');
            @fputs($this->file, $query . "\n");
            @fputs($this->file, str_repeat('=', 80) . "\n");
            @fclose($this->file);
        }

        public function get_insert($table, $data)
        {
            foreach($data as $curdata){
                $this->fields[] = $curdata['field'];
                switch(strtolower($curdata['type'])){
                    case 'i':
                        $this->values[] = (int)$curdata['value'];
                        break;
                    case 's':
                        $this->values[] = '\'' . $curdata['value'] . '\'';
                        break;
                    case 'v':
                        $this->values[] = $curdata['value'];
                        break;
                    case 'd':
                        $this->values[] = '\'' . date('Ymd H:i:s', $curdata['value']) . '\'';
                        break;
                    case 'ds':
                        $this->values[] = '\'' . date('Ymd', $curdata['value']) . '\'';
                        break;
                    case 'e':
                        $this->values[] = 'NULL';
                        break;
                }
            }
            return 'INSERT INTO ' . $table . ' (' . implode(', ', $this->fields) . ') VALUES (' . implode(', ', $this->values) . ')';
        }

        public function get_query_count()
        {
            return $this->querycount;
        }

        public function get_quearies()
        {
            return $this->queries;
        }

        public function check_if_table_exists($table)
        {
            return $this->query('SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = N\'' . $this->sanitize_var($table) . '\'')->fetch();
        }

        public function check_if_column_exists($column, $table)
        {
            return $this->query('SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = \'' . $this->sanitize_var($table) . '\'  AND COLUMN_NAME = \'' . $this->sanitize_var($column) . '\'')->fetch();
        }

        public function add_column($column, $table, $info)
        {
            $query = 'ALTER TABLE ' . $this->sanitize_var($table) . ' ADD ' . $this->sanitize_var($column) . ' ' . $info['type'];
            if($info['identity'] == 1){
                $query .= ' IDENTITY(1,1)';
            }
            if($info['is_primary_key'] == 1){
                $query .= ' PRIMARY KEY';
            }
            $query .= ($info['null'] == 1) ? ' NULL' : ' NOT NULL';
            if($info['default'] != ''){
                $query .= ' DEFAULT ' . $info['default'] . '';
            }
            return $this->query($query);
        }

        public function remove_table($table)
        {
            return $this->query('DROP TABLE ' . $this->sanitize_var($table) . '');
        }

        private function debug_pdo_query($raw_sql, $params = [])
        {
            $keys = [];
            $values = $params;
            foreach($params as $key => $value){
                if(is_string($key)){
                    $keys[] = '/' . $key . '/';
                } else{
                    $keys[] = '/[?]/';
                }
                if(is_string($value)){
                    $values[$key] = "'" . $value . "'";
                } else if(is_array($value)){
                    $values[$key] = implode(',', $value);
                } else if(is_null($value)){
                    $values[$key] = 'NULL';
                } else if(is_bool($value)){
                    $values[$key] = ($value === false) ? 0 : 1;
                }
            }
            return preg_replace($keys, $values, $raw_sql, 1, $count);
        }
    }
	
	class sqlsrv extends library
    {
        private $db_conn = null;
        private $host = '';
        private $user = '';
        private $pass = '';
        private $con_type = '';
        private $file;
        private $query = '';
		private $stmt = '';
        private $db = '';
        private $queries = [];
        private $querycount = 0;
        private $fields = [];
        private $values = [];
        private $error = [];

        public function __construct($host, $user, $pass, $db, $con_type = '')
        {
            $this->host = $host;
            $this->user = $user;
            $this->pass = $pass;
            $this->db = $db;
            $this->make_connection();
        }

        private function make_connection()
        {
            if(!extension_loaded('sqlsrv')){
                throw new Exception('Please enable sqlsrv extension in your php.ini');
            } else{
                $this->db_conn = sqlsrv_connect($this->host, ["Database"=> $this->db, "UID"=> $this->user, "PWD"=> $this->pass, "ReturnDatesAsStrings" => true]);
                if(!$this->db_conn){
                    $this->error = "DmN CMS: Failed to connect to sql server instance. Please check your configuration details.\n";
					$errors = $this->error();
                    if(trim($errors) != ''){
                        $this->error .= 'Error: ' . $errors;
                    }
                    throw new Exception(htmlspecialchars($errors));
                }
            }
        }

        public function get_connection()
        {
            return $this->db_conn;
        }

        public function prepare($query)
        {
            $this->query = $query;
            return $this;
        }

        public function execute($params = [], $dump = false)
        {
			$this->stmt = sqlsrv_prepare($this->db_conn, $this->replace_named_params($this->query), $this->remove_keys_from_params($params));
			if($this->stmt != false){
				$query = $this->compile_binds($this->query, $params, $dump);
				if(sqlsrv_execute($this->stmt) != false){
					if(defined('LOG_SQL')){
						if(LOG_SQL == true){
							$this->log($query, 'database_log_' . date('Y-m-d', time()) . '.txt');
						}
					}
					return $this;
				}
				else{
					$this->log($query, 'database_error_log_' . date('Y-m-d', time()) . '.txt');
					$this->log($this->error(), 'database_error_log_' . date('Y-m-d', time()) . '.txt');
					throw new Exception('Sql sintax error. Please check application/logs/database_error_log_' . date('Y-m-d', time()) . '.txt for errors.');
				}
			}
			else{
				$this->log($query, 'database_error_log_' . date('Y-m-d', time()) . '.txt');
                $this->log($this->error(), 'database_error_log_' . date('Y-m-d', time()) . '.txt');
                throw new Exception('Sql sintax error. Please check application/logs/database_error_log_' . date('Y-m-d', time()) . '.txt for errors.');
			}
        }
		
		private function remove_keys_from_params($params){
			$data = [];
			foreach($params AS $key => $value){
				$data[] = $value;
			}
			return $data;
		}
		
		private function replace_named_params($sql){
			if(strpos($sql, ':') === false)
				return $sql;
			else{
				$patterns = [];
				$replacements = [];
				$i = 0;
				preg_match_all('/:(?P<name>[a-zA-Z_]+)/i', $sql, $match, PREG_SET_ORDER);
				foreach($match as $key => $value){
					$patterns[$i] = '/' . $value[0] . '\b/u';
					$replacements[$i] = '?';
					$i++;
				}
				$sql = preg_replace($patterns, $replacements, $sql);
				return $sql;
			}
		}
		// @ioncube.dk use_funcs2("DmN ","cms", "DmN") -> "DmNDmNCMS110Stable" RANDOM
        function compile_binds($sql, $binds, $dump = false)
        {
            if(strpos($sql, ':') === false)
                return $sql;
            if(!is_array($binds))
                $binds = [$binds];
            preg_match_all('/:(?P<name>[a-zA-Z_]+)/i', $sql, $match, PREG_SET_ORDER);
            $patterns = [];
            $replacements = [];
            $i = 0;
            foreach($match as $key => $value){
                $patterns[$i] = '/' . $value[0] . '\b/u';
                $replacements[$i] = $this->escape($binds[$value[0]]);
                $i++;
            }
            $sql = preg_replace($patterns, $replacements, $sql);
            return $sql;
        }

        public function query($query)
        {
            $this->stmt = sqlsrv_query($this->db_conn, $query);
            if($this->stmt != false){
                if(defined('LOG_SQL')){
                    if(LOG_SQL == true){
                        $this->log($query, 'database_log_' . date('Y-m-d', time()) . '.txt');
                    }
                }
                return $this;
            } else{
                $this->log($query, 'database_error_log_' . date('Y-m-d', time()) . '.txt');
                $this->log($this->error(), 'database_error_log_' . date('Y-m-d', time()) . '.txt');
                throw new Exception('Sql sintax error. Please check application/logs/database_error_log_' . date('Y-m-d', time()) . '.txt for errors.');
            }
        }

        public function cached_query($name, $query, $data = [], $cache_time = 60)
        {
            if($this->config->config_entry('main|cache_type') == 'file'){
                $this->load->lib('cache', ['File', ['cache_dir' => APP_PATH . DS . 'data' . DS . 'cache']]);
            } else{
                $this->load->lib('cache', ['MemCached', ['ip' => $this->config->config_entry('main|mem_cached_ip'), 'port' => $this->config->config_entry('main|mem_cached_port')]]);
            }
            $cached_data = $this->cache->get($name, true);
            if(!$cached_data){
                $stmt = $this->prepare($query);
                $stmt->execute($data);
                $result = $stmt->fetch_all();
                if(count($result) > 0){
                    $this->cache->set($name, $result, $cache_time);
                    return $result;
                } else{
                    return false;
                }
            }
            return $cached_data;
        }

        public function fetch()
        {
            return sqlsrv_fetch_array($this->stmt, SQLSRV_FETCH_ASSOC);
        }

        public function fetch_all()
        {
            $list = [];
            while($row = $this->fetch()){
                $list[] = $row;
            }
            return $list;
        }

        public function numrows()
        {
            return sqlsrv_num_rows($this->stmt);
        }

        public function snumrows($query)
        {
            $query = $this->query($query)->fetch();
            return $query['count'];
        }

        public function check_table($table = '')
        {
            return $this->snumrows('SELECT COUNT(*) AS count FROM information_schema.tables WHERE table_name = \'' . $this->sanitize_var($table) . '\'');
        }

        public function rows_affected()
        {
            return sqlsrv_rows_affected($this->stmt);
        }

        public function close_cursor()
        {
            return;
        }

        public function bind_parameters($parameter = '', $variable = '', $data_type = '', $length = null)
        {
            return;
        }

        public function last_insert_id()
        {
            $q = $this->query('SELECT @@IDENTITY AS id')->fetch();
            return $q['id'];
        }

        public function error()
        {
			$errors = sqlsrv_errors();
			$message = '';
            if($errors != NULL){
				foreach($errors as $error){
					$message .= 'SQLSTATE: '.$error[ 'SQLSTATE'].', code: '.$error[ 'code'].', message: '.$error[ 'message'].'<br />';
				}
			}
			return $message;
        }

        public function get_insert($table, $data)
        {
            foreach($data as $curdata){
                $this->fields[] = $curdata['field'];
                switch(strtolower($curdata['type'])){
                    case 'i':
                        $this->values[] = (int)$curdata['value'];
                        break;
                    case 's':
                        $this->values[] = '\'' . $curdata['value'] . '\'';
                        break;
                    case 'v':
                        $this->values[] = $curdata['value'];
                        break;
                    case 'd':
                        $this->values[] = '\'' . date('Ymd H:i:s', $curdata['value']) . '\'';
                        break;
                    case 'ds':
                        $this->values[] = '\'' . date('Ymd', $curdata['value']) . '\'';
                        break;
                    case 'e':
                        $this->values[] = 'NULL';
                        break;
                }
            }
            return 'INSERT INTO ' . $table . ' (' . implode(', ', $this->fields) . ') VALUES (' . implode(', ', $this->values) . ')';
        }

        private function log($query, $file = 'database_log.txt')
        {
            $this->file = @fopen(APP_PATH . DS . 'logs' . DS . $file, 'a');
            @fputs($this->file, $query . "\n");
            @fputs($this->file, str_repeat('=', 80) . "\n");
            @fclose($this->file);
        }

        public function get_query_count()
        {
            return $this->querycount;
        }

        public function get_quearies()
        {
            return $this->queries;
        }

        public function sanitize_var($var)
        {
            return (!preg_match('/^\-?\d+(\.\d+)?$/D', $var) || preg_match('/^0\d+$/D', $var)) ? preg_replace('/[\000\010\011\012\015\032\047\134]/', '', $var) : $var;
        }

        public function escape($str)
        {
            if(is_string($str)){
                $str = "'" . $this->sanitize_var($str) . "'";
            } else if(is_bool($str)){
                $str = ($str === false) ? 0 : 1;
            } else if(is_null($str)){
                $str = 'NULL';
            }
            return $str;
        }

        public function check_if_table_exists($table)
        {
            return $this->query('SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = N\'' . $this->sanitize_var($table) . '\'')->fetch();
        }

        public function check_if_column_exists($column, $table)
        {
            return $this->query('SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = \'' . $this->sanitize_var($table) . '\'  AND COLUMN_NAME = \'' . $this->sanitize_var($column) . '\'')->fetch();
        }

        public function add_column($column, $table, $info)
        {
            $query = 'ALTER TABLE ' . $this->sanitize_var($table) . ' ADD ' . $this->sanitize_var($column) . ' ' . $info['type'];
            if($info['identity'] == 1){
                $query .= ' IDENTITY(1,1)';
            }
            if($info['is_primary_key'] == 1){
                $query .= ' PRIMARY KEY';
            }
            $query .= ($info['null'] == 1) ? ' NULL' : ' NOT NULL';
            if($info['default'] != ''){
                $query .= ' DEFAULT ' . $info['default'] . '';
            }
            return $this->query($query);
        }

        public function remove_table($table)
        {
            return $this->query('DROP TABLE ' . $this->sanitize_var($table) . '');
        }
    }