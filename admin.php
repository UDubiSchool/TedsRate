<?php
    require_once "session_inc.php";
    require_once "header.inc.php";
?>
<link rel="stylesheet" type="text/css" href="css/ui-grid.min.css">

<div id="wrapper" ng-app="administrator" ng-controller="adminCtrl">
     <!-- Sidebar -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand" href="admin.php">TEDSRate Admin</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li ui-sref-active="active" class="">
                    <a href ui-sref="dashboard" class="disabled"><i class="fa fa-dashboard"></i> Dashboard</a>
                </li>
                <li ui-sref-active="active" class="">
                    <a href ui-sref="projects">Projects</a>
                </li>
                 <!--
                 <li class=""><a class="disabled" href="admin_rp.php"><i class="fa fa-file-text"></i> Assessments</a></li>
                 <li class=""><a class="disabled" href="admin_pjt_project.php">Projects</a></li>
                 <li class=""><a class="disabled" href="admin_pjt_atft.php">Artifacts</a></li>
                 <li class=""><a class="disabled" href="admin_pjt_scenario.php">Scenarios</a></li>
                 <li class=""><a class="disabled" href="admin_pjt_persona.php">Personas</a></li>
                 <li class=""><a class="disabled" href="admin_pjt_user.php">Users</a></li>
                 <li class=""><a class="disabled" href="admin_pjt_cate.php">Categories</a></li> -->
            </ul>
        <ul class="nav navbar-nav navbar-right navbar-user">
           <li>
               <a id="logout" href="logout.php">Log out</a>
           </li>
        </ul>
     </div><!-- /.navbar-collapse -->
    </nav>
     <div id=page-wrapper ui-view></div>
</div>

<script src="js/d3.min.js"></script>
<script src="js/nv.d3.min.js"></script>
<script src="js/angular/angular.min.js" type="text/javascript"></script>
<script src="js/angular/angular-animate.js" type="text/javascript"/></script>
<script src="js/angular/ui-bootstrap-tpls-1.1.2.min.js" type="text/javascript"/></script>
<script src="js/angular/angular-ui-router.min.js" type="text/javascript"/></script>
<script src="js/angular/elastic.js" type="text/javascript" /></script>
<script src="js/angular/validate.js" type="text/javascript" /></script>
<script src="js/angular/angular-cookies.js"></script>
<script src="js/angular/angular-bootstrap-lightbox.js"></script>
<script src="js/angular/ng-file-upload-shim.min.js"></script>
<script src="js/angular/ng-file-upload.min.js"></script>
<script src="js/angular/angular-nvd3.min.js"></script>
<script src="js/angular/angular-spinners.min.js"></script>
<script src="js/angular/ui-grid.min.js"></script>


<script src="js/angular/models/tedsModels.js"></script>
<script src="js/angular/common/directives/dropdown/dropdown.directive.js"></script>
<script src="js/angular/common/directives/filterList/filterList.directive.js"></script>
<script src="js/angular/common/directives/pivotTable/pivotTable.directive.js"></script>
<script src="js/angular/common/directives/angularPrint/angularPrint.js"></script>
<script src="js/angular/common/toArray.js"></script>
<script src="js/admin.js" type="text/javascript"></script>