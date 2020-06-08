<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require '../mail/Exception.php';
    require '../mail/PHPMailer.php';

    include '../db.php';
    $db = DB::getInstance();

    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $id = $db->checkEmail($email);
        if ($id != null) {
            $token = bin2hex(random_bytes(32));
            //Validity of code in minutes
            $validity = 1200;
            $db->setPasswordVerificationToken($token, $id, $validity);
            $link = "<a href=\"https://muensterer.net/custom-product-upload/resetPassword/reset-password.php?verification=" . $token . "\">Passwort zurücksetzen</a>";

            $mail = new PHPMailer(true);
            try {

                $mail->CharSet ="UTF-8";

                $mail->setFrom('tettnang@kauft-online.de', 'Tettnang Kauft Online');
                $mail->addAddress($email);     // Add a recipient
                $mail->addReplyTo('tettnang@kauft-online.de', 'Tettnang Kauft Online');

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Passwort zurücksetzen';
                $mail->Body    = 'Guten Tag,<br> bitte klicken Sie zum zurücksetzen Ihres Passworts auf folgenden Link: ' . $link . '<br><br>Falls Sie Ihr Passwort nicht zurücksetzen wollten, können Sie diese E-Mail ignorieren.<br><br>Mit freundlichen Grüßen<br>Ihr tettnang.kauft-online.de - Team';
                $mail->AltBody = 'Guten Tag, bitte rufen Sie zum zurücksetzen Ihres Passworts folgenden Link im Browser auf: ' . $link;

                $mail->send();
                $info = "Passwort erfolgreich geändert.";

            } catch (Exception $e) {
                $error = "E-Mail konnte nicht gesendet werden. Bitte versuchen Sie es später erneut.";
            }


            $info = "E-Mail erfolgreich gesendet.";
        } else {
            $error = "E-Mail Adresse nicht bekannt.";
        }
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
        <form method="POST" action="send-verification.php" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <p class="block text-gray-700 text-md font-bold mb-3">
                    Passwort zurücksetzen
                </p>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    E-Mail Adresse
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="email" id="email" type="text" placeholder="E-Mail">

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