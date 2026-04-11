<?php

namespace App\Controllers;

use App\Models\Dashboard;
use App\Models\GradeLevel;
use App\Models\Section;
use App\Models\Student;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $this->requireAdmin();

        $name = $_SESSION['name'] ?? 'User';

        // Get dashboard data
        $dashboardModel = new Dashboard();

        try {
            $stats = $dashboardModel->getAllStats();
            $students_per_grade = $dashboardModel->getStudentsPerGrade();
            $enrollment_status = $dashboardModel->getEnrollmentStatus();
            $recent_students = $dashboardModel->getRecentStudents(5);

            // Render the dashboard view
            $this->render('dashboard', [
                'pageTitle' => 'BESEMS - Dashboard',
                'name' => $name,
                'stats' => $stats,
                'students_per_grade' => $students_per_grade,
                'enrollment_status' => $enrollment_status,
                'recent_students' => $recent_students
            ]);
        } catch (\Exception $e) {
            // Handle errors gracefully
            error_log("Dashboard error: " . $e->getMessage());

            $this->render('admin/dashboard', [
                'pageTitle' => 'BESEMS - Dashboard',
                'name' => $name,
                'stats' => [
                    'total_students' => 0,
                    'pending_enrollments' => 0,
                    'total_sections' => 0,
                    'total_subjects' => 0
                ],
                'students_per_grade' => [],
                'enrollment_status' => ['approved' => 0, 'pending' => 0, 'declined' => 0],
                'recent_students' => []
            ]);
        }
    }

    // ==================== ENROLLMENT MANAGEMENT ====================

    public function enrollmentManagement()
    {
        $this->requireAdmin();

        $studentModel = new Student();

        // Get filter parameters
        $search = $_GET['search'] ?? '';
        $status_filter = $_GET['status'] ?? '';
        $enrollment_filter = $_GET['enrollment'] ?? 'For Review'; // Default to "For Review"
        $grade_filter = $_GET['grade'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = 15;
        $offset = ($page - 1) * $per_page;

        // Get filtered students
        $result = $studentModel->getAllStudentsWithFilters(
            $search,
            $status_filter,
            $enrollment_filter,
            $grade_filter,
            $per_page,
            $offset
        );

        // Get status counts
        $counts = $studentModel->getEnrollmentRequestsCounts();

        // Get grade levels for filter
        $gradeLevelModel = new GradeLevel();
        $grade_levels = $gradeLevelModel->getAllGradeLevels();

        $this->render('enrollments', [
            'pageTitle' => 'Enrollment Management - BESEMS',
            'students' => $result['students'],
            'search' => $search,
            'status_filter' => $status_filter,
            'enrollment_filter' => $enrollment_filter,
            'grade_filter' => $grade_filter,
            'current_page' => $page,
            'total_pages' => ceil($result['total'] / $per_page),
            'total_records' => $result['total'],
            'counts' => $counts,
            'grade_levels' => $grade_levels,
            'success_message' => $this->getSuccessMessage(),
            'error_message' => $this->getErrorMessage()
        ]);
    }

    public function reviewEnrollment()
    {
        $this->requireAdmin();

        $student_id = $_GET['id'] ?? null;

        if (!$student_id) {
            $this->redirectWithError('enrollments', 'Student ID is required');
        }

        $studentModel = new Student();
        $student = $studentModel->getStudentById($student_id);

        if (!$student) {
            $this->redirectWithError('enrollments', 'Student not found');
        }

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEnrollmentReview($student_id);
        }

        // Get grade levels and sections for assignment
        $gradeLevelModel = new GradeLevel();
        $sectionModel = new Section();

        $grade_levels = $gradeLevelModel->getAllGradeLevels();
        $sections = $sectionModel->getAllSections();

        $this->render('review-enrollment', [
            'pageTitle' => 'Review Enrollment - BESEMS',
            'student' => $student,
            'grade_levels' => $grade_levels,
            'sections' => $sections
        ]);
    }

    private function handleEnrollmentReview($student_id)
    {
        $action = $_POST['action'] ?? '';
        $remarks = trim($_POST['remarks'] ?? '');
        $section_id = !empty($_POST['section_id']) ? (int)$_POST['section_id'] : null;

        $admin_id = $this->getCurrentUserId();
        $studentModel = new Student();
        $sectionModel = new Section();

        if ($action === 'approve') {
            // Check if section is selected
            if (!$section_id) {
                $this->setErrorMessage('Please select a section for the student');
                return;
            }

            // Check if section has available slots
            if (!$sectionModel->hasAvailableSlots($section_id)) {
                $this->setErrorMessage('Selected section is full. Please choose another section.');
                return;
            }

            // Approve enrollment
            if ($studentModel->updateEnrollmentStatus($student_id, 'Approved', $remarks, $admin_id)) {
                // Assign to section
                $studentModel->assignToSection($student_id, $section_id);

                $this->redirectWithSuccess(
                    'enrollments?enrollment=Approved',
                    'Enrollment approved successfully!'
                );
            } else {
                $this->setErrorMessage('Failed to approve enrollment');
            }
        } elseif ($action === 'decline') {
            // Decline enrollment
            if (empty($remarks)) {
                $this->setErrorMessage('Please provide a reason for declining');
                return;
            }

            if ($studentModel->updateEnrollmentStatus($student_id, 'Declined', $remarks, $admin_id)) {
                $this->redirectWithSuccess(
                    'enrollments?enrollment=Declined',
                    'Enrollment declined'
                );
            } else {
                $this->setErrorMessage('Failed to decline enrollment');
            }
        } elseif ($action === 'return_incomplete') {
            // Return as incomplete
            if (empty($remarks)) {
                $this->setErrorMessage('Please specify what requirements are missing');
                return;
            }

            if ($studentModel->updateEnrollmentStatus($student_id, 'Incomplete', $remarks, $admin_id)) {
                $this->redirectWithSuccess(
                    'enrollments?enrollment=Incomplete',
                    'Enrollment marked as incomplete'
                );
            } else {
                $this->setErrorMessage('Failed to update enrollment status');
            }
        }
    }

    public function quickApprove()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: enrollments');
            exit;
        }

        $student_id = $_POST['student_id'] ?? null;
        $section_id = $_POST['section_id'] ?? null;

        if (!$student_id || !$section_id) {
            $this->redirectWithError('enrollments', 'Invalid request');
        }

        $admin_id = $this->getCurrentUserId();
        $studentModel = new Student();
        $sectionModel = new Section();

        // Check section availability
        if (!$sectionModel->hasAvailableSlots($section_id)) {
            $this->redirectWithError('enrollments', 'Selected section is full');
        }

        // Approve and assign
        if ($studentModel->updateEnrollmentStatus($student_id, 'Approved', 'Quick approval', $admin_id)) {
            $studentModel->assignToSection($student_id, $section_id);
            $this->redirectWithSuccess('enrollments', 'Student approved successfully!');
        } else {
            $this->redirectWithError('enrollments', 'Failed to approve student');
        }
    }

    // ==================== STUDENT MANAGEMENT ====================

    public function studentManagement()
    {
        $this->requireAdmin();

        $studentModel = new Student();
        $gradeLevelModel = new GradeLevel();
        $sectionModel = new Section();

        // Get filter parameters
        $search = $_GET['search'] ?? '';
        $status_filter = $_GET['status'] ?? '';
        $grade_filter = $_GET['grade'] ?? '';
        $section_filter = $_GET['section'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = 15;
        $offset = ($page - 1) * $per_page;

        // Get filtered students
        $result = $studentModel->getAllStudentsForManagement(
            $search,
            $status_filter,
            $grade_filter,
            $section_filter,
            $per_page,
            $offset
        );

        // Handle CSV export
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            $this->exportAllStudentsToCSV($result['students']);
            exit;
        }

        // Get statistics
        $stats = $studentModel->getStudentStatsByStatus();

        // Get grade levels and sections for filters
        $grade_levels = $gradeLevelModel->getAllGradeLevels();
        $sections = $sectionModel->getAllSections();

        $this->render('students', [
            'pageTitle' => 'Student Management - BESEMS',
            'students' => $result['students'],
            'search' => $search,
            'status_filter' => $status_filter,
            'grade_filter' => $grade_filter,
            'section_filter' => $section_filter,
            'current_page' => $page,
            'total_pages' => ceil($result['total'] / $per_page),
            'total_records' => $result['total'],
            'stats' => $stats,
            'grade_levels' => $grade_levels,
            'sections' => $sections,
            'success_message' => $this->getSuccessMessage(),
            'error_message' => $this->getErrorMessage()
        ]);
    }

    public function viewStudent()
    {
        $this->requireAdmin();

        $student_id = $_GET['id'] ?? null;

        if (!$student_id) {
            $this->redirectWithError('students', 'Student ID is required');
        }

        $studentModel = new Student();
        $student = $studentModel->getStudentById($student_id);

        if (!$student) {
            $this->redirectWithError('students', 'Student not found');
        }

        $this->render('admin/view-student', [
            'pageTitle' => 'View Student - BESEMS',
            'student' => $student
        ]);
    }

    public function editStudent()
    {
        $this->requireAdmin();

        $student_id = $_GET['id'] ?? null;

        if (!$student_id) {
            $this->redirectWithError('students', 'Student ID is required');
        }

        $studentModel = new Student();
        $student = $studentModel->getStudentById($student_id);

        if (!$student) {
            $this->redirectWithError('students', 'Student not found');
        }

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEditStudentSubmission($student_id);
        }

        $this->render('admin/edit-student', [
            'pageTitle' => 'Edit Student - BESEMS',
            'student' => $student,
            'error_message' => $this->getErrorMessage()
        ]);
    }

    private function handleEditStudentSubmission($student_id)
    {
        $studentModel = new Student();
        $errors = [];

        // Validate required fields
        $required = ['lrn', 'first_name', 'last_name', 'date_of_birth', 'gender', 'barangay', 'city_municipality', 'province', 'guardian_relationship'];
        $errors = array_merge($errors, $this->validateRequired($_POST, $required));

        // Validate LRN
        if (!empty($_POST['lrn'])) {
            if (!preg_match('/^[0-9]{12}$/', $_POST['lrn'])) {
                $errors[] = "LRN must be exactly 12 digits";
            } elseif ($studentModel->isLRNTaken($_POST['lrn'], $student_id)) {
                $errors[] = "LRN already exists in the system";
            }
        }

        // Validate contact numbers if provided
        if (!empty($_POST['father_contact'])) {
            $error = $this->validateContactNumber($_POST['father_contact']);
            if ($error) $errors[] = "Father's {$error}";
        }
        if (!empty($_POST['mother_contact'])) {
            $error = $this->validateContactNumber($_POST['mother_contact']);
            if ($error) $errors[] = "Mother's {$error}";
        }

        if (!empty($errors)) {
            $this->setErrorMessage(implode('<br>', $errors));
            return;
        }

        // Prepare data
        $data = $this->prepareStudentData($_POST);

        // Update student
        if ($studentModel->updateStudent($student_id, $data)) {
            $this->redirectWithSuccess(
                "view-student?id={$student_id}",
                "Student information updated successfully!"
            );
        } else {
            $this->setErrorMessage("Failed to update student information");
        }
    }

    public function assignStudentSection()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: students');
            exit;
        }

        $student_id = $_POST['student_id'] ?? null;
        $section_id = $_POST['section_id'] ?? null;

        if (!$student_id) {
            $this->redirectWithError('students', 'Student ID is required');
        }

        $studentModel = new Student();
        $sectionModel = new Section();

        // If section_id is empty, remove assignment
        if (empty($section_id)) {
            if ($studentModel->assignToSection($student_id, null)) {
                $this->redirectWithSuccess('students', 'Student removed from section');
            } else {
                $this->redirectWithError('students', 'Failed to update assignment');
            }
            return;
        }

        // Check section availability
        if (!$sectionModel->hasAvailableSlots($section_id)) {
            $this->redirectWithError('students', 'Selected section is full');
        }

        // Assign to section
        if ($studentModel->assignToSection($student_id, $section_id)) {
            $this->redirectWithSuccess('students', 'Student assigned successfully!');
        } else {
            $this->redirectWithError('students', 'Failed to assign student');
        }
    }

    public function changeStudentStatus()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: students');
            exit;
        }

        $student_id = $_POST['student_id'] ?? null;
        $status = $_POST['status'] ?? null;

        if (!$student_id || !$status) {
            $this->redirectWithError('students', 'Invalid request');
        }

        $valid_statuses = ['Active', 'Inactive', 'Transferred', 'Graduated', 'Dropped'];
        if (!in_array($status, $valid_statuses)) {
            $this->redirectWithError('students', 'Invalid status');
        }

        $studentModel = new Student();

        if ($studentModel->updateStudentStatus($student_id, $status)) {
            $this->redirectWithSuccess('students', "Student status changed to {$status}");
        } else {
            $this->redirectWithError('students', 'Failed to update status');
        }
    }

    private function prepareStudentData($post)
    {
        return [
            'lrn' => trim($post['lrn']),
            'first_name' => trim($post['first_name']),
            'middle_name' => !empty($post['middle_name']) ? trim($post['middle_name']) : null,
            'last_name' => trim($post['last_name']),
            'name_extension' => !empty($post['name_extension']) ? trim($post['name_extension']) : null,
            'date_of_birth' => $post['date_of_birth'],
            'place_of_birth' => !empty($post['place_of_birth']) ? trim($post['place_of_birth']) : null,
            'gender' => $post['gender'],
            'mother_tongue' => !empty($post['mother_tongue']) ? trim($post['mother_tongue']) : null,
            'religion' => !empty($post['religion']) ? trim($post['religion']) : null,
            'indigenous_people' => !empty($post['indigenous_people']) ? trim($post['indigenous_people']) : null,
            'house_number' => !empty($post['house_number']) ? trim($post['house_number']) : null,
            'street_name' => !empty($post['street_name']) ? trim($post['street_name']) : null,
            'barangay' => trim($post['barangay']),
            'city_municipality' => trim($post['city_municipality']),
            'province' => trim($post['province']),
            'region' => !empty($post['region']) ? trim($post['region']) : null,
            'zip_code' => !empty($post['zip_code']) ? trim($post['zip_code']) : null,
            'father_name' => !empty($post['father_name']) ? trim($post['father_name']) : null,
            'father_occupation' => !empty($post['father_occupation']) ? trim($post['father_occupation']) : null,
            'father_contact' => !empty($post['father_contact']) ? trim($post['father_contact']) : null,
            'mother_name' => !empty($post['mother_name']) ? trim($post['mother_name']) : null,
            'mother_occupation' => !empty($post['mother_occupation']) ? trim($post['mother_occupation']) : null,
            'mother_contact' => !empty($post['mother_contact']) ? trim($post['mother_contact']) : null,
            'guardian_name' => !empty($post['guardian_name']) ? trim($post['guardian_name']) : null,
            'guardian_relationship' => $post['guardian_relationship'],
            'guardian_occupation' => !empty($post['guardian_occupation']) ? trim($post['guardian_occupation']) : null,
            'enrollment_type' => $post['enrollment_type'] ?? 'New',
            'previous_school' => !empty($post['previous_school']) ? trim($post['previous_school']) : null,
            'previous_grade_level' => !empty($post['previous_grade_level']) ? trim($post['previous_grade_level']) : null
        ];
    }

    private function exportAllStudentsToCSV($students)
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="all_students_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');

        // CSV Headers
        fputcsv($output, [
            'LRN',
            'Full Name',
            'Gender',
            'Age',
            'Date of Birth',
            'Grade Level',
            'Section',
            'Enrollment Status',
            'Student Status',
            'Address',
            'Father Name',
            'Father Contact',
            'Mother Name',
            'Mother Contact',
            'Guardian Username',
            'Guardian Email',
            'Guardian Contact'
        ]);

        // CSV Data
        foreach ($students as $student) {
            $full_name = trim($student['first_name'] . ' ' .
                ($student['middle_name'] ?? '') . ' ' .
                $student['last_name'] . ' ' .
                ($student['name_extension'] ?? ''));

            $address = trim(($student['house_number'] ?? '') . ' ' .
                ($student['street_name'] ?? '') . ', ' .
                $student['barangay'] . ', ' .
                $student['city_municipality'] . ', ' .
                $student['province']);

            fputcsv($output, [
                $student['lrn'],
                $full_name,
                $student['gender'],
                $student['age'],
                $student['date_of_birth'],
                $student['grade_name'] ?? 'Not Assigned',
                $student['section_name'] ?? 'Not Assigned',
                $student['enrollment_status'] ?? 'Not Enrolled',
                $student['student_status'],
                $address,
                $student['father_name'] ?? 'N/A',
                $student['father_contact'] ?? 'N/A',
                $student['mother_name'] ?? 'N/A',
                $student['mother_contact'] ?? 'N/A',
                $student['guardian_username'],
                $student['guardian_email'],
                $student['guardian_contact']
            ]);
        }

        fclose($output);
    }
}
