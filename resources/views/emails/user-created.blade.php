<!DOCTYPE html>
<html>
<head>
    <title>Your Account Details</title>
</head>
<body>
    <p>Hello {{ $name }},</p>

    <p>Your account has been created. Here are your login details:</p>

    <ul>
        <li><strong>Email:</strong> {{ $email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>

    <p>You can now log in using the credentials above. For security, please change your password after logging in.</p>

    <p>Thank you!</p>
</body>
</html>
