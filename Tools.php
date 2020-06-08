<?php
    require_once 'Language.php';

    final class Tools {

        private $currentLanguage;

        public function getCurrentLanguage() {
            return $this->currentLanguage;
        }

        public function setCurrentLanguage(string $language) {
            switch($language) {
                case "german":
                    $this->currentLanguage = new German;
                    break;
                case "english":
                    $this->currentLanguage = new English;
                    break;
            }
        }

        public function checkPasswordRequirements($password) {
            $errors = array();

            if (strlen($password) < 8) {
                $errors[] = "Password should be min 8 characters";
            }
            if (!preg_match("/\d/", $password)) {
                $errors[] = "Password should contain at least one digit";
            }
            if (!preg_match("/[A-Z]{1,}/", $password)) {
                $errors[] = "Password should contain at least one Capital Letter";
            }
            if (!preg_match("/[a-z]/", $password)) {
                $errors[] = "Password should contain at least one small Letter";
            }
            if (!preg_match("/\W/", $password)) {
                $errors[] = "Password should contain at least one special character";
            }
            if (preg_match("/\s/", $password)) {
                $errors[] = "Password should not contain any white space";
            }

            if ($errors) {
                foreach ($errors as $error) {
                    echo $error . "\n";
                }
                die();
            } else {
                echo "$password => MATCH\n";
            }
        }

    }
?>