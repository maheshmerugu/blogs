<!-- resources/views/emails/your_custom_email.blade.php -->

<!DOCTYPE html>
<html>
<head>
     <title>Teacher Schedule Class Details</title>
</head>
<body>
    <h1>Hello {{$teacherName->teacher_name}}!</h1>
    <p>This is a reminder for your live class starting soon.</p>
    <p>Here are the details for your schedule class:</p>
    <p>You can view the schedule by clicking on the link below:</p>
    <a href="{{ $url }}">Click here to join the class</a>
</body>
</html>
