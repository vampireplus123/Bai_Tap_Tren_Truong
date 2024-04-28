<?php
session_start();
include 'QuestionField.php';

// Check if tag filter is provided in the URL parameters
if (isset($_GET['tagFilter'])) {
    // Retrieve the selected tag
    $selectedTag = $_GET['tagFilter'];

    // Filter questions based on the selected tag
    $filteredQuestions = array_filter($data, function($question) use ($selectedTag) {
        // Check if the question's tags contain the selected tag
        return in_array($selectedTag, explode(',', $question['Tag']));
    });
} else {
    // If tag filter is not provided, use all questions
    $filteredQuestions = $data;
}
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
                    <a class="navbar-brand" href="Home.php">Greenwich Of University</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarScroll">
                        <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="Home.php">Home</a>
                            </li>
                            <li class="nav-item  dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="material-symbols-outlined">
                                        account_circle
                                    </span>
                                </a>
                                <ul class="dropdown-menu">
                                <?php
                                    // Check if the user is logged in
                                    if (isset($_SESSION['username'])) {
                                        // If logged in, display the username
                                        echo '<li><a class="dropdown-item" href="/Profile/ProfilePage.php">' . $_SESSION['username'] . '</a></li>';
                                    } else {
                                        // If not logged in, display "Profile"
                                        echo '<li><a class="dropdown-item" href="#">Profile</a></li>';
                                    }
                                ?>
                                    <li><a class="dropdown-item" href="/Message/Message.php">Send Messenger to Admin</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <?php
                                        // Check if the user is logged in
                                        if (isset($_SESSION['user_id'])) {
                                            // If logged in, display the Logout button
                                            echo '<li><a class = "dropdown-item" href="/Login/Logout.php" class="nav-link">Logout</a></li>';
                                        } else {
                                            // If not logged in, display the Login button
                                            echo '<li><a class = "dropdown-item" href="/Login/Login.php" class="nav-link">Login</a></li>';
                                        }
                                    ?>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="/AdminSite/AdminHome.php">Admin Login</a>
                            </li>
                        </ul>
                        <form class="d-flex" role="search" method="GET" action="Home.php">
                            <input class="form-control me-2" type="search" name="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    </div>
                </div>
            </nav>
        </header>
        <br>
        <main>
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="Question">Question</div>
                    </div>
                    <div class="col">
                        <button type="button" class="btn btn-outline-success"><a href="/AskQuestion/AskQuestionPage.php">Ask Question</a></button>
                    </div>
                    <div class="col">
                        <form class="d-flex" method="GET" action="Home.php">
                            <select class="form-select me-2" name="tagFilter">
                                <option value="General">General</option>
                                <!-- JavaScript will populate this with tag options -->
                                <option value="IT">IT</option>
                                <option value="Business">Business</option>
                                <option value="Marketing">Marketing</option>
                            </select>
                            <button class="btn btn-primary" type="submit">Filter</button>
                        </form>
                    </div>
                </div>
                <br>
                <!-- Show all the question block -->
            <?php foreach ($filteredQuestions as $questionfield): ?>
                        <div class="row">
                            <div class="col">
                                <div class="card question-card">
                                    <div class="card-body">
                                    <a href="/QuestionPage/QuestionPage.php?id=<?php echo $questionfield['ID']; ?>" class="link">
                                            <h5 class="card-title"><?php echo $questionfield['QuestionName']; ?></h5>
                                    </a>
                                        <h6 class="card-subtitle mb-2 text-muted">Asked by: <?php echo $questionfield['Publisher']; ?></h6>
                                        <div class="tags">
                                            <?php
                                            // Split tags string into an array
                                            $tags = explode(',', $questionfield['Tag']);
                                            foreach ($tags as $tag): ?>
                                            <span class="badge bg-primary"><?php echo $tag; ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php endforeach; ?>
            </div>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>
