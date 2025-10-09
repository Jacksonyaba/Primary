<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Handle submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $address = trim($_POST['address']);
    $parent_name = trim($_POST['parent_name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $class = $_POST['class'];
    $previous_school = trim($_POST['previous_school']);
    $message = trim($_POST['message']);

    // Upload settings
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $photoFilename = '';
    $documentFilename = '';
    $pdfFilename = '';

    // Handle passport photo
    if (!empty($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $photo = $_FILES['photo'];
        $photoMax = 2 * 1024 * 1024; // 2 MB
        $photoAllowed = ['jpg', 'jpeg', 'png', 'gif'];
        $pExt = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));

        if ($photo['error'] === UPLOAD_ERR_OK && $photo['size'] <= $photoMax && in_array($pExt, $photoAllowed)) {
            $photoFilename = time() . '_photo_' . bin2hex(random_bytes(6)) . '.' . $pExt;
            $dest = $uploadDir . $photoFilename;
            if (!move_uploaded_file($photo['tmp_name'], $dest)) {
                $error = "Failed to save passport photo.";
            }
        } else {
            $error = "Invalid passport photo. Accepts jpg, png, gif up to 2MB.";
        }
    }

    // Handle document_filename (ID)
    if (!empty($_FILES['document_filename']) && $_FILES['document_filename']['error'] !== UPLOAD_ERR_NO_FILE) {
        $doc = $_FILES['document_filename'];
        $docMax = 5 * 1024 * 1024; // 5 MB
        $docAllowed = ['pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png'];
        $dExt = strtolower(pathinfo($doc['name'], PATHINFO_EXTENSION));

        if ($doc['error'] === UPLOAD_ERR_OK && $doc['size'] <= $docMax && in_array($dExt, $docAllowed)) {
            $documentFilename = time() . '_doc_' . bin2hex(random_bytes(6)) . '.' . $dExt;
            $dest = $uploadDir . $documentFilename;
            if (!move_uploaded_file($doc['tmp_name'], $dest)) {
                $error = "Failed to save document file.";
            }
        } else {
            $error = "Invalid document. Accepts pdf/doc/docx/txt/images up to 5MB.";
        }
    }

    // Handle pdf_filename (Report Card)
    if (!empty($_FILES['pdf_filename']) && $_FILES['pdf_filename']['error'] !== UPLOAD_ERR_NO_FILE) {
        $pdf = $_FILES['pdf_filename'];
        $pdfMax = 5 * 1024 * 1024; // 5 MB
        $pdfAllowed = ['pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png'];
        $pExt = strtolower(pathinfo($pdf['name'], PATHINFO_EXTENSION));

        if ($pdf['error'] === UPLOAD_ERR_OK && $pdf['size'] <= $pdfMax && in_array($pExt, $pdfAllowed)) {
            $pdfFilename = time() . '_pdf_' . bin2hex(random_bytes(6)) . '.' . $pExt;
            $dest = $uploadDir . $pdfFilename;
            if (!move_uploaded_file($pdf['tmp_name'], $dest)) {
                $error = "Failed to save PDF/report card file.";
            }
        } else {
            $error = "Invalid report card. Accepts pdf/doc/docx/txt/images up to 5MB.";
        }
    }

    if (empty($error)) {
        // Note: Add 'photo' and 'document' columns to your admissions table (VARCHAR) if not present.
        $stmt = mysqli_prepare($conn, "INSERT INTO admissions (name, gender, dob, address, parent_name, phone, email, class, previous_school, message, photo, document_filename, pdf_filename) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssssssssssss", $name, $gender, $dob, $address, $parent_name, $phone, $email, $class, $previous_school, $message, $photoFilename, $documentFilename, $pdfFilename);

        if (mysqli_stmt_execute($stmt)) {
            $success = true;
        } else {
            $error = "Something went wrong. Please try again.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admission Form - Mentor Nursery & Primary School</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-100 min-h-screen flex items-center justify-center p-4 ">

    <div class="bg-blue-400 text-white shadow-lg rounded-2xl w-full max-w-2xl p-6 mx-auto">
        <h1 class="text-3xl font-bold text-center text-white mb-6">🎓 Admission Application Form</h1>

        <?php if (!empty($success)): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4 text-center">✅ Your application has been submitted successfully.</div>
        <?php elseif (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4 text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- enctype required for file uploads -->
        <form method="POST" class="space-y-4" enctype="multipart/form-data">

            <div class="grid md:grid-cols-2 gap-4">

                <!-- New: passport photo -->
                <div>
                    <label class="block text-white mb-1">Passport Photo (jpg, png) — max 2MB</label>
                    <input type="file" name="photo" accept="image/*" class="w-full border rounded-lg p-2 bg-white text-black">
                </div>

                <div>
                    <label class="block text-white mb-1">Full Name *</label>
                    <input type="text" name="name" required class="w-full border rounded-lg p-2 bg-white text-black focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-white mb-1">Gender *</label>
                    <select name="gender" required class="w-full border rounded-lg p-2 bg-white text-black focus:ring-2 focus:ring-blue-400">
                        <option value="">Select Gender</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-white mb-1">Date of Birth *</label>
                    <input type="date" name="dob" required class="w-full border rounded-lg p-2 bg-white text-black focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-white mb-1">Class Applying For *</label>
                    <select name="class" required class="w-full border rounded-lg p-2 bg-white text-black focus:ring-2 focus:ring-blue-400">
                        <option value="">Select Class</option>
                        <option>Nursery 1</option>
                        <option>Nursery 2</option>
                        <option>Nursery 3</option>
                        <option>Primary 1</option>
                        <option>Primary 2</option>
                        <option>Primary 3</option>
                        <option>Primary 4</option>
                        <option>Primary 5</option>
                        <option>Primary 6</option>
                        <option>Primary 7</option>
                        <option>Primary 8</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-white mb-1">Home Address *</label>
                <input type="text" name="address" required class="w-full border rounded-lg p-2 bg-white text-black focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-white mb-1">Parent / Guardian Name *</label>
                    <input type="text" name="parent_name" required class="w-full border rounded-lg p-2 bg-white text-black focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-white mb-1">Phone Number *</label>
                    <input type="tel" name="phone" required class="w-full border rounded-lg p-2 bg-white text-black focus:ring-2 focus:ring-blue-400">
                </div>
            </div>

            <div>
                <label class="block text-white mb-1">Email Address *</label>
                <input type="email" name="email" required class="w-full border rounded-lg p-2 bg-white text-black focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-white mb-1">Previous School (if any)</label>
                <input type="text" name="previous_school" class="w-full border rounded-lg p-2 bg-white text-black focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- New: document upload -->
            <div>
                <label class="block text-white mb-1">Report Card (PDF / DOC / image) — max 5MB</label>
                <input type="file" name="pdf_filename" accept=".pdf,.doc,.docx,.txt,image/*" class="w-full border rounded-lg p-2 bg-white text-black">
            </div>

            <div>
                <label class="block text-white mb-1">ID (PDF / DOC / image) — max 5MB</label>
                <input type="file" name="document_filename" accept=".pdf,.doc,.docx,.txt,image/*" class="w-full border rounded-lg p-2 bg-white text-black">
            </div>
            <div>
                <label class="block text-white mb-1">Additional Message</label>
                <textarea name="message" rows="4" class="w-full border rounded-lg p-2 bg-white text-black focus:ring-2 focus:ring-blue-400" placeholder="Any special note or question..."></textarea>
            </div>






            <button type="submit" class="w-full bg-gray-400 text-blue-700 py-3 rounded-lg hover:bg-green-500 transition font-semibold">
                Submit Application
            </button>
        </form>
    </div>

</body>


</html>

<?php include 'includes/footer.php'; ?>