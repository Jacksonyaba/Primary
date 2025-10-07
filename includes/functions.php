
<?php
session_start();
require_once 'db.php';

// Admin auth
function is_admin_logged_in()
{
    return isset($_SESSION['admin']);
}
function require_admin_login()
{
    if (!is_admin_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

// File upload helper
function upload_image($input_name, $upload_dir = '../assets/uploads/')
{
    if (!isset($_FILES[$input_name]) || $_FILES[$input_name]['error'] != 0) return null;
    $file_tmp = $_FILES[$input_name]['tmp_name'];
    $file_name = time() . '_' . basename($_FILES[$input_name]['name']);
    $target = $upload_dir . $file_name;
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) return null;
    if (move_uploaded_file($file_tmp, $target)) return $file_name;
    return null;
}
?>
