<?php
session_start();
error_reporting(0);
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != 'true') {
    header('Location: index.php');
    exit();
}
require('lib/db.php');
require('lib/investment.php');
?>
<html lang="en">
    <head>
        <title>Crypto-Index</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/tr-hover.css" />
        <script src="js/functions.js"></script>
    </head>
    <body>
        <div id="headerBg">
            <div id="messageBox"><p class="message">Here's welcoming message!<br /><br /><a onclick="hideMessage();">CLOSE [X]</a></p></div>
            <div id="headerLeft">
                <p><a onclick="return confirm('Are you sure you want to update?')" href="cmc-update.php">Update CoinMarketCap.com data</a></p>
                <p><a href="usdpln-update.php">Change price of $1</a></p>
                <p><a href="markets.php">Exchanges list</a></p>
                <p><a href="investment-add.php">Add investment</a></p>
            </div>
            <div id="headerRight">
                <p>Data date: <b>
<?php
$file = 'txt/cmc-time.txt';
$timestamp = file_get_contents($file);
$timestamp = str_replace('T', ' @ ', $timestamp);
$timestamp = explode('.', $timestamp);
echo $timestamp[0].' (UTC-0)';
?>
		</b></p>
		<p>Current price of $1: <b>
<?php
$file = 'txt/usdpln.txt';
$current_exchange_usdpln = file_get_contents($file);
echo $current_exchange_usdpln;
?> PLN</b></p>
		<p><a onclick="return confirm('Are you sure you want to logout?')" href="logout.php">Logout</a></p>
            </div>
	</div>
	<div id="preContent"></div>
	<div id="contentBg">
            <hr />
<?php
$total_values_index = 0;
$total_values = array();
$query1 = 'SELECT DISTINCT market FROM invest ORDER BY market ASC';
$result1 = $mysqli->query($query1);
?>
            <table>
<?php
foreach($result1 as $market) {
    echo '<tr><td colspan="18"><p class="title" style="font-size: 1.2em; margin: 0px;">'.$market['market'].' investments</p></td></tr>'; // Yeah... I know
?>
                <tr class="titleLine">
                    <td>cryptocurrency name</td>
                    <td>symbol</td>
                    <td>quantity</td>
                    <td>total value PLN</td>
                    <td>price PLN</td>
                    <td>total value USD</td>
                    <td>price USD</td>
                    <td style="width: 10px; background-color: white; !important"></td>
                    <td>current price USD</td>
                    <td>current total value PLN</td>
                    <td>ROI</td>
                    <td>1h</td>
                    <td>24h</td>
                    <td>7d</td>
                    <td style="width: 10px; background-color: white; !important"></td>
                    <td>operations</td>
                    <td style="width: 10px; background-color: white; !important"></td>
                    <td>wallet name</td>
                </tr>
<?php
    $query2 = 'SELECT * FROM invest WHERE market LIKE \''.$market['market'].'\' ORDER BY id ASC';
    $result2 = $mysqli->query($query2);
    $row_color_counter = 0;
    foreach($result2 as $investment) {
        $query3 = 'SELECT * FROM crypto WHERE id = '.$investment['crypto_id'].' LIMIT 1';
        $result3 = $mysqli->query($query3);
        $crypto = $result3->fetch_assoc();
        $current_pln_value = null;
        $current_pln_value = $investment['quantity'] * $crypto['price'] * $current_exchange_usdpln;
        $roi = null;
        $roi = (($current_pln_value*100)/$investment['value_pln'])-100;
        $row_color_counter += 1;
        if($row_color_counter % 2 == 0) {
            echo '<tr class="secondLine">';
        } else {
            echo '<tr class="firstLine">';
        }
?>
                    <td><?php echo $crypto['name']; ?></td>
                    <td><?php echo $crypto['symbol']; ?></td>
                    <td><?php echo $investment['quantity']; ?></td>
                    <td><?php echo $investment['value_pln']; ?> PLN</td>
                    <td><?php echo $investment['exchange_pln']; ?> PLN</td>
                    <td>$<?php echo $investment['value_usd']; ?></td>
                    <td>$<?php echo $investment['exchange_usd']; ?></td>
                    <td style="background-color: white; !important"></td>
                    <td>$<?php echo round($crypto['price'], 4); ?></td>
                    <td><?php echo round($current_pln_value, 2); ?> PLN</td>
                    <td><?php if($roi >= 0) { echo '<span class="green">'.round($roi, 2).'%</span>'; } else { echo '<span class="red">'.round($roi, 2).'%</span>'; } ?></td>
                    <td><?php if($crypto['percent_change_1h'] >= 0) { echo '<span class="green">▲</span>'; } else { echo '<span class="red">▼</span>'; }?></td>
                    <td><?php if($crypto['percent_change_24h'] >= 0) { echo '<span class="green">&nbsp;▲</span>'; } else { echo '<span class="red">&nbsp;▼</span>'; }?></td>
                    <td><?php if($crypto['percent_change_7d'] >= 0) { echo '<span class="green">▲</span>'; } else { echo '<span class="red">▼</span>'; }?></td>
                    <td style="width: 10px; background-color: white; !important"></td>
                    <td><a href="investment-edit.php?id=<?php echo $investment['id']; ?>">edit</a> | <a style="color: red; display: none;" id="inv_del_but_id_<?php echo $investment['id']; ?>" href="investment-delete.php?id=<?php echo $investment['id']; ?>" onclick="return multiConfirm('<?php echo $investment['id']; ?>');">delete</a><a id="unlocking_but_id_<?php echo $investment['id']; ?>" style="cursor: pointer; display: inline;" onclick="unlockDeleteKey('<?php echo $investment['id']; ?>');">delete</a></td>
                    <td style="width: 10px; background-color: white; !important"></td>
                    <td><?php echo $investment['comments']; ?></td>
                </tr>
<?php
        $total_values[$total_values_index]['owner'] = $investment['comments'];
        $total_values[$total_values_index]['value'] = $current_pln_value;
        $total_values_index += 1;
    }
}
?>
        </table>
<?php
// This part of code was previously designed to deal with wallets' owners,
// but now allows user to display summary value of specific wallets
// (variables' names stayed untouched)
$total_owners = '';
if($total_values_index > 0) {
    for($i = 0; $i < $total_values_index; $i++) {
        if(strpos($total_owners, $total_values[$i]['owner']) === false) {
            if($total_owners == '') {
                $total_owners .= $total_values[$i]['owner'];
            } else {
                $total_owners .= ','.$total_values[$i]['owner'];
            }
        }
    }
}
$total_owners = explode(',', $total_owners);
$total_value_html = '<hr /><p style=\"color: grey;\"><b>Values of specific wallets</b></p> ';
$individual_sum = -1;
foreach($total_owners as $owner) {
    $individual_sum = 0;
    foreach($total_values as $invest) {
        if($invest['owner'] == $owner) {
            $individual_sum += $invest['value'];
        }
    }
    $total_value_html .= '<p>'.$owner.': <b>'.round($individual_sum, 2).' PLN</b> / <b>'.round($individual_sum/$current_exchange_usdpln, 2).' USD</b></p>';
}
if($individual_sum != -1) {
    echo '<script>document.getElementById("preContent").innerHTML = "'.$total_value_html.'";</script>';
}
?>
        </div>
        <script>checkMessageCookie();</script>
    </body>
</html>