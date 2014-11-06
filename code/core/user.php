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
    private $score = 0.0;

    private $decay_constant = 0.75;

    function __construct($uname) {
        global $id;
        global $name;
        global $killer;
        global $explorer;
        global $socialiser;
        global $achiever;
        global $islecturer;
        global $score;

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
                $score = $row['score'];
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
    function get_killer() {
        global $killer;
        return $killer;
    }
    function get_explorer() {
        global $explorer;
        return $explorer;
    }
    function get_socialiser() {
        global $socialiser;
        return $socialiser;
    }
    function get_achiever() {
        global $achiever;
        return $achiever;
    }
    function get_score() {
        global $score;
        return $score;
    }

    function update_killer($value) {
        global $id;
        global $killer;
        if ($killer != 0) {
            global $decay_constant;
            $delta = pow($decay_constant, -1);
            $killer = (($value * $delta) + $killer) / ($delta + 1);
        } else {
            $killer = $value;
        }
        database::query("UPDATE ds_user SET killer = $killer WHERE userid = $id");
    }
    function update_explorer($value) {
        global $id;
        global $explorer;
        if ($explorer != 0) {
            global $decay_constant;
            $delta = pow($decay_constant, -1);
            $explorer = (($value * $delta) + $explorer) / ($delta + 1);
        } else {
            $explorer = $value;
        }
        database::query("UPDATE ds_user SET explorer = $explorer WHERE userid = $id");
    }
    function update_socialiser($value) {
        global $id;
        global $socialiser;
        if ($socialiser != 0) {
            global $decay_constant;
            $delta = pow($decay_constant, -1);
            $socialiser = (($value * $delta) + $socialiser) / ($delta + 1);
        } else {
            $socialiser = $value;
        }
        database::query("UPDATE ds_user SET socialiser = $socialiser WHERE userid = $id");
    }
    function update_achiever($value) {
        echo '!', $value, '!';
        global $id;
        global $achiever;
        if ($achiever != 0) {
            global $decay_constant;
            $delta = pow($decay_constant, -1);
            $achiever = (($value * $delta) + $achiever) / ($delta + 1);
        } else {
            $achiever = $value;
        }
        echo $value, $achiever, $id;
        database::query("UPDATE ds_user SET achiever = $achiever WHERE userid = $id");
    }
    function update_score($value) {
        global $id;
        global $score;
        if ($score != 0) {
            global $decay_constant;
            $delta = pow($decay_constant, -1);
            $score = (($value * $delta) + $score) / ($delta + 1);
        } else {
            $score = $value;
        }
        database::query("UPDATE ds_user SET score = $score WHERE userid = $id");
    }
}