<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: /login.php');
}
require_once(__DIR__ . '/vendor/mysql.ini.php');
createUsersTable();
createUserSettingsTable();
createTasksTable();
//This is to handle the post request from the form
if (isset($_POST["submit"]) && !isset($_POST["delete"])) {
    try {
        $results = mySqlInsert($_POST, $_SESSION['user']);
    } catch (Exception $e) {
        echo $e;
        $results = false;
    }
    if ($results) {
        // Redirects user so that a refresh of the page doesn't resubmit the post request
        header('Location: /tasks.php');
        exit();
    }
} elseif (isset($_POST["delete"])) {
    try {
        $results = mySqlDeleteTask($_POST, $_SESSION['user']);
    } catch (Exception $e) {
        echo $e;
        $results = false;
    }
    if ($results) {
        // Redirects user so that a refresh of the page doesn't resubmit the post request
        header('Location: /tasks.php');
        exit();
    };
}
require_once(__DIR__ . '/vendor/header.php');
?>

    <div class="flex container">
    <div class="card-group center flex-row" style="height: auto;">
        <div class="card list-group flex-column">
            <h3 class="card-header">Time Tracker</h3>
            <!--Post request requires names to pass to the post array-->
            <form method="post" class>
                <p class=label>What are you working on?</p>
                <input type="text" name="task">
                <label class="label" for="task">Task</label>
                <input type="datetime-local" name="startTime">
                <label class="label" for="startTime">Start Time</label>
                <input type="datetime-local" name="endTime">
                <label class="label" for="endTime">End Time</label>
                <button class="btn-primary" type="submit" name="submit">Done</button>
            </form>
            <h5>Today's tasks</h5>
            <?php
            $arr1 = mySqlTodaysTotalTime($_SESSION['user']);
            foreach ($arr1 as $k => $v) {
                foreach ($v as $k1 => $v1) {
                    echo "<label>Hours Today: " . round(floatval($v1), 2) . "</label>";
                }
            }
            ?>
            <form method="post">
                <div style="height: 225px; overflow-y: scroll;">
                    <table class="center" style="border: solid 1px black;">
                        <tr>
                            <th>Date</th>
                            <th>Task</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Total Time</th>
                        </tr>
                        <?php
                        $arr = mySqlFetchToday($_SESSION['user']);
                        $date = '';
                        foreach ($arr as $k => $v) {
                            echo "<tr>\n";
                            foreach ($v as $k1 => $v1) {
                                if ($k1 == "createdDate") {
                                    $date = $v1;
                                    echo "<td class='cols'><input type='checkbox' name='taskId' value=" . $v["id"] . ">&nbsp;" . $v1 . "</td>\n";
                                } elseif ($k1 == "id") {
                                } else {
                                    echo "<td class='cols'>" . $v1 . "</td>\n";
                                }
                            }
                            echo "<tr>\n";
                        }

                        echo '</table>';
                        echo '</div>';
                        echo '<button class="btn-primary" type="submit" name="delete" value=' . $date . '>Delete</button>';
                        echo '</form>';
                        ?>
                </div>
        </div>
    </div>
<?php require_once(__DIR__ . '/vendor/footer.php'); ?>