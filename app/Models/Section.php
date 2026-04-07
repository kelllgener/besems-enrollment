<?php

namespace App\Models;

use App\Core\DbConfig;

class Section
{
    private $db;

    public function __construct()
    {
        $dbConfig = new DbConfig();
        $this->db = $dbConfig->getConnection();
    }

    // Get all sections
    public function getAllSections()
    {
        $result = $this->db->query("
            SELECT 
                s.*,
                gl.grade_name,
                COUNT(st.student_id) as current_students
            FROM sections s
            LEFT JOIN grade_levels gl ON s.grade_id = gl.grade_id
            LEFT JOIN students st ON s.section_id = st.assigned_section_id
            GROUP BY s.section_id
            ORDER BY gl.grade_id, s.section_name
        ");
        
        $sections = [];
        while ($row = $result->fetch_assoc()) {
            $sections[] = $row;
        }
        
        return $sections;
    }

    // Get sections by grade
    public function getSectionsByGrade($grade_id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                s.*,
                gl.grade_name,
                COUNT(st.student_id) as current_students
            FROM sections s
            LEFT JOIN grade_levels gl ON s.grade_id = gl.grade_id
            LEFT JOIN students st ON s.section_id = st.assigned_section_id
            WHERE s.grade_id = ?
            GROUP BY s.section_id
            ORDER BY s.section_name
        ");
        
        $stmt->bind_param("i", $grade_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $sections = [];
        while ($row = $result->fetch_assoc()) {
            $sections[] = $row;
        }
        
        return $sections;
    }

    // Get section by ID
    public function getSectionById($section_id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                s.*,
                gl.grade_name,
                COUNT(st.student_id) as current_students
            FROM sections s
            LEFT JOIN grade_levels gl ON s.grade_id = gl.grade_id
            LEFT JOIN students st ON s.section_id = st.assigned_section_id
            WHERE s.section_id = ?
            GROUP BY s.section_id
        ");
        
        $stmt->bind_param("i", $section_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    // Check if section has available slots
    public function hasAvailableSlots($section_id)
    {
        $section = $this->getSectionById($section_id);
        
        if (!$section) {
            return false;
        }
        
        return $section['current_students'] < $section['max_students'];
    }
}