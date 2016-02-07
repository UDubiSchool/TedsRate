<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/assessment.css">
    <script src="js/angular.min.js" type="text/javascript"/></script>
    <script src="js/angular-animate.js" type="text/javascript"/></script>
    <script src="js/ui-bootstrap-1.1.2.min.js" type="text/javascript"/></script>
    <script src="js/jquery-1.11.0.min.js"></script>
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
                        <input class="form-control" type="text" ng-model="signup.email" placeholder="email">
                        <input class="form-control" type="password" name="password" ng-model="signup.password" placeholder="password">
                        <input class="form-control" type="password" name="confirm" ng-model="signup.confirm" placeholder="confirm">
                    </div>
                    <div ng-if="signin" id="signin" >
                        <input class="form-control" type="text" ng-model="signin.email" placeholder="email">
                        <input class="form-control" type="password" name="password" ng-model="signin.password" placeholder="password">
                    </div>
                    <div class="navigation"><a class="btn btn-primary prev" ng-click="prev()">Back</a><a class="btn btn-primary next" ng-click="next()">Continue</a></div>
                </div>

                <div ng-if="assessment.questions.demograpic " class="panel hidden col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 demographic-panel">
                    <h3>User Questions</h3>
                    <div  ng-repeat="question in assessment.questions.demographic" class="question">
                        <h4>{{question.questionName}}</h4>
                        <p>{{question.questionDesc}}</p>
                        <div ng-if="question.questionData.questionType == 'Boolean'">
                            <div class="col-xs-8 center-block clearfix">
                                <div class="col-xs-3">
                                    {{question.Boolean.false}}
                                </div>
                                <div class="col-xs-1">
                                    <label class="btn checkbox" ng-model="attribute.ratingValue" uib-btn-radio="'false'"></label>
                                </div>
                                <div class="col-xs-1">
                                    <label class="btn checkbox" ng-model="attribute.ratingValue" uib-btn-radio="'true'"></label>
                                </div>
                                <div class="col-xs-3">
                                    {{question.Boolean.true}}
                                </div>
                            </div>
                        </div>
                        <div ng-if="question.questionData.questionType == 'Radio'">

                        </div>
                        <div ng-if="question.questionData.questionType == 'Text'">

                        </div>
                        <div ng-if="question.questionData.questionType == 'Select'">

                        </div>
                        <div ng-if="question.questionData.questionType == 'Check'">

                        </div>
                    </div>
                    <div class="navigation"><a class="btn btn-primary prev" ng-click="prev()">Back</a><a class="btn btn-primary next" ng-click="next()">Continue</a></div>
                </div>

                <div ng-if="assessment.questions.project " class="panel hidden col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 project-panel">
                    <h3>Project Questions</h3>


                    <div  ng-repeat="question in assessment.questions.project" class="question">
                        <h4>{{question.questionName}}</h4>
                        <p>{{question.questionDesc}}</p>
                        <div ng-if="question.questionData.questionType == 'Boolean'" class="clearfix">
                            <div class="col-xs-3 col-xs-offset-2 clearfix Boolean">
                                <div class="col-xs-3">
                                    {{question.questionData.Boolean.false}}
                                </div>
                                <div class="col-xs-3">
                                    <label class="btn checkbox" ng-model="question.response" uib-btn-radio="'false'"></label>
                                </div>
                                <div class="col-xs-3">
                                    <label class="btn checkbox" ng-model="question.response" uib-btn-radio="'true'"></label>
                                </div>
                                <div class="col-xs-3">
                                    {{question.questionData.Boolean.true}}
                                </div>
                            </div>
                        </div>
                        <div ng-if="question.questionData.questionType == 'Radio'" class="clearfix">
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
                                    {{question.questionData.Radio.preface}}
                                </div>
                                <div class="col-xs-1">
                                    <label class="btn checkbox" ng-model="question.response" uib-btn-radio="'1'"></label>
                                </div>
                                <div class="col-xs-1">
                                    <label class="btn checkbox" ng-model="question.response" uib-btn-radio="'2'"></label>
                                </div>
                                <div class="col-xs-1">
                                    <label class="btn checkbox" ng-model="question.response" uib-btn-radio="'3'"></label>
                                </div>
                                <div class="col-xs-1">
                                    <label class="btn checkbox" ng-model="question.response" uib-btn-radio="'4'"></label>
                                </div>
                                <div class="col-xs-1">
                                    <label class="btn checkbox" ng-model="question.response" uib-btn-radio="'5'"></label>
                                </div>
                                <div class="col-xs-3">
                                    {{question.questionData.Radio.postface}}
                                </div>
                            </div>
                        </div>
                        <div ng-if="question.questionData.questionType == 'Text'" class="clearfix">
                            <input class="form-control" type="text" ng-model="question.response" placeholder="{{question.questionData.Text.placeholder}}" ng-value="{{question.response}}">
                        </div>
                        <div ng-if="question.questionData.questionType == 'Select'" class="clearfix">
                            <select class="form-control col-sm-6" ng-model="question.response">
                                <option value=""></option>
                                <option ng-repeat="option in question.questionData.Select.options" ng-value="option">{{option}}</option>
                            </select>
                        </div>
                        <div ng-if="question.questionData.questionType == 'Check'" class="clearfix">
                            <p>Check all that apply</p>
                            <div ng-repeat="check in question.questionData.Check.options" class="col-md-6">
                                {{check}}
                                <label class="btn checkbox" ng-model="question.response[check]" uib-btn-checkbox btn-checkbox-true="1" btn-checkbox-false="0"></label>
                            </div>
                        </div>
                    </div>



                    <div class="navigation"><a class="btn btn-primary prev" ng-click="prev()">Back</a><a class="btn btn-primary next" ng-click="next()">Continue</a></div>
                </div>

                <div ng-repeat="criterion in assessment.criteria" class="panel hidden col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 attribute-panel">
                    <h3>{{criterion.criterionName}}</h3>
                    <p>{{criterion.criterionDesc}}</p>
                    <div ng-repeat="attribute in criterion.attributes"class="attribute clearfix">
                        <h4>{{attribute.attributeName}}</h4>
                        <p>{{attribute.attributeDesc}}</p>
                        <div ng-if="assessment.configuration.uiConfiguration.ratingStyle == 'Likert'" class="col-xs-12">

                            <div class="col-xs-11 center-block clearfix">
                                <div class="col-xs-3"></div>
                                <div class="col-xs-1">1</div>
                                <div class="col-xs-1">2</div>
                                <div class="col-xs-1">3</div>
                                <div class="col-xs-1">4</div>
                                <div class="col-xs-1">5</div>
                                <div class="col-xs-3"></div>
                            </div>
                            <div class="col-xs-11 center-block clearfix">
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
                    </div>
                    <div class="navigation"><a class="btn btn-primary prev" ng-click="prev()">Back</a><a class="btn btn-primary next" ng-click="next()">Continue</a></div>
                </div>

            </div> <!-- END PANEL WRAPPER -->

        </div> <!-- END CONTAINER FLUID -->
    </form>
</body>

