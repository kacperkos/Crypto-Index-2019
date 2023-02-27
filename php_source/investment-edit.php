<?php
session_start();
error_reporting(0);
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header('Location: index.php');
    exit();
}
require('lib/db.php');
require('lib/investment.php');
if(isset($_GET['id']) && $_GET['id'] != null) {
    $investment = new toUpdateInvestment();
    $investment->getFromDatabase($_GET['id']);
} elseif(isset($_POST['id']) && $_POST['id'] != null) {
    $investment = new toUpdateInvestment();
    $investment->id = $_POST['id'];
    $investment->crypto_id = $_POST['kryptowaluta'];
    $investment->quantity = $_POST['ilosc'];
    $investment->market = $_POST['gielda'];
    $investment->exchange_pln = $_POST['kurs_pln'];
    $investment->value_pln = $_POST['wartosc_pln'];
    $investment->exchange_usdpln = $_POST['kurs_usdpln'];
    $investment->exchange_usd = $_POST['kurs_usd'];
    $investment->value_usd = $_POST['wartosc_usd'];
    $investment->comments = $_POST['komentarze'];
    $investment->updateInDatabase();
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
exit();
}
?>
<html lang="pl">
    <head>
        <title>Crypto-Index</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <script src="js/functions.js"></script>
    </head>
    <body>
        <p><b>Edit investment</b></p>
        <form method="POST" action="investment-edit.php">
            <input type="number" name="id" value="<?php echo $investment->id; ?>" style="display: none;" />
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
    echo '<option ';
    if($markets[$i] === $investment->market)
        echo 'selected';
    echo ' value="'.$markets[$i].'">'.$markets[$i].'</option>';
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
    echo '<option ';
    if($crypto['id'] == $investment->crypto_id)
        echo 'selected';
    echo ' value="'.$crypto['id'].'">'.$crypto['name'].' ('.$crypto['symbol'].')</option>';
}
?>	
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Quantity:</td>
                    <td><input required type="number" min="0.00000001" step="0.00000001" name="ilosc" onchange="calculateValues();" value="<?php echo $investment->quantity; ?>" /> pcs</td>
                </tr>
                <tr>
                    <td>Cryptocurrency price PLN:</td>
                    <td><input required type="number" min="0.0001" step="0.0001" name="kurs_pln" onchange="calculateValues();" value="<?php echo $investment->exchange_pln; ?>" /></td>
                </tr>
                <tr>
                    <td>Total value PLN:</td>
                    <td>
                        <input required type="number" step="0.01" name="wartosc_pln_show" disabled value="<?php echo $investment->value_pln; ?>" />
                        <input required type="number" step="0.01" name="wartosc_pln" hidden value="<?php echo $investment->value_pln; ?>" />
                    </td>
                </tr>
                <tr>
                    <td>Price of $1 in the moment of investment: </td>
                    <td><input required type="number" step="0.0001" min="0.0001" name="kurs_usdpln" onchange="calculateValues();" value="<?php echo $investment->exchange_usdpln; ?>"> PLN</td>
                </tr>
                <tr>
                    <td>Cryptocurrency price USD:</td>
                    <td>
                        <input required type="number" step="0.0001" name="kurs_usd_show" disabled value="<?php echo $investment->exchange_usd; ?>" />
                        <input required type="number" step="0.0001" name="kurs_usd" hidden value="<?php echo $investment->exchange_usd; ?>" />
                    </td>
                </tr>
                <tr>
                    <td>Total value USD:</td>
                    <td>
                        <input required type="number" step="0.01" name="wartosc_usd_show" disabled value="<?php echo $investment->value_usd; ?>" />
                        <input required type="number" step="0.01" name="wartosc_usd" hidden value="<?php echo $investment->value_usd; ?>" />
                    </td>
                </tr>
                <tr>
                    <td>Wallet name:</td>
                    <td><input type="text" size="43" name="komentarze" value="<?php echo $investment->comments; ?>" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Save changes" /></td>
                </tr>
            </table>
        </form>
        <p><a href="index-logged.php">Back to main page</a></p>
    </body>
</html>