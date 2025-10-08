<?php
// create_admin.php — run once from CLI or browser (remove after use)
require_once 'includes/db.php';

$username = 'admin';           // change as desired
$password_plain = 'ChangeMe123!'; // change to a secure password

$hash = password_hash($password_plain, PASSWORD_DEFAULT);

$stmt = mysqli_prepare($conn, "INSERT INTO admin_users (username, password) VALUES (?, ?)");
mysqli_stmt_bind_param($stmt, 'ss', $username, $hash);
if (mysqli_stmt_execute($stmt)) {
    echo "Admin created: $username\n";
} else {
    echo "Failed: " . mysqli_error($conn);
}
mysqli_stmt_close($stmt);
