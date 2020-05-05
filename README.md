# Secure Login System - A simple PHP/MySQL OOP login system with focus on security

## Class features
- User Registration & Login via Email and Password
- Optional E-Mail Verification
- Forgot-Password Feature
- Reset-Password Feature
- Hashed & salted passwords (```PASSWORD_DEFAULT```'s ```BCRYPT``` algorithm) - passwords are NEVER processed in plain text
- Very easy usage through PHP OOP

## Usage
### Loading & Creating a SLS instance
```
require 'SLS.php'

$sls = SLS::getInstance();
```
### Logging In User
The following code will log in the user, initialize his session and redirect him to the confidential page `confidential.php`
```
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
```
$user = $sls->registerUser($email, $plainTextassword);
```
```registerUser()``` returns a ```User``` Object
### Obtain user ID and E-Mail address
The getter functions ```SLS::getID()``` and ```SLS::getEmail()``` return the ```Users```'s ID and E-Mail address
```
$id = $user->getID();
$email = $user->Email();
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License
Secure Login System is free for personal use. Change and modify it as you wish.
If you like to use Secure Login System commercially, please drop me an email at dominik@muensterer.net and we'll find a solution.
