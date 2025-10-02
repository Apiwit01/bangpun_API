<?php
header("Content-Type: application/json; charset=UTF-8");

$servername = "localhost";
$username = "root"; // ถ้าใช้ XAMPP ปกติจะเป็น root
$password = ""; // ถ้าไม่ได้ตั้งรหัสผ่าน ให้เว้นว่าง
$dbname = "bangpun_db"; // 🔹 ใส่ชื่อฐานข้อมูลของคุณที่ถูกต้อง

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// ดึงข้อมูลจากตาราง activities
$sql = "SELECT * FROM activities ORDER BY activity_id DESC";
$result = $conn->query($sql);

$activities = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }
}

// ส่ง JSON กลับไป
echo json_encode($activities, JSON_UNESCAPED_UNICODE);

$conn->close();
?>
