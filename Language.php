<?php

    require_once 'Config.php';

    class Language {
        private $conf;
        public $password_too_short;
        public $password_not_enough_lowercase;
        public $password_not_enough_uppercase;
        public $password_not_enough_numbers;
        public $password_not_enough_special;
        public $email_verification_subject;
        public $email_verification_body_intro;
        public $email_verification_body_disclaimer;
        public $confirm_email_address;
        public $email_sent_successfully;
        public $email_already_exists;
        public $passwords_dont_match;
        public $email_invalid;
        public $verification_token_wrong_or_expired;
        public $email_couldnt_be_sent;


        /**
         * Returns Language object of language defined in Conf.php
         */
        public function getCurrentLanguage() {
            if ($this->conf::LANGUAGE == "german") {
                return new German;
            } else {
                return new English;
            }
        }

        public function __construct () {
            $this->conf = Configuration::getInstance();
        }

    }

    class German extends Language {

        public function __construct () {
            $this->conf = Configuration::getInstance();

            //Passwort Anforderungen
            $this->password_too_short = "Ihr Passwort muss aus mindestens " . $this->conf::MINPASSWORDLENGTH . "Zeichen bestehen.";
            $this->password_not_enough_lowercase = "Ihr Passwort muss mindestens " . $this->conf::MINLOWERCASELETTERS . "Kleinbuchstaben beinhalten.";
            $this->password_not_enough_uppercase = "Ihr Passwort muss mindestens " . $this->conf::MINUPPERCASELETTERS . "Großbuchstaben beinhalten.";
            $this->password_not_enough_numbers = "Ihr Passwort muss mindestens " . $this->conf::MINNUMBERS . "Ziffern beinhalten.";
            $this->password_not_enough_special = "Ihr Passwort muss mindestens " . $this->conf::MINSPECIALCHARS . "Sonderzeichen beinhalten.";

            //E-Mail Texte
            $this->email_verification_subject = "Bitte bestätigen Sie Ihre E-Mail Adresse";
            $this->email_verification_body_intro = "Guten Tag,<br> bitte klicken Sie zum Bestätigen Ihrer E-Mail Adresse auf folgenden Link:<br>";
            $this->email_verification_body_disclaimer = "<br><br>Falls Sie sich nicht bei uns registriert haben, können Sie diese E-Mail ignorieren.<br><br>Mit freundlichen Grüßen";

            //UI Texte
            $this->confirm_email_address = "E-Mail Adresse bestätigen";
            $this->email_sent_successfully = "Vielen Dank für die Registrierung. Sobald Sie Ihre E-Mail Adresse bestätigt haben, können Sie sich anmelden.";
            $this->email_already_exists = "E-Mail Adresse ist bereits in Benutzung.";
            $this->passwords_dont_match = "Die eingegebenen Passwörter stimmen nicht überein.";
            $this->email_invalid = "Die eingegebene E-Mail Adresse ist ungültig.";
            $this->email_password_wrong = "E-Mail oder Passwort falsch.";
            $this->verification_token_wrong_or_expired = "Verifikationstoken falsch oder abgelaufen.";
            $this->email_couldnt_be_sent = "Die Verifizierungs E-Mail konnte leider nicht gesendet werden. Bitte versuchen Sie es später noch einmal..";

        }
    }

    class English extends Language {
        public $language = "english";

        public function __construct () {
            $this->conf = Configuration::getInstance();

            //Password requirements
            $this->password_too_short = "Your password must include at least " . $this->conf::MINPASSWORDLENGTH . "character(s).";
            $this->password_not_enough_lowercase = "Must include at least " . $this->conf::MINLOWERCASELETTERS . "lowercase letter(s).";
            $this->password_not_enough_uppercase = "Must include at least " . $this->conf::MINUPPERCASELETTERS . "uppercase letter(s).";
            $this->password_not_enough_numbers = "Must include at least " . $this->conf::MINNUMBERS . "number(s).";
            $this->password_not_enough_special = "Must include at least " . $this->conf::MINSPECIALCHARS . "special character(s).";

            //E-Mail wordings
            $this->email_verification_subject = "Please confirm your email address";
            $this->email_verification_body_intro = "Hello!<br>Please click the following link to confirm your email address:<br>";
            $this->email_verification_body_disclaimer = "<br><br>If you did not ask to be registered at our platform, you can simply ignore this message.<br><br>Kind regards";

            //UI Wordings
            $this->confirm_email_address = "Confirm email address";
            $this->email_sent_successfully = "Thank you for registering. After you have confirmed your email addres you will be able to login.";
            $this->email_already_exists = "The entered email address is already in use.";
            $this->passwords_dont_match = "The entered passwords don't match.";
            $this->email_invalid = "The entered email address is invalid.";
            $this->email_password_wrong = "Email or password wrong.";
            $this->verification_token_wrong_or_expired = "Verification token wrong or expired.";
            $this->email_couldnt_be_sent = "Verification email could not be sent. This is our fault. Please try again later.";
        }

    }
?>