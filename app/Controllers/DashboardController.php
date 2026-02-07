<?php

namespace App\Controllers;

use App\Models\Dashboard;

class DashboardController extends BaseController
{
    public function index()
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
            
            $this->render('dashboard', [
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
}