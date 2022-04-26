<?php
session_start();
require_once(__DIR__ . '/vendor/mysql.ini.php');
createUsersTable();
createUserSettingsTable();
createTasksTable();
if (isset($_POST['register'])) {
    $results = createUser($_POST);
    if ($results) {
        header('Location: /login.php');
    } else {
        echo "<a>A user already exists with that username or email</a>";
    }
}
if (isset($_SESSION['user'])) {
    header('Location: /index.php');
} else {
    require_once(__DIR__ . "/vendor/header.php");
    require_once(__DIR__ . "/vendor/mysql.ini.php");
}
?>
<div class="flex container">
    <div class="card-group center flex-row" style="height: auto;">
        <div class="card list-group flex-column">
            <h1 class="card-header" style="margin-bottom: 5px;">Login</h1>
            <form method="POST">
                <input type="text" placeholder="username" value="" name="username"/>
                <br/>
                <input type="password" placeholder="password..." value="" name="password"/>
                <br/>
                <input type="text" placeholder="first name..." value="" name="firstname"/>
                <br/>
                <input type="text" placeholder="last name..." value="" name="lastname"/>
                <br/>
                <input type="text" placeholder="email..." value="" name="email"/>
                <br/>
                <button class="btn-primary" type="submit" name="register">Register</button>
            </form>
        </div>
    </div>
</div>
<?php require_once(__DIR__ . '/vendor/footer.php'); ?>
