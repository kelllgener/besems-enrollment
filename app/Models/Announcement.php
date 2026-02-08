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

    // Get published announcements for guardians with filters and pagination
    public function getAnnouncementsWithFilters($type_filter = '', $search = '', $limit = 10, $offset = 0)
    {
        $sql = "
            SELECT 
                a.*,
                u.username as posted_by,
                gl.grade_name
            FROM announcements a
            LEFT JOIN users u ON a.admin_id = u.user_id
            LEFT JOIN grade_levels gl ON a.target_grade_id = gl.grade_id
            WHERE a.is_published = 1
            AND (a.target_audience = 'All' OR a.target_audience = 'Guardians')
            AND (a.expiry_date IS NULL OR a.expiry_date >= CURDATE())
        ";
        
        $count_sql = "
            SELECT COUNT(*) as total
            FROM announcements a
            WHERE a.is_published = 1
            AND (a.target_audience = 'All' OR a.target_audience = 'Guardians')
            AND (a.expiry_date IS NULL OR a.expiry_date >= CURDATE())
        ";
        
        // Add type filter
        if (!empty($type_filter)) {
            $type_filter = $this->db->real_escape_string($type_filter);
            $sql .= " AND a.announcement_type = '{$type_filter}'";
            $count_sql .= " AND a.announcement_type = '{$type_filter}'";
        }
        
        // Add search filter
        if (!empty($search)) {
            $search = $this->db->real_escape_string($search);
            $sql .= " AND (a.title LIKE '%{$search}%' OR a.content LIKE '%{$search}%')";
            $count_sql .= " AND (a.title LIKE '%{$search}%' OR a.content LIKE '%{$search}%')";
        }
        
        // Get total count
        $count_result = $this->db->query($count_sql);
        $total = $count_result->fetch_assoc()['total'];
        
        // Add ordering and pagination
        $sql .= " ORDER BY a.created_at DESC LIMIT {$limit} OFFSET {$offset}";
        
        // Execute query
        $result = $this->db->query($sql);
        
        $announcements = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $announcements[] = $row;
            }
        }
        
        return [
            'announcements' => $announcements,
            'total' => $total
        ];
    }

    // Get published announcements for guardians (simple version)
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

    // Get single announcement by ID
    public function getAnnouncementById($announcement_id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                a.*,
                u.username as posted_by,
                gl.grade_name
            FROM announcements a
            LEFT JOIN users u ON a.admin_id = u.user_id
            LEFT JOIN grade_levels gl ON a.target_grade_id = gl.grade_id
            WHERE a.announcement_id = ?
            AND a.is_published = 1
        ");
        
        $stmt->bind_param("i", $announcement_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    // Get announcements count by type
    public function getAnnouncementCountsByType()
    {
        $result = $this->db->query("
            SELECT 
                announcement_type,
                COUNT(*) as count
            FROM announcements
            WHERE is_published = 1
            AND (target_audience = 'All' OR target_audience = 'Guardians')
            AND (expiry_date IS NULL OR expiry_date >= CURDATE())
            GROUP BY announcement_type
        ");
        
        $counts = [];
        while ($row = $result->fetch_assoc()) {
            $counts[$row['announcement_type']] = $row['count'];
        }
        
        return $counts;
    }
}