<?php

namespace App\Models;

use App\Core\DbConfig;

class Announcement
{
    private $db;

    public function __construct()
    {
        $dbConfig = new DbConfig();
        $this->db = $dbConfig->getConnection();
    }

    // Get published announcements for guardians
    public function getPublishedAnnouncements($limit = 5)
    {
        $stmt = $this->db->prepare("
            SELECT 
                a.*,
                u.username as posted_by
            FROM announcements a
            LEFT JOIN users u ON a.admin_id = u.user_id
            WHERE a.is_published = 1
            AND (a.target_audience = 'All' OR a.target_audience = 'Guardians')
            AND (a.expiry_date IS NULL OR a.expiry_date >= CURDATE())
            ORDER BY a.created_at DESC
            LIMIT ?
        ");
        
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $announcements = [];
        while ($row = $result->fetch_assoc()) {
            $announcements[] = $row;
        }
        
        return $announcements;
    }
}