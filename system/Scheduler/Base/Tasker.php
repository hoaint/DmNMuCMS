<?php
    require(SYSTEM_PATH . DS . 'Scheduler' . DS . 'Task.php');
    require(SYSTEM_PATH . DS . 'Scheduler' . DS . 'Base' . DS . 'Job.php');

    abstract class Tasker
    {
        /**
         * Path to where jobs are located.
         * @since 1.0.0
         * @var array
         */
        protected $jobsPath;
        /**
         * Path to where jobs are located.
         * @since 1.0.0
         * @var array
         */
        protected $session;
        /**
         * List of jobs to run.
         * @since 1.0.0
         * @var array
         */
        protected $jobs;
        protected $presets = ['yearly' => '0 0 1 1 *', 'annually' => '0 0 1 1 *', 'monthly' => '0 0 1 * *', 'weekly' => '0 0 * * 0', 'daily' => '0 0 * * *', 'hourly' => '0 * * * *',];
        protected $task_name;

        /**
         * Default constructor.
         * @since 1.0.0
         */
        public function __construct()
        {
            $this->time = time();
            $this->jobs = [];
        }

        /**
         * Adds job to list.
         * @since 1.0.0
         *
         * @param string $name Job name.
         * @param object $task Clouse task creator.
         *
         * @return this for chaining
         */
        public function job($name, Closure $task)
        {
            //set task name for log
            $this->task_name = $name;
            $filename = $this->jobsPath . '/' . $name . '.php';
            // Validate that file exists
            if(!file_exists($filename))
                throw new Exception('Job file not located at:' . $filename);
            // Include job class
            include $filename;
            $job = new $name();
            // Assign task
            $job->task = $task(new Task);
            // Add to list of jobs
            $this->jobs[] = $job;
            // Chaining
            return $this;
        }

        /**
         * Starts tasker.
         * @since 1.0.0
         *
         * @return this for chaining
         */
        public function start()
        {
            // Check on first time execution
            if(!$this->session->has('last_exec_time'))
                $this->session->set('last_exec_time', 0);
            // Loop jobs
            for($i = count($this->jobs) - 1; $i >= 0; --$i){
                $this->executeJob($i);
            }
            // Scheduler finished.
            $this->session->set('last_exec_time', time());
            $this->session->save();
            // Chaining
            return $this;
        }

        /**
         * Executes job at index passed by.
         * @since 1.0.0
         *
         * @param int $index Index.
         */
        private function executeJob($index)
        {
            try{
                if(!$this->check_lock($this->jobs[$index])){
                    if($this->onSchedule($this->jobs[$index])){
                        $this->jobs[$index]->execute();
                        writelog("Task executed " . print_r($this->task_name, 1) . "", "scheduler");
                        $this->log($this->jobs[$index]);
                        $this->lock($this->jobs[$index]);
                    }
                }
            } catch(Exception $e){
                // TODO
            }
        }

        /**
         * Returns flag indicating if task is onSchedule and ready to be executed.
         * @since 1.0.0
         *
         * @param object $job .
         */
        private function onSchedule(Job $job)
        {
            if(!$this->session->has('jobs') && !isset($this->session->get('jobs')->{get_class($job)}))
                return true;
            switch($job->task->interval){
                case Task::MIN1:
                    if($this->lapsedTimeToMinutes($job) > 1)
                        return true;
                    break;
                case Task::MIN5:
                    if($this->lapsedTimeToMinutes($job) > 5)
                        return true;
                    break;
                case Task::MIN10:
                    if($this->lapsedTimeToMinutes($job) > 10)
                        return true;
                    break;
                case Task::MIN30:
                    if($this->lapsedTimeToMinutes($job) > 30)
                        return true;
                    break;
                case Task::MIN60:
                    if($this->lapsedTimeToMinutes($job) > 60)
                        return true;
                    break;
                case Task::MIN720:
                    if($this->lapsedTimeToMinutes($job) > 720)
                        return true;
                    break;
                case Task::DAILY:
                    if($this->timeToDay($job) != date('Ymd'))
                        return true;
                    break;
                case Task::MONTHLY:
                    if($this->timeToMonth($job) != date('Ym'))
                        return true;
                    break;
                case Task::WEEKLY:
                    if($this->timeToWeek($job) != date('YW'))
                        return true;
                    break;
                case Task::NOW:
                    return true;
                case Task::CUSTOM:
                    if($this->isDue($job))
                        return true;
                    break;
            }
            return false;
        }

        /**
         * Returns TRUE if the requested job is due at the given time
         * @param string $job
         * @return bool
         */
        private function isDue(Job $job)
        {
            //pre($job->task->interval);
            if(!$parts = $this->parseExpr($job->task->present)){
				writelog("Task time cannot be parsed " . $job->task->present . "", "scheduler");
                return false;
			}	
            foreach($this->parseTimestamp(time()) as $i => $k)
                if(!in_array($k, $parts[$i])){
					//writelog("Task is not due " . $k . ", ".print_r($parts, 1)."", "scheduler");
                    return false;
				}
            return true;
        }

        /**
         * Parse a cron expression
         * @param string $expr
         * @return array|FALSE
         */
        private function parseExpr($expr)
        {
            $parts = [];
            if(preg_match('/^@(\w+)$/', $expr, $m)){
                if(!isset($this->presets[$m[1]]))
                    return false;
                $expr = $this->presets[$m[1]];
            }
            $expr = preg_split('/\s+/', $expr, -1, PREG_SPLIT_NO_EMPTY);
            $ranges = [
				0 => 59, //minute
                1 => 23, //hour
                2 => 31, //day of month
                3 => 12, //month
                4 => 6 //day of week
            ];
            foreach($ranges as $i => $max){
                if(isset($expr[$i]) && preg_match_all('/(?<=,|^)\h*(?:(\d+)(?:-(\d+))?|(\*))(?:\/(\d+))?\h*(?=,|$)/', $expr[$i], $matches, PREG_SET_ORDER)){
                    $parts[$i] = [];
                    foreach($matches as $m){
                        if(!$range = @range(@$m[3] ? 0 : $m[1], @$m[3] ? $max : (@$m[2] ?: $m[1]), @$m[4] ?: 1))
                            return false;//step exceeds specified range
                        $parts[$i] = array_merge($parts[$i], $range);
                    }
                } else{
                    return false;
                }
            }
            return $parts;
        }

        /**
         * Define a schedule preset
         * @param string $name
         * @param string $expr
         */
        private function preset($name, $expr)
        {
            $this->presets[$name] = $expr;
        }

        /**
         * Parse a timestamp
         * @param int $time
         * @return array
         */
        private function parseTimestamp($time)
        {
            return [(int)date('i', $time), //minute
                (int)date('H', $time), //hour
                (int)date('d', $time), //day of month
                (int)date('m', $time), //month
                (int)date('w', $time) //day of week
            ];
        }

        /**
         * Logs executed job.
         * @since 1.0.0
         *
         * @param object $job .
         */
        private function log(Job $job)
        {
            if(!$this->session->has('jobs'))
                $this->session->set('jobs', new stdClass);
            if(!isset($this->session->get('jobs')->{get_class($job)}))
                $this->session->get('jobs')->{get_class($job)} = new stdClass;
            $this->session->get('jobs')->{get_class($job)}->time = time();
        }

        /**
         * Lock Job for one minute if executed.
         * @since 1.0.0
         *
         * @param object $job .
         */
        private function lock(Job $job)
        {
            if(!$this->session->has('locked_jobs'))
                $this->session->set('locked_jobs', new stdClass);
            if(!isset($this->session->get('locked_jobs')->{get_class($job)}))
                $this->session->get('locked_jobs')->{get_class($job)} = new stdClass;
            $this->session->get('locked_jobs')->{get_class($job)}->lock_time = strtotime("+20 seconds");
        }

        /**
         * Lock Job for one minute if executed.
         * @since 1.0.0
         *
         * @param object $job .
         */
        private function check_lock(Job $job)
        {
            if(!$this->session->has('locked_jobs')){
                return false;
            }
            if(!isset($this->session->get('locked_jobs')->{get_class($job)})){
                return false;
            }
            if(!isset($this->session->get('locked_jobs')->{get_class($job)}->lock_time)){
                return false;
            }
            if($this->session->get('locked_jobs')->{get_class($job)}->lock_time < time()){
                return false;
            }
            return true;
        }

        /**
         * Returns lapsed time to minutes.
         * @since 1.0.0
         *
         * @return float
         */
        private function lapsedTimeToMinutes(Job $job)
        {
            return ($this->time - $this->session->get('jobs')->{get_class($job)}->time) / 60;
        }

        /**
         * Returns last executed to day.
         * @since 1.0.0
         *
         * @return string
         */
        private function timeToDay(Job $job)
        {
            return date('Ymd', $this->session->get('jobs')->{get_class($job)}->time);
        }

        /**
         * Returns last executed to day.
         * @since 1.0.0
         *
         * @return string
         */
        private function timeToMonth(Job $job)
        {
            return date('Ym', $this->session->get('jobs')->{get_class($job)}->time);
        }

        /**
         * Returns last executed to day.
         * @since 1.0.0
         *
         * @return string
         */
        private function timeToWeek(Job $job)
        {
            return date('YW', $this->session->get('jobs')->{get_class($job)}->time);
        }
    }