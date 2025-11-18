<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../tests/Database.php";

class LoginTest extends TestCase
{
    private $db;

    protected function setUp(): void
    {
        $this->db = new DatabaseTest();
    }

    public function testLoginBerhasil()
    {
        // buat akun dummy
        $email = "testlogin@example.com";
        $password = "password123";
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // hapus jika akun sudah ada
        $this->db->conn->prepare("DELETE FROM users WHERE email=?")->execute([$email]);

        // buat akun baru
        $this->db->conn->prepare(
            "INSERT INTO users (fullname, email, password, failed_attempts, lock_time) VALUES (?, ?, ?, 0, 0)"
        )->execute(["Test User", $email, $hashed]);

        // ambil user
        $stmt = $this->db->conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // cek password
        $this->assertTrue(password_verify($password, $user["password"]));
    }

    public function testLoginGagalPasswordSalah()
    {
        $email = "testwrong@example.com";
        $correctPass = "password123";
        $wrongPass = "abc12345";
        $hashed = password_hash($correctPass, PASSWORD_DEFAULT);

        $this->db->conn->prepare("DELETE FROM users WHERE email=?")->execute([$email]);

        // buat akun baru
        $this->db->conn->prepare(
            "INSERT INTO users (fullname, email, password, failed_attempts, lock_time) VALUES (?, ?, ?, 0, 0)"
        )->execute(["Wrong Pass User", $email, $hashed]);

        // ambil user
        $stmt = $this->db->conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // cek password harus gagal
        $this->assertFalse(password_verify($wrongPass, $user["password"]));
    }
}
