<?php

namespace App\Models;

use App\Core\DbConfig;

class Requirement
{
    private $db;

    public function __construct()
    {
        $dbConfig = new DbConfig();
        $this->db = $dbConfig->getConnection();
    }

    // Create student requirements record
    public function createStudentRequirements($student_id)
    {
        $stmt = $this->db->prepare("
        INSERT INTO student_requirements (student_id, enrollment_status, submitted_at) 
        VALUES (?, 'Pending', NOW())
    ");

        $stmt->bind_param("i", $student_id);
        return $stmt->execute();
    }

    // Update student requirements
    public function updateRequirements($student_id, $requirements)
    {
        $stmt = $this->db->prepare("
        UPDATE student_requirements 
        SET 
            birth_certificate = ?,
            report_card_form137 = ?,
            good_moral_certificate = ?,
            certificate_of_completion = ?,
            id_picture_2x2 = ?,
            transfer_credential = ?,
            medical_certificate = ?,
            enrollment_status = ?,
            submitted_at = NOW(),
            updated_at = NOW()
        WHERE student_id = ?
    ");

        $stmt->bind_param(
            "iiiiiiisi",
            $requirements['birth_certificate'],
            $requirements['report_card_form137'],
            $requirements['good_moral_certificate'],
            $requirements['certificate_of_completion'],
            $requirements['id_picture_2x2'],
            $requirements['transfer_credential'],
            $requirements['medical_certificate'],
            $requirements['enrollment_status'],
            $student_id
        );

        return $stmt->execute();
    }
}
