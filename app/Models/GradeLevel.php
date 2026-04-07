<?php

namespace App\Models;

use App\Core\DbConfig;

class GradeLevel
{
    private $db;

    public function __construct()
    {
        $dbConfig = new DbConfig();
        $this->db = $dbConfig->getConnection();
    }

    // Get all grade levels
    public function getAllGradeLevels()
    {
        $result = $this->db->query("
            SELECT * FROM grade_levels 
            WHERE is_active = 1 
            ORDER BY grade_level
        ");
        
        $grades = [];
        while ($row = $result->fetch_assoc()) {
            $grades[] = $row;
        }
        
        return $grades;
    }

    // Get grade level by ID
    public function getGradeById($grade_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM grade_levels WHERE grade_id = ?");
        $stmt->bind_param("i", $grade_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
}