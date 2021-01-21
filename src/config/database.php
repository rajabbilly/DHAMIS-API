<?php 
    class Database {
        // Properties
        //DESKTOP-3PHRVRQ
        private $serverName = "10.200.1.13";
        private $uid = "sa";
        private $password = "Rajabenock2510@";
        private $database = "HIVData-live";
        public $apiKey = [
            '5fcd27bc', 
            'a4595012'  //Alternative Key
        ];
        private $conn;

        // Connect function
        public function getConnection() {
            $this->conn = NULL;
            try{
                $this->conn = new PDO("sqlsrv:server=".$this->serverName.";Database=".$this->database, $this->uid, $this->password,
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    )
                );
            }catch (PDOException $e){
                die("Error connecting to SQL Server: ". $e->getMessage());
            }
            return $this->conn;
        }
    }