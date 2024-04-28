<?php
include 'Action.php';

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['username'])) {
    // Nếu người dùng chưa đăng nhập, chuyển hướng người dùng đến trang đăng nhập
    header("Location: /Login/Login.php");
    exit(); // Dừng kịch bản PHP ở đây để tránh tiếp tục chạy mã HTML dưới
}

// List of existing tags
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
                            <li><a class="dropdown-item" href="/Message/Message.php">Send Messenger to Admin</a></li>
                            <li><hr class="dropdown-divider"></li>
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
<main>
    <div class="container mt-4">
        <h2>Ask a Question</h2>
        <form action="Action.php" method="POST">
            <!-- Hidden input field to store question ID -->


            <div class="mb-3">
                <label for="question_name" class="form-label">Question Name</label>
                <input type="text" class="form-control" id="question_name" name="question_name" required>
            </div>
            <div class="mb-3">
                <label for="tag" class="form-label">Tag</label>
                <select class="form-select" id="tag" name="tag" required>
                    <?php foreach ($existingTags as $tag): ?>
                        <option value="<?php echo $tag; ?>"><?php echo $tag; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="question_detail" class="form-label">Question Detail</label>
                <textarea class="form-control" id="question_detail" name="question_detail" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
