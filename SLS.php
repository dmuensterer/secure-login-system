<?php

    interface SLSI {
        public static function getInstance();
        public function loginUserWithEmailAndPassword(string $email, string $plain_password);
        public function loginUserWithId(int $id);
        public function logoutUser();
        public function registerAccount(string $email, string $plain_password);
        public function verifyPasswordReset(string $passwordVerification);
        public function verifyEmail(string $emailVerification);
    }

    require_once 'Config.php';
    require_once 'DB.php';
    require_once 'Email.php';
    require_once 'Tools.php';


    final class SLS implements SLSI {
        private static $instance = null;

        private $DBInstance;
        private $emailInstance;
        private $configInstance;

        public static function getInstance(): SLS
        {
            if (static::$instance === null) {
                static::$instance = new static();
            }
            return static::$instance;
        }

        /*
        * Wrapper functions
        */

        public function loginUserWithEmailAndPassword(string $email, string $plain_password) {
            $user = $this->DBInstance->loginUserWithEmailAndPassword($email, $plain_password);
            if ($user != null) {
                session_start();
                $_SESSION[$this->configInstance::APPNAME . '_id'] = $user;
            }
            return $user;
        }

        public function loginUserWithId(int $user_id) {
            $user = $this->DBInstance->loginUserWithId($user_id);
            if ($user != null) {
                session_start();
                $_SESSION[$this->configInstance::APPNAME . '_id'] = $user;
            }
            return $user;
        }

        public function isUserLoggedIn() {
            if (isset($_SESSION[$this->configInstance::APPNAME . '_id'])) {
                return true;
            }
            return false;
        }

        public function logoutUser () {
            //Delete cookie
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params["path"],
                    $params["domain"], $params["secure"], $params["httponly"]
                );
            }
            //Destroy session
            if(isset($_SESSION)) {
                session_destroy();
                echo "Session destroyed!";
            }
        }

        public function registerAccount(string $email, string $plain_password) {

            $tools = new Tools;
            $password_valid = $tools->isPasswordValid($plain_password);

            //Check if password is valid
            if (is_a($password_valid, 'Err')) {
                return $password_valid;
            }

            //Check if email verification is activated in Conf.php
            if ($this->configInstance::EMAILVERIFICATION) {
                //Send verification mail
                $sendmail = $this->emailInstance->sendEmailVerificationEmail($email);

                //Error while sending email. Return Err Msg
                if (is_a($sendmail, 'Err')) {
                    return $sendmail;
                }
            }

            $register =  $this->DBInstance->registerAccount($email, $plain_password);


            if (!is_a($register, 'Err')) {
                $this->loginUserWithId($register->getUserId());
                header('Location: dashboard.php');
                return true;
            }
            return $register;
        }

        //Checks if password reset was requested and returns corresponding user object.
        //Returns null if no valid password request was found
        public function verifyPasswordReset(string $passwordVerification) {
            return $this->DBInstance->verifyPasswordReset($passwordVerification);
        }

        /**
         * Checks if provided string is a valid verification token
         */
        public function verifyEmail(string $verification_token) {
            return $this->DBInstance->verifyEmail($verification_token);
        }

        private function __construct () {
            $this->DBInstance = DB::getInstance();
            $this->configInstance = Configuration::getInstance();
            $this->emailInstance = new Email;
        }
    }

?>