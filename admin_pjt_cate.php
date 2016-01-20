<?php
    require_once "session_inc.php";
	require_once "header.inc.php";
	require_once "dbconnect.php";

	//set up some SQL statements
	$sql["language"] = 'SELECT * from languages';

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
            $pre_result = $dbq->prepare("select categoryTitle, categoryDescription, criteriaName, languageTitle
                FROM category
                JOIN criteria ON criteria.criteriaID = category.criteriaID
                JOIN languages ON languages.languageID = category.categoryLanguage");
            $pre_result->execute();
            while ($row = $pre_result->fetch(PDO::FETCH_ASSOC)) {
            // print_r($row);
            echo "<tr><td>$row[categoryTitle]</td><td>$row[categoryDescription]</td><td>$row[criteriaName]</td><td>$row[languageTitle]</td></tr>";
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
     	$active = "Category";
     	include "footer.inc.php";
?>