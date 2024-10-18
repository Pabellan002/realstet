<?php

class Sale {
    public static function getRecent($db, $limit) {
        $sql = "SELECT s.*, p.title as property_title FROM sales s JOIN properties p ON s.property_id = p.id ORDER BY s.sale_date DESC LIMIT ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getMonthlyData($db, $months) {
        $sql = "SELECT DATE_FORMAT(sale_date, '%Y-%m') as month, SUM(sale_price) as total 
                FROM sales 
                WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL ? MONTH) 
                GROUP BY DATE_FORMAT(sale_date, '%Y-%m') 
                ORDER BY month";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $months);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
