<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 04/11/2014
 * Time: 11:31
 */

require 'core/database.php';

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
        echo $current_question;
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
    }

} else {
    //Or forward to review page
    header('Location: review.php');
}