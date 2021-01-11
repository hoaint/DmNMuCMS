<?php
    in_file();

    class pagination
    {
        public $page;
        public $size;
        public $total_records;
        public $link;
        public $lastpage;

        /**
         * Class Constructor
         *
         */
        public function __construct()
        {
        }

        /**
         * Initialize class
         *
         * @param integer $page
         * @param integer $size
         * @param integer $total_records
         * @param string $link
         */
        public function initialize($page = 0, $size = 0, $total_records = 0, $link = '')
        {
            $this->page = $page;
            $this->size = $size;
            $this->total_records = $total_records;
            $this->link = $link;
        }

        /**
         * Get the limit
         *
         * @return string
         */
        public function getLimit()
        {
            $this->lastpage = ($this->total_records == 0) ? 0 : ceil($this->total_records / $this->size);
            //$page = $this->page;
            if($this->page < 1){
                $page = 1;
            } else if($this->page > $this->lastpage && $this->lastpage > 0){
                $page = $this->lastpage;
            } else{
                $page = $this->page;
            }
            $sql = ($page == 1) ? $page * $this->size : ($page - 1) * $this->size;
            return $sql;
        }

        /**
         * Creates page navigation links
         *
         * @return    string
         */
        public function create_links()
        {
            $totalPages = floor($this->total_records / $this->size);
            $totalPages += ($this->total_records % $this->size != 0) ? 1 : 0;
            if($totalPages < 1 || $totalPages == 1)
                return null;
            $output = null;
            $loopStart = 1;
            $loopEnd = $totalPages;
            if($totalPages > 5){
                if($this->page <= 3){
                    $loopStart = 1;
                    $loopEnd = 5;
                } else if($this->page >= $totalPages - 2){
                    $loopStart = $totalPages - 4;
                    $loopEnd = $totalPages;
                } else{
                    $loopStart = $this->page - 2;
                    $loopEnd = $this->page + 2;
                }
            }
            if($loopStart != 1){
                $output .= sprintf('<a id="back" href="' . $this->link . '">&#171;</a>', '1');
            }
            if($this->page > 1){
                $output .= sprintf('<a id="prev" href="' . $this->link . '">' . __('Previous') . '</a>', $this->page - 1);
            }
            for($i = $loopStart; $i <= $loopEnd; $i++){
                if($i == $this->page){
                    $output .= '<a class="on">' . $i . '</a>';
                } else{
                    $output .= sprintf('<a href="' . $this->link . '">', $i) . $i . '</a>';
                }
            }
            if($this->page < $totalPages){
                $output .= sprintf('<a id="next" href="' . $this->link . '">' . __('Next') . '</a>', $this->page + 1);
            }
            if($loopEnd != $totalPages){
                $output .= sprintf('<a id="forward" href="' . $this->link . '">&#187;</a>', $totalPages);
            }
            return '<div id="pagination"><ul><li>' . $output . '</li></ul></div>';
        }
    }