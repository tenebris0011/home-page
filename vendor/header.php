<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="icon" href="./img/favicon-16x16.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
          crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <script src="../js/custom.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body class="main">
<?php if (!isset($_SESSION['user'])) {
    echo '<ul class="nav navbar-dark bg-dark">';
    echo '<a class="navbar-brand pad-left">Wilson Code</a>';
    echo '<li class="nav-item">';
    echo '<a class="nav-link" href="../login.php">Login</a>';
    echo '</li>';
    echo '<li class="nav-item">';
    echo '<a class="nav-link" href="../register.php">Register</a>';
    echo '</li>';

    echo '</ul>';
} else {
    echo '<ul class="nav navbar-dark bg-dark">';
    echo '<a class="navbar-brand pad-left">Wilson Code</a>';
    echo '<li class="nav-item">';
    echo '<a class="nav-link" href="../index.php">Home</a>';
    echo '</li>';
    echo '<li class="nav-item">';
    echo '<a class="nav-link" href="../tasks.php">Tasks</a>';
    echo '</li>';
    echo '<li class="nav-item">';
    echo '<a class="nav-link" href="/vendor/logout.ini.php">Logout</a>';
    echo '</li>';
    echo '</ul>';
} ?>

