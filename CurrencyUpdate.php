<?php

class CurrencyUpdate {

    function __construct($dbhost, $dbuser, $dbpass) {

        $this->sources = array(
            'rates.json',
            'http://localhost/rates1.json',
        );


        $this->mysqli = $this->connect($dbhost, $dbuser, $dbpass);
        $this->rates = $this->get_rates();
    }

    private function connect($dbhost, $dbuser, $dbpass) {
        $mysqli = new mysqli($dbhost, $dbuser, $dbpass, "intis");

        if ($mysqli->connect_errno) {
            printf("Connect error: %s\n", $mysqli->connect_error);
            exit();
        }

        return $mysqli;
    }

    private function get_rates(){
        $rates = [];
        foreach ($this->sources as $src) {
            $json = file_get_contents($src);
            $source = json_decode($json);
            $rates = array_merge($rates, $this->prepareRates($source));
        }
        $rates = array_map("unserialize", array_unique(array_map("serialize", $rates)));

        return $rates;
    }

    private static function prepareRates($source) {
        $rates = array();
        if ($source) {
            if (!($source instanceof stdClass) or !property_exists($source, 'rates')) {
                $rate = new stdClass();
                foreach ($source as $item) {
                    foreach ($item as $property => $value) {
                        $rate->symbol = $property;
                        $rate->rate = $value;
                    }
                    $rates[] = $rate;
                }
            } else {
                $rates = $source->rates;
            }
        }

        return $rates;
    }

    function update() {
        $sql = $this->mysqli->prepare("INSERT INTO currency (symbol, rate) VALUES (?, ?) ON DUPLICATE KEY UPDATE rate = ?;");
        foreach ($this->rates as $rate) {
            $sql->bind_param('sdd', $rate->symbol, $rate->rate, $rate->rate);
            if (!$sql->execute()) {
                printf("Error: %s\n", $this->mysqli->error);
                exit();
            }
        }
        $sql->close();
        printf("Successful update rates!\n");
    }
}