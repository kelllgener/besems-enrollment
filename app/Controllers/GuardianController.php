<?php

namespace App\Controllers;

use App\Models\Student;
use App\Models\Announcement;
use App\Models\Requirement;

class GuardianController extends BaseController
{
    public function myStudents()
    {
        // Check if user is logged in and is a guardian
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guardian') {
            header('Location: login');
            exit;
        }

        $guardian_id = $_SESSION['user_id'];
        $studentModel = new Student();

        // Get filter parameters
        $search = $_GET['search'] ?? '';
        $status_filter = $_GET['status'] ?? '';
        $enrollment_filter = $_GET['enrollment'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = 10;
        $offset = ($page - 1) * $per_page;

        // Get filtered students with pagination
        $result = $studentModel->getStudentsWithFilters(
            $guardian_id,
            $search,
            $status_filter,
            $enrollment_filter,
            $per_page,
            $offset
        );

        $students = $result['students'];
        $total_records = $result['total'];
        $total_pages = ceil($total_records / $per_page);

        // Handle CSV export
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            $this->exportStudentsToCSV($students);
            exit;
        }

        $this->renderGuardian('my-students', [
            'pageTitle' => 'My Children - BESEMS',
            'students' => $students,
            'search' => $search,
            'status_filter' => $status_filter,
            'enrollment_filter' => $enrollment_filter,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_records' => $total_records,
            'per_page' => $per_page
        ]);
    }

    public function addStudent()
    {
        // Check if user is logged in and is a guardian
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guardian') {
            header('Location: login');
            exit;
        }

        $requirementModel = new Requirement();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $studentModel = new Student();

            // Validate required fields
            $required_fields = [
                'lrn',
                'first_name',
                'last_name',
                'date_of_birth',
                'gender',
                'barangay',
                'city_municipality',
                'province',
                'guardian_relationship'
            ];

            foreach ($required_fields as $field) {
                if (empty($_POST[$field])) {
                    $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required";
                }
            }

            // Validate LRN (12 digits)
            if (!empty($_POST['lrn'])) {
                if (!preg_match('/^[0-9]{12}$/', $_POST['lrn'])) {
                    $errors[] = "LRN must be exactly 12 digits";
                }
                // Check if LRN already exists
                if ($studentModel->findByLRN($_POST['lrn'])) {
                    $errors[] = "LRN already exists in the system";
                }
            }

            // Validate date of birth
            if (!empty($_POST['date_of_birth'])) {
                $dob = new \DateTime($_POST['date_of_birth']);
                $today = new \DateTime('today');
                $age = $dob->diff($today)->y;

                if ($age < 5 || $age > 15) {
                    $errors[] = "Student age must be between 5 and 15 years old for elementary enrollment";
                }
            }

            // Validate contact numbers (if provided)
            if (!empty($_POST['father_contact']) && !preg_match('/^09[0-9]{9}$/', $_POST['father_contact'])) {
                $errors[] = "Father's contact number must be in format: 09XXXXXXXXX";
            }
            if (!empty($_POST['mother_contact']) && !preg_match('/^09[0-9]{9}$/', $_POST['mother_contact'])) {
                $errors[] = "Mother's contact number must be in format: 09XXXXXXXXX";
            }

            // If no errors, create the student
            if (empty($errors)) {
                $data = [
                    'guardian_id' => $_SESSION['user_id'],
                    'lrn' => trim($_POST['lrn']),
                    'first_name' => trim($_POST['first_name']),
                    'middle_name' => !empty($_POST['middle_name']) ? trim($_POST['middle_name']) : null,
                    'last_name' => trim($_POST['last_name']),
                    'name_extension' => !empty($_POST['name_extension']) ? trim($_POST['name_extension']) : null,
                    'date_of_birth' => $_POST['date_of_birth'],
                    'place_of_birth' => !empty($_POST['place_of_birth']) ? trim($_POST['place_of_birth']) : null,
                    'gender' => $_POST['gender'],
                    'mother_tongue' => !empty($_POST['mother_tongue']) ? trim($_POST['mother_tongue']) : null,
                    'religion' => !empty($_POST['religion']) ? trim($_POST['religion']) : null,
                    'indigenous_people' => !empty($_POST['indigenous_people']) ? trim($_POST['indigenous_people']) : null,
                    'house_number' => !empty($_POST['house_number']) ? trim($_POST['house_number']) : null,
                    'street_name' => !empty($_POST['street_name']) ? trim($_POST['street_name']) : null,
                    'barangay' => trim($_POST['barangay']),
                    'city_municipality' => trim($_POST['city_municipality']),
                    'province' => trim($_POST['province']),
                    'region' => !empty($_POST['region']) ? trim($_POST['region']) : null,
                    'zip_code' => !empty($_POST['zip_code']) ? trim($_POST['zip_code']) : null,
                    'father_name' => !empty($_POST['father_name']) ? trim($_POST['father_name']) : null,
                    'father_occupation' => !empty($_POST['father_occupation']) ? trim($_POST['father_occupation']) : null,
                    'father_contact' => !empty($_POST['father_contact']) ? trim($_POST['father_contact']) : null,
                    'mother_name' => !empty($_POST['mother_name']) ? trim($_POST['mother_name']) : null,
                    'mother_occupation' => !empty($_POST['mother_occupation']) ? trim($_POST['mother_occupation']) : null,
                    'mother_contact' => !empty($_POST['mother_contact']) ? trim($_POST['mother_contact']) : null,
                    'guardian_name' => !empty($_POST['guardian_name']) ? trim($_POST['guardian_name']) : null,
                    'guardian_relationship' => $_POST['guardian_relationship'],
                    'guardian_occupation' => !empty($_POST['guardian_occupation']) ? trim($_POST['guardian_occupation']) : null,
                    'enrollment_type' => $_POST['enrollment_type'] ?? 'New',
                    'previous_school' => !empty($_POST['previous_school']) ? trim($_POST['previous_school']) : null,
                    'previous_grade_level' => !empty($_POST['previous_grade_level']) ? trim($_POST['previous_grade_level']) : null
                ];

                $student_id = $studentModel->createStudent($data);

                if ($student_id) {
                    $requirementModel->createStudentRequirements($student_id);

                    $_SESSION['student_added_success'] = "Student added successfully! Please upload the required documents.";
                    header("Location: requirements?student_id=" . $student_id);
                    exit();
                } else {
                    $errors[] = "Failed to add student. Please try again.";
                }
            }

            // If there are errors, show the form again with errors
            $this->renderGuardian('add-student', [
                'pageTitle' => 'Add Student - BESEMS',
                'errors' => $errors,
                'old' => $_POST,
            ]);
        } else {
            $this->renderGuardian('add-student', [
                'pageTitle' => 'Add Student - BESEMS'
            ]);
        }
    }


    private function exportStudentsToCSV($students)
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="my_students_' . date('Y-m-d') . '.csv"');

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
            'Mother Name',
            'Contact Number'
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
                $student['mother_name'] ?? 'N/A',
                $student['father_contact'] ?? $student['mother_contact'] ?? 'N/A'
            ]);
        }

        fclose($output);
    }

    public function requirements()
    {
        // Check if user is logged in and is a guardian
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guardian') {
            header('Location: login');
            exit;
        }

        $requirementModel = new Requirement();
        $guardian_id = $_SESSION['user_id'];
        $studentModel = new Student();

        // Get all students for dropdown
        $all_students = $studentModel->getStudentsListByGuardian($guardian_id);

        // Get selected student ID
        $student_id = $_GET['student_id'] ?? null;

        // If no student selected but students exist, select the first one
        if (!$student_id && !empty($all_students)) {
            $student_id = $all_students[0]['student_id'];
        }

        $student = null;
        $success_message = null;
        $error_message = null;

        if ($student_id) {
            // Get student details with requirements
            $student = $studentModel->getStudentWithRequirements($student_id, $guardian_id);

            // Handle form submission
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id']) && $_POST['student_id'] == $student_id) {

                // Collect requirements data
                $requirements = [
                    'birth_certificate' => isset($_POST['birth_certificate']) ? 1 : 0,
                    'report_card_form137' => isset($_POST['report_card_form137']) ? 1 : 0,
                    'good_moral_certificate' => isset($_POST['good_moral_certificate']) ? 1 : 0,
                    'certificate_of_completion' => isset($_POST['certificate_of_completion']) ? 1 : 0,
                    'id_picture_2x2' => isset($_POST['id_picture_2x2']) ? 1 : 0,
                    'transfer_credential' => isset($_POST['transfer_credential']) ? 1 : 0,
                    'medical_certificate' => isset($_POST['medical_certificate']) ? 1 : 0
                ];

                // Check if all required documents are submitted
                $all_required_submitted = $requirements['birth_certificate'] &&
                    $requirements['report_card_form137'] &&
                    $requirements['good_moral_certificate'] &&
                    $requirements['certificate_of_completion'] &&
                    $requirements['id_picture_2x2'];

                // Determine enrollment status
                if ($all_required_submitted) {
                    $requirements['enrollment_status'] = 'For Review';
                } else {
                    $requirements['enrollment_status'] = 'Incomplete';
                }

                // Update requirements
                if ($requirementModel->updateRequirements($student_id, $requirements)) {
                    $success_message = "Requirements updated successfully! " .
                        ($all_required_submitted ? "Your enrollment is now ready for admin review." : "Please complete all required documents.");

                    // Refresh student data
                    $student = $studentModel->getStudentWithRequirements($student_id, $guardian_id);
                } else {
                    $error_message = "Failed to update requirements. Please try again.";
                }
            }
        }

        // Check for success message from add-student
        if (isset($_SESSION['student_added_success'])) {
            $success_message = $_SESSION['student_added_success'];
            unset($_SESSION['student_added_success']);
        }

        $this->renderGuardian('requirements', [
            'pageTitle' => 'Student Requirements - BESEMS',
            'all_students' => $all_students,
            'student' => $student,
            'selected_student_id' => $student_id,
            'success_message' => $success_message,
            'error_message' => $error_message
        ]);
    }
}
