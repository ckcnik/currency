<?php
require_once 'gate.php';
require_once (__ROOT__ . '/app/DbConnect.php');

use app\MySqlDbConnect;

class MySqlDbConnectTest extends PHPUnit_Framework_TestCase {

    public function testConnectDb() {
        global $settings;
        $obj = new MySqlDbConnect($settings['db']['test']['host'], $settings['db']['test']['user'], $settings['db']['test']['pass'],
            $settings['db']['test']['name']);

        $this->assertInstanceOf('mysqli', $obj->connect());
    }
}