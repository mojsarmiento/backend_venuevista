<?php
// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set header for JSON response
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$user = 'root'; // Default XAMPP username
$pass = '';     // Default XAMPP password (empty)
$db = 'venue_management';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["message" => "Connection failed: " . $conn->connect_error]));
}

// Check if email is set
if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email exists, generate a token and expiry time
        $token = bin2hex(random_bytes(50)); // Generate a random token
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Set expiry to 1 hour from now

        // Update token and expiry in the database
        $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        // Send email
        $mail = new PHPMailer(true);

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mjsarmiento234@gmail.com'; // Your Gmail address
            $mail->Password   = 'nzgx xmtf fvip uxkc';         // Your Gmail password or App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Email content
            $mail->setFrom('your_email@gmail.com', 'Your Name');
            $mail->addAddress($email); // Add recipient's email
            $mail->Subject = 'Password Reset Request';
            $resetLink = "http://localhost/database/reset_password.php?token=$token&email=" . urlencode($email);
            $mail->Body    = "Click the link below to reset your password: <a href=\"$resetLink\">Reset Password</a>";
            $mail->isHTML(true); // Set email format to HTML

            // Send email
            $mail->send();
            echo json_encode(["message" => "Reset link sent to $email"]);
        } catch (Exception $e) {
            echo json_encode(["message" => "Failed to send email. Mailer Error: {$mail->ErrorInfo}"]);
        }
    } else {
        echo json_encode(["message" => "Email not found"]);
    }
    $stmt->close();
} else {
    echo json_encode(["message" => "Email parameter not set"]);
}

$conn->close();
?>



