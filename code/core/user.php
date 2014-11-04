<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 19/10/2014
 * Time: 14:49
 */
require 'database.php';

class user {
    private $id = 0;
    private $name = '';
    private $kiler = 0.0;
    private $explorer = 0.0;
    private $socialiser = 0.0;
    private $achiever = 0.0;
    private $islecturer = false;

    function __construct($uname) {
        global $id;
        global $name;
        global $killer;
        global $explorer;
        global $socialiser;
        global $achiever;
        global $islecturer;

        $user_info = database::query("SELECT * FROM ds_user WHERE name='$uname' LIMIT 1");
        if ($user_info) {
            while($row = mysqli_fetch_array($user_info)) {
                $id = $row['userid'];
                $name = $row['name'];
                $killer = $row['killer'];
                $explorer = $row['explorer'];
                $socialiser = $row['socialiser'];
                $achiever = $row['achiever'];
                $islecturer = $row['islecturer'];
            }
        }
    }

    function get_id() {
        global $id;
        return $id;
    }
    function get_name() {
        global $name;
        return $name;
    }
    function get_lecturer() {
        global $islecturer;
        return $islecturer;
    }
}