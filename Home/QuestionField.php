<?php

// Database connection parameters
$servername = "localhost";
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password
$dbname = 'coursework';

// Allow user to select table (modify based on your chosen approach)
$selectedTable = isset($_GET['table']) ? $_GET['table'] : 'questionfield'; // Example using URL parameter

// Allow user to select tag (modify based on your chosen approach)
$selectedTag = isset($_GET['tag']) ? $_GET['tag'] : ''; // Example using URL parameter

try {
  // Create a new PDO instance
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  
  // Set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  // Prepare SQL statement to fetch data
  if (!empty($selectedTag)) {
    // If tag is selected, filter based on the tag
    $stmt = $conn->prepare("SELECT * FROM $selectedTable WHERE Tag LIKE :tag");
    $stmt->bindParam(':tag', $selectedTag);
  } else {
    // If no tag is selected, fetch all data
    $stmt = $conn->prepare("SELECT * FROM $selectedTable");
  }
  
  // Execute the statement
  $stmt->execute();
  
  // Fetch all rows as associative array
  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
  // Display error message if connection fails
  echo "Connection failed: " . $e->getMessage();
  exit(); // Exit script if there's an error
}
?>
