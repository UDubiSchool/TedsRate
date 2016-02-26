<?php
    namespace models;
    require_once('model.php');
    use PDO;

    class user_model extends model {
        public function __construct()
        {
            parent::__construct();
        }

        // get a user by their primary key
        public function get()
        {
            $userID = $_POST['id'];

            $getUser = $this->db->prepare("SELECT * FROM user WHERE userID=:id");
            $getUser->bindValue(':id', $userID, PDO::PARAM_INT);
            $getUser->execute();
            if ($this->numRows() == 1) {
                $data['user'] = $getUser->fetch();
            } else {
                $data['user'] = false;
            }

            $getUser->closeCursor();

            $this->echoJSON($data);
        }

        // returns the user with the matching email password combination
        public function validate()
        {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $getUser = $dbq->prepare("SELECT * FROM user u JOIN user_authority ua ON u.userID = ua.userID JOIN authority a ON a.authorityID = ua.authorityID WHERE email=:email AND passwordValue=:password AND a.authorityLevel != 2");
            $getUser->bindValue(':email', $email, PDO::PARAM_STR);
            $getUser->bindValue(':password', $password, PDO::PARAM_STR);
            $getUser->execute();
            if ($this->numRows() == 1) {
                $data['user'] = $getUser->fetch();
            } else {
                $data['user'] = false;
            }
            $getUser->closeCursor();
            $this->echoJSON($data);
        }

        // returns boolean value of if the given email exists in the database
        public function getEmail()
        {
            $email = $_POST['email'];
            $getUser = $dbq->prepare("SELECT * FROM user WHERE email=:email");
            $getUser->bindValue(':email', $email, PDO::PARAM_STR);
            $getUser->execute();
            if ($this->numRows() == 1) {
                $data['exists'] = true;
            } else {
                $data['exists'] = false;
            }
            $getUser->closeCursor();
            $this->echoJSON($data);
        }

        // updates a user by id adding the given email and password
        public function post()
        {
            $userID = intval($_POST['id']);
            $email = $_POST['email'];
            $password = $_POST['password'];
            $updateUser = $this->db->prepare("UPDATE user SET email=:email, passwordValue=:password WHERE userID=:id");
            $updateUser->bindValue(':id', $userID, PDO::PARAM_INT);
            $updateUser->bindValue(':email', $email, PDO::PARAM_STR);
            $updateUser->bindValue(':password', $password, PDO::PARAM_STR);
            $updateUser->execute();
            if ($this->numRows() == 1) {
                $data['user'] = true;
            } else {
                $data['user'] = false;
            }
            $updateUser->closeCursor();
            $this->echoJSON($data);
        }

        // deletes the user with the given id
        public function delete()
        {

            $userID = intval($_POST['userID']);

            $getUser = $dbq->prepare("DELETE FROM user WHERE userID=:userID");
            $getUser->bindValue(':userID', $userID, PDO::PARAM_INT);
            $getUser->execute();
            if ($this->numRows() == 1) {
                $data['deleted'] = true;
            } else {
                $data['deleted'] = false;
            }
            $getUser->closeCursor();
            $this->echoJSON($data);
        }


    }