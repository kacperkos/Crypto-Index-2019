<?php
session_start();
error_reporting(0);
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header('Location: index.php');
    exit();
}
if(isset($_POST['kurs']) && $_POST['kurs'] != null ) {
    $file = 'txt/usdpln.txt';
    file_put_contents($file, $_POST['kurs']);		
}
?>
<html lang="en">
    <head>
        <title>Crypto-Index</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
    </head>
    <body>
        <p>Current price of $1: <b><?php $file = 'txt/usdpln.txt'; echo file_get_contents($file);?> PLN</b></p>
        <form method="POST" action="usdpln-update.php">
            <input autofocus type="number" min="0.0001" step="0.0001" name="kurs" required value="<?php $file = 'txt/usdpln.txt'; echo file_get_contents($file);?>" />
            <input type="submit" value="Save changes" />
        </form>
        <p><a href="index-logged.php">Back to main page</a></p>
    </body>
</html>