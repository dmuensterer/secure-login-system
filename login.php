<?php
    require_once 'SLS.php';
    $sls = SLS::getInstance();

    //Redirect if already logged in
    if ($sls->isUserLoggedIn()) {
        header('Location: dashboard.php');
        die();
    }


    if (
        isset($_POST['email']) &&
        isset($_POST['password'])
    ) {
        //Initialize and serialize user input
        $email = htmlentities($_POST['email']);
        $plain_password = htmlentities($_POST['password']);

        $login = $sls->loginUserWithEmailAndPassword($email, $plain_password);

        if (!is_a($login, 'Err')) {
            header('Location: dashboard.php');
        } else {
            $error = $login->getDescription();
        }

    }

?>

<html>
<head>
    <title>
        Tettnang Kauft Online - Login
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.3.5/tailwind.min.css">
</head>

<body class="bg-gray-100 flex h-screen">
    <div class="w-full m-auto max-w-xs">
    <form method="POST" action="login.php" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
        <p class="block text-gray-700 text-md font-bold mb-3">
            Tettnang Kauft Online - Login
        </p>
        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
            E-Mail
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="email" id="email" type="text" placeholder="E-Mail">
        </div>
        <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
            Passwort
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="password" id="password" type="password" placeholder="******************">
        <?php
            if (isset($error)) {
                echo "
                    <div class=\"text-red-500 text-sm py-2\">
                " . $error . "
                    </div>
                ";
            }
        ?>
        </div>
        <div class="flex items-center justify-between">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            Einloggen
        </button>
        <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="resetPassword/send-verification.php">
            Passwort vergessen?
        </a>
        </div>
        <span class="text-sm mx-auto block my-4">
            Noch keinen Account?
            <a href="register.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Jetzt registrieren
            </a>
        </span>

    </form>
    <p class="text-center text-gray-500 text-xs">
        &copy;2020 muensterer.net All rights reserved.
    </p>
    </div>
</body>
</html>