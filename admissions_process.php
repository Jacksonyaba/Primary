
<?php
require __DIR__ . '/includes/db.php';
// Basic server-side validation and insertion
$parent = trim($_POST['parent_name'] ?? '');
$child = trim($_POST['child_name'] ?? '');
$class = trim($_POST['class_applied'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$phone = trim($_POST['phone'] ?? '');
$notes = trim($_POST['notes'] ?? '');
if (!$parent || !$child || !$class) {
header('Location: /admissions.php?error=missing'); exit;
}
$stmt = $pdo->prepare('INSERT INTO admissions (parent_name, child_name, class_applied, email, phone, notes, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
$stmt->execute([$parent, $child, $class, $email, $phone, $notes]);
header('Location: /admissions.php?success=1');