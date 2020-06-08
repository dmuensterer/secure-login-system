<?php

    class Token {

        public function generateToken($length) {
            return bin2hex(random_bytes($length));
        }

        public function __construct () {
        }
    }

?>