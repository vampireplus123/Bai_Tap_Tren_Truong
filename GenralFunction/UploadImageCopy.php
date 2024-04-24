<?php
session_start();

class ImageUploader {
    private $targetDir;
    private $usernameField;
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

    private function getQuestionOwnerUserID($questionId, $tableName) {
        try {
            // Connect to the database
            $conn = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->dbUsername, $this->dbPassword);
            $stmt = $conn->prepare("SELECT UserID FROM $tableName WHERE ID = :questionId");
            $stmt->bindParam(':questionId', $questionId);
            $stmt->execute();

            // Fetch the result
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $row['UserID'];
            } else {
                return null; // Return null if the question does not exist
            }
        } catch(PDOException $e) {
            echo "Lỗi kết nối: " . $e->getMessage();
            return null; // Return null in case of database error
        }
    }

    public function uploadImage($tableName, $questionId, $avatarFieldName = null) {
        if (!isset($_SESSION['user_id'])) {
            echo "Bạn phải đăng nhập trước khi tải lên hình ảnh.";
            return;
        }

        // Retrieve the user ID of the logged-in user
        $loggedInUserId = $_SESSION['user_id'];
        
        $avatarFieldName = $avatarFieldName ?? $this->avatarField;
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
            try {
                // Retrieve the owner's user ID associated with the question ID
                $questionOwnerUserId = $this->getQuestionOwnerUserID($questionId, 'questionfield');

                // Check if the question exists and the logged-in user is the owner
                if ($questionOwnerUserId !== null && $questionOwnerUserId == $loggedInUserId) {
                    // Proceed with the image upload
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

                    $sql = "INSERT INTO $tableName ($avatarFieldName, ImageId) VALUES (:avatarPath, :questionId)";    
                    $stmt = $conn->prepare($sql);    
                    $stmt->bindParam(':avatarPath', $avatarPath);
                    $stmt->bindParam(':questionId', $questionId);
                    $stmt->execute();
                    
                    // Redirect to profile page after successful upload
                    $redirectLocation = $_POST['redirectLocation'];
                    header("Location: $redirectLocation");
                    exit();
                } else {
                    echo "Bạn không có quyền tải lên hình ảnh cho câu hỏi này.";
                }
            } catch(PDOException $e) {
                echo "Lỗi kết nối: " . $e->getMessage();
            } catch(Exception $e) {
                echo $e->getMessage();
            }
        } else {
            echo "Có lỗi xảy ra khi upload file 23.";
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

// Create an instance of ImageUploader and upload the image
$uploader = new ImageUploader($_SERVER['DOCUMENT_ROOT'] . "/GenralFunction/images/", $avatarField, $dbHost, $dbName, $dbUsername, $dbPassword);
$uploader->uploadImage($tableName, $questionId);
?>
