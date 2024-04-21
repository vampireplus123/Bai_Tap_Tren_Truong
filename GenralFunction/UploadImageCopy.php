<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    header("Location: login.php");
    exit();
}

class ImageUploader {
    private $targetDir;
    private $avatarField;
    private $dbHost;
    private $dbName;
    private $dbUsername;
    private $dbPassword;

    public function __construct($targetDir, $avatarField, $dbHost, $dbName, $dbUsername, $dbPassword) {
        $this->targetDir = $targetDir;
        $this->avatarField = $avatarField;
        $this->dbHost = $dbHost;
        $this->dbName = $dbName;
        $this->dbUsername = $dbUsername;
        $this->dbPassword = $dbPassword;
    }

    public function uploadImage($tableName, $questionId, $userId, $avatarFieldName = null) {
        $avatarFieldName = $avatarFieldName ?? $this->avatarField;
        
        // Check if the current user is the owner of the question
        $questionOwner = $this->isQuestionOwner($questionId, $userId);
        echo "Question Owner ID: $questionOwner <br>"; // Print the question owner ID for debugging

        if ($questionOwner === $userId) {
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
                try {
                    // Connect to the database
                    $conn = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->dbUsername, $this->dbPassword);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // File details
                    $targetFile = $this->targetDir . basename($_FILES["image"]["name"]);

                    // Check if file is an image
                    $check = getimagesize($_FILES["image"]["tmp_name"]);
                    if ($check === false) {
                        throw new Exception("File không phải là hình ảnh.");
                    }

                    // Check file size
                    if ($_FILES["image"]["size"] > 5000000) {
                        throw new Exception("File của bạn quá lớn. Chỉ chấp nhận hình ảnh nhỏ hơn 5MB.");
                    }

                    // Move the uploaded file
                    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                        throw new Exception("Có lỗi xảy ra khi upload file.");
                    }

                    // Update database with the image path
                    $avatarPath = "/GenralFunction/images/" . basename($_FILES["image"]["name"]);

                    // Construct SQL query
                    $sql = "INSERT INTO $tableName ($avatarFieldName, ImageId) VALUES (:avatarPath, :questionId)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':avatarPath', $avatarPath);
                    $stmt->bindParam(':questionId', $questionId);
                    $stmt->execute();

                    $redirectLocation = $_POST['redirectLocation'];
                    // Redirect to profile page after successful upload
                    header("Location: $redirectLocation");
                    exit();
                } catch(PDOException $e) {
                    echo "Lỗi kết nối: " . $e->getMessage();
                } catch(Exception $e) {
                    echo $e->getMessage();
                }
            } else {
                echo "Có lỗi xảy ra khi upload file 23.";
            }
        } else {
            echo "Bạn không có quyền upload ảnh cho câu hỏi này.";
        }
    }

    private function isQuestionOwner($questionId, $sessionUserId) {
        try {
            // Connect to the questionfield database
            $questionConn = new PDO("mysql:host=localhost;dbname=questionfield", $this->dbUsername, $this->dbPassword);
            $questionConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $userConn = new PDO("mysql:host=localhost;dbname=user", $this->dbUsername, $this->dbPassword);
            $userConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Prepare SQL query to get the question's Publisher (owner)
            $stmt = $questionConn->prepare("
            SELECT questionfield.ID, user.UserName, questionfield.QuestionName, questionfield.Tag, questionfield.QuestionDetail
            FROM user
            INNER JOIN questionfield ON user.UserName = questionfield.Publisher
            WHERE questionfield.ID = :questionId
        ");
            $stmt->bindParam(':questionId', $questionId);
            $stmt->execute();
    
            // Fetch the result (Publisher)
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Check if the result is valid
            if ($result) {
                $questionOwner = $result['UserName'];
    
                // Check if the session user ID matches the owner of the question
                if ($questionOwner === $sessionUserId) {
                    return $questionOwner; // Return the question owner ID
                } else {
                    return false; // User is not the owner of the question
                }
            } else {
                // Handle case where no Publisher was found for the given question ID
                echo "Question ID not found or no Publisher associated with the question.";
                return false;
            }
        } catch(PDOException $e) {
            // Handle database connection error
            echo "Database connection error: " . $e->getMessage();
            return false; // Return false in case of an error
        }
    }
}

// Retrieve form data including the table name and avatar field name
$dbHost = $_POST['dbHost'] ?? 'localhost';
$dbName = $_POST['dbName'];
$dbUsername = $_POST['dbUsername'] ?? 'root';
$dbPassword = $_POST['dbPassword'] ?? '';
$tableName = $_POST['tableName'] ?? '';
$avatarField = $_POST['avatarField'] ?? '';
$questionId = $_POST['questionId'] ?? '';

$userID = $_SESSION['user_id']; // Get the user ID from the session

// Create an instance of ImageUploader and upload the image
$uploader = new ImageUploader($_SERVER['DOCUMENT_ROOT'] . "/GenralFunction/images/", $avatarField, $dbHost, $dbName, $dbUsername, $dbPassword);
$uploader->uploadImage($tableName, $questionId, $userID);
?>
