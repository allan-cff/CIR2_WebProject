<?php
    include "commands.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $conn = dbConnect();
    addStudent($conn, "patoche@isen.com", "Coucou", "Patoche", "test", "0616155976", "CIR3");
    $students = getAllStudents($conn);
    foreach($students as $student){
        echo $student['mail'];
    }
?>