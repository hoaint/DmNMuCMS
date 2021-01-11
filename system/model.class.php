<?php
    in_file();

    class model
    {
        public function __get($key)
        {
            $registry = controller::get_instance();
            return $registry->$key;
        }
    }
