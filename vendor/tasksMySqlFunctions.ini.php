<?php
class tasksMySqlFunctions
{
    function __construct() {
    }

    public static function instance(): self
    {
        static $obj;
        return !isset($obj) ? $obj = new self() : $obj;
    }

    /**
     * @throws Exception
     */
    public function mySqlInsert($arr)
    {
        try {
            $conn = new PDO('mysql:hostname=timekeepingsql;port=3306;dbname=php_environment', 'root', 'password');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = 'INSERT INTO tasks (task, start_time, end_time, total_time) values (:task, :startTime, :endTime, :timeSpent)';
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                //'task'=>filter_var($arr['task'],FILTER_SANITIZE_STRING),
                'task'=>htmlspecialchars($arr['task']),
                'startTime'=>filter_var(strtotime($arr['startTime']), FILTER_SANITIZE_NUMBER_INT), // Convert to unix timestamp
                'endTime'=>filter_var(strtotime($arr['endTime']), FILTER_SANITIZE_NUMBER_INT), // Convert to unix timestamp
                'timeSpent'=> ((strtotime($arr['endTime']) - strtotime($arr['startTime'])) / 60) // Do int math to get total hours
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
    public function mySqlDeleteTask($arr, $userId)
    {
        try {
            $conn = new PDO('mysql:hostname=timekeepingsql;port=3306;dbname=php_environment', 'root', 'password');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'DELETE FROM tasks where task = :task and created_date = :created_date;';
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'task'=>htmlspecialchars(str_replace('_', ' ', $arr['task'])),
                'created_date'=>htmlspecialchars(str_replace('_', ' ', $arr['delete'])),
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
    public function mySqlFetchToday()
    {
        try {
            $conn = new PDO('mysql:hostname=timekeepingsql;port=3306;dbname=php_environment', 'root', 'password');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'SELECT created_date, task, FROM_UNIXTIME(start_time), FROM_UNIXTIME(end_time), (total_time / 60) 
from tasks where created_date = CURDATE() order by FROM_UNIXTIME(start_time)';
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
    public function mySqlFetchThisWeek()
    {
        try {
            $conn = new PDO('mysql:hostname=timekeepingsql;port=3306;dbname=php_environment', 'root', 'password');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'SELECT created_date, task, FROM_UNIXTIME(start_time), FROM_UNIXTIME(end_time), (total_time / 60) 
from tasks where created_date between DATE_ADD(created_date, INTERVAL(-WEEKDAY(created_date)) DAY) and CURDATE() order by FROM_UNIXTIME(start_time)';
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
    public function mySqlTodaysTotalTime()
    {
        try {
            $conn = new PDO('mysql:hostname=timekeepingsql;port=3306;dbname=php_environment', 'root', 'password');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'SELECT sum(total_time / 60) from tasks where created_date = CURDATE()';
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
    public function mySqlTotalHours($type)
    {
        try {
            $conn = new PDO('mysql:hostname=timekeepingsql;port=3306;dbname=php_environment', 'root', 'password');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'SELECT sum(total_time / 60) from tasks where task like \'%'.$type.'%\' and created_date = CURDATE()';
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
    public function mySqlTotalWeekTime()
    {
        try {
            $conn = new PDO('mysql:hostname=timekeepingsql;port=3306;dbname=php_environment', 'root', 'password');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'SELECT sum(total_time / 60)
from tasks where created_date between DATE_ADD(created_date, INTERVAL(-WEEKDAY(created_date)) DAY) and CURDATE() order by FROM_UNIXTIME(start_time)';
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
}