<?php
session_start();
require_once(__DIR__ . '/vendor/mysql.ini.php');
if (isset($_SESSION['user']) && checkIsAdmin($_SESSION['user'])) {
    require_once(__DIR__ . '/vendor/header.php');
} else {
    header('Location: /index.php');
}
?>
<div class="flex container">
    <div class="card-group center flex-row" style="height: auto;">
        <div class="card list-group flex-column">
            <div style="height: 600px; overflow-y: scroll;">
                <table class="center" style="border: solid 1px black;">
                    <tr>
                        <th>User</th>
                        <th>Date</th>
                        <th>Task</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Total Time</th>
                    </tr>
                    <?php
                    $arr = fetchAllUsersTasks();
                    $date = '';
                    foreach ($arr as $k => $v) {
                        echo "<tr>\n";
                        foreach ($v as $k1 => $v1) {
                            echo "<td class='cols'>" . $v1 . "</td>\n";
                        }
                        echo "<tr>\n";
                    }

                    echo '</table>';
                    echo '</div>';
                    ?>
            </div>
        </div>
    </div>
    <?php
    require_once(__DIR__ . '/vendor/footer.php')
    ?>