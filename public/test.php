<?php

$email_otp = rand(1231, 7879);
$body = 'Your OTP is: ' . $email_otp . ' Please do not share it with anybody.';
$details = [
    'name' => 'Your App Name',
    'body' => $body
];

$to = "tsunil870@gmail.com";
$subject = 'Mail from Your App Name';
$message = '
<html>
<head>
    <title>' . $details['name'] . '</title>
</head>
<body>
    <h1>' . $details['name'] . '</h1>
    <p>' . $details['body'] . '</p>
</body>
</html>
';

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: Your App Name <your_username>' . "\r\n"; // Replace 'your_username' with the appropriate email account username

mail($to, $subject, $message, $headers);

echo "Email sent successfully.";
?>