<?php
require_once 'config.php';

$message = '';
$levels = ['1', '2', '3', '4'];

// جلب الأقسام
$departments = fetch_all_data($pdo, 'departments', 'dept_name');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $student_name  = trim($_POST['name'] ?? '');
    $student_email = trim($_POST['email'] ?? '');
    $dept_id       = (int)($_POST['dept_id'] ?? 0);
    $level         = $_POST['level'] ?? '';

    if ($student_name !== '' && $student_email !== '' && $dept_id > 0 && in_array($level, $levels)) {
        try {
            // توليد رقم القيد (مثال: 2026-0001)
            $year = date('Y');
            $last_id = $pdo->query("SELECT student_id FROM students ORDER BY student_id DESC LIMIT 1")->fetchColumn();
            $last_id = $last_id ?: 0;

            $registration_number = $year . '-' . str_pad($last_id + 1, 4, '0', STR_PAD_LEFT);

            $sql = "INSERT INTO students 
                    (registration_number, student_name, email, dept_id, level)
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $registration_number,
                $student_name,
                $student_email,
                $dept_id,
                $level
            ]);

            $message = '<p style="color:green;">تم تسجيل الطالب بنجاح ✅<br>رقم القيد: <strong>'
                     . htmlspecialchars($registration_number) .
                     '</strong></p>';

        } catch (PDOException $e) {
            $message = '<p style="color:red;">خطأ في التسجيل: '
                     . htmlspecialchars($e->getMessage()) .
                     '</p>';
        }
    } else {
        $message = '<p style="color:red;">الرجاء تعبئة جميع الحقول بشكل صحيح.</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل طالب جديد</title>
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
            color: #007bff;
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
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>تسجيل طالب جديد</h1>

    <?= $message ?>

    <form method="POST">
        <label>اسم الطالب الكامل:</label>
        <input type="text" name="name" required>

        <label>البريد الإلكتروني:</label>
        <input type="email" name="email" required>

        <label>القسم:</label>
        <select name="dept_id" required>
            <option value="0">-- اختر القسم --</option>
            <?php foreach ($departments as $dept): ?>
                <option value="<?= htmlspecialchars($dept['dept_id']) ?>">
                    <?= htmlspecialchars($dept['dept_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>المستوى الدراسي:</label>
        <select name="level" required>
            <option value="">-- اختر المستوى --</option>
            <?php foreach ($levels as $lvl): ?>
                <option value="<?= $lvl ?>">المستوى <?= $lvl ?></option>
            <?php endforeach; ?>
        </select>

        <input type="submit" value="تسجيل الطالب">
    </form>

    <p style="text-align:center; margin-top:20px;">
        <a href="add_grade.php">إدخال درجة</a> |
        <a href="view_grades.php">عرض النتائج</a>
    </p>
</div>

</body>
</html>
