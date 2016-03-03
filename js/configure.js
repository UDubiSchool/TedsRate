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

app.controller('projectCtrl', ['$scope', '$http', '$animate', 'projectService', '$q', 'Upload', '$uibModal', 'languageService', 'alertService', function($scope, $http, $animate, projectService, $q, Upload, $uibModal, languageService, alertService) {
    // $scope.alerts =[];
    // $scope.isCollapsed = true;
    projectService.getAll().then(function(response) {
        var deferred = $q.defer();
        console.log(response);
        var tmp = response.data;
        angular.forEach(tmp, function(project, projectKey){
            project.collapsed = true;
            project.artifactsCollapsed = true;
            project.scenariosCollapsed = true;
            project.personasCollapsed = true;
            project.rolesCollapsed = true;
            project.assessmentsCollapsed = true;
            project.assessmentFilters = {
                artifacts: [],
                personas: [],
                scenarios: [],
                users: [],
                roles: [],
                configurations: []
            };
            angular.forEach(project.artifacts, function(artifact, artifactKey){
                artifact.collapsed = true;
                if(project.assessmentFilters.artifacts.indexOf(artifact.artifactName) === -1) {
                    project.assessmentFilters.artifacts.push(artifact.artifactName);
                }
            });
            angular.forEach(project.scenarios, function(scenario, artifactKey){
                scenario.collapsed = true;
                if(project.assessmentFilters.scenarios.indexOf(scenario.scenarioName) === -1) {
                    project.assessmentFilters.scenarios.push(scenario.scenarioName);
                }
            });
            angular.forEach(project.personas, function(persona, artifactKey){
                persona.collapsed = true;
                if(project.assessmentFilters.personas.indexOf(persona.personaName) === -1) {
                    project.assessmentFilters.personas.push(persona.personaName);
                }
            });
            angular.forEach(project.roles, function(role, artifactKey){
                role.collapsed = true;
                if(project.assessmentFilters.roles.indexOf(role.roleName) === -1) {
                    project.assessmentFilters.roles.push(role.roleName);
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

    $scope.setTargetAssessment = function (project, assessment) {
        project.targetAssessment = assessment;
    }

    $scope.unsetTargetAssessment = function (project) {
        delete project.targetAssessment;
    }

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
        role: 'partials/admin/modals/add_role.html'
    };

    var modalControllers = {
        project: 'addProjectCtrl',
        artifact: 'addArtifactCtrl',
        scenario: 'addScenarioCtrl',
        persona: 'addPersonaCtrl',
        role: 'addRoleCtrl'
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
}]).controller('assessmentCtrl', ['$scope', '$state', '$stateParams', function($scope, $state, $stateParams){
    $scope.assessment = $stateParams.assessment;
    console.log('opened assessmentCtrl');
    console.log($stateParams.assessment);
}]);

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
    console.log($scope.artifact);

    // add artifact to db and to project then return altered project to main ctrl to add to DOM
    artifactService.post($scope.artifact).then(function(response){
        if(response.status) {
            if ($scope.artifact.artifactID) {
                artifactService.get($scope.artifact.artifactID).then(function(response){
                    newProject.artifacts.push(response.data[0]);
                    $scope.$parent.addAlert('The artifact has successfully been associated with the project.', 'success');
                });
            } else {
                newProject.artifacts.push($scope.artifact);
                $scope.$parent.addAlert('The artifact has successfully been added to the database.', 'success');
            }
            $uibModalInstance.close(newProject);
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
    console.log($scope.scenario);
    // add scenario to db and to project then return altered project to main ctrl to add to DOM
    scenarioService.post($scope.scenario).then(function(response){
        if(response.status) {
            if ($scope.scenario.scenarioID) {
                scenarioService.get($scope.scenario.scenarioID).then(function(response){
                    newProject.scenarios.push(response.data[0]);
                    $scope.$parent.addAlert('The scenario has successfully been associated with the project.', 'success');
                });
            } else {
                newProject.scenarios.push($scope.scenario);
                $scope.$parent.addAlert('The scenario has successfully been added to the database.', 'success');
            }
            $uibModalInstance.close(newProject);
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
                });
            } else {
                newProject.personas.push($scope.persona);
                $scope.$parent.addAlert('The persona has successfully been added to the database.', 'success');
            }
            $uibModalInstance.close(newProject);
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
                });
            } else {
                newProject.roles.push($scope.role);
                $scope.$parent.addAlert('The role has successfully been added to the database.', 'success');
            }
            $uibModalInstance.close(newProject);
        } else {
            $scope.$parent.addAlert('The role could not be added to the database.', 'danger');
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