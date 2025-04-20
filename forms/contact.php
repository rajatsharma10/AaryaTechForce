<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);

    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        http_response_code(400); // Bad Request if fields are missing
        echo "All fields are required.";
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400); // Bad Request for invalid email
        echo "Invalid email format.";
        exit;
    }

    // Send email
    $to = "rajat@aaryatechforce.com";  // Your real email
    $email_subject = "New Message: $subject";
    $email_body = "Name: $name\n";
    $email_body .= "Email: $email\n";
    $email_body .= "Message:\n$message\n";

    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";

    if (mail($to, $email_subject, $email_body, $headers)) {
        http_response_code(200);  // Success
        echo "Message sent successfully.";  // Return success message
    } else {
        http_response_code(500);  // Failure
        echo "Failed to send the message.";  // Return failure message
    }
} else {
    http_response_code(403);  // Forbidden for direct access
    echo "Direct access not allowed.";
}
?>
