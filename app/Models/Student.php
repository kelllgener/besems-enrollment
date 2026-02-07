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

    // Find student by LRN
    public function findByLRN($lrn)
    {
        $stmt = $this->db->prepare("SELECT * FROM students WHERE lrn = ?");
        $stmt->bind_param("s", $lrn);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Create new student
    // Create new student
    public function createStudent($data)
    {
        $stmt = $this->db->prepare("
        INSERT INTO students (
            guardian_id, lrn, first_name, middle_name, last_name, name_extension,
            date_of_birth, place_of_birth, gender, mother_tongue, religion, indigenous_people,
            house_number, street_name, barangay, city_municipality, province, region, zip_code,
            father_name, father_occupation, father_contact,
            mother_name, mother_occupation, mother_contact,
            guardian_name, guardian_relationship, guardian_occupation,
            enrollment_type, previous_school, previous_grade_level,
            student_status
        ) VALUES (
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?,
            ?, ?, ?,
            'Active'
        )
    ");

        // Count: 31 parameters (32nd is hardcoded 'Active')
        // Type string needs: 1 'i' for integer + 30 's' for strings = 31 total
        $stmt->bind_param(
            "issssssssssssssssssssssssssssss", // i + 30 s's = 31 characters
            $data['guardian_id'],           // 1 - i (integer)
            $data['lrn'],                   // 2 - s
            $data['first_name'],            // 3 - s
            $data['middle_name'],           // 4 - s
            $data['last_name'],             // 5 - s
            $data['name_extension'],        // 6 - s
            $data['date_of_birth'],         // 7 - s
            $data['place_of_birth'],        // 8 - s
            $data['gender'],                // 9 - s
            $data['mother_tongue'],         // 10 - s
            $data['religion'],              // 11 - s
            $data['indigenous_people'],     // 12 - s
            $data['house_number'],          // 13 - s
            $data['street_name'],           // 14 - s
            $data['barangay'],              // 15 - s
            $data['city_municipality'],     // 16 - s
            $data['province'],              // 17 - s
            $data['region'],                // 18 - s
            $data['zip_code'],              // 19 - s
            $data['father_name'],           // 20 - s
            $data['father_occupation'],     // 21 - s
            $data['father_contact'],        // 22 - s
            $data['mother_name'],           // 23 - s
            $data['mother_occupation'],     // 24 - s
            $data['mother_contact'],        // 25 - s
            $data['guardian_name'],         // 26 - s
            $data['guardian_relationship'], // 27 - s
            $data['guardian_occupation'],   // 28 - s
            $data['enrollment_type'],       // 29 - s
            $data['previous_school'],       // 30 - s
            $data['previous_grade_level']   // 31 - s
        );

        if ($stmt->execute()) {
            return $this->db->insert_id;
        }

        return false;
    }

    // Create student requirements record
    public function createStudentRequirements($student_id)
    {
        $stmt = $this->db->prepare("
        INSERT INTO student_requirements (student_id, enrollment_status, submitted_at) 
        VALUES (?, 'Pending', NOW())
    ");

        $stmt->bind_param("i", $student_id);
        return $stmt->execute();
    }
}
