<?php
require 'db.php'; // เรียกใช้ไฟล์เชื่อมต่อฐานข้อมูล

// ตั้งค่า Header ให้รองรับ JSON
header("Content-Type: application/json; charset=UTF-8");

// ตรวจสอบว่าคำขอเป็น POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    // ตรวจสอบว่ามีข้อมูลครบถ้วนหรือไม่
    if (!empty($data["fullname"]) && !empty($data["email"]) && !empty($data["password"]) && !empty($data["user_type"])) {
        $fullname = $data["fullname"];
        $email = $data["email"];
        $password = password_hash($data["password"], PASSWORD_BCRYPT);
        $user_type = $data["user_type"];

        try {
            // ตรวจสอบว่าอีเมลนี้มีอยู่ในระบบแล้วหรือไม่
            $stmt = $pdo->prepare("SELECT user_id FROM Users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                echo json_encode(["status" => "error", "message" => "Email already exists"]);
                exit;
            }
            
            // เพิ่มข้อมูลผู้ใช้ใหม่
            $stmt = $pdo->prepare("INSERT INTO Users (fullname, email, password, user_type) VALUES (?, ?, ?, ?)");
            $stmt->execute([$fullname, $email, $password, $user_type]);
            
            echo json_encode(["status" => "success", "message" => "Registration successful"]);
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Please provide all required fields"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>