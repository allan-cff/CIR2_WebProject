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

        public function averageInLesson($lesson, $mailStudent){
            $sql = $this->database->conn->prepare('SELECT * FROM public.grade')
        }
        // ADD HERE FUNCTIONS ONLY AN AUTHENTIFIED STUDENT CAN USE    
    }
?>