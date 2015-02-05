<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 05/02/2015
 * Time: 10:49
 */

require_once 'core/user.php';
require 'core/header.php';

session_start();

$module = 10;
$current_user = new user($_SESSION['user']);
if ($current_user->get_lecturer() == 1) {

//Or update stored values
    $query = "SELECT SUM( a.killer ) as sk, SUM( a.socialiser ) as ss, SUM( a.explorer ) as se, SUM( a.achiever ) as sa, SUM( a.value ) as sv, r.f_userid as us
          FROM ds_result
            AS r
          INNER JOIN ds_answer
            AS a
            ON r.f_answerid = a.answerid
          GROUP BY r.f_userid";
    $result = database::query($query);

    echo '<table><tr><td>userid</td><td>killer</td><td>socialiser</td><td>explorer</td><td>achiever</td><td>score</td><td>K</td><td>S</td><td>E</td><td>A</td></tr>';
    while ($row = mysqli_fetch_array($result)) {

        $ach = ' (' . round($row['sa'] * 100 / 18) . ')';
        $kil = ' (' . round($row['sk'] * 100 / 18) . ')';
        $exp = ' (' . round($row['se'] * 100 / 18) . ')';
        $soc = ' (' . round($row['ss'] * 100 / 18) . ')';
        $val = ' (' . round($row['sv'] * 100 / 36) . ')';

        $ach2 = ' ' . round($row['sa'] * 100 / 18);
        $kil2 = ' ' . round($row['sk'] * 100 / 18);
        $exp2 = ' ' . round($row['se'] * 100 / 18);
        $soc2 = ' ' . round($row['ss'] * 100 / 18);

        echo '<tr><td>' . $row['us'] . '</td><td>' . $row['sk'] . $kil . '</td><td>' . $row['ss'] . $soc . '</td><td>' . $row['se'] . $exp . '</td><td>' . $row['sa']
            . $ach . '</td><td>' . $row['sv'] . $val . '</td><td>' . $kil2 . '</td><td>' . $soc2 . '</td><td>' . $exp2 . '</td><td>' . $ach2 . '</td></tr>';
    }
    echo '</table>';
}
require 'core/footer.php';