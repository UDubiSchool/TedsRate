<?php

// require_once "session_inc.php";



/*
Setting up some default values so there are no nulls

All variables will be stored in associate array in $ids
*/
$ids['user'] = $_POST['userID']; //user id - get from session
$ids['persona'] = $_POST['personaID']; //persona id, get from rater.php form submit
$ids['scenario'] = $_POST['scenarioID']; //scenario id, get from rater.php form submit
$ids['project'] = $_POST['projectID']; //project id, get from rater.php form submit
$ids['artifact'] = $_POST['artifactID']; //artifact id, get from rater.php form submit
$ids['assessmentID'] = $_POST['assessmentID'];
$screenshots = array();
$userRating_ID;

require_once "dbconnect.php";
// print_r($ids);
// print "<br/>";
// exit;

$error_free = true;
try {
    $dbq = db_connect();
    $dbq->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // debug statements
    //  print_r($ids);
    //  print "<br/>";

    if ($_POST['rate']) { //array of form elements with name="rate[]" from rater.php, they correspond to all category rating inputs
        $_SESSION['rateform'] = $_POST['rate'];

        // debugger;

        foreach($_POST['rate'] as $k => $v) {
            if (is_numeric($v) && $v != 0) { // if input is a number and not 0
                $categoryID = intval(str_replace("'", "", $k));

                // prepare PDO statement, addUserRating SPROC is now an INSERT OR UPDATE ON UNIQUE KEY
                $stmt = $dbq->prepare("CALL addRating(:ratingValue, :attributeID, :assessmentID, @ratingID)");
                $stmt->bindValue(':ratingValue', $v, PDO::PARAM_STR);
                $stmt->bindValue(':attributeID', $categoryID, PDO::PARAM_INT);
                $stmt->bindValue(':assessmentID', $ids['assessmentID'], PDO::PARAM_INT);
                $stmt->execute();

                $ratingID = $dbq->query('SELECT @ratingID')->fetchColumn();


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
                          $screen_sql = "INSERT INTO screenshot (screenshotPath, ratingID) VALUES ('$path' , $ratingID)";

                          $dbq->query($screen_sql);
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
                    // exit;
                    $oldCom = $dbq->prepare("SELECT commentID FROM comment WHERE ratingID = :ratingID");
                    $oldCom->execute(array(':ratingID' => $ratingID));
                    $commentID = $oldCom->fetchColumn();
                    // echo $comID;
                    if ($commentID) {
                      // echo "updating";
                      $updateCom = $dbq->prepare("UPDATE comment SET comment = :comment, dateCreated = NOW() WHERE commentID = :commentID");
                      $updateCom->execute(array(':comment' => $_POST['comment'][$k], ':commentID' => $commentID));
                    } else {
                      // echo "inserting";
                      $addComment = $dbq->prepare("INSERT INTO comment (comment, ratingID) VALUES ( :comment , :ratingID)");
                      $addComment->execute(array(':comment' => $_POST['comment'][$k], ':ratingID' => $ratingID ));
                    }
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

        // update assessment table

        $urp_update_query = "UPDATE `assessment` SET `completionDate`=NOW()
                            WHERE `assessmentID` = " . $ids['assessmentID'];

        $stmt = $dbq->query($urp_update_query);
    }
    $dbq = NULL;

}

$_SESSION['ids'] = $ids;
?>