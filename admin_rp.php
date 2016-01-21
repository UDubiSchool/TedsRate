<?php
    require_once "session_inc.php";
    require_once "header.inc.php";
    require_once "dbconnect.php";

//set up some SQL statements
$sql["project"] = 'SELECT * from project';
$sql["project_atft"] = 'SELECT * FROM projectArtifact pa
                        join project p on p.projectID = pa.projectID
                        join artifact a on a.artifactID = pa.artifactID';
$sql["persona"] = 'select personaeID as perid, personaTitle as perTitle from personae';

try {
    $dbq = db_connect();

?>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.0-beta.1/angular.min.js" type="text/javascript"></script> -->
<script src="js/angular.min.js" type="text/javascript"></script>
<script src="js/app.js" type="text/javascript"></script>
<div id="wrapper">
     <?php
        include "nav_part.inc.php";
     ?>

      <div id="page-wrapper" ng-app="ratingsApp" ng-controller="MainController">

          <!-- notice info -->
          <div id="noticeInfo"></div>

        <!-- container -->
        <div id="sitecontainer" style="width:75%;">
            <h1>User Rating Progress Information</h1>
            <dl class="clearfix">
                <div class="col-xs-6">
                    <dt>Project</dt>
                    <dd>
                        <select class="form-control pull-left" name="project" ng-model="ratingOptions.project" id="">
                            <option value=""></option>
                            <option ng-repeat="option in projectOptions" value="{{option}}">{{option}}</option>
                        </select>
                    </dd>
                    <dt>Artifact</dt>
                    <dd>
                        <select class="form-control pull-left" name="project" ng-model="ratingOptions.artifact" id="">
                            <option value=""></option>
                            <option ng-repeat="option in artifactOptions" value="{{option}}">{{option}}</option>
                        </select>
                    </dd>
                    <dt>Persona</dt>
                    <dd>
                        <select class="form-control pull-left" name="project" ng-model="ratingOptions.persona" id="">
                            <option value=""></option>
                            <option ng-repeat="option in personaOptions" value="{{option}}">{{option}}</option>
                        </select>
                    </dd>
                </div>
                <div class="col-xs-6">
                    <dt>Scenario</dt>
                    <dd>
                        <select class="form-control pull-left" name="project" ng-model="ratingOptions.scenario" id="">
                            <option value=""></option>
                            <option ng-repeat="option in scenarioOptions" value="{{option}}">{{option}}</option>
                        </select>
                    </dd>
                    <dt>User</dt>
                    <dd>
                        <select class="form-control pull-left" name="project" ng-model="ratingOptions.userprofile" id="">
                            <option value=""></option>
                            <option ng-repeat="option in userOptions" value="{{option}}">{{option}}</option>
                        </select>
                    </dd>
<!--                     <dt>Complete</dt>
                    <dd>
                        <select class="form-control pull-left" name="project" ng-model="ratingOptions.complete" id="">
                            <option value="null"></option>
                            <option value="true">True</option>
                        </select>
                    </dd> -->
                </div>
            </dl>

            <table ng-show="ratings" id="user_rating_progress_tbl" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Artifact</th>
                        <th>Persona</th>
                        <th>Scenario</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="rating in ratings | filter:ratingOptions">
                        <td>{{rating.project}}</td>
                        <td>{{rating.artifact}}</td>
                        <td>{{rating.persona}}</td>
                        <td>{{rating.scenario}}</td>
                        <td>{{rating.userprofile}}</td>
                        <td>
                            <div ng-if="rating.complete === 'true'">Completed at {{rating.completionDate}}</div>
                            <div ng-if="rating.complete !== 'true'">
                                <button class="email_sender btn btn-primary btn-sm" data-toggle="modal" data-target="#emailModal" data-email="{{rating.email}}" data-urpid="{{rating.urpID}}" onclick="readyModal($(this))">Send Invitation</button>
                            </div>
                        </td>
                        <td><div ng-show="rating.ratingUrl"><a ng-href="http://{{rating.ratingUrl}}" class="btn btn-primary" target="_blank"><i class="fa fa-pencil"></i></a></div></td>
                    </tr>
                </tbody>
            </table>


            <h1>Admin Form</h1>

            <form id="addProject" name="addProject" action="adminproc.php" method="post">

                <!--- Add Project -->
                <h2>1. Choose a Project</h2>
                <div id="project">
                    <select name="project" id="projectID" class="form-control notEmpty">
                        <option value="" disabled selected>Select your option</option>

                        <?php
                            //make project options
                            foreach ($dbq->query($sql["project"]) as $row) {
                                printf('<option value="' . $row['projectID'] . '">' . $row['projectTitle'] . '</option>');
                            }
                        ?>
                    </select>
                    <!-- link to add project -->
                    <a href="admin_pjt_project.php">Add New Project</a>
                </div>
                <div id="project_based_wrapper" style="display: none;" class="dependWrapper">
                    <h3>1.1 Choose Project Artifact</h3>
                    <select id="projectArtifactReceiver" class="form-control notEmpty" name="artifact">
                        <option value="" disabled selected>Select your option</option>
                    </select>
                    <a href="admin_pjt_atft.php">Add New Artifact</a>

                    <!--==========================================================================================-->
                </div>

                <!-- Add persona-based params -->
                <h2>2. Choose a Persona</h2>
                <select id="personaID" class="form-control notEmpty" name="persona">
                    <option value="" disabled selected>Select your option</option>
                    <?php
                    //make persona options
                    foreach ($dbq->query($sql["persona"]) as $row) {
                        printf('<option value="' . $row['perid'] . '">' . $row['perTitle'] . '</option>');
                    }
                    ?>
                </select>
                <a href="admin_pjt_p    ersona.php">Add New Persona</a>

                <div id="persona_based_wrapper" style="display: none;" class="dependWrapper">
                    <h3>2.1 Choose Scenario</h3>
                    <select id="personaScenarioReceiver" class="form-control notEmpty" name="scenario">
                        <option value="" disabled selected>Select your option</option>
                    </select>
                    <a href="admin_pjt_scenario.php">Add New Scenario</a>
                    <!-- seperate line -->
                    <h3>2.2 Choose User</h3>
                    <select id="personaUserReceiver" class="form-control notEmpty" name="user">
                        <option value="" disabled selected>Select your option</option>
                    </select>
                    <a href="admin_pjt_user.php">Add New User</a>
                </div>
                <!-- user rating progress / user rating process -->
                <input type="hidden" value="user_rating_progress" name="source" class="notEmpty">
                <!--==========================================================================================-->
                <div class="form-group">
                    <input type="submit" class="btn btn-success form-control form-button">
                </div>
            </form>
        </div>

          <?php
          // logout form
          require_once "logout_form.inc.php";
          ?>

      <div id="emailModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h4 class="modal-title" id="myModalLabel">Please confirm...</h4>
                  </div>
                  <div class="modal-body">
                      Please confirm: by clicking the "Send it!" button, this invitation
<!--                      <div class="rating_info_check"></div>-->
                      will be sent to the following user via his/her email:
                      <div class="email_check"></div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                      <button type="button" class="btn btn-primary" data-dismiss="modal" id="email_sender_confirm">Send it!</button>
                  </div>
              </div>
          </div>
      </div>

        <?php
            //close connection
            $dbq = NULL;
        } catch (PDOException $e) {
             print ("getMessage(): " . $e->getMessage () . "\n");
        }
        ?>

<!-- include js files -->
    <!-- // <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> -->
    <!-- // <script src="javascripts/foundation.js"></script> -->
    <!-- // <script src="js/app.js"></script> -->
    <script src="js/admin.js"></script>
    <script>
        $(function() {
//            console.log("triggered");
            AjaxHandler.init("#projectID", "#projectArtifactReceiver", "project_artifact");
            AjaxHandler.init("#personaID", "#personaScenarioReceiver", "persona_scenario");
            AjaxHandler.init("#personaID", "#personaUserReceiver", "persona_user");
        });
    </script>

    <?php
        $active = "Process";
        include "footer.inc.php";
     ?>


