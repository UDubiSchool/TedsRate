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
  </head>

  <body>

    <div id="wrapper">

        <?php
        include "nav_part.inc.php";
        ?>
      <div id="page-wrapper">

        <div class="row">
          <div class="col-lg-12">
            <h1>Dashboard <small>Completed Rating Progress</small></h1>
            <ol class="breadcrumb">
              <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
            </ol>
          </div>
        </div><!-- /.row -->

<?php
    // outer level query
    $query = "select CONCAT(upro.firstName, ' ', upro.lastName) as userName,
                upro.email as email,
                urp.completionDate as completeDate,
                pjt.projectTitle as pjtTitle,
                a.artifactTitle as aTitle,
                urp.userRatingProgressID as urpID

                from userRatingProgress urp
                join userProfile upro on urp.userID = upro.userID
                join personae p on urp.personaID = p.personaeID
                join scenario s on urp.scenarioID = s.scenarioID
                join projectArtifact pa on urp.projectArtifactID = pa.projectArtifactID
                join project pjt on pjt.projectID = pa.projectID
                join artifact a on a.artifactID = pa.artifactID
                where urp.isComplete = 'true'
                ORDER BY completeDate DESC
                LIMIT 25
                ";

        $first_level_result = $dbq->prepare($query);
        $first_level_result->execute();
        while ($row = $first_level_result->fetch(PDO::FETCH_ASSOC)) {
?>

<?php
    // inner level query
        $inner_query = "select c.categoryTitle as cTitle,
                        ur.ratingID as ratingScore
                        from userRatingProgress urp
                        join userRating ur on urp.userRatingProgressID = ur.userRatingProcessID
                        join scenarioCategory sc on ur.scenarioCategoryID = sc.SC_ID
                        join category c on c.categoryID = sc.categoryID
                        where urp.userRatingProgressID = " . $row['urpID'];
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
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse_<?= $row['urpID'] ?>">
                                <?= $row['userName'] . " | " . $row['pjtTitle'] . " | " . $row['aTitle'] ?>
                                <span class="left-small"><?= " -- Completed at: " . $row['completeDate'] ?></span>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse_<?= $row['urpID'] ?>" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="half-table-wrapper">
                                <table class="tbl_first_half table table-bordered table-hover table-striped tablesorter">
                                    <tr>
                                        <th>Category</th>
                                        <th>User rating score</th>
                                    </tr>
                                    <?php
                                    for ($i = 0; $i < count($sec_first_half); $i++) {
                                        print "<tr class='data_wrapper'><td>" . $sec_first_half[$i]['cTitle'] . "</td>";
                                        print "<td>" . $sec_first_half[$i]['ratingScore'] . "</td></tr>";
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
                                        print "<tr class='data_wrapper'><td>" . $sec_second_half[$i]['cTitle'] . "</td>";
                                        print "<td>" . $sec_second_half[$i]['ratingScore'] . "</td></tr>";
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




  <!-- Included JS Files -->
  <!-- template plugins -->
  <!-- JavaScript -->
    <script src="javascripts/jquery-1.10.2.js"></script>
    <script src="javascripts/bootstrap.js"></script>

    <!-- Page Specific Plugins -->
    <!-- // <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script> -->
    <!-- // <script src="http://cdn.oesmith.co.uk/morris-0.4.3.min.js"></script> -->
    <script src="javascripts/morris/chart-data-morris.js"></script>
    <script src="javascripts/tablesorter/jquery.tablesorter.js"></script>
    <script src="javascripts/tablesorter/tables.js"></script>
    <script src="javascripts/main.js"></script>
    <script src="javascripts/notice.js"></script>
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