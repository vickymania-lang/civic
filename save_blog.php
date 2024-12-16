<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// Database configuration
$host = 'localhost';
$dbname = 'civic';
$username = 'root';
$password = ''; // Change to your MySQL password

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $heading = $_POST['name'];
    $date = $_POST['date'];
    $details = $_POST['message'];

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $uploadFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
            $imagePath = $uploadFilePath;
        } else {
            $imagePath = null;
        }
    } else {
        $imagePath = null;
    }

    // Insert data into the database
    $sql = "INSERT INTO blog1 (heading, date, image_path, details) VALUES (:heading, :date, :image_path, :details)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':heading', $heading);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':image_path', $imagePath);
    $stmt->bindParam(':details', $details);

    if ($stmt->execute()) {
        echo "Blog post saved successfully!";
        header("Location: index.html");
        exit;
    } else {
        echo "Failed to save the blog post.";
    }
}
