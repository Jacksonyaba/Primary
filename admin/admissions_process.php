<?php
require_once '../includes/db.php';

// Capture and sanitize inputs
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$class = trim($_POST['class'] ?? '');
$message = trim($_POST['message'] ?? '');

// Basic validation
if (!$name || !$email || !$phone || !$class) {
    $error = 'Please fill in all required fields.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Please enter a valid email address.';
} else {
    // Insert data into admissions table
    $stmt = mysqli_prepare($conn, "INSERT INTO admissions (name, email, phone, class, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    mysqli_stmt_bind_param($stmt, 'sssss', $name, $email, $phone, $class, $message);

    if (mysqli_stmt_execute($stmt)) {
        $success = true;

        // School email address
        $school_email = 'info@mentorprimary.edu';
        $headers  = "From: Mentor School <info@mentorprimary.edu>\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Message to school
        $school_message = "New Admission Application Received:\n\n" .
            "Name: $name\n" .
            "Email: $email\n" .
            "Phone: $phone\n" .
            "Class Applied: $class\n\n" .
            "Message:\n$message\n\n" .
            "Submitted on: " . date('Y-m-d H:i:s');

        mail($school_email, "New Admission Application from $name", $school_message, $headers);

        // Confirmation email to applicant
        $confirmation = "Dear $name,\n\nThank you for applying to Mentor Nursery and Primary School.\n\n" .
            "We have received your application for admission into class: $class.\n\n" .
            "Our admissions team will contact you shortly for the next steps.\n\n" .
            "Warm regards,\nMentor Nursery and Primary School\ninfo@mentorprimary.edu";

        mail($email, "Application Received — Mentor Nursery and Primary School", $confirmation, $headers);

        // Redirect to form with success status
        header("Location: admissions.php?status=success");
        exit;
    } else {
        $error = 'Failed to submit your application. Please try again later.';
    }

    mysqli_stmt_close($stmt);
}

// Redirect with error message if failed
if (isset($error)) {
    header("Location: admissions.php?status=error&msg=" . urlencode($error));
    exit;
}
