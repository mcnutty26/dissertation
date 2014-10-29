<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 28/10/2014
 * Time: 20:50
 */

require 'core/user.php';

//Process actions
if (isset($_POST['newM'])) {
    $newName = database::escape($_POST['newM']);
    database::query("INSERT INTO ds_modules (name) VALUES ('$newName')");
}
if (isset($_POST['newC'])) {
    $newName = database::escape($_POST['newC']);
    $newQuestion = database::escape($_POST['newQ']);
    $module = database::escape($_POST['module']);
    database::query("INSERT INTO ds_content (F_moduleid, name, question) VALUES ('$module', '$newName', '$newQuestion')");
}

//Process display
if ($_POST['action'] == 'content') {

    if (isset($_POST['module'])) {
        $module = database::escape($_POST['module']);
        $result = database::query("SELECT * FROM ds_content WHERE F_moduleid == $module");
        echo '<table><thead><tr><td>Question ID</td><td>Name</td><td>Question</td><td>Edit?</td></tr></thead>';
        while($row = mysqli_fetch_array($result)) {
            echo '<tr><td>' . $row['contentid'] . '</td><td>' . $row['name'] . '</td><td>' . $row['question'] . '</td><td><form action="create.php" method="post"><input type="hidden" name="action" value="question"><input type="hidden" name="content" value="' . $row['contentid'] . '"><input type="submit" value="Edit"></form></td></tr>';
        }
        echo '<tr><form action="create.php" method="post"><input type="hidden" name="module" value="' . $module . '"><td>-</td><td><input type="text" name="newC" placeholder="New Question Name"></td><td><input type="text" name="newQ"></td><td><input type="submit" value="Save"></td></form></tr>';
        echo '</table>';
    }

} elseif ($_POST['action'] == 'user') {

} else {

    echo 'Available Modules:';
    $result = database::query("SELECT * FROM ds_modules");
    //$result2 = database::query("SELECT * FROM ds_content WHERE F_moduleid =" . )
    if ($result) {
        echo '<table><thead><tr><td>Module ID</td><td>Name</td><td>Content</td><td>Edit?</td></tr></thead>';
        while($row = mysqli_fetch_array($result)) {
            echo '<tr><td>' . $row['moduleid'] . '</td><td>' . $row['name'] . '</td><td>TODO</td><td><form action="create.php" method="post"><input type="hidden" name="action" value="content"><input type="hidden" name="module" value="' . $row['moduleid'] . '"><input type="submit" value="Edit"></form></td></tr>';
        }
        echo '<tr><form action="create.php" method="post"><td>-</td><td><input type="text" name="newM" placeholder="New Module Name"></td><td>None ... yet!</td><td><input type="submit" value="Save"></td></form></tr>';
        echo '</table>';
    }

}
