<?php
session_start();
require_once(__DIR__ . '/vendor/mysql.ini.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
    $results = addUserSite($_POST,$_SESSION['user']);
    if ($results) {
        header('Location: /index.php');
    }
}
if (isset($_SESSION["user"])) {
    require_once(__DIR__ . '/vendor/header.php');
    createUsersTable();
    createUserSettingsTable();
    createTasksTable();
} else {
    header('Location: /login.php');
}
?>
<div class="flex container">
    <div class="card-group center flex-row" style="height: 50rem">
        <div class="card list-group flex-column">
            <h1 class="card-header" style="margin-bottom: 5px;">Login</h1>
            <form method="POST">
                <input type="text" placeholder="website..." value="" name="url"/>
                <br/>
                <input type="text" placeholder="website name..." value="" name="name"/>
                <br/>
                <input type="text" placeholder="class name..." value="" name="class"/>
                <br/>
                <button class="btn-primary" type="submit" name="addSite">Add Site</button>
            </form>
            <a href="https://fontawesome.com/icons/" target="_blank">Checkout font awesome for class info.</a>
        </div>
    </div>
</div>
<?php require_once(__DIR__ . '/vendor/footer.php'); ?>
