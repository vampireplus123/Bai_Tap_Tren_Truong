<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <header>
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <a class="navbar-brand" href="AdminLoginPage.php">Greenwich Of University</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <?php
                    session_start();
                    if(isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
                        echo '<a href="/logout.php" class="btn btn-danger">Logout</a>';
                    } else {
                        echo '<a href="AdminLoginPage.php" class="btn btn-primary">Login</a>';
                    }
                    ?>
                </div>
            </nav>
    </header>

    <main>
        <div class="container mt-5">
            <h2>Create User</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Create User</button>
            </form>
        </div>
    </main>
    
    <?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate form data (you can add more validation as needed)

        // Retrieve form data
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Database connection
        $dsn = "mysql:host=localhost;dbname=questionfield";
        $db_username = "root";
        $db_password = "";

        try {
            $pdo = new PDO($dsn, $db_username, $db_password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare an SQL statement to insert a new user
            $sql = "INSERT INTO `user` (`UserName`, `Password`) VALUES (:username, :password)";
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);

            // Execute the statement
            $stmt->execute();

            // Redirect back to the page where users are listed
            header("Location: AdminHome.php");
            exit();
        } catch (PDOException $e) {
            // Handle database errors
            echo "Error: " . $e->getMessage();
        }

        // Close connection
        unset($pdo);
    }
    ?>

</body>
</html>
