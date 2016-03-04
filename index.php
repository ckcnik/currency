<?php
define('__ROOT__', dirname(__FILE__));
require_once (__ROOT__ . '/app/DbConnect.php');
require_once (__ROOT__ . '/app/misc/settings.php');
require_once (__ROOT__ . '/app/Currency.php');

use app\MySqlDbConnect;
use app\Currency;

$dbSetting = $settings['db']['release'];
$connect = new MySqlDbConnect($dbSetting['host'], $dbSetting['user'], $dbSetting['pass'], $dbSetting['name']);

$currency = new Currency($connect);
 if ($currency->update() ) {
    print('Successful update rates!\n');
 }