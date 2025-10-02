<?php
// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$host = "localhost";
$dbname = "bangpun_db";
$username = "root"; // เปลี่ยนตามค่าที่ใช้จริง
$password = ""; // เปลี่ยนตามค่าที่ใช้จริง

try {
    // สร้างการเชื่อมต่อด้วย PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // ตั้งค่า PDO ให้แสดงข้อผิดพลาดเมื่อเกิดปัญหา
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // แสดงข้อความข้อผิดพลาดหากเชื่อมต่อไม่สำเร็จ
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $e->getMessage()]));
}
?>