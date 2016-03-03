<?php
require_once 'CurrencyUpdate.php';

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'root';

$currency = new CurrencyUpdate($dbhost, $dbuser, $dbpass);
$currency->update();