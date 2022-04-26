<?php
session_start();
require_once(__DIR__ . '/vendor/mysql.ini.php');
createUserSitesTable();
$arr = fetchUserSites($_SESSION['user']);
foreach ($arr as $k => $v) {
    for ($i = 0; $i < sizeof($arr); $i +=sizeof($arr)) {
            echo "<button class=\"btn btn-primary\" style=\"width: 10rem; margin-left: auto; margin-right: auto; margin-bottom: 5px\" onclick=\"window.open('".$v['url']."')\">";
			echo '<i class='.$v['class'].'></i>';
			echo '<br>'.$v['name'];
			echo '</button>';
    }
    echo "<tr>\n";
}
?>