<?php
// Include this code in the page that displays the reset link
if (isset($_GET['token']) && isset($_GET['email'])) {
    $token = htmlspecialchars($_GET['token']);
    $email = htmlspecialchars($_GET['email']);
} else {
    echo "Invalid request.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 50px;
        }
        .container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            color: red;
            text-align: center;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Reset Your Password</h2>
    <form method="POST" action="reset_password_handler.php">
        <input type="hidden" name="email" value="<?php echo $email; ?>">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required>
        <div class="message" id="passwordMessage"></div>
        <button type="submit">Reset Password</button>
    </form>
</div>

<script>
    const passwordInput = document.getElementById('new_password');
    const passwordMessage = document.getElementById('passwordMessage');

    passwordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const regex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/; // At least 8 characters, one letter and one number

        if (!regex.test(password)) {
            passwordMessage.textContent = 'Password must be at least 8 characters long and contain at least one letter and one number.';
        } else {
            passwordMessage.textContent = '';
        }
    });
</script>

</body>
</html>
