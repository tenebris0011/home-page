<?php
session_start();
require_once(__DIR__ . '/vendor/mysql.ini.php');
if (isset($_POST['login'])) {
    $results = login($_POST);
    if ($results) {
        $_SESSION['user'] = $results;
        header('Location: /index.php');
    }
}
createUsersTable();
createUserSettingsTable();
createTasksTable();
if (!isset($_SESSION['user'])) {
    require_once(__DIR__ . "/vendor/header.php");
    require_once(__DIR__ . "/vendor/mysql.ini.php");
} else {
    header('Location: /index.php');
}
?>
<div class="flex container">
    <div class="card-group center flex-row" style="height: auto">
        <div class="card list-group flex-column">
            <h1 class="card-header" style="margin-bottom: 5px;">Login</h1>
            <form method="POST">
                <input type="text" placeholder="username..." value="" name="username"/>
                <br/>
                <input type="password" placeholder="password..." value="" name="password"/>
                <br/>
                <button class="btn-primary" type="submit" name="login">Login</button>
            </form>
        </div>
    </div>
</div>
<?php require_once(__DIR__ . '/vendor/footer.php'); ?>
