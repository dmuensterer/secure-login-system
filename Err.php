<?php
    /**
     * Custom Error class with mandatory description
     * This Class is used in the UI PHP files to display errors to users
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