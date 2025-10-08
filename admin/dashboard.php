
<?php
require_once '../includes/functions.php';
require_admin_login();
?>
<?php include '../includes/header.php'; ?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <a href="events_crud.php" class="bg-blue-100 p-4 rounded hover:bg-blue-200">Manage Events & News</a>
    <a href="gallery_crud.php" class="bg-yellow-100 p-4 rounded hover:bg-yellow-200">Manage Gallery</a>
    <a href="staff_crud.php" class="bg-green-100 p-4 rounded hover:bg-green-200">Manage Staff</a>
    <a href="contact_messages.php" class="bg-red-100 p-4 rounded hover:bg-red-200">View Contact Messages</a>
    <a href="logout.php" class="bg-gray-300 p-4 rounded hover:bg-gray-400">Logout</a>
</div>
<?php include '../includes/footer.php'; ?>