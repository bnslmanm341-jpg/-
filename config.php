<?php
$servername = "	sql203.infinityfree.com"; // مثل sql123.infinityfree.com
$username = "if0_40951210_grades"; // عادة نفس اسم القاعدة
$password = "";                       // فارغ في النسخة المجانية
$dbname = "if0_40951210_grades";    // نفس اسم القاعدة

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
