<?php
// Enter your MySQL configuration data below
$server = 'localhost';
$user = '';
$pass = '';
$db = '';
$mysqli = new mysqli($server, $user, $pass, $db);
if($mysqli->connect_error) {
    die('<b>DB connection failed:</b> ' . $mysqli->connect_error);
}