<?php
require_once 'vendor/autoload.php';
require_once 'CurrencyUpdate.php';


class CurrencyUpdateTest extends PHPUnit_Framework_TestCase {

    var $dbhost = 'localhost';
    var $dbuser = 'root';
    var $dbpass = 'root';


    public function testConnectDb() {

        $class = new ReflectionClass('CurrencyUpdate');
        $method = $class->getMethod('connect');
        $method->setAccessible(true);
        $obj = new CurrencyUpdate($this->dbhost, $this->dbuser, $this->dbpass);

        $result = $method->invoke($obj, $this->dbhost, $this->dbuser, $this->dbpass);
        $this->assertInstanceOf('mysqli', $result);
    }

    public function testGetRates() {

        $class = new ReflectionClass('CurrencyUpdate');
        $method = $class->getMethod('get_rates');
        $method->setAccessible(true);
        $obj = new CurrencyUpdate($this->dbhost, $this->dbuser, $this->dbpass);

        $result = $method->invoke($obj);
        $this->assertNotEmpty($result);
    }

    public function testPrepareRates() {

        $class = new ReflectionClass('CurrencyUpdate');
        $method = $class->getMethod('prepareRates');
        $method->setAccessible(true);
        $obj = new CurrencyUpdate($this->dbhost, $this->dbuser, $this->dbpass);

        $r1 = (object) ['symbol' => 'USD', 'rate' => 3];
        $r2 = (object) ['symbol' => 'RUR', 'rate' => 5];
        $r3 = (object) ['symbol' => 'EUR', 'rate' => 1];
        $result = $method->invoke($obj, [$r1, $r2, $r3]);
        $this->assertEquals(count($result), 3);
    }
}