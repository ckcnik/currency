<?php
namespace app {

    use mysqli;

    /**
     * Interface DbConnect
     */
    interface DbConnect {
        public function connect();

        public function getErrorMessage();

        public function sqlPrepare($sql);
    }

    /**
     * Class MySqlDbConnect
     */
    class MySqlDbConnect implements DbConnect {

        public $connect;
        private $name; // dbname
        private $host;
        private $user;
        private $pass;

        function __construct($host, $user, $pass, $name) {
            $this->setParamConnects($host, $user, $pass, $name);
            $this->connect = $this->connect();
        }

        private function setParamConnects($host, $user, $pass, $name) {
            $this->host = $host;
            $this->user = $user;
            $this->pass = $pass;
            $this->name = $name;
        }

        /**
         * Return connect object to Db
         * @return mysqli
         */
        public function connect() {
            $mysqli = new \mysqli($this->host, $this->user, $this->pass, $this->name);

            if ($mysqli->connect_errno) {
                printf("Connect error: %s\n", $mysqli->connect_error);
                exit();
            }

            return $mysqli;
        }

        /**
         * @return string
         */
        public function getErrorMessage() {
            return "Error: {$this->connect->error}\n";
        }

        /**
         * @param $sql
         * @return \mysqli_stmt
         */
        public function sqlPrepare($sql) {
            $sql = $this->connect->prepare($sql);
            return $sql;
        }

        /**
         * Close db connect
         */
        function __destruct() {
            $this->connect->close();
        }
    }
}