<?php
session_start();
error_reporting(0);
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header('Location: index.php');
    exit();
}
require('lib/db.php');
// Function to fetch data from CoinMarketCap.com
function getCMCdata($start) {
    require('lib/config.php');
    $start .= '';
    $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
    $parameters = [
      'start' => $start,
      'limit' => '5000',
      'convert' => 'USD'
    ];
    $headers = [
      'Accepts: application/json',
      'X-CMC_PRO_API_KEY: '.$CMC_api_key
    ];
    $qs = http_build_query($parameters); // Query string encode the parameters
    $request = "{$url}?{$qs}"; // Create the request URL
    $curl = curl_init(); // Get cURL resource
    // Set cURL options
    curl_setopt_array($curl, array(
      CURLOPT_URL => $request,            // set the request URL
      CURLOPT_HTTPHEADER => $headers,     // set the headers 
      CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
    ));
    $response = curl_exec($curl); // Send the request, save the response
    if($response === false) {
        echo '<b>cURL error:</b> '.var_export(curl_error($curl), true)."<br /><br /><b>Query:</b> ".var_export(curl_getinfo($curl), true);
        exit();
    } else {
        $response = json_decode($response); // Decode json response
        curl_close($curl); // Close request
        return $response; // Return response
    }
}
// Function to save TIMESTAMP of last CMC data update
function saveCMCtimestamp($timestamp) {
    $file = 'txt/cmc-time.txt';
    file_put_contents($file, $timestamp);
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
<?php
$crypto_updated = 0;
$crypto_updated_errors = 0;
$crypto_added = 0;
$crypto_added_errors = 0;
$start = 1;
while(true) {
    $CMC_data = null;
    $CMC_data = getCMCdata($start);
    if($start > $CMC_data->status->total_count) {
        break;
    }
    saveCMCtimestamp($CMC_data->status->timestamp);
    $CMC_data = $CMC_data->data;
    // Procedure of saving data from CoinMarketCap.com to database
    foreach($CMC_data as $CMC_crypto) {
        if($CMC_crypto->quote->USD->percent_change_1h == null) {
            $CMC_crypto->quote->USD->percent_change_1h = 'null';
        }
        if($CMC_crypto->quote->USD->percent_change_24h == null) {
            $CMC_crypto->quote->USD->percent_change_24h = 'null';
        }
        if($CMC_crypto->quote->USD->percent_change_7d == null) {
            $CMC_crypto->quote->USD->percent_change_7d = 'null';
        }
        $CMC_crypto->name = str_replace("'", "\'", $CMC_crypto->name);
        // Check if that cryptocurrenct exsists in the database
        $result = $mysqli->query('SELECT * FROM crypto WHERE cmc_id = '.$CMC_crypto->id.' LIMIT 1');
        if($result->num_rows == 0) {
            // Not exsists; save it as the new one
            $query = 'INSERT INTO crypto (
                cmc_id, 
                name, 
                symbol, 
                price, 
                percent_change_1h, 
                percent_change_24h, 
                percent_change_7d)
                VALUES (
                '.$CMC_crypto->id.', 
                \''.$CMC_crypto->name.'\', 
                \''.$CMC_crypto->symbol.'\', 
                '.$CMC_crypto->quote->USD->price.', 
                '.$CMC_crypto->quote->USD->percent_change_1h.', 
                '.$CMC_crypto->quote->USD->percent_change_24h.', 
                '.$CMC_crypto->quote->USD->percent_change_7d.'
                )';
            if(!$mysqli->query($query)) {
                // Saving error
                $crypto_added_errors += 1;
                echo '<p><span class="red">'.$query.'</span></p>';
            } else {
                // Saved correctly
                $crypto_added  += 1;
            }		
        } else {
            // Exists; update
            $query = 'UPDATE crypto SET
                name = \''.$CMC_crypto->name.'\',
                symbol = \''.$CMC_crypto->symbol.'\',
                price = '.$CMC_crypto->quote->USD->price.', 
                percent_change_1h = '.$CMC_crypto->quote->USD->percent_change_1h.',
                percent_change_24h = '.$CMC_crypto->quote->USD->percent_change_24h.',
                percent_change_7d = '.$CMC_crypto->quote->USD->percent_change_7d.'
                WHERE symbol = \''.$CMC_crypto->symbol.'\' 
                AND cmc_id = '.$CMC_crypto->id.' 
                ';
            if(!$mysqli->query($query)) {
                // Update error
                $crypto_updated_errors += 1;
                echo '<p><span class="red">'.$query.'</span></p>';  
            } else {
                // Updated correctly
                $crypto_updated += 1;
            }
        }
    }
    $start += 5000; // Moving cURL request start point
}
echo '<p>'.$crypto_added.' new cryptocurrencies added ('.$crypto_added_errors.' error(s))</p>';
echo '<p>'.$crypto_updated.' cryptocurrencies updated ('.$crypto_updated_errors.' error(s))</p>';
?>
        <p><a href="index-logged.php">Back to main page</a></p>
    </body>
</html>