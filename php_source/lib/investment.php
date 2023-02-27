<?php
class newInvestment {
    public $crypto_id;
    public $quantity;
    public $market;
    public $exchange_pln;
    public $value_pln;
    public $exchange_usdpln;
    public $exchange_usd;
    public $value_usd;
    public $comments;
    public function saveToDatabase() {
        require('lib/db.php');
        if($this->comments == null) {
            $this->comments = 'null';
        } else {
            $this->comments = "'".$this->comments."'";
        }
        $query = 'INSERT INTO invest (
        crypto_id, 
        quantity, 
        market, 
        exchange_pln, 
        value_pln, 
        exchange_usdpln, 
        exchange_usd, 
        value_usd, 
        comments) 
        VALUES (
        '.$this->crypto_id.', 
        '.$this->quantity.', 
        \''.$this->market.'\', 
        '.$this->exchange_pln.', 
        '.$this->value_pln.', 
        '.$this->exchange_usdpln.', 
        '.$this->exchange_usd.', 
        '.$this->value_usd.', 
        '.$this->comments.'
        )';
        if(!$mysqli->query($query)) {
            // Saving error
            echo '<script>alert("ERROR! INVESTMENT NOT ADDED!")</script>';
        } else {
            // Saved correctly
            echo '<script>alert("Investment has been added :-)")</script>';
        }
    }
}
class toUpdateInvestment extends newInvestment {
    public $id;
    public function getFromDatabase($passed_id) {
        require('lib/db.php');
        $query = 'SELECT * FROM invest WHERE id = '.$passed_id.' LIMIT 1 ';
        $result = $mysqli->query($query);
        $result = $result->fetch_assoc();
        $this->id = $result['id'];
        $this->crypto_id = $result['crypto_id'];
        $this->quantity = $result['quantity'];
        $this->market = $result['market'];
        $this->exchange_pln = $result['exchange_pln'];
        $this->value_pln = $result['value_pln'];
        $this->exchange_usdpln = $result['exchange_usdpln'];
        $this->exchange_usd = $result['exchange_usd'];
        $this->value_usd = $result['value_usd'];
        $this->comments = $result['comments'];
    }
    public function updateInDatabase() {
        require('lib/db.php');
        if($this->comments == null) {
            $this->comments = 'null';
        } else {
            $this->comments = "'".$this->comments."'";
        }
        $query = 'UPDATE invest SET 
        crypto_id = '.$this->crypto_id.', 
        quantity = '.$this->quantity.', 
        market = \''.$this->market.'\', 
        exchange_pln = '.$this->exchange_pln.', 
        value_pln = '.$this->value_pln.', 
        exchange_usdpln = '.$this->exchange_usdpln.', 
        exchange_usd = '.$this->exchange_usd.', 
        value_usd = '.$this->value_usd.', 
        comments = '.$this->comments.' 
        WHERE id = '.$this->id;
        if(!$mysqli->query($query)) {
            // Updating error
            echo '<script>alert("ERROR! INVESTMENT NOT UPDATED!")</script>';
        } else {
            // Updated correctly
            echo '<script>alert("Investment has been updated ^^")</script>';
        }
    }	
	
}