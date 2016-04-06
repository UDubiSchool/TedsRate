'use strict';
var app = angular.module('administrator', ['ngAnimate', 'ui.bootstrap', 'bootstrapLightbox', 'ui.validate', 'ngCookies', 'ngFileUpload', 'teds.models', 'ui.router', 'teds.directives.dropdown', 'teds.directives.filterList']);

app.service('alertService', function(){
    this.alerts = [];

    this.addAlert = function (msg,type) {
        this.alerts.push({
            type: type,
            msg: msg
        });
    }

    this.closeAlert = function(index) {
        this.alerts.splice(index, 1);
    };
});

app.controller('adminCtrl', ['$scope', '$rootScope', '$state', '$stateParams', 'alertService', function($scope, $rootScope, $state, $stateParams, alertService){
    $scope.alerts = alertService.alerts;
    // $rootScope.$state = $state;
    // $rootScope.$stateParams = $stateParams;

    $scope.addAlert = alertService.addAlert;
    $scope.closeAlert = alertService.closeAlert;
}]);

app.controller('projectCtrl', ['$scope', '$http', '$animate', 'projectService', '$q', 'Upload', '$uibModal', 'languageService', 'alertService', 'userService', 'statService', function($scope, $http, $animate, projectService, $q, Upload, $uibModal, languageService, alertService, userService, statService) {

    // get initial project data for page load
    projectService.getBasic().then(function(response){
        var deferred = $q.defer();
        var tmp = response.data;
        angular.forEach(tmp, function(project, projectKey){
            project.collapsed = true;
        });
        deferred.resolve(tmp);
        return deferred.promise;
    }).then(function(processed){
        $scope.projects = processed;

        // get all data after intial project data load
        projectService.getAll().then(function(response) {
            var deferred = $q.defer();
            console.log(response);
            var tmp = response.data;
            angular.forEach(tmp, function(project, projectKey){
                project.collapsed = true;
                project.selected ={
                    artifact: '',
                    scenario: '',
                    persona: '',
                    role: '',
                    assessment: '',
                    configuration: ''
                };
                $scope.$watch(function () {
                    return project.selected.artifact;
                }, function(artifact){
                    if(artifact != undefined && artifact !=null && artifact !='') {
                        statService.byArtifact(project.projectID, artifact.artifactID).then(function(stats){
                            project.selected.artifact.stats = stats.data;
                            console.log(stats);
                        });
                        console.log('a change happened!');
                    }
                }, false);

                $scope.$watch(function () {
                    return project.selected.scenario;
                }, function(scenario){
                    if(scenario != undefined && scenario !=null && scenario !='') {
                        statService.byScenario(project.projectID, scenario.scenarioID).then(function(stats){
                            project.selected.scenario.stats = stats.data;
                            console.log(stats);
                        });
                        console.log('a change happened!');
                    }
                }, false);

                  $scope.$watch(function () {
                    return project.selected.configuration;
                }, function(configuration){
                    if(configuration != undefined && configuration !=null && configuration !='') {
                        statService.byConfiguration(project.projectID, configuration.configurationID).then(function(stats){
                            project.selected.configuration.stats = stats.data;
                            console.log(stats);
                        });
                        console.log('a change happened!');
                    }
                }, false);


                // set up the filterlists

                project.scenariosList = [];
                project.scenariosStats = {
                    Count: project.scenarios.length
                };
                angular.forEach(project.scenarios, function(scenario, scenarioKey) {
                    if(scenario !== undefined && scenario !== null && scenario !== '') {
                        var tmp = {
                            details: scenario,
                            listData: {
                                name: scenario.scenarioName,
                                description: scenario.scenarioDescription,
                            }
                        };
                        project.scenariosList.push(tmp);
                    }

                });


                project.artifactsList = [];
                project.artifactsStats = {
                    Count: project.artifacts.length
                };
                angular.forEach(project.artifacts, function(artifact, artifactKey) {
                    if(artifact !== undefined && artifact !== null && artifact !== '') {
                        var tmp = {
                            details: artifact,
                            listData: {
                                name: artifact.artifactName,
                                description: artifact.artifactDescription,
                            },
                            specialFields: {
                                link: {
                                    value: decodeURIComponent(artifact.artifactURL),
                                    type: 'link'
                                }
                            }
                        };
                        project.artifactsList.push(tmp);
                    }

                });

                project.assessmentsList = [];
                project.assessmentsStats = {
                    Count: project.assessments.length
                };
                angular.forEach(project.assessments, function(assessment, assessmentKey) {
                    if(assessment !== undefined && assessment !== null && assessment !== '') {
                        var tmp = {
                            details: assessment,
                            listData: {
                                project: assessment.projectName,
                                artifact: assessment.artifactName,
                                persona: assessment.personaName,
                                role: assessment.roleName,
                                scenario: assessment.scenarioName,
                                user: assessment.email,
                                configuration: assessment.attributeConfigurationName,
                            },
                            specialFields: {
                                link: {
                                    value: 'assessment.php?asid=' + assessment.assessmentIDHashed,
                                    type: 'link'
                                },
                                issued: {
                                    value: assessment.issuanceDate,
                                    preface: 'Issued on',
                                    type: 'date'
                                },
                                completion: {
                                    value: assessment.completionDate,
                                    preface: 'Completed on',
                                    type: 'date'
                                },
                                edited: {
                                    value: assessment.lastEditDate,
                                    preface: 'Last Edited on',
                                    type: 'date'
                                }
                            }
                        };
                        project.assessmentsList.push(tmp);
                    }

                });

                project.configurationsList = [];
                project.configurationsStats = {
                    Count: project.configurations.length
                };
                angular.forEach(project.configurations, function(configuration, configurationKey) {
                    if(configuration !== undefined && configuration !== null && configuration !== '') {
                        var tmp = {
                            details: configuration,
                            listData: {
                                // configuration: configuration.configurationName,
                                attributes: configuration.attributeConfigurationName,
                                questions: configuration.questionConfigurationName,
                                interface: configuration.uiConfigurationName,
                                artifact: configuration.artifactName,
                                scenario: configuration.scenarioName,
                                persona: configuration.personaName,
                                role: configuration.roleName,
                            },
                            specialFields: {
                                link: {
                                    value: 'start.php?c=' + configuration.configurationIDHashed,
                                    type: 'link'
                                }
                            }
                        };
                        project.configurationsList.push(tmp);
                    }

                });

            });
            deferred.resolve(tmp);
            return deferred.promise;
        }).then(function(processed){
            $scope.projects = processed;
        });
    });


    userService.getAll().then(function(response){
        $scope.users = response.data;
    });

    // $scope.setTargetAssessment = function (project, assessment) {
    //     project.targetAssessment = assessment;
    // }

    // $scope.unsetTargetAssessment = function (project) {
    //     delete project.targetAssessment;
    // }

    $scope.addAlert = alertService.addAlert;

    $scope.closeAlert = alertService.closeAlert;

    languageService.getAll().then(function(response){
        $scope.languages = response.data;
    })

    var modalTemplates = {
        project: 'partials/admin/modals/add_project.html',
        artifact: 'partials/admin/modals/add_artifact.html',
        scenario: 'partials/admin/modals/add_scenario.html',
        persona: 'partials/admin/modals/add_persona.html',
        role: 'partials/admin/modals/add_role.html',
        configuration: 'partials/admin/modals/add_configuration.html',
        assessment: 'partials/admin/modals/add_assessment.html'
    };

    var modalControllers = {
        project: 'addProjectCtrl',
        artifact: 'addArtifactCtrl',
        scenario: 'addScenarioCtrl',
        persona: 'addPersonaCtrl',
        role: 'addRoleCtrl',
        configuration: 'addConfigurationCtrl',
        assessment: 'addAssessmentCtrl'
    };

    $scope.modals = {
        open: function(target, project, index) {
            var modalInstance = $uibModal.open({
                  animation: true,
                  templateUrl: modalTemplates[target],
                  controller: modalControllers[target],
                  size: 'lg',
                  resolve: {
                    project: project,
                  },
                  scope: $scope
            });

            modalInstance.result.then(function (newProject) {
              $scope.projects[index] = newProject;
            }, function () {});
        }
    };

}]).controller('assessmentsCtrl', ['$scope', '$state', '$stateParams', function($scope, $state, $stateParams){
    // $scope.assessment = $stateParams.assessment;
    // console.log('opened assessmentCtrl');
    // console.log($state);
    // console.log($stateParams);
}]).controller('addAssessmentCtrl', function($scope, $uibModalInstance, project, assessmentService){
    var newProject = project;
    $scope.project = project;

    $scope.ok = function () {
      $scope.assessment.projectID = project.projectID;
      var filteredData = {
        configurationID: $scope.assessment.configurationID,
        userID: $scope.assessment.userID
      };
      // add configuration to db and to project then return altered project to main ctrl to add to DOM
      assessmentService.post(filteredData).then(function(response){
          if(response.status) {

              var id = response.data.data.id;
              assessmentService.get(id).then(function(response){
                  var assessment = response.data[0];
                  newProject.assessments.push(assessment);
                  newProject.assessmentsStats.Count++;
                  var tmp = {
                      details: assessment,
                        listData: {
                            project: assessment.projectName,
                            artifact: assessment.artifactName,
                            persona: assessment.personaName,
                            role: assessment.roleName,
                            scenario: assessment.scenarioName,
                            user: assessment.email,
                            configuration: assessment.attributeConfigurationName,
                        },
                        specialFields: {
                            link: {
                                value: 'assessment.php?asid=' + assessment.assessmentIDHashed,
                                type: 'link'
                            },
                            issued: {
                                value: assessment.issuanceDate,
                                preface: 'Issued on',
                                type: 'date'
                            },
                            completion: {
                                value: assessment.completionDate,
                                preface: 'Completed on',
                                type: 'date'
                            },
                            edited: {
                                value: assessment.lastEditDate,
                                preface: 'Last Edited on',
                                type: 'date'
                            }
                        }
                  };
                  newProject.assessmentsList.push(tmp);
                  $scope.$parent.addAlert('The assessment has successfully been added to the database.', 'success');
                  $uibModalInstance.close(newProject);
              });


          } else {
              $scope.$parent.addAlert('The assessment could not be added to the database.', 'danger');
              $uibModalInstance.dismiss('cancel');

          }
      });
    };

    $scope.cancel = function () {
      $uibModalInstance.dismiss('cancel');
    };
});

