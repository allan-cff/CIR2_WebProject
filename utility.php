<?php

    // ADD HERE UTILITY CLASSES, USABLE BY EVERYONE REGARDLESS OF PERMISSIONS
    // THESE CLASSES SHOULD NOT SHOW PRIVATE DATA OR MODIFY DATA

    class User {
        public $mail;
        public $name;
        public $surname;
        public $phone;
        private $is_admin;
        public function __construct($dbRow){
            $this->mail = $dbRow['mail'];
            $this->name = $dbRow['name'];
            $this->surname = $dbRow['surname'];
            $this->phone = $dbRow['phone'];
            $this->is_admin = $dbRow['is_admin'] ?? false;
        }
        public function getFullName(){
            return $this->surname . ' ' . $this->name;
        }
        public function isAdmin(){
            return $this->is_admin;
        }
    }

    class Student extends User {
        public $id;
        public $class;
        public function __construct($dbRow){
            parent::__construct($dbRow);
            $studentClass = new SchoolClass($dbRow);
            $this->class = $studentClass;
            $this->id = $dbRow['student_id'];
        }
    }

    class Teacher extends User {
        public function __construct($dbRow){
            parent::__construct($dbRow);
        }
    }

    class SchoolClass {
        public $cycle;
        public $firstYear;
        public $graduationYear;
        public $name;
        public $campus;
        public $id;
        public function __construct($dbRow){
            $this->cycle = $dbRow['cycle'];
            $this->firstYear = $dbRow['first_year'];
            $this->graduationYear = $dbRow['graduation_year'];
            $this->name = $dbRow['class_name'];
            $this->campus = $dbRow['campus_name'];
            $this->id = $dbRow['class_id'];
        }
        public function print(){
            return $this->name . ", " . $this->campus . ", " . $this->cycle . ", " . $this->firstYear . "/" . $this->graduationYear;
        }
        public function getDbRow(){
            return array(              
                "cycle" => $this->cycle,
                "first_year" => $this->firstYear,
                "graduation_year" => $this->graduationYear,
                "campus_name" => $this->campus,
                "class_name" => $this->name,
                "class_id" => $this->id
            );
        }
    }

    class Lesson {
        public $class;
        public $teacher;
        public $subject;
        public $start;
        public $end;
        public $id;
        public function __construct($dbRow){
            $this->class = new SchoolClass($dbRow);
            $this->teacher = new Teacher($dbRow);
            $this->subject = $dbRow["subject"];
            $this->start = $dbRow["date_begin"];
            $this->end = $dbRow["date_end"];
            $this->id = $dbRow["lesson_id"];
        }
    }
?>