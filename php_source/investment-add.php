<?php
session_start();
error_reporting(0);
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header('Location: index.php');
    exit();
}
require('lib/db.php');
require('lib/investment.php');
if(isset($_POST['gielda']) && $_POST['gielda'] != null) {
    $investment = new newInvestment();
    $investment->crypto_id = $_POST['kryptowaluta'];
    $investment->quantity = $_POST['ilosc'];
    $investment->market = $_POST['gielda'];
    $investment->exchange_pln = $_POST['kurs_pln'];
    $investment->value_pln = $_POST['wartosc_pln'];
    $investment->exchange_usdpln = $_POST['kurs_usdpln'];
    $investment->exchange_usd = $_POST['kurs_usd'];
    $investment->value_usd = $_POST['wartosc_usd'];
    $investment->comments = $_POST['komentarze'];
    $investment->saveToDatabase();
}
?>
<html lang="en">
    <head>
        <title>Crypto-Index</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script src="js/functions.js"></script>
    </head>
    <body>
        <p><b>Add 
<?php
if(isset($investment)) {
    echo ' another ';
}
?>investment</b></p>
        <form method="POST" action="investment-add.php">
            <table>
                <tr>
                    <td>Exchange:</td>
                    <td>
                        <select required name="gielda">
                            <option></option>
<?php
$file = 'txt/markets.txt';
$markets = file_get_contents($file);
$markets = explode(',', $markets);
for($i=0; $i<sizeof($markets); $i++) {
    echo '<option value="'.$markets[$i].'">'.$markets[$i].'</option>';
}
?>
			</select>
                    </td>
		</tr>
                <tr>
                    <td>Cryptocurrency:</td>
                    <td>
                        <select required name="kryptowaluta">
                            <option></option>
<?php
$query = 'SELECT * FROM crypto ORDER BY name';
$result = $mysqli->query($query);
foreach($result as $crypto) {
    echo '<option value="'.$crypto['id'].'">'.$crypto['name'].' ('.$crypto['symbol'].')</option>';
}
?>	
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Quantity:</td>
                    <td><input required type="number" min="0.00000001" step="0.00000001" name="ilosc" onchange="calculateValues();" /> pcs</td>
                </tr>
                <tr>
                    <td>Cryptocurrency price PLN:</td>
                    <td><input required type="number" min="0.0001" step="0.0001" name="kurs_pln" onchange="calculateValues();" /></td>
                </tr>
                <tr>
                    <td>Total value PLN:</td>
                    <td>
                        <input required type="number" step="0.01" name="wartosc_pln_show" disabled />
                        <input required type="number" step="0.01" name="wartosc_pln" hidden />
                    </td>
                </tr>
                <tr>
                    <td>Price of $1 in the moment of investment:</td>
                    <td><input required type="number" step="0.0001" min="0.0001" name="kurs_usdpln" onchange="calculateValues();"> PLN</td>
                </tr>
                <tr>
                    <td>Cryptocurrency price USD:</td>
                    <td>
                        <input required type="number" step="0.0001" name="kurs_usd_show" disabled />
                        <input required type="number" step="0.0001" name="kurs_usd" hidden />
                    </td>
                </tr>
                <tr>
                    <td>Total value USD:</td>
                    <td>
                        <input required type="number" step="0.01" name="wartosc_usd_show" disabled />
                        <input required type="number" step="0.01" name="wartosc_usd" hidden />
                    </td>
                </tr>
                <tr>
                    <td>Wallet name:</td>
                    <td><input type="text" size="43" name="komentarze" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Add investment" /></td>
                </tr>
            </table>
        </form>
        <p><a href="index-logged.php">Back to main page</a></p>
    </body>
</html>