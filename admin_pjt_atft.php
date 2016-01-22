<?php
    require_once "session_inc.php";
	require_once "header.inc.php";
	require_once "dbconnect.php";

	//set up some SQL statements
	$sql["language"] = 'SELECT * from language';

	try {
		$dbq = db_connect();

?>

<div id="wrapper">
	<?php
     	include "nav_part.inc.php";
	?>

	<div id="page-wrapper">
		<h1>Project Artifact Information</h1>
		<table id="pjt_atft_tbl" class="table table-bordered table-hover table-striped tablesorter">
			<thead>
              	<tr>
	                <th>Name</th>
	                <th>URL</th>
	                <th>Description</th>
              	</tr>
            </thead>
			<tbody>

				<?php
					$pre_result = $dbq->prepare("select artifactName, artifactURL, artifactDescription, artifactID from artifact");
					$pre_result->execute();
					while ($row = $pre_result->fetch(PDO::FETCH_ASSOC)) {
						// print_r($row);
						printf('<tr id="%s"><td>%s</td><td><a href="%s" target="blank">%s</a></td><td>%s</td></tr>',$row['artifactID'] , $row['artifactName'],urldecode($row['artifactURL']),urldecode($row['artifactURL']),$row['artifactDescription'] ? $row['artifactDescription'] : "No information provided");
					}
				?>
			</tbody>
		</table>

		<!-- adding new action -->
		<div class="action_wrapper">
			<div class="center-block"><button class="toggle btn btn-default">Add New Artifact</button></div>
			<div class="clearfix"></div>
			<div class="toggle-content center-block" style="display:none;">
				<form id="addProject" name="addProject" action="adminproc.php" method="post">
					<h2>Add Project Artifact(s)</h2>
<!--					<a class="addmore" href="#" id="addMoreArtifacts">Add Another Artifact</a>-->
					<div id="artifacts">
						<div class="addArtifact">
							<label for="artifactName[]">Artifact Title</label><input class="input-text form-control notEmpty" type="text" name="artifactName[]" />
							<label for="artifactURL[]">Artifact URL</label><input class="input-text form-control notEmpty" type="text" name="artifactURL[]" />
							<select name="projectID[]" class="form-control notEmpty">
								<?php
									//make languages select
									foreach ($dbq->query('select * from project') as $row) {
										printf('<option value="' . $row['projectID'] . '">' . $row['projectName'] . '</option>');
									}
								?>
							</select>
						</div>
					</div>
					<input type="hidden" name="source" value="atft" class="notEmpty">
					<input class="btn btn-success form-control form-button" type="submit">
				</form>
			</div>
		</div>

	</div>

    <?php
    // logout form
    require_once "logout_form.inc.php";
    ?>

</div>

<?php
		//close connection
		$dbq = NULL;
	} catch (PDOException $e) {
	     print ("getMessage(): " . $e->getMessage () . "\n");
	}
?>


<?php
     	$active = "Artifact";
     	include "footer.inc.php";
?>
<!-- include js files -->
	<script src="js/admin.js"></script>
