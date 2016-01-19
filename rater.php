<?php
require_once "header.no_session.inc.php";
//require_once "session_inc.php";

$pid = null; //project id
$aid = null; //artifact id


//get project and artifact IDS from GET variables via <form> submit in index.php
//if ( isset($_GET['selProject']) && isset($_GET['selArtifact']) ){
//	$pid = $_GET['selProject'];
//	$aid = $_GET['selArtifact'];
//}

require_once "dbconnect.php";

// selLanguage=5&selProject=26&selArtifact=60&selScenario=27&selPersona=20
if (isset($_GET['selLanguage']) && isset($_GET['selProject']) && isset($_GET['selScenario']) && isset($_GET['selPersona']) && isset($_GET['selArtifact'])) {
    foreach ($_GET as $key => $value) {
        if (preg_match("/^\s*$/i", $value)) {
            ?>
            <div class="error_container">
                <h2>Authentication failed: an invalid URL is provided!</h2>
                <p>Please make sure you have not modified the link</p>
                <p>Try again using the original link from your email.</p>
                <p>If the problem still occurs, contact us: <a href="mailto:gaodl@uw.edu">TEDS team</a></p>
                <p>Sorry for the trouble.</p>
            </div>
            <?php

            return;
        }
    }

    try {
        $dbq = db_connect();
        $dbq->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $pid = $_GET['selProject'];
        $aid = $_GET['selArtifact'];
        $lanID = $_GET['selLanguage'];
        $personaID = $_GET['selPersona'];
        $scenarioID = $_GET['selScenario'];
        $urpID = $_GET['urpId'];

        $authenticate_query =  "SELECT * FROM userRatingProgress urp
                                join projectArtifact pa on urp.projectArtifactID = pa.projectArtifactID
                                join userProfile upro on upro.userID = urp.userID
                                where pa.projectID = " . $pid .
                                " and pa.artifactID = " . $aid .
                                " and upro.preferredLanguage = " . $lanID .
                                " and urp.personaID = " . $personaID .
                                " and urp.scenarioID = " . $scenarioID .
                                " and urp.`userRatingProgressID` = " . $urpID;

//        echo($authenticate_query);

        $flag = $dbq->query($authenticate_query)->fetchAll();
//        print_r($flag);

        if (!$flag) {
            // authentication failed: user identity mismatch
        ?>
            <div class="error_container">
                <h2>Authentication failed: user identity mis-match!</h2>
                <p>We are sorry, but according to our record, you don't have access to this rating. Please make sure the link is not modified. </p>
                <p>Please try again using the original link from your email. </p>
                <p>And if it still does not work, please contact us: <a href="mailto:gaodl@uw.edu">TEDS team</a></p>
            </div>
        <?php
        } else {
//            print_r($flag[0]['userRatingProgressID']);
//            echo($flag[0]['userRatingProgressID']);
//            echo($flag[0]['userID']);

            // available variables
            $uid = $flag[0]['userID'];
            $urpID = $flag[0]['userRatingProgressID'];
        ?>
                <!-- container -->
                <link rel="stylesheet" href="stylesheets/rater.css">
                <div id="sitecontainer">
                    <div class="row">
                        <div id="artPane" class="eight columns">

                        <form action="process.php" id="rateForm" method="post" enctype="multipart/form-data">


                            <input type="hidden" name="actProject" value="<?php echo $pid;?>" class="notEmpty">
                            <input type="hidden" name="actArtifact" value="<?php echo $aid;?>" class="notEmpty">
                            <input type="hidden" name="userID" value="<?php echo $uid ?>" class="notEmpty">
                            <input type="hidden" name="personaID" value="<?php echo $personaID ?>" class="notEmpty">
                            <input type="hidden" name="scenarioID" value="<?php echo $scenarioID ?>" class="notEmpty">
                            <input type="hidden" name="urpID" value="<?php echo $urpID ?>" class="notEmpty">
            <?php
                $sth = $dbq->prepare('CALL getArtifact('.$aid.',@title,@url,@desc,@type)');
                $sth->execute();
                while ($row = $sth->fetch()){
            ?>
                <dl id="anchorSel" class="sub-nav">
                  <dt>Active site view:</dt>
                  <dd class="active"><a href="#">
            <?php
                //populate site (artifact) title in view toggle
                printf($row['title']);
            ?>
                  </a></dd>
                  <dd><a href="#">Anchor Site</a></dd>
                </dl>


                <div id="sitePane">
                    <div id="currRate" class="activeSite">

            <?php

                 printf("<h2>%s: %s</h2>", $row['title'], urldecode($row['URL']));
                 echo '<input type="hidden" id="activeIframeSrc" value="' . urldecode($row['URL']) . '">';
                 print_r('<iframe id="activeIframe" scrolling="auto" src=""></iframe>');
                }
                $sth->closeCursor();
            ?>
                    </div>
                    <div id="anchor" class="activeSite">
                        <h2>Anchor Site - Wikipedia.org, http://en.wikipedia.org</h2>
                        <iframe width="100%" scrolling="auto" src="http://en.wikipedia.org"></iframe>
                    </div>
                </div>
                        </div>


                        <div id="ratePane" class="four columns">
            <?php
                //populate project title and description
                $sth = $dbq->query('CALL getProject('.$pid.',@title,@desc)');
                //printf ("rows/cols returned: %d, %d\n", $sth->rowCount(),$sth->columnCount());

                while ($row = $sth->fetch()){
                 printf ("<h2>%s</h2><p>%s</p>", $row['title'], $row['description']);
                }
                $sth->closeCursor();
            ?>

                            <table width="100%">
                                <tr>
                                    <td>
            1. Current Persona:
            <p id="personae">
            <?php
                //populate personas the "language" value (5) is hard coded!
                $sth = $dbq->query('select * from personae where personae.personaeID = ' . $personaID);
                while ($row = $sth->fetch()){
                    echo($row['personaTitle']);
                }
                $sth->closeCursor();
            ?>
            </p>
                                    </td>
                                    <td>
            2. Current Scenario
            <p id="scenario">
            <?php
            //populate scenarios the "language" value (5) is hard coded!

                $sth = $dbq->query('select * from scenario where scenario.scenarioID = ' . $scenarioID);
                while ($row = $sth->fetch()){
                    echo($row['scenarioTitle']);
                }
                $sth->closeCursor();
            ?>
            </p>
                                    </td>
                                </tr>
                            </table>
                        <h4>Select User Agent</h4>
                        <select class="form-control" name="userAgentPicker" id="userAgentPicker">
                            <option class='default-val'  value="">Default</option>
                            <option data-width="640" data-height="1136" value="Mozilla/5.0 (iPhone; U; CPU iPhone OS 5_1_1 like Mac OS X; en) AppleWebKit/534.46.0 (KHTML, like Gecko) CriOS/19.0.1084.60 Mobile/9B206 Safari/7534.48.3">Chrome, iPhone 5</option>
                            <option data-width="720" data-height="1280" value="Mozilla/5.0 (Linux; U; Android-4.0.3; en-us; Galaxy Nexus Build/IML74K) AppleWebKit/535.7 (KHTML, like Gecko) CrMo/16.0.912.75 Mobile Safari/535.7">Chrome, Galaxy Nexus</option>
                            <option  data-width="720" data-height="1280" value="Mozilla/5.0 (Android; Mobile; rv:40.0) Gecko/40.0 Firefox/40.0">Firefox, Android</option>
                        </select>
                        <br>
                        <h2>Categories</h2>

            <ul id="categories">
            <?php
            //populate categories the "language" value (1) is hard coded!
                try {
                    // echo "started retieval";
                    $ratingData = [];
                    $current = "SELECT
                    userRating.id,
                    userRating.ratingID,
                    category.categoryID,
                    category.categoryTitle,
                    screenshot.screenshotPath,
                    screenshot.screenshotDesc,
                    comment.comment
                    FROM userRatingProgress
                    JOIN userRating ON userRatingProgress.userRatingProgressID = userRating.userRatingProcessID
                    JOIN scenarioCategory ON scenarioCategory.SC_ID = userRating.scenarioCategoryID
                    JOIN category ON scenarioCategory.categoryID = category.categoryID
                    LEFT JOIN userRating_screenshot ON userRating.id = userRating_screenshot.userRatingID
                    LEFT JOIN screenshot ON userRating_screenshot.screenshotID = screenshot.screenshotID
                    LEFT JOIN userRating_comment ON userRating.id = userRating_comment.userRatingID
                    LEFT JOIN comment ON userRating_comment.commentID = comment.commentID
                    WHERE userRatingProgress.userRatingProgressID = $urpID;";
                    $current = $dbq->query($current);
                    while ($currentResult = $current->fetch()){
                        array_push($ratingData, $currentResult);
                    }
                } catch (PDOException $e) {
                    echo $e;
                }
                // echo"<pre>";
                // print_r($ratingData);
                // echo"</pre>";
                // exit;
                $sth = $dbq->query('CALL getParentCategories(5,@cid,@ctitle,@cdesc)');

                while ($prow = $sth->fetch()){
                printf('<li><b>%s</b>', $prow['categoryTitle']);
            ?>
                <ul>
            <?php

                    //prints out the ratings attributes and if either the session data is filled or the rating was previously submitted and $ratingsData is populated then fill out with those values. If neither is specified then it will just print the blank fields.
                    foreach($dbq->query('CALL getCategoryAndChildren('. $prow['categoryID'] .',@cid,@ctitle,@description)') as $row) {
                        $rating = '';
                        $hasScreenshot = false;
                        $hasComment = false;
                        $screenshots = array();
                        // $screenshot_path = '';
                        $comment = '';

                        if (!empty($ratingData)) {
                            foreach ($ratingData as $key => $value) {
                                if ($value['categoryID'] == $row['categoryID']) {
                                    $rating = $value['ratingID'];
                                    if(isset($value['screenshotPath'])) {

                                        $hasScreenshot = true;
                                        if(!in_array($value['screenshotPath'], $screenshots)) {
                                            array_push($screenshots, $value['screenshotPath']);
                                        }
                                        // echo 'found screenshot';
                                        // echo $value['screenshotPath'];
                                    }
                                    if(isset($value['comment'])) {
                                        $hasComment = true;
                                        $comment = $value['comment'];
                                        // echo $thisComment;
                                    }
                                }
                            }
                            array_reverse($screenshots);
                        }

                        echo "<li><div>";
                        echo $row['categoryTitle'] . '<input class="notEmpty ratingInput" name="rate[' . $row['categoryID'] .  ']" type="text"';
                        if (isset($_SESSION['rateform'])){
                            printf('value="' . $_SESSION['rateform'][$row['categoryID']] . '"/>');
                        } elseif (!empty($ratingData)) {
                            printf('value="' . $rating . '"/>');
                        } else {
                            printf('/>');
                        }
                        echo '<b class="toggle" data-target="definition">Show Definition</b>
                                 <b class="toggle" data-target="screenshot">Add Screenshot</b>
                                 <b class="toggle" data-target="comment">Add Comment</b>
                                 </div>
                                 <div>
                                    <div class="definition toggle-target"><p>' . $row['categoryDescription'] . '</p></div>';
                        if (!empty($ratingData)) {

                            if ($hasScreenshot) {
                                echo '<div class="screenshot toggle-target">';
                                foreach ($screenshots as $key => $value) {
                                    echo '<p><a href="' . $value . '" target="_blank">Screenshot ' . $key . '</a></p>';
                                }
                                echo '<input type="file" class="form-control" name="screenshot['  . $row['categoryID'] .  ']"></div>';
                            } else {
                                echo '<div class="screenshot toggle-target"><input type="file" class="form-control" name="screenshot['  . $row['categoryID'] .  ']"></div>';
                            }
                        } else {
                            echo '<div class="screenshot toggle-target"><input type="file" class="form-control" name="screenshot['  . $row['categoryID'] .  ']"></div>';
                        }

                        echo '<div class="comment toggle-target"><textarea class="form-control" name="comment['  . $row['categoryID'] .  ']">' . $comment . '</textarea></div>';


                        echo '</div>';
                        echo "</li>";
                    }
            ?>
                </ul>
            <?php
                print "</li>";
                }
                $sth->closeCursor();

            //close connection
            $dbq = NULL;
        ?>
            </ul>

                        <button id="saveForm" class="btn btn-primary">Save Form</button>
                        <button id="submitForm" class="btn btn-success">Submit Form</button>

                        </form>
                    </div>

                </div>


            </div>
            <!-- sitecontainer -->

            <!-- Included JS Files -->
            <!-- // <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> -->

            <script>
                $(document).ready(function() {
                    //automatically set height of iframe based on browser window size on page load
                    var defaultUA = navigator.userAgent;
                    // console.log(defaultUA);
                    var defaultUrl = "UAProxy.php?";
                    // var finalUrl = defaultUrl + "target=" + $("#activeIframeSrc").val() + "&ua=" +  defaultUA;
                    // $("#activeIframe").attr("src", finalUrl);
                    var ifheight = $(window).height() - $("#sitePane").offset()['top']-50;
                    $("#sitePane iframe").height(ifheight);

                    $('#userAgentPicker .default-val').attr("value", defaultUA);
                    $('#userAgentPicker .default-val').attr("data-width", "100%");
                    $('#userAgentPicker .default-val').attr("data-height", ifheight);

                    changeIframe();



                    //toggle category box collapse/expand on click of main category title
                    $("#categories > li b").click(function(){
                        $(this).parent("li").find("ul").toggle();
                    }).click();

                    //runs process.php and redirects back to this page.
                    $("#saveForm").click(function(){
                        $("#rateForm").attr('action', 'process.php?type=save');
                        $("#rateForm").submit();
                    });

                    $("#submitForm").click(function(){
                        $("#rateForm").attr('action', 'process.php?type=submit');
                        $("#rateForm").submit();
                    });

                    //toggle between anchor site display and current rating site
                    $("#anchorSel a").click(function(e){
                        $(this).parent("dd").toggleClass("active").siblings().toggleClass("active");
                        $("#sitePane .activeSite").toggle();
                        return false;
                    });

                    $(".toggle").click(function(){
                        var target = ".";
                        target = target + $(this).attr('data-target');
                        console.log(target);
                        $(this).parent().next().children(target).slideToggle();
                    });

                    $("#userAgentPicker").change(function() {
                        changeIframe();
                        // document.getElementById('activeIframe').contentWindow.location.reload();


                    });

                    function changeIframe() {
                        var option = $("#userAgentPicker").find(":selected");
                        var width = option.attr("data-width");
                        if (width == "100%") {
                            width = $("#sitePane").width();
                        }
                        var height = option.attr("data-height");
                        // var wrapper = $("#activeIframeWrapper");
                        var iframe = $("#activeIframe");
                        var finalUrl = defaultUrl + "target=" + $("#activeIframeSrc").val() + "&ua=" +  option.val() + "&w=" + width + "&h=" + height;
                        // wrapper.width(width);
                        console.log(width);
                        console.log($(window).width());
                        var scale = parseInt(width) / (parseInt($(window).width()) + 100);
                        scale = scale * (parseInt($(window).width())/parseInt($("#sitePane").width()));
                        iframe.width(width/scale)
                        iframe.height(height/scale);

                        console.log(scale);
                        iframe.css("-webkit-transform", "scale(" + scale + ")");
                        iframe.css("-moz-transform-scale", scale);
                        iframe.attr("src", finalUrl);
                    }
                }); // END DOC READY

            </script>
            <?php
            printf('existing session: %s', session_id() );
            ?>

            <div id="savemsg" class="reveal-modal">
                <h2>Your form been saved</h2>
                <p>The fields you have filled out so far have been saved, but they have not been submitted. Please submit all results once you are done.</p>
                <a class="close-reveal-modal">&#215;</a>
            </div>
<?php
        }
    } catch (PDOException $e) {
         print ("getMessage(): " . $e->getMessage () . "\n");
    }
    ?>


<?php
} else {
?>
    <div class="error_container">
        <h2>Authentication failed: an invalid URL is provided!</h2>
        <p>Please make sure you have not modified the link</p>
        <p>Try again using the original link from your email.</p>
        <p>If the problem still occurs, contact us: <a href="mailto:gaodl@uw.edu">TEDS team</a></p>
        <p>Sorry for the trouble.</p>
    </div>
<?php
}
?>

</body>
</html>
