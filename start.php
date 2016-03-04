<?php

require_once "dbconnect.php";

try {
    $dbq = db_connect();
    $dbq->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    session_start();

    if (isset($_GET['c'])) {
        $configurationIDHashed = $_GET['c'];
        $getConfID = $dbq->query("SELECT configurationID FROM configuration WHERE configurationIDHashed = '$configurationIDHashed'");
        $configurationID = $getConfID->fetchColumn();

        if (isset($_COOKIE['teds_userID'])) {
            $userID = $_COOKIE['teds_userID'];
            if (isValidUser($dbq, $userID)){
                $redirect = makeAssessment($dbq, $userID, $configurationID);
                header("Location: $redirect");

            } else {
                // session_regenerate_id(true);

                $userID = makeUser($dbq);
                $redirect = makeAssessment($dbq, $userID, $configurationID);
                header("Location: $redirect");
            }
        } else {
            // session_regenerate_id(true);
            $userID = makeUser($dbq);
            $redirect = makeAssessment($dbq, $userID, $configurationID);
            header("Location: $redirect");
        }
    } else {
        echo "You are not using this page correctly.";
    }
} catch (PDOException $e) {
    echo $e;
}

// checks to see if the user exists in user entity
function isValidUser($dbq, $userID) {
    $makePartialUser = $dbq->prepare("CALL getUser(:userID, @rowCount)");
    $makePartialUser->bindValue(':userID', $userID, PDO::PARAM_INT);
    $makePartialUser->execute();
    $makePartialUser->closeCursor();

    $rowCount = $dbq->query('SELECT @rowCount')->fetchColumn();
    if($rowCount == 1) {
        return true;
    } else {
        return false;
    }
}

// makes a partial user
function makeUser($dbq) {
    $makePartialUser = $dbq->prepare("CALL addPartialUser(@userID)");
    $makePartialUser->execute();
    $makePartialUser->closeCursor();

    $userID = $dbq->query('SELECT @userID')->fetchColumn();

    $exipirationDate = time() + 86400 * 365 * 1; //cookie expires in one year so there is little likelyhood that they make duplicate temporaries.
    setcookie('teds_userID', $userID, $exipirationDate, "/");

    return $userID;
}

/* * makes or retrieves the assessment, creates the hash and url and updates the assessment.
    * returns -  location to redirect.
*/
function makeAssessment($dbq, $userID, $configurationID) {
    $url = $_SERVER['REQUEST_URI']; //returns the current URL
    $parts = explode('/',$url);
    $root_url = $_SERVER['SERVER_NAME'];
    for ($i = 0; $i < count($parts) - 1; $i++) {
        $root_url .= $parts[$i] . "/";
    }

    $addAssessment = $dbq->prepare("CALL addAssessment(:userID, :configurationID, @assessmentID)");
    $addAssessment->bindValue(':userID', $userID, PDO::PARAM_INT);
    $addAssessment->bindValue(':configurationID', $configurationID, PDO::PARAM_INT);
    $addAssessment->execute();
    $addAssessment->closeCursor();

    $assessmentID = $dbq->query('SELECT @assessmentID')->fetchColumn();


    $hash = hash('sha256', $assessmentID);

    $targetURL = "assessment.php?&asid=" . $hash;
    $fullUrl = $root_url;
    $fullUrl .= $targetURL;

    $addHash = $dbq->prepare("UPDATE assessment SET assessmentIDHashed = :hash, ratingUrl = :url where assessmentID = :assessmentID");
    $addHash->bindValue(':hash', $hash, PDO::PARAM_STR);
    $addHash->bindValue(':url', $fullUrl, PDO::PARAM_STR);
    $addHash->bindValue(':assessmentID', $assessmentID, PDO::PARAM_INT);
    $addHash->execute();

    return $targetURL;
}
?>