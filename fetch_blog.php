<?php
header('Content-Type: application/json');

// Database configuration
$host = 'localhost';
$dbname = 'civic';
$username = 'root';
$password = ''; // Update this with your database password

try {
    // Create a database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch entries
    $sql = "SELECT id, heading, details, date FROM blog1 ORDER BY date DESC";
    $stmt = $conn->query($sql);
    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return data as JSON
    echo json_encode($entries);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
