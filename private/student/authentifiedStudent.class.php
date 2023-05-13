<?php
    require_once(realpath(dirname(__FILE__) . '/../../utility.php'));
    require_once(realpath(dirname(__FILE__) . '/../../database.class.php'));
    //Weird require as this file will be included and change location

    // THIS CLASS IS USABLE ONLY BY AN AUTHENTIFIED STUDENT
    // IT SHOULD BE USED TO PROVIDE ALL FUNCTIONS TO ACCESS DATABASE AS A STUDENT

    class AuthentifiedStudent extends Student {
        private $database;
        public function __construct($db, $dbRow){
            parent::__construct($dbRow);
            $this->database = $db;
        }
        public function connect(){
            return $this->database->connect();
        }

        public function listLessons(){
            $sql = $this->database->conn->prepare('SELECT * FROM public.lesson JOIN public.student USING (mail);');
            $sql->execute;
            $lessonsList = $sql->fetchAll(PDO::FETCH_ASSOC);
            $toLessonClass = function($lessonDbRow){
                return new Lesson($lessonDbRow);
            };       
            return array_map($toLessonClass, $lessonsList);
        }

        public function averageInLessonByStudent($lesson, $mailStudent){
            $sql = $this->database->conn->prepare("SELECT AVG(grade) FROM public.grade JOIN public.student USING(student_id) JOIN public.evaluation USING(eval_id) WHERE eval_id = (SELECT eval_id WHERE lesson_id = (SELECT lesson_id FROM public.lesson WHERE subject = :lesson)) AND mail = :mail;");
            $sql->execute;
            $average = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $average;
        }

        public function averageInLesson($lesson){
            $sql = $this->database->conn->prepare("SELECT AVG(grade) FROM public.grade JOIN public.evaluation USING(eval_id) WHERE eval_id = (SELECT eval_id WHERE lesson_id = (SELECT lesson_id FROM public.lesson WHERE subject = :lesson));");
            $sql->execute;
            $average = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $average;
        }

        public function isAverageUnder10($lesson){
            $sql = $this->database->conn->prepare("SELECT COUNT(*) + 1 AS student_rank FROM (SELECT g1.student_id, AVG(g1.grade) AS student_average FROM public.grade g1 JOIN public.evaluation e1 ON g1.eval_id = e1.eval_id WHERE e1.lesson_id = (SELECT lesson_id FROM public.lesson WHERE subject = :lesson_name) GROUP BY g1.student_id) AS t1 WHERE t1.student_average > (SELECT AVG(g2.grade) AS class_average FROM public.grade g2 JOIN public.evaluation e2 ON g2.eval_id = e2.eval_id WHERE e2.lesson_id = (SELECT lesson_id FROM public.lesson WHERE subject = :lesson_name)) AND t1.student_id = :student_id;");
            $sql->execute;
            $average = $sql->fetchAll(PDO::FETCH_ASSOC);
            if($average < 10){
                return true;
            }
            else{
                return false;
            }
        }

        public function rankInLesson($lesson, $mail){
            $sql = $this->database->conn->prepare("SELECT COUNT(*) + 1 AS student_rank FROM (SELECT g1.student_id, AVG(g1.grade) AS student_average FROM public.grade g1 JOIN public.evaluation e1 ON g1.eval_id = e1.eval_id WHERE e1.lesson_id = (SELECT lesson_id FROM public.lesson WHERE subject = :lesson_name) GROUP BY g1.student_id) AS t1 WHERE t1.student_average > (SELECT AVG(g2.grade) AS class_average FROM public.grade g2 JOIN public.evaluation e2 ON g2.eval_id = e2.eval_id WHERE e2.lesson_id = (SELECT lesson_id FROM public.lesson WHERE subject = :lesson_name)) AND t1.student_id = (SELECT student_id FROM public.student WHERE mail = :mail);");
            $sql->bindParam(':lesson_name', $lesson);
            $sql->bindParam(':mail', $mail);
            $sql->execute;
            $rank = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $rank;
        }
        
        public function appreciationInLesson($lesson, $mail){
            $sql = $this->database->conn->prepare("SELECT appreciation FROM public.appreciation WHERE student_id = (SELECT student_id FROM public.student WHERE mail = :mail) AND lesson_id = (SELECT lesson_id FROM public.lesson WHERE subject = :lesson_name);");
            $sql->bindParam(':lesson_name', $lesson);
            $sql->bindParam(':mail', $mail);
            $sql->execute;
            $appreciation = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $appreciation;
        }
        // ADD HERE FUNCTIONS ONLY AN AUTHENTIFIED STUDENT CAN USE    
    }
?>