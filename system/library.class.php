<?php
    in_file();

    class library
    {
        public function __get($key)
        {
            $registry = controller::get_instance();
            return $registry->$key;
        }
    }
