<?php
require 'db.php'; // เรียกใช้ไฟล์เชื่อมต่อฐานข้อมูล

// ตั้งค่า Header ให้รองรับ JSON
header("Content-Type: application/json; charset=UTF-8");

// ตรวจสอบว่าเป็นคำขอแบบ POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    // ตรวจสอบว่ามีการส่ง email และ password มาหรือไม่
    if (!empty($data["email"]) && !empty($data["password"])) {
        $email = $data["email"];
        $password = $data["password"];

        try {
            // ค้นหาผู้ใช้จากฐานข้อมูล
            $stmt = $pdo->prepare("SELECT user_id, fullname, email, password, user_type FROM Users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user["password"])) {
                // สำเร็จ: ส่งข้อมูลผู้ใช้กลับไป
                echo json_encode([
                    "status" => "success",
                    "message" => "Login successful",
                    "user" => [
                        "user_id" => $user["user_id"],
                        "fullname" => $user["fullname"],
                        "email" => $user["email"],
                        "user_type" => $user["user_type"]
                    ]
                ]);
            } else {
                // ล้มเหลว: อีเมลหรือรหัสผ่านไม่ถูกต้อง
                echo json_encode(["status" => "error", "message" => "Invalid email or password"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Please provide email and password"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>