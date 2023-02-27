<?php
session_start();
error_reporting(0);
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header('Location: index-logged.php');
    exit();
}
require('lib/config.php');
?>
<html lang="en">
    <head>
        <title>Test title</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <script src="js/functions.js"></script>
    </head>
    <body>
<?php
// Very strange logging procedure, don't you think?
//
// Well, this tool was developed for single user (for me) so all you need
// is to set up the password in lib/config.php file (don't forget to MD5 it!)
//
// Ohh... and the logging page looks like programming playground on purpose ^^
// Tool was originally placed on subdomain 'test' to look like code under construction
//
// $_POST can be accessed without filtering here,
// because passphrase checking don't involving accessing database.
if(isset($_POST['test_field']) && $_POST['test_field'] !== null) {
    if(md5($_POST['test_field']) == $md5password) {
        $_SESSION['loggedin'] = true;
        header('Location: index-logged.php');
        exit();
    } else {
        // Script below shows casual viewers that this website acts strange
        // so it's probably some programming test script
        $test_field = filter_var($_POST['test_field'], FILTER_SANITIZE_SPECIAL_CHARS);
        if($test_field != "") {
            $test_field = "Value entered: ".$test_field;
        }
        echo '<script>alert("JavaScript Alert demo! \\n'.$test_field.'");</script>';
    }
}
?>
        <form method="POST" action="index.php">
            <input type="text" value="Enter something..." name="test_field" onclick="resetTestFieldValue();" />
            <input type="submit" value="and click me" />
        </form>
    </body>
</html>