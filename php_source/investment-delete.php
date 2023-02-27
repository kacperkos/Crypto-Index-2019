<?php
session_start();
error_reporting(0);
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header('Location: index.php');
    exit();
}
if(isset($_GET['id']) && $_GET['id'] != null) {
    require('lib/db.php');
    $query = 'DELETE FROM invest WHERE id = '.$_GET['id'].' LIMIT 1';
    if(!$mysqli->query($query)) {
        // Deletion error
        echo '<script>alert("ERROR! INVESTMENT NOT DELETED!")</script>';
    } else {
        // Deleted correctly
        echo '<script>alert("Investment has been deleted :-(")</script>';
    }
?>
<html lang="en">
    <head>
        <title>Crypto-Index</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="refresh" content="0; url=index-logged.php" />
    </head>
    <body></body>
</html>
<?php
}