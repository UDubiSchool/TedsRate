<?php

    require 'dbconnect.php';
    $dbq = db_connect();
    try {
        $getAssessments = $dbq->query("SELECT * from assessment");
        while ($row = $getAssessments->fetch(PDO::FETCH_ASSOC)) {
            $personaID = $row['personaID'];
            $scenarioID = $row['scenarioID'];
            $projectID = '';
            $artifactID = '';
            $attributeConfigurationID = $row['configurationID'];

            $getProjectArtifact = $dbq->query("SELECT * FROM projectArtifact WHERE projectArtifactID = $row[projectArtifactID]");
            while ($paRow = $getProjectArtifact->fetch(PDO::FETCH_ASSOC)) {
                $projectID = $paRow['projectID'];
                $artifactID = $paRow['artifactID'];
            }

            $addAssessmentConfiguration = $dbq->prepare("CALL addAssessmentConfiguration(:projectID, :artifactID, :scenarioID, :personaID, @assessmentConfID)");
            $addAssessmentConfiguration->bindValue(':projectID', $projectID, PDO::PARAM_INT);
            $addAssessmentConfiguration->bindValue(':artifactID', $artifactID, PDO::PARAM_INT);
            $addAssessmentConfiguration->bindValue(':scenarioID', $scenarioID, PDO::PARAM_INT);
            $addAssessmentConfiguration->bindValue(':personaID', $personaID, PDO::PARAM_INT);
            $addAssessmentConfiguration->execute();
            $addAssessmentConfiguration->closeCursor();

            $assessmentConfigurationID = $dbq->query('SELECT @assessmentConfID')->fetchColumn();
            // echo "assessmentConfID: $assessmentConfigurationID  ";

            $addConfiguration = $dbq->prepare("CALL addConfiguration(:atrConfID, :assConfID, @confID)");
            $addConfiguration->bindValue(':atrConfID', $attributeConfigurationID, PDO::PARAM_INT);
            $addConfiguration->bindValue(':assConfID', $assessmentConfigurationID, PDO::PARAM_INT);
            $addConfiguration->execute();
            $addConfiguration->closeCursor();

            $configurationID = $dbq->query('SELECT @confID')->fetchColumn();
            // echo "confID: $configurationID  ";
            $assessmentID = $row['assessmentID'];
            // echo "assessmentID: $assessmentID  ";


            $updateAssessment = $dbq->query("UPDATE `assessment` SET `configurationID` = $configurationID WHERE `assessmentID` = $assessmentID");
            // $updateAssessment->closeCursor();

        }
    } catch (PDOException $e) {
        echo $e;
    }


?>