<?php
$host = "localhost"; 
$user = "root"; // XAMPP ใช้ root เป็นค่าเริ่มต้น
$password = ""; // ค่าเริ่มต้นของ XAMPP ไม่มีรหัสผ่าน
$database = "bangpun_db"; // แก้เป็นชื่อฐานข้อมูลของคุณ

$conn = mysqli_connect($host, $user, $password, $database);

// เช็คการเชื่อมต่อ
if (!$conn) {
    die("เชื่อมต่อฐานข้อมูลไม่สำเร็จ: " . mysqli_connect_error());
}
?>
