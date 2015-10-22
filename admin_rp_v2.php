<?php
    require_once "session_inc.php";
    require_once "header.inc.php";
  
/*
  require_once "dbconnect.php";

//set up some SQL statements
$sql["project"] = 'SELECT * from project';
$sql["project_atft"] = 'SELECT * FROM projectArtifact pa
                        join project p on p.projectID = pa.projectID
                        join artifact a on a.artifactID = pa.artifactID';
$sql["persona"] = 'select personaeID as perid, personaTitle as perTitle from personae';

try {
	$dbq = db_connect();
	
	*/
	
?>

<div id="wrapper">
     <?php
     	include "nav_part.inc.php"; 
     ?>

      <div id="page-wrapper">

          <!-- notice info -->
          <div id="noticeInfo"></div>

		<!-- container -->
		<div id="sitecontainer" style="width:900px;">
            <h1>User Rating Progress Information</h1>
            <table id="user_rating_progress_tbl" class="table table-bordered table-hover table-striped tablesorter">
                <thead>
                <tr>
                    <th>Project</th>
                    <th>Artifact</th>
                    <th>Persona</th>
                    <th>Scenario</th>
                    <th>User</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>

                <?php
	/*
		Place code to connect to your DB here.
	*/
	//include('dbconnect.php');	// include your code to connect to DB.

	$conn = new mysqli( 'tedsrate.ovid.u.washington.edu', 'root', 'dongh3d3long', 'artifactRating2' );
	
	$tbl_name="userRatingProgress";		//your table name
	// How many adjacent pages should be shown on each side?
	$adjacents = 5;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	$query = "SELECT COUNT(*) as num FROM $tbl_name";
	
	$total_pages = $conn->query($query);
	
	//echo $total_pages;
	
	/* Setup vars for query. */
	$targetpage = "admin_rp_v2.php"; 	//your file name  (the name of this file)
	$limit = 10; 								//how many items to show per page
	$page = $_GET['page'];
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
	
	/* Get data. */
	$sql = "SELECT pjt.projectTitle as project, a.artifactTitle as artifact,
                                             p.personaTitle as persona, s.scenarioTitle as scenario,
                                             CONCAT(upro.firstName, ' ', upro.lastName) as userprofile,
                                             urp.isComplete as complete,
                                             urp.completionDate as completionDate,
                                             upro.email as email,
                                             urp.userRatingProgressID as urpID
                                             FROM userRatingProgress urp
                                             join userProfile upro on urp.userID = upro.userID
                                             join userPersonae uper on uper.userID = upro.userID
                                             join personae p on uper.personaeID = p.personaeID
                                             join personaScenario ps on p.personaeID = ps.personaID
                                             join scenario s on ps.scenarioID = s.scenarioID
                                             join projectArtifact pa on urp.projectArtifactID = pa.projectArtifactID
                                             join project pjt on pjt.projectID = pa.projectID
                                             join artifact a on a.artifactID = pa.artifactID
                                             where upro.userID = urp.userID
                                             and p.personaeID = urp.personaID
                                             and s.scenarioID = urp.scenarioID order by completionDate DESC LIMIT $start, $limit";
	$result = $conn->query($sql);
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"$targetpage?page=$prev\">� previous</a>";
		else
			$pagination.= "<span class=\"disabled\">� previous</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"$targetpage?page=$next\">next �</a>";
		else
			$pagination.= "<span class=\"disabled\">next �</span>";
		$pagination.= "</div>\n";		
	}
?>

	<?php
		while($row = mysql_fetch_array($result))
		{
	
		  while ($row = $pre_result->fetch(PDO::FETCH_ASSOC)) {
                    // print_r($row);
                    // $languageID = $row['scenarioLanguageID'];
                    // $lan_re = mysql_query("select languageTitle from languages where languageID = ".(string)$row['scenarioLanguageID']);
                    // print($lan_re);
                    printf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
                            $row['project'],$row['artifact'],$row['persona'],$row['scenario'], $row['userprofile'],
                            $row['complete'] ? "Completed at " . $row['completionDate'] :
                            "<button class='email_sender btn btn-primary btn-sm' data-target='#emailModal' data-email='" . $row['email'] .
                            "' data-urpid='" . $row['urpID'] . "'>Send Invitation</button>"
                    );
                }
	
		}
	?>

<?=$pagination?>
	
                </tbody>
            </table>


			<h1>Admin Form</h1>

			<form id="addProject" name="addProject" action="adminproc.php" method="post">

				<!--- Add Project -->
				<h2>1. Choose a Project</h2>
				<div id="project">
				    <select name="project" id="projectID" class="form-control notEmpty">
                        <option value="" disabled selected>Select your option</option>
				
                        <?
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
                    <?
                    //make persona options
                    foreach ($dbq->query($sql["persona"]) as $row) {
                        printf('<option value="' . $row['perid'] . '">' . $row['perTitle'] . '</option>');
                    }
                    ?>
                </select>
                <a href="admin_pjt_persona.php">Add New Persona</a>

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

          <?
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


<!-- include js files -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="javascripts/modernizr.foundation.js"></script>
	<script src="javascripts/foundation.js"></script>
	<script src="javascripts/app.js"></script>
	<script src="javascripts/admin.js"></script>
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


