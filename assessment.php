<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/assessment.css">
    <script src="js/angular.min.js" type="text/javascript"/></script>
    <script src="js/angular-animate.js" type="text/javascript"/></script>
    <script src="js/ui-bootstrap-tpls-1.1.2.min.js" type="text/javascript"/></script>
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="js/elastic.js"></script>
    <script src="js/assessment.js"></script>
</head>
<body>
    <input type="hidden" id='asid' name='asid' value="<?php echo $_GET['asid']?>">
    <form action="finish.php">
        <div class="container-fluid" ng-app="assessmentApp" ng-controller="assessmentController">
            <div class="header"><h2>{{assessment.project.name}}</h2></div>
            <div id="panel-wrapper" class="clear">
                <div class="panel active col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                    <h3>Welcome</h3>
                    <p>This assessment is a component of the Purposeful Sampling Research Project at the University of Washington Information School. The work group's aim is to refine a methodology to measure the usability of content and information artifacts in mobile applications, specifically the usability of professional sport team mobile applications, based on the Taylor-Eisenberg-Dirks-Scholl (TEDS) information artifact value factorization framework. The next page will provide you an overview of the information usability factors that we are studying. </p>
                    <p>Thank you in advance for taking the time to take part in this evaluation!</p>
                    <p>The TEDS Purposeful Sampling Research Group</p>
                    <div class="navigation"><a class="btn btn-block btn-primary next" ng-click="next()">Start</a></div>
                </div>

                <div ng-if="!assessment.user.email " class="panel hidden col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 user-panel">
                    <h3>Hello New User</h3>
                    <h5>Please complete your <a href="" ng-click="signin = false">registration</a> or <a href="" ng-click="signin = true">sign in</a></h5>

                    <div ng-if="!signin" id="signup">
                        <p>Please provide us with an email and password so that we may track you across individual ratings.</p>
                        <div class="form-group">
                            <input class="form-control" type="text" ng-model="signup.email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="password" name="password" ng-model="signup.password" placeholder="Password">
                            <input class="form-control" type="password" name="confirm" ng-model="signup.confirm" placeholder="Confirm">
                        </div>
                    </div>
                    <div ng-if="signin" id="signin" >
                        <div class="form-group">
                            <input class="form-control" type="text" ng-model="signin.email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="password" name="password" ng-model="signin.password" placeholder="Password">
                        </div>
                    </div>
                    <div class="navigation"><a class="btn btn-primary prev" ng-click="prev()">Back</a><a class="btn btn-primary next" ng-click="next()">Continue</a></div>
                </div>

                <!-- BEGIN QUESTION PANELS -->
                <div ng-if="assessment.questions.demographic" class="panel hidden col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 demographic-panel">
                    <h3>User Questions</h3>
                    <div  ng-repeat="question in assessment.questions.demographic" class="question" question-template></div>
                    <div class="navigation"><a class="btn btn-primary prev" ng-click="prev()">Back</a><a class="btn btn-primary next" ng-click="next()">Continue</a></div>
                </div>

                <div ng-if="assessment.questions.project" class="panel hidden col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 project-panel">
                    <h3>Project Questions</h3>
                    <div  ng-repeat="question in assessment.questions.project" class="question" question-template></div>
                    <div class="navigation"><a class="btn btn-primary prev" ng-click="prev()">Back</a><a class="btn btn-primary next" ng-click="next()">Continue</a></div>
                </div>

                <div ng-if="assessment.questions.artifact" class="panel hidden col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 artifact-panel">
                    {{assessment.questions.artifact}}
                    <h3>Artifact Questions</h3>
                    <div  ng-repeat="question in assessment.questions.artifact" class="question" question-template></div>
                    <div class="navigation"><a class="btn btn-primary prev" ng-click="prev()">Back</a><a class="btn btn-primary next" ng-click="next()">Continue</a></div>
                </div>

                <div ng-if="assessment.questions.scenario" class="panel hidden col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 scenario-panel">
                    {{assessment.questions.scenario}}
                    <h3>Scenario Questions</h3>
                    <div  ng-repeat="question in assessment.questions.scenario" class="question" question-template></div>
                    <div class="navigation"><a class="btn btn-primary prev" ng-click="prev()">Back</a><a class="btn btn-primary next" ng-click="next()">Continue</a></div>
                </div>
                <!-- END QUESTION PANELS -->

                <!-- BEGIN ATTRIBUTE PANELS -->
                <div ng-repeat="criterion in assessment.criteria" class="panel hidden col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 attribute-panel">
                    <h3>{{criterion.criterionName}}</h3>
                    <p>{{criterion.criterionDesc}}</p>
                    <div ng-repeat="attribute in criterion.attributes"class="attribute clearfix">
                        <h4>{{attribute.attributeName}}</h4>
                        <p>{{attribute.attributeDesc}}</p>
                        <p ng-if="attribute.attributeTypeName == 'Cluster'">
                            This ranking refers to the information artifacts' effectiveness evaluated on the aspects of:
                            <span ng-repeat="category in attribute.categories"> <a uib-popover="{{category.attributeDesc}}" popover-trigger="outsideClick">{{category.attributeName}}</a>{{$last ? '' : ', '}}</span>
                        </p>

                        <div ng-if="assessment.configuration.uiConfiguration.ratingStyle == 'Likert'" class="col-xs-12">

                            <div class="col-xs-11 center-block clearfix likert">
                                <div class="col-xs-3"></div>
                                <div class="col-xs-1">1</div>
                                <div class="col-xs-1">2</div>
                                <div class="col-xs-1">3</div>
                                <div class="col-xs-1">4</div>
                                <div class="col-xs-1">5</div>
                                <div class="col-xs-3"></div>
                            </div>
                            <div class="col-xs-11 center-block clearfix likert">
                                <div class="col-xs-3">
                                    {{attribute.preface}}
                                </div>
                                <div class="col-xs-1">
                                    <label class="btn checkbox" ng-model="attribute.ratingValue" uib-btn-radio="'1'"></label>
                                </div>
                                <div class="col-xs-1">
                                    <label class="btn checkbox" ng-model="attribute.ratingValue" uib-btn-radio="'2'"></label>
                                </div>
                                <div class="col-xs-1">
                                    <label class="btn checkbox" ng-model="attribute.ratingValue" uib-btn-radio="'3'"></label>
                                </div>
                                <div class="col-xs-1">
                                    <label class="btn checkbox" ng-model="attribute.ratingValue" uib-btn-radio="'4'"></label>
                                </div>
                                <div class="col-xs-1">
                                    <label class="btn checkbox" ng-model="attribute.ratingValue" uib-btn-radio="'5'"></label>
                                </div>
                                <div class="col-xs-3">
                                    {{attribute.postface}}
                                </div>
                            </div>
                        </div>

                        <div ng-if="assessment.configuration.uiConfiguration.ratingStyle == 'Text'">
                            Text Box
                        </div>

                        <div class="comment">
                            <h4>Notes</h4>
                            <textarea name="" id="" ng-model="attribute.comment" cols="" rows="1" placeholder="Place any explanation here" msd-elastic>{{attribute.comment}}</textarea>
                        </div>
                    </div>
                    <div class="navigation"><a class="btn btn-primary prev" ng-click="prev()">Back</a><a class="btn btn-primary next" ng-click="($last && save()) || next()">{{$last ? "Finish": "Continue"}}</a></div>
                </div>
                <!-- END ATTRIBUTE PANELS -->

                <div class="panel hidden col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 finished-panel">
                    <h3>Thank you for completing our survey</h3>
                    <div class="navigation"><a class="btn btn-primary prev" ng-click="prev()">Back</a></div>
                </div>

            </div> <!-- END PANEL WRAPPER -->

        </div> <!-- END CONTAINER FLUID -->
    </form>
</body>

