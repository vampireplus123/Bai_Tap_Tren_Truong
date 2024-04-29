<!DOCTYPE html>
<?php 
session_start();
?>
<html>
<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/Css/Home.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="AdminHome.php">Greenwich Of University</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Notification bell icon with badge -->
                <div class="position-relative">
                    <a class="nav-link" href="NoticePage.php">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">Notification</span>
                    </a>
                </div>

                <?php
                if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
                    echo '<a href="Logout.php" class="btn btn-danger">Logout</a>';
                } else {
                    echo '<a href="AdminLoginPage.php" class="btn btn-primary">Login</a>';
                }
                ?>
            </div>
        </nav>
    </header>
    <main>
    <div class="container">
        <h2>Admin Notifications</h2>
        <?php
        // Check for success message in session variable
        if (isset($_SESSION['reply_success']) && $_SESSION['reply_success'] === true) {
            echo '<div class="alert alert-success" role="alert">Reply submitted successfully!</div>';
            // Unset the success session variable
            unset($_SESSION['reply_success']);
        }
        ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Detail</th>
                        <th>Reply</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Fetch and display notifications here -->
                <?php
                    // Step 1: Establish a PDO database connection
                    $host = 'localhost'; // Replace with your host
                    $dbname = 'coursework'; // Replace with your database name
                    $username = 'root'; // Replace with your username
                    $password = ''; // Replace with your password

                    try {
                        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
                        // Set PDO error mode to exception
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    } catch (PDOException $e) {
                        die("Error: " . $e->getMessage());
                    }

                    // Step 2: Prepare and execute SQL query to fetch notifications
                    if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
                        $admin_id = $_SESSION['admin_id'];

                        try {
                            $stmt = $pdo->prepare("SELECT ID, Title, Detail, Reply FROM notification WHERE UserID = :admin_id");
                            $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
                            $stmt->execute();
                            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        } catch (PDOException $e) {
                            die("Error: " . $e->getMessage());
                        }

                        // Step 3: Fetch and display notifications
                        foreach ($notifications as $notification) {
                            echo "<tr>";
                            echo "<td>" . $notification['ID'] . "</td>";
                            echo "<td>" . $notification['Title'] . "</td>";
                            echo "<td>" . $notification['Detail'] . "</td>";
                            echo "<td>";
                            echo "<button class='btn btn-primary reply-btn' data-notification-id='" . $notification['ID'] . "'>Reply</button>";
                            echo "<div id='reply-form-" . $notification['ID'] . "' style='display:none;'>";
                            echo "<form method='post' action='reply.php'>";
                            echo "<input type='hidden' name='notification_id' value='" . $notification['ID'] . "'>";
                            echo "<textarea name='reply' rows='3' class='form-control' placeholder='Enter your reply'></textarea><br>";
                            echo "<button type='submit' class='btn btn-success'>Submit Reply</button>";
                            echo "</form>";
                            echo "</div>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        // Handle case where admin is not logged in
                        echo "Admin not logged in.";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    </main>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    // Add event listener to reply buttons
    document.addEventListener('DOMContentLoaded', function() {
        const replyButtons = document.querySelectorAll('.reply-btn');
        replyButtons.forEach(button => {
            button.addEventListener('click', function() {
                const notificationId = button.dataset.notificationId;
                const replyForm = document.getElementById('reply-form-' + notificationId);
                if (replyForm.style.display === 'none') {
                    replyForm.style.display = 'block';
                } else {
                    replyForm.style.display = 'none';
                }
            });
        });
    });
</script>
</html>
