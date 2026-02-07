<?php

namespace App\Models;

use App\Core\DbConfig;

class Dashboard
{
    private $db;

    public function __construct()
    {
        $dbConfig = new DbConfig();
        $this->db = $dbConfig->getConnection();
    }

    // Get total students count
    public function getTotalStudents()
    {
        $result = $this->db->query("SELECT COUNT(*) as count FROM students");
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    // Get pending enrollments count
    public function getPendingEnrollments()
    {
        $result = $this->db->query("SELECT COUNT(*) as count FROM student_requirements WHERE enrollment_status = 'Pending'");
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    // Get total sections count
    public function getTotalSections()
    {
        $result = $this->db->query("SELECT COUNT(*) as count FROM sections");
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    // Get total subjects count
    public function getTotalSubjects()
    {
        $result = $this->db->query("SELECT COUNT(*) as count FROM subjects");
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    // Get students per grade level
    public function getStudentsPerGrade()
    {
        $query = "
            SELECT 
                gl.grade_name,
                COUNT(s.student_id) as student_count
            FROM grade_levels gl
            LEFT JOIN sections sec ON gl.grade_id = sec.grade_id
            LEFT JOIN students s ON sec.section_id = s.assigned_section_id
            GROUP BY gl.grade_id, gl.grade_name
            ORDER BY gl.grade_id
        ";
        
        $result = $this->db->query($query);
        $data = [];
        
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        return $data;
    }

    // Get enrollment status breakdown
    public function getEnrollmentStatus()
    {
        $approved = $this->db->query("SELECT COUNT(*) as count FROM student_requirements WHERE enrollment_status = 'Approved'");
        $pending = $this->db->query("SELECT COUNT(*) as count FROM student_requirements WHERE enrollment_status = 'Pending'");
        $declined = $this->db->query("SELECT COUNT(*) as count FROM student_requirements WHERE enrollment_status = 'Declined'");
        
        return [
            'approved' => $approved->fetch_assoc()['count'],
            'pending' => $pending->fetch_assoc()['count'],
            'declined' => $declined->fetch_assoc()['count']
        ];
    }

    // Get recent students
    public function getRecentStudents($limit = 5)
    {
        $stmt = $this->db->prepare("
            SELECT 
                s.student_id,
                s.first_name,
                s.last_name,
                s.lrn,
                COALESCE(sr.enrollment_status, 'Pending') as enrollment_status
            FROM students s
            LEFT JOIN student_requirements sr ON s.student_id = sr.student_id
            ORDER BY s.student_id DESC
            LIMIT ?
        ");
        
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        return $data;
    }

    // Get all dashboard statistics at once
    public function getAllStats()
    {
        return [
            'total_students' => $this->getTotalStudents(),
            'pending_enrollments' => $this->getPendingEnrollments(),
            'total_sections' => $this->getTotalSections(),
            'total_subjects' => $this->getTotalSubjects()
        ];
    }
}