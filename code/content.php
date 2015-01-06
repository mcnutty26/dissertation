<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 04/11/2014
 * Time: 11:31
 */

require 'core/user.php';
require 'core/header.php';

session_start();
if (isset($_POST['module'])) {
    $_SESSION['module'] = database::escape($_POST['module']);
}
$module = $_SESSION['module'];
$user = $_SESSION['userid'];

//Process answers
if (isset($_POST['answer'])) {
    $answer = $_POST['answer'];
    $query =   "INSERT INTO ds_result
                (F_userid, F_answerid)
                VALUES ($user, $answer)";
    database::query($query);
}

//Determine which questions have been answered already
$max = 0;
$question = 0;

//Get the highest question ID for this module
$query = "SELECT contentid
          FROM ds_content
          WHERE F_moduleid = $module
          ORDER BY contentid DESC
          LIMIT 1";
$result = database::query($query);
while($row = mysqli_fetch_array($result)) {
    $max = $row['contentid'];
}

//Get the highest answered question ID for this user/module pair
$query2 = " SELECT c.contentid
            FROM   ds_content AS c
                   INNER JOIN ds_answer AS a
                           ON c.contentid = a.f_contentid
                   INNER JOIN ds_result AS r
                           ON a.answerid = r.f_answerid
                   INNER JOIN ds_user AS u
                           ON r.f_userid = u.userid
            WHERE  u.userid = $user
                   AND c.f_moduleid = $module
            ORDER  BY c.contentid DESC
            LIMIT  1";
$result2 = database::query($query2);
while($row2 = mysqli_fetch_array($result2)) {
    $question = $row2['contentid'];
}

if ($question < $max) {
    //Load the next question
    $query = "SELECT *
              FROM ds_content
              WHERE F_moduleid = $module
              AND contentid > $question
              ORDER BY contentid ASC
              LIMIT 1";
    $result = database::query($query);
    while($row = mysqli_fetch_array($result)) {
        $current_question = $row['contentid'];
        echo '<h2>' . $row['name'] . '</h2>';
        echo '<h3>' . $row['question'] . '</h3>';
        $query2 = " SELECT *
                    FROM ds_answer
                    WHERE F_contentid = $current_question";
        $result2 = database::query($query2);
        echo '<form action="content.php" method="post">';
        while($row2 = mysqli_fetch_array($result2)) {
            echo '<input type="radio" name="answer" value="' . $row2['answerid'] . '">' . $row2['answer'] . ' (' . $row2['value'] . ' marks)<br>';
        }
        echo '<input type="submit" value="Submit">';
        echo '</form>';
        //Chat plugin
        echo '<form action="discuss.php" method="post">
              <input type="hidden" name="choice" value=' . $module . '>
              <input type="hidden" name="choice2" value=' . $current_question . '>
              <input type="submit" value="Discuss this question with others">';
    }

} else {
    //Or update stored values
    $query = "SELECT a.killer, a.socialiser, a.explorer, a.achiever, a.value FROM ds_user AS u
              INNER JOIN ds_result AS r ON u.userid = r.F_userid
              INNER JOIN ds_answer AS a ON r.F_answerid = a.answerid
              INNER JOIN ds_content AS c ON a.F_contentid = c.contentid
              INNER JOIN ds_module AS m ON c.F_moduleid = m.moduleid
              WHERE u.userid = $user
              AND m.moduleid = $module";
    $result = database::query($query);
    $t_killer = $t_socialiser = $t_explorer = $t_achiever = $t_score = 0.0;
    while($row = mysqli_fetch_array($result)) {
        $t_killer += $row['killer'];
        $t_explorer += $row['explorer'];
        $t_socialiser += $row['socialiser'];
        $t_achiever += $row['achiever'];
        $t_score += $row['value'];
    }
    $current_user = new user($_SESSION['user']);
    $current_user->update_killer($t_killer);
    $current_user->update_explorer($t_explorer);
    $current_user->update_socialiser($t_socialiser);
    $current_user->update_achiever($t_achiever);
    $current_user->update_score($t_score);
    echo $t_score;
    //Set module as complete
    database::query("UPDATE ds_class SET complete = 1 WHERE F_userid = $user");

    //Redirect to review page
    header('Location: review.php');
}
require 'core/footer.php';