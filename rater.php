<?php
require_once "header.no_session.inc.php";
//require_once "session_inc.php";

$pid = null; //project id
$aid = null; //artifact id

require_once "dbconnect.php";

// selLanguage=5&selProject=26&selArtifact=60&selScenario=27&selPersona=20
if (isset($_GET['asid']) || isset($_GET['urpId'])) {
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


        $pid = '';
        $aid = '';
        $lanID = '';
        $personaID = '';
        $scenarioID = '';
        $hashedID = $_GET['asid'];

        $authenticate_query = $dbq->prepare("SELECT * FROM assessment
                                join projectArtifact pa on assessment.projectArtifactID = pa.projectArtifactID
                                join user u on u.userID = assessment.userID
                                where assessment.`assessmentIDHashed` = :hashedID");
        $authenticate_query->bindValue(':hashedID', $hashedID, PDO::PARAM_STR);
        $authenticate_query->execute();

        $flag = $authenticate_query->fetchAll();;
        $pid = $flag[0]['projectID'];
        $aid = $flag[0]['artifactID'];
        $lanID = $flag[0]['preferredLanguage'];
        $personaID = $flag[0]['personaID'];
        $scenarioID = $flag[0]['scenarioID'];
        $assessmentID = $flag[0]['assessmentID'];

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

            // available variables
            $uid = $flag[0]['userID'];
            $assessmentID = $flag[0]['assessmentID'];
            $data = [
                'assessmentID' => $assessmentID
            ];


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
            $sth = $dbq->query('select * from persona where persona.personaID = ' . $personaID);
            while ($row = $sth->fetch()){
                $tmp = [
                    'personaName' => $row['personaName'],
                ];
                $data['persona'] = $tmp;
            }
            $sth->closeCursor();


            //populate scenarios the "language" value (5) is hard coded!
            $sth = $dbq->query('select * from scenario where scenario.scenarioID = ' . $scenarioID);
            while ($row = $sth->fetch()){
                $tmp = [
                    'scenarioName' => $row['scenarioName'],
                ];
                $data['scenario'] = $tmp;
            }
            $sth->closeCursor();

            //populate categories the "language" value (1) is hard coded!
            try {
                // echo "started retieval";
                $ratingData = [];
                $current = "SELECT
                rating.ratingID,
                rating.ratingValue,
                rating.categoryID,
                category.categoryName,
                screenshot.screenshotPath,
                screenshot.screenshotDesc,
                comment.comment
                FROM assessment
                JOIN rating ON assessment.assessmentID = rating.assessmentID
                JOIN category ON rating.categoryID = category.categoryID
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
            } catch (PDOException $e) {
                echo $e;
            }
            // echo '<pre>';
            // print_r($data['ratingsData']);
            // echo'</pre>';

            // gets categories and attaches ratings comments and files
            try {
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
                    foreach($dbq->query('CALL getCategories('. $prow['criterionID'] .',@cid,@ctitle,@description)') as $row) {
                        $ratingValue = '';
                        $hasScreenshot = false;
                        $hasComment = false;
                        $screenshots = [];
                        $comment = '';


                        if (!empty($data['ratingsData'])) {
                            // echo "not empty!";
                            foreach ($data['ratingsData'] as $key => $value) {

                                if ($value['categoryID'] == $row['categoryID']) {

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
                            'categoryID' => $row['categoryID'],
                            'categoryName' => $row['categoryName'],
                            'categoryDesc' => $row['categoryDesc'],
                            'ratingValue' => $ratingValue,
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

            } catch (PDOException $e) {
                echo $e;
            }
            // exit;
            //close connection
            $dbq = NULL;

        ?>
                <!-- container -->
                <link rel="stylesheet" href="css/rater.css">
                <div id="sitecontainer">
                    <div class="row">
                        <div id="artPane" class="eight columns">

                        <form action="process.php" id="rateForm" method="post" enctype="multipart/form-data">


                            <input type="hidden" name="actProject" value="<?php echo $pid;?>" class="notEmpty">
                            <input type="hidden" name="actArtifact" value="<?php echo $aid;?>" class="notEmpty">
                            <input type="hidden" name="userID" value="<?php echo $uid ?>" class="notEmpty">
                            <input type="hidden" name="personaID" value="<?php echo $personaID ?>" class="notEmpty">
                            <input type="hidden" name="scenarioID" value="<?php echo $scenarioID ?>" class="notEmpty">
                            <input type="hidden" name="assessmentID" value="<?php echo $assessmentID ?>" class="notEmpty">
                            <dl id="anchorSel" class="sub-nav">
                              <dt>Active site view:</dt>
                              <dd class="active"><a href="#"><?php echo $data['artifact']['name']; ?></a></dd>
                              <dd><a href="#">Anchor Site</a></dd>
                            </dl>
                            <div id="sitePane">
                                <div id="currRate" class="activeSite">
                                <h2><?php echo $data['artifact']['name'] . ": " . urldecode($data['artifact']['url']); ?></h2>
                                <input type="hidden" id="activeIframeSrc" value="<?php echo urldecode($data['artifact']['url']); ?>">
                                <iframe id="activeIframe" scrolling="auto" src=""></iframe>
                                </div>
                                <div id="anchor" class="activeSite">
                                    <h2>Anchor Site - Wikipedia.org, http://en.wikipedia.org</h2>
                                    <iframe id="anchorIframe" width="100%" scrolling="auto" src="http://en.wikipedia.org"></iframe>
                                </div>
                            </div>
                        </div>
                        <div id="ratePane" class="four columns">
                            <h2><?php echo $data['project']['name']; ?></h2><p><?php echo $data['project']['description']; ?></p>
                            <table width="100%">
                                <tr>
                                    <td>
                                        1. Current Persona:
                                        <p id="personae"><?php echo $data['persona']['personaName']; ?></p>
                                    </td>
                                    <td>
                                        2. Current Scenario
                                        <p id="scenario"><?php echo $data['scenario']['scenarioName']; ?></p>
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
                                foreach ($data['criteria'] as $key => $criterion) {
                            ?>
                                <li>
                                    <b><?php echo $criterion['criterionName']; ?></b>
                                    <ul>
                                        <?php
                                            foreach ($criterion['attributes'] as $key => $attribute) {
                                        ?>
                                        <li>
                                            <div>
                                                <h5><?php echo $attribute['categoryName']; ?></h5>
                                                <input class="notEmpty ratingInput" name="rate['<?php echo $attribute['categoryID']; ?>']" type="text" value="<?php echo $attribute['ratingValue']; ?>"/>
                                                <b class="toggle" data-target="definition">Show Definition</b>
                                                <b class="toggle" data-target="screenshot">Add Screenshot</b>
                                                <b class="toggle" data-target="comment">Add Comment</b>
                                            </div>
                                            <div>
                                                <div class="definition toggle-target">
                                                    <p><?php echo $attribute['categoryDesc']; ?></p>
                                                </div>
                                                <div class="screenshot toggle-target">
                                                    <?php
                                                    foreach ($attribute['screenshots'] as $key => $value) {
                                                        echo '<p><a href="' . $value . '" target="_blank">Screenshot ' . $key . '</a></p>';
                                                    }
                                                    ?>
                                                    <input type="file" class="form-control" name="screenshot['<?php echo $attribute['categoryID']; ?>']">
                                                </div>
                                                <div class="comment toggle-target">
                                                    <textarea class="form-control" name="comment['<?php echo $attribute['categoryID']; ?>']"><?php echo $attribute['comment']; ?></textarea>
                                                </div>
                                            </div>
                                        </li>
                                        <?php
                                            } // end attribute foreach
                                        ?>
                                    </ul>
                                </li>
                            <?php
                                } // end groups foreach
                            ?>
                            </ul>
                            <button id="saveForm" class="btn btn-primary">Save Form</button>
                            <button id="submitForm" class="btn btn-success">Submit Form</button>

                            </form>
                        </div>
                    </div>
                </div>
            <!-- sitecontainer -->

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
                        $(this).parent().next().children(target).slideToggle();
                    });

                    $("#userAgentPicker").change(function() {
                        changeIframe();
                    });

                    function changeIframe() {
                        var iframes = [$("#activeIframe")];

                        iframes.forEach(function(iframe) {
                            var option = $("#userAgentPicker").find(":selected");
                            var width = option.attr("data-width");
                            var sitePaneWidth = parseInt($("#sitePane").width());
                            var windowWidth = parseInt($(window).width());
                            // var iframe = $("#activeIframe");
                            if (width == "100%") {
                                width = $("#sitePane").width();
                            }

                            var innerWidth = iframe.contents().width();
                            // var innerWidth = 980;
                            var scale = (width - 15) / innerWidth ;


                            var height = option.attr("data-height");
                            var finalUrl = defaultUrl + "target=" + $("#activeIframeSrc").val() + "&ua=" +  option.val() + "&w=" + width + "&h=" + height;


                            iframe.width(width/scale)
                            iframe.height(height/scale);
                            iframe.css("-webkit-transform", "scale(" + scale + ")");
                            iframe.css("-moz-transform-scale", scale);
                            iframe.attr("src", finalUrl);
                        })

                    }
                }); // END DOC READY

            </script>
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
