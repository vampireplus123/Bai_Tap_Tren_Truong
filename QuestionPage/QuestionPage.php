<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    include $_SERVER['DOCUMENT_ROOT'] . '/Home/Questionfield.php';

    // Debugging: Output the value of $_GET['id']
    if(isset($_GET['id'])) {
        echo "Question ID: " . $_GET['id'];
        include 'UploadComment.php';
    } else {
        echo "No question ID found in URL.";
    }
?>
<html>
    <head>
        <link rel="stylesheet" href="/Css/Home.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
      <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
    </head>
    <body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="/Home/Home.php">Greenwich Of University</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/Home/Home.php">Home</a>
                        </li>
                        <li class="nav-item  dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-symbols-outlined">
                                    account_circle
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                            <?php
                                // Kiểm tra xem người dùng đã đăng nhập hay chưa
                                if (isset($_SESSION['username'])) {
                                    // Nếu đã đăng nhập, hiển thị tên người dùng
                                    echo '<li><a class="dropdown-item" href="/Profile/ProfilePage.php">' . $_SESSION['username'] . '</a></li>';
                                } else {
                                    // Nếu chưa đăng nhập, hiển thị chữ "Profile"
                                    echo '<li><a class="dropdown-item" href="#">Profile</a></li>';
                                }
                            ?>
                                <li><a class="dropdown-item" href="#">Send Messenger to Admin</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class = "dropdown-item" href="/register/register.php" class="nav-link">Register</a></li>
                                <?php
                                     // Kiểm tra xem người dùng đã đăng nhập hay chưa
                                    if (isset($_SESSION['user_id'])) {
                                        // Nếu đã đăng nhập, hiển thị nút Logout
                                        echo '<li><a class = "dropdown-item" href="/Login/Logout.php" class="nav-link">Logout</a></li>';
                                    } else {
                                        // Nếu chưa đăng nhập, hiển thị nút Login
                                        echo '<li><a class = "dropdown-item" href="/Login/Login.php" class="nav-link">Login</a></li>';
                                    }
                                ?>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/AdminSite/AdminHome.php">Admin Login</a>
                        </li>
                    </ul>
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>
    <br>
    <main>  
        <div class="container">
            
            <?php if ($question):?>
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title"><?php echo $question['QuestionName']; ?></h5>
                            <form action="/GenralFunction/UploadImageCopy.php" method="post" enctype="multipart/form-data">
                                <label for="uploadImage" class="btn btn-outline-primary">
                                    <i class="fas fa-upload"></i> Upload Image
                                </label>
                                <input type="file" id="uploadImage" name="image" style="display: none;">
                                <!-- Hidden input fields for database connection details -->
                                <input type="hidden" name="dbHost" id="dbHost" value='localhost'>
                                <input type="hidden" name="dbUsername" id="dbUsername" value='root'>
                                <input type="hidden" name="dbPassword" id="dbPassword" value=''>

                                <!-- Specify the correct database and table name -->
                                <input type="hidden" name="dbName" id="dbName" value='questionfield'>
                                <input type="hidden" name="tableName" id="tableName" value='Image'>

                                <!-- Specify the correct avatar field name -->
                                <input type="hidden" name="avatarField" id="avatarField" value='ID'>
                                <input type="hidden" name="redirectLocation" value="/QuestionPage/QuestionPage.php?id=<?php echo $question_id; ?>">

                                <!-- Hidden input fields for condition clause if needed -->
                                <input type="hidden" name="conditionField" id="conditionField" value='ID'>
                                <input type="hidden" name="conditionValue" id="conditionValue" value='<?php echo $question_id; ?>'> <!-- Assuming you have $question_id available -->
                                <input type="submit" name="submit" value="Submit" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div>
                            <p class="card-text"><?php echo $question['QuestionDetail']; ?></p>
                        </div>
                    </div>
                </div>

            <?php endif; ?>
        </div>   
        <?php
             // Display each comment
             if ($comments) {
                foreach ($comments as $comment) {
                    echo'<div class="container">';
                        echo '<div class="card mt-3">';
                            echo '<div class="card-header">';
                                echo 'Comment';
                            echo '</div>';
                        echo '<div class="card-body">';
                                echo '<p class="card-text">' . $comment['Content'] . '</p>';
                            echo '</div>';
                        echo '</div>';
                    echo'</div>';
                }
            } 
        ?>
        <div class="container">
            <div class="card mt-3">
                <div class="card-header">
                    Post a Comment
                </div>
                <div class="card-body">
                    <form action="UploadComment.php" method="post">
                        <input type="hidden" name="question_id" value="<?php echo $question_id;?>">
                        <input type="hidden" name="comment_id" value="<?php echo $comment_id;?>">
                        <div class="mb-3">
                            <label for="commentContent" class="form-label">Your Comment</label>
                            <textarea class="form-control" id="commentContent" name="comment" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div> 
</main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>