<?php
    require_once "../dbconnect.php";

    try {
        $dbq = db_connect();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST))
            $_POST = json_decode(file_get_contents('php://input'), true);

        if(!isset($_POST['asid'])) {
            exit;
        }

        $hashedID = $_POST['asid'];


        $authenticate_query = $dbq->prepare("SELECT * FROM assessment
                                LEFT JOIN configuration ON configuration.configurationID = assessment.configurationID
                                LEFT JOIN assessmentConfiguration ON assessmentConfiguration.assessmentConfigurationID = configuration.assessmentConfigurationID
                                LEFT JOIN questionConfiguration ON questionConfiguration.questionConfigurationID = configuration.questionConfigurationID
                                LEFT JOIN uiConfiguration ON uiConfiguration.uiConfigurationID = configuration.uiConfigurationID
                                LEFT JOIN user ON user.userID = assessment.userID
                                WHERE assessment.assessmentIDHashed = :hashedID");
        $authenticate_query->bindValue(':hashedID', $hashedID, PDO::PARAM_STR);
        $authenticate_query->execute();

        $flag = $authenticate_query->fetchAll();;
        $pid = $flag[0]['projectID'];
        $aid = $flag[0]['artifactID'];
        $lanID = $flag[0]['languageID'];
        $personaID = $flag[0]['personaID'];
        $scenarioID = $flag[0]['scenarioID'];
        $assessmentID = $flag[0]['assessmentID'];

        $questionConfigurationID = $flag[0]['questionConfigurationID'];
        $configurationID = $flag[0]['configurationID'];
        $uiConfigurationID = $flag[0]['uiConfigurationID'];

        $fName = $flag[0]['firstName'];
        $lName = $flag[0]['lastName'];
        $userID = $flag[0]['userID'];




        // available variables
        $uid = $flag[0]['userID'];
        $assessmentID = $flag[0]['assessmentID'];
        $data = [
            'assessmentID' => $assessmentID,
            'configuration' => [],
            'questions' => []
        ];

        //populate user
        $sth = $dbq->prepare("CALL getUser(:userID, @rowCount)");
        $sth->bindValue(':userID', $userID, PDO::PARAM_INT);
        $sth->execute();
        while ($row = $sth->fetch()){
        // $data['user'] = $row;
        };
        $sth->closeCursor();



        //populate site (artifact) title in view toggle
        $sth = $dbq->prepare('CALL getArtifact('.$aid.',@title,@url,@desc,@type)');
        $sth->execute();
        while ($row = $sth->fetch()){
        $tmp = [
            'name' => $row['name'],
            'url' => $row['URL']
        ];
        $data['artifact'] = $tmp;
        };
        $sth->closeCursor();


        //populate project title and description
        $sth = $dbq->query('CALL getProject('.$pid.',@title,@desc)');
        while ($row = $sth->fetch()){
         $tmp = [
             'name' => $row['name'],
             'description' => $row['description']
         ];
         $data['project'] = $tmp;
        }
        $sth->closeCursor();


        //populate personas the "language" value (5) is hard coded!
        $sth = $dbq->query('SELECT * FROM persona where persona.personaID = ' . $personaID);
        while ($row = $sth->fetch()){
            $tmp = [
                'personaName' => $row['personaName'],
            ];
            $data['persona'] = $tmp;
        }
        $sth->closeCursor();


        //populate scenarios the "language" value (5) is hard coded!
        $sth = $dbq->query('SELECT * FROM scenario where scenario.scenarioID = ' . $scenarioID);
        while ($row = $sth->fetch()){
            $tmp = [
                'scenarioName' => $row['scenarioName'],
            ];
            $data['scenario'] = $tmp;
        }
        $sth->closeCursor();

        //populate uiConfig
        $sth = $dbq->query('SELECT * FROM uiConfiguration where uiConfigurationID = ' . $uiConfigurationID);
        while ($row = $sth->fetch()){
            $data['configuration']['uiConfiguration'] = $row;
        }
        $sth->closeCursor();


        // get the question types
        $sth = $dbq->query("SELECT * FROM question q
                                        INNER JOIN question_questionConfiguration qqc ON qqc.questionID = q.questionID
                                        INNER JOIN questionConfiguration qc ON qc.questionConfigurationID = qqc.questionConfigurationID
                                        INNER JOIN questionType qt ON qt.questionTypeID = q.questionTypeID
                                        LEFT JOIN question_project qp ON qp.questionID = q.questionID
                                        LEFT JOIN question_scenario qs ON qs.questionID = q.questionID
                                        LEFT JOIN question_attribute qatt ON qatt.questionID = q.questionID
                                        LEFT JOIN question_artifact qart ON qart.questionID = q.questionID
                                        WHERE qc.questionConfigurationID = $questionConfigurationID
                                        AND (qp.projectID = $pid
                                        OR qs.scenarioID = $scenarioID
                                        OR qart.artifactID = $aid)
                                        ");
        while ($row = $sth->fetch()){
            array_push($data['questions'], $row);
        }
        $sth->closeCursor();


        if($questionConfigurationID == 2) {
            $questions = [
             'demographic' => [],
             'project' =>[],
             'artifact' =>[],
             'scenario' => [],
             'attribute' => []
             ];

             // get the question types
             $sth = $dbq->query("SELECT * FROM question q
                                             INNER JOIN questionType qt ON qt.questionTypeID = q.questionTypeID
                                             WHERE qt.questionTypeName = 'Demographic'
                                             ");
             while ($row = $sth->fetch()){
                 if($row['projectID']) {
                     $questions['project'][intval($row['questionID'])] = $row;
                 }
                 $questions[intval($row['questionID'])] = $row;
             }
             $sth->closeCursor();

            // get the question types
            $sth = $dbq->query("SELECT * FROM question q
                                            INNER JOIN questionType qt ON qt.questionTypeID = q.questionTypeID
                                            INNER JOIN question_project qp ON qp.questionID = q.questionID
                                            WHERE qp.projectID = $pid
                                            ");
            while ($row = $sth->fetch()){
                if($row['projectID']) {
                    $questions['project'][intval($row['questionID'])] = $row;
                }
                $questions[intval($row['questionID'])] = $row;
            }
            $sth->closeCursor();

            $sth = $dbq->query("SELECT * FROM question q
                                            INNER JOIN questionType qt ON qt.questionTypeID = q.questionTypeID
                                            INNER JOIN question_scenario qs ON qs.questionID = q.questionID
                                            WHERE qs.scenarioID = $scenarioID
                                            ");
            while ($row = $sth->fetch()){
                if($row['scenarioID']) {
                    $questions['scenario'][intval($row['questionID'])] = $row;
                }
                $questions[intval($row['questionID'])] = $row;
            }
            $sth->closeCursor();
            $data['questions'] = $questions;

            $sth = $dbq->query("SELECT * FROM question q
                                            INNER JOIN questionType qt ON qt.questionTypeID = q.questionTypeID
                                            INNER JOIN question_artifact qart ON qart.questionID = q.questionID
                                            WHERE qart.artifactID = $aid
                                            ");
            while ($row = $sth->fetch()){
                if($row['artifactID']) {
                    $questions['artifact'][intval($row['questionID'])] = $row;
                }
                $questions[intval($row['questionID'])] = $row;
            }
            $sth->closeCursor();

            $sth = $dbq->query("SELECT * FROM question q
                                            INNER JOIN questionType qt ON qt.questionTypeID = q.questionTypeID
                                            INNER JOIN question_attribute quat ON quat.questionID = q.questionID
                                            ");
            while ($row = $sth->fetch()){
                if($row['artifactID']) {
                    $questions['artifact'][intval($row['questionID'])] = $row;
                }
                $questions[intval($row['questionID'])] = $row;
            }
            $sth->closeCursor();


        } //end add questions

        //populate categories the "language" value (1) is hard coded!
        // echo "started retieval";
        $ratingData = [];
        $current = "SELECT
        rating.ratingID,
        rating.ratingValue,
        attribute.attributeID,
        attribute.attributeName,
        screenshot.screenshotPath,
        screenshot.screenshotDesc,
        comment.comment
        FROM assessment
        LEFT JOIN rating ON assessment.assessmentID = rating.assessmentID
        LEFT JOIN attribute ON attribute.attributeID = rating.attributeID
        LEFT JOIN rating_screenshot ON rating.ratingID = rating_screenshot.ratingID
        LEFT JOIN screenshot ON rating_screenshot.screenshotID = screenshot.screenshotID
        LEFT JOIN rating_comment ON rating.ratingID = rating_comment.ratingID
        LEFT JOIN comment ON rating_comment.commentID = comment.commentID
        WHERE assessment.assessmentID = $assessmentID;";
        $current = $dbq->query($current);
        while ($currentResult = $current->fetch()){
            array_push($ratingData, $currentResult);
        }
        array_reverse($ratingData);
        $data['ratingsData']= $ratingData;

        // gets categories and attaches ratings comments and files
        $criteria = [];
        $sth = $dbq->query('CALL getCriteria(5,@cid,@ctitle,@cdesc)');
        while ($prow = $sth->fetch()){
            $criterion = [
                'criterionName' => $prow['criterionName'],
                'criterionID' => $prow['criterionID'],
                'criterionDesc' => $prow['criterionDesc']
            ];
            $attributes = [];

            //prints out the ratings attributes and if either the session data is filled or the rating was previously submitted and $ratingsData is populated then fill out with those values. If neither is specified then it will just print the blank fields.
            foreach($dbq->query("CALL getConfigurationCriterionAttributes($assessmentID, $prow[criterionID])") as $row) {
                $ratingValue = '';
                $hasScreenshot = false;
                $hasComment = false;
                $screenshots = [];
                $comment = '';


                if (!empty($data['ratingsData'])) {
                    // echo "not empty!";
                    foreach ($data['ratingsData'] as $key => $value) {

                        if ($value['attributeID'] == $row['attributeID']) {

                            $ratingValue = intval($value['ratingValue']);

                            if(isset($value['screenshotPath'])) {
                                $hasScreenshot = true;
                                if(!in_array($value['screenshotPath'], $screenshots)) {
                                    array_push($screenshots, $value['screenshotPath']);
                                }
                            }
                            // echo $value['comment'];
                            if(isset($value['comment'])) {
                                $hasComment = true;
                                $comment = $value['comment'];
                            }
                        }
                    }
                    array_reverse($screenshots);
                }

                $attribute = [
                    'attributeID' => $row['attributeID'],
                    'attributeName' => $row['attributeName'],
                    'attributeDesc' => $row['attributeDesc'],
                    'ratingValue' => $ratingValue,
                    'preface' => $row['attributePreface'],
                    'postface' => $row['attributePostface'],
                    'hasScreenshot' => $hasScreenshot,
                    'hasComment' => $hasComment,
                    'screenshots' => $screenshots,
                    'comment' => $comment
                ];

                array_push($attributes, $attribute);
            }
            array_reverse($attributes);
            $criterion['attributes'] = $attributes;
            array_push($criteria, $criterion);

        }
        array_reverse($criteria);
        $data['criteria'] = $criteria;
        $sth->closeCursor();

        unset($data['ratingsData']);


        header('Content-Type: application/json');
        echo json_encode($data, TRUE);
        //close connection
        $dbq = NULL;

    } catch (PDOException $e) {
         print ("getMessage(): " . $e->getMessage () . "\n");
    }

    // checks to see if the user exists in user entity
    function isValidUser($dbq, $userID) {
        $makePartialUser = $dbq->prepare("CALL getUser(:userID, @rowCount)");
        $makePartialUser->bindValue(':userID', $userID, PDO::PARAM_INT);
        $makePartialUser->execute();
        $makePartialUser->closeCursor();

        $rowCount = $dbq->query('SELECT @rowCount')->fetchColumn();
        if($rowCount = 1) {
            return true;
        } else {
            return false;
        }
    }

?>