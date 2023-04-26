<?php
    require_once "constants.php";
    require_once "database.class.php";
    require_once "utility.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $database = new Database(DB_NAME, DB_SERVER, DB_PORT, DB_USER, DB_PASSWORD);
    $success = $database->connect();
    if($success){
        echo "successfully connected to database <br>";
        $me = $database->authentify("allan@isen.fr", "passwordRandomPasHaché");
        if($me){
            echo "<br>My name is " . $me->getFullName() . "<br>";
            echo "I am an " . get_class($me) . "<br>";
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
                echo "I just deleted mateosorin@isen.fr from the list <br>";
            }
            $success = $me->addTeacher($mSorin, 'superSecure');
            if($success){
                echo "I just added " . $me->getUser("mateosorin@isen.fr")->getFullName() . " to the list <br>";
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
            $success = $me->deleteSemester('2018-09-01');
            if($success){
                echo "I deleted a semester starting from 2018-09-01<br>";
            }
            $success = $me->addSemester('2018-09-01', '2019-02-01');
            if($success){
                echo "I added a semester from 2018-09-01 to 2019-02-01 <br>";
            }
            echo "<b>LESSONS</b><br>";
            $success = $me->addLesson('FHS', 'mateosorin@isen.fr', 'CIR2', '2023-04-25');
            if($success){
                echo "This must not print as semester doesn't exists";
            }
            $success = $me->addLesson('FHS', 'mateosorin@isen.fr', 'CIR2', '2018-09-01');
            if($success){
                echo "I just added an FHS Lesson with mateosorin@isen.fr teaching CIR2 from 2018-09-01 to end of semester<br>";
            }
        }
    }
?>