app.controller('addProjectCtrl', function ($scope, $uibModalInstance, project) {

  $scope.project = project;
  console.log(project);

  $scope.ok = function () {
    // add project then return to main ctrl to add to DOM
    $uibModalInstance.close($scope.selected.item);
  };

  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };
}).controller('addArtifactCtrl', function ($scope, $uibModalInstance, project, artifactService, artifactTypeService) {

  var newProject = project;
  $scope.project = project;
  artifactTypeService.getAll().then(function(response){
    $scope.artifactTypes = response.data;
  });
  artifactService.getAll().then(function(response){
    $scope.artifacts = response.data;
  });

  $scope.ok = function () {
    $scope.artifact.projectID = project.projectID;
    // $scope.artifact.artifactURL = encodeURI($scope.artifact.artifactURL);
    // console.log($scope.artifact);

    // add artifact to db and to project then return altered project to main ctrl to add to DOM
    artifactService.post($scope.artifact).then(function(response){
        if(response.status) {
            if ($scope.artifact.artifactID) {
                artifactService.get($scope.artifact.artifactID).then(function(response){
                    newProject.artifacts.push(response.data[0]);
                    $scope.$parent.addAlert('The artifact has successfully been associated with the project.', 'success');
                    $uibModalInstance.close(newProject);
                });
            } else {
                newProject.artifacts.push($scope.artifact);
                $scope.$parent.addAlert('The artifact has successfully been added to the database.', 'success');
                $uibModalInstance.close(newProject);
            }
        } else {
            $scope.$parent.addAlert('The artifact could not be added to the database.', 'danger');
            $uibModalInstance.dismiss('cancel');

        }
    });
  };

  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };
}).controller('addScenarioCtrl', function ($scope, $uibModalInstance, project, scenarioService) {

  var newProject = project;
  $scope.project = project;
  scenarioService.getAll().then(function(response){
    $scope.scenarios = response.data;
  });

  $scope.ok = function () {
    $scope.scenario.projectID = project.projectID;
    // console.log($scope.scenario);
    // add scenario to db and to project then return altered project to main ctrl to add to DOM
    scenarioService.post($scope.scenario).then(function(response){
        if(response.status) {
            if ($scope.scenario.scenarioID) {
                scenarioService.get($scope.scenario.scenarioID).then(function(response){
                    newProject.scenarios.push(response.data[0]);
                    $scope.$parent.addAlert('The scenario has successfully been associated with the project.', 'success');
                    $uibModalInstance.close(newProject);
                });
            } else {
                newProject.scenarios.push($scope.scenario);
                $scope.$parent.addAlert('The scenario has successfully been added to the database.', 'success');
                $uibModalInstance.close(newProject);
            }
        } else {
            $scope.$parent.addAlert('The scenario could not be added to the database.', 'danger');
            $uibModalInstance.dismiss('cancel');

        }
    });
  };

  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };
}).controller('addPersonaCtrl', function ($scope, $uibModalInstance, project, personaService) {

  var newProject = project;
  $scope.project = project;
  personaService.getAll().then(function(response){
    $scope.personas = response.data;
  });

  $scope.ok = function () {
    $scope.persona.projectID = project.projectID;
    // add persona to db and to project then return altered project to main ctrl to add to DOM
    personaService.post($scope.persona).then(function(response){
        if(response.status) {
            if ($scope.persona.personaID) {
                personaService.get($scope.persona.personaID).then(function(response){
                    newProject.personas.push(response.data[0]);
                    $scope.$parent.addAlert('The persona has successfully been associated with the project.', 'success');
                    $uibModalInstance.close(newProject);
                });
            } else {
                newProject.personas.push($scope.persona);
                $scope.$parent.addAlert('The persona has successfully been added to the database.', 'success');
                $uibModalInstance.close(newProject);
            }
        } else {
            $scope.$parent.addAlert('The persona could not be added to the database.', 'danger');
            $uibModalInstance.dismiss('cancel');

        }
    });
  };

  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };
}).controller('addRoleCtrl', function ($scope, $uibModalInstance, project, roleService) {

  var newProject = project;
  $scope.project = project;
  roleService.getAll().then(function(response){
    $scope.roles = response.data;
  });

  $scope.ok = function () {
    $scope.role.projectID = project.projectID;
    // add role to db and to project then return altered project to main ctrl to add to DOM
    roleService.post($scope.role).then(function(response){
        if(response.status) {
            if ($scope.role.roleID) {
                roleService.get($scope.role.roleID).then(function(response){
                    newProject.roles.push(response.data[0]);
                    $scope.$parent.addAlert('The role has successfully been associated with the project.', 'success');
                    $uibModalInstance.close(newProject);
                });
            } else {
                newProject.roles.push($scope.role);
                $scope.$parent.addAlert('The role has successfully been added to the database.', 'success');
                $uibModalInstance.close(newProject);
            }
        } else {
            $scope.$parent.addAlert('The role could not be added to the database.', 'danger');
            $uibModalInstance.dismiss('cancel');

        }
    });
  };

  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };
}).controller('addConfigurationCtrl', function ($scope, $uibModalInstance, project, configurationService) {
    // console.log($scope.$parent);

  var newProject = project;
  $scope.project = project;
  configurationService.getAttributeConfigurations().then(function(response){
    $scope.attributeConfigurations = response.data;
  });
  configurationService.getAssessmentConfigurations().then(function(response){
    $scope.assessmentConfigurations = response.data;
  });
  configurationService.getQuestionConfigurations().then(function(response){
    $scope.questionConfigurations = response.data;
  });
  configurationService.getUiConfigurations().then(function(response){
    $scope.uiConfigurations = response.data;
  });

  $scope.ok = function () {
    $scope.configuration.projectID = project.projectID;

    // add configuration to db and to project then return altered project to main ctrl to add to DOM
    configurationService.post($scope.configuration).then(function(response){
        if(response.status) {
            if ($scope.configuration.configurationID) {
                configurationService.get($scope.configuration.configurationID).then(function(response){
                    newProject.configurations.push(response.data[0]);
                    newProject.configurationsList.push(response.data[0]);
                    $scope.$parent.addAlert('The configuration has successfully been associated with the project.', 'success');
                    $uibModalInstance.close(newProject);
                });
            } else {
                var id = response.data.data.id;
                configurationService.get(id).then(function(response){
                    var configuration = response.data[0];
                    newProject.configurations.push(configuration);
                    newProject.configurationsStats.Count++;
                    var tmp = {
                        details: configuration,
                        listData: {
                            // configuration: configuration.configurationName,
                            attributes: configuration.attributeConfigurationName,
                            questions: configuration.questionConfigurationName,
                            interface: configuration.uiConfigurationName,
                            artifact: configuration.artifactName,
                            scenario: configuration.scenarioName,
                            persona: configuration.personaName,
                            role: configuration.roleName,
                        },
                        specialFields: {
                            link: {
                                value: 'start.php?c=' + configuration.configurationIDHashed,
                                type: 'link'
                            }
                        }
                    };
                    newProject.configurationsList.push(tmp);
                    $scope.$parent.addAlert('The configuration has successfully been added to the database.', 'success');
                    $uibModalInstance.close(newProject);
                });

            }
        } else {
            $scope.$parent.addAlert('The configuration could not be added to the database.', 'danger');
            $uibModalInstance.dismiss('cancel');

        }
    });
  };

  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };
});

