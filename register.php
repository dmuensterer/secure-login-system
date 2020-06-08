<?php
error_reporting(E_ALL);

require_once 'Config.php';
require_once 'SLS.php';

session_start();
$sls = SLS::getInstance();
$l = new Language;
$language = $l->getCurrentLanguage();
$conf = Configuration::getInstance();

//Redirect if already logged in
if (isset($_SESSION['id'])) {
    header('Location: form.php');
}

if (isset($_GET['verification'])) {
    $verification = htmlentities($_GET['verification']);
    $user = $sls->verifyEmail($verification);

    if (is_a($user, 'Err')) {
        $error = $user->getDescription();
    } else {
        $sls->loginUserWithId($user->getUserId());
        header('Location: form.php');
    }
}

if (
    isset($_POST['email']) &&
    isset($_POST['password']) &&
    isset($_POST['rpassword'])
) {
    //Initialize and serialize user input
    $email = htmlentities($_POST['email']);
    $plain_password = htmlentities($_POST['password']);
    $plain_rpassword = htmlentities($_POST['rpassword']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if ($plain_password == $plain_rpassword) {

            $user = $sls->registerAccount($email, $plain_password);

            if (is_a($user, 'Err')) {
                $error = $user->getDescription();
            }

        } else {
            $error = $language->passwords_dont_match;
        }
    } else {
        $error = $language->email_invalid;
    }

    if (!isset($error)) {
        $info = "User successfully registered.";
    }

}

?>


<html>

<head>
    <title>
        Registrieren
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.3.5/tailwind.min.css">
</head>

<body class="bg-gray-100 flex h-screen">
    <div class="w-full m-auto max-w-xs">
        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="POST" action="register.php">
            <div class="mb-4">
                <p class="block text-gray-700 text-md font-bold mb-3">
                    Registrieren
                </p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    E-Mail
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="email" id="email" type="text" placeholder="Benutzername">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Passwort
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="password" id="password" type="password" placeholder="******************">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Passwort wiederholen
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name="rpassword" id="rpassword" type="password" placeholder="******************">
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
                    Registrieren
                </button>
            </div>
            <?php
                if (isset($info)) {
                    echo "
                        <div class=\"text-green-500 text-sm pt-2\">
                    " . $info . "
                        </div>
                    ";
                }
            ?>
            <span class="text-sm mx-auto block my-4">
            Schon einen Account?
            <a href="login.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Hier einloggen
            </a>
        </span>
        </form>
        <p class="text-center text-gray-500 text-xs">
            &copy;2020 muensterer.net Alle Rechte vorbehalten.
        </p>
    </div>
</body>

</html>