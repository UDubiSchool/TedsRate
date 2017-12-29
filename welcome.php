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
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                        <table class="table table-striped">
                        <thead>
                            <tr>
                                <td class="col-sm-2">Team</td>
                                <td class="col-sm-5">Player Information</td>
                                <td class="col-sm-5">Schedule, Results and League</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Sounders FC</td>
                                <td><a class="btn btn-primary btn-block" href="start.php?c=01299ac65733b5a3d774265fbfe8396b8611e5e3321855dbc541cd301e71fe5e">Start Survey</a></td>
                                <td><a class="btn btn-primary btn-block" href="start.php?c=de5872c6bb4494cebd250152ce148cd6231654e4469229f2f993984b3950b422">Start Survey</a></td>
                            </tr>
                            <tr>
                                <td>FC Barcelona</td>
                                <td><a class="btn btn-primary btn-block" href="start.php?c=2c4cf657337835125bc4258d0e2e546af4185bdb70f64e1b0aa46d1d78017404">Start Survey</a></td>
                                <td><a class="btn btn-primary btn-block" href="start.php?c=21ef779311a43f0e067d0f4f600bb5451a8a7e093662086a1fe6a75d27d7892a">Start Survey</a></td>
                            </tr>
                            <tr>
                                <td>Real Madrid</td>
                                <td><a class="btn btn-primary btn-block" href="start.php?c=64c212df34c66e6fe9fccbfebc8899c10584cfa1669c42a175d65db073b13bc0">Start Survey</a></td>
                                <td><a class="btn btn-primary btn-block" href="start.php?c=2af4dd48399a5cf64c23fc7933e11aaf6171d80001b4b1377498ae6056b1acbf">Start Survey</a></td>
                            </tr>
                            <tr>
                                <td>Arsenal FC</td>
                                <td><a class="btn btn-primary btn-block" href="start.php?c=392a52e4f77c40bf3321dc2feac356fac2a906a80c961748170af4ce2bce1e6a">Start Survey</a></td>
                                <td><a class="btn btn-primary btn-block" href="start.php?c=f65ccfbfec288565c1d414275985547799fde0ed286c85a50bd0ec5faa01d1ac">Start Survey</a></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>   
                    </div>
                     
                    
		    <br/>
		    <br/>
		    <br/>
                    <p>
                        The UW TEDS Soccer Research Project is a research from the University of Washington Information School testing a methodology to measure the usability of iOS (Apple) mobile soccer apps. We would really appreciate your evaluation feedback of the iOS mobile application of your team. Each team application has 2 scenario surveys.
                    </p>
		    <p>
			Please make sure you have the latest version of the your team's iOS app before doing your analysis. To do this, go to the app store and update to the latest version if it that option is available.
		    </p>
                    <p>
                        In this round of our study we are comparing four mobile soccer applications from around the world. This research is not funded by the four teams in this study or any other soccer organization. It is strictly an academic study evaluating and comparing mobile applications on iOS.
                    </p>
                    <p>
                      We are analyzing 2 scenarios in this study, "Player Information", and "Schedule, Results and League". We'd love your opinion on both scenarios if possible, but if you only do one, that's fine too. Each survey takes about 5 minutes to complete. We appreciate you taking the time and your valuable opinion. The first 25 users who complete both surveys will receive a $5 Amazon gift card. Thanks!
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
                        <i>The TEDS Team</i>
                    </p>

                    <?php
                    }
                    ?>

                </div>

            </div>
        </div>
    </body>
</html>
