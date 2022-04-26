<?php

$dbServer = "php-mysql";
$dbUser = "root";
$dbPass = "password";
$dbName = "php-environment";

function connect()
{
    global $dbServer, $dbUser, $dbPass, $dbName;
    try {
        $conn = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $dbPass);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return "Connected successfully";
    } catch (PDOException $e) {
        return "Connection failed: " . $e->getMessage();
    }
}

function createUsersTable()
{
    global $dbServer, $dbUser, $dbPass, $dbName;
    try {
        $conn = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $dbPass);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // sql to create table
        $sql = "CREATE TABLE IF NOT EXISTS users (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(30) NOT NULL,
  password VARCHAR(255) NOT NULL,
  firstname VARCHAR(30) NOT NULL,
  lastname VARCHAR(30) NOT NULL,
  email VARCHAR(50),
  reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  )";

        // use exec() because no results are returned
        $conn->exec($sql);
        return True;
    } catch (PDOException $e) {
        return $e->getMessage();
    }

    $conn = null;
}

function createUserSettingsTable()
{
    global $dbServer, $dbUser, $dbPass, $dbName;
    try {
        $conn = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $dbPass);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // sql to create table
        $sql = "CREATE TABLE IF NOT EXISTS userSettings (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
userId INT(6) UNSIGNED,
settingName VARCHAR(30) NOT NULL,
settingValue VARCHAR(255) NOT NULL,
reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (userId) REFERENCES users(id)
);";

        // use exec() because no results are returned
        $conn->exec($sql);
        return True;
    } catch (PDOException $e) {
        return False;
    }

    $conn = null;
}

function createTasksTable()
{
    global $dbServer, $dbUser, $dbPass, $dbName;
    try {
        $conn = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $dbPass);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // sql to create table
        $sql = "CREATE TABLE userTasks (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
userId INT(6) UNSIGNED,
createdDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
task nvarchar(255), 
startTime bigint, 
endTime bigint, 
totalTime bigint,
FOREIGN KEY (userId) REFERENCES users(id)
);";

        // use exec() because no results are returned
        $conn->exec($sql);
        return True;
    } catch (PDOException $e) {
        return False;
    }

    $conn = null;
}


function createUserSitesTable()
{ {
        global $dbServer, $dbUser, $dbPass, $dbName;
        try {
            $conn = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $dbPass);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // sql to create table
            $sql = "CREATE TABLE userSites (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
userId INT(6) UNSIGNED,
url varchar(255) NOT NULL,
name varchar(30) NOT NULL,
class varchar(255) NOT NULL,
FOREIGN KEY (userId) REFERENCES users(id)
);";

            // use exec() because no results are returned
            $conn->exec($sql);
            return True;
        } catch (PDOException $e) {
            return False;
        }

        $conn = null;
    }
}

function fetchUserSites($userID)
{
    global $dbServer, $dbUser, $dbPass, $dbName;
    try {
        $conn = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $dbPass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT url, name, class from userSites where userId = ' . $userID . ' order by name;';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();

        $conn = null;
    } catch (PDOException $e) {
        echo $e;
        throw new Exception('Failed to fetch sql data: ' . $e->getMessage());
    }
}

function addUserSite($arr, $userId)
{
    global $dbServer, $dbUser, $dbPass, $dbName;
    try {
        $conn = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $dbPass);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO userSites (userid, url, name, class)
  VALUES (:userid, :url, :name, :class)";
        $stmt = $conn->prepare($sql);

        $stmt->execute([
            'userid' => $userId,
            'url' => htmlspecialchars($arr["url"]),
            'name' => htmlspecialchars($arr["name"]),
            'class' => htmlspecialchars($arr["class"])
            // use exec() because no results are returned
        ]);
        return True;
    } catch (PDOException $e) {
        return $e->getMessage();
    }

    $conn = null;
}

function createUser($arr)
{
    global $dbServer, $dbUser, $dbPass, $dbName;
    try {
        if (!checkIfUserExists($arr['username'], $arr['email'])) {
            $conn = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $dbPass);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $userPassword = passwordHash($arr["username"], $arr["password"]);
            $sql = "INSERT INTO users (username, password, firstname, lastname, email)
  VALUES (:username, :password, :firstname, :lastname, :email)";
            $stmt = $conn->prepare($sql);

            $stmt->execute([
                'username' => htmlspecialchars($arr["username"]),
                'password' => $userPassword,
                'firstname' => htmlspecialchars($arr["firstname"]),
                'lastname' => htmlspecialchars($arr["lastname"]),
                'email' => filter_var($arr["email"], FILTER_SANITIZE_EMAIL)
                // use exec() because no results are returned
            ]);
            return True;
        } else {
            return False;
        }
    } catch (PDOException $e) {
        return $e->getMessage();
    }

    $conn = null;
}

function login($arr)
{
    global $dbServer, $dbUser, $dbPass, $dbName;
    try {
        $userName = htmlspecialchars($arr['username']);
        $password = htmlspecialchars($arr['password']);
        $conn = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $dbPass);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $userPassword = passwordHash($userName, $password);
        $sql = "SELECT password FROM users where username = '" . $userName . "';";
        $results = $conn->query($sql);
        if ($results->rowCount() === 1) {
            // output data of each row
            while ($row = $results->fetchAll()) {
                $check = $row[0]['password'];
                if ($userPassword === $check) {
                    $id = "SELECT id FROM users where username = '" . $userName . "';";
                    $res = $conn->query($id);
                    if ($res->rowCount() === 1) {
                        while ($r = $res->fetchAll()) {
                            return $r[0]['id'];
                        }
                    }
                } else {
                    return "Failed to authenticate with provider username and password combo.";
                }
            }
        } else {
            return "No results found for provided username and password combo.";
        }
    } catch (PDOException $e) {
        return $e->getMessage();
    }

    $conn = null;
}

