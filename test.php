<?php
    include "commands.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $conn = dbConnect();
    addStudent($conn, "patoche@isen.com", "Coucou", "Patoche", "test", "0616155976", "CIR3");
    addStudent($conn, "patoche2@isen.com", "Coucou", "Patoche", "test", "0616155976", "CIR3");
    $students = getStudents($conn);
    echo "<b>ETUDIANTS</b><br>";
    foreach($students as $student){
        echo $student['mail']. '<br>';
    }
    deleteUser($conn, "patoche@isen.com");
    echo "<b>KILL PATOCHE</b><br>";
    $students = getStudents($conn);
    foreach($students as $student){
        echo $student['mail']. '<br>';
    }
    echo "<b>PROFESSEURS</b><br>";
    addTeacher($conn, 'patrick@isen.com', 'Patrick', 'Pat', 'test', '0616155975');
    addTeacher($conn, 'patrick3@isen.com', 'nik', 'la', 'test', '0616155976');
    addTeacher($conn, 'nazi@isen.com', 'isen', 'nazi', 'test', '0616155977');
    $teachers = getTeachers($conn);
    foreach($teachers as $teacher){
        echo $teacher['mail']. '<br>';
    }
    echo "<b>ADMINS</b><br>";
    addAdmin($conn, 'quentin@isen.com', 'Quentin', 'Le Goff', 'test', '0750230013');
    $admin = getAdmin($conn);
    echo $admin['mail']. '<br>';
?>