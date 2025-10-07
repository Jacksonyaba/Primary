<?php
require_once 'includes/db.php';

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$subject = $_POST['subject'] ?? 'No subject';
$message = $_POST['message'] ?? '';

if(!$name || !$email || !$message){
    $error='Please fill all required fields.';
} else {
    $stmt = mysqli_prepare($conn,"INSERT INTO contact_messages (name,email,subject,message,created_at) VALUES (?,?,?,?,NOW())");
    mysqli_stmt_bind_param($stmt,'ssss',$name,$email,$subject,$message);
    if(mysqli_stmt_execute($stmt)){
        $success='Message sent successfully.';
        $school_email='info@mentorprimary.edu';
        $headers='From: info@mentorprimary.edu';

        // Email to school
        mail($school_email,'New Contact Message: '.$subject,"From: $name <$email>\n\n$message",$headers);

        // Confirmation to sender
        mail($email,'Confirmation: We Received Your Message',"Dear $name,\n\nThank you for contacting Mentor Nursery and Primary School. We received your message:\n\n$message\n\nWe will respond shortly.",$headers);
    } else { $error='Failed to send message.'; }
    mysqli_stmt_close($stmt);
}
?>
