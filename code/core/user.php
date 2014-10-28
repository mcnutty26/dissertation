<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 19/10/2014
 * Time: 14:49
 */
require 'database.php';

class user {
    public $name = '';
    public $kiler = 0.0;
    public $explorer = 0.0;
    public $socialiser = 0.0;
    public $achiever = 0.0;

    function __construct($uname) {
        global $name;
        $user_info = database::query("SELECT * FROM ds_users WHERE name='$uname' LIMIT 1");
        if ($user_info) {
            while($row = mysqli_fetch_array($user_info)) {
                $name = $row['name'];
                $killer = $row['killer'];
                $killer = $row['explorer'];
                $killer = $row['socialiser'];
                $killer = $row['achiever'];
            }
        }
    }
    function print_user() {
        global $name;
        echo $name;
    }
}