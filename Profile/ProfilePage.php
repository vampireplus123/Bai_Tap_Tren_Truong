<?php
include('DisplayProfileAndQuestion.php');
session_start();
?>

<html>
    <head>
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
      <link rel="stylesheet" href="/Css/Home.css">
      <style>
        .card-container {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        .card {
            border: 2px solid #007bff;
            border-radius: 10px;
            padding: 20px;
            width: calc(50% - 20px); /* Thêm phần tử trừ cho padding */
        }
        @media (max-width: 768px) {
            .card-container {
                flex-direction: column;
            }
            .card {
                width: 100%;
                margin-bottom: 20px; /* Thêm margin dưới cho card */
            }
        }
    </style>
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
                                    <li><a class = "dropdown-item" href="#" class="nav-link">Register</a></li>
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
        <!-- profile Name-->
    <div class="container">
        <div class="card-container">
            <!-- Left Card - Profile Image -->
           <!-- Left Card - Profile Image -->
            <div class="card">
                <?php
                // include($_SERVER['DOCUMENT_ROOT'] . '/GenralFunction/UploadImage.php');
                // Check if user has uploaded an image
                if (!empty($AvatarProfile)) {
                    echo '<img src="' . $AvatarProfile . '" alt="Avatar" style="width: 100px; height: 100px;">';
                } else {
                    // Nếu không có avatar, hiển thị một hình ảnh mặc định hoặc thông báo rỗng
                    echo '<img src="default_avatar.jpg" alt="Default Avatar" style="width: 100px; height: 100px;">';
                }
                ?>
                <div class="card-body">
                    <h5 class="card-title">Profile Image</h5>
                    <!-- Upload form -->
                    <form action="/GenralFunction/UploadImage.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="image" id="image">
                        <button type="submit" name="upload_image" class="btn btn-primary">Upload Image</button>
                    </form> 
                </div>
            </div>
            <!-- Right Card - Profile Information -->
            <div class="card">
                <div class="card-body">
                <?php
                    // Kiểm tra xem có dữ liệu hồ sơ trong session không
                    if (isset($_SESSION['profile_data']) && !empty($_SESSION['profile_data'])) {
                        $profileData = $_SESSION['profile_data'][0]; // Lấy bản ghi đầu tiên
                        // Hiển thị thông tin hồ sơ từ bản ghi đầu tiên
                        echo "<h5 class=card-title>$profileData[UserName]</h5>";
                        // Hiển thị các thông tin khác ở đây
                    } else {
                        // Xử lý trường hợp không có dữ liệu hồ sơ trong session
                        echo "<h1>Profile Data Not Found</h1>";
                    }
                ?>
                    <!-- Chỉ hiển thị biến $profileName một lần -->
                    <h5 class="card-title"><?php echo $profileName ?></h5>
                    <p class="card-text">Your Questions: </p>
                </div>
            </div>
        </div>
    </div>
    <!--Question-->
    <div class="container">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Number Of Questions</th>
                        <th scope="col">Question Name</th>
                        <th scope="col">Delete</th>
                        <th scope="col">Edit</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $stt = 1;
                    // Loop through your questions and display them
                    foreach ($result as $question) {
                        echo "<tr>";
                        echo "<td>$stt</td>";
                        echo "<td>" . $question['QuestionName'] . "</td>";
                        echo "<td>
                                <form action='DeleteQuestion.php' method='post'>
                                    <input type='hidden' name='question_id' value='" . $question['ID'] . "'>
                                    <button type='submit' name='delete_question' class='btn btn-danger'>Delete</button>
                                </form>
                            </td>"; // Use a form to delete the question
                        echo "<td>
                            <form action='EditQuestion.php' method='post'>
                                <input type='hidden' name='question_id' value='" . $question['ID'] . "'>
                                <button type='submit' name='edit_question' class='btn btn-primary'>Edit</button>
                            </form>
                        </td>"; // Use a form to edit the question
                        echo "</tr>";
                        $stt++;
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>