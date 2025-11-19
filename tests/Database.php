<?php

class DatabaseTest {
    public $conn;

    public function __construct() {
        $this->conn = new PDO(
            "mysql:host=127.0.0.1;dbname=db_login1",
            "root",
            "",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
}
