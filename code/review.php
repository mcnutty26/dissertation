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
$userid = $current_user->get_id();
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
    $query = "
      SELECT SUM(value)
      FROM ds_result AS r
      INNER JOIN ds_answer AS a ON r.F_answerid = a.answerid
      INNER JOIN ds_content AS c ON a.F_contentid = c.contentid
      WHERE r.F_userid = $userid
      AND c.F_moduleid = $module";
    $query2 = "
      SELECT SUM(value)
      FROM ds_result AS r
      INNER JOIN ds_content as c ON r.F_contentid = c.contentid
      WHERE c.F_moduleid = $module";
}
