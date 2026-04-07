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

        $this->render('enrollment-management', [
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
            $this->redirectWithError('enrollment-management', 'Student ID is required');
        }

        $studentModel = new Student();
        $student = $studentModel->getStudentById($student_id);

        if (!$student) {
            $this->redirectWithError('enrollment-management', 'Student not found');
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

        $this->render('admin/review-enrollment', [
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
                    'enrollment-management?enrollment=Approved',
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
                    'enrollment-management?enrollment=Declined',
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
                    'enrollment-management?enrollment=Incomplete',
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
            header('Location: enrollment-management');
            exit;
        }

        $student_id = $_POST['student_id'] ?? null;
        $section_id = $_POST['section_id'] ?? null;

        if (!$student_id || !$section_id) {
            $this->redirectWithError('enrollment-management', 'Invalid request');
        }

        $admin_id = $this->getCurrentUserId();
        $studentModel = new Student();
        $sectionModel = new Section();

        // Check section availability
        if (!$sectionModel->hasAvailableSlots($section_id)) {
            $this->redirectWithError('enrollment-management', 'Selected section is full');
        }

        // Approve and assign
        if ($studentModel->updateEnrollmentStatus($student_id, 'Approved', 'Quick approval', $admin_id)) {
            $studentModel->assignToSection($student_id, $section_id);
            $this->redirectWithSuccess('enrollment-management', 'Student approved successfully!');
        } else {
            $this->redirectWithError('enrollment-management', 'Failed to approve student');
        }
    }
}
