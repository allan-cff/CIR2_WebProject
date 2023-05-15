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
            $sql = $this->database->conn->prepare('SELECT * FROM public.lesson JOIN public.class USING (class_id) JOIN public.cycle USING (cycle_id) JOIN public.campus USING (campus_id) JOIN public.semester USING (semester_id) JOIN public.matter USING(matter_id) JOIN public.user ON teacher = mail JOIN public.student USING (class_id) WHERE student.mail = :mail;');
            $sql->bindParam(':mail', $this->mail);
            $sql->execute();
            $lessonsList = $sql->fetchAll(PDO::FETCH_ASSOC);
            $toLessonClass = function($lessonDbRow){
                return new Lesson($lessonDbRow);
            };       
            return array_map($toLessonClass, $lessonsList);
        }

        public function personalAverageInLesson($subject, $dateBegin){
            $sql = $this->database->conn->prepare("SELECT date_begin, date_end, (SELECT SUM(grade * coeff)/SUM(coeff) FROM public.grade NATURAL JOIN public.student NATURAL JOIN public.evaluation NATURAL JOIN public.lesson NATURAL JOIN public.matter WHERE subject = :subject AND mail = :mail AND public.lesson.semester_id = public.semester.semester_id) AS \"average\" FROM public.semester WHERE date_begin = :dateBegin;");
            $sql->bindParam(':mail', $this->mail);
            $sql->bindParam(':subject', $subject);
            $sql->bindParam(':dateBegin', $dateBegin);
            $sql->execute();
            $average = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $average;
        }

        public function classAverageInLesson($subject, $dateBegin){
            $sql = $this->database->conn->prepare("SELECT date_begin, date_end, (SELECT SUM(grade * coeff)/SUM(coeff) FROM public.grade NATURAL JOIN public.student NATURAL JOIN public.evaluation NATURAL JOIN public.lesson NATURAL JOIN public.matter WHERE subject = :subject AND public.lesson.semester_id = public.semester.semester_id AND class_id = :class_id) AS \"average\" FROM public.semester WHERE date_begin = :dateBegin;");
            $sql->bindParam(':class_id', $this->class->id);
            $sql->bindParam(':subject', $subject);
            $sql->bindParam(':dateBegin', $dateBegin);
            $sql->execute();
            $average = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $average;
        }

        public function rankInLesson($subject, $dateBegin){
            $sql = $this->database->conn->prepare("SELECT date_begin, date_end, (SELECT COUNT(*) + 1 AS \"rank\" FROM public.student s WHERE (SELECT AVG(grade) FROM public.grade g NATURAL JOIN public.evaluation NATURAL JOIN public.lesson NATURAL JOIN public.matter WHERE subject = :subject AND s.mail = g.mail AND public.lesson.semester_id = public.semester.semester_id) > (SELECT AVG(grade) FROM public.grade g JOIN public.evaluation USING(eval_id) JOIN public.lesson USING(lesson_id) JOIN public.matter USING(matter_id) WHERE subject = :subject AND public.lesson.semester_id = public.semester.semester_id AND g.mail = :mail)) FROM public.semester WHERE date_begin = :dateBegin;");
            $sql->bindParam(':subject', $subject);
            $sql->bindParam(':mail', $this->mail);
            $sql->bindParam(':dateBegin', $dateBegin);
            $sql->execute();
            $rank = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $rank;
        }
        public function listSemesters(){
            $sql = $this->database->conn->prepare('SELECT * FROM public.semester;');
            $sql->execute();
            return $semestersList = $sql->fetchAll(PDO::FETCH_ASSOC);
        }        // ADD HERE FUNCTIONS ONLY AN AUTHENTIFIED STUDENT CAN USE    
    }
?>