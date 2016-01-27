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
        <h1>Category Information</h1>
        <table id="pjt_cate_tbl" class="table table-bordered table-hover table-striped tablesorter">
            <thead>
            	<tr>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Criteria</th>
                        <th>Language</th>
            	</tr>
            </thead>
            <tbody>

            <?php
            $pre_result = $dbq->prepare("SELECT attributeName, attributeDesc, criterionName, languageName
                FROM category c
                LEFT JOIN attribute a ON a.attributeID = c.attributeID
                LEFT JOIN criterion ON criterion.criterionID = a.criterionID
                LEFT JOIN language ON language.languageID = a.languageID");
            $pre_result->execute();
            while ($row = $pre_result->fetch(PDO::FETCH_ASSOC)) {
            // print_r($row);
            echo "<tr><td>$row[attributeName]</td><td>$row[attributeDesc]</td><td>$row[criterionName]</td><td>$row[languageName]</td></tr>";
            }
            ?>
            </tbody>
        </table>
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

<?php
     	$active = "Categories";
     	include "footer.inc.php";
?>