<?php

class Inquiry {
    public static function getRecent($db, $limit) {
        $sql = "SELECT i.*, p.title as property_title FROM inquiries i JOIN properties p ON i.property_id = p.id ORDER BY i.inquiry_date DESC LIMIT ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
