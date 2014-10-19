<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 19/10/2014
 * Time: 14:49
 */
require 'database.php';

class user {
    public $name;

    function __construct($name) {
        $user_info = database::query("SELECT * FROM ds_users WHERE uname='$name' LIMIT 1");
        if ($user_info) {
            while($row = mysqli_fetch_array($result)) {
                $name = $row['name'];
            }
        }
    }
    function print_user() {
        echo $name;
    }
}