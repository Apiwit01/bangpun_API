<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug ข้อมูลที่รับมา
    error_log("Received POST data: " . print_r($_POST, true));
    error_log("Received FILES data: " . print_r($_FILES, true));

    // รับค่าจาก React Native
    $activity_id = uniqid(); // 🔥 ใช้ UUID (เปลี่ยนเป็นระบบที่รองรับถ้าต้องการ)
    $title = $_POST['title'] ?? null;
    $details = $_POST['details'] ?? null;
    $location = $_POST['location'] ?? null;
    $contact = $_POST['contact'] ?? null;
    
    // ตรวจสอบว่าข้อมูลครบหรือไม่
    if (!$title || !$details || !$location || !$contact) {
        error_log("❌ Missing required fields.");
        echo json_encode(["status" => "error", "message" => "กรุณากรอกข้อมูลให้ครบ"]);
        exit;
    }

    // ตรวจสอบและอัปโหลดรูปภาพ
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $image_url = null;

    if (!empty($_FILES["image"]["name"])) {
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = "http://10.88.1.203/bangpun_API/" . $target_file;
        } else {
            error_log("❌ Image upload failed.");
            echo json_encode(["status" => "error", "message" => "อัปโหลดรูปภาพไม่สำเร็จ"]);
            exit;
        }
    }

    // 🔥 **คำสั่ง SQL ใหม่ ใช้ `activity_id` และ `image_url`**
    $sql = "INSERT INTO activities (activity_id, title, details, location, contact, image_url) 
            VALUES ('$activity_id', '$title', '$details', '$location', '$contact', '$image_url')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["status" => "success", "message" => "เพิ่มกิจกรรมสำเร็จ"]);
    } else {
        error_log("❌ SQL Error: " . mysqli_error($conn));
        echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาด: " . mysqli_error($conn)]);
    }

    mysqli_close($conn);
}
?>
