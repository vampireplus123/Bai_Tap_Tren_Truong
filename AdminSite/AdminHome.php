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
        <div class="container mt-4">
            <div class="row">
                <div class="col">
                    <?php
                    if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
                        // Plus button
                        echo '<button class="btn btn-success mb-3" onclick="openCreateUserForm()">+</button>';
                        
                        // Display table only if admin is logged in
                        echo '<table class="table">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>ID</th>';
                        echo '<th>Username</th>';
                        echo '<th>Action</th>'; // New column for action buttons
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        // Database connection
                        $dsn = "mysql:host=localhost;dbname=questionfield";
                        $username = "root";
                        $password = "";

                        try {
                            $pdo = new PDO($dsn, $username, $password);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Fetching users from the database
                            $sql = "SELECT * FROM `user`";
                            $stmt = $pdo->query($sql);

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr>';
                                echo '<td>' . $row['ID'] . '</td>';
                                echo '<td>' . $row['UserName'] . '</td>';
                                echo '<td><button class="btn btn-danger" onclick="deleteUser(' . $row['ID'] . ')">Delete</button></td>'; // Delete button
                                echo '</tr>';
                            }
                        } catch (PDOException $e) {
                            die("ERROR: Could not able to execute $sql. " . $e->getMessage());
                        }

                        // Close connection
                        unset($pdo);

                        echo '</tbody>';
                        echo '</table>';
                    } else {
                        // Display a message or redirect to login page if admin is not logged in
                        echo '<p>You need to be logged in as an admin to view this content.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    function openCreateUserForm() {
        // Redirect to the create user page or open a modal form for creating a new user
        window.location.href = "UserCreate.php";
    }

    function deleteUser(userID) {
        if (confirm("Are you sure you want to delete this user?")) {
            // You can perform an AJAX request to delete the user or submit a form with user ID to another PHP script for deletion
            // For demonstration purposes, let's redirect to a delete_user.php script with the user ID
            window.location.href = "DeleteUser.php?id=" + userID;
        }
    }
</script>
</html>
