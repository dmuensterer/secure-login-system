<?php

include 'product.php';
include 'db.php';

session_start();

if (!$sls->isUserLoggedIn()) {
    header('Location: login.php');
    die();
}

//Check if get data exists
if (isset($_GET['removeProduct'])) {
    $id = $_GET['removeProduct'];
    $db = DB::getInstance();
    //Remove Product
    $db->removeProduct($id);
}

//Check if POST data exists
if (
    isset($_POST['title']) &&
    isset($_POST['description']) &&
    isset($_POST['price']) &&
    isset($_FILES["image"])
) {

    //Get user input
    $input_title = $_POST['title'];
    $input_description = $_POST['description'];
    $input_price = $_POST['price'];
    $image = $_FILES["image"];

    //Serialize user input
    $title = htmlentities($input_title);
    $description = htmlentities($input_description);
    $price = htmlentities($input_price);

    //Replace commas with dots and remove spaces, then check if number
    $price = str_replace(',', '.', $price);
    $price = str_replace(' ', '', $price);

    if (!is_numeric($price)) {
        die('Entered price is not a valid number');
    } else {
        $price = (float) $price;
    }

    //Generate random name for image
    $filename = bin2hex(random_bytes(8));

    //Get image data
    $target_dir = "uploads/";
    $imageFileType = strtolower(pathinfo(basename($image["name"]), PATHINFO_EXTENSION));
    $target_file = $target_dir . $filename . "." . $imageFileType;


    //Check if file already exists
    if (file_exists($target_file)) {
        die("File already exists.");
    }

    //Check image file size
    if ($image["size"] > 5000000) {
        die("Sorry, your file is too large.");
    }

    //Check if file is image
    if (!(getimagesize($image["tmp_name"]) !== false)) {
        die('Uploaded file is not an image');
    }

    //Check if image extension is jpg, png, jpeg or gif
    if (
        $imageFileType != "jpg" &&
        $imageFileType != "png" &&
        $imageFileType != "jpeg" &&
        $imageFileType != "gif"
    ) {
        die('Please only upload jpg, png, jpeg or gif files.');
    }

    //Try uploading image
    if (!move_uploaded_file($image["tmp_name"], $target_file)) {
        die("Sorry, there was an error uploading your file.");
    }

    $db = DB::getInstance();
    $db->addProduct($title, $description, $price, $target_file);
}
    $db = DB::getInstance();
    $products = $db->getProductsOfUser($_SESSION['id']);

?>

<html>

