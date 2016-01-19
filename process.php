<?php

// require_once "session_inc.php";



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
                // echo $v;
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

                // $userRating_ID = $dbq->query('SELECT @lurID')->fetchColumn();
                $userRating_ID = $dbq->query('SELECT LAST_INSERT_ID()')->fetchColumn();
                // echo $userRating_ID;

                $ids['lastUserRatingProcessID'] = $dbq->query('SELECT @nrid')->fetchColumn();
                // echo $ids['lastUserRatingProcessID'];
                // exit;

                if (preg_match("/^\s*$/i", $ids['lastUserRatingProcessID'])) {
                    $error_free = false;
                }
                // echo "<pre>";
                // print_r($_FILES['screenshot']);
                // echo"</pre>";

                // if there is a file set for this rating then add it
                if(!empty($_FILES['screenshot']['name'][$k])){

                      $thisScreenshot = $_FILES['screenshot'];
                      $errors= array();
                      $file_name = $thisScreenshot['name'][$k];
                      $file_size =$thisScreenshot['size'][$k];
                      $file_tmp =$thisScreenshot['tmp_name'][$k];
                      $file_type=$thisScreenshot['type'][$k];
                      $file_ext=strtolower(end(explode('.',$thisScreenshot['name'][$k])));

                      $expensions= array("jpeg","jpg","png");

                      if(in_array($file_ext,$expensions)=== false){
                         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
                      }

                      if($file_size > 2097152){
                         $errors[]='File size must be less than 2 MB';
                      }

                      if(empty($errors)==true){
                        $path = "upload/screenshots/".$file_name;
                        if(move_uploaded_file($file_tmp, $path)) {
                          $screen_sql = "INSERT INTO screenshot (screenshotPath, userCreated) VALUES ('$path' , " . $ids['user'] . ")";

                          $dbq->query($screen_sql);
                          $screenshot_ID = $dbq->query('SELECT LAST_INSERT_ID();')->fetchColumn();
                          $userRating_screen_sql = "INSERT INTO userRating_screenshot (userRatingID, screenshotID) VALUES ($userRating_ID , $screenshot_ID)";
                          $dbq->query($userRating_screen_sql);
                        } else {
                          // echo "could not upload file at " . $file_tmp;
                          exit;
                        }
                      }
                      else{
                         print_r($errors);
                      }
                   }


                   //adding comments if they exist
                   if(!empty($_POST['comment'][$k])){
                    // echo "found one";
                    $addComment = $dbq->prepare("INSERT INTO comment (comment, userCreated) VALUES ( :comment , :userID)");
                    $addComment->execute(array(':comment' => $_POST['comment'][$k], ':userID' => $ids['user'] ));
                    $comment_id = $dbq->query('SELECT LAST_INSERT_ID()')->fetchColumn();

                    $add_comment_assoc_sql = "INSERT INTO userRating_comment (userRatingID, commentID) VALUES ($userRating_ID, $comment_id)";
                    $dbq->query($add_comment_assoc_sql);
                   }

                // debug statements
                //              echo "last insert / update id: ". $dbq->query('SELECT @nrid')->fetchColumn() . '<br />';


            }//end if is a number
        } // end print rate row
    } // if post rate is set
} //END TRY

catch(PDOException $e) {

    // Report errors
    //  printf ($e->getMessage());

    $ids['db_err_message'] = $e->getMessage();
    $error_free = false;
}

if (!$error_free) {
  require_once "header.no_session.inc.php";
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
    if ($_GET['type'] == 'save') {
        $referer = $_SERVER['HTTP_REFERER'];
        header("Location: $referer");
    }
    if ($_GET['type'] == 'submit') {
      require_once "header.no_session.inc.php";

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
    }
    $dbq = NULL;

}

$_SESSION['ids'] = $ids;
?>