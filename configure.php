<?php
    require_once "session_inc.php";
    require_once "header.inc.php";
?>
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

<script src="js/angular/models/tedsModels.js"></script>
<script src="js/angular/common/directives/dropdown/dropdown.directive.js"></script>
<script src="js/angular/common/directives/filterList/filterList.directive.js"></script>
<script src="js/angular/common/directives/pivotTable/pivotTable.directive.js"></script>
<script src="js/angular/common/directives/angularPrint/angularPrint.js"></script>
<link src="js/angular/common/directives/angularPrint/angularPrint.css"></link>
<script src="js/angular/common/toArrayFilter.js"></script>
<script src="js/configure.js" type="text/javascript"></script>
<div id="wrapper" ng-app="administrator" ng-controller="adminCtrl">
     <?php
        include "nav_part.inc.php";
     ?>
     <div id=page-wrapper ui-view></div>
</div>