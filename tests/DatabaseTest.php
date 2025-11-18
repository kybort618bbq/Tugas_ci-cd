<?php

class DatabaseTest
{
    public $conn; // UBah protected menjadi PUBLIC

    public function __construct()
    {
        $host = getenv("DB_HOST");
        $name = getenv("DB_NAME");
        $user = getenv("DB_USER");
        $pass = getenv("DB_PASS");

        $this->conn = new PDO("mysql:host=$host;dbname=$name", $user, $pass);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
