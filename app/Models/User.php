<?php

namespace App\Models;

use App\Core\DbConfig;

class User
{
    private $db;

    public function __construct()
    {
        $dbConfig = new DbConfig();
        $this->db = $dbConfig->getConnection();
    }

    
    // Find user by username (for login)
    public function findByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Find user by email
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Get user by ID
    public function findById($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    
    // Create new guardian user
    public function create($data)
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("
            INSERT INTO users (username, password, email, contact_number, role, is_active) 
            VALUES (?, ?, ?, ?, 'guardian', 1)
        ");
        
        $stmt->bind_param(
            "ssss",
            $data['username'],
            $hashedPassword,
            $data['email'],
            $data['contact_number']
        );
        
        return $stmt->execute();
    }

    
    // Update user profile
    public function updateProfile($user_id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET email = ?, contact_number = ?, updated_at = NOW()
            WHERE user_id = ?
        ");
        
        $stmt->bind_param(
            "ssi",
            $data['email'],
            $data['contact_number'],
            $user_id
        );
        
        return $stmt->execute();
    }

    // Update password
    public function updatePassword($user_id, $new_password)
    {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("
            UPDATE users 
            SET password = ?, updated_at = NOW()
            WHERE user_id = ?
        ");
        
        $stmt->bind_param("si", $hashed_password, $user_id);
        
        return $stmt->execute();
    }

    // Deactivate account
    public function deactivateAccount($user_id)
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET is_active = 0, updated_at = NOW()
            WHERE user_id = ?
        ");
        
        $stmt->bind_param("i", $user_id);
        
        return $stmt->execute();
    }

    
    // Verify password
    public function verifyPassword($user, $password)
    {
        return password_verify($password, $user['password']);
    }

    // Check if user is active
    public function isActive($user)
    {
        return $user['is_active'] == 1;
    }
}