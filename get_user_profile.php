<?php
header('Content-Type: application/json');

// ข้อมูลการเชื่อมต่อฐานข้อมูล
$host = 'localhost';
$user = 'root'; // หรือชื่อผู้ใช้ MySQL ของคุณ
$password = ''; // หรือรหัสผ่าน MySQL ของคุณ
$database = 'bangpun_db'; // เปลี่ยนเป็นชื่อฐานข้อมูลของคุณ

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($host, $user, $password, $database);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'เชื่อมต่อฐานข้อมูลไม่สำเร็จ']);
    exit();
}

// รับค่า user_id จาก URL
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'ไม่พบ user_id']);
    exit();
}

// คำสั่ง SQL ดึงข้อมูลโปรไฟล์ผู้ใช้
$sql = "SELECT user_id, fullname, profile_image FROM users WHERE USER_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // กรณีมีภาพ ให้เพิ่ม URL สมมติว่าคุณเก็บไว้ในโฟลเดอร์ uploads/
    if (!empty($user['profile_image'])) {
        $user['profile_image'] = 'http://10.88.1.203/bangpun_API/uploads/' . $user['profile_image'];
    } else {
        $user['profile_image'] = null;
    }

    echo json_encode([
        'status' => 'success',
        'fullname' => $user['fullname'],
        'profile_image' => $user['profile_image']
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'ไม่พบผู้ใช้']);
}

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();
?>
