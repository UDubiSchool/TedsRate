<?php
    require_once "classes/project_model.php";
    use models\project_model;
    require_once "../dbconnect.php";

    try {
        // $dbq = db_connect();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
            $_POST = json_decode(file_get_contents('php://input'), true);

        if(!isset($_GET['f'])) {
            exit;
        }
        $function = $_GET['f'];
        // $toInclude = "user/$function.php";
        $possibleFunctions = ['get', 'getAll', 'getAssoc', 'getAllAssoc', 'delete', 'post', 'put'];

        if(in_array($function, $possibleFunctions)) {
            $model = new project_model();
            $model->$function();
        } else {
            exit;
        }

        //close connection
        // $dbq = NULL;

    } catch (PDOException $e) {
         print ("getMessage(): " . $e->getMessage () . "\n");
    }

?>