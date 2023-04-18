<?php
include "commands.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);
$conn = dbConnect();
addStudent($conn, "quentin@isen.com", 1, 1);
$students = getStudents($conn);
foreach($students as $student){
    echo $student['email'];
}
?>