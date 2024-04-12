<?php
session_start();

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Check if there was no error during the upload
    if ($_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        $servername = "localhost";
        $username = 'root'; // Your database username
        $password = ''; // Your database password
        $dbname = "questionfield"; // Your database name

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $targetDir = "uploads/"; // Directory where the file will be saved
        $targetFile = $targetDir . basename($_FILES["image"]["name"]); // Path of the file on the server

        // Check if the file already exists
        if (file_exists($targetFile)) {
            echo "Sorry, file already exists.";
        } else {
            // Move the uploaded file to the specified directory
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                // Read the image file
                $imageData = file_get_contents($targetFile);

                // Escape special characters to prevent SQL injection
                $escapedImageData = $conn->real_escape_string($imageData);

                // Insert image data into database
                $sql = "INSERT INTO questionfield (QuestionImage) VALUES ('$escapedImageData')";
                if ($conn->query($sql) === TRUE) {
                    echo "The file ". htmlspecialchars( basename( $_FILES["image"]["name"])). " has been uploaded and saved to database.";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        $conn->close();
    } else {
        echo "Error: " . $_FILES["image"]["error"];
    }
}
?>
