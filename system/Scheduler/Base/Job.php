<?php

    abstract class Job
    {
        /**
         * Task process linked to job.
         * @since 1.0.0
         * @var object
         */
        protected $task;

        /**
         * Method called to run job.
         * @since 1.0.0
         */
        public function execute()
        {
            // TODO
        }

        /**
         * Getter function.
         * @since 1.0.0
         *
         * @return mixed
         */
        public function __get($property)
        {
            if($property === 'task')
                return $this->$property;
        }

        /**
         * Setter function.
         * @since 1.0.0
         *
         * @return mixed
         */
        public function __set($property, $value)
        {
            if($property === 'task' && is_a($value, 'Task'))
                $this->$property = $value;
        }
    }