<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name    = $_POST['name'] ?? '';
  $email   = $_POST['email'] ?? '';
  $subject = $_POST['subject'] ?? '';
  $message = $_POST['message'] ?? '';

  // Validate fields
  if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo "All fields are required.";
    exit;
  }

  // Prepare email
  $to = "info@aaryatechforce.com";  // Your real email address here
  $headers = "From: $email\r\nReply-To: $email\r\n";
  $body = "Name: $name\nEmail: $email\nSubject: $subject\nMessage:\n$message";

  // Send email (commented for testing purpose)
  if (mail($to, $subject, $body, $headers)) {
    echo "Message sent successfully.";  // Must match JS expectations
  } else {
    echo "Failed to send message.";
  }
} else {
  echo "Invalid request method.";
}
