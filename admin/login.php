<?php
require_once '../includes/db.php';
session_start();

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT id, password FROM admin_users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $hash);

    if (mysqli_stmt_fetch($stmt)) {
        if (password_verify($password, $hash)) {
            $_SESSION['admin'] = $id;
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid username or password';
        }
    } else {
        $error = 'Invalid username or password';
    }

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor School | Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #facc15 100%);
            background-attachment: fixed;
        }
    </style>
</head>

<body class="flex items-center justify-center h-screen">
    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-2xl w-96 p-8 border border-blue-100">
        <div class="text-center mb-6">
            <img src="../assets/images/logo.png" alt="School Logo" class="w-20 h-20 mx-auto mb-2 rounded-full border-4 border-yellow-400 shadow-md">
            <h2 class="text-2xl font-bold text-blue-900">Mentor Nursery & Primary School</h2>
            <p class="text-sm text-gray-600 mt-1">Admin Portal Login</p>
        </div>

        <?php if (isset($error)) : ?>
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4 text-center text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm text-gray-700 mb-1">Username</label>
                <input type="text" name="username" required
                    class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full p-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <button type="submit"
                class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold p-2 rounded transition duration-300">
                Login
            </button>
        </form>

        <p class="text-center text-xs text-gray-500 mt-6">
            &copy; <?= date('Y') ?> Mentor Nursery & Primary School. All rights reserved.
        </p>
    </div>
</body>

</html>