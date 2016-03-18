<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/angular-bootstrap-lightbox.css">
        <link rel="stylesheet" href="css/assessment.css">
        <script src="js/jquery-1.11.0.min.js" type="text/javascript"/></script>
        <script src="js/angular/angular.min.js" type="text/javascript"/></script>
        <script src="js/angular/angular-animate.js" type="text/javascript"/></script>
        <script src="js/angular/ui-bootstrap-tpls-1.1.2.min.js" type="text/javascript"/></script>
        <script src="js/angular/elastic.js" type="text/javascript" /></script>
        <script src="js/angular/validate.js" type="text/javascript" /></script>
        <script src="js/angular/angular-cookies.js"></script>
        <script src="js/angular/angular-bootstrap-lightbox.js"></script>
        <script src="js/angular/ng-file-upload-shim.min.js"></script>
        <script src="js/angular/ng-file-upload.min.js"></script>
        <script src="js/angular/models/tedsModels.js"></script>
        <script src="js/assessment.js"></script>
        <!-- <base href="/"> -->
        <base href="/tedsrate/tedsrate/">
    </head>
    <body>
        <input type="hidden" id='asid' name='asid' value="<?php echo $_GET['asid']?>">
        <div class="container-fluid clearfix" ng-app="assessmentApp" ng-controller="assessmentController">
            <div id="header" class="clearfix header">
                <div class="clearfix pull-left">
                    <h2>{{assessment.project.name}} - {{assessment.scenario.name}}</h2>
                </div>
            </div>
            <div id="alert-wrapper">
                <uib-alert ng-repeat="alert in alerts" type="{{alert.type}}" close="closeAlert($index)">{{alert.msg}}</uib-alert>
            </div>
            <div id="panel-wrapper" class="clearfix">

                <div class="panel active col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                    <h2>Welcome</h2>
                    <p>
                        This assessment is a component of the Purposeful Sampling Research Project and the University of Washington Information School. The work group’s aim is to refine a methodology to measure the usability of content and information artifacts. It has been used to evaluate mobile applications with a concentration on professional sport team mobile applications, but it is now being adapted to evaluate emergency management information systems, specifically WebEOC. The methodology is based on the Taylor-Eisenberg-Dirks-Scholl (TEDS) information artifact value factorization framework.
                    </p>
                    <p>Although these assessments are short, you do not need the do them all at once. Your changes are saved so you can take as much time as you need!</p>
                    <br>
                    <p>The focus of this assessment is to analyze the <a ng-href="{{assessment.artifact.url}}">{{assessment.artifact.name}}</a></p>
                    <br>
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
                                        An email is required</span>
                                      <span class="error" ng-show="signupForm.email.$error.email">
                                        Not a valid email</span>
                                        <span class="error" ng-show="signupForm.email.$error.taken">
                                        That email has already been registered</span>
                                </div>
                                <input class="form-control" type="email" name="email" ng-model="signup.email" placeholder="Email" required ng-change="taken(signupForm)" ng-model-options="{ updateOn: 'blur' }">
                            </div>

                            <div class="form-group">
                                <div role="alert">
                                      <span class="error" ng-show="signupForm.password.$viewValue !== signupForm.confirm.$viewValue && !signupForm.$pristine && signupForm.password.$viewValue !== undefined && signupForm.confirm.$viewValue !== undefined">
                                        Passwords do not match
                                        </span>
                                </div>
                                <input class="form-control" type="password" name="password" ng-model="signup.password" placeholder="Password" required ng-model-options="{ debounce: 0 }">
                                <input class="form-control" type="password" name="confirm" ng-model="signup.confirm" placeholder="Confirm" required ng-model-options="{ debounce: 500 }">
                            </div>
                            <input class="hidden" type="submit" value="Sign Up">

                            <div class="navigation">
                                <div class="col-xs-2">
                                    <a class="btn btn-block btn-primary prev" ng-click="prev()" scroll scroll-target="#header">Back</a>
                                </div>
                                <div class="col-xs-8"></div>
                                <div class="col-xs-2">
                                    <a class="btn btn-block btn-primary next" ng-click="addUser(signupForm)" ng-disabled="signupForm.$pristine || signupForm.$invalid || signupForm.password.$viewValue !== signupForm.confirm.$viewValue || signupForm.email.$error.taken" scroll scroll-target="#header">Sign Up</a>
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
                                    <span class="error" ng-show="signinForm.notFound">
                                    Invalid email/password combination!</span>
                                </div>
                                <input class="form-control" type="email" name="email" ng-model="signin.email" placeholder="Email" required ng-model-options="{ updateOn: 'blur' }" ng-change="signinForm.notFound = false">
                            </div>

                            <div class="form-group">
                                <input class="form-control" type="password" name="password" ng-model="signin.password" placeholder="Password" required ng-change="signinForm.notFound = false">
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
                    <div ng-if="key == 'demographic'">
                        <h5>Privacy Policy</h5>
                        <p>Your personal information is used to better understand the needs and preferences of groups of individuals with similar characteristics. We do not sell, trade, or otherwise transfer to outside parties your personally identifiable information. After the research project is completed the information you provided will be completely deleted.</p>
                    </div>
                    <div  ng-repeat="question in group" class="question" question-template></div>
                    <div class="navigation" panel-navigation></div>
                </div>
                <!-- END QUESTION PANELS -->

                <!-- BEGIN ATTRIBUTE PANELS -->
                <div ng-repeat="criterion in assessment.criteria" class="panel attribute-panel hidden col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 attribute-panel">

                    <div class="clearfix bottom-line scenario">
                        <div class="pull-right">
                            <h4 class="text-right">Scenario - <b>{{assessment.scenario.name}}</b></h4>
                            <p>{{assessment.scenario.description}}</p>
                        </div>
                    </div>
                    <!-- <h2>{{criterion.criterionName}}</h2> -->
                    <!-- <p>{{criterion.criterionDesc}}</p> -->
                    <div class="attributes">
                        <div ng-repeat="(attributeKey, attribute) in criterion.attributes"class="attribute clearfix">
                            <div class="clearfix">
                                <h3 class="pull-left">{{attribute.attributeName}} </h3>
                                <a style='margin-top:22px;margin-left:5px' class="pull-left" ng-if="attribute.attributeTypeName == 'Cluster'" uib-popover-template="'clusterTemp.html'" popover-trigger="outsideClick"><i class="fa fa-info-circle"></i></a>
                            </div>

                            <script type="text/ng-template" id="clusterTemp.html">
                                <div>
                                    This ranking refers to the information artifacts' effectiveness evaluated on the aspects of:
                                    <span ng-repeat="category in attribute.categories">
                                        <a uib-popover="{{category.attributeLaymanDesc}}" popover-trigger="outsideClick">{{category.attributeName}}</a>{{$last ? '' : ', '}}
                                    </span>
                                </div>
                            </script>

                            <p ng-if="assessment.configuration.uiConfiguration.descriptionType == 'Layman'">{{attribute.attributeLaymanDesc}}</p>
                            <p ng-if="assessment.configuration.uiConfiguration.descriptionType == 'Intellectual'">{{attribute.attributeDesc}}</p>


                            <div ng-if="assessment.configuration.uiConfiguration.ratingStyle == 'Likert'" class="col-xs-12 clearfix likert-rater">

                                <div class="center-block clearfix likert">
                                    <!-- <div class="col-xs-3"></div> -->
                                    <div class="">1</div>
                                    <div class="">2</div>
                                    <div class="">3</div>
                                    <div class="">4</div>
                                    <div class="">5</div>
                                    <!-- <div class="col-xs-3"></div> -->
                                </div>
                                <div class="center-block clearfix likert">
                                    <!-- <div class="col-xs-3"></div> -->
                                    <div class="">Very Poor</div>
                                    <div class="">Poor</div>
                                    <div class="">Fair</div>
                                    <div class="">Good</div>
                                    <div class="">Very Good</div>
                                    <!-- <div class="col-xs-3"></div> -->
                                </div>
                                <div class="center-block clearfix likert">
                            <!--         <div class="col-xs-3">
                                        {{attribute.preface}}
                                    </div> -->
                                    <div class="">
                                        <label class="btn checkbox" ng-model="attribute.ratingValue" uib-btn-radio="'1'" ng-change="trackProgress(attribute.ratingValue, '{{attribute.ratingValue}}', true); save.rating(attribute)"></label>
                                    </div>
                                    <div class="">
                                        <label class="btn checkbox" ng-model="attribute.ratingValue" uib-btn-radio="'2'" ng-change="trackProgress(attribute.ratingValue, '{{attribute.ratingValue}}', true); save.rating(attribute)"></label>
                                    </div>
                                    <div class="">
                                        <label class="btn checkbox" ng-model="attribute.ratingValue" uib-btn-radio="'3'" ng-change="trackProgress(attribute.ratingValue, '{{attribute.ratingValue}}', true); save.rating(attribute)"></label>
                                    </div>
                                    <div class="">
                                        <label class="btn checkbox" ng-model="attribute.ratingValue" uib-btn-radio="'4'" ng-change="trackProgress(attribute.ratingValue, '{{attribute.ratingValue}}', true); save.rating(attribute)"></label>
                                    </div>
                                    <div class="">
                                        <label class="btn checkbox" ng-model="attribute.ratingValue" uib-btn-radio="'5'" ng-change="trackProgress(attribute.ratingValue, '{{attribute.ratingValue}}', true); save.rating(attribute)"></label>
                                    </div>
                                    <!-- <div class="col-xs-3">
                                        {{attribute.postface}}
                                    </div> -->
                                </div>
                            </div>

                            <div ng-if="assessment.configuration.uiConfiguration.ratingStyle == 'Text'">
                                <input class="form-control" type="text" name="rating" ng-model="attribute.ratingValue" placeholder="Rating eg. 4" ng-change="trackProgress(attribute.ratingValue, '{{attribute.ratingValue}}', true); save.rating(attribute)">
                            </div>
                            <div class="optional-section col-xs-12 clearfix">
                                <h6>Please feel free to explain your perspective on the applications performance for this rating and/or provide screenshots in the 2 sections below.</h6>
                                <div class="comment form-group clearfix">
                                    <h4>Notes <span class="h5"> (optional)</span></h4>
                                    <textarea name="" id="" ng-model="attribute.comment" ng-change="save.comment(attribute)" ng-model-options="{updateOn: 'blur'}" cols="" rows="1" placeholder="Type optional comment(s) for this question here" msd-elastic>{{attribute.comment}}</textarea>
                                </div>
                                <div class="screenshots form-group clearfix">
                                    <div class="clearfix">
                                        <h4 class="pull-left">Screenshots <span class="h5">(optional)</span></h4>
                                        <a style='margin-top:8px;margin-left:5px' class="pull-left" uib-popover-template="'screenshot.html'" popover-trigger="outsideClick"><i class="fa fa-question-circle"></i></a>
                                    </div>
                                    <script type="text/ng-template" id="screenshot.html">
                                        <h4>Need Help making a screenshot?</h4>
                                        <p>Here are some how-to links for various devices.</p>
                                        <ul class="list-unstyled">
                                            <li><a href="http://www.imore.com/screenshot-mac" target="_blank">Mac</a></li>
                                            <li><a href="http://windows.microsoft.com/en-us/windows/take-screen-capture-print-screen" target="_blank">PC</a></li>
                                            <li><a href="http://www.thegeekstuff.com/2012/08/screenshot-ubuntu/" target="_blank">Linux - Ubuntu</a></li>
                                            <li><a href="https://support.apple.com/en-us/HT200289" target="_blank">iPhone</a></li>
                                            <li><a href="http://www.makeuseof.com/tag/6-ways-to-take-screenshots-on-android/" target="_blank">Android</a></li>
                                            <li><a href="http://www.windowsphone.com/en-us/how-to/wp8/photos/take-a-screenshot" target="_blank">Windows Phone</a></li>
                                        </ul>
                                    </script>
                                    <div class="col-xs-3" ng-repeat="screenshot in attribute.screenshots">
                                        <a ng-click="openLightboxModal(attribute.screenshots, $index)">
                                            <img ng-src="{{screenshot}}" class="col-xs-12 img-thumbnail" alt="">
                                        </a>
                                    </div>
                                    <div class="col-sm-3">
                                        <div ngf-drop ngf-select ng-model="files[attribute.attributeID]" class="drop-box"
                                               ngf-change="save.screenshot(attribute)"
                                               ngf-allow-dir="true"
                                               accept="image/*"
                                               ngf-pattern="'image/*'"
                                               ngf-drag-over-class="{pattern: 'image/*', accept:'acceptFile', reject:'rejectFile', delay:100}"
                                               >Drop images here or click to upload</div>
                                        <div ngf-no-file-drop>File Drag/Drop is not supported for this browser</div>
                                        <uib-progressbar ng-if="attribute.progressPercentage" class="progress-striped active" value="attribute.progressPercentage" type="success">uploading</uib-progressbar>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="navigation">
                        <div class="col-xs-2">
                            <a class="btn btn-block btn-primary prev" ng-click="prev()" scroll scroll-target="#header">Back</a>
                        </div>
                        <div ng-class="{'col-xs-6': requiredItems - completedItems == 0 && !$last}" class="col-xs-8">
                            <uib-progressbar max="requiredItems" value="completedItems"><span style="color:white; white-space:nowrap;">Progress: {{completedItems}} / {{requiredItems}}</span></uib-progressbar>
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