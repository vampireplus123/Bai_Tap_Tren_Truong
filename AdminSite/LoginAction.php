<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "coursework"; // Replace with your database name

// Start session
session_start();

try {
    // Create a PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Handle database connection errors
    echo "Connection failed: " . $e->getMessage();
    exit(); // Stop execution if connection fails
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Prepare SQL statement to select admin with provided username and password
        $stmt = $conn->prepare("SELECT ID FROM admin WHERE UserName = :username AND Password = :password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

       // If authentication is successful
        if ($stmt->rowCount() > 0) {
            // Authentication successful, get admin ID from the query result
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $admin_id = $row['ID'];
                    
            // Store admin ID and username in session
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['admin_username'] = $username;
            
            // Set admin_logged_in to true
            $_SESSION['admin_logged_in'] = true;

            // Redirect to the admin home page after successful login
            header("Location: AdminHome.php");
            exit();
        } else {
            // Authentication failed, display error message or redirect to login page again
            $_SESSION['login_error'] = "Invalid username or password";
            header("Location: AdminLoginPage.php");
            exit();
        }

    } catch(PDOException $e) {
        // Handle database errors
        $_SESSION['login_error'] = "Database error: " . $e->getMessage();
        header("Location: AdminLoginPage.php");
        exit();
    }
}

// Close the database connection
$conn = null;
?>
