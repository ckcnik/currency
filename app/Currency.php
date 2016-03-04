<?php
namespace app {
    require_once 'SourceRate.php';

    class Currency {

        public $sourceRate;
        private $db;

        /**
         * CurrencyUpdate constructor.
         * @param $connect
         */
        function __construct($connect) {
            $this->db = $connect;
            $this->sourceRate = new SourceRate();
        }

        /**
         * Insert or update rates in table currency
         */
        public function update() {
            $sql = $this->db->sqlPrepare("INSERT INTO currency (symbol, rate) VALUES (?, ?) ON DUPLICATE KEY UPDATE rate = ?;");
            foreach ($this->sourceRate->rates as $rate) {
                $sql->bind_param('sdd', $rate->symbol, $rate->rate, $rate->rate);
                if (!$sql->execute()) {
                    printf($this->db->getErrorMessage());
                    exit();
                }
            }
            return true;
        }
    }
}
