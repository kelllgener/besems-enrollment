<?php

namespace App\Controllers;

use App\Models\Student;
use App\Models\Announcement;

class GuardianController extends BaseController
{
    public function dashboard()
    {
        // Check if user is logged in and is a guardian
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guardian') {
            header('Location: login');
            exit;
        }

        $guardian_id = $_SESSION['user_id'];
        $name = $_SESSION['name'] ?? 'Guardian';

        // Get student data
        $studentModel = new Student();
        $students = $studentModel->getStudentsByGuardian($guardian_id);
        $student_counts = $studentModel->getStudentCountsByStatus($guardian_id);
        $enrollment_counts = $studentModel->getEnrollmentStatusCounts($guardian_id);

        // Get announcements
        $announcementModel = new Announcement();
        $announcements = $announcementModel->getPublishedAnnouncements(5);

        $this->renderGuardian('dashboard', [
            'pageTitle' => 'Guardian Dashboard - BESEMS',
            'name' => $name,
            'students' => $students,
            'student_counts' => $student_counts,
            'enrollment_counts' => $enrollment_counts,
            'announcements' => $announcements
        ]);
    }

    public function addStudent()
    {
        // Check if user is logged in and is a guardian
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guardian') {
            header('Location: login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $studentModel = new Student();

            // Validate required fields
            $required_fields = [
                'lrn', 'first_name', 'last_name', 'date_of_birth', 'gender',
                'barangay', 'city_municipality', 'province', 'guardian_relationship'
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
                    // Create student requirements record
                    $studentModel->createStudentRequirements($student_id);
                    
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
}