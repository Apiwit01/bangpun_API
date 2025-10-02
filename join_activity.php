<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

include 'connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$activity_id = $data["activity_id"] ?? null;

if (!$activity_id) {
    echo json_encode(["status" => "error", "message" => "ไม่มีรหัสกิจกรรม"]);
    exit;
}

$sql = "INSERT INTO participants (activity_id) VALUES ('$activity_id')";

if (mysqli_query($conn, $sql)) {
    echo json_encode(["status" => "success", "message" => "เข้าร่วมกิจกรรมสำเร็จ"]);
} else {
    echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาด: " . mysqli_error($conn)]);
}

mysqli_close($conn);
?>
