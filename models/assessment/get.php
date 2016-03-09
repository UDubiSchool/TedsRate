<?php

    if(!isset($_POST['asid'])) {
        exit;
    }

    $hashedID = $_POST['asid'];


    $authenticate_query = $dbq->prepare("SELECT * FROM assessment
                            INNER JOIN configuration ON configuration.configurationID = assessment.configurationID
                            INNER JOIN assessmentConfiguration ON assessmentConfiguration.assessmentConfigurationID = configuration.assessmentConfigurationID
                            INNER JOIN questionConfiguration ON questionConfiguration.questionConfigurationID = configuration.questionConfigurationID
                            INNER JOIN uiConfiguration ON uiConfiguration.uiConfigurationID = configuration.uiConfigurationID
                            INNER JOIN user ON user.userID = assessment.userID
                            WHERE assessment.assessmentIDHashed = :hashedID");
    $authenticate_query->bindValue(':hashedID', $hashedID, PDO::PARAM_STR);
    $authenticate_query->execute();


    $flag = $authenticate_query->fetchAll();
    // print_r($hashedID);
    // exit;
    $pid = $flag[0]['projectID'];
    $aid = $flag[0]['artifactID'];
    $lanID = $flag[0]['languageID'];
    $personaID = $flag[0]['personaID'];
    $roleID = $flag[0]['roleID'];
    $scenarioID = $flag[0]['scenarioID'];
    $assessmentID = $flag[0]['assessmentID'];

    $questionConfigurationID = $flag[0]['questionConfigurationID'];
    $configurationID = $flag[0]['configurationID'];
    $configurationIDHashed = $flag[0]['configurationIDHashed'];
    $uiConfigurationID = $flag[0]['uiConfigurationID'];

    $fName = $flag[0]['firstName'];
    $lName = $flag[0]['lastName'];
    $userID = $flag[0]['userID'];




    // available variables
    // $uid = $flag[0]['userID'];
    // $assessmentID = $flag[0]['assessmentID'];
    $data = [
        'assessmentID' => $assessmentID,
        'configurationID' => $configurationID,
        'configurationIDHashed' => $configurationIDHashed,
        'user' => [],
        'configuration' => [],
        'questions' => []
    ];


    //populate user
    $sth = $dbq->prepare("CALL getUser(:userID, @rowCount)");
    $sth->bindValue(':userID', $userID, PDO::PARAM_INT);
    $sth->execute();
    while ($row = $sth->fetch()){
    $data['user'] = $row;
    };
    $sth->closeCursor();



    //populate site (artifact) title in view toggle
    $sth = $dbq->prepare('CALL getArtifact('.$aid.',@title,@url,@desc,@type)');
    $sth->execute();
    while ($row = $sth->fetch()){
    $tmp = [
        'name' => $row['name'],
        'description' => $row['description'],
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
            'name' => $row['personaName'],
            'description' => $row['personaDesc']
        ];
        $data['persona'] = $tmp;
    }
    $sth->closeCursor();

    //populate role
    $sth = $dbq->query('SELECT * FROM role where role.roleID = ' . $roleID);
    while ($row = $sth->fetch()){
        $tmp = [
            'name' => $row['roleName'],
            'description' => $row['roleDesc']
        ];
        $data['role'] = $tmp;
    }
    $sth->closeCursor();


    //populate scenarios
    $sth = $dbq->query('SELECT * FROM scenario where scenario.scenarioID = ' . $scenarioID);
    while ($row = $sth->fetch()){
        $tmp = [
            'name' => $row['scenarioName'],
            'description' => $row['scenarioDescription']
        ];
        $data['scenario'] = $tmp;
    }
    $sth->closeCursor();
    echo $scenarioID
    // header('Content-Type: application/json');
    echo json_encode($data, TRUE);
    exit;

    //populate uiConfig
    $sth = $dbq->query('SELECT * FROM uiConfiguration where uiConfigurationID = ' . $uiConfigurationID);
    while ($row = $sth->fetch()){
        $data['configuration']['uiConfiguration'] = $row;
    }
    $sth->closeCursor();



    // get the question types
    $questions = [];
    $sth = $dbq->query("SELECT q.questionID, q.questionName, q.questionDesc, q.questionData, q.questionRequired, qt.questionTypeName, qp.projectID, qs.scenarioID, qart.artifactID, qper.personaID, qrol.roleID
                                    FROM question q
                                    INNER JOIN question_questionConfiguration qqc ON qqc.questionID = q.questionID
                                    INNER JOIN questionConfiguration qc ON qc.questionConfigurationID = qqc.questionConfigurationID
                                    AND qc.questionConfigurationID = $questionConfigurationID
                                    INNER JOIN questionType qt ON qt.questionTypeID = q.questionTypeID
                                    LEFT JOIN question_project qp ON q.questionID = qp.questionID
                                    AND qp.projectID = $pid
                                    LEFT JOIN question_scenario qs ON q.questionID = qs.questionID
                                    AND qs.scenarioID = $scenarioID
                                    LEFT JOIN question_attribute qatt ON q.questionID = qatt.questionID
                                    LEFT JOIN question_artifact qart ON q.questionID = qart.questionID
                                    AND qart.artifactID = $aid
                                    LEFT JOIN question_persona qper ON q.questionID = qper.questionID
                                    AND qper.personaID = $personaID
                                    LEFT JOIN question_role qrol ON q.questionID = qrol.questionID
                                    AND qrol.roleID = $roleID
                                    ORDER BY qt.questionTypeID ASC, q.questionID ASC
                                    ");
    while ($row = $sth->fetch()){
        if(!array_key_exists(strtolower($row['questionTypeName']), $questions)) {
            $questions[strtolower($row['questionTypeName'])] = [];
        }
        $inner = $dbq->query("SELECT r.responseAnswer, r.responseID, a.assessmentID
                                FROM question q
                                INNER JOIN response r ON q.questionID = r.questionID
                                INNER JOIN assessment a ON r.assessmentID = a.assessmentID
                                INNER JOIN user u ON a.userID = u.userID
                                WHERE u.userID = $userID
                                AND q.questionID = $row[questionID]
                                ");
        $inner = $inner->fetch();
        $row['responseID'] = $inner['responseID'];
        $row['responseAnswer'] = $inner['responseAnswer'];
        $row['assessmentID'] = $inner['assessmentID'];
        array_push($questions[strtolower($row['questionTypeName'])], $row);
    }
    $sth->closeCursor();



    foreach ($questions as &$cat) {
        foreach ($cat as &$question) {
            $question['responseID'] =  intval($question['responseID']);
            $question['response'] = $question['responseAnswer'];
            $question['questionRequired'] = intval($question['questionRequired']);
        }
    }


    $data['questions'] = $questions;

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
    LEFT JOIN screenshot ON rating.ratingID = screenshot.ratingID
    LEFT JOIN comment ON rating.ratingID = comment.ratingID
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

                        $ratingValue = strval(intval($value['ratingValue']));

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
                'attributeDesc' => json_encode($row['attributeDesc']),
                'attributeLaymanDesc' => json_encode($row['attributeLaymanDesc']),
                'attributeTypeName' => $row['attributeTypeName'],
                'attributeTypeDesc' => $row['attributeTypeDesc'],
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

    unset($data['ratingsData']);
    $sth->closeCursor();

    foreach ($criteria as $criterionKey => $criterion) {
        foreach ($criterion['attributes'] as $attributeKey => $attribute) {
            if($attribute['attributeTypeName'] == 'Cluster') {
                $criteria[$criterionKey]['attributes'][$attributeKey]['categories'] = [];

                $sth = $dbq->query("SELECT attr.attributeID, attr.attributeName, attr.attributeDesc, attr.attributeLaymanDesc
                                                FROM cluster cl
                                                INNER JOIN cluster_category cc ON cc.clusterID = cl.attributeID
                                                INNER JOIN category ca ON cc.categoryID = ca.attributeID
                                                INNER JOIN attribute attr ON attr.attributeID = ca.attributeID
                                                WHERE cl.attributeID = $attribute[attributeID]
                                                ");
                while ($row = $sth->fetch()){
                    $tmp = [
                        'attributeID' => $row['attributeID'],
                        'attributeName' => $row['attributeName'],
                        'attributeDesc' => json_encode($row['attributeDesc']),
                        'attributeLaymanDesc' => json_encode($row['attributeLaymanDesc']),
                    ];
                    array_push($criteria[$criterionKey]['attributes'][$attributeKey]['categories'], $tmp);
                }
                $sth->closeCursor();
            }
        }
    }
    $data['criteria'] = $criteria;

    header('Content-Type: application/json');
    echo json_encode($data, TRUE);


?>