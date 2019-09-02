<?php

Abstract class DbModel {

     private static $db_host = '';
     private static $db_user = '';
     private static $db_pass = '';
     protected $db_name = '';
     protected $query;
     protected $rows = array();
     private $conn;
   
  

     /**
      * Connect to the database
      */
    private function open_connection() {
       $this->conn = new mysqli(self::$db_host, self::$db_user,
       self::$db_pass, $this->db_name);
    }

      /**
        * Disconnect the database
      */
      private function close_connection() {
        $this->conn->close();
      }


      /**
         * Execute a simple query of type INSERT, DELETE, UPDATE
       */
      protected function execute_single_query() {
       $this->open_connection();
       $this->conn->query($this->query);
       $this->close_connection();
      }


     /**
      * Returns the results of a query in an Array
      */
      protected function get_results_from_query() {
         $this->open_connection();
         $result = $this->conn->query($this->query);
         while ($this->rows[] = $result->fetch_assoc());
         $result->close();
         $this->close_connection();
         array_pop($this->rows);
       }


 }






?>
