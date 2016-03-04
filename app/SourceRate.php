<?php
/**
 * Storage currency sources
 * Class SourceRate
 */
namespace app {
    const REMOTE_SOURCE = 'http://localhost/rates1.json';
    const LOCAL_SOURCE = 'rates.json';
    class SourceRate {

        /**
         * SourceRate constructor.
         */
        function __construct() {

            $this->sources = array(
                REMOTE_SOURCE,
                $this->getFullPath(LOCAL_SOURCE),
            );
            $this->rates = $this->getAllRates();
        }

        private function getFullPath($name) {

            return __ROOT__ . '/' . $name;
        }
        /**
         * Bringing to mind {obj->symbol = 'USD'; obj->rate = 3}
         * @param $source
         * @return array
         */
        private static function prepareRates($source) {
            $rates = array();
            if ($source) {
                if (!($source instanceof \stdClass) or !property_exists($source, 'rates')) {
                    $rate = new \stdClass();
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

        /**
         * Get rates from all sources
         * @return array
         */
        public function getAllRates() {
            $rates = [];
            foreach ($this->sources as $src) {
                $json = file_get_contents($src);
                $source = json_decode($json);
                $rates = array_merge($rates, $this->prepareRates($source));
            }
            $rates = array_map("unserialize", array_unique(array_map("serialize", $rates)));

            return $rates;
        }
    }
}