<?php
    require_once('utility.php');    
    include "commands.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $conn = dbConnect();
    addStudent($conn, "patoche@isen.com", "Coucou", "Patoche", "test", "0616159571", "CIR2");
    addStudent($conn, "patoche2@isen.com", "Coucou", "Patoche", "test", "0616159500", "CIR3");
    $students = getAllStudents($conn);
    echo "<b>ETUDIANTS</b><br>";
    foreach($students as $student){
        echo $student['mail']. '<br>';
    }
    deleteUser($conn, "patoche@isen.com");
    echo "<b>KILL PATOCHE</b><br>";
    $students = getAllStudents($conn);
    foreach($students as $student){
        echo $student['mail']. '<br>';
    }
    echo "<b>PROFESSEURS</b><br>";
    addTeacher($conn, 'patrick@isen.com', 'Patrick', 'Pat', 'test', '0616155975');
    addTeacher($conn, 'patrick3@isen.com', 'nik', 'la', 'test', '0616155983');
    addTeacher($conn, 'con@isen.com', 'con', 'isen', 'test', '0616155977');
    $teachers = getAllTeachers($conn);
    foreach($teachers as $teacher){
        echo $teacher['mail']. '<br>';
    }
    echo "<b>ADMINS</b><br>";
    addAdmin($conn, 'quentin@isen.com', 'Quentin', 'Le Goff', 'test', '0750230013');
    addAdmin($conn, 'allan@isen.com', 'Allan', 'Cueff', 'test', '0750230014');
    $admins = getAllAdmins($conn);
    foreach($admins as $admin){
        echo $admin['mail']. '<br>';
    }
    echo "<b>SEMESTERS</b><br>";
    addSemester($conn, '2018-09-01', '2019-02-01');
    deleteSemester($conn, '2018-09-01');
    deleteSemester($conn, '2023-04-25');
    echo "<b>LESSONS</b><br>";
    addTeacher($conn, 'mateosorin@isen.fr', 'Mateo', 'Sorin', 'test', '0616155998');
    getAllTeachers($conn);
    addSemester($conn, '2023-04-25', '2023-07-02');
    addLesson($conn, 'FHS', 'mateosorin@isen.fr', 'CIR2', '2023-04-25');
    echo "<b>EVALUATION</b><br>";
    $lesson = new Lesson(array(
        "subject" => "FHS",
        "date_begin" => "2023-04-25",
        "date_end" => "osef",
        "mail" => "mateosorin@isen.fr",
        "name" => "osef",
        "surname" => "osef",
        "phone" => "osef",
        "is_admin" => "osef",
        "cycle" => "CIR",
        "study_year" => 2,
        "class_name" => "CIR2",
        "campus_name" => "Nantes"
    ));
    addEvaluation($conn, $lesson, '2019-01-29 8:00:00', '2019-01-29 9:30:00', 2, "Calculatrices interdites");
    echo "<b>GRADE</b><br>";
    addGrade($conn, "lara.clette@messagerie.fr", $lesson, 17);
    addGrade($conn, "bernard.tichaud@messagerie.fr", $lesson, 2.5);
    echo '<b>MODIFY<b>';
    modifyUser($conn, 'lara.clette@messagerie.fr', 'Fosse', 'Raphaël', 'test', '0616171819');    
    modifyPhone($conn, 'lara.clette@messagerie.fr', '0636656565');
?>