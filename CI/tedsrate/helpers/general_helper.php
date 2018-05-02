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

    function echoCSV ($data, $fileName) {
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=$fileName.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        $toEcho = "";
        foreach ($data as $key => $row) {
            array_walk($row, function(&$str) {
                if(is_string($str)){
                    $str = "\"$str\"";
                }
            });
            $toEcho .= implode(", ", $row);
            $toEcho .= "\n";
        }
        echo $toEcho;

    }

    function dumpArray($array)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }