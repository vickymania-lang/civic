<?php
// fetch_blog_details.php
header('Content-Type: application/json');

// Check if the 'id' parameter is in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Debugging: Log ID to the server instead of outputting it to the client
    // error_log("Received ID: $id");

    // Connect to the database
    $host = 'localhost';
    $db = 'civic';
    $user = 'root';
    $pass = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query to fetch the blog post by ID
        $stmt = $pdo->prepare("SELECT * FROM blog1 WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $entry = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($entry) {
            // Ensure only the final JSON response is output
            echo json_encode($entry);
        } else {
            // Return an error if no post is found
            echo json_encode(['error' => 'Post not found']);
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // Handle missing ID parameter
    echo json_encode(['error' => 'ID parameter missing']);
}
