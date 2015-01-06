<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 02/12/2014
 * Time: 17:33
 */

require_once "database.php";
session_start();

$module = $_POST['module'];
$user = $_SESSION['userid'];

echo $module;
echo $user;

$query = "DELETE r
          FROM ds_result AS r
          INNER JOIN ds_answer as a ON r.F_answerid = a.answerid
          INNER JOIN ds_content AS c ON a.F_contentid = c.contentid
          INNER JOIN ds_module AS m ON c.F_moduleid = m.moduleid
          INNER JOIN ds_class AS l ON m.moduleid = l.F_moduleid
          INNER JOIN ds_user AS u ON l. F_userid = u.userid
          WHERE u.userid = $user
          AND m.moduleid = $module";

database::query($query);

$query2 = "UPDATE ds_class
          SET complete = 0
          WHERE F_userid = $user
          AND F_moduleid = $module";

database::query($query2);

$_SESSION['module'] = $module;
header('Location: ../content.php');
?>