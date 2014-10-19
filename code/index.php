<?php
/**
 * Created by PhpStorm.
 * User: Will
 * Date: 19/10/2014
 * Time: 14:55
 */
echo 'hello';

require './core/user.php';
$current_user = new user('John');
$current_user.print_user();

?>