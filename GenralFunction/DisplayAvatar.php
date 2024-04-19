<?php
function getAvatar($username) {
    $servername = "localhost";
    $db_username = 'root'; // Replace with your database username
    $db_password = ''; // Replace with your database password
    $dbname = "user"; 

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $db_username, $db_password);
        // Set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL statement to select Avatar column from user table
        $stmt = $conn->prepare("SELECT Avatar FROM user WHERE UserName=:username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $avatar = "";
        // Check if there is any result from the query
        if ($stmt->rowCount() > 0) {
            // Fetch the result
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // Return the avatar image path if exists
            $avatar = $row['Avatar'];
            return $avatar;
        } else {
            // Return error message if avatar not found
            return "Không tìm thấy hình ảnh avatar.";
        }
    } catch(PDOException $e) {
        // Return error message if there's a connection error
        return "Lỗi kết nối: " . $e->getMessage();
    } finally {
        // Close the connection
        $conn = null;
    }
}

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if username is set in session
if(isset($_SESSION['username'])) {
    // Get the username from session
    $username = $_SESSION['username'];

    // Call the function to get the avatar
    $Avatar = getAvatar($username);

    // Echo the avatar image path or error message
} else {
    // Echo message if user is not logged in
    echo "Vui lòng đăng nhập để xem hình ảnh avatar.";
}
?>
