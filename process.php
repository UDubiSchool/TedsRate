
<?php

// require_once "session_inc.php";

require_once "header.no_session.inc.php";

/*
Setting up some default values so there are no nulls

All variables will be stored in associate array in $ids
*/
$ids['user'] = $_POST['userID']; //user id - get from session
$ids['persona'] = $_POST['personaID']; //persona id, get from rater.php form submit
$ids['scenario'] = $_POST['scenarioID']; //scenario id, get from rater.php form submit
$ids['project'] = $_POST['actProject']; //project id, get from rater.php form submit
$ids['artifact'] = $_POST['actArtifact']; //artifact id, get from rater.php form submit
$ids['userRating'] = $_POST['urpID'];
$screenshots = array();

require_once "dbconnect.php";
// print_r($ids);
// print "<br/>";
// exit;

$error_free = true;
try {
    $dbq = db_connect();
    $dbq->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // set up simple SQL statements for grabbing some intermediate combination IDs to use in the addUserRating SPROC below

    $sql['psid'] = 'SELECT psID from personaScenario WHERE personaID=' . $ids['persona'] . ' AND scenarioID=' . $ids['scenario'];
    $sql['upid'] = 'SELECT userPersonaeID from userPersonae WHERE userID=' . $ids['user'] . ' AND personaeID=' . $ids['persona'];
    $sql['paid'] = 'SELECT projectArtifactID from projectArtifact WHERE projectID=' . $ids['project'] . ' AND artifactID=' . $ids['artifact'];

    // run queries set up above, put values in to $ids array

    foreach($sql as $k => $v) {
        foreach($dbq->query($v) as $row) {
            $ids[$k] = $row[0];
        }
    }

    // debug statements
    //  print_r($ids);
    //  print "<br/>";

    if ($_POST['rate']) { //array of form elements with name="rate[]" from rater.php, they correspond to all category rating inputs
        $_SESSION['rateform'] = $_POST['rate'];

        // debugger;

        foreach($_POST['rate'] as $k => $v) {
            if (is_numeric($v) && $v != 0) { // if input is a number and not 0

                // get scenarioCategoryID

                $sql = 'SELECT SC_ID from scenarioCategory WHERE scenarioID=' . $ids['scenario'] . ' AND categoryID=' . $k;
                foreach($dbq->query($sql) as $row) {
                    $scid = $row[0];
                }

                // prepare PDO statement, addUserRating SPROC is now an INSERT OR UPDATE ON UNIQUE KEY

                $stmt = $dbq->prepare("CALL addUserRating(:uid,:rid,:upid,:psid,:scid,:aid,@nrid,:urpID)");
                $stmt->bindValue(':uid', $ids['user'], PDO::PARAM_INT);
                $stmt->bindValue(':rid', $v, PDO::PARAM_STR);
                $stmt->bindValue(':upid', $ids['upid'], PDO::PARAM_INT);
                $stmt->bindValue(':psid', $ids['psid'], PDO::PARAM_INT);
                $stmt->bindValue(':scid', $scid, PDO::PARAM_INT);
                $stmt->bindValue(':aid', $ids['artifact'], PDO::PARAM_INT);
                $stmt->bindValue(':urpID', $ids['userRating'], PDO::PARAM_INT);
                $stmt->execute();

                // debug statements
                //              echo "last insert / update id: ". $dbq->query('SELECT @nrid')->fetchColumn() . '<br />';

                $ids['lastUserRatingProcessID'] = $dbq->query('SELECT @nrid')->fetchColumn();
                if (preg_match("/^\s*$/i", $ids['lastUserRatingProcessID'])) {
                    $error_free = false;
                }
            }
        }
    }

    if ($_POST['ratingNarrative']) { //if descriptive narrative exists, do things with it
        $_SESSION['ratingNarrative'] = $_POST['ratingNarrative'];

        //      $stmt = $dbq->prepare("CALL addNarrative(narrative,urid,@nnid)");
        //      $stmt->bindValue('narrative',$_POST['ratingNarrative'], PDO::PARAM_STR);
        //      $stmt->bindValue('urid',$ids['userRating'], PDO::PARAM_INT);
        //      $stmt->execute();

        $stmt = $dbq->prepare("INSERT INTO ratingNarrative
                            (userRatingID, userNarrative)
                            VALUES
                            (" . $ids['userRating'] . ", '" . (string)$_POST['ratingNarrative'] . "')");
        $stmt->execute();

        // debug statements
        //      echo $_POST['ratingNarrative'] . '<br />';
        //      echo "last insert / update id: ".$dbq->query('SELECT LAST_INSERT_ID();')->fetchColumn() . '<br />';

        $ids['narrativeID'] = $dbq->query('SELECT LAST_INSERT_ID();')->fetchColumn();

        //        echo("INSERT INTO ratingNarrative
        //                            (userRatingID, userNarrative)
        //                            VALUES
        //                            (" . $ids['userRating'] . ", '" . (string)$_POST['ratingNarrative'] ."')");
        //        echo("<p>last inserted narrative id: " . $ids['narrativeID'] . "</p>");

        if (preg_match("/^\s*$/i", $ids['narrativeID'])) {
            $error_free = false;
        }
    }

    if ($_FILES) {
        $updir = "upload/"; //set up upload directory

        //        print_r($_FILES);
        // test if files exist, if yes, then say so, in not, upload and move files from tmp

        for ($i = 0; $i < 2; $i++) {
            if ($_FILES["scn"]["error"][$i] > 0) {

                //              echo "Return Code: " . $_FILES["scn"]["error"][$i] . " NO FILE UPLOADED " . "<br />";
                //                $error_free = false;

                $screenshots[$i] = NULL;
            }
            else {

                //              echo "Uploaded: " . $_FILES["scn"]["name"][$i] . "<br />";

                $screenshots[$i] = $updir . $_FILES["scn"]["name"][$i];
                if (file_exists($updir . $_FILES["scn"]["name"][$i])) {

                    //                  echo $_FILES["scn"]["name"][$i] . " already exists.<br />";

                    $error_free = false;
                }
                else {
                    move_uploaded_file($_FILES["scn"]["tmp_name"][$i], $updir . $_FILES["scn"]["name"][$i]);

                    //                  echo "Stored in: " . $updir . $_FILES["scn"]["name"][$i] . "<br />";

                }
            }
        }

        // debug statements
        //      echo $updir . $_FILES["scn"]["name"][0];
        //      echo $updir . $_FILES["scn"]["name"][1];
        // call to database and store screenshot locations

        $stmt = $dbq->prepare("CALL addScreenShot(:urid,:scn1,:scn2,@scnid)");
        $stmt->bindValue(':urid', $ids['userRating'], PDO::PARAM_INT);
        $stmt->bindValue(':scn1', $updir . $_FILES["scn"]["name"][0], PDO::PARAM_STR);
        $stmt->bindValue(':scn2', $updir . $_FILES["scn"]["name"][1], PDO::PARAM_STR);
        $stmt->execute();

        // debug statements
        //      echo "last insert / update id: ". $dbq->query('SELECT @scnid')->fetchColumn() . '<br />';

    }

    // close dbconn
    //  $dbq = NULL;
    //  print "close db connection<br />";

}

catch(PDOException $e) {

    // Report errors
    //  printf ($e->getMessage());

    $ids['db_err_message'] = $e->getMessage();
    $error_free = false;
}

if (!$error_free) {
?>
    <div class="error_container">
        <h2>Warning: records not saved! </h2>
        <p>Ooops! Server error. More information as following:</p>
        <p><b><?php
    printf($ids['db_err_message']) ?></b></p>
        <p>Please try again, or contact us: <a href="mailto:timca@uw.edu">TEDS team</a></p>
        <p>Sorry for the troubles.</p>
    </div>
    <?php
}
else {
?>
    <div class="info_container">
        <h2>Success: records successfully saved to the database! </h2>
        <p>Thank you for your participation! We appreciate it!</p>
        <p>If you have any question about the rating, don't hesitate to contact us: <a href="mailto:gaodl@uw.edu">TEDS team</a></p>
    </div>
    <?php

    // update userRatingProcess table

    $urp_update_query = "UPDATE `userRatingProgress` SET `isComplete`='true',`completionDate`=NOW()
                        WHERE `userRatingProgressID` = " . $ids['userRating'];

    //    echo($urp_update_query);

    $stmt = $dbq->query($urp_update_query);
    $dbq = NULL;
}

$_SESSION['ids'] = $ids;
?>