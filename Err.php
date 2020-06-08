<?php
    /**
     * Creates new Error with provided description
     */
    class Err {
        private $err_description;

        public function getDescription() {
            return $this->err_description;
        }

        public function __construct ($err_description)  {
            $this->err_description = $err_description;
        }
    }
?>