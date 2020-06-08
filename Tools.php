<?php
    require_once 'Config.php';
    require_once 'Language.php';
    require_once 'Err.php';

    final class Tools {
        private $configInstance;
        private $language;

        public function isPasswordValid($password) {

            if (strlen($password) < $this->configInstance::MINPASSWORDLENGTH) {
                return new Err ($this->language->password_too_short);
            }
            if (!preg_match("/\d{" . $this->configInstance::MINNUMBERS . ",}/", $password)) {
                return new Err ($this->language->password_not_enough_numbers);
            }
            if (!preg_match("/[A-Z]{" . $this->configInstance::MINUPPERCASELETTERS . ",}/", $password)) {
                return new Err ($this->language->password_not_enough_uppercase);
            }
            if (!preg_match("/[a-z]{" . $this->configInstance::MINLOWERCASELETTERS . ",}/", $password)) {
                return new Err ($this->language->password_not_enough_lowercase);
            }
            if (!preg_match("/\W{" . $this->configInstance::MINSPECIALCHARS . ",}/", $password)) {
                return new Err ($this->language->password_not_enough_special);
            }
             return true;
        }

        public function __construct() {
            $this->configInstance = Configuration::getInstance();
            $l = new Language;
            $this->language = $l->getCurrentLanguage();
        }

    }
?>