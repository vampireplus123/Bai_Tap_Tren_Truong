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

        echo 'begin echo....';
        echo 'host=' . $dbHost . '....';
        echo 'dbname=' . $dbName . '...';
        echo 'username=' .  $dbUsername . '....';
        echo 'password = ' . $dbPassword . '....';
        echo 'avatarField = ' . $avatarField . '....';      

    }

    public function uploadImage($tableName, $questionId, $conditionField = null, $conditionValue = null, $avatarFieldName = null) {
        echo 'begin echo';
        echo $tableName;
        echo $avatarFieldName;

        $avatarFieldName = $avatarFieldName ?? $this->avatarField;
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
            try {
                // Connect to the database
                $conn = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->dbUsername, $this->dbPassword);
                echo "\n connection: mysql:host=$this->dbHost;dbname=$this->dbName";
                echo "\n $this->dbUsername, $this->dbPassword";

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

                echo "\n avatarPath = " . $avatarPath;

                // Construct SQL query
                //$sql = "UPDATE $tableName SET $avatarFieldName=:avatarPath";
                $sql = "INSERT INTO $tableName ($avatarFieldName, ImageId) VALUES (:avatarPath, :questionId)";

                // if ($conditionField && $conditionValue) {
                //     $sql .= " WHERE $conditionField=:conditionValue";
                // }
                // echo "\n sql statement = " . $sql;

                
                $stmt = $conn->prepare($sql);    
                echo "\n $sql";

                $stmt->bindParam(':avatarPath', $avatarPath);
                $stmt->bindParam(':questionId', $questionId);
                // if ($conditionField && $conditionValue) {
                //     $stmt->bindParam(':conditionValue', $conditionValue);
                // }
                $stmt->execute();
                
                //return;
                
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
