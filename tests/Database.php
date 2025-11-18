<?php

class DatabaseTest
{
    public $conn;

    public function __construct()
    {
        $host = getenv("DB_HOST") ?: "127.0.0.1";   // gunakan host benar di GitHub
        $name = getenv("DB_NAME");
        $user = getenv("DB_USER");
        $pass = getenv("DB_PASS");

        $dsn = "mysql:host=$host;dbname=$name;charset=utf8";

        $this->conn = new PDO($dsn, $user, $pass);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}
