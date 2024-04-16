
<?php 
include ('UploadComment.php');
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
                                     // Kiểm tra xem người dùng đã đăng nhập hay chưa
                                    if (isset($_SESSION['user_id'])) {
                                        // Nếu đã đăng nhập, hiển thị nút Logout
                                        // echo '<a href="/Login/Logout.php" class="nav-link">Logout</a>';
                                        echo '<li><a class = "dropdown-item" href="/Login/Logout.php" class="nav-link">Logout</a></li>';
                                    } else {
                                        // Nếu chưa đăng nhập, hiển thị nút Login
                                        echo '<li><a class = "dropdown-item" href="/Login/Login.php" class="nav-link">Login</a></li>';
                                    }
                                ?>
                            </ul>
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
                    <?php
                        // Split tags string into an array
                        $tags = explode(',', $question['Tag']);
                        foreach ($tags as $tag): ?>
                        <span class="badge bg-primary"><?php echo $tag; ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $question['QuestionName']; ?></h5>
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