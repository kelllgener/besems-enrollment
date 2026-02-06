<?php
namespace App\Core;

class DbConfig {
  private $host = "localhost";
  private $user = "root";
  private $pass = "";
  private $db   = "elem_enrollment_db";

  public function getConnection() {
      $conn = new \mysqli($this->host, $this->user, $this->pass, $this->db);
      if ($conn->connect_error) {
          die("Database Connection Error: " . $conn->connect_error);
      }
      return $conn;
  }
}