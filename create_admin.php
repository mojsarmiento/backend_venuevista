<?php
// Database connection settings
$host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'venue_management';

// Create a connection
$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the email, password, and full name
$email = 'venuevistaadmin@gmail.com';
$password = 'VenueVista123@';
$fullname = 'Venue Vista'; // Define full name

// Hash the password using bcrypt (PASSWORD_DEFAULT)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Output the hashed password for debugging
echo "Hashed Password: " . $hashed_password . "<br>";  // Debugging line to check the hashed password

// Check if the password was hashed successfully
if (!$hashed_password) {
    die("Password hashing failed.");
}

// Prepare and bind the SQL statement to insert the admin user
if ($stmt = $conn->prepare("INSERT INTO login (email, password, user_type, full_name) VALUES (?, ?, ?, ?)")) {
    $user_type = 'admin'; // Define user type as 'admin'
    $stmt->bind_param("ssss", $email, $hashed_password, $user_type, $fullname); // Include full_name in binding
    
    if ($stmt->execute()) {
        echo "Admin user created successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Database query failed: " . $conn->error;
}

// Close the connection
$conn->close();
?>