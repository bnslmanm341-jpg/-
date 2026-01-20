<?php
require_once 'config.php';

// استعلام لربط الجداول: students, grades, courses, departments
$sql = "SELECT 
            s.registration_number,
            s.student_name,
            d.dept_name,
            c.course_code,
            c.course_name,
            g.grade
        FROM grades g
        JOIN students s ON g.student_id = s.student_id
        JOIN courses c ON g.course_id = c.course_id
        JOIN departments d ON s.dept_id = d.dept_id
        ORDER BY s.registration_number ASC, c.course_code ASC";

$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>نتائج الطلاب النهائية</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 20px;
        }
        .container {
            max-width: 1100px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: right;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .links {
            text-align: center;
            margin-bottom: 20px;
        }
        .links a {
            margin: 0 10px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>نتائج الطلاب النهائية</h1>

    <div class="links">
        <a href="add_student.php">تسجيل طالب جديد</a>
        |
        <a href="add_grade.php">إدخال درجة</a>
    </div>

    <?php if (count($results) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>رقم القيد</th>
                    <th>اسم الطالب</th>
                    <th>القسم</th>
                    <th>كود المادة</th>
                    <th>اسم المادة</th>
                    <th>الدرجة</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['registration_number']) ?></td>
                        <td><?= htmlspecialchars($row['student_name']) ?></td>
                        <td><?= htmlspecialchars($row['dept_name']) ?></td>
                        <td><?= htmlspecialchars($row['course_code']) ?></td>
                        <td><?= htmlspecialchars($row['course_name']) ?></td>
                        <td><strong><?= htmlspecialchars($row['grade']) ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center; color:#888;">لا توجد نتائج مسجلة حتى الآن.</p>
    <?php endif; ?>
</div>

</body>
</html>
