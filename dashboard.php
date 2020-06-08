<?php
    require_once 'SLS.php';
    $sls = SLS::getInstance();

    session_start();

    if (!$sls->isUserLoggedIn()) {
        header('Location: login.php');
        die();
    }
?>

<html>
<head>
<title>
Dashboard für eingeloggte Nutzer
</title>
</head>
<body>
Geheimer Inhalt
</body>
</html>