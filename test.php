<?php
include "commands.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);
$conn = dbConnect();
addUser($conn,'léna.zi@messagerie.fr', 'Zi', 'Léna', 'test', '11/04/2023 15:30:00.000', '0612345687');
if ($conn == false){
    echo '<br>error';
}
$users = getUsers($conn);
echo '<br>';
foreach($users as $user){
    echo $user["mail"].'<br>';
}
print_r($users);
$userToSearch = getUser($conn, 'léna.zi@messagerie.fr');
echo $userToSearch['mail'];
?>