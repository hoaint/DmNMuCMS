<?php

    class Task
    {
        /**
         * Daily task constant.
         * @since 1.0.0
         * @var int
         */
        const DAILY = 0;
        /**
         * Every 1 minute task constant.
         * @since 1.0.0
         * @var int
         */
        const MIN1 = 1;
        /**
         * Every 5 minutes task constant.
         * @since 1.0.0
         * @var int
         */
        const MIN5 = 2;
        /**
         * Every 10 minutes task constant.
         * @since 1.0.0
         * @var int
         */
        const MIN10 = 3;
        /**
         * Every 30 minutes task constant.
         * @since 1.0.0
         * @var int
         */
        const MIN30 = 4;
        /**
         * Every 60 minutes / 1 hour task constant.
         * @since 1.0.0
         * @var int
         */
        const MIN60 = 5;
        /**
         * Every 12 hours task constant.
         * @since 1.0.0
         * @var int
         */
        const MIN720 = 6;
        /**
         * Once every month task constant.
         * @since 1.0.0
         * @var int
         */
        const MONTHLY = 7;
        /**
         * Once every week task constant.
         * @since 1.0.0
         * @var int
         */
        const WEEKLY = 8;
        /**
         * Custom time task constant.
         * @since 1.0.0
         * @var object
         */
        const CUSTOM = 9;
        /**
         * Now task constant.
         * @since 1.0.0
         * @var int
         */
        const NOW = -1;
        /**
         * Task process linked to job.
         * @since 1.0.0
         * @var object
         */
        protected $interval;
        /**
         * Set custom task runtime
         * @since 1.0.0
         * @var object
         */
        protected $present;

        /**
         * Sets task to a daily interval.
         * @since 1.0.0
         *
         * @return this
         */
        public function daily()
        {
            $this->interval = self::DAILY;
            return $this;
        }

        /**
         * Sets task to a weekly interval.
         * @since 1.0.0
         *
         * @return this
         */
        public function weekly()
        {
            $this->interval = self::WEEKLY;
            return $this;
        }

        /**
         * Sets task to a monthly interval.
         * @since 1.0.0
         *
         * @return this
         */
        public function monthly()
        {
            $this->interval = self::MONTHLY;
            return $this;
        }

        /**
         * Sets task to a minute interval.
         * @since 1.0.0
         *
         * @return this
         */
        public function everyMinute()
        {
            $this->interval = self::MIN1;
            return $this;
        }

        /**
         * Sets task to a 5 minutes interval.
         * @since 1.0.0
         *
         * @return this
         */
        public function everyFiveMinutes()
        {
            $this->interval = self::MIN5;
            return $this;
        }

        /**
         * Sets task to a 10 minutes interval.
         * @since 1.0.0
         *
         * @return this
         */
        public function everyTenMinutes()
        {
            $this->interval = self::MIN10;
            return $this;
        }

        /**
         * Sets task to a 30 minutes interval.
         * @since 1.0.0
         *
         * @return this
         */
        public function everyHalfHour()
        {
            $this->interval = self::MIN30;
            return $this;
        }

        /**
         * Sets task to a 60 minutes interval.
         * @since 1.0.0
         *
         * @return this
         */
        public function everyHour()
        {
            $this->interval = self::MIN60;
            return $this;
        }

        /**
         * Sets task to a 720 minutes interval.
         * @since 1.0.0
         *
         * @return this
         */
        public function everyTwelveHours()
        {
            $this->interval = self::MIN720;
            return $this;
        }

        /**
         * Sets task to a now/constant interval.
         * @since 1.0.0
         *
         * @return this
         */
        public function now()
        {
            sleep(5);
            $this->interval = self::NOW;
            return $this;
        }

        /**
         * Sets task to a custom/constant interval.
         * @since 1.0.0
         *
         * @return this
         */
        public function custom($present = '')
        {
            $this->interval = self::CUSTOM;
            $this->present = $present;
            return $this;
        }

        /**
         * Getter function.
         * @since 1.0.0
         *
         * @return mixed
         */
        public function __get($property)
        {
            if($property === 'interval' || $property === 'present')
                return $this->$property;
        }
    }