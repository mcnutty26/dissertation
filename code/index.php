<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 19/10/2014
 * Time: 14:55
 */

require './core/user.php';
require 'core/header.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: core/login.php');
}

$current_user = new user($_SESSION['user']);
$_SESSION['userid'] = $current_user->get_id();

echo 'You are logged in as ' . $current_user->get_name() . '<br><br>';
echo 'Thank you for participating in this study. Please refrain from using the forward and back buttons in your browser whilst using this software.<br><br>';
echo 'If you have any problems, please contact William Seymour at w.r.seymour&#64;warwick.ac.uk<br><br>';

if ($current_user->get_lecturer() == 1) { ?>
    <form action="create.php" method="post">
        <input type="submit" value="Create and administer content">
    </form>
<? } else {
    $query =    "SELECT F_moduleid, m.name
                 FROM ds_class AS c
                 INNER JOIN ds_module AS m ON F_moduleid = moduleid
                 WHERE c.F_userid = '" . $current_user->get_id() . "'";
                 //AND complete = 0";
    $result = database::query($query);
    ?>
<form action="content.php" method="post">
    <select name="module">
        <? while($row = mysqli_fetch_array($result)) {
            echo '<option value="' . $row['F_moduleid'] . '">' . $row['name'] . '</option>';
        } ?>
    </select>
    <input type="submit" value="Take this module">
</form>
<? } ?>

<form action="core/login.php" method="post">
<input name="out" id="out" type="hidden" value="OUT">
<input type="submit" value="Logout">
</form>
<? require 'core/footer.php'; ?>