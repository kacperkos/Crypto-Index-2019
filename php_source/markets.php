<?php
session_start();
error_reporting(0);
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header('Location: index.php');
    exit();
}
if(isset($_POST['gieldy']) && $_POST['gieldy'] != null ) {
    $file = 'txt/markets.txt';
    file_put_contents($file, $_POST['gieldy']);	
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
        <p>Current exchanges list: <b><?php $file = 'txt/markets.txt'; echo file_get_contents($file);?></b></p>
        <form method="POST" action="markets.php">
            <input type="text" autofocus name="gieldy" size="43" value="<?php $file = 'txt/markets.txt'; echo file_get_contents($file);?>" />
            <input type="submit" value="Save changes" />
        </form>
        <p class="hint">Hint: Enter exchanges' names delimited with commas</p>
        <p><a href="index-logged.php">Back to main page</a></p>
    </body>
</html>