<?php
session_start();

class ImageUploader {
    private $targetDir;
    private $avatarField;
    private $db;

    public function __construct($targetDir, $avatarField, $db) {
        $this->targetDir = $targetDir;
        $this->avatarField = $avatarField;
        $this->db = $db;
    }

    private function getQuestionOwnerUserID($questionId, $tableName) {
        $stmt = $this->db->prepare("SELECT UserID FROM $tableName WHERE ID = :questionId");
        $stmt->bindParam(':questionId', $questionId);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['UserID'] : null;
    }

    public function uploadImage($tableName, $questionId, $avatarFieldName = null) {
        if (!isset($_SESSION['user_id'])) {
            echo "Bạn phải đăng nhập trước khi tải lên hình ảnh.";
            return;
        }

        $loggedInUserId = $_SESSION['user_id'];
        $avatarFieldName = $avatarFieldName ?? $this->avatarField;

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
            try {
                $questionOwnerUserId = $this->getQuestionOwnerUserID($questionId, 'questionfield');
                if ($questionOwnerUserId !== null && $questionOwnerUserId == $loggedInUserId) {
                    $targetFile = $this->targetDir . basename($_FILES["image"]["name"]);
                    $check = getimagesize($_FILES["image"]["tmp_name"]);

                    if ($check === false) {
                        throw new Exception("File không phải là hình ảnh.");
                    }

                    if ($_FILES["image"]["size"] > 5000000) {
                        throw new Exception("File của bạn quá lớn. Chỉ chấp nhận hình ảnh nhỏ hơn 5MB.");
                    }

                    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                        throw new Exception("Có lỗi xảy ra khi upload file.");
                    }

                    $avatarPath = "/GenralFunction/images/" . basename($_FILES["image"]["name"]);
                    $sql = "INSERT INTO $tableName ($avatarFieldName, ImageId) VALUES (:avatarPath, :questionId)";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(':avatarPath', $avatarPath);
                    $stmt->bindParam(':questionId', $questionId);
                    $stmt->execute();
                    $redirectLocation = $_POST['redirectLocation'] ?? '/';
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
            echo "Có lỗi xảy ra khi upload file.";
        }
    }
}

// Connection to the database
$dbHost = $_POST['dbHost'] ?? 'localhost';
$dbName = $_POST['dbName'];
$dbUsername = $_POST['dbUsername'] ?? 'root';
$dbPassword = $_POST['dbPassword'] ?? '';

try {
    $db = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUsername, $dbPassword);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Lỗi kết nối: " . $e->getMessage();
    exit();
}

// Form data including the table name and avatar field name
$tableName = $_POST['tableName'] ?? '';
$avatarField = $_POST['avatarField'] ?? '';
$questionId = $_POST['questionId'] ?? '';

// ImageUploader instance and image upload
$uploader = new ImageUploader($_SERVER['DOCUMENT_ROOT'] . "/GenralFunction/images/", $avatarField, $db);
$uploader->uploadImage($tableName, $questionId);
?>
