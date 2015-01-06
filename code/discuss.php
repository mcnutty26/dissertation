<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 29/11/2014
 * Time: 15:40
 */
require "core/user.php";
require 'core/header.php';

session_start();
$current_user = new user($_SESSION['user']);
$user = $_SESSION['user'];
$userid = $current_user->get_id();

//Process actions
if (isset($_POST['message'])) {
    $message = database::escape($_POST['message']);
    if (isset($_POST['choice2'])) { //User commented on a question
        $c2 = $_SESSION['choice2'];
        $query = "INSERT INTO ds_chat
                      (type, itemid, F_userid, message)
                      VALUES
                      ('CONTENT', $c2, $userid, '$message')";
        database::query($query);
    } else { //User commented on a module
        $c = $_SESSION['choice'];
        $query = "INSERT INTO ds_chat
                      (type, itemid, F_userid, message)
                      VALUES
                      ('MODULE', $c, $userid, '$message')";
        database::query($query);
    }
}

//Process display

$query =    "SELECT name, moduleid
             FROM ds_module AS m";

$result = database::query($query);
if (!isset($_POST['choice'])) { //The user just landed on this page
    echo '? -> ?';
    ?>
    <form action="discuss.php" method="post">
        <select name="choice">
            <? while ($row = mysqli_fetch_array($result)) {
                echo '<option value="' . $row['moduleid'] . '">' . $row['name'] . '</option>';
            } ?>
        </select>
        <input type="submit" value="Submit">
    </form>
<?
} else if (!isset($_POST['choice2'])) { //The user has picked a module, but not a specific question
    $query =    "SELECT name
             FROM ds_module
             WHERE moduleid = " . $_POST['choice'];
    $result = database::query($query);
    $choice = '';
    while ($row = mysqli_fetch_array($result)) {
        $_SESSION['choice'] = $row['name'];
    }

    echo $_SESSION['choice'] . ' -> ?';
    $_SESSION['choice'] = $_POST['choice'];
    $query =    "SELECT name, contentid
             FROM ds_content AS c
             WHERE F_moduleid = " . $_POST['choice'];

    $result = database::query($query);
    ?>
    <form action="discuss.php" method="post">
        <select name="choice2">
            <? while($row = mysqli_fetch_array($result)) {
                echo '<option value="' . $row['contentid'] . '">' . $row['name'] . '</option>';
            } ?>
        </select>
        <input type="hidden" name="choice" value="<? echo $_POST['choice']; ?>">
        <input type="submit" value="Submit">
    </form>
    <?
    echo "<br><br>Current talk on this module:";
    $query =    "SELECT u.name, c.message
    FROM ds_chat AS c
    INNER JOIN ds_user AS u ON c.F_userid = u.userid
    WHERE c.type = 'MODULE'
    AND itemid =" . $_POST['choice'];
    $result = database::query($query);
    echo '<table border=1>';
        echo '<tr><td>User</td><td>Message</td></tr>';
        while ($row = mysqli_fetch_array($result)) {
        echo '<tr><td>'. $row['name'] . '</td><td>' . $row['message'] . '</td></tr>';
        }
        echo '</table>';
    echo '<br>Have your say:<br>'; ?>
    <form action="discuss.php" method="post">
        <input type="text" name="message"><br>
        <input type="hidden" name="choice" value="<? echo $_POST['choice']; ?>">
        <input type="submit" value="Post">
    </form>

    <?
} else { //The user has narrowed down the selection to a specific question
    $query =    "SELECT name
             FROM ds_content AS m
             WHERE contentid = " . $_POST['choice2'];
    $result = database::query($query);
    $choice2 = '';
    while ($row = mysqli_fetch_array($result)) {
        $choice2 = $row['name'];
    }

    echo $_SESSION['choice'] . ' -> ' . $choice2;
    $_SESSION['choice2'] = $_POST['choice2'];

    echo "<br><br>Current talk on this question:";
    $query =    "SELECT u.name, c.message
                 FROM ds_chat AS c
                 INNER JOIN ds_user AS u ON c.F_userid = u.userid
                 WHERE c.type = 'CONTENT'
                 AND itemid =" . $_POST['choice2'];
    $result = database::query($query);
    echo '<table border=1>';
    echo '<tr><td>User</td><td>Message</td></tr>';
    while ($row = mysqli_fetch_array($result)) {
        echo '<tr><td>'. $row['name'] . '</td><td>' . $row['message'] . '</td></tr>';
    }
    echo '</table>';
    echo '<br>Have your say:<br>'; ?>
    <form action="discuss.php" method="post">
        <input type="text" name="message"><br>
        <input type="hidden" name="choice" value="<? echo $_POST['choice']; ?>">
        <input type="hidden" name="choice2" value="<? echo $_POST['choice2']; ?>">
        <input type="submit" value="Post">
    </form>
<? }
require 'core/footer.php';?>