<?php
require_once 'config.php';

$message = '';

// جلب الطلاب
$students = $pdo->query(
    "SELECT student_id, registration_number, student_name 
     FROM students 
     ORDER BY student_name ASC"
)->fetchAll();

// جلب المواد
$courses = $pdo->query(
    "SELECT course_id, course_code, course_name 
     FROM courses 
     ORDER BY course_name ASC"
)->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $student_id = (int)($_POST['student_id'] ?? 0);
    $course_id  = (int)($_POST['course_id'] ?? 0);
    $grade      = (float)($_POST['grade'] ?? -1);

    if ($student_id > 0 && $course_id > 0 && $grade >= 0 && $grade <= 100) {
        try {
            $sql = "INSERT INTO grades (student_id, course_id, grade)
                    VALUES (?, ?, ?)
                    ON DUPLICATE KEY UPDATE grade = VALUES(grade)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$student_id, $course_id, $grade]);

            $message = '<p style="color:green;">تم تسجيل / تحديث الدرجة بنجاح ✅</p>';

        } catch (PDOException $e) {
            $message = '<p style="color:red;">خطأ في تسجيل الدرجة: '
                     . htmlspecialchars($e->getMessage()) .
                     '</p>';
        }
    } else {
        $message = '<p style="color:red;">الرجاء إدخال بيانات صحيحة (الدرجة بين 0 و 100).</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إدخال درجات الطلاب</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 20px;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #dc3545;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>إدخال درجات الطلاب</h1>

    <?= $message ?>

    <form method="POST">
        <label>الطالب (رقم القيد - الاسم):</label>
        <select name="student_id" required>
            <option value="0">-- اختر الطالب --</option>
            <?php foreach ($students as $student): ?>
                <option value="<?= htmlspecialchars($student['student_id']) ?>">
                    <?= htmlspecialchars($student['registration_number']) ?> - 
                    <?= htmlspecialchars($student['student_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>المادة:</label>
        <select name="course_id" required>
            <option value="0">-- اختر المادة --</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?= htmlspecialchars($course['course_id']) ?>">
                    <?= htmlspecialchars($course['course_code']) ?> - 
                    <?= htmlspecialchars($course['course_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>الدرجة (من 0 إلى 100):</label>
        <input type="number" name="grade" step="0.01" min="0" max="100" required>

        <input type="submit" value="تسجيل الدرجة">
    </form>

    <p style="text-align:center; margin-top:20px;">
        <a href="add_student.php">تسجيل طالب</a> |
        <a href="view_grades.php">عرض النتائج</a>
    </p>
</div>

</body>
</html>