app.config(function($stateProvider, $urlRouterProvider) {
  //
  // For any unmatched url, redirect to /projects
  $urlRouterProvider.otherwise("/projects");
  //
  // Now set up the states
  $stateProvider
    .state('projects', {
      url: "/projects",
      templateUrl: "partials/admin/project_manager.html",
      controller: 'projectCtrl'
    });
    // .state('projects.assessmentDetails', {
    //   url: "/assessments/:id",
    //   templateUrl: "partials/admin/assessment.html",
    //   controller: 'assessmentCtrl',
    //   params: {
    //           // here we define default value for foo
    //           // we also set squash to false, to force injecting
    //           // even the default value into url
    //           assessment: {
    //             value: null,
    //           },
    //           // this param is not part of url
    //           // it could be passed with $state.go or ui-sref
    //           hiddenParam: 'YES',
    //         },
    // });
    // .state('state2', {
    //   url: "/state2",
    //   templateUrl: "partials/state2.html"
    // })
    // .state('state2.list', {
    //   url: "/list",
    //   templateUrl: "partials/state2.list.html",
    //   controller: function($scope) {
    //     $scope.things = ["A", "Set", "Of", "Things"];
    //   }
    // });
});
app.filter('capitalize', function() {
    return function(input) {
      return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
    }
});