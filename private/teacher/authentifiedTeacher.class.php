<?php
    require_once(realpath(dirname(__FILE__) . '/../../utility.php'));
    require_once(realpath(dirname(__FILE__) . '/../../database.class.php'));
    //Weird require as this file will be included and change location

    // THIS CLASS IS USABLE ONLY BY AN AUTHENTIFIED TEACHER
    // IT SHOULD BE USED TO PROVIDE ALL FUNCTIONS TO ACCESS DATABASE AS A TEACHER

    class AuthentifiedTeacher extends Teacher {
        private $database;
        public function __construct($db, $dbRow){
            parent::__construct($dbRow);
            $this->database = $db;
        }
        public function connect(){
            return $this->database->connect();
        }
        public function addGrade($mailStudent, $lesson, $evalDate, $grade){
            $gradeInsert = $this->database->conn->prepare("INSERT INTO public.grade (student_id, grade, eval_id) VALUES((SELECT student_id FROM public.student WHERE mail = :mailStudent), :grade, (SELECT eval_id FROM public.evaluation WHERE begin_datetime = :startDate AND lesson_id = (SELECT lesson_id FROM public.lesson WHERE subject = :subject AND teacher = :teacherMail AND class_id = (SELECT class_id FROM public.class WHERE class_name = :className AND study_year = :studyYear AND cycle_id = (SELECT cycle_id FROM public.cycle WHERE cycle = :cycle) AND campus_id = (SELECT campus_id FROM public.campus WHERE campus_name = :campus))) LIMIT 1));");
            $gradeInsert->bindParam(':mailStudent', $mailStudent);
            $gradeInsert->bindParam(':grade', $grade);
            $gradeInsert->bindParam(':startDate', $evalDate);
            $gradeInsert->bindParam(':subject', $lesson->subject);
            $gradeInsert->bindParam(':teacherMail', $lesson->teacher->mail);
            $gradeInsert->bindParam(':className', $lesson->class->name);
            $gradeInsert->bindParam(':studyYear', $lesson->class->studyYear);
            $gradeInsert->bindParam(':cycle', $lesson->class->cycle);
            $gradeInsert->bindParam(':campus', $lesson->class->campus);
            $gradeInsert->execute();
            return $gradeInsert->rowCount() === 1;
        }
        public function listLessons(){
            $sql = $this->database->conn->prepare("SELECT lesson_id, (SELECT AVG(grade) FROM public.grade WHERE eval_id = public.evaluation.eval_id) AS average, (SELECT COUNT(grade) FROM public.grade WHERE eval_id = public.evaluation.eval_id) AS not_null, coeff, begin_datetime, subject, class_name, cycle, study_year, campus_name, (SELECT COUNT(*) FROM public.student WHERE class_id = public.class.class_id) AS \"student_count\" FROM public.evaluation JOIN public.lesson USING (lesson_id) JOIN public.class USING (class_id) JOIN public.cycle USING (cycle_id) JOIN public.campus USING (campus_id) WHERE teacher = :teacherMail;");
            $sql->bindParam(':teacherMail', $this->mail);
            $sql->execute();
            $lessonsList = $sql->fetchAll(PDO::FETCH_ASSOC);
            $result = array();
            foreach($lessonsList as $value){
                $lessonArray = array_intersect_key($value, array(
                    "average" => null,
                    "not_null" => null,
                    "coeff" => null, 
                    "begin_datetime" => null
                ));
                if(!isset($result[$value["lesson_id"]])){
                    $result[$value["lesson_id"]] = array_intersect_key($value, array(
                        "subject" => null,
                        "class_name" => null, 
                        "cycle" => null, 
                        "study_year" => null, 
                        "campus_name" => null,
                        "student_count" => null
                    ));
                    $result[$value["lesson_id"]]["evaluations"] = array();
                }
                array_push($result[$value["lesson_id"]]["evaluations"], $lessonArray);
            }
            return $result;
        }

        public function listSemesters(){
            $sql = $this->database->conn->prepare('SELECT * FROM public.semester;');
            $sql->execute();
            return $semestersList = $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        public function addAppreciation($mailStudent, $beginDate, $appreciation){
            $sql = $this->database->conn->prepare('INSERT INTO public.appreciation (appraisal, semester_id, student_id) VALUES (:appreciation, (SELECT semester_id FROM public.semester WHERE date_begin = :dateBegin), (SELECT student_id FROM public.student where mail = :mailStudent));');
            $sql->bindParam(':mailStudent', $mailStudent);
            $sql->bindParam(':dateBegin', $beginDate);
            $sql->bindParam(':appreciation', $appreciation);
            $sql->execute();
            return $sql->rowCount() === 1;
        }
        // ADD HERE FUNCTIONS ONLY AN AUTHENTIFIED STUDENT CAN USE  
        // TODO : check if student is in class for grade addition and listing
    }      
?>