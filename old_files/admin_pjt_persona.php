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
		<h1>Persona Information</h1>
		<table id="pjt_personae_tbl" class="table table-bordered table-hover table-striped tablesorter">
			<thead>
              	<tr>
	                <th>Name</th>
	               	<th>Description</th>
	               	<th>Language</th>
              	</tr>
            </thead>
			<tbody>

				<?php
					$pre_result = $dbq->prepare("select personaName, personaDesc, languageName from persona JOIN language ON language.LanguageID = persona.languageID");
					$pre_result->execute();
					while ($row = $pre_result->fetch(PDO::FETCH_ASSOC)) {
						// print_r($row);
						printf('<tr><td>%s</td><td>%s</td><td>%s</td></tr>', $row['personaName'],$row['personaDesc'] ? $row['personaDesc'] : "No information provided", $row['languageName']);
					}
				?>
			</tbody>
		</table>

		<!-- adding new action -->
		<div class="action_wrapper">
			<div class="center-block"><button class="toggle btn btn-default">Add New Persona</button></div>
			<div class="clearfix"></div>
			<div class="toggle-content center-block" style="display:none;">
				<form id="addProject" name="addProject" action="adminproc.php" method="post">
					<h2>Add Project Persona(s)</h2>
<!--					<a class="addmore" href="#" id="addMorePersonas">Add Another Persona</a>-->
					<div id="personas">
						<div class="addPersona">
							<label for="personaName[]">Persona Name</label><input class="input-text form-control notEmpty" type="text" name="personaTitle[]" />
							<label for="personaDesc[]">Persona Description</label><input class="input-text form-control notEmpty" type="text" name="personaDesc[]" />
						</div>
					</div>
					<input type="hidden" name="source" value="persona" class="notEmpty">
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


<!-- include js files -->
	<script src="js/admin.js"></script>

<?php
     	$active = "Persona";
     	include "footer.inc.php";
?>