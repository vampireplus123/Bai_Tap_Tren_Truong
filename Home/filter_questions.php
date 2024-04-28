<?php
// Database connection parameters
$servername = "localhost";
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password
$dbname = 'questionfield';

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if tag is provided in the GET request
    if(isset($_GET['tagFilter'])) {
        // Sanitize and validate the input
        $selectedTag = trim($_GET['tagFilter']); // Trim whitespace
        if(empty($selectedTag)) {
            echo 'No tag provided.';
            exit;
        }

        try {
            // Prepare SQL statement to fetch filtered questions
            $sql = "SELECT * FROM questionfield WHERE Tag LIKE ?";
            $stmt = $conn->prepare($sql);
            
            // Bind the parameter (with wildcard for partial match)
            $stmt->execute(["%$selectedTag%"]);

            // Fetch the filtered questions as an associative array
            $filteredQuestions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Output HTML markup for the filtered questions
            foreach($filteredQuestions as $question) {
                // Output question details here
                echo '<div class="row">';
                echo '<div class="col">';
                echo '<div class="card question-card">';
                echo '<div class="card-body">';
                echo '<a href="/QuestionPage/QuestionPage.php?id=' . $question['ID'] . '" class="link">';
                echo '<h5 class="card-title">' . $question['QuestionName'] . '</h5>';
                echo '</a>';
                echo '<h6 class="card-subtitle mb-2 text-muted">Asked by: ' . $question['Publisher'] . '</h6>';
                echo '<div class="tags">';
                // Split tags string into an array and display them
                $tags = explode(',', $question['Tag']);
                foreach ($tags as $tag) {
                    echo '<span class="badge bg-primary">' . $tag . '</span>';
                }
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } catch(PDOException $e) {
            // Display error message if connection fails
            echo "Connection failed: " . $e->getMessage();
        }
    } else {
        // If tag filter is not provided in the GET parameters, fetch all questions
        // You can include the code to fetch all questions here
        echo 'Fail';
    }
} catch(PDOException $e) {
    // Display error message if connection fails
    echo "Connection failed: " . $e->getMessage();
} catch(InvalidArgumentException $e) {
    // Display error message for invalid tag value
    echo $e->getMessage();
}
?>
