<?php

namespace App\Controllers;

class GuardianController extends BaseController
{
    public function addStudent()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: login');
            exit;
        }

        $this->renderGuardian('add-student', [
            'pageTitle' => 'BESEMS - Add Student',
            'name' => $_SESSION['name'] ?? 'Guardian'
        ]);
    }
}
