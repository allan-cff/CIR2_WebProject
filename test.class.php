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
            echo "<b>LOG IN AS AN ADMIN</b><br>&#9989; - My name is " . $me->getFullName() . "<br>";
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
            $success = $me->addSemester('2018-09-01', '2019-02-01', 'S1 2018/2019');
            if($success){
                echo "&#9989; - I added a semester from 2018-09-01 to 2019-02-01 <br>";
            } else {
                $me->deleteSemester('2018-09-01');
                $success = $me->addSemester('2018-09-01', '2019-02-01', 'S1 2018/2019');
                if($success){
                    echo "&#9989; - I deleted and re-added a semester starting from 2018-09-01<br>";
                } else {
                    echo "&#9989; - I can't add a semester from 2018-09-01 to 2019-02-01 : SHOULD NOT HAPPEN<br>";
                }
            }
            $success = $me->addSemester('2018-01-01', '2018-11-01', 'S2 2017/2018');
            if($success){
                echo "&#x274C; - I added a semester from 2018-01-01 to 2018-11-01 : SHOULD NOT HAPPEN<br>";
            } else {
                echo "&#9989; - I can't add a semester from 2018-11-01 to 2018-11-01 as it is overlapping with other semester<br>";
            }
            echo "<b>LESSONS</b><br>";
            $success = $me->addMatter('FHS');
            if($success){
                echo "&#9989; - I just added the matter FHS<br>";
            }
            $success = $me->addLesson('FHS', 'mateosorin@isen.fr', 'CIR2', '2023-04-25');
            if($success){
                echo "&#x274C; - This must not print as semester start doesn't exists<br>";
            }
            $success = $me->addLesson('FHS', 'mateosorin@isen.fr', 'CIR2', '2018-09-01');
            if($success){
                echo "&#9989; - I just added an FHS Lesson with mateosorin@isen.fr teaching CIR2 from 2018-09-01 to february 2019<br>";
            }
            $lessonsList = $me->listLessons();
            foreach($lessonsList as $lesson){
                if($lesson->class->name === "CIR2" && $lesson->subject === "FHS"){
                    $cir2FHSLessonId = $lesson->id;
                }
                echo $lesson->teacher->getFullName() . " is teaching " . $lesson->subject . " to the " . $lesson->class->name . "<br>";
            }
            $success = $me->addEvaluation($cir2FHSLessonId, '2018-11-18 8:00:00', '2018-11-18 9:30:00', 1, 'blabla');
            if($success){
                echo "&#9989; - I just added an evaluation of FHS on 2018-11-18 8:00:00 for CIR2<br>";
            } else {
                echo "&#x274C; - I can't add an evaluation of FHS on 2018-11-18 8:00:00 for CIR2 : SHOULD NOT HAPPEN<br>";
            }
            $success = $me->addEvaluation($cir2FHSLessonId, '2019-01-29 8:00:00', '2019-01-29 9:30:00', 2, 'deuxième DS');
            if($success){
                echo "&#9989; - I just added an evaluation of FHS coeff 2 on 2019-01-29 8:00:00 for CIR2<br>";
            } else {
                echo "&#x274C; - I can't add an evaluation of FHS coeff 2 on 2019-01-29 8:00:00 for CIR2 : SHOULD NOT HAPPEN<br>";
            }
            $success = $me->addClass('CIR1', 'Nantes', 'CIR', 2022, 2027);
            if($success){
                echo "&#9989; - I just added the CIR 1 class in Nantes<br>";
            } else {
                $classList = $me->listClasses();
                foreach($classList as $class){
                    if($class->name === "CIR1" && $class->campus === "Nantes"){
                        $me->deleteClass($class->id);
                    }
                }
                $success = $me->addClass('CIR1', 'Nantes', 'CIR', 2022, 2027);
                if($success){
                    echo "&#9989; - I just deleted and re-added the CIR 1 class in Nantes<br>";
                } else {
                    echo "&#x274C; - I can't add the CIR 1 class in Nantes<br>";
                }    
            }
            print_r($me->listClasses());
            $success = $me->addLesson('FHS', 'mateosorin@isen.fr', 'CIR1', '2018-09-01');
            if($success){
                echo "<br>&#9989; - I just added an FHS Lesson with mateosorin@isen.fr teaching CIR1 from 2018-09-01 to end of semester<br>";
            }
            $lessonsList = $me->listLessons();
            foreach($lessonsList as $lesson){
                if($lesson->class->name === "CIR1" && $lesson->subject === "FHS"){
                    $cir1FHSLessonId = $lesson->id;
                }
                echo $lesson->teacher->getFullName() . " is teaching " . $lesson->subject . " to the " . $lesson->class->name . "<br>";
            }
            $success = $me->addEvaluation($cir1FHSLessonId, '2018-11-18 8:00:00', '2018-11-18 9:30:00', 1, 'blabla');
            if($success){
                echo "&#9989; - I just added an evaluation of FHS on 2018-11-18 8:00:00 for CIR1<br>";
            } else {
                echo "&#x274C; - I can't add an evaluation of FHS on 2018-11-18 8:00:00 for CIR1 : SHOULD NOT HAPPEN<br>";
            }
            $success = $me->addEvaluation($cir1FHSLessonId, '2019-01-29 8:00:00', '2019-01-29 9:30:00', 1, 'deuxième DS');
            if($success){
                echo "&#9989; - I just added an evaluation of FHS on 2019-01-29 8:00:00 for CIR1<br>";
            } else {
                echo "&#x274C; - I can't add an evaluation of FHS on 2019-01-29 8:00:00 for CIR1 : SHOULD NOT HAPPEN<br>";
            }
            $listTeacher = $me->listTeachers();
            foreach($listTeacher as $teacher){
                echo "<br>" . $teacher->getFullName();
            }
        }
        $me = $database->authentify("mateosorin@isen.fr", "superSecure");
        $me->connect();
        if($me){
            echo "<br><b>LOG IN AS A TEACHER</b><br>&#9989; - My name is " . $me->getFullName() . "<br>";
            echo "&#9989; - I am an " . get_class($me) . "<br>";
            echo "<b>GRADES</b><br>";
            $me->addGrade("lara.clette@messagerie.fr", $cir2FHSLessonId, '2019-01-29 8:00:00', 16);
            echo "Adding a 16 in FHS to lara.clette<br>";
            $me->addGrade("jacques.ouzi@messagerie.fr", $cir2FHSLessonId, '2019-01-29 8:00:00', 12);
            echo "Adding a 12 in FHS to jacques.ouzi<br>";
            $me->addGrade("bernard.tichaud@messagerie.fr", $cir2FHSLessonId, '2019-01-29 8:00:00', 9);
            echo "Adding a 9 in FHS to bernard.tichaud<br>";
            $me->addGrade("lara.clette@messagerie.fr", $cir2FHSLessonId, '2018-11-18 8:00:00', 14);
            echo "Adding a 14 in FHS to lara.clette on the other eval<br>";
            $me->addGrade("jacques.ouzi@messagerie.fr", $cir2FHSLessonId, '2018-11-18 8:00:00', 11);
            echo "Adding a 11 in FHS to jacques.ouzi on the other eval<br>";
            $me->addGrade("bernard.tichaud@messagerie.fr", $cir2FHSLessonId, '2018-11-18 8:00:00', 11);
            echo "Adding a 11 in FHS to bernard.tichaud on the other eval<br>";
            echo "Should have 3 not null and an average of 12.33 for the first eval<br>";
            echo "Should have 3 not null and an average of 12 for the second eval<br>";
            print_r($me->listLessons());
        }
        $me = $database->authentify("lara.clette@messagerie.fr", "passwordRandomPasHaché"); 
        $me->connect();
        if($me){
            echo "<br><b>LOG IN AS A STUDENT</b><br>&#9989; - My name is " . $me->getFullName() . "<br>";
            echo "&#9989; - I am an " . get_class($me) . "<br>";
            echo "<b>LESSONS</b><br>";
            $lessonsList = $me->listLessons();
            foreach($lessonsList as $lesson){
                echo $lesson->subject . "<br>";
            }
            $classFHSAverage = $me->classAverageInLesson('FHS', '2018-09-01');
            foreach($classFHSAverage as $av){
                echo "My class average in FHS is : " . ($av["average"] ?? "undefined") . " for semester going from " . $av["date_begin"] ." to " . $av["date_end"] . "<br>";
            }
            $persoFHSAverage = $me->personalAverageInLesson('FHS', '2018-09-01');
            foreach($persoFHSAverage as $av){
                echo "My personnal average in FHS is : " . ($av["average"] ?? "undefined") . " for semester going from " . $av["date_begin"] ." to " . $av["date_end"] . "<br>";
            } 
            $FHSranks = $me->rankInLesson('FHS', '2018-09-01');
            foreach($FHSranks as $rank){
                echo "My rank in FHS is : " . ($rank["rank"] ?? "undefined") . " for semester going from " . $rank["date_begin"] ." to " . $rank["date_end"] . "<br>";
            }
        }
        $me = $database->authentify("jacques.ouzi@messagerie.fr", "passwordRandomPasHaché"); 
        $me->connect();
        if($me){
            echo "<br><b>LOG IN AS A STUDENT</b><br>&#9989; - My name is " . $me->getFullName() . "<br>";
            echo "&#9989; - I am an " . get_class($me) . "<br>";
            echo "<b>LESSONS</b><br>";
            $lessonsList = $me->listLessons();
            foreach($lessonsList as $lesson){
                echo $lesson->subject . "<br>";
            }
            $classFHSAverage = $me->classAverageInLesson('FHS', '2018-09-01');
            foreach($classFHSAverage as $av){
                echo "My class average in FHS is : " . ($av["average"] ?? "undefined") . " for semester going from " . $av["date_begin"] ." to " . $av["date_end"] . "<br>";
            }
            $persoFHSAverage = $me->personalAverageInLesson('FHS', '2018-09-01');
            foreach($persoFHSAverage as $av){
                echo "My personnal average in FHS is : " . ($av["average"] ?? "undefined") . " for semester going from " . $av["date_begin"] ." to " . $av["date_end"] . "<br>";
            } 
            $FHSranks = $me->rankInLesson('FHS', '2018-09-01');
            foreach($FHSranks as $rank){
                echo "My rank in FHS is : " . ($rank["rank"] ?? "undefined") . " for semester going from " . $rank["date_begin"] ." to " . $rank["date_end"] . "<br>";
            }
        }
        $me = $database->authentify("bernard.tichaud@messagerie.fr", "passwordRandomPasHaché"); 
        $me->connect();
        if($me){
            echo "<br><b>LOG IN AS A STUDENT</b><br>&#9989; - My name is " . $me->getFullName() . "<br>";
            echo "&#9989; - I am an " . get_class($me) . "<br>";
            echo "<b>LESSONS</b><br>";
            $lessonsList = $me->listLessons();
            foreach($lessonsList as $lesson){
                echo $lesson->subject . "<br>";
            }
            $classFHSAverage = $me->classAverageInLesson('FHS', '2018-09-01');
            foreach($classFHSAverage as $av){
                echo "My class average in FHS is : " . ($av["average"] ?? "undefined") . " for semester going from " . $av["date_begin"] ." to " . $av["date_end"] . "<br>";
            }
            $persoFHSAverage = $me->personalAverageInLesson('FHS', '2018-09-01');
            foreach($persoFHSAverage as $av){
                echo "My personnal average in FHS is : " . ($av["average"] ?? "undefined") . " for semester going from " . $av["date_begin"] ." to " . $av["date_end"] . "<br>";
            } 
            $FHSranks = $me->rankInLesson('FHS', '2018-09-01');
            foreach($FHSranks as $rank){
                echo "My rank in FHS is : " . ($rank["rank"] ?? "undefined") . " for semester going from " . $rank["date_begin"] ." to " . $rank["date_end"] . "<br>";
            }
        }       
    }
?>