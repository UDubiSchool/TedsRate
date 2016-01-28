<?php
// ============================== authentication ===============================
require_once "session_inc.php";
// ============================== authentication ===============================

require_once "dbconnect.php";

if ($_POST) {
    $source        = $_POST['source']; // source param
    $authenticated = false;
    $userID = '';
    try {
        $dbq = db_connect();

        switch ($source) {
            case 'project':
                //try to insert the project into mysql database and get the new added project id
                //prepare PDO statement, addProject SPROC
                $projectName       = $_POST['projectName'];
                $projectDescription = $_POST['projectDesc'];
                $projectLanguageID  = $_POST['projectLang'];
                $stmt               = $dbq->prepare("CALL addProject(:pname,:pdescript,:pLan,@nid)");
                $stmt->bindValue(':pname', $projectName, PDO::PARAM_STR);
                $stmt->bindValue(':pdescript', $projectDescription, PDO::PARAM_STR);
                $stmt->bindValue(':pLan', $projectLanguageID, PDO::PARAM_INT);
                $stmt->execute();
                //debug statements
                $projectID = $dbq->query('SELECT @nid')->fetchColumn();
                break;

            case 'scenario':
                //insert scenarios
                $scenarioName = $_POST['scenarioName'];
                $scenarioDesc  = $_POST['scenarioDesc'];
                $scenarioIDs   = array();
                for ($i = 0; $i < count($scenarioName); $i++) {
                    //prepare PDO statement, addArtifact SPROC
                    $stmt = $dbq->prepare("CALL addScenario(:scenarioName,:description,:languageID,@nid)");
                    $stmt->bindValue(':scenarioName', $scenarioName[$i], PDO::PARAM_STR);
                    $stmt->bindValue(':description', $scenarioDesc[$i], PDO::PARAM_STR);
                    $stmt->bindValue(':languageID', 5, PDO::PARAM_INT);
                    $stmt->execute();
                    $scenarioID = $dbq->query('SELECT @nid')->fetchColumn();
                    array_push($scenarioIDs, $scenarioID);
                }
                //update scenarioCategory
                $sql["attributeID"] = 'SELECT * from attribute';
                foreach ($dbq->query($sql["attributeID"]) as $aID) {

                    for ($i = 0; $i < count($scenarioIDs); $i++) {
                        $sql["sceCate"] = 'INSERT INTO scenarioAttribute (scenarioID, attributeID) VALUES (' . $scenarioIDs[$i] . ', ' . $aID['attributeID'] . ')';
                        $dbq->query($sql["sceCate"]);
                    }

                }
                break;

            case 'atft':
                //try to insert artifact into database
                $artifactName    = $_POST['artifactName'];
                $artifactURL      = $_POST['artifactURL'];
                $artifactTypeID   = 4;
                $artifactLanguage = 5;
                $artifactIDs      = array();
                for ($i = 0; $i < count($artifactName); $i++) {
                    //prepare PDO statement, addArtifact SPROC
                    $stmt = $dbq->prepare("CALL addArtifact(:artifactName,:aurl,:typeID,:Lan,@nid)");
                    $stmt->bindValue(':artifactName', $artifactName[$i], PDO::PARAM_STR);
                    $url = $artifactURL[$i];
                    if (!preg_match('/^http\S+/i', $url)) {
                        $url = "http://{$url}";
                    }
                    $url = urlencode($url);
                    $stmt->bindValue(':aurl', $url, PDO::PARAM_STR);
                    $stmt->bindValue(':typeID', $artifactTypeID, PDO::PARAM_INT);
                    $stmt->bindValue(':Lan', $artifactLanguage, PDO::PARAM_INT);
                    $stmt->execute();

                    $artifactID = $dbq->query('SELECT @nid')->fetchColumn();
                    array_push($artifactIDs, $artifactID);
                }

                //update projectArtifact
                $paids = array();
                for ($i = 0; $i < count($artifactIDs); $i++) {
                    $artifactID = $artifactIDs[$i];
                    $projectID  = $_POST['projectID'][$i];
                    $the_query  = "Insert Into projectArtifact (projectID, artifactID, isAnchor) VALUES(" . $projectID . ", " . $artifactID . ", " . "null);";
                    $stmt       = $dbq->query($the_query);

                    $paid = $dbq->query('SELECT @nid')->fetchColumn();
                    array_push($paids, $paid);
                }
                break;

            case 'persona':
                //insert personae
                $personaName = $_POST['personaName'];
                $personaDesc = $_POST['personaDesc'];
                $personaIDs  = array();
                for ($i = 0; $i < count($personaName); $i++) {
                    $stmt = $dbq->prepare("CALL addPersona(:personaTitle,:description,:languageID,@nid)");
                    $stmt->bindValue(':personaTitle', $personaName[$i], PDO::PARAM_STR);
                    $stmt->bindValue(':description', $personaDesc[$i], PDO::PARAM_STR);
                    $stmt->bindValue(':languageID', 5, PDO::PARAM_INT);
                    $stmt->execute();
                    $personaID = $dbq->query('SELECT @nid')->fetchColumn();
                    array_push($personaIDs, $personaID);
                };

                // prepare scenarioIDs
                $scenarioIDs = array();
                $pre_result  = $dbq->prepare("select scenarioID from scenario");
                $pre_result->execute();
                while ($row = $pre_result->fetch(PDO::FETCH_ASSOC)) {
                    $scenarioIDs[] = $row['scenarioID'];
                }

                //update personaScenario
                $psIDs = array();
                for ($i = 0; $i < count($personaIDs); $i++) {
                    for ($j = 0; $j < count($scenarioIDs); $j++) {
                        $personaID  = $personaIDs[$i];
                        $scenarioID = $scenarioIDs[$j];
                        $stmt       = $dbq->prepare("CALL addPersonaScenario(:pid,:sid,@PSID)");
                        $stmt->bindValue(':pid', $personaID, PDO::PARAM_INT);
                        $stmt->bindValue(':sid', $scenarioID, PDO::PARAM_INT);
                        $stmt->execute();
                        $psID = $dbq->query('SELECT @PSID')->fetchColumn();
                    }
                }
                break;

            case 'user':
                //try to insert the project into mysql database and get the new added project id
                //prepare PDO statement, addProject SPROC
                $email          = $_POST['email'];
                $firstName      = $_POST['firstName'];
                $lastName       = $_POST['lastName'];
                $languageID     = $_POST['languageID'];
                $AuthorityLevel = 1;
                $userPersonas   = $_POST['userPersona'];
                $userID         = null;
                $the_query      = "INSERT INTO `user`(`email`, `firstName`, `lastName`, `languageID`, `passwordValue`, `AuthorityLevel`)
                                            VALUES ('" . (string) $email . "','" . (string) $firstName . "','" . (string) $lastName . "','" . (string) $languageID . "','placeholder','" . (string) $AuthorityLevel . "')";

                $stmt = $dbq->prepare($the_query);
                $stmt->execute();
                $userID = $dbq->query('SELECT LAST_INSERT_ID();')->fetchColumn();

                // update userPersonae
                for ($i = 0; $i < count($userPersonas); $i++) {
                    $the_query = "INSERT INTO `userPersona`(`userID`, `personaID`) VALUES (" . (string) $userID . "," . (string) $userPersonas[$i] . ")";
                    $stmt      = $dbq->prepare($the_query);
                    $stmt->execute();
                }

                break;

            case "assessment":

                $url = $_SERVER['REQUEST_URI']; //returns the current URL
                $parts = explode('/',$url);
                $root_url = $_SERVER['SERVER_NAME'];
                for ($i = 0; $i < count($parts) - 1; $i++) {
                    $root_url .= $parts[$i] . "/";
                }

                $projectID  = $_POST['project'];
                $artifactID = $_POST['artifact'];
                $personaID  = $_POST['persona'];
                $scenarioID = $_POST['scenario'];
                $userID     = $_POST['user'];
                $attributeConfigurationID = $_POST['attributeConfiguration'];


                $addAssessmentConfiguration = $dbq->prepare("CALL addAssessmentConfiguration(:projectID, :artifactID, :scenarioID, :personaID, @assessmentConfID)");
                $addAssessmentConfiguration->bindValue(':projectID', $projectID, PDO::PARAM_INT);
                $addAssessmentConfiguration->bindValue(':artifactID', $artifactID, PDO::PARAM_INT);
                $addAssessmentConfiguration->bindValue(':scenarioID', $scenarioID, PDO::PARAM_INT);
                $addAssessmentConfiguration->bindValue(':personaID', $personaID, PDO::PARAM_INT);
                $addAssessmentConfiguration->execute();
                $addAssessmentConfiguration->closeCursor();

                $assessmentConfigurationID = $dbq->query('SELECT @assessmentConfID')->fetchColumn();

                $addConfiguration = $dbq->prepare("CALL addConfiguration(:atrConfID, :assConfID, @confID)");
                $addConfiguration->bindValue(':atrConfID', $attributeConfigurationID, PDO::PARAM_INT);
                $addConfiguration->bindValue(':assConfID', $assessmentConfigurationID, PDO::PARAM_INT);
                $addConfiguration->execute();
                $addConfiguration->closeCursor();

                $configurationID = $dbq->query('SELECT @confID')->fetchColumn();

                $the_query = "INSERT INTO `assessment`(`configurationID`, `userID`, `personaID`, `scenarioID`, `projectArtifactID`, `isComplete`, `completionDate`)
                              VALUES ($configurationID, $userID, $personaID, $scenarioID, $project_artifactID, null, null)";
                $stmt      = $dbq->prepare($the_query);
                $stmt->execute();

                $assessmentID = $dbq->query('SELECT LAST_INSERT_ID();')->fetchColumn();

                $hash = hash('sha256', $assessmentID);

                $language = 5;

                $targetURL = "rater.php?&asid=" . $hash;
                $fullUrl = $root_url;
                $fullUrl .= $targetURL;

                $addHash = $dbq->prepare("UPDATE assessment SET assessmentIDHashed = :hash, ratingUrl = :url where assessmentID = :assessmentID");
                $addHash->bindValue(':hash', $hash, PDO::PARAM_STR);
                $addHash->bindValue(':url', $fullUrl, PDO::PARAM_STR);
                $addHash->bindValue(':assessmentID', $assessmentID, PDO::PARAM_INT);
                $addHash->execute();

                break;

            case "index":

                if (isset($_POST['user_email']) && isset($_POST['password'])) {
                    $user_email = $_POST['user_email'];
                    $password   = $_POST['password'];
                    if (!preg_match("/^\s*$/i", $user_email) && !preg_match("/^\s*$/i", $password)) {
                        $auth_query = "select * from user
                                       where email = '" . (string) $user_email . "'
                                        and AuthorityLevel = 2";
                        $result     = $dbq->query($auth_query)->fetchAll();
                        if ($result) {
                            if ($password == $result[0]['passwordValue']) {
                                $authenticated = true;
                            }
                            $userID = $result[0]['userID'];
                        }
                    }
                }
                break;

            case "logout":
                // log the user
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                // delete session
                unset($_SESSION['user']);
                session_destroy();
                break;

            default:
                break;
        }
    }
    catch (PDOException $e) {
        // Report errors
        printf ($e->getMessage());
    }
}
// redirect based on source param

if ($source == "assessment") {
    $source_url = "admin_rp.php";
} elseif ($source == "index") {
    if ($authenticated) {
        // authenticate the user
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // auth okay, setup session
        $_SESSION['user_email'] = $_POST['user_email'];
        $_SESSION['teds.userID'] = $userID;
        $source_url             = "admin.php?notice=success";


    } else {
        $source_url = "index.php?notice=no_access";
    }
} elseif ($source == "logout") {
    $source_url = "index.php?notice=logout";
} else {
    $source_url = "admin_pjt_" . (string) $source . ".php";
}

header("Location: $source_url");
?>