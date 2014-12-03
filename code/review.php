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
echo "You have completed this module<br><br>";
echo "Your dominant Bartle personality type is: ";
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
echo '<br><br>';
echo "Breakdown:<br>";
$achiever = $current_user->get_achiever();
$killer = $current_user->get_killer();
$socialiser = $current_user->get_socialiser();
$explorer = $current_user->get_explorer();
$total = $socialiser + $killer + $achiever + $explorer;

$achiever = round(($achiever / $total) * 100, 0);
$killer = round(($killer / $total) * 100, 0);
$socialiser = round(($socialiser / $total) * 100, 0);
$explorer = round(($explorer / $total) * 100, 0);
echo "Achiever: $achiever %<br>";
echo "Killer: $killer %<br>";
echo "Socialiser: $socialiser %<br>";
echo "Explorer: $explorer %<br>";

if (isset($_SESSION['module'])) {
    //Display stats for the last module opened
    $query = "
      SELECT SUM(value) as val
      FROM ds_result AS r
      INNER JOIN ds_answer AS a ON r.F_answerid = a.answerid
      INNER JOIN ds_content AS c ON a.F_contentid = c.contentid
      WHERE r.F_userid = $userid
      AND c.F_moduleid = $module";
    $result = database::query($query);
    $total = 0;
    $max = database::getModuleMax($module);
    while($row = mysqli_fetch_array($result)) {
        $total = $row['val'];
    }
    echo "<br>Your score for this module was $total / $max <br>";
    echo '<form action="discuss.php" method="post">
              <input type="hidden" name="choice" value=' . $module . '>
              <input type="submit" value="Discuss this module with others">
              </form>';
    echo '<form action="core/reset.php" method="post">
              <input type="hidden" name="module" value=' . $module . '>
              <input type="submit" value="Take this module again">
              </form>';
}
