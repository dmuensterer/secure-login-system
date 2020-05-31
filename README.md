# Secure Login System - A simple PHP/MySQL OOP login system with focus on security

## Class features
- User Registration & Login via Email and Password
- Optional E-Mail Verification
- Forgot-Password Feature
- Reset-Password Feature
- Hashed & salted passwords (```PASSWORD_DEFAULT```'s ```BCRYPT``` algorithm) - passwords are NEVER processed in plain text
- configurable, mandatory password length (default is 8 character)
- configurable, mandatory password charset (default is at least one lowercase and uppercase letter, number and special character)
- Very easy usage through PHP OOP

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
