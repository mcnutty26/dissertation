<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 19/10/2014
 * Time: 14:55
 */

require './core/user.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: core/login.php');
}

$current_user = new user($_SESSION['user']);
$current_user->print_user();

?>
<form action="core/login.php" method="post">
<input name="out" id="out" type="hidden" value="OUT">
<input type="submit" value="Submit">
</form>
