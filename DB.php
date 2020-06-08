<?php

    include 'User.php';
    require_once 'Config.php';
    require_once 'Language.php';
    require_once 'Err.php';

    final class DB {
        private static $instance = null;
        private $configInstance;
        private $language;
        private $conn;

        /**
         * gets the instance via lazy initialization (created on first usage)
         */
        public static function getInstance(): DB
        {
            if (static::$instance === null) {
                static::$instance = new static();
            }

            return static::$instance;
        }

        /**
         * Makes database query by email address then hashes provided password, cross-checkes them and creates session
         */
        public function loginUserWithEmailAndPassword(string $email, string $plain_password) {

            //If aleady logged in, destroy current session
            if (isset($_SESSION)) {
                session_destroy();
            }

            //Check if email verification is activated
            if ($this->configInstance::EMAILVERIFICATION) {
                $stmt = "SELECT Users.id AS user_id, Users.password, Accounts.id AS account_id FROM Users LEFT JOIN Accounts ON Users.id = Accounts.id WHERE Users.email = :email AND email_verified = 1";

            } else {
                $stmt = "SELECT Users.id AS user_id, Users.password, Accounts.id AS account_id FROM Users LEFT JOIN Accounts ON Users.id = Accounts.id WHERE Users.email = :email";
            }

            $prep = $this->conn->prepare($stmt);
            $prep->bindParam(':email', $email);
            $prep->execute();

            if ($prep->rowCount() > 0) {
                $result = $prep->fetch();
                $hashed_password = $result['password'];

                //Verify provided password with hashed password obtained from DB
                if (password_verify($plain_password, $hashed_password)) {
                    return new User(
                        $result['account_id'],
                        $result['user_id'],
                        $email
                    );
                }
            }
            return new Err($this->language->email_password_wrong);
        }

        /**
         * Makes database query by user_id then creates session
         */
        public function loginUserWithId(int $id) {

            //If aleady logged in, destroy current session
            if (isset($_SESSION)) {
                session_destroy();
            }

            $stmt = "SELECT Users.id AS user_id, Users.email, Accounts.id AS account_id FROM Users LEFT JOIN Accounts ON Users.id = Accounts.id WHERE Users.id = :user_id";

            $prep = $this->conn->prepare($stmt);
            $prep->bindParam(':user_id', $user_id);
            $prep->execute();

            if ($prep->rowCount() > 0) {
                $result = $prep->fetch();
                $hashed_password = $result['password'];

                //Verify provided password with hashed password obtained from DB
                return new User(
                    $result['account_id'],
                    $result['user_id'],
                    $result['email'],
                );
            }
            return new Err($this->language->email_password_wrong);
        }

        public function registerAccount(string $email, string $plain_password) {
            $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
            $prep = $this->conn->prepare("SELECT 1 FROM Users WHERE email = :email");
            $prep->bindParam(':email', $email);
            $prep->execute();

            if ($prep->rowCount() == 0) {
                //Create Account
                $prep = $this->conn->prepare("INSERT INTO Accounts (account_data) VALUES ('');");
                $prep->execute();

                $accountId = $this->conn->lastInsertId();

                //Create User
                $prep = $this->conn->prepare("INSERT INTO Users (email, password, account) VALUES (:email, :password, LAST_INSERT_ID());");
                $prep->bindParam(':email', $email);
                $prep->bindParam(':password', $hashed_password);
                $prep->execute();

                $userId = $this->conn->lastInsertId();

                return new User(
                    $accountId,
                    $userId,
                    $email
                );
            }
            return new Err($this->language->email_already_exists);
        }

        /**Checks if password reset was requested and returns corresponding user object.
        *Returns null if no valid password request was found
        **/
        public function verifyPasswordReset(string $passwordVerification) {
            $prep = $this->conn->prepare("SELECT Users.id AS user_id, Users.email, Accounts.id as account_id FROM Users LEFT JOIN Accounts ON Users.id = Accounts.id WHERE passwordVerification = :passwordVerification AND password_reset_valid_until > now()");
            $prep->bindParam(':passwordVerification', $passwordVerification);
            $prep->execute();
            if ($prep->rowCount() == 1) {
                $result = $prep->fetch();
                return new User(
                    $result['account_id'],
                    $result['user_id'],
                    $result['email']
                );
            }
            return new Err($this->language->verification_token_wrong_or_expired);
        }

        public function verifyEmail(string $verification_token) {
            $prep = $this->conn->prepare("SELECT Users.id as user_id, Users.email, Accounts.id AS account_id FROM Users LEFT JOIN Accounts on Users.id = Accounts.id WHERE email_verification = :verification_token AND email_verification_valid_until > now()");
            $prep->bindParam(':verification_token', $verification_token);
            $prep->execute();

            if ($prep->rowCount() == 1) {
                $result = $prep->fetch();

                //Verification token is valid, set email verification status to true for user
                $this->setEmailAsVerified($result['user_id']);
                //Make verification token invalid
                $this->invalidateEmailVerification($result['user_id']);

                return new User(
                    $result['account_id'],
                    $result['user_id'],
                    $result['email']
                );
            }
            return new Err($this->language->verification_token_wrong_or_expired);
        }

        public function setEmailAsVerified(int $id) {
            $prep = $this->conn->prepare("UPDATE Users SET email_verified = 1 WHERE id = :id");
            $prep->bindParam(':id', $id);
            $prep->execute();
        }

        //Resets password of user to hashed plaintext password provided
        public function resetPassword(int $id, string $plain_password) {
            $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
            $prep = $this->conn->prepare("UPDATE Users SET password = :hashed_password WHERE id = :id");
            $prep->bindParam(':hashed_password', $hashed_password);
            $prep->bindParam(':id', $id);
            $prep->execute();
        }

        public function checkEmail(string $email) {
            $prep = $this->conn->prepare("SELECT id FROM Users WHERE email = :email");
            $prep->bindParam(':email', $email);
            $prep->execute();
            if ($prep->rowCount() == 1) {
                return $prep->fetch()['id'];
            }
            return new Err($this->language->email_already_exists);
        }

        public function setPasswordVerificationToken(string $token, int $id) {
            $validUntil = date ("Y-m-d H:i:s", strtotime("+" . (string) $this->configInstance::TOKENVALIDITY . " minutes"));
            $prep = $this->conn->prepare("UPDATE Users SET passwordVerification = :token, password_reset_valid_until = :validity WHERE id = :id");
            $prep->bindParam(':token', $token);
            $prep->bindParam(':validity', $validUntil);
            $prep->bindParam(':id', $id);
            $prep->execute();
        }

        public function setEmailVerificationToken(string $token, int $id, int $validity) {
            $validUntil = date ("Y-m-d H:i:s", strtotime("+" . (string) $validity . " minutes"));
            $prep = $this->conn->prepare("UPDATE Users SET email_verification = :token, email_verification_valid_until = :validity WHERE id = :id");
            $prep->bindParam(':token', $token);
            $prep->bindParam(':validity', $validUntil);
            $prep->bindParam(':id', $id);
            $prep->execute();
        }

        public function invalidatePasswordVerification(int $id) {
            $prep = $this->conn->prepare("UPDATE Users SET passwordVerification = NULL, password_reset_valid_until = NULL WHERE id = :user_id");
            $prep->bindParam(':user_id', $user_id);
            $prep->execute();
        }

        public function invalidateEmailVerification(int $user) {
            $prep = $this->conn->prepare("UPDATE Users SET email_verification = NULL, email_verification_valid_until = NULL WHERE id = :user");
            $prep->bindParam(':user', $user);
            $prep->execute();
        }

        private function __construct(){
            $this->configInstance = Configuration::getInstance();
            $this->language = (new Language)->getCurrentLanguage();
            $this->conn = new PDO("mysql:host=" . $this->configInstance::DBSERVERNAME . ";dbname=" . $this->configInstance::DBNAME, $this->configInstance::DBUSERNAME, $this->configInstance::DBPASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        public function __destruct() {
            $this->conn = null;
        }
        private function __clone(){}
        private function __wakeup(){}
    }
