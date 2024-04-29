<?php
// Database connection parameters
$host = 'localhost'; // Change this to your database host
$dbname = 'coursework'; // Change this to your database name
$username = 'root'; // Change this to your database username
$password = ''; // Change this to your database password

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set PDO attributes
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle database connection error
    echo "Database connection failed: " . $e->getMessage();
    exit(); // Terminate script execution
}

function getQuestionImages($questionID, $pdo) {
    try {
        // Prepare SQL statement to select Image column from image table
        $stmt = $pdo->prepare("SELECT Image FROM image WHERE ImageID = :questionID");
        $stmt->bindParam(':questionID', $questionID);
        $stmt->execute();

        $images = array();
        // Check if there are any results from the query
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Add each image to the $images array
            $images[] = $row['Image'];
        }
        return $images;
    } catch(PDOException $e) {
        // Return error message if there's a connection error
        return array();
    }
}

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the question ID is set
if(isset($_GET['id'])) {
    // Get the question ID from the URL
    $questionID = $_GET['id'];

    // Call the function to get the question images
    $questionImages = getQuestionImages($questionID, $pdo);

    // Loop through the images and display them
} else {
    // Echo message if question ID is not set
    echo "No question ID found in URL.";
}
?>
