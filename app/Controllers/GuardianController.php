<?php

namespace App\Controllers;

use App\Models\Student;
use App\Models\Announcement;
use App\Models\Requirement;
use App\Models\User;

class GuardianController extends BaseController
{
    public function dashboard()
    {
        $this->requireGuardian();
        
        $guardian_id = $this->getCurrentUserId();
        $name = $_SESSION['name'] ?? 'Guardian';
        
        $studentModel = new Student();
        $announcementModel = new Announcement();
        
        $this->render('dashboard', [
            'pageTitle' => 'Guardian Dashboard - BESEMS',
            'name' => $name,
            'students' => $studentModel->getStudentsByGuardian($guardian_id),
            'student_counts' => $studentModel->getStudentCountsByStatus($guardian_id),
            'enrollment_counts' => $studentModel->getEnrollmentStatusCounts($guardian_id),
            'announcements' => $announcementModel->getPublishedAnnouncements(5)
        ]);
    }

    // ==================== MY STUDENTS ====================
    
    public function myStudents()
    {
        $this->requireGuardian();
        
        $guardian_id = $this->getCurrentUserId();
        $studentModel = new Student();

        // Get filter parameters
        $search = $_GET['search'] ?? '';
        $status_filter = $_GET['status'] ?? '';
        $enrollment_filter = $_GET['enrollment'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = 10;
        $offset = ($page - 1) * $per_page;

        // Get filtered students
        $result = $studentModel->getStudentsWithFilters(
            $guardian_id, 
            $search, 
            $status_filter, 
            $enrollment_filter,
            $per_page,
            $offset
        );

        // Handle CSV export
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            $this->exportStudentsToCSV($result['students']);
            exit;
        }

        $this->render('my-students', [
            'pageTitle' => 'My Children - BESEMS',
            'students' => $result['students'],
            'search' => $search,
            'status_filter' => $status_filter,
            'enrollment_filter' => $enrollment_filter,
            'current_page' => $page,
            'total_pages' => ceil($result['total'] / $per_page),
            'total_records' => $result['total'],
            'per_page' => $per_page
        ]);
    }

    // ==================== ADD STUDENT ====================
    
