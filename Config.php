<?php
  require_once 'Tools.php';

  /**
  * Config
  **/

  final class Configuration {
    private static $instance = null;

    public static function getInstance(): Configuration {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    // Set MariaDB/MySQL credentials
    public const DBSERVERNAME = "localhost";
    public const DBNAME = "auth";
    public const DBUSERNAME = "auth_user";
    public const DBPASSWORD = "mypassword";

    //Set instance name of app. This will allow you multiple sessions on one PHP installation
    public const APPNAME = "myapp";

    //Are created accounts Multi User Accounts and therefore can be used my multiple users per account?
    public const MULTIUSERACCOUNT = true;

    // Is email verification needed by the user?
    public const EMAILVERIFICATION = false;

    // Set Language, valid options are currently 'german' or 'english'
    public const LANGUAGE = "german";

    // Set minimum required password length for users
    public const MINPASSWORDLENGTH = 8;

    // Set required password charset
    // This only sets MINIMAL requirements.
    public const MINLOWERCASELETTERS = 1;
    public const MINUPPERCASELETTERS = 1;
    public const MINNUMBERS = 1;
    public const MINSPECIALCHARS = 1;

    //Set token complexity in bit
    public const TOKENCOMPLEXITY = 128;

    //Validity of verification code sent via email in minutes
    public const TOKENVALIDITY = 120;

    //Sender address displayed when sending verification emails
    public const SENDERADDRESS = "info@muensterer.net";
    public const SENDERNAME = "muensterer.net Web Services";
  }

?>