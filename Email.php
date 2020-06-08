<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'mail/Exception.php';
    require 'mail/PHPMailer.php';
    require_once 'Config.php';
    require_once 'Language.php';
    require_once 'Token.php';

    interface EmailI {
        public function sendEmailVerificationEmail(string $token);
        public function sendForgotPasswordEmail(string $token);
        public function sendChangePasswordEmail(string $token);
    }

    class Email implements EmailI {

        private $configInstance;
        private $language;

        /**
         * Creates and sends email including verification link for user to verify email address
         */
        public function sendEmailVerificationEmail(string $email) {

            $t = new Token();
            $token = $t->generateToken($this->configInstance::TOKENCOMPLEXITY);

            $html_link = "<a href=\"https://muensterer.net/custom-product-upload/register.php?verification=" . $token . ">" . $this->language->confirm_email_address . "</a>";

            $mail = new PHPMailer(true);
            try {

                $mail->CharSet ="UTF-8";

                $mail->setFrom($this->configInstance::SENDERADDRESS, $this->configInstance::SENDERNAME);
                $mail->addAddress($email);
                $mail->addReplyTo($this->configInstance::SENDERADDRESS, $this->configInstance::SENDERNAME);

                // Content
                $mail->isHTML(true);
                $mail->Subject = $this->language->email_verification_subject;
                $mail->Body    = $this->language->email_verification_body_intro . $html_link . $this->language->email_verification_body_disclaimer;

                $mail->send();

            } catch (Exception $e) {
                return new Err($this->language->email_couldnt_be_sent);
            }
        }


        public function sendForgotPasswordEmail(string $token) {

        }
        public function sendChangePasswordEmail(string $token) {

        }

        public function __construct () {
            $this->configInstance = Configuration::getInstance();
            $this->language = (new Language)->getCurrentLanguage();
        }
    }

?>