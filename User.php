<?php

    interface UserI {
        public function getAccountId();
        public function getUserId();
        public function getEmail();
    }

    class User implements UserI {
        private $accountId;
        private $userId;
        private $email;
        private $shopName;

        function __construct($accountId, $userId, $email) {
            $this->accountId = $accountId;
            $this->userId = $userId;
            $this->title = $email;
        }

        public function getAccountId() {
            return $this->accountId;
        }
        public function getUserId() {
            return $this->userId;
        }
        public function getEmail() {
            return $this->email;
        }
    }
?>