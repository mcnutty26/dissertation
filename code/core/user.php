<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 19/10/2014
 * Time: 14:49
 */
require_once 'database.php';

class user {
    private $id = 0;
    private $name = '';
    private $kiler = 0.0;
    private $explorer = 0.0;
    private $socialiser = 0.0;
    private $achiever = 0.0;
    private $islecturer = false;
    private $score = 0.0;
    private $decay_constant = 0.0;
    public static $MASK_KILLER = 0b1000;
    public static $MASK_EXPLORER = 0b0100;
    public static $MASK_SOCIALISER = 0b0010;
    public static $MASK_ACHIEVER = 0b0001;

    function __construct($uname) {
        global $id;
        global $name;
        global $killer;
        global $explorer;
        global $socialiser;
        global $achiever;
        global $islecturer;
        global $score;
        global $decay_constant;

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
        $decay_constant = 1 / 0.5;
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
    function get_dominant() {
        /*
         * Returns a binary value indicating all of the personality types this user currently has
         * $mask & 0b1000 -> killer
         * $mask & 0b0100 -> explorer
         * $mask & 0b0010 -> socialiser
         * $mask & 0b0001 -> achiever
         */
        global $killer;
        global $explorer;
        global $socialiser;
        global $achiever;
        $mask = 0b0000;
        $max = round(max($killer, $explorer, $socialiser, $achiever), 2);
        if (round($killer, 2) == $max) {
            $mask = $mask | self::$MASK_KILLER;
        }
        if (round($explorer, 2) == $max) {
            $mask = $mask | self::$MASK_EXPLORER;
        }
        if (round($socialiser, 2) == $max) {
            $mask = $mask| self::$MASK_SOCIALISER;
        }
        if (round($achiever, 2) == $max) {
            $mask = $mask | self::$MASK_ACHIEVER;
        }
        return $mask;
    }

    function update_killer($value) {
        global $id;
        global $killer;
        global $decay_constant;
        if ($killer != 0) {
            $killer = (($value * $decay_constant) + $killer) / ($decay_constant+ 1);
        } else {
            $killer = $value;
        }
        database::query("UPDATE ds_user SET killer = $killer WHERE userid = $id");
    }
    function update_explorer($value) {
        global $id;
        global $explorer;
        global $decay_constant;
        if ($explorer != 0) {
            $explorer = (($value * $decay_constant) + $explorer) / ($decay_constant + 1);
        } else {
            $explorer = $value;
        }
        database::query("UPDATE ds_user SET explorer = $explorer WHERE userid = $id");
    }
    function update_socialiser($value) {
        global $id;
        global $socialiser;
        global $decay_constant;
        if ($socialiser != 0) {
            $socialiser = (($value * $decay_constant) + $socialiser) / ($decay_constant + 1);
        } else {
            $socialiser = $value;
        }
        database::query("UPDATE ds_user SET socialiser = $socialiser WHERE userid = $id");
    }
    function update_achiever($value) {
        global $id;
        global $achiever;
        global $decay_constant;
        if ($achiever != 0) {
            $achiever = (($value * $decay_constant) + $achiever) / ($decay_constant + 1);
        } else {
            $achiever = $value;
        }
        database::query("UPDATE ds_user SET achiever = $achiever WHERE userid = $id");
    }
    function update_score($value) {
        global $id;
        global $score;
        global $decay_constant;
        if ($score != 0) {
            $score = (($value * $decay_constant) + $score) / ($decay_constant + 1);
        } else {
            $score = $value;
        }
        database::query("UPDATE ds_user SET score = $score WHERE userid = $id");
    }
}