<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 06/01/2015
 * Time: 13:52
 */

require_once 'core/user.php';
require 'core/header.php';

$module = $_POST['module'];

$query = "SELECT userid, name, explorer, killer, socialiser, achiever, score
          FROM ds_user AS u
          INNER JOIN ds_class AS c ON u.userid = c.F_userid
          WHERE c.F_moduleid = $module";

$result = database::query($query);
echo "<table><thead><tr><td>Userid</td><td>Name</td><td>Explorer</td><td>Killer</td><td>Socialiser</td><td>Achiever</td><td>Score</td></tr></thead>";
while ($row = mysqli_fetch_array($result)) {
    $userid = $row['userid'];
    $name = $row['name'];
    $explorer = round(($row['explorer'] * 100), 0) . '&#37;';
    $killer = round(($row['killer'] * 100), 0) . '&#37;';
    $socialiser = round(($row['socialiser'] * 100), 0) . '&#37;';
    $achiever = round(($row['achiever'] * 100), 0) . '&#37;';

    $user = new user($name);
    $mask = $user->get_dominant();
    if (($mask & user::$MASK_KILLER) == user::$MASK_KILLER) {
        $killer = "<div class='bold'>" . $killer . "</div>";
    }
    if (($mask & user::$MASK_EXPLORER) == user::$MASK_EXPLORER) {
        $explorer = "<div class='bold'>" . $explorer . "</div>";
    }
    if (($mask & user::$MASK_SOCIALISER) == user::$MASK_SOCIALISER) {
        $socialiser = "<div class='bold'>" . $socialiser . "</div>";
    }
    if (($mask & user::$MASK_ACHIEVER) == user::$MASK_ACHIEVER) {
        $achiever = "<div class='bold'>" . $achiever . "</div>";
    }

    $score = $row['score'];
    echo "<tr><td>$userid</td><td>$name</td><td>$explorer</td><td>$killer</td><td>$socialiser</td><td>$achiever</td><td>$score</td></tr>";
}
echo "</table>";
?>
<br />
<form action="create.php" method="post">
        <input type="submit" value="Back to overview">
    </form>
<?
require 'core/footer.php';