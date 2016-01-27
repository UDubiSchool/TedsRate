<?php
// ============================== authentication ===============================
    require_once "session_inc.php";
// ============================== authentication ===============================

    $thisFile = basename($_SERVER['PHP_SELF']);
    // echo $this;
?>
<!-- Sidebar -->
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <a class="navbar-brand" href="admin.php">TEDSRate Admin</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <ul class="nav navbar-nav side-nav">
            <li class="<?php if ($thisFile =='admin.php') { echo 'active';};  ?>"><a href="admin.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="<?php if ($thisFile =='admin_rp.php') { echo 'active';};  ?>"><a href="admin_rp.php"><i class="fa fa-file-text"></i> Assessments</a></li>
            <li class=""><a href="admin_pjt_project.php">Projects</a></li>
            <li class=""><a href="admin_pjt_atft.php">Artifacts</a></li>
            <li class=""><a href="admin_pjt_scenario.php">Scenarios</a></li>
            <li class=""><a href="admin_pjt_persona.php">Personas</a></li>
            <li class=""><a href="admin_pjt_user.php">Users</a></li>
            <li class=""><a href="admin_pjt_cate.php">Categories</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right navbar-user">
              <li>
                  <a id="logout">Log out</a>
              </li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </nav>

