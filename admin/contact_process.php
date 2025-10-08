<?php
require_once '../includes/db.php';

// Sanitize inputs
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? 'No subject');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$message) {
    $error = 'Please fill all required fields.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Invalid email address.';
} else {
    // Insert into database
    $stmt = mysqli_prepare($conn, "INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())");
    mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $subject, $message);

    if (mysqli_stmt_execute($stmt)) {
        $success = 'Message sent successfully.';

        // School email
        $school_email = 'info@mentorprimary.edu';
        $headers = "From: Mentor School <info@mentorprimary.edu>\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Send email to school
        $mail_to_school = mail(
            $school_email,
            'New Contact Message: ' . $subject,
            "From: $name <$email>\n\n$message",
            $headers
        );

        // Send confirmation to sender (using same address)
        $confirmation = "Dear $name,\n\nThank you for contacting Mentor Nursery and Primary School.\n\nWe received your message:\n\n$message\n\nWe will respond shortly.\n\nWarm regards,\nMentor Nursery and Primary School";
        $mail_to_sender = mail($email, 'Confirmation: We Received Your Message', $confirmation, $headers);

        // Optional redirect with success flag
        header("Location: contact.php?status=success");
        exit;
    } else {
        $error = 'Failed to send message.';
    }

    mysqli_stmt_close($stmt);
}

// Redirect back with error message if needed
if (isset($error)) {
    header("Location: contact.php?status=error&msg=" . urlencode($error));
    exit;
}
?>
