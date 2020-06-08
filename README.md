# Secure Login System - A simple PHP/MySQL OOP login system with focus on security

## Class features
- Account Registration & Login via Email and Password
- Multi-User Account support
- Multi-Language support (currently translations for English and German)
- Optional E-Mail Verification (Token Strength Configurable)
- Forgot-Password Feature
- Reset-Password Feature
- All debatable variables and configurations are easily configurable from the ``Configuration`` class 
- Configurable mandatory password length (default is 8 character)
- Configurable, minimum password charset (lowercase and uppercase letters, numbers and special characters)
- Very easy usage through PHP OOP

## Default Security Benefits
 - Hashed & salted passwords (```PASSWORD_DEFAULT```'s ```BCRYPT``` algorithm) - passwords are NEVER processed in plain text
 - Database connection run using PDO prepared statements and in use of ``PDO::ERRMODE_EXCEPTION`` - no mysqli usage
 - All user inputs are properly serialized using ``htmlentities()``

## Usage
### Loading & Creating a SLS instance
```php
require 'SLS.php';

$sls = SLS::getInstance();
```
### Logging In User
The following code will log in the user, initialize his session and redirect him to the confidential page `confidential.php`
```php
$user = $sls->loginUser($username,$plainTextPassword);
if ($user != null) {
    session_start();
    $_SESSION['id'] = $user->getId();
    header('Location: confidential.php');
} else {
    $error = "E-Mail/Password wrong.";
}
```
```loginUser()``` returns a ```User``` Object
### Registering User
```php
$user = $sls->registerUser($email, $plainTextPassword);
```
```registerUser()``` returns a ```User``` Object

A user is automatically logged in after a successful registration.
### Obtain user ID and E-Mail address
The getter methods ```SLS::getID()``` and ```SLS::getEmail()``` return the ```Users```'s ID and E-Mail address
```php
$id = $user->getID();
$email = $user->Email();
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
Secure Login System is free for personal use. Change and modify it as you wish.
If you like to use Secure Login System commercially, please drop me an email at dominik@muensterer.net and we'll find a solution.
