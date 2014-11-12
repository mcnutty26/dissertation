<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 04/11/2014
 * Time: 13:31
 */

require 'core/user.php';
session_start();
$current_user = new user($_SESSION['user']);
$mask = $current_user->get_dominant();
$user = $_SESSION['user'];
$module = $_SESSION['module'];

//Display stats for this user, depending on what type of bartle personality they are
if (($mask & user::$MASK_KILLER) == user::$MASK_KILLER) {
    echo 'KILLER';
}
if (($mask & user::$MASK_EXPLORER) == user::$MASK_EXPLORER) {
    echo 'EXPLORER';
}
if (($mask & user::$MASK_SOCIALISER) == user::$MASK_SOCIALISER) {
    echo 'SOCIALISER';
}
if (($mask & user::$MASK_ACHIEVER) == user::$MASK_ACHIEVER) {
    echo 'ACHIEVER';
}

if (isset($_SESSION['module'])) {
    //Display stats for the last module opened

}
