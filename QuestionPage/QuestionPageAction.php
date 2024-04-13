<?php
        // Include the database connection file or define connection details here
        $host = 'localhost';
        $dbname = 'questionfield';
        $username = 'root';
        $password = '';        

        // Function to establish a PDO connection
        function connectToDatabase($host, $dbname, $username, $password) {
            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
                return null;
            }
        }

        // Function to fetch questions
        function getQuestions($pdo) {
            $sql = "SELECT * FROM questionfield";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Function to fetch comments for a question
        function getCommentsForQuestion($pdo, $question_id) {
            $sql = "SELECT * FROM comment WHERE CommentID = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$question_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

       // Function to display questions and their comments
        function displayQuestionsWithComments($pdo, $questions) {
            foreach ($questions as $question) {
                // echo "<div class='card'>";
                // echo "<div class='card-header'>";
                // Split tags string into an array
                $tags = explode(',', $question['Tag']);
                // foreach ($tags as $tag) {
                //     echo "<span class='badge bg-primary'>$tag</span>";
                // }
                // echo "</div>";
                // echo "<div class='card-body'>";
                // echo "<h5 class='card-title'>{$question['QuestionName']}</h5>";
                // echo "<p class='card-text'>{$question['QuestionDetail']}</p>";

                // Fetch and display comments for this question
                $comments = getCommentsForQuestion($pdo, $question['ID']);
                // if ($comments) {
                //     echo "<div class='comments'>";
                //     foreach ($comments as $comment) {
                //         echo "<div class='comment'>";
                //         echo "<p>{$comment['Content']}</p>";
                //         echo "</div>";
                //     }
                //     echo "</div>";
                // } else {
                //     echo "No comments found for this question."; // Display a message if no comments are found
                // }

                // // Comment Form
                // echo "<div class='comment-form mt-4'>";
                // echo "<form action='UploadComment.php' method='POST'>";
                // echo "<div class='mb-3'>";
                // echo "<label for='comment' class='form-label'>Your Comment:</label>";
                // echo "<textarea class='form-control' id='comment' name='comment' rows='3' required></textarea>";
                // echo "</div>";
                // // Hidden input field to pass question ID
                // echo "<input type='hidden' name='question_id' value='{$question['ID']}'>";
                // echo "<button type='submit' class='btn btn-primary'>Submit Comment</button>";
                // echo "</form>";
                // echo "</div>"; // Close comment-form div

                // echo "</div>"; // Close card-body div
                // echo "</div>"; // Close card div
            }
}
        // Establish connection to the database
        $pdo = connectToDatabase($host, $dbname, $username, $password);

        // If connection is successful, proceed to fetch and display questions
        if ($pdo) {
            $questions = getQuestions($pdo);
            displayQuestionsWithComments($pdo, $questions);

            // Close connection
            $pdo = null;
        }
        ?>