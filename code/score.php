<?php
/**
 * Created by PhpStorm.
 * User: William
 * Date: 14/12/2014
 * Time: 16:37
 */

require_once 'core/user.php';
require 'core/header.php';

session_start();
$current_user = new user($_SESSION['user']);
$mask = $current_user->get_dominant();
$user = $_SESSION['user'];
$userid = $current_user->get_id();
$module = $_SESSION['module'];

// Get a list of users and scores from the database
$query = "SELECT name, score
          FROM ds_user
          ORDER BY score DESC";

$result = database::query($query);

$u_score = $current_user->get_score();
if ($current_user->get_score() == 0) {
    $u_score = 0.00001;
}

echo '<table><thead><tr><td>User</td><td>Score</td><td>Relative</td></tr></thead>';
while($row = mysqli_fetch_array($result)) {
    $t_name = $row['name'];
    $t_score = $row['score'];
    $output = "<td>$t_name</td><td>$t_score</td><td>" . ($t_score / $u_score) * 100 . "&#37;</td></tr>";
    if ($t_name === $user) {
        echo '<tr class="bold">' . $output;
    } else {
        echo '<tr>' . $output;
    }
}
echo '</table>';
require 'core/footer.php';