<?php
    require_once 'SLS.php';

    $sls = SLS::getInstance();

    $user = $sls->registerAccount("test123@123.com", "test");

    if($user) {
        echo "Registrierung erfolgreich!";
        echo "E-Mail: " . $user->getEmail() . "<br>";
        echo "Account Id: " . $user->getAccountId() . "<br>";
        echo "User Id: " . $user->getUserId() . "<br>";
    } else {
        echo "Registrierung nicht erfolgreich, da die E-Mail Adresse schon benutzt wird.";
    }
/*
    if($sls->LoginUser("dominik@muensterer.net", "test")) {
        echo "Eingeloggt";
    } else {
        echo "Username/Password wrong.";
    }
*/
    //$sls->logoutUser();
?>