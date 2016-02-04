<?php
    require 'dbconnect.php';
    $dbq = db_connect();
    $getAssessments = $dbq->query("SELECT configurationID from configuration");
    while ($row = $getAssessments->fetch(PDO::FETCH_ASSOC)) {
        $hash = hash('sha256', $row['configurationID']);
        // $addHash = $dbq->query("UPDATE assessment SET assessmentIDHashed = $hash");
        $addHash = $dbq->prepare("UPDATE configuration SET configurationIDHashed = :hash where configurationID = :configurationID");
        $addHash->bindValue(':hash', $hash, PDO::PARAM_STR);
        $addHash->bindValue(':configurationID', $row['configurationID'], PDO::PARAM_INT);
        $addHash->execute();
    }
?>