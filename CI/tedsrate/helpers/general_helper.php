<?php

    function echoJSON ($data, $parseFunctions = false)
    {
        header('Content-Type: application/json');
        $payload = json_encode($data, TRUE);
        if($parseFunctions) {
            $payload = str_replace('"%%', "", $payload);
            $payload = str_replace('%%"', "", $payload);
        }
        echo $payload;
    }

    function dumpArray($array)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }