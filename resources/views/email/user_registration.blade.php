<!DOCTYPE html>
<html>
<head>
    <title>Your Account has been Created</title>
</head>
<body>
    <h1>Hello, {{ $fullName }}</h1>
    <p>You have successfully been registered on the autoSpa portal and a copy of your credentials has been shared with you below.</p>
    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>
    <p>You can log in to the portal using the above credentials.</p>
    <p><a href="{{ $loginUrl }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; border-radius: 5px;">Login to autoSpa</a></p>
    <p>Thank you!</p>
</body>
</html>
