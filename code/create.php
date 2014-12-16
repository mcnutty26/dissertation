<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 28/10/2014
 * Time: 20:50
 */

require_once 'core/user.php';
session_start();
if (new user($_SESSION['user']) != true) {
    http_redirect('index.php');
}

require 'core/header.php';
//Process actions
if (isset($_POST['newM'])) {
    $newName = database::escape($_POST['newM']);
    database::query("INSERT INTO ds_module (name) VALUES ('$newName')");
} elseif (isset($_POST['newC'])) {
    $newName = database::escape($_POST['newC']);
    $newQuestion = database::escape($_POST['newQ']);
    $module = database::escape($_POST['module']);
    database::query("INSERT INTO ds_content (F_moduleid, name, question) VALUES ($module, '$newName', '$newQuestion')");
} elseif (isset($_POST['newA'])) {
    $value = database::escape($_POST['value']);
    $newAnswer = database::escape($_POST['newA']);
    $content = database::escape($_POST['content']);
    $explorer = database::escape($_POST['ex']);
    $killer = database::escape($_POST['ki']);
    $socialiser = database::escape($_POST['so']);
    $achiever = database::escape($_POST['ac']);
    database::query("INSERT INTO ds_answer (F_contentid, answer, value, explorer, killer, socialiser, achiever) VALUES ($content, '$newAnswer', $value, $explorer, $killer, $socialiser, $achiever);");
} elseif (isset($_POST['delete'])) {
    $answer = database::escape($_POST['delete']);
    database::query("DELETE FROM ds_answer WHERE answerid = $answer");
} elseif (isset($_POST['newL'])) {
    $user = database::escape($_POST['newL']);
    $module = database::escape($_POST['module']);
    database::query("INSERT INTO ds_class (F_userid, F_moduleid) VALUES ($user, $module)");
} elseif (isset($_POST['newU'])) {
    $user = database::escape($_POST['newU']);
    database::query("INSERT INTO ds_user (name) VALUES ('$user')");
}

//Process display
if ($_POST['action'] == 'content') {

    if (isset($_POST['module'])) {
        $module = database::escape($_POST['module']);
        $result = database::query("SELECT * FROM ds_content WHERE F_moduleid = $module");
        echo '<table><thead><tr><td>Question ID</td><td>Name</td><td>Question</td><td>Edit?</td></tr></thead>';
        while($row = mysqli_fetch_array($result)) {
            echo '<tr><td>' . $row['contentid'] . '</td><td>' . $row['name'] . '</td><td>' . $row['question'] . '</td><td><form action="create.php" method="post"><input type="hidden" name="action" value="answer"><input type="hidden" name="content" value="' . $row['contentid'] . '"><input type="submit" value="Edit"></form></td></tr>';
        }
        echo '<tr><form action="create.php" method="post"><input type="hidden" name="module" value="' . $module . '"><input type="hidden" name="action" value="content"><td>-</td><td><input type="text" name="newC" placeholder="New Question Name"></td><td><input type="text" name="newQ"></td><td><input type="submit" value="Save"></td></form></tr>';
        echo '</table>';
    }

} elseif ($_POST['action'] == 'answer') {

    if (isset($_POST['content'])) {
        $content = database::escape($_POST['content']);
        $result = database::query("SELECT * FROM ds_answer WHERE F_contentid = $content");
        echo '<table><thead><tr><td>Answer ID</td><td>Answer</td><td>Weight</td><td>+explorer</td><td>+killer</td><td>+socialiser</td><td>+achiever</td><td>Delete?</td></tr></thead>';
        while($row = mysqli_fetch_array($result)) {
            echo '<tr><td>' . $row['answerid'] . '</td><td>' . $row['answer'] . '</td><td>' . $row['value'] . '</td><td>' . $row['explorer'] . '</td><td>' . $row['killer'] . '</td><td>' . $row['socialiser'] . '</td><td>' . $row['achiever'] . '</td><td><form action="create.php" method="post"><input type="hidden" name="action" value="answer"><input type="hidden" name="content" value="' . $content . '"><input type="hidden" name="delete" value="' . $row['answerid'] . '"><input type="submit" value="Delete"></form></td></tr>';
        }
        echo '<tr><form action="create.php" method="post"><input type="hidden" name="content" value="' . $content . '"><input type="hidden" name="action" value="answer"><td>-</td><td><input type="text" name="newA" placeholder="New Answer"></td><td><input type="text" name="value"></td><td><input type="text" name="ex"></td><td><input type="text" name="ki"></td><td><input type="text" name="so"></td><td><input type="text" name="ac"></td><td><input type="submit" value="Save"></td></form></tr>';
        echo '</table>';
    }

} elseif ($_POST['action'] == 'user') {

} else {

    echo 'Available Modules:';
    $result = database::query("SELECT * FROM ds_module");
    //$result2 = database::query("SELECT * FROM ds_content WHERE F_moduleid =" . )
    if ($result) {
        echo '<table><thead><tr><td>Module ID</td><td>Name</td><td>Content</td><td>Edit?</td></tr></thead>';
        while($row = mysqli_fetch_array($result)) {
            echo '<tr><td>' . $row['moduleid'] . '</td><td>' . $row['name'] . '</td><td>TODO</td><td><form action="create.php" method="post"><input type="hidden" name="action" value="content"><input type="hidden" name="module" value="' . $row['moduleid'] . '"><input type="submit" value="Edit"></form></td></tr>';
        }
        echo '<tr><form action="create.php" method="post"><td>-</td><td><input type="text" name="newM" placeholder="New Module Name"></td><td>None ... yet!</td><td><input type="submit" value="Save"></td></form></tr>';
        echo '</table>';
    }

    echo 'Available Users:';
    $result2 = database::query("SELECT * FROM ds_user WHERE islecturer = 0");
    if ($result2) {
        echo '<table><thead><tr><td>User ID</td><td>Name</td><td>Classes</td><td>Add to class?</td><td>View?</td></tr></thead>';
        while($row = mysqli_fetch_array($result2)) {
            $result3 = database::query("SELECT m.name FROM ds_class INNER JOIN ds_module AS m ON F_moduleid = m.moduleid WHERE F_userid = " . $row['userid']);
            $classes = '';
            while($row2 = mysqli_fetch_array($result3)) {
                $classes .= $row2['name'] . '<br>';
            }
            echo '<tr><td>' . $row['userid'] . '</td><td>' . $row['name'] . '</td><td>' . $classes . '</td><td><form action="create.php" method="post"><input type="hidden" name="newL" value="' . $row['userid'] . '"><input type="text" name="module" placeholder="Module ID"><input type="submit" value="Add"></form></td><td>TODO</td></tr>';
        }
        echo '<tr><form action="create.php" method="post"><td>-</td><td><input type="text" name="newU" placeholder="New User Name"></td><td>-</td><td><input type="submit" value="Save"></td></form></tr>';
        echo '</table>';
    }

}
require 'core/footer.php';