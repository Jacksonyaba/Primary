<?php
require_once '../includes/db.php';
session_start();


if(isset($_POST['username']) && isset($_POST['password'])){
$username=$_POST['username'];
$password=$_POST['password'];


$stmt=mysqli_prepare($conn,"SELECT id,password FROM admin_users WHERE username=?");
mysqli_stmt_bind_param($stmt,'s',$username);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,$id,$hash);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);


if($id && password_verify($password,$hash)){
$_SESSION['admin']=$id;
header('Location: dashboard.php');
exit;
} else {
$error='Invalid username or password';
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
<form method="POST" class="bg-white p-6 rounded shadow-md w-80">
<h2 class="text-xl font-bold mb-4">Admin Login</h2>
<?php if(isset($error)) echo '<div class="bg-red-100 text-red-700 p-2 rounded mb-2">'.$error.'</div>'; ?>
<input type="text" name="username" placeholder="Username" required class="w-full p-2 border rounded mb-2">
<input type="password" name="password" placeholder="Password" required class="w-full p-2 border rounded mb-2">
<button class="w-full bg-blue-600 text-white p-2 rounded">Login</button>
</form>
</body>
</html>