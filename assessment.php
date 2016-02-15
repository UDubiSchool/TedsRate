<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/angular-bootstrap-lightbox.css">
        <link rel="stylesheet" href="css/assessment.css">
        <script src="js/jquery-1.11.0.min.js" type="text/javascript"/></script>
        <script src="js/angular.min.js" type="text/javascript"/></script>
        <script src="js/angular-animate.js" type="text/javascript"/></script>
        <script src="js/ui-bootstrap-tpls-1.1.2.min.js" type="text/javascript"/></script>
        <script src="js/elastic.js" type="text/javascript" /></script>
        <script src="js/validate.js" type="text/javascript" /></script>
        <script src="js/angular-cookies.js"></script>
        <script src="js/angular-bootstrap-lightbox.js"></script>
        <script src="js/assessment.js"></script>
        <base href="/">
    </head>
    <body>
        <input type="hidden" id='asid' name='asid' value="<?php echo $_GET['asid']?>">
        <div class="container-fluid clearfix" ng-app="assessmentApp" ng-controller="assessmentController">
            <div id="header" class="header"><h1>{{assessment.project.name}} - {{assessment.scenario.name}}</h1></div>
            <div id="alert-wrapper">
                <uib-alert ng-repeat="alert in alerts" type="{{alert.type}}" close="closeAlert($index)">{{alert.msg}}</uib-alert>
            </div>
            <div id="panel-wrapper" class="clearfix">

                <div class="panel active col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                    <h2>Welcome</h2>
                    <p>This assessment is a component of the Purposeful Sampling Research Project at the University of Washington Information School. The work group's aim is to refine a methodology to measure the usability of content and information artifacts in mobile applications, specifically the usability of professional sport team mobile applications, based on the Taylor-Eisenberg-Dirks-Scholl (TEDS) information artifact value factorization framework. The next page will provide you an overview of the information usability factors that we are studying. </p>
                    <p>Thank you in advance for taking the time to take part in this evaluation!</p>
                    <p>The TEDS Purposeful Sampling Research Group</p>
                    <div class="navigation">
                        <a class="btn btn-block btn-primary next" ng-click="next()" ng-disabled="!ready" scroll scroll-target="#header">{{!ready ? "Loading..." : "Start"}}</a>
                    </div>
                </div>

                <div ng-if="!userValidated" class="panel hidden col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 user-panel">
                    <h2>Hello {{assessment.user.email ? 'Returning' : "New"}} User</h2>
                    <h5 ng-if="assessment.user.email">The user associated with this assessment has already been registered. Please sign in to access the rest of the survey.</h5>
                    <h5 ng-if="!assessment.user.email">Please complete your <a ng-click="$parent.showSignin = false">registration</a> or, if you have already registered with us before, <a ng-click="$parent.showSignin = true">sign in</a></h5>

                    <div ng-if="!showSignin" id="signup">
                        <p>Please provide us with an email and password so that we may track you across individual ratings.</p>
                        <form novalidate name='signupForm' ng-submit="addUser(signupForm) && signupForm.$valid && signupForm.$dirty">

                            <div class="form-group">
                                <div role="alert">
                                      <span class="error" ng-show="signupForm.email.$error.required && !signupForm.$pristine">
                                        An email is required!</span>
                                      <span class="error" ng-show="signupForm.email.$error.email">
                                        Not valid email!</span>
                                </div>
                                <input class="form-control" type="email" name="email" ng-model="signup.email" placeholder="Email" required ng-model-options="{ debounce: 250 }">
                            </div>

                            <div class="form-group">
                                <div role="alert">
                                      <span class="error" ng-show="signupForm.confirm.$error.mismatch && !signupForm.$pristine">
                                        Passwords do not match.
                                        </span>
                                </div>
                                <input class="form-control" type="password" name="password" ng-model="signup.password" placeholder="Password" required ng-model-options="{ debounce: 150 }">
                                <input class="form-control" type="password" name="confirm" ng-model="signup.confirm" placeholder="Confirm" required ui-validate="{ mismatch: '$value!==password' }"
                        ui-validate-watch=" 'password' " ng-model-options="{ debounce: 150 }">
                            </div>
                            <input class="hidden" type="submit" value="Sign Up">

                            <div class="navigation">
                                <div class="col-xs-2">
                                    <a class="btn btn-block btn-primary prev" ng-click="prev()" scroll scroll-target="#header">Back</a>
                                </div>
                                <div class="col-xs-8"></div>
                                <div class="col-xs-2">
                                    <a class="btn btn-block btn-primary next" ng-click="addUser(signupForm)" ng-disabled="signupForm.$pristine || signupForm.$invalid" scroll scroll-target="#header">Sign Up</a>
                                </div>
                            </div>
                        </form>

                    </div>



                    <div ng-if="showSignin" id="signin" >
                        <form novalidate name='signinForm' ng-submit="validateUser(signinForm)">

                            <div class="form-group">
                                <div role="alert">
                                  <span class="error" ng-show="signinForm.email.$error.required && !signinForm.$pristine">
                                    An email is required!</span>
                                  <span class="error" ng-show="signinForm.email.$error.email">
                                    Not valid email!</span>
                                    <span class="error" ng-show="signinForm.$error.notFound">
                                    Invalid email/password combination!</span>
                                </div>
                                <input class="form-control" type="email" name="email" ng-model="signin.email" placeholder="Email" required ng-model-options="{ debounce: 200 }">
                            </div>

                            <div class="form-group">
                                <input class="form-control" type="password" name="password" ng-model="signin.password" placeholder="Password" required>
                            </div>
                            <input class="hidden" type="submit" value="Log In">
                            <div class="navigation">
                                <div class="col-xs-2">
                                    <a class="btn btn-block btn-primary prev" ng-click="prev()" scroll scroll-target="#header">Back</a>
                                </div>
                                <div class="col-xs-8"></div>
                                <div class="col-xs-2">
                                    <a class="btn btn-block btn-primary next" ng-click="validateUser(signinForm)" ng-disabled="signinForm.$pristine || signinForm.$invalid" scroll scroll-target="#header">Log In</a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- BEGIN QUESTION PANELS -->
                <div ng-repeat="(key, group) in assessment.questions" ng-if="group" class="panel hidden col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 {{key}}-panel">
                    <h2>{{key | capitalize}} Questions</h2>
                    <h4>{{assessment[key].description}}</h4>
                    <div  ng-repeat="question in group" class="question" question-template></div>
                    <div class="navigation" panel-navigation></div>
                </div>
                <!-- END QUESTION PANELS -->

                <!-- BEGIN ATTRIBUTE PANELS -->
                <div ng-repeat="criterion in assessment.criteria" class="panel hidden col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 attribute-panel">
                    <h2>{{criterion.criterionName}}</h2>
                    <p>{{criterion.criterionDesc}}</p>
                    <div ng-repeat="(attributeKey, attribute) in criterion.attributes"class="attribute clearfix">
                        <h3>{{attribute.attributeName}}</h3>
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
                                    <label class="btn checkbox" ng-model="attribute.ratingValue" uib-btn-radio="'1'" ng-change="trackProgress(attribute.ratingValue, '{{attribute.ratingValue}}', true); save.rating(attribute)"></label>
                                </div>
                                <div class="col-xs-1">
                                    <label class="btn checkbox" ng-model="attribute.ratingValue" uib-btn-radio="'2'" ng-change="trackProgress(attribute.ratingValue, '{{attribute.ratingValue}}', true); save.rating(attribute)"></label>
                                </div>
                                <div class="col-xs-1">
                                    <label class="btn checkbox" ng-model="attribute.ratingValue" uib-btn-radio="'3'" ng-change="trackProgress(attribute.ratingValue, '{{attribute.ratingValue}}', true); save.rating(attribute)"></label>
                                </div>
                                <div class="col-xs-1">
                                    <label class="btn checkbox" ng-model="attribute.ratingValue" uib-btn-radio="'4'" ng-change="trackProgress(attribute.ratingValue, '{{attribute.ratingValue}}', true); save.rating(attribute)"></label>
                                </div>
                                <div class="col-xs-1">
                                    <label class="btn checkbox" ng-model="attribute.ratingValue" uib-btn-radio="'5'" ng-change="trackProgress(attribute.ratingValue, '{{attribute.ratingValue}}', true); save.rating(attribute)"></label>
                                </div>
                                <div class="col-xs-3">
                                    {{attribute.postface}}
                                </div>
                            </div>
                        </div>

                        <div ng-if="assessment.configuration.uiConfiguration.ratingStyle == 'Text'">
                            Text Box
                        </div>

                        <div class="comment form-group clearfix">
                            <h3>Notes</h3>
                            <textarea name="" id="" ng-model="attribute.comment" ng-change="save.comment(attribute)" ng-model-options="{updateOn: 'blur'}" cols="" rows="1" placeholder="Type optional comment(s) for this question here" msd-elastic>{{attribute.comment}}</textarea>
                        </div>
                        <div class="screenshots form-group clearfix">
                            <h3>Screenshots</h3>
                            <div class="col-sm-3">
                                <input type="file" class="screenshotUpload col-xs-12" name="myfile" file-model="files[attribute.attributeID]"/>
                                <button class="btn btn-success" ng-click="save.screenshot(attribute)">upload me</button>
                            </div>
                            <div class="col-xs-3" ng-repeat="screenshot in attribute.screenshots">
                                <a ng-click="openLightboxModal(attribute.screenshots, $index)">
                                    <img ng-src="{{screenshot}}" class="col-xs-12 img-thumbnail" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="navigation">
                        <div class="col-xs-2">
                            <a class="btn btn-block btn-primary prev" ng-click="prev()" scroll scroll-target="#header">Back</a>
                        </div>
                        <div ng-class="{'col-xs-6': requiredItems - completedItems == 0 && !$last}" class="col-xs-8">
                            <uib-progressbar max="requiredItems" value="completedItems"><span style="color:white; white-space:nowrap;">{{completedItems}} / {{requiredItems}}</span></uib-progressbar>
                        </div>
                        <div ng-if="!$last" class="col-xs-2">
                            <a class="btn btn-block next btn-primary" ng-click="next()" scroll scroll-target="#header">Continue</a>
                        </div>
                        <div ng-if="requiredItems - completedItems == 0 || $last" class="col-xs-2">
                            <a class="btn btn-block btn-success next" ng-disabled="requiredItems - completedItems !== 0 "  ng-click="save.finish(); last()" scroll scroll-target="#header">Finish</a>
                        </div>
                    </div>
                </div>
                <!-- END ATTRIBUTE PANELS -->

                <div class="panel hidden col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 finished-panel">
                    <h2>Thank you for completing our survey</h2>
                    <p>{{finished ? "Your assessment has been saved. You may safely close this page." : "Your assessment is being saved. Please do not close this page."}}</p>
                    <div class="navigation">
                        <div class="col-xs-2">
                            <a class="btn btn-block btn-primary prev" ng-click="prev()" scroll scroll-target="#header">Back</a>
                        </div>
                    </div>
                </div>

            </div> <!-- END PANEL WRAPPER -->

        </div> <!-- END CONTAINER FLUID -->
    </body>
</html>