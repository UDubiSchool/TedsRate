<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/base.css">

        <!-- <link rel="stylesheet" href="css/angular-bootstrap-lightbox.css"> -->
        <!-- <script src="js/jquery-1.11.0.min.js" type="text/javascript"></script> -->
        <!-- <script src="js/angular/angular.min.js" type="text/javascript"></script> -->
        <!-- <base href="/"> -->
        <!-- <base href="/tedsrate/tedsrate/"> -->
    </head>
    <body>
        <style>
            body {
                background-color: #2c3e50;

            }

           /* .header {
                background-color: #fefefe;
                margin-bottom: 15px;
            }*/

            .banner-header {
                position: absolute;
                top:0px;
                left: 15px;
                z-index: 20;
            }

            .content-wrapper {
                background-color: #fff;
                padding:0px;
                font-size: 1.3em;
            }

            .banner-img {
                width: 100%;
                /*padding-bottom:25px;*/
                position:relative;
                margin-top:-20px;
            }

            .content {
                padding:25px 15px;
                text-align: left;
            }
            .content .configuration-btn {
                margin:5px 0px;
            }
        </style>
        <div class="clearfix">
         <!--    <div id="header" class="clearfix header">
                <div class="clearfix pull-left">
                </div>
            </div> -->
            <div class="container content-wrapper col-xs-12 col-sm-11 col-md-9 col-lg-8 center-block">
                <h2 class="banner-header">Welcome to TedsRate</h2>

                <img src="img/soccerBall.jpg" alt="Soccer ball image" class="banner-img">

                <div class="content">
                    <?php
                    if(isset($_GET['g'])) {
                        require_once "dbconnect.php";
                        $dbq = db_connect();


                        // echo 'was set';
                        $group_query = $dbq->prepare("SELECT * FROM `group` g
                                                INNER JOIN group_configuration gc on gc.groupID = g.groupID
                                                INNER JOIN configuration ON configuration.configurationID = gc.configurationID
                                                INNER JOIN assessmentConfiguration ON assessmentConfiguration.assessmentConfigurationID = configuration.assessmentConfigurationID
                                                INNER JOIN artifact ON artifact.artifactID = assessmentConfiguration.artifactID
                                                INNER JOIN scenario ON scenario.scenarioID = assessmentConfiguration.scenarioID
                                                WHERE g.groupID = :groupID");
                        $group_query->bindValue(':groupID', intval($_GET['g']), PDO::PARAM_STR);
                        $group_query->execute();

                        $group = $group_query->fetchAll();

                        foreach ($group as $key => $value) {
                        ?>
                            <div class="col-xs-6 configuration-btn">
                                <a class="btn btn-primary btn-block" href="start.php?c=<?php echo $value['configurationIDHashed']?>">Start <?php echo "$value[artifactName] - $value[scenarioName]";?></a>

                            </div>
                        <?php
                        }
                    ?>

                    <div class="welcome-text col-xs-12">
                        <br>
                        <?php
                            echo $group[0]['groupWelcomeTemplate'];
                        ?>
                    </div>
                    <?php
                    } else {
                    ?>

                    <div class="col-xs-6">
                        <a class="btn btn-primary btn-block" href="start.php?c=62f77e7d6197863ac98d9e0cfa76bea0c8e05379ed5281afbe72f7fc206fe37b">Start Player Information</a>

                    </div>
                    <div class="col-xs-6">
                        <a class="btn btn-primary btn-block" href="start.php?c=e52d08747b9d7a6d04551bb86ee3f7ee6c49f7477c8cd66f77448378cc30b92b">Start Schedule, Results and League</a>
                    </div>
		    <br/>
                    <p>
                        The UW TEDS Soccer Research Project is a research from the University of Washington Information School testing a methodology to measure the usability of iOS (Apple) mobile soccer apps. We would really appreciate your evaluation feedback of the Sounders FC iOS mobile application.
                    </p>
		    <p>
			Please make sure you have the latest version of the Sounders iOS app before doing your analysis. To do this, go to the app store and update to the latest version if it that option is available.
		    </p>
                    <p>
                        The Sounders FC application is one of 11 mobile soccer applications from around the world we are comparing in this study. In addition to the academic study, the analysis and data collected will be anonymized and presented to the Sounders to assist in improving their application.
                    </p>
                    <p>
                        We are analyzing 2 scenarios in this study, "Player Information", and "Schedule, Results and League". We'd love your opinion on both scenarios if possible, but if you only do one, that's fine too.  Each survey takes about 5 minutes to complete. We appreciate you taking the time and your valuable opinion.   The first 25 users who complete both surveys will receive a $5 Amazon gift card. Thanks!
                    </p>

                    <!-- <p>
                        Please use the following link to evaluate the "Player Information" features. (Roster, player card, player stats, etc):
                        <br>
                    </p>
                    <p>
                        Please use the following link to evaluate "Schedule, Results and League" features (Schedule calendar, game stats, recap, the table, etc):
                        <br>
                    </p> -->
                    <p>
                        No personal data will be shared from these surveys.
                    </p>
                    <p>
                        Thanks!
                        <br>
                        <br>
                        <i>The Teds Team</i>
                    </p>

                    <?php
                    }
                    ?>

                </div>

            </div>
        </div>
    </body>
</html>
