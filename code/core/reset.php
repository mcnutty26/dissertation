<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 02/12/2014
 * Time: 17:33
 */

require "database.php";
session_start();

$module = $_POST['module'];
$user = $_SESSION['userid'];

echo $module;
echo $user;

$query = "CREATE TABLE #answers
          (
            id INT
          );

          INSERT INTO #answers
          SELECT answerid
          FROM ds_content AS c
          INNER JOIN ds_answer AS a ON c.contentid = a.F_contentid
          INNER JOIN ds_result AS r ON a.answerid = r.F_answerid
          WHERE r.F_userid = $user
          AND c.F_moduleid = $module;

          DELETE
          FROM ds_answer
          WHERE answerid IN
          (
            SELECT answerid
            FROM #answers
          );
          ";

database::query($query);

$query = "UPDATE ds_class
          SET complete = 0
          WHERE F_userid = $user
          AND F_moduleid = $module";

database::query($query);

$_SESSION['module'] = $module;
//header('Location: ../content.php');
?>