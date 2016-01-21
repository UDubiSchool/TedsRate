<?php
// require_once "session_inc.php";
// ============================== authentication ===============================
//if (session_status() == PHP_SESSION_NONE) {
//    session_start();
//}
//session_regenerate_id();
//if(!isset($_SESSION['user_email'])) {    // if there is no valid session
//    header("Location: index.php?notice=login_first");
//}
require_once "session_inc.php";
// ============================== authentication ===============================

require_once "dbconnect.php";

if ($_POST) {
    $source        = $_POST['source']; // source param
    $authenticated = false;
    try {
        $dbq = db_connect();

        switch ($source) {
            case 'project':
                //try to insert the project into mysql database and get the new added project id
                //prepare PDO statement, addProject SPROC
                $projectTitle       = $_POST['projectName'];
                $projectDescription = $_POST['projectDesc'];
                $projectLanguageID  = $_POST['projectLang'];
                $stmt               = $dbq->prepare("CALL addProject(:ptitle,:pdescript,:pLan,@nid)");
                $stmt->bindValue(':ptitle', $projectTitle, PDO::PARAM_STR);
                $stmt->bindValue(':pdescript', $projectDescription, PDO::PARAM_STR);
                $stmt->bindValue(':pLan', $projectLanguageID, PDO::PARAM_INT);
                $stmt->execute();
                //debug statements
                $projectID = $dbq->query('SELECT @nid')->fetchColumn();
                break;

            case 'scenario':
                //insert scenarios
                $scenarioTitle = $_POST['scenarioTitle'];
                $scenarioDesc  = $_POST['scenarioDesc'];
                $scenarioIDs   = array();
                for ($i = 0; $i < count($scenarioTitle); $i++) {
                    //prepare PDO statement, addArtifact SPROC
                    $stmt = $dbq->prepare("CALL addScenario(:title,:description,:languageID,@nid)");
                    $stmt->bindValue(':title', $scenarioTitle[$i], PDO::PARAM_STR);
                    $stmt->bindValue(':description', $scenarioDesc[$i], PDO::PARAM_STR);
                    $stmt->bindValue(':languageID', 5, PDO::PARAM_INT);
                    $stmt->execute();
                    $scenarioID = $dbq->query('SELECT @nid')->fetchColumn();
                    array_push($scenarioIDs, $scenarioID);
                }
                //update scenarioCategory
                $sql["categoryID"] = 'SELECT * from category where categoryID > 6';
                foreach ($dbq->query($sql["categoryID"]) as $cID) {

                    for ($i = 0; $i < count($scenarioIDs); $i++) {
                        $sql["sceCate"] = 'INSERT INTO scenarioCategory (scenarioID, categoryID) VALUES (' . $scenarioIDs[$i] . ', ' . $cID['categoryID'] . ')';
                        $dbq->query($sql["sceCate"]);
                    }

                }
                break;

            case 'atft':
                //try to insert artifact into database
                $artifactTitle    = $_POST['artifactTitle'];
                $artifactURL      = $_POST['artifactURL'];
                $artifactTypeID   = 4;
                $artifactLanguage = 5;
                $artifactIDs      = array();
                for ($i = 0; $i < count($artifactTitle); $i++) {
                    //prepare PDO statement, addArtifact SPROC
                    $stmt = $dbq->prepare("CALL addArtifact(:atitle,:aurl,:typeID,:Lan,@nid)");
                    $stmt->bindValue(':atitle', $artifactTitle[$i], PDO::PARAM_STR);
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
                    $stmt = $dbq->prepare("CALL addPersona(:title,:description,:languageID,@nid)");
                    $stmt->bindValue(':title', $personaName[$i], PDO::PARAM_STR);
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
                $the_query      = "INSERT INTO `userProfile`(`email`, `firstName`, `lastName`, `preferredLanguage`, `passwordValue`, `AuthorityLevel`)
                                            VALUES ('" . (string) $email . "','" . (string) $firstName . "','" . (string) $lastName . "','" . (string) $languageID . "','placeholder','" . (string) $AuthorityLevel . "')";

                $stmt = $dbq->prepare($the_query);
                $stmt->execute();
                $userID = $dbq->query('SELECT LAST_INSERT_ID();')->fetchColumn();

                // update userPersonae
                for ($i = 0; $i < count($userPersonas); $i++) {
                    $the_query = "INSERT INTO `userPersonae`(`userID`, `personaID`) VALUES (" . (string) $userID . "," . (string) $userPersonas[$i] . ")";
                    $stmt      = $dbq->prepare($the_query);
                    $stmt->execute();
                }

                break;

            case "user_rating_progress":

                $root_url = $_SERVER['SERVER_NAME'];
                for ($i = 0; $i < count($parts) - 1; $i++) {
                    $root_url .= $parts[$i] . "/";
                }

                $project  = $_POST['project'];
                $artifact = $_POST['artifact'];
                $persona  = $_POST['persona'];
                $scenario = $_POST['scenario'];
                $user     = $_POST['user'];

                $project_artifactID = $dbq->query('select * from projectArtifact pa
                                                   join project p on pa.projectID = p.projectID
                                                   join artifact a on pa.artifactID = a.artifactID
                                                   where p.projectID = ' . $project . '
                                                   and a.artifactID = ' . $artifact)->fetchColumn();

                $the_query = "INSERT INTO `userRatingProgress`(`userID`, `personaID`, `scenarioID`, `projectArtifactID`, `isComplete`, `completionDate`)
                              VALUES (" . $user . "," . $persona . "," . $scenario . "," . $project_artifactID . ",null,null)";
                $stmt      = $dbq->prepare($the_query);
                $stmt->execute();

                $user_ratingID = $dbq->query('SELECT LAST_INSERT_ID();')->fetchColumn();

                $language = 5;

                $targetURL = "rater.php?&urpId=" . $user_ratingID;
                $fullUrl .= $root_url . "/";
                $fullUrl .= $targetURL;
                $addUrl = "UPDATE `userRatingProgress` SET `ratingUrl` = '" . $fullUrl . "' WHERE userRatingProgressID = " . $user_ratingID;
                $exec   = $dbq->prepare($addUrl);
                $exec->execute();

                break;

            case "index":

                if (isset($_POST['user_email']) && isset($_POST['password'])) {
                    $user_email = $_POST['user_email'];
                    $password   = $_POST['password'];
                    if (!preg_match("/^\s*$/i", $user_email) && !preg_match("/^\s*$/i", $password)) {
                        $auth_query = "select * from userProfile
                                       where email = '" . (string) $user_email . "'
                                        and AuthorityLevel = 2";
                        $result     = $dbq->query($auth_query)->fetchAll();
                        if ($result) {
                            if ($password == $result[0]['passwordValue']) {
                                $authenticated = true;
                            }
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

if ($source == "user_rating_progress") {
    $source_url = "admin_rp.php";
} elseif ($source == "index") {
    if ($authenticated) {
        // authenticate the user
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // auth okay, setup session
        $_SESSION['user_email'] = $_POST['user_email'];
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