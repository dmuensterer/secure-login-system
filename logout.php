<?php
    require_once 'SLS.php';
    $sls = SLS::getInstance();

    $sls->logoutUser();


    header('Location: login.php');
?>