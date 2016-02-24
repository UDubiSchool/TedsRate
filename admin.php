<?php
// ============================== authentication ===============================
    require_once "session_inc.php";
// ============================== authentication ===============================

    require_once "dbconnect.php";
    try {
    $dbq = db_connect();
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard - SB Admin</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Add custom CSS here -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <!-- Page Specific CSS -->
    <link rel="stylesheet" href="http://cdn.oesmith.co.uk/morris-0.4.3.min.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/jquery-1.11.0.min.js"></script>

  </head>

  <body>

    <div id="wrapper">

        <?php
        include "nav_part.inc.php";
        ?>
      <div id="page-wrapper">

        <div class="row">
          <div class="col-lg-12">
            <h1>Dashboard <small>Completed Assessments</small></h1>
            <ol class="breadcrumb">
              <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
            </ol>
          </div>
        </div><!-- /.row -->

<?php
    // outer level query
    $query = "SELECT CONCAT(u.firstName, ' ', u.lastName) AS userName,
                u.email AS email,
                ass.completionDate AS completeDate,
                pjt.projectName AS projectName,
                a.artifactName AS artifactName,
                ass.assessmentID

                FROM assessment ass
                LEFT JOIN user u ON ass.userID = u.userID
                LEFT JOIN configuration con ON con.configurationID = ass.configurationID
                LEFT JOIN assessmentConfiguration ascon ON ascon.assessmentConfigurationID = con.assessmentConfigurationID
                LEFT JOIN persona p ON ascon.personaID = p.personaID
                LEFT JOIN scenario s ON ascon.scenarioID = s.scenarioID
                LEFT JOIN project pjt ON pjt.projectID = ascon.projectID
                LEFT JOIN artifact a ON a.artifactID = ascon.artifactID
                WHERE ass.completionDate IS NOT NULL
                ORDER BY completeDate DESC
                LIMIT 25
                ";

        $first_level_result = $dbq->prepare($query);
        $first_level_result->execute();
        while ($row = $first_level_result->fetch(PDO::FETCH_ASSOC)) {
?>

<?php
    // inner level query
        $inner_query = "SELECT a.attributeName AS cName,
                        r.ratingValue
                        FROM assessment ass
                        LEFT JOIN rating r ON ass.assessmentID = r.assessmentID
                        LEFT JOIN attribute a ON a.attributeID = r.attributeID
                        LEFT JOIN category c ON c.attributeID = a.attributeID
                        where ass.assessmentID = " . $row['assessmentID'];
        $stmt = $dbq->prepare($inner_query);
        $stmt->execute();
        $second_level_result = $stmt->fetchAll();
        $half = count($second_level_result) / 2;
        $sec_first_half = array_slice($second_level_result, 0, $half);
        $sec_second_half = array_slice($second_level_result, $half + 1);

?>

        <div class="row">
            <div class="panel-group urp_record_group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?= $row['assessmentID'] ?>">
                                <?= $row['userName'] . " | " . $row['projectName'] . " | " . $row['artifactName'] ?>
                                <span class="left-small"><?= " -- Completed at: " . $row['completeDate'] ?></span>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse_<?= $row['assessmentID'] ?>" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="half-table-wrapper">
                                <table class="tbl_first_half table table-bordered table-hover table-striped tablesorter">
                                    <tr>
                                        <th>Category</th>
                                        <th>User rating score</th>
                                    </tr>
                                    <?php
                                    for ($i = 0; $i < count($sec_first_half); $i++) {
                                        print "<tr class='data_wrapper'><td>" . $sec_first_half[$i]['cName'] . "</td>";
                                        print "<td>" . $sec_first_half[$i]['ratingValue'] . "</td></tr>";
                                    }
                                    ?>
                                </table>
                            </div>
                            <div class="half-table-wrapper">
                                <table class="tbl_second_half table table-bordered table-hover table-striped tablesorter">
                                    <tr>
                                        <th>Category</th>
                                        <th>User rating score</th>
                                    </tr>
                                    <?php
                                    for ($i = 0; $i < count($sec_second_half); $i++) {
                                        print "<tr class='data_wrapper'><td>" . $sec_second_half[$i]['cName'] . "</td>";
                                        print "<td>" . $sec_second_half[$i]['ratingValue'] . "</td></tr>";
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /accordion -->
        </div><!-- /.row -->
<?php
        }
?>


        <?php
            require_once "logout_form.inc.php";
        ?>
      </div><!-- /#page-wrapper -->
      <div id="noticeInfo"></div>
    </div><!-- /#wrapper -->

  <!-- /template -->




    <script src="js/bootstrap.js"></script>

    <!-- Page Specific Plugins -->
    <script src="js/main.js"></script>
    <script src="js/notice.js"></script>
  <!-- /template plugins -->
    <?php
        require_once "notice.inc.php";
    ?>
</body>
</html>
<?php
    } catch (PDOException $e) {
        print ("getMessage(): " . $e->getMessage () . "\n");
    }
?>