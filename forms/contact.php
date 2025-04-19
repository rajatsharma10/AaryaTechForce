<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = strip_tags(trim($_POST["name"]));
  $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
  $subject = strip_tags(trim($_POST["subject"]));
  $message = trim($_POST["message"]);

  $to = "rajat@aaryatechforce.com"; // ✅ your actual email

  $email_subject = "New Contact Form Message: $subject";
  $email_content = "You have received a new message from your website contact form.\n\n";
  $email_content .= "Name: $name\n";
  $email_content .= "Email: $email\n\n";
  $email_content .= "Message:\n$message\n";

  $email_headers = "From: $name <$email>\r\n";
  $email_headers .= "Reply-To: $email\r\n";

  if (mail($to, $email_subject, $email_content, $email_headers)) {
    http_response_code(200);
    echo "Your message has been sent. Thank you!";
  } else {
    http_response_code(500);
    echo "Oops! Something went wrong and we couldn't send your message.";
  }
} else {
  http_response_code(403);
  echo "There was a problem with your submission. Please try again.";
}
?>
