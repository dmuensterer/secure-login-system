<?php
    include '../db.php';
    $db = DB::getInstance();


    if (isset($_GET['verification'])) {
        $verification = $_GET['verification'];
        $user = $db->verifyPasswordReset($verification);
        if ($user == null) {
            $error = "Code abgelaufen. Bitte erneut anfordern";
        } else {
            if(isset($_POST['password']) && isset($_POST['rpassword'])) {
                $password = $_POST['password'];
                $rpassword = $_POST['rpassword'];

                if ($password == $rpassword) {
                    //Set new password
                    $db->resetPassword($user->getId(), $password);
                    //Make verification code unvalid
                    $db->invalidatePasswordVerification($user->getId());
                    //Send verification link via email

                    $info = "Passwort erfolgreich geändert.";
                } else {
                    $error = "Passwörter müssen übereinstimmen.";
                }
            }
        }
    } else {
        $error = "Code abgelaufen. Bitte erneut anfordern";
    }

?>

<html>
    <head>
        <title>
            Tettnang Kauft Online - Passwort zurücksetzen
        </title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.3.5/tailwind.min.css">
    </head>
    <body class="bg-gray-100 flex h-screen">
        <div class="w-full m-auto max-w-xs">
        <form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <p class="block text-gray-700 text-md font-bold mb-3">
                    Passwort zurücksetzen
                </p>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Neues Passwort
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 mb-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="password" id="password" type="password" placeholder="************">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Passwort wiederholen
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 mb-2 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="rpassword" id="password" type="password" placeholder="************">
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
                    Passwort zurücksetzen
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
                <a href="../login.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Zum Login
                </a>
            </span>


        </form>
        <p class="text-center text-gray-500 text-xs">
            &copy;2020 muensterer.net All rights reserved.
        </p>
        </div>
    </body>
</html>
