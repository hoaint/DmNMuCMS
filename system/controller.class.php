<?php
    in_file();

    use Gettext\Translator;
    use Gettext\Translations;
    use Gettext\Generators;

    class initialize
    {
        private $translator;
        private $translations;

        public function __construct(config $config)
        {
            static $translation_data = null;
            date_default_timezone_set($config->config_entry('main|timezone'));
            $this->setLocalization($config);
            $this->translator = new Translator();
            if($translation_data == null){
				$file = APP_PATH . DS . 'localization' . DS . $_COOKIE['dmn_language'] . DS . 'LC_MESSAGES' . DS . 'message.json';
				if(file_exists($file)){
					$translation_data = Translations::fromJsonFile($file);
				}
				else{
					$translation_data = Translations::fromMoFile(substr($file, 0, -4).'mo');
				}
			}
            $this->translator->loadTranslations($translation_data);
            $this->translator->register();
            if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'){
                $this->translations = Generators\Jed::toString($translation_data);
            }
        }

        private function setLocalization($config)
        {
            if(!isset($_COOKIE['dmn_language'])){
                $language = $config->language();
                setcookie("dmn_language", $language, strtotime('+5 days', time()), "/");
                $_COOKIE['dmn_language'] = $language;
            }
        }

        public function translations()
        {
            return $this->translations;
        }
    }

    class controller
    {
        private static $_instance;
        public $translations;

        public function __construct()
        {
            self::$_instance = $this;
            foreach($this->is_loaded() as $key => $class){
                $this->$key = load_class($class);
            }
            $this->config = $this->load_class('config');
            $this->load = $this->load_class('load');
            $this->translations = (new initialize($this->config))->translations();
            date_default_timezone_set($this->config->config_entry('main|timezone'));
        }

        public static function get_instance()
        {
            if(!self::$_instance instanceof self){
                self::$_instance = new controller;
            }
            return self::$_instance;
        }

        protected function load_class($class)
        {
            return load_class($class);
        }

        protected function is_loaded()
        {
            return is_loaded();
        }
    }

    interface pluginInterface
    {
        public function index();

        public function install();

        public function uninstall();

        public function enable();

        public function disable();

        public function admin();

        public function about();
    }