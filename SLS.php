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
                $_SESSION[$this->ConfigInstance::APPNAME . '_id'] = $user;
            }
            return $user;
        }

        public function loginUserWithId(int $user_id) {
            $user = $this->DBInstance->loginUserWithId($user_id);
            if ($user != null) {
                session_start();
                $_SESSION[$this->ConfigInstance::APPNAME . '_id'] = $user;
            }
            return $user;
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
            if ($this->configInstance::EMAILVERIFICATION) {
                //Send verification mail
                $sendmail = $this->emailInstance->sendEmailVerificationEmail($email);

                if (is_a($sendmail, 'Err')) {
                    return $sendmail;
                }
            }

            return $this->DBInstance->registerAccount($email, $plain_password);
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