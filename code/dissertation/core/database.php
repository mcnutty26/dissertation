<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 19/10/2014
 * Time: 14:04
 */

define('host', 'localhost');
define('username', 'mcnutty');
define('password', 'passw0Rd');
define('dbname', 'mcnutty');

class database {
    public $conn;

    function __construct() {
        $conn = mysqli_connect(host, username, password, dbname);
        if (!$conn) {
            die('Could not connect to database');
        }
    }

    function query($sql) {
        $result = mysqli_query($conn, $sql);
        return $result;
    }

    function __destruct() {
        mysqli_close($conn);
    }
}

?>