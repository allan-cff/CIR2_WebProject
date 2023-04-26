<?php
    require_once 'utility.php';

    // THIS CLASS IS USABLE ONLY BY AN AUTHENTIFIED TEACHER
    // IT SHOULD BE USED TO PROVIDE ALL FUNCTIONS TO ACCESS DATABASE AS A TEACHER

    class AuthentifiedTeacher extends Teacher {
        private $conn;
        public function __construct($conn, $dbRow){
            parent::__construct($dbRow);
            $this->conn = $conn;
        } 
        // ADD HERE FUNCTIONS ONLY AN AUTHENTIFIED NOT ADMINISTRATOR TEACHER CAN USE    
    }
?>