    public function addStudent()
    {
        $this->requireGuardian();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleAddStudentSubmission();
        } else {
            $this->render('add-student', [
                'pageTitle' => 'Add Student - BESEMS'
            ]);
        }
    }

    private function handleAddStudentSubmission()
    {
        $studentModel = new Student();
        $requirementModel = new Requirement();
        $errors = [];

        // Validate required fields
        $required = ['lrn', 'first_name', 'last_name', 'date_of_birth', 'gender', 'barangay', 'city_municipality', 'province', 'guardian_relationship'];
        $errors = array_merge($errors, $this->validateRequired($_POST, $required));

        // Validate LRN
        if (!empty($_POST['lrn'])) {
            if (!preg_match('/^[0-9]{12}$/', $_POST['lrn'])) {
                $errors[] = "LRN must be exactly 12 digits";
            } elseif ($studentModel->findByLRN($_POST['lrn'])) {
                $errors[] = "LRN already exists in the system";
            }
        }

        // Validate age
        if (!empty($_POST['date_of_birth'])) {
            $age = (new \DateTime($_POST['date_of_birth']))->diff(new \DateTime('today'))->y;
            if ($age < 5 || $age > 15) {
                $errors[] = "Student age must be between 5 and 15 years old";
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
            $this->render('add-student', [
                'pageTitle' => 'Add Student - BESEMS',
                'errors' => $errors,
                'old' => $_POST
            ]);
            return;
        }

        // Prepare data
        $data = $this->prepareStudentData($_POST);
        
        // Create student
        $student_id = $studentModel->createStudent($data);

        if ($student_id) {
            $requirementModel->createStudentRequirements($student_id);
            $this->redirectWithSuccess(
                "requirements?student_id={$student_id}",
                "Student added successfully! Please upload the required documents."
            );
        } else {
            $this->render('add-student', [
                'pageTitle' => 'Add Student - BESEMS',
                'errors' => ["Failed to add student. Please try again."],
                'old' => $_POST
            ]);
        }
    }

    private function prepareStudentData($post)
    {
        return [
            'guardian_id' => $this->getCurrentUserId(),
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

    // ==================== REQUIREMENTS ====================
    
    public function requirements()
    {
        $this->requireGuardian();
        
        $guardian_id = $this->getCurrentUserId();
        $studentModel = new Student();

        $all_students = $studentModel->getStudentsListByGuardian($guardian_id);
        $student_id = $_GET['student_id'] ?? ($all_students[0]['student_id'] ?? null);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
            $this->handleRequirementsSubmission($student_id);
        }

        $student = $student_id ? $studentModel->getStudentWithRequirements($student_id, $guardian_id) : null;

        $this->render('requirements', [
            'pageTitle' => 'Student Requirements - BESEMS',
            'all_students' => $all_students,
            'student' => $student,
            'selected_student_id' => $student_id,
            'success_message' => $this->getSuccessMessage(),
            'error_message' => $this->getErrorMessage()
        ]);
    }

    private function handleRequirementsSubmission($student_id)
    {
        $studentModel = new Student();
        $requirementModel = new Requirement();
        
        // Verify authorization
        if (!$studentModel->belongsToGuardian($student_id, $this->getCurrentUserId())) {
            $this->redirectWithError('requirements', 'Unauthorized access');
        }

        $requirements = [
            'birth_certificate' => isset($_POST['birth_certificate']) ? 1 : 0,
            'report_card_form137' => isset($_POST['report_card_form137']) ? 1 : 0,
            'good_moral_certificate' => isset($_POST['good_moral_certificate']) ? 1 : 0,
            'certificate_of_completion' => isset($_POST['certificate_of_completion']) ? 1 : 0,
            'id_picture_2x2' => isset($_POST['id_picture_2x2']) ? 1 : 0,
            'transfer_credential' => isset($_POST['transfer_credential']) ? 1 : 0,
            'medical_certificate' => isset($_POST['medical_certificate']) ? 1 : 0
        ];

        $all_required = $requirements['birth_certificate'] && 
                       $requirements['report_card_form137'] && 
                       $requirements['good_moral_certificate'] && 
                       $requirements['certificate_of_completion'] && 
                       $requirements['id_picture_2x2'];

        $requirements['enrollment_status'] = $all_required ? 'For Review' : 'Incomplete';

        if ($requirementModel->updateRequirements($student_id, $requirements)) {
            $message = $all_required 
                ? "Requirements updated successfully! Your enrollment is now ready for admin review."
                : "Requirements updated. Please complete all required documents.";
            $this->setSuccessMessage($message);
        } else {
            $this->setErrorMessage("Failed to update requirements. Please try again.");
        }
    }

    // ==================== SETTINGS ====================
    
    public function settings()
    {
        $this->requireGuardian();
        
        $guardian_id = $this->getCurrentUserId();
        $userModel = new User();
        $user = $userModel->findById($guardian_id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSettingsSubmission($userModel, $user);
        }

        $this->render('settings', [
            'pageTitle' => 'Settings - BESEMS',
            'user' => $user,
            'success_message' => $this->getSuccessMessage(),
            'error_message' => $this->getErrorMessage()
        ]);
    }

    private function handleSettingsSubmission($userModel, $user)
    {
        $action = $_POST['action'] ?? '';

        if ($action === 'update_profile') {
            $this->handleProfileUpdate($userModel, $user);
        } elseif ($action === 'change_password') {
            $this->handlePasswordChange($userModel, $user);
        }
    }

    private function handleProfileUpdate($userModel, $user)
    {
        $errors = [];

        $email_error = $this->validateEmail($_POST['email']);
        if ($email_error) $errors[] = $email_error;

        $contact_error = $this->validateContactNumber($_POST['contact_number']);
        if ($contact_error) $errors[] = $contact_error;

        // Check email uniqueness
        if ($_POST['email'] !== $user['email']) {
            $existing = $userModel->findByEmail($_POST['email']);
            if ($existing && $existing['user_id'] != $user['user_id']) {
                $errors[] = "Email is already used by another account";
            }
        }

        if (empty($errors)) {
            $data = [
                'email' => trim($_POST['email']),
                'contact_number' => trim($_POST['contact_number'])
            ];

            if ($userModel->updateProfile($user['user_id'], $data)) {
                $_SESSION['email'] = $data['email'];
                $this->setSuccessMessage("Profile updated successfully!");
            } else {
                $this->setErrorMessage("Failed to update profile");
            }
        } else {
            $this->setErrorMessage(implode('<br>', $errors));
        }
    }

    private function handlePasswordChange($userModel, $user)
    {
        $errors = [];

        if (empty($_POST['current_password'])) {
            $errors[] = "Current password is required";
        } elseif (!$userModel->verifyPassword($user, $_POST['current_password'])) {
            $errors[] = "Current password is incorrect";
        }

        $password_error = $this->validatePassword($_POST['new_password']);
        if ($password_error) $errors[] = $password_error;

        if ($_POST['new_password'] !== $_POST['confirm_password']) {
            $errors[] = "New passwords do not match";
        }

        if (empty($errors)) {
            if ($userModel->updatePassword($user['user_id'], $_POST['new_password'])) {
                $this->setSuccessMessage("Password changed successfully!");
            } else {
                $this->setErrorMessage("Failed to change password");
            }
        } else {
            $this->setErrorMessage(implode('<br>', $errors));
        }
    }

    // ==================== ANNOUNCEMENTS ====================
    
    public function announcements()
    {
        $this->requireGuardian();
        
        $announcementModel = new Announcement();

        $type_filter = $_GET['type'] ?? '';
        $search = $_GET['search'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = 9;
        $offset = ($page - 1) * $per_page;

        $result = $announcementModel->getAnnouncementsWithFilters($type_filter, $search, $per_page, $offset);

        $this->render('announcements', [
            'pageTitle' => 'Announcements - BESEMS',
            'announcements' => $result['announcements'],
            'type_filter' => $type_filter,
            'search' => $search,
            'current_page' => $page,
            'total_pages' => ceil($result['total'] / $per_page),
            'total_records' => $result['total'],
            'counts_by_type' => $announcementModel->getAnnouncementCountsByType()
        ]);
    }

    public function viewAnnouncement()
    {
        $this->requireGuardian();
        
        $announcement_id = $_GET['id'] ?? null;
        
        if (!$announcement_id) {
            header('Location: announcements');
            exit;
        }

        $announcementModel = new Announcement();
        $announcement = $announcementModel->getAnnouncementById($announcement_id);

        if (!$announcement) {
            $this->redirectWithError('announcements', "Announcement not found");
        }

        $this->render('announcement', [
            'pageTitle' => 'Announcement - BESEMS',
            'announcement' => $announcement
        ]);
    }

    // ==================== HELPER METHODS ====================
    
    private function exportStudentsToCSV($students)
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="my_students_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        fputcsv($output, ['LRN', 'Full Name', 'Gender', 'Age', 'Date of Birth', 'Grade Level', 'Section', 'Enrollment Status', 'Student Status', 'Address', 'Father Name', 'Mother Name', 'Contact Number']);
        
        foreach ($students as $student) {
            $full_name = trim($student['first_name'] . ' ' . ($student['middle_name'] ?? '') . ' ' . $student['last_name'] . ' ' . ($student['name_extension'] ?? ''));
            $address = trim(($student['house_number'] ?? '') . ' ' . ($student['street_name'] ?? '') . ', ' . $student['barangay'] . ', ' . $student['city_municipality'] . ', ' . $student['province']);
            
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
                $student['mother_name'] ?? 'N/A',
                $student['father_contact'] ?? $student['mother_contact'] ?? 'N/A'
            ]);
        }
        
        fclose($output);
    }
}