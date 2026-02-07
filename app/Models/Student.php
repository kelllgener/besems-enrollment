<?php

namespace App\Models;

use App\Core\DbConfig;

class Student
{
    private $db;

    public function __construct()
    {
        $dbConfig = new DbConfig();
        $this->db = $dbConfig->getConnection();
    }

    // Get all students for a specific guardian
    public function getStudentsByGuardian($guardian_id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                s.*,
                gl.grade_name,
                sec.section_name,
                sec.room_number,
                sr.enrollment_status,
                sr.remarks,
                TIMESTAMPDIFF(YEAR, s.date_of_birth, CURDATE()) as age
            FROM students s
            LEFT JOIN sections sec ON s.assigned_section_id = sec.section_id
            LEFT JOIN grade_levels gl ON sec.grade_id = gl.grade_id
            LEFT JOIN student_requirements sr ON s.student_id = sr.student_id
            WHERE s.guardian_id = ?
            ORDER BY s.created_at DESC
        ");
        
        $stmt->bind_param("i", $guardian_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        
        return $students;
    }

    // Get student count by status for a guardian
    public function getStudentCountsByStatus($guardian_id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN student_status = 'Active' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN student_status = 'Inactive' THEN 1 ELSE 0 END) as inactive,
                SUM(CASE WHEN student_status = 'Transferred' THEN 1 ELSE 0 END) as transferred,
                SUM(CASE WHEN student_status = 'Graduated' THEN 1 ELSE 0 END) as graduated
            FROM students
            WHERE guardian_id = ?
        ");
        
        $stmt->bind_param("i", $guardian_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    // Get enrollment status counts for a guardian
    public function getEnrollmentStatusCounts($guardian_id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(CASE WHEN sr.enrollment_status = 'Pending' THEN 1 END) as pending,
                COUNT(CASE WHEN sr.enrollment_status = 'For Review' THEN 1 END) as for_review,
                COUNT(CASE WHEN sr.enrollment_status = 'Approved' THEN 1 END) as approved,
                COUNT(CASE WHEN sr.enrollment_status = 'Declined' THEN 1 END) as declined,
                COUNT(CASE WHEN sr.enrollment_status = 'Incomplete' THEN 1 END) as incomplete
            FROM students s
            LEFT JOIN student_requirements sr ON s.student_id = sr.student_id
            WHERE s.guardian_id = ?
        ");
        
        $stmt->bind_param("i", $guardian_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    // Get requirements status for a specific student
    public function getStudentRequirements($student_id)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM student_requirements 
            WHERE student_id = ?
        ");
        
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
}