<?php
    require_once 'utility.php';

    // THIS CLASS IS USABLE ONLY BY AN AUTHENTIFIED STUDENT
    // IT SHOULD BE USED TO PROVIDE ALL FUNCTIONS TO ACCESS DATABASE AS A STUDENT

    class AuthentifiedStudent extends Student {
        private $conn;
        public function __construct($conn, $dbRow){
            parent::__construct($dbRow);
            $this->conn = $conn;

        // ADD HERE FUNCTIONS ONLY AN AUTHENTIFIED STUDENT CAN USE    
        } 
    }
?>