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
    public function getStudentsWithFilters($guardian_id, $search = '', $status_filter = '', $enrollment_filter = '', $limit = 10, $offset = 0)
    {
        // Ensure limit and offset are non-negative integers
        $limit = (int)$limit;
        $offset = (int)$offset;
        if ($limit < 1) {
            $limit = 10;
        }
        if ($offset < 0) {
            $offset = 0;
        }

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
            WHERE s.guardian_id = {$guardian_id}
        ";

        $count_sql = "
            SELECT COUNT(*) as total
            FROM students s
            LEFT JOIN student_requirements sr ON s.student_id = sr.student_id
            WHERE s.guardian_id = {$guardian_id}
        ";

        // Add search filter
        if (!empty($search)) {
            $search = $this->db->real_escape_string($search);
            $search_condition = " AND (s.lrn LIKE '%{$search}%' OR s.first_name LIKE '%{$search}%' OR s.last_name LIKE '%{$search}%' OR CONCAT(s.first_name, ' ', s.last_name) LIKE '%{$search}%')";
            $sql .= $search_condition;
            $count_sql .= $search_condition;
        }

        // Add student status filter
        if (!empty($status_filter)) {
            $status_filter = $this->db->real_escape_string($status_filter);
            $status_condition = " AND s.student_status = '{$status_filter}'";
            $sql .= $status_condition;
            $count_sql .= $status_condition;
        }

        // Add enrollment status filter
        if (!empty($enrollment_filter)) {
            $enrollment_filter = $this->db->real_escape_string($enrollment_filter);
            $enrollment_condition = " AND sr.enrollment_status = '{$enrollment_filter}'";
            $sql .= $enrollment_condition;
            $count_sql .= $enrollment_condition;
        }

        // Get total count
        $count_result = $this->db->query($count_sql);
        $total = $count_result->fetch_assoc()['total'];

        // Add ordering and pagination
        $sql .= " ORDER BY s.created_at DESC LIMIT {$limit} OFFSET {$offset}";

        // Execute query
        $result = $this->db->query($sql);

        $students = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }

        return [
            'students' => $students,
            'total' => $total
        ];
    }

    // Get student with requirements by ID (with authorization check)
    public function getStudentWithRequirements($student_id, $guardian_id = null)
    {
        $sql = "
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
            WHERE s.student_id = ?
        ";

        if ($guardian_id !== null) {
            $sql .= " AND s.guardian_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $student_id, $guardian_id);
        } else {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $student_id);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
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

    // Find student by LRN
    public function findByLRN($lrn)
    {
        $stmt = $this->db->prepare("SELECT * FROM students WHERE lrn = ?");
        $stmt->bind_param("s", $lrn);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
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

        $stmt->bind_param(
            "issssssssssssssssssssssssssssss",
            $data['guardian_id'],
            $data['lrn'],
            $data['first_name'],
            $data['middle_name'],
            $data['last_name'],
            $data['name_extension'],
            $data['date_of_birth'],
            $data['place_of_birth'],
            $data['gender'],
            $data['mother_tongue'],
            $data['religion'],
            $data['indigenous_people'],
            $data['house_number'],
            $data['street_name'],
            $data['barangay'],
            $data['city_municipality'],
            $data['province'],
            $data['region'],
            $data['zip_code'],
            $data['father_name'],
            $data['father_occupation'],
            $data['father_contact'],
            $data['mother_name'],
            $data['mother_occupation'],
            $data['mother_contact'],
            $data['guardian_name'],
            $data['guardian_relationship'],
            $data['guardian_occupation'],
            $data['enrollment_type'],
            $data['previous_school'],
            $data['previous_grade_level']
        );

        if ($stmt->execute()) {
            return $this->db->insert_id;
        }

        return false;
    }


    // Check if student belongs to guardian (authorization)
    public function belongsToGuardian($student_id, $guardian_id)
    {
        $stmt = $this->db->prepare("SELECT student_id FROM students WHERE student_id = ? AND guardian_id = ?");
        $stmt->bind_param("ii", $student_id, $guardian_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }


    // ==================== ADMIN ENROLLMENT MANAGEMENT ====================

    // Get all students with filters (for admin - no guardian_id restriction)

    public function getAllStudentsWithFilters($search = '', $status_filter = '', $enrollment_filter = '', $grade_filter = '', $limit = 10, $offset = 0)
    {
        // Ensure limit and offset are non-negative integers
        $limit = (int)$limit;
        $offset = (int)$offset;
        if ($limit < 1) {
            $limit = 10;
        }
        if ($offset < 0) {
            $offset = 0;
        }

        $sql = "
        SELECT 
            s.*,
            gl.grade_name,
            sec.section_name,
            sec.room_number,
            sr.enrollment_status,
            sr.remarks,
            sr.submitted_at,
            u.username as guardian_username,
            u.email as guardian_email,
            u.contact_number as guardian_contact,
            TIMESTAMPDIFF(YEAR, s.date_of_birth, CURDATE()) as age
        FROM students s
        LEFT JOIN sections sec ON s.assigned_section_id = sec.section_id
        LEFT JOIN grade_levels gl ON sec.grade_id = gl.grade_id
        LEFT JOIN student_requirements sr ON s.student_id = sr.student_id
        LEFT JOIN users u ON s.guardian_id = u.user_id
        WHERE 1=1
    ";

        $count_sql = "
        SELECT COUNT(*) as total
        FROM students s
        LEFT JOIN student_requirements sr ON s.student_id = sr.student_id
        LEFT JOIN sections sec ON s.assigned_section_id = sec.section_id
        WHERE 1=1
    ";

        // Add search filter
        if (!empty($search)) {
            $search = $this->db->real_escape_string($search);
            $search_condition = " AND (s.lrn LIKE '%{$search}%' OR s.first_name LIKE '%{$search}%' OR s.last_name LIKE '%{$search}%' OR CONCAT(s.first_name, ' ', s.last_name) LIKE '%{$search}%')";
            $sql .= $search_condition;
            $count_sql .= $search_condition;
        }

        // Add student status filter
        if (!empty($status_filter)) {
            $status_filter = $this->db->real_escape_string($status_filter);
            $status_condition = " AND s.student_status = '{$status_filter}'";
            $sql .= $status_condition;
            $count_sql .= $status_condition;
        }

        // Add enrollment status filter
        if (!empty($enrollment_filter)) {
            $enrollment_filter = $this->db->real_escape_string($enrollment_filter);
            $enrollment_condition = " AND sr.enrollment_status = '{$enrollment_filter}'";
            $sql .= $enrollment_condition;
            $count_sql .= $enrollment_condition;
        }

        // Add grade filter
        if (!empty($grade_filter)) {
            $grade_filter = $this->db->real_escape_string($grade_filter);
            $grade_condition = " AND sec.grade_id = '{$grade_filter}'";
            $sql .= $grade_condition;
            $count_sql .= $grade_condition;
        }

        // Get total count
        $count_result = $this->db->query($count_sql);
        $total = $count_result->fetch_assoc()['total'];

        // Add ordering and pagination
        $sql .= " ORDER BY 
        CASE sr.enrollment_status
            WHEN 'For Review' THEN 1
            WHEN 'Pending' THEN 2
            WHEN 'Incomplete' THEN 3
            WHEN 'Approved' THEN 4
            WHEN 'Declined' THEN 5
            ELSE 6
        END,
        s.created_at DESC 
        LIMIT {$limit} OFFSET {$offset}
    ";

        // Execute query
        $result = $this->db->query($sql);

        $students = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }

        return [
            'students' => $students,
            'total' => $total
        ];
    }

    // Get enrollment requests count by status
    public function getEnrollmentRequestsCounts()
    {
        $result = $this->db->query("
        SELECT 
            COUNT(CASE WHEN sr.enrollment_status = 'Pending' THEN 1 END) as pending,
            COUNT(CASE WHEN sr.enrollment_status = 'For Review' THEN 1 END) as for_review,
            COUNT(CASE WHEN sr.enrollment_status = 'Approved' THEN 1 END) as approved,
            COUNT(CASE WHEN sr.enrollment_status = 'Declined' THEN 1 END) as declined,
            COUNT(CASE WHEN sr.enrollment_status = 'Incomplete' THEN 1 END) as incomplete
        FROM students s
        LEFT JOIN student_requirements sr ON s.student_id = sr.student_id
    ");

        return $result->fetch_assoc();
    }

    // Get student by ID (no authorization check - for admin)
    public function getStudentById($student_id)
    {
        $stmt = $this->db->prepare("
        SELECT 
            s.*,
            gl.grade_name,
            sec.section_name,
            sec.room_number,
            sec.section_id,
            sr.*,
            u.username as guardian_username,
            u.email as guardian_email,
            u.contact_number as guardian_contact,
            TIMESTAMPDIFF(YEAR, s.date_of_birth, CURDATE()) as age
        FROM students s
        LEFT JOIN sections sec ON s.assigned_section_id = sec.section_id
        LEFT JOIN grade_levels gl ON sec.grade_id = gl.grade_id
        LEFT JOIN student_requirements sr ON s.student_id = sr.student_id
        LEFT JOIN users u ON s.guardian_id = u.user_id
        WHERE s.student_id = ?
    ");

        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Update enrollment status (Admin action)
    public function updateEnrollmentStatus($student_id, $status, $remarks, $admin_id)
    {
        $stmt = $this->db->prepare("
        UPDATE student_requirements 
        SET 
            enrollment_status = ?,
            remarks = ?,
            reviewed_by = ?,
            reviewed_at = NOW(),
            approved_by = CASE WHEN ? = 'Approved' THEN ? ELSE approved_by END,
            approved_at = CASE WHEN ? = 'Approved' THEN NOW() ELSE approved_at END,
            updated_at = NOW()
        WHERE student_id = ?
    ");

        $stmt->bind_param("ssisssi", $status, $remarks, $admin_id, $status, $admin_id, $status, $student_id);

        return $stmt->execute();
    }

    // Assign student to section
    public function assignToSection($student_id, $section_id)
    {
        $stmt = $this->db->prepare("
        UPDATE students 
        SET assigned_section_id = ?, updated_at = NOW()
        WHERE student_id = ?
    ");

        $stmt->bind_param("ii", $section_id, $student_id);

        return $stmt->execute();
    }


    // ==================== ADMIN STUDENT MANAGEMENT ====================

    // Get all students for admin management
    public function getAllStudentsForManagement($search = '', $status_filter = '', $grade_filter = '', $section_filter = '', $limit = 15, $offset = 0)
    {
        $sql = "
        SELECT 
            s.*,
            gl.grade_name,
            sec.section_name,
            sec.room_number,
            sr.enrollment_status,
            u.username as guardian_username,
            u.email as guardian_email,
            u.contact_number as guardian_contact,
            TIMESTAMPDIFF(YEAR, s.date_of_birth, CURDATE()) as age
        FROM students s
        LEFT JOIN sections sec ON s.assigned_section_id = sec.section_id
        LEFT JOIN grade_levels gl ON sec.grade_id = gl.grade_id
        LEFT JOIN student_requirements sr ON s.student_id = sr.student_id
        LEFT JOIN users u ON s.guardian_id = u.user_id
        WHERE 1=1
    ";

        $count_sql = "
        SELECT COUNT(*) as total
        FROM students s
        LEFT JOIN sections sec ON s.assigned_section_id = sec.section_id
        WHERE 1=1
    ";

        // Add search filter
        if (!empty($search)) {
            $search = $this->db->real_escape_string($search);
            $search_condition = " AND (s.lrn LIKE '%{$search}%' OR s.first_name LIKE '%{$search}%' OR s.last_name LIKE '%{$search}%' OR CONCAT(s.first_name, ' ', s.last_name) LIKE '%{$search}%')";
            $sql .= $search_condition;
            $count_sql .= $search_condition;
        }

        // Add student status filter
        if (!empty($status_filter)) {
            $status_filter = $this->db->real_escape_string($status_filter);
            $status_condition = " AND s.student_status = '{$status_filter}'";
            $sql .= $status_condition;
            $count_sql .= $status_condition;
        }

        // Add grade filter
        if (!empty($grade_filter)) {
            $grade_filter = $this->db->real_escape_string($grade_filter);
            $grade_condition = " AND sec.grade_id = '{$grade_filter}'";
            $sql .= $grade_condition;
            $count_sql .= $grade_condition;
        }

        // Add section filter
        if (!empty($section_filter)) {
            $section_filter = $this->db->real_escape_string($section_filter);
            $section_condition = " AND s.assigned_section_id = '{$section_filter}'";
            $sql .= $section_condition;
            $count_sql .= $section_condition;
        }

        // Get total count
        $count_result = $this->db->query($count_sql);
        $total = $count_result->fetch_assoc()['total'];

        // Add ordering and pagination
        $sql .= " ORDER BY s.last_name ASC, s.first_name ASC LIMIT {$limit} OFFSET {$offset}";

        // Execute query
        $result = $this->db->query($sql);

        $students = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }

        return [
            'students' => $students,
            'total' => $total
        ];
    }

    // Get student statistics by status
    public function getStudentStatsByStatus()
    {
        $result = $this->db->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN student_status = 'Active' THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN student_status = 'Inactive' THEN 1 ELSE 0 END) as inactive,
            SUM(CASE WHEN student_status = 'Transferred' THEN 1 ELSE 0 END) as transferred,
            SUM(CASE WHEN student_status = 'Graduated' THEN 1 ELSE 0 END) as graduated,
            SUM(CASE WHEN student_status = 'Dropped' THEN 1 ELSE 0 END) as dropped
        FROM students
    ");

        return $result->fetch_assoc();
    }

    // Update student information
    public function updateStudent($student_id, $data)
    {
        $stmt = $this->db->prepare("
        UPDATE students 
        SET 
            lrn = ?,
            first_name = ?,
            middle_name = ?,
            last_name = ?,
            name_extension = ?,
            date_of_birth = ?,
            place_of_birth = ?,
            gender = ?,
            mother_tongue = ?,
            religion = ?,
            indigenous_people = ?,
            house_number = ?,
            street_name = ?,
            barangay = ?,
            city_municipality = ?,
            province = ?,
            region = ?,
            zip_code = ?,
            father_name = ?,
            father_occupation = ?,
            father_contact = ?,
            mother_name = ?,
            mother_occupation = ?,
            mother_contact = ?,
            guardian_name = ?,
            guardian_relationship = ?,
            guardian_occupation = ?,
            enrollment_type = ?,
            previous_school = ?,
            previous_grade_level = ?,
            updated_at = NOW()
        WHERE student_id = ?
    ");

        $stmt->bind_param(
            "ssssssssssssssssssssssssssssssi",
            $data['lrn'],
            $data['first_name'],
            $data['middle_name'],
            $data['last_name'],
            $data['name_extension'],
            $data['date_of_birth'],
            $data['place_of_birth'],
            $data['gender'],
            $data['mother_tongue'],
            $data['religion'],
            $data['indigenous_people'],
            $data['house_number'],
            $data['street_name'],
            $data['barangay'],
            $data['city_municipality'],
            $data['province'],
            $data['region'],
            $data['zip_code'],
            $data['father_name'],
            $data['father_occupation'],
            $data['father_contact'],
            $data['mother_name'],
            $data['mother_occupation'],
            $data['mother_contact'],
            $data['guardian_name'],
            $data['guardian_relationship'],
            $data['guardian_occupation'],
            $data['enrollment_type'],
            $data['previous_school'],
            $data['previous_grade_level'],
            $student_id
        );

        return $stmt->execute();
    }

    // Update student status
    public function updateStudentStatus($student_id, $status)
    {
        $stmt = $this->db->prepare("
        UPDATE students 
        SET student_status = ?, updated_at = NOW()
        WHERE student_id = ?
    ");

        $stmt->bind_param("si", $status, $student_id);

        return $stmt->execute();
    }

    // Check if LRN exists (excluding current student)
    public function isLRNTaken($lrn, $exclude_student_id = null)
    {
        if ($exclude_student_id) {
            $stmt = $this->db->prepare("SELECT student_id FROM students WHERE lrn = ? AND student_id != ?");
            $stmt->bind_param("si", $lrn, $exclude_student_id);
        } else {
            $stmt = $this->db->prepare("SELECT student_id FROM students WHERE lrn = ?");
            $stmt->bind_param("s", $lrn);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }
}
