<?php
// Enable error reporting for debugging (can be removed in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'venue_management';

// Create a connection
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Check for connection errors
if ($conn->connect_error) {
    echo json_encode(['error' => true, 'message' => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Set the character set to utf8mb4
if (!$conn->set_charset("utf8mb4")) {
    echo json_encode(['error' => true, 'message' => "Error setting charset: " . $conn->error]);
    exit();
}

// Prepare response array
$response = array();

// Get the input data
$data = json_decode(file_get_contents("php://input"));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($data->full_name ?? '');
    $email = trim($data->email ?? '');
    $password = trim($data->password ?? '');

    // Check for empty fields
    if (empty($full_name) || empty($email) || empty($password)) {
        $response['error'] = true;
        $response['message'] = "Please fill all fields.";
    } else {
        // Check if the email already exists in the login table
        $check_query = "SELECT * FROM login WHERE email = ?";
        if ($stmt = $conn->prepare($check_query)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $response['error'] = true;
                $response['message'] = "Email already exists!";
            } else {
                // Hash the password using a secure method
                $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Use PASSWORD_DEFAULT for flexibility

                // Insert user into the users table
                $insert_user_query = "INSERT INTO users (full_name, email, password, created_at) VALUES (?, ?, ?, NOW())";
                if ($user_stmt = $conn->prepare($insert_user_query)) {
                    $user_stmt->bind_param("sss", $full_name, $email, $hashed_password);
                    if ($user_stmt->execute()) {
                        // Insert user into the login table with full_name and 'Reserver' as the default user_type
                        $insert_login_query = "INSERT INTO login (full_name, email, password, user_type, created_at) VALUES (?, ?, ?, ?, NOW())";
                        $user_type = 'Reserver'; // Set default user_type to 'Reserver'
                        if ($login_stmt = $conn->prepare($insert_login_query)) {
                            $login_stmt->bind_param("ssss", $full_name, $email, $hashed_password, $user_type);
                            if ($login_stmt->execute()) {
                                $response['error'] = false;
                                $response['message'] = "Registration successful!";
                            } else {
                                $response['error'] = true;
                                $response['message'] = "Login table insertion failed: " . $login_stmt->error;
                            }
                        } else {
                            $response['error'] = true;
                            $response['message'] = "Login table statement preparation failed.";
                        }
                    } else {
                        $response['error'] = true;
                        $response['message'] = "User table insertion failed: " . $user_stmt->error;
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = "User table statement preparation failed.";
                }
            }
            $stmt->close(); // Close the prepared statement
        } else {
            $response['error'] = true;
            $response['message'] = "Database query preparation error.";
        }
    }
}

// Send JSON response
echo json_encode($response);
exit();
?>
