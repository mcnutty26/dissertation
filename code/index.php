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
echo $current_user->get_name();

if ($current_user->get_lecturer()) { ?>
<form action="create.php" method="post">
    <input name="out" id="out" type="hidden" value="OUT">
    <input type="submit" value="Create and administer content">
</form>
<? } else {
    $query =    "SELECT m.F_moduleid, m.name
                 FROM ds_class AS c
                 INNER JOIN ds_module AS m ON c.F_moduleid = m.moduleid
                 WHERE c.userid = '" . $current_user->get_id() . "'";

    ?>
<form action="content.php" method="post">
    <select name="module">
        <option
    </select>
    <input name="out" id="out" type="hidden" value="OUT">
    <input type="submit" value="Create and administer content">
</form>
<? } ?>

<form action="core/login.php" method="post">
<input name="out" id="out" type="hidden" value="OUT">
<input type="submit" value="Logout">
</form>
