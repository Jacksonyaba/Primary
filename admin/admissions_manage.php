<?php
session_start();
require_once '../includes/db.php';

// ✅ Secure admin access
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// ✅ Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM admissions WHERE id=$id");
    header('Location: admissions_manage.php?status=deleted');
    exit;
}

// ✅ Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_email'])) {
    $to = $_POST['reply_email'];
    $subject = $_POST['reply_subject'] ?? 'Response from Mentor Nursery & Primary School';
    $message = $_POST['reply_message'] ?? '';
    $headers = "From: info@mentorprimary.edu\r\nReply-To: info@mentorprimary.edu";

    if (mail($to, $subject, $message, $headers)) {
        $mail_status = 'success';
    } else {
        $mail_status = 'failed';
    }
}

// ✅ Sorting, search, pagination
$sort = $_GET['sort'] ?? 'date_desc';
switch ($sort) {
    case 'date_asc':
        $orderBy = "ORDER BY created_at ASC";
        break;
    case 'class_asc':
        $orderBy = "ORDER BY class ASC";
        break;
    case 'class_desc':
        $orderBy = "ORDER BY class DESC";
        break;
    default:
        $orderBy = "ORDER BY created_at DESC";
}

$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search = $_GET['search'] ?? '';
$term = "%$search%";

// ✅ Count total records
$count_stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM admissions WHERE name LIKE ? OR email LIKE ? OR phone LIKE ?");
mysqli_stmt_bind_param($count_stmt, 'sss', $term, $term, $term);
mysqli_stmt_execute($count_stmt);
mysqli_stmt_bind_result($count_stmt, $total);
mysqli_stmt_fetch($count_stmt);
mysqli_stmt_close($count_stmt);

// ✅ Fetch records
$stmt = mysqli_prepare($conn, "SELECT * FROM admissions WHERE name LIKE ? OR email LIKE ? OR phone LIKE ? $orderBy LIMIT ? OFFSET ?");
mysqli_stmt_bind_param($stmt, 'sssii', $term, $term, $term, $limit, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total_pages = ceil($total / $limit);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Admissions - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    <header class="bg-blue-700 text-white p-4 flex justify-between items-center">
        <h1 class="font-bold text-xl">🎓 Admissions Management</h1>
        <nav>
            <a href="dashboard.php" class="px-3 hover:underline">Dashboard</a>
            <a href="logout.php" class="px-3 hover:underline">Logout</a>
        </nav>
    </header>

    <main class="max-w-6xl mx-auto p-6">
        <?php if (isset($_GET['status']) && $_GET['status'] === 'deleted'): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-center">
                ✅ Record deleted successfully.
            </div>
        <?php endif; ?>

        <?php if (isset($mail_status)): ?>
            <div class="p-3 rounded mb-4 text-center <?= $mail_status === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                <?= $mail_status === 'success' ? '✉️ Email sent successfully.' : '❌ Failed to send email.' ?>
            </div>
        <?php endif; ?>

        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-3">
            <h2 class="text-2xl font-semibold text-gray-800">All Applications</h2>
            <form method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search..." class="p-2 border rounded-lg focus:ring-2 focus:ring-blue-300">
                <select name="sort" class="p-2 border rounded-lg bg-white focus:ring-2 focus:ring-blue-300">
                    <option value="date_desc" <?= $sort === 'date_desc' ? 'selected' : '' ?>>Newest</option>
                    <option value="date_asc" <?= $sort === 'date_asc' ? 'selected' : '' ?>>Oldest</option>
                    <option value="class_asc" <?= $sort === 'class_asc' ? 'selected' : '' ?>>Class A–Z</option>
                    <option value="class_desc" <?= $sort === 'class_desc' ? 'selected' : '' ?>>Class Z–A</option>
                </select>
                <button class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800">Filter</button>
            </form>
        </div>

        <!-- ✅ Admissions Table -->
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full border-collapse">
                <thead class="bg-blue-100 text-blue-900 text-sm uppercase font-semibold">
                    <tr>
                        <th class="p-3 border-b text-left">#</th>
                        <th class="p-3 border-b text-left">Name</th>
                        <th class="p-3 border-b text-left">Email</th>
                        <th class="p-3 border-b text-left">Phone</th>
                        <th class="p-3 border-b text-left">Class</th>
                        <th class="p-3 border-b text-left">Date</th>
                        <th class="p-3 border-b text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-3 border-b"><?= $row['id'] ?></td>
                                <td class="p-3 border-b font-semibold"><?= htmlspecialchars($row['name']) ?></td>
                                <td class="p-3 border-b"><?= htmlspecialchars($row['email']) ?></td>
                                <td class="p-3 border-b"><?= htmlspecialchars($row['phone']) ?></td>
                                <td class="p-3 border-b"><?= htmlspecialchars($row['class']) ?></td>
                                <td class="p-3 border-b text-sm text-gray-500"><?= $row['created_at'] ?></td>
                                <td class="p-3 border-b text-center space-x-2">
                                    <button onclick='viewDetails(<?= json_encode($row) ?>)' class="text-blue-600 hover:underline">View</button> |
                                    <button onclick='openReply("<?= htmlspecialchars($row['email']) ?>")' class="text-green-600 hover:underline">Reply</button> |
                                    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this record?');" class="text-red-600 hover:underline">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">No records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- ✅ Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="flex justify-center mt-6 space-x-2">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&sort=<?= $sort ?>"
                        class="px-3 py-1 rounded <?= $i == $page ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </main>

    <!-- ✅ Details Modal -->
    <div id="detailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-11/12 max-w-lg shadow-xl relative">
            <button onclick="closeModal('detailsModal')" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">&times;</button>
            <h3 class="text-xl font-bold mb-3 text-blue-700">Admission Details</h3>
            <div id="modalContent" class="space-y-2 text-gray-700"></div>
        </div>
    </div>

    <!-- ✅ Reply Modal -->
    <div id="replyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <form method="POST" class="bg-white rounded-lg p-6 w-11/12 max-w-lg shadow-xl relative">
            <button type="button" onclick="closeModal('replyModal')" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">&times;</button>
            <h3 class="text-xl font-bold mb-3 text-green-700">Reply to Applicant</h3>
            <input type="email" id="replyEmail" name="reply_email" readonly class="w-full p-3 border rounded mb-3 bg-gray-100 text-gray-700">
            <input type="text" name="reply_subject" placeholder="Subject" class="w-full p-3 border rounded mb-3 focus:ring-2 focus:ring-green-300" required>
            <textarea name="reply_message" rows="6" placeholder="Write your message..." class="w-full p-3 border rounded mb-3 focus:ring-2 focus:ring-green-300" required></textarea>
            <button class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700 transition w-full">Send Email</button>
        </form>
    </div>

    <script>
        function viewDetails(data) {
            const modal = document.getElementById('detailsModal');
            const content = document.getElementById('modalContent');
            content.innerHTML = `
                <p><strong>Name:</strong> ${data.student_name}</p>
                <p><strong>Email:</strong> ${data.email}</p>
                <p><strong>Phone:</strong> ${data.phone}</p>
                <p><strong>Class:</strong> ${data.class}</p>
                <p><strong>Date:</strong> ${data.created_at}</p>
                <p><strong>Message:</strong></p>
                <p class="whitespace-pre-line bg-gray-50 p-3 rounded">${data.message}</p>
            `;
            modal.classList.remove('hidden');
        }

        function openReply(email) {
            document.getElementById('replyEmail').value = email;
            document.getElementById('replyModal').classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }
    </script>
</body>
</html>
