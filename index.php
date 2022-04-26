<?php
session_start();
require_once(__DIR__ . '/vendor/mysql.ini.php');
if (isset($_SESSION["user"])) {
    require_once(__DIR__ . '/vendor/header.php');
    createUsersTable();
    createUserSettingsTable();
    createTasksTable();
    createUserSitesTable();
} else {
    header('Location: /login.php');
}
?>
<div class="flex container">
    <div class="card-group center flex-row" style="height: auto;">
        <div class="card list-group flex-column">
            <div class="grid">
                <?php
                $arr = fetchUserSites($_SESSION['user']);
                foreach ($arr as $k => $v) {
                    for ($i = 0; $i < sizeof($arr); $i += sizeof($arr)) {
                        echo "<button class=\"btn btn-primary\" style=\"width: 10rem; margin-left: auto; margin-right: auto; margin-bottom: 5px\" onclick=\"window.open('" . $v['url'] . "')\">";
                        echo '<i class="' . $v['class'] . '"></i>';
                        echo '<br>' . $v['name'];
                        echo '</button>';
                    }
                    echo "<tr>\n";
                }
                ?>
                <button class="btn btn-primary" style="width: 10rem; margin-left: auto; margin-right: auto; margin-bottom: 5px" onclick="window.open('/addSite.php', '_self')">
                    <i class="fa fa-plus"></i>
                    <br>Add Site
                </button>
            </div>
        </div>
        <div class="flex-column card">
            <h1 class="card-header">Search</h1>
            <label for="search">Search Options</label>
            <select name="options" id="searchOptions" style="width: 10rem; align-self: center">
                <option value="gsearch">Google</option>
                <option value="rsearch">Reddit</option>
                <option value="ssearch">Stack Overflow</option>
            </select>
            <form class="card-body">
                <input type="text" id="search-box" class="search-box" placeholder="Search..." style="width: 10rem">
                </br>
                <button onclick="search(document.getElementById('search-box').value, document.getElementById('searchOptions').value)" class="btn btn-primary">Search
                </button>
            </form>
        </div>

    </div>
</div>
<?php require_once(__DIR__ . '/vendor/footer.php'); ?>