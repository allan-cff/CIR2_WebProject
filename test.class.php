<?php
    require_once "constants.php";
    require_once "database.class.php";
    require_once "utility.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $database = new Database(DB_NAME, DB_SERVER, DB_PORT, DB_USER, DB_PASSWORD);
    $success = $database->connect();
    if($success){
        echo "&#9989; - successfully connected to database <br>";
        $me = $database->authentify("allan@isen.fr", "passwordRandomPasHaché");
        $me->connect();
        if($me){
            echo "<b>Login</b><br>&#9989; - My name is " . $me->getFullName() . "<br>";
            echo "&#9989; - I am an " . get_class($me) . "<br>";
            $mSorinInfos = '{
                "mail": "mateosorin@isen.fr",
                "name": "Sorin",
                "surname": "Mateo",
                "phone": "0616155998",
                "is_admin": false
            }';
            $mSorin = new Teacher(json_decode($mSorinInfos, true));
            $success = $me->deleteUser('mateosorin@isen.fr');
            if($success){
                echo "&#9989; - I just deleted mateosorin@isen.fr from the list <br>";
            }
            $success = $me->addTeacher($mSorin, 'superSecure');
            if($success){
                echo "&#9989; - I just added " . $me->getUser("mateosorin@isen.fr")->getFullName() . " to the list <br>";
            }
            echo "<b>USERS</b><br>";
            $usersList = $me->listUsers();
            foreach($usersList as $user){
                echo $user->getFullName() . " is a " . get_class($user);
                if($user->isAdmin()){
                    echo " and has administrator rights";
                }
                echo "<br>";
            }
            echo "<b>SEMESTERS</b><br>";
            $success = $me->addSemester('2018-09-01', '2019-02-01');
            if($success){
                echo "&#9989; - I added a semester from 2018-09-01 to 2019-02-01 <br>";
            } else {
                $me->deleteSemester('2018-09-01');
                $success = $me->addSemester('2018-09-01', '2019-02-01');
                if($success){
                    echo "&#9989; - I deleted and re-added a semester starting from 2018-09-01<br>";
                } else {
                    echo "&#9989; - I can't add a semester from 2018-09-01 to 2019-02-01 : SHOULD NOT HAPPEN<br>";
                }
            }
            $success = $me->addSemester('2018-01-01', '2018-11-01');
            if($success){
                echo "&#x274C; - I added a semester from 2018-01-01 to 2018-11-01 : SHOULD NOT HAPPEN<br>";
            } else {
                echo "&#9989; - I can't add a semester from 2018-11-01 to 2018-11-01 as it is overlapping with other semester<br>";
            }
            echo "<b>LESSONS</b><br>";
            $success = $me->addLesson('FHS', 'mateosorin@isen.fr', 'CIR2', '2023-04-25');
            if($success){
                echo "&#x274C; - This must not print as semester start doesn't exists<br>";
            }
            $success = $me->addLesson('FHS', 'mateosorin@isen.fr', 'CIR2', '2018-09-01');
            if($success){
                echo "&#9989; - I just added an FHS Lesson with mateosorin@isen.fr teaching CIR2 from 2018-09-01 to end of semester<br>";
            }
            $lessonsList = $me->listLessons();
            foreach($lessonsList as $lesson){
                echo $lesson->teacher->getFullName() . " is teaching " . $lesson->subject . " to the " . $lesson->class->name . "<br>";
            }
            $success = $me->addEvaluation($lessonsList[1], '2018-11-18 8:00:00', '2018-11-18 9:30:00', 1, 'blabla');
            if($success){
                echo "&#9989; - I just added an evaluation of FHS on 2018-11-18 8:00:00 for CIR2<br>";
            } else {
                echo "&#x274C; - I can't add an evaluation of FHS on 2018-11-18 8:00:00 for CIR2 : SHOULD NOT HAPPEN<br>";
            }
            $success = $me->addEvaluation($lessonsList[1], '2019-01-29 8:00:00', '2019-01-29 9:30:00', 1, 'deuxième DS');
            if($success){
                echo "&#9989; - I just added an evaluation of FHS on 2019-01-29 8:00:00 for CIR2<br>";
            } else {
                echo "&#x274C; - I can't add an evaluation of FHS on 2019-01-29 8:00:00 for CIR2 : SHOULD NOT HAPPEN<br>";
            }
            $success = $me->addClass('CIR1', 'Nantes', 'CIR', 1);
            if($success){
                echo "&#9989; - I just added the CIR 1 class in Nantes<br>";
            } else {
                $me->deleteClass('CIR1', 'Nantes', 'CIR');
                $success = $me->addClass('CIR1', 'Nantes', 'CIR', 1);
                if($success){
                    echo "&#9989; - I just deleted and re-added the CIR 1 class in Nantes<br>";
                } else {
                    echo "&#x274C; - I can't add the CIR 1 class in Nantes<br>";
                }    
            }
            print_r($me->listClasses());
        }
        $me = $database->authentify("mateosorin@isen.fr", "superSecure");
        $me->connect();
        if($me){
            echo "<br><b>New login</b><br>&#9989; - My name is " . $me->getFullName() . "<br>";
            echo "&#9989; - I am an " . get_class($me) . "<br>";
            echo "<b>GRADES</b><br>";
            $me->addGrade("lara.clette@messagerie.fr", $lessonsList[1], 16);
            echo "Adding a 16 in FHS to lara.clette<br>";
            $me->addGrade("jacques.ouzi@messagerie.fr", $lessonsList[1], 12);
            echo "Adding a 12 in FHS to jacques.ouzi<br>";
            $me->addGrade("bernard.tichaud@messagerie.fr", $lessonsList[1], 9);
            echo "Adding a 9 in FHS to bernard.tichaud<br>";
            echo "Should have 3 not null and an average of 12.33<br>";
            print_r($me->listEvaluations());
        }
    }
?>