<head>
    <title>
        Custom Product Upload
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.3.5/tailwind.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" integrity="sha256-KzZiKy0DWYsnwMF+X1DvQngQ2/FxF7MF3Ff72XcpuPs=" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-6 pt-8">
        <div class="w-full">
            <div class="flex flex-wrap justify-between">
                <button class="lg:hidden text-spaceblue-500 flex cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="w-8 h-8">
                        <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path>
                    </svg>
                </button>
                <nav class="hidden lg:flex flex-1 items-center justify-between">
                    <div class="flex flex-1"><a href="#" class="mx-4 text-base text-spaceblue-400 font-medium hover:text-spaceblue-500">Home</a> <a href="#" class="mx-4 text-base text-spaceblue-400 font-medium hover:text-spaceblue-500">Pricing</a> <a href="https://feedr.dk/contact" class="mx-4 text-base text-spaceblue-400 font-medium hover:text-spaceblue-500">Contact</a> <a href="https://feedr.dk/help" class="mx-4 text-base text-spaceblue-400 font-medium hover:text-spaceblue-500">Help</a> <a href="#" class="mx-4 text-base text-spaceblue-400 font-medium hover:text-spaceblue-500">Blog</a></div>
                    <div class="flex items-center">
                        <a href="logout.php" class="mx-4 text-base text-spaceblue-400 font-medium hover:text-spaceblue-400">
                            Ausloggen
                        </a>
                        <!--<a href="logout.php" class="px-6 py-3 ml-4 rounded text-base text-white font-semibold bg-blue-400 hover:bg-blue-300">
                            Ausloggen
                        </a>-->
                    </div>
                </nav>
                <nav class="w-full py-4 lg:hidden" style="display: none;"><a href="#" class="block px-4 py-4 text-base text-spaceblue-400 font-medium hover:underline">Home</a> <a href="#" class="block px-4 py-4 text-base text-spaceblue-400 font-medium hover:underline">Pricing</a> <a href="https://feedr.dk/contact" class="block px-4 py-4 text-base text-spaceblue-400 font-medium hover:underline">Contact</a> <a href="https://feedr.dk/help" class="block px-4 py-4 text-base text-spaceblue-400 font-medium hover:underline">Help</a> <a href="#" class="block px-4 py-4 text-base text-spaceblue-400 font-medium hover:underline">Blog</a> <a href="https://feedr.dk/login" class="block px-4 py-4 text-base text-spaceblue-400 font-medium hover:underline">Log in</a> <a href="https://feedr.dk/register" class="block px-4 py-4 text-base text-spaceblue-400 font-medium hover:underline">Get started</a>
                    <div class="flex justify-center mt-3">
                        <a rel="alternate" hreflang="da" href="#"><img src="/images/flags/da.svg" alt="Dansk" class="w-10 mx-4"></a>
                        <a rel="alternate" hreflang="en" href="#"><img src="/images/flags/en.svg" alt="English" class="w-10 mx-4"></a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <div class="container mt-20 mx-auto">
        <p class="text-xl">Artikel</p>
        <div class="flex flex-wrap">
            <form enctype="multipart/form-data" action="form.php" method="POST">
                <div class="m-4 scale-105 w-64 rounded overflow-hidden shadow-lg card">
                    <div class="w-64 h-64 flex bg-blue-100">
                        <button type="button" class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-3 m-auto border-b-4 border-blue-700 hover:border-blue-500 rounded" onclick="clickFileUpload()">
                            Hochladen
                        </button>
                        <input type="file" name="image" id="fileUpload" class="p-2 custom-file-input" value="Bild auswählen" hidden="hidden">
                    </div>
                    <div class="px-6 py-4">
                        <div class="font-bold text-base mb-2">
                            <input type="text" name="title" class="shadow appearance-none border rounded p-2 w-full text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Produktname">
                        </div>
                        <p class="text-gray-700 text-base">
                            <input type="text" name="description" class="shadow appearance-none border rounded p-2 w-full text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Produktbeschreibung">
                        </p>
                    </div>
                    <div class="inline-flex flex-row justify-between text-sm inline-block font-bold px-6 py-4 text-gray-800">
                        <span>
                            <input type="text" name="price" class="shadow appearance-none border rounded p-2 w-16 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Preis"> €
                        </span>
                    </div>
                    <input type="submit" class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-3 mx-2 border-b-4 border-blue-700 hover:border-blue-500 rounded">

                    <!--<div class="px-6 py-4">
                        <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">#photography</span>
                        <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">#travel</span>
                        <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700">#winter</span>
                    </div>-->
                </div>
            </form>
            <?php
                foreach ($products as $product) {
                    echo "
                        <div class=\"m-4 scale-105 w-64 relative rounded overflow-hidden shadow-lg card\">
                            <div class=\"w-64 h-64\" style=\"background-image: url('" . $product->image . "'); background-size: cover;\"></div>
                            <!--<a href=\"" . $product->image . "\" target=\"_blank\"><img class=\"w-full\" src=\"" . $product->image . "\" alt=\"Sunset in the mountains\"></a>-->
                            <div class=\"px-6 py-4\">
                                <div class=\"font-bold text-xl mb-2\">" . $product->title . "</div>
                                <p class=\"text-gray-700 text-base\">
                                " . $product->description . "
                                </p>
                            </div>
                            <div class=\"flex flex-row absolute bottom-0 w-full justify-between text-sm font-bold px-6 py-4 text-gray-800\">
                                <span>" . number_format($product->price, 2, ',', '') . " €</span>
                                <a href=\"?removeProduct=" . $product->id . "\" class=\"text-sm text-red-400\"><i class=\"fas fa-trash-alt mr-1\"></i>Löschen</a>
                            </div>
                            <!--<div class=\"px-6 py-4\">
                                <span class=\"inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2\">#photography</span>
                                <span class=\"inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2\">#travel</span>
                                <span class=\"inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700\">#winter</span>
                            </div>-->
                        </div>
                    ";
                }
            ?>
        </div>
            <?php
        /*
            foreach ($products as $product) {
                echo "<tr>";

                    echo "<td class=\"text-left py-5 border-b-2 border-gray-400\">";
                    echo "<a href=\"" . htmlentities($product->image) . "\" target=\"_blank\"><img src=\"" . $product->image . "\"></a>";
                    echo "</td>";

                    echo "<td class=\"text-left py-5 border-b-2 border-gray-400\">";
                    echo htmlentities($product->title);
                    echo "</td>";

                    echo "<td class=\"text-left py-5 border-b-2 border-gray-400\">";
                    echo htmlentities($product->description);
                    echo "</td>";

                    echo "<td class=\"text-left py-5 border-b-2 border-gray-400\">";
                    echo htmlentities($product->price);
                    echo "</td>";

                    echo "<td class=\"text-left py-5 border-b-2 border-gray-400\">";
                    echo "<a href=\"?removeProduct=" . htmlentities($product->id) . "\" class=\"underline text-blue-600 hover:text-blue-800\">Remove Product</a>";
                    echo "</td>";

                echo "</tr>";
            }*/
                ?>
    </div>
    <script>
        function clickFileUpload() {
            document.getElementById('fileUpload').click();
        }
    </script>
</body>

</html>