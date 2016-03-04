<?php
require_once 'gate.php';
require_once (__ROOT__ . '/app/Currency.php');
require_once (__ROOT__ . '/app/DbConnect.php');

use app\Currency;
use app\MySqlDbConnect;

class CurrencyTest extends PHPUnit_Extensions_Database_TestCase {

    public function getConnection() {
        global $settings;
        $pdo = new PDO("mysql:host={$settings['db']['test']['host']};dbname={$settings['db']['test']['name']}", $settings['db']['test']['user'],
            $settings['db']['test']['pass']);
        return $this->createDefaultDBConnection($pdo, 'testdb');
    }

    public function getDataSet() {
        return $this->createFlatXMLDataSet(dirname(__FILE__).'/_files/currency.xml');
    }

    public function testUpdate() {
        global $settings;
        $connect = new MySqlDbConnect($settings['db']['test']['host'], $settings['db']['test']['user'], $settings['db']['test']['pass'],
            $settings['db']['test']['name']);

        $currency = new Currency($connect);
        $currency->update();
        $this->assertTrue($currency->update());
    }
}
