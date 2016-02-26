<?php

    function echoJSON ($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data, TRUE);
    }

    function dumpArray($array)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }