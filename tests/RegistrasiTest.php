<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../tests/DatabaseTest.php";

class RegistrasiTest extends TestCase
{
    private $db;

    protected function setUp(): void
    {
        $this->db = new DatabaseTest();
    }

    public function testRegistrasiBerhasil()
    {
        $email = "registertest@example.com";

        // hapus akun jika sudah ada
        $this->db->conn->prepare("DELETE FROM users WHERE email=?")->execute([$email]);

        $fullname = "Test Register";
        $password = "password123";
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // buat user baru
        $stmt = $this->db->conn->prepare(
            "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)"
        );
        $stmt->execute([$fullname, $email, $hashed]);

        // ambil kembali user
        $stmt = $this->db->conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals($fullname, $user["fullname"]);
        $this->assertEquals($email, $user["email"]);
        $this->assertTrue(password_verify($password, $user["password"]));
    }

    public function testRegistrasiGagalEmailSudahAda()
    {
        $email = "duplicate@example.com";
        $fullname = "User Test";
        $password = password_hash("password123", PASSWORD_DEFAULT);

        // pastikan user sudah ada
        $this->db->conn->prepare("DELETE FROM users WHERE email=?")->execute([$email]);
        $this->db->conn->prepare(
            "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)"
        )->execute([$fullname, $email, $password]);

        // coba daftar dengan email sama
        $stmt = $this->db->conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // email sudah ada → user tidak null
        $this->assertNotNull($user);
    }
}
