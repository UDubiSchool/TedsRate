<?php
    require_once "dbconnect.php";
    echo "test";

    try {

        $dbq = db_connect();
        echo "Connected successfully";

    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

?>