function passwordHash($user, $password)
{
    $password .= $user;
    return hash('sha256', $password);
}

function checkIfUserExists($user, $email)
{
    global $dbServer, $dbUser, $dbPass, $dbName;
    try {
        $conn = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $dbPass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * from users where username = \'' . htmlspecialchars($user) . '\' or email = \'' . filter_var($email, FILTER_SANITIZE_EMAIL) . '\';';
        $results = $conn->query($sql);
        if ($results->rowCount() >= 1) {
            return True;
        } else {
            return False;
        }

        $conn = null;
    } catch (PDOException $e) {
        throw new Exception('Failed to fetch sql data: ' . $e->getMessage());
    }
}

function checkIsAdmin($userId) {
    global $dbServer, $dbUser, $dbPass, $dbName;
    try {
        $conn = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $dbPass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * from userSettings where userId = :userId and settingValue = \'isAdmin\';';
        $stmt = $conn->prepare($sql);
      
        if ($stmt->execute([
            'userId'=>filter_var($userId, FILTER_SANITIZE_NUMBER_INT)
        ])){
            return True;
        } else {
            return False;
        }
        $conn = null;
    } catch (PDOException $e) {
        throw new Exception('Failed to fetch sql data: ' . $e->getMessage());
    }
}


/*
* Begin userTasks functions
*/
/**
 * @throws Exception
 */
function mySqlFetchToday($userID)
{
    global $dbServer, $dbUser, $dbPass, $dbName;
    try {
        $conn = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $dbPass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT id,CONVERT_TZ(createdDate, \'+00:00\', \'-05:00\') as createdDate, task, FROM_UNIXTIME(startTime), FROM_UNIXTIME(endTime), (totalTime / 60) 
from userTasks where createdDate between CURDATE() - INTERVAL 1 day and CURDATE() + INTERVAL 1 day and userId = ' . filter_var($userID, FILTER_SANITIZE_NUMBER_INT) . ' order by FROM_UNIXTIME(startTime) desc';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();

        $conn = null;
    } catch (PDOException $e) {
        echo $e;
        throw new Exception('Failed to fetch sql data: ' . $e->getMessage());
    }
}

/**
 * @throws Exception
 */
function fetchAllUsersTasks()
{
    global $dbServer, $dbUser, $dbPass, $dbName;
    try {
        $conn = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $dbPass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT concat(firstName, \' \', lastName) as Name, CONVERT_TZ(createdDate, \'+00:00\', \'-05:00\') as createdDate, task, FROM_UNIXTIME(startTime), FROM_UNIXTIME(endTime), (totalTime / 60) 
from userTasks join users on userTasks.userId = users.id order by username, FROM_UNIXTIME(startTime) desc';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();

        $conn = null;
    } catch (PDOException $e) {
        echo $e;
        throw new Exception('Failed to fetch sql data: ' . $e->getMessage());
    }
}

/**
 * @throws Exception
 */
function mySqlDeleteTask($arr, $userId)
{
    global $dbServer, $dbUser, $dbPass, $dbName;
    try {
        $conn = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $dbPass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'DELETE FROM userTasks where id = :taskId;';
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'taskId' => htmlspecialchars($arr['taskId'])
        ]);
        $conn = null;

        return true;
    } catch (PDOException $e) {
        throw new Exception('Failed to fetch sql data: ' . $e->getMessage());
    }
}

/**
 * @throws Exception
 */
function mySqlInsert($arr, $userId)
{
    global $dbServer, $dbUser, $dbPass, $dbName;
    try {
        $conn = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $dbPass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'INSERT INTO userTasks (userId, task, startTime, endtime, totalTime) values (:userId, :task, :startTime, :endTime, :timeSpent)';
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            //'task'=>filter_var($arr['task'],FILTER_SANITIZE_STRING),
            'userId' => filter_var($userId,FILTER_SANITIZE_NUMBER_INT),
            'task' => htmlspecialchars($arr['task']),
            'startTime' => filter_var(strtotime($arr['startTime']), FILTER_SANITIZE_NUMBER_INT), // Convert to unix timestamp
            'endTime' => filter_var(strtotime($arr['endTime']), FILTER_SANITIZE_NUMBER_INT), // Convert to unix timestamp
            'timeSpent' => ((strtotime($arr['endTime']) - strtotime($arr['startTime'])) / 60) // Do int math to get total hours
        ]);
        $conn = null;

        return true;
    } catch (PDOException $e) {
        throw new Exception('Failed to fetch sql data: ' . $e->getMessage());
    }
}

/**
 * @throws Exception
 */
function mySqlTodaysTotalTime($userId)
{
    global $dbServer, $dbUser, $dbPass, $dbName;
    try {
        $conn = new PDO("mysql:host=$dbServer;dbname=$dbName", $dbUser, $dbPass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT sum(totalTime / 60) from userTasks where createdDate >= CURDATE() and userId = ' . filter_var($userId, FILTER_SANITIZE_NUMBER_INT);
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();

        $conn = null;
    } catch (PDOException $e) {
        echo $e;
        throw new Exception('Failed to fetch sql data: ' . $e->getMessage());
    }
}
