<?php
    namespace models;
    require_once "../dbconnect.php";

    class model
    {
        var $db;
        private $post = [];
        private $data = [];

        public function __construct()
        {
            $this->db = db_connect();
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)) {
                $_POST = json_decode(file_get_contents('php://input'), true);
                $this->post = $_POST;
            }
        }

        public function get()
        {

        }

        public function put()
        {

        }

        public function post()
        {

        }

        public function delete()
        {

        }

        // returns the number of rows affected by the last insert/update/delete
        public function numRows()
        {
            $numberOfRows = $this->db->query("SELECT FOUND_ROWS();");
            return $numberOfRows->fetchColumn();
        }

        // echos the data to json
        public function echoJSON($data)
        {
            header('Content-Type: application/json');
            echo json_encode($data, TRUE);
        }
    }