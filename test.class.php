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
            try {
                $me->deleteUser('mateosorin@isen.fr');
                echo "&#9989; - I just deleted mateosorin@isen.fr from the list <br>";
            } catch(Exception $e){
                echo "&#9989; - I cant delete mateosorin@isen.fr from the list as it probably doesnt exists<br>";
            }
            $me->addTeacher($mSorin, 'superSecure');
            echo "&#9989; - I just added " . $me->getUser("mateosorin@isen.fr")->getFullName() . " to the list <br>";
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
            try {
                $me->addSemester('2018-09-01', '2019-02-01', 'S1 2018/2019');
                echo "&#9989; - I added a semester from 2018-09-01 to 2019-02-01 <br>";
            } catch(Exception $e) {
                $me->deleteSemester('2018-09-01');
                $success = $me->addSemester('2018-09-01', '2019-02-01', 'S1 2018/2019');
                echo "&#9989; - I deleted and re-added a semester starting from 2018-09-01<br>";
            }
            try {
                $me->addSemester('2018-01-01', '2018-11-01', 'S2 2017/2018');
                echo "&#x274C; - I added a semester from 2018-01-01 to 2018-11-01 : SHOULD NOT HAPPEN<br>";
            } catch (Exception $e){
                echo "&#9989; - I can't add a semester from 2018-11-01 to 2018-11-01 as it is overlapping with other semester<br>";
            }
            echo "<b>LESSONS</b><br>";
            try {
                $me->addMatter('FHS');
                echo "&#9989; - I just added the matter FHS<br>";
            } catch(Exception $e){
                echo "&#9989; - FHS already exists<br>";
            }
            $cir2FHSLessonId;
            try {
                $cir2FHSLessonId = $me->addLesson('FHS', 'mateosorin@isen.fr', 1, '2023-04-25');
                echo "&#x274C; - Added lesson id : ". $cir2FHSLessonId ." with a semester that doesnt exists<br>";
            } catch(Exception $e) {
                echo "&#9989; - Can't add a lesson with semester that doesnt exists<br>";
            }
            try {
                $cir2FHSLessonId = $me->addLesson('FHS', 'mateosorin@isen.fr', 1, '2018-09-01');
                echo "&#9989; - I just added an FHS Lesson id : ". $cir2FHSLessonId ." with mateosorin@isen.fr teaching CIR2 from 2018-09-01 to february 2019<br>";
            } catch (Exception $e) {
                echo "&#x274C; - I cant add an FHS Lesson with mateosorin@isen.fr teaching CIR2 from 2018-09-01 to february 2019<br>";
            }
            $lessonsList = $me->listLessons();
            foreach($lessonsList as $lesson){
                echo $lesson->teacher->getFullName() . " is teaching " . $lesson->subject . " to the " . $lesson->class->name . "<br>";
            }
            $cir2FHSeval1;
            try {
                $cir2FHSeval1 = $me->addEvaluation($cir2FHSLessonId, '2018-11-18 8:00:00', '2018-11-18 9:30:00', 1, 'blabla');
                echo "&#9989; - I just added an evaluation id : ". $cir2FHSeval1 ." of FHS on 2018-11-18 8:00:00 for CIR2<br>";
            } catch (Exception $e){
                echo "&#x274C; - I can't add an evaluation of FHS on 2018-11-18 8:00:00 for CIR2 : SHOULD NOT HAPPEN<br>";
            }
            try {
                $cir2FHSeval1 = $me->addEvaluation($cir2FHSLessonId, '2019-01-29 8:00:00', '2019-01-29 9:30:00', 2, 'deuxième DS');
                echo "&#9989; - I just added an evaluation id : ". $cir2FHSeval1 ." of FHS coeff 2 on 2019-01-29 8:00:00 for CIR2<br>";
            } catch (Exception $e) {
                echo "&#x274C; - I can't add an evaluation of FHS coeff 2 on 2019-01-29 8:00:00 for CIR2 : SHOULD NOT HAPPEN<br>";
            }
            $cir1classId;
            try {
                $cir1classId = $me->addClass('CIR1', 'Nantes', 'CIR', 2022, 2027);
                echo "&#9989; - I just added the CIR 1 class id : ". $cir1classId ." in Nantes<br>";
            } catch(Exception $e) {
                $classList = $me->listClasses();
                foreach($classList as $class){
                    if($class->name === "CIR1" && $class->campus === "Nantes"){
                        $me->deleteClass($class->id);
                    }
                }
                $cir1classId = $me->addClass('CIR1', 'Nantes', 'CIR', 2022, 2027);
                echo "&#9989; - I just deleted and re-added the CIR 1 class id : " . $cir1classId . " in Nantes<br>";
            }
            print_r($me->listClasses());
            try {
                $cir1FHSLessonId = $me->addLesson('FHS', 'mateosorin@isen.fr', $cir1classId, '2018-09-01');
                echo "<br>&#9989; - I just added an FHS Lesson with mateosorin@isen.fr teaching CIR1 from 2018-09-01 to end of semester<br>";
            } catch(Exception $e) {
                echo "<br>&#x274C; - I cant add an FHS Lesson with mateosorin@isen.fr teaching CIR1 from 2018-09-01 to end of semester<br>";
            }
            $lessonsList = $me->listLessons();
            foreach($lessonsList as $lesson){
                echo $lesson->teacher->getFullName() . " is teaching " . $lesson->subject . " to the " . $lesson->class->name . "<br>";
            }
            try {
                $me->addEvaluation($cir1FHSLessonId, '2018-11-18 8:00:00', '2018-11-18 9:30:00', 1, 'blabla');
                echo "&#9989; - I just added an evaluation of FHS on 2018-11-18 8:00:00 for CIR1<br>";
            } catch(Excpetion $e) {
                echo "&#x274C; - I can't add an evaluation of FHS on 2018-11-18 8:00:00 for CIR1 : SHOULD NOT HAPPEN<br>";
            }
            try {
                $me->addEvaluation($cir1FHSLessonId, '2019-01-29 8:00:00', '2019-01-29 9:30:00', 1, 'deuxième DS');
                echo "&#9989; - I just added an evaluation of FHS on 2019-01-29 8:00:00 for CIR1<br>";
            } catch (Exception $e){
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
            echo "<br><br>";
            print_r($me->listLessonGrades($cir2FHSLessonId));
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