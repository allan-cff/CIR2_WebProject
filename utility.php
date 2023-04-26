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
            $this->is_admin = $dbRow['is_admin'];
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
            //$studentClass = new SchoolClass($dbRow);
            //$this->class = $studentClass;
            //$this->id = $dbRow['student_id'];
        }
    }

    class Teacher extends User {
        public function __construct($dbRow){
            parent::__construct($dbRow);
        }
    }

    class SchoolClass {
        public $id;
        public $cycle;
        public $study_year;
        public $name;
        public $campus;
        public function __construct($dbRow){
            $this->id = $dbRow['class_id'];
            $this->cycle = $dbRow['cycle'];
            $this->study_year = $dbRow['study_year'];
            $this->name = $dbRow['class_name'];
            $this->campus = $dbRow['campus_name'];
        } 
    }
?>