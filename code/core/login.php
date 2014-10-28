<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 28/10/2014
 * Time: 14:55
 */

session_start();
if (isset($_POST['name'])) {
    $_SESSION['user'] = $_POST['name'];
    header("Location: ../index.php");
}
if (isset($_POST['out'])) {
    session_destroy();
}
?>
<form action="login.php" method="post">
<label for="name">Username:</label>
<input type="text" placeholder="Username" name="name" id="name">
<input type="submit" value="Submit">
</form>