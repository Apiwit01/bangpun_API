<?php
require 'db.php'; // เรียกใช้ไฟล์เชื่อมต่อฐานข้อมูล

// ตั้งค่า Header ให้รองรับ JSON
header("Content-Type: application/json; charset=UTF-8");

try {
    // ดึงข้อมูลผู้ใช้ทั้งหมดจากฐานข้อมูล
    $stmt = $pdo->query("SELECT user_id, fullname, email, user_type, created_at FROM Users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "message" => "Users fetched successfully",
        "users" => $users
    ]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
