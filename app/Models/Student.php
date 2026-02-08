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

    // Get students with filters and pagination
    // Get students with filters and pagination (with debugging)
    public function getStudentsWithFilters($guardian_id, $search = '', $status_filter = '', $enrollment_filter = '', $limit = 10, $offset = 0)
    {
        // 1. Sanitize pagination inputs
        $offset = max(0, (int)$offset);
        $limit = max(1, (int)$limit);
        $guardian_id = (int)$guardian_id;

        // 2. Build the base WHERE conditions
        $where = "WHERE s.guardian_id = {$guardian_id}";

        if (!empty($search)) {
            $search = $this->db->real_escape_string($search);
            $where .= " AND (s.lrn LIKE '%{$search}%' OR s.first_name LIKE '%{$search}%' OR s.last_name LIKE '%{$search}%' OR CONCAT(s.first_name, ' ', s.last_name) LIKE '%{$search}%')";
        }

        if (!empty($status_filter)) {
            $status_filter = $this->db->real_escape_string($status_filter);
            $where .= " AND s.student_status = '{$status_filter}'";
        }

        if (!empty($enrollment_filter)) {
            $enrollment_filter = $this->db->real_escape_string($enrollment_filter);
            $where .= " AND sr.enrollment_status = '{$enrollment_filter}'";
        }

        // 3. Get total count first
        $count_sql = "SELECT COUNT(*) as total FROM students s 
                  LEFT JOIN student_requirements sr ON s.student_id = sr.student_id 
                  {$where}";

        $count_result = $this->db->query($count_sql);
        $total = ($count_result) ? $count_result->fetch_assoc()['total'] : 0;

        // 4. Get the actual data
        $sql = "
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
        {$where}
        ORDER BY s.created_at DESC 
        LIMIT {$limit} OFFSET {$offset}
    ";

        $result = $this->db->query($sql);

        $students = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }

        return [
            'students' => $students,
            'total' => (int)$total
        ];
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

    // Get student with requirements by ID
    public function getStudentWithRequirements($student_id, $guardian_id)
    {
        $stmt = $this->db->prepare("
        SELECT 
            s.*,
            sr.*,
            gl.grade_name,
            sec.section_name,
            TIMESTAMPDIFF(YEAR, s.date_of_birth, CURDATE()) as age
        FROM students s
        LEFT JOIN student_requirements sr ON s.student_id = sr.student_id
        LEFT JOIN sections sec ON s.assigned_section_id = sec.section_id
        LEFT JOIN grade_levels gl ON sec.grade_id = gl.grade_id
        WHERE s.student_id = ? AND s.guardian_id = ?
    ");

        $stmt->bind_param("ii", $student_id, $guardian_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Update student requirements
    public function updateRequirements($student_id, $requirements)
    {
        $stmt = $this->db->prepare("
        UPDATE student_requirements 
        SET 
            birth_certificate = ?,
            report_card_form137 = ?,
            good_moral_certificate = ?,
            certificate_of_completion = ?,
            id_picture_2x2 = ?,
            transfer_credential = ?,
            medical_certificate = ?,
            enrollment_status = ?,
            submitted_at = NOW(),
            updated_at = NOW()
        WHERE student_id = ?
    ");

        $stmt->bind_param(
            "iiiiiiisi",
            $requirements['birth_certificate'],
            $requirements['report_card_form137'],
            $requirements['good_moral_certificate'],
            $requirements['certificate_of_completion'],
            $requirements['id_picture_2x2'],
            $requirements['transfer_credential'],
            $requirements['medical_certificate'],
            $requirements['enrollment_status'],
            $student_id
        );

        return $stmt->execute();
    }

    // Get all students for requirements dropdown
    public function getStudentsListByGuardian($guardian_id)
    {
        $stmt = $this->db->prepare("
        SELECT 
            s.student_id,
            s.lrn,
            s.first_name,
            s.last_name,
            sr.enrollment_status,
            TIMESTAMPDIFF(YEAR, s.date_of_birth, CURDATE()) as age
        FROM students s
        LEFT JOIN student_requirements sr ON s.student_id = sr.student_id
        WHERE s.guardian_id = ?
        ORDER BY s.first_name ASC
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
}
