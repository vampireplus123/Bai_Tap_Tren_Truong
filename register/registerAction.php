<?php
$servername = "localhost";
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password
$dbname = "user";   // Replace with your database name

session_start(); // Start session

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$con = mysqli_connect($servername, $username, $password, $dbname);

if(mysqli_connect_error()) {
    exit('Error connecting to the database' . mysqli_connect_error());
}

if(isset($_POST['username'], $_POST['password'])) {
    // Both username and password are set
    if(empty($_POST['username']) || empty($_POST['password'])) {
        // Either username or password is empty
        exit('Empty Field(s)');
    } else {
        // Both fields are filled, proceed with your logic
        
        // Function to check if username exists
        function isUsernameExists($username, $conn) {
            $query = "SELECT * FROM users WHERE username = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->num_rows > 0;
        }

        // Function to add new user
        function addUser($username, $password, $conn) {
            // Note: You need to add password field in the INSERT query if you're storing passwords
            $query = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            return $stmt->affected_rows === 1;
        }

        // Example usage
        $newUsername = $_POST['username'];
        $newPassword = $_POST['password'];

        if (isUsernameExists($newUsername, $conn)) {
            echo "Username already exists!";
        } else {
            if (addUser($newUsername, $newPassword, $conn)) {
                echo "User added successfully!";
            } else {
                echo "Failed to add user.";
            }
        }
    }
} else {
    // Either username or password is not set
    exit('Values Empty');
}
?>
