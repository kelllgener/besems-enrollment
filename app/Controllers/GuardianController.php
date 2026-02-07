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
}