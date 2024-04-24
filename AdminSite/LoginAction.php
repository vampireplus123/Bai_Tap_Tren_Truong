<?php
session_start();

// Database connection parameters
$servername = "localhost"; // Change this to your database server name
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$database = "questionfield"; // Change this to your database name

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Create a PDO connection
        $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // SQL query to select the admin with the provided username
        $sql = "SELECT * FROM admin WHERE UserName = :username";
        
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':username', $username);
        
        // Execute the SQL statement
        $stmt->execute();
        
        // Fetch the admin with the provided username
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if an admin with the provided username exists and if the password matches
        if ($admin && password_verify($password, $admin['Password'])) {
            // Set session variables
            $_SESSION['admin_logged_in'] = true;
            
            // Redirect to a page after successful login
            header("Location: AdminHome.php");
            exit();
        } else {
            // Redirect back to the login page with an error message
            $_SESSION['login_error'] = "Invalid username or password";
            echo"Fail";
            exit();
        }
    } catch(PDOException $e) {
        // Handle database connection errors
        $_SESSION['login_error'] = "Database connection failed: " . $e->getMessage();
        // header("Location: /login.php");
        echo"Fail";
        exit();
    }
}
?>
