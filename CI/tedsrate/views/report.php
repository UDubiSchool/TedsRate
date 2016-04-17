<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>TedsRate Report</title>
    <link rel="stylesheet" href="../../../../../css/base.css">
    <!--[if lt IE 9]>
        <link rel="stylesheet" href="css/ie.css">
    <![endif]-->
    <!-- IE Fix for HTML5 Tags -->
    <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href="../../../../../css/bootstrap.css" rel="stylesheet">
    <!-- <link href="../../../../../css/sb-admin.css" rel="stylesheet"> -->
    <link href="../../../../../css/nv.d3.css" rel="stylesheet">
    <link href="../../../../../js/angular/common/directives/angularPrint/angularPrint.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../../../font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="../../../../../css/liam.css">
    <link rel="stylesheet" type="text/css" href="../../../../../css/main.css">

    <script src="../../../../../js/jquery-1.11.0.min.js"></script>
    <script src="../../../../../js/bootstrap.js"></script>
    <script src="../../../../../js/angular/angular.min.js" type="text/javascript"></script>

    <script src="../../../../../js/d3.min.js"></script>
    <script src="../../../../../js/nv.d3.min.js"></script>
    <!-- <script src="../../../../../js/angular/angular-animate.js" type="text/javascript"/></script> -->
    <script src="../../../../../js/angular/ui-bootstrap-tpls-1.1.2.min.js" type="text/javascript"/></script>
    <!-- <script src="../../../../../js/angular/angular-ui-router.min.js" type="text/javascript"/></script> -->
    <script src="../../../../../js/angular/elastic.js" type="text/javascript" /></script>
    <script src="../../../../../js/angular/validate.js" type="text/javascript" /></script>
    <script src="../../../../../js/angular/angular-cookies.js"></script>
    <script src="../../../../../js/angular/angular-bootstrap-lightbox.js"></script>
    <!-- <script src="../../../../../js/angular/ng-file-upload-shim.min.js"></script> -->
    <!-- <script src="../../../../../js/angular/ng-file-upload.min.js"></script> -->
    <script src="../../../../../js/angular/angular-nvd3.min.js"></script>

    <script src="../../../../../js/angular/models/tedsModels.js"></script>
    <script src="../../../../../js/angular/common/directives/dropdown/dropdown.directive.js"></script>
    <script src="../../../../../js/angular/common/directives/filterList/filterList.directive.js"></script>
    <script src="../../../../../js/angular/common/directives/pivotTable/pivotTable.directive.js"></script>
    <script src="../../../../../js/angular/common/directives/angularPrint/angularPrint.js"></script>
    <script src="../../../../../js/angular/common/toArray.js"></script>
    <script src="../../../../../js/report.js" type="text/javascript"></script>


    <base href="/tedsrate/tedsrate/">
    <!-- <base href="/"> -->
</head>
<body>
    <style>
        body {
            background-color: #eaeaea;
            height:100vh;
        }

        .header {
            /*position:fixed;*/
            /*z-index: 1000;*/
            /*width:100%;*/
            margin:0px 0px;
            padding:5px 40px;
            box-shadow: 0px 3px 3px #ccc;
            border-bottom:1px solid #ddd;
            background: #fff;
            position: relative;
            z-index: 2;

        }

        .center-block {
            float:none;
            margin:auto;
            /*text-align: center;*/
        }

        .content-wrapper {
            background-color: #fff;
            padding:25px;
            postion:relative;
            z-index: 1;
            /*top:-10px;*/
        }
    </style>

    <div ng-app="report" ng-controller="reportCtrl" print-section>
        <div class="header">
            <h2>TedsRate Report for {{reportData.details.project.name}}</h2>
        </div>
        <div class="col-xs-10 center-block content-wrapper">
            <h1 ng-if="reportData.details.item.url">
                <a ng-href="reportData.details.item.url">
                    {{reportData.details.item.name}}
                </a>
            </h1>
            <h1 ng-if="!reportData.details.item.url">
                {{reportData.details.item.name}}
            </h1>
            <h3>{{reportData.details.item.desc}}</h3>

            <teds-pivot-table class="clearfix" teds-data="reportData.stats" teds-options="pivotOptions" teds-passback="passback">
              <div ng-show="passback.row">
                <h3>{{passback.row.name}}</h3>
                <h5>{{passback.row.desc}}</h5>
                <h4>Comments</h4>
                <ul class="list-unstyled">
                  <li ng-repeat="comment in passback.row.comments">
                    {{comment.comment}}
                  </li>
                </ul>

                <h4>Screenshots</h4>
                <div class="col-xs-3" ng-repeat="screenshot in passback.row.screenshots">
                    <a ng-click="Lightbox.openModal(passback.row.screenshots, $index)">
                      <img ng-src="{{screenshot.screenshotPath}}" class="col-xs-12 img-thumbnail" alt="">
                    </a>
                </div>
              </div>
              <div class="divider" ng-show="passback.column && passback.row"></div>
              <div ng-show="passback.column">
                <h3>{{passback.column.name}}</h3>
                <h5>{{passback.column.desc}}</h5>
                <h4>Questions</h4>
                <div class="col-sm-6" ng-repeat="question in passback.column.questions">
                  <p>{{question.name}}</p>
                  <h5>{{question.desc}}</h5>
                  <nvd3 options="sampleChartOptions" data="question.chartData"></nvd3>
                </div>
              </div>
            </teds-pivot-table>
        </div>
    </div>



    <script>
        var reportData = <?php echo json_encode($final, true)?>;
    </script>
</body>
