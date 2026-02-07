<?php

namespace App\Controllers;

use App\Models\Announcement;
use App\Models\Dashboard;
use App\Models\Student;

class DashboardController extends BaseController
{
    public function adminDashboard()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: login');
            exit;
        }

        $name = $_SESSION['name'] ?? 'User';
        
        // Get dashboard data
        $dashboardModel = new Dashboard();
        
        try {
            $stats = $dashboardModel->getAllStats();
            $students_per_grade = $dashboardModel->getStudentsPerGrade();
            $enrollment_status = $dashboardModel->getEnrollmentStatus();
            $recent_students = $dashboardModel->getRecentStudents(5);
            
            // Render the dashboard view
            $this->renderAdmin('dashboard', [
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
            
            $this->renderAdmin('admin/dashboard', [
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

    public function guardianDashboard()
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