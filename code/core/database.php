<?php
/**
 * Created by PhpStorm.
 * User: William Seymour!
 * Date: 19/10/2014
 * Time: 14:04
 */

define('host', 'localhost');
define('username', 'mcnutty');
define('password', 'passw0Rd');
define('dbname', 'mcnutty');

class database {
    public static function query($sql) {
        $conn = mysqli_connect(host, username, password, dbname);
        if (!$conn) {
            die('Could not connect to database');
        }
        $result = mysqli_query($conn, $sql);
        return $result;
    }
}
?>