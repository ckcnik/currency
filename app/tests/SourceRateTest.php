<?php
require_once 'gate.php';
require_once (__ROOT__ . '/app/SourceRate.php');

use app\SourceRate;

class SourceRateTest extends PHPUnit_Framework_TestCase {

    public function testGetRates() {
        $obj = new SourceRate();
        $this->assertNotEmpty($obj->rates);
    }

    public function testGetFullPath() {
        $method = new ReflectionMethod(
            'app\SourceRate', 'getFullPath'
        );

        $method->setAccessible(true);
        $obj = new SourceRate();
        $this->assertFileExists($method->invoke($obj, app\LOCAL_SOURCE));
    }

    /**
     * @dataProvider providerPrepareRates
     */
    public function testPrepareRates($r1, $r2, $r3) {

        $method = new ReflectionMethod(
            'app\SourceRate', 'prepareRates'
        );
        $method->setAccessible(true);
        $obj = new SourceRate();

        $result = $method->invoke($obj, [$r1, $r2, $r3]);

        $ct = 0;
        foreach ($result as $item) {
            if (property_exists($item, 'symbol') and property_exists($item, 'rate')) {
                $ct++;
            }
        }

        $this->assertEquals($ct, 3);
    }

    public function providerPrepareRates () {
        return array (
            array (
                (object) ['symbol' => 'USD', 'rate' => 3],
                (object) ['symbol' => 'RUR', 'rate' => 5],
                (object) ['symbol' => 'EUR', 'rate' => 1]),
            array (
                (object) ['USD' => 3],
                (object) ['RUR' => 5],
                (object) ['EUR' => 1]),
        );
    }
}