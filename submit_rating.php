<?php
// Database connection settings
$host = 'localhost';
$db = 'venue_management'; // Your database name
$user = 'root'; // Default XAMPP MySQL user
$pass = ''; // Default XAMPP MySQL password (usually empty)

// Create a new PDO instance
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input values
    $venue_id = isset($_POST['venue_id']) ? intval($_POST['venue_id']) : null;
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
    $rating = isset($_POST['rating']) ? floatval($_POST['rating']) : null;

    // Validate inputs
    if ($venue_id && $user_id && $rating >= 1 && $rating <= 5) {
        try {
            // Optional: Insert the rating into a separate ratings table
            // $stmt = $pdo->prepare("INSERT INTO ratings (venue_id, user_id, rating) VALUES (:venue_id, :user_id, :rating)");
            // $stmt->execute(['venue_id' => $venue_id, 'user_id' => $user_id, 'rating' => $rating]);

            // Update the venue's average ratings
            $stmt = $pdo->prepare("UPDATE venues SET ratings = (SELECT AVG(rating) FROM ratings WHERE venue_id = :venue_id) WHERE venue_id = :venue_id");
            $stmt->execute(['venue_id' => $venue_id]);

            echo json_encode(['success' => true, 'message' => 'Rating submitted successfully.']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Only POST method is allowed.']);
}
?>
