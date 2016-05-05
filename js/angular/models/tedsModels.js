'use strict';
var app = angular.module('teds.models', []);

app.service('uploadService', ['$http', '$q', function ($http, $q) {
    this.uploadFileToUrl = function(file, uploadUrl){
        var fd = new FormData();
        fd.append('file', file);

        return $http.post(uploadUrl, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }
}]);

app.service('statService', ['$http', '$q', function ($http, $q) {
    this.byArtifact = function(projectID, artifactID){
        var target = "CI/index.php/stats/byArtifact/" + projectID + "/" + artifactID;
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.byScenario = function(projectID, scenarioID){
        var target = "CI/index.php/stats/byScenario/" + projectID + "/" + scenarioID;
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.byProject = function(projectID){
        var target = "CI/index.php/stats/byProject/" + projectID;
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.byConfiguration = function(projectID, configurationID){
        var target = "CI/index.php/stats/byConfiguration/" + projectID + "/" + configurationID;
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }
}]);

app.service('projectService', ['$http', '$q', 'uiGridConstants', function ($http, $q, uiGridConstants, $interval) {
    this.projects = {};
    this.overviews = {};
    this.current = {};
    var projectService = this;

    this.get = function(id) {
        var target = "CI/index.php/api/projects/"+id;
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        }).then(function(response2){
            projectService.projects[response2.data[0].projectID] = response2.data[0];
            projectService.current =  projectService.projects[response2.data[0].projectID];
            return response2;
        });

    }

    this.getAll = function() {
        var target = "CI/index.php/api/projects/";
        var data = {};
        var projectService = this;
        return $http.get(target, data, {
        }).success(function(response){
            projectService.projects = response;
            return projectService.projects;
        }).error(function(response){
            return response;
        });
    }

    this.getBasic = function() {
        var target = "CI/index.php/project/getBasic";
        var data = {};
        var projectService = this;
        return $http.get(target, data, {
        }).success(function(response){

            angular.forEach(response, function(project, projectKey) {
                projectService.projects[project.projectID] = project;
            });
            projectService.hasBasics = true;
            return projectService.projects;
        }).error(function(response){
            return response;
        });
    }

    this.post = function(data) {
        var target = "CI/index.php/api/projects/";
        var data = data;
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.put = function(data) {
        var target = "CI/index.php/api/projects/"+ data.projectID;
        var data = data;
        return $http.put(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.patch = function(data) {
        var target = "CI/index.php/api/projects/"+ data.projectID;
        var data = data;
        return $http.patch(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.delete = function(id) {
        var target = "CI/index.php/api/projects/"+ id;
        var data = {
            id: id
        };
        return $http.delete(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }
    this.load = function (id) {
        var deferredOuter = $q.defer();
        if(Object.keys(projectService.projects).length > 0 && projectService.projects[id].initialLoad) {
            projectService.current = projectService.projects[id];
            deferredOuter.resolve(projectService.current);
            return deferredOuter.promise;
        } else{
            // get all data after intial project data load
            console.log('Project not loaded. Loading now....')
            // projectService.projects[index].loading = true;
            return projectService.get(id).then(function(response) {
                // console.log(projectService.current);
                var deferred = $q.defer();
                var project = response.data[0];
                project.initialLoad = true;
                project.selected ={
                    artifact: '',
                    scenario: '',
                    persona: '',
                    role: '',
                    assessment: '',
                    configuration: ''
                };
                deferred.resolve(project);
                return deferred.promise;
            }).then(function(processed){

                projectService.projects[id] = processed;
                projectService.current = projectService.projects[id];
                deferredOuter.resolve(projectService.current);
                return deferredOuter.promise;
            });
        }
    };
}]);

app.service('artifactService', ['$http', '$q', function ($http, $q) {
    this.get = function(id) {
        var target = "CI/index.php/api/artifacts/"+id;
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.getAll = function() {
        var target = "CI/index.php/api/artifacts/";
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.post = function(data) {
        var target = "CI/index.php/api/artifacts/";
        var data = data;
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.put = function(data) {
        var target = "CI/index.php/api/artifacts/"+ data.artifactID;
        var data = data;
        return $http.put(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.patch = function(data) {
        var target = "CI/index.php/api/artifacts/"+ data.artifactID;
        var data = data;
        return $http.patch(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.delete = function(id) {
        var target = "CI/index.php/api/artifacts/"+ id;
        var data = {
            id: id
        };
        return $http.delete(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }
}]);
app.service('artifactTypeService', ['$http', '$q', function ($http, $q) {
    this.get = function(id) {
        var target = "CI/index.php/api/artifact-Types/"+id;
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.getAll = function() {
        var target = "CI/index.php/api/artifact-Types/";
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.post = function(data) {
        var target = "CI/index.php/api/artifact-Types/";
        var data = data;
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.put = function(data) {
        var target = "CI/index.php/api/artifact-Types/"+ data.artifactTypeID;
        var data = data;
        return $http.put(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.patch = function(data) {
        var target = "CI/index.php/api/artifact-Types/"+ data.artifactTypeID;
        var data = data;
        return $http.patch(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.delete = function(id) {
        var target = "CI/index.php/api/artifact-Types/"+ id;
        var data = {
            id: id
        };
        return $http.delete(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }
}]);
app.service('languageService', ['$http', '$q', function ($http, $q) {
    this.get = function(id) {
        var target = "CI/index.php/api/languages/"+id;
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.getAll = function() {
        var target = "CI/index.php/api/languages/";
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.post = function(data) {
        var target = "CI/index.php/api/languages/";
        var data = data;
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.put = function(data) {
        var target = "CI/index.php/api/languages/"+ data.languageID;
        var data = data;
        return $http.put(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.patch = function(data) {
        var target = "CI/index.php/api/languages/"+ data.languageID;
        var data = data;
        return $http.patch(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.delete = function(id) {
        var target = "CI/index.php/api/languages/"+ id;
        var data = {
            id: id
        };
        return $http.delete(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }
}]);
app.service('scenarioService', ['$http', '$q', function ($http, $q) {
    this.get = function(id) {
        var target = "CI/index.php/api/scenarios/"+id;
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.getAll = function() {
        var target = "CI/index.php/api/scenarios/";
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.post = function(data) {
        var target = "CI/index.php/api/scenarios/";
        var data = data;
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.put = function(data) {
        var target = "CI/index.php/api/scenarios/"+ data.scenarioID;
        var data = data;
        return $http.put(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.patch = function(data) {
        var target = "CI/index.php/api/scenarios/"+ data.scenarioID;
        var data = data;
        return $http.patch(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.delete = function(id) {
        var target = "CI/index.php/api/scenarios/"+ id;
        var data = {
            id: id
        };
        return $http.delete(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }
}]);

app.service('personaService', ['$http', '$q', function ($http, $q) {
    this.get = function(id) {
        var target = "CI/index.php/api/personas/"+id;
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.getAll = function() {
        var target = "CI/index.php/api/personas/";
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.post = function(data) {
        var target = "CI/index.php/api/personas/";
        var data = data;
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.put = function(data) {
        var target = "CI/index.php/api/personas/"+ data.personaID;
        var data = data;
        return $http.put(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.patch = function(data) {
        var target = "CI/index.php/api/personas/"+ data.personaID;
        var data = data;
        return $http.patch(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.delete = function(id) {
        var target = "CI/index.php/api/personas/"+ id;
        var data = {
            id: id
        };
        return $http.delete(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }
}]);
app.service('roleService', ['$http', '$q', function ($http, $q) {
    this.get = function(id) {
        var target = "CI/index.php/api/roles/"+id;
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.getAll = function() {
        var target = "CI/index.php/api/roles/";
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.post = function(data) {
        var target = "CI/index.php/api/roles/";
        var data = data;
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.put = function(data) {
        var target = "CI/index.php/api/roles/"+ data.roleID;
        var data = data;
        return $http.put(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.patch = function(data) {
        var target = "CI/index.php/api/roles/"+ data.roleID;
        var data = data;
        return $http.patch(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.delete = function(id) {
        var target = "CI/index.php/api/roles/"+ id;
        var data = {
            id: id
        };
        return $http.delete(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }
}]);
app.service('configurationService', ['$http', '$q', function ($http, $q) {
    this.get = function(id) {
        var target = "CI/index.php/api/configurations/"+id;
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.getAll = function() {
        var target = "CI/index.php/api/configurations/";
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.getAttributeConfigurations = function() {
        var target = "CI/index.php/api/attributeConfigurations/";
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.getAssessmentConfigurations = function() {
        var target = "CI/index.php/api/assessmentConfigurations/";
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.getQuestionConfigurations = function() {
        var target = "CI/index.php/api/questionConfigurations/";
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.getUiConfigurations = function() {
        var target = "CI/index.php/api/uiConfigurations/";
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.getComponents = function() {
        var deferred = $q.defer();
        var data = {};
        var assessmentConfigurations = this.getAssessmentConfigurations();
        var attributeConfigurations = this.getAttributeConfigurations();
        var questionConfigurations = this.getQuestionConfigurations();
        var uiConfigurations = this.getUiConfigurations();
        var ret = {
            assessmentConfigurations: assessmentConfigurations.data,
            attributeConfigurations: attributeConfigurations.data,
            questionConfigurations: questionConfigurations.data,
            uiConfigurations: uiConfigurations.data
        };
        deferred.resolve(ret);
        return deferred.promise;
    }

    this.post = function(data) {
        var target = "CI/index.php/api/configurations/";
        var data = data;
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.put = function(data) {
        var target = "CI/index.php/api/configurations/"+ data.configurationID;
        var data = data;
        return $http.put(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.patch = function(data) {
        var target = "CI/index.php/api/configurations/"+ data.configurationID;
        var data = data;
        return $http.patch(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.delete = function(id) {
        var target = "CI/index.php/api/configurations/"+ id;
        var data = {
            id: id
        };
        return $http.delete(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }
}]);



// old vanilla services
app.service('userService', ['$http', '$q', function ($http, $q) {

    this.get = function(id) {
        var target = "CI/index.php/api/users/"+id;
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.getAll = function(){
        var target ="CI/index.php/api/users/";
        var data ={};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    // validates a email-password pair
    this.validate = function(email, password){
        var target = "models/user.php?f=get";
        var data = {
            email: email,
            password: password
        };
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.findEmail = function(email) {
        var target = "models/user.php?f=getEmail";
        var data = {
            email: email
        };
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    //updates a user
    this.post = function(data){
        var target = "models/user.php?f=post";
        // var data = data;
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    // deletes the user corrosponding to the ID
    this.delete = function(userID){
        var target = "models/user.php?f=delete";
        // var data = data;
        return $http.post(target, {userID: userID}, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }
}]);

app.service('assessmentService', ['$http', '$q', function ($http, $q) {

    this.get = function(id) {
        var target = "CI/index.php/api/assessments/"+id;
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.getAll = function() {
        var target = "CI/index.php/api/assessments/";
        var data = {};
        return $http.get(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.post = function(data) {
        var target = "CI/index.php/api/assessments/";
        var data = data;
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    // gets a single assessment based on the hashed ID
    this.validate = function(data){
        var target = "models/assessment.php?f=get";
        // var data = data;
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    // gets an assessment based on the unique key (userID, ConfigurationID)
    this.getByUserConf = function(data){
        var target = "models/assessment.php?f=getByUserConf";
        // var data = data;
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    // updates an assessments user
    this.updateUser = function(data){
        var target = "models/assessment.php?f=updateUser";
        // var data = data;
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    // saves the assessment
    this.save = function(data){
        var target = "models/assessment.php?f=save";
        // var data = data;
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    // saves the assessment
    this.finish = function(data){
        var target = "models/assessment.php?f=finish";
        // var data = data;
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.delete = function(id) {
        var target = "CI/index.php/api/assessments/"+id;
        var data = {};
        return $http.delete(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

}]);

app.service('ratingService', ['$http', '$q', function ($http, $q) {

    //adds a rating
    this.put = function(data){
        var target = "models/rating.php?f=put";
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }
}]);

app.service('responseService', ['$http', '$q', function ($http, $q) {

    //adds a response
    this.put = function(data){
        var target = "models/response.php?f=put";
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }
}]);

app.service('commentService', ['$http', '$q', function ($http, $q) {

    //adds a comment
    this.put = function(data){
        var target = "models/comment.php?f=put";
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.delete = function(data){
        var target = "models/comment.php?f=delete";
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }
}]);

app.service('screenshotService', ['$http', '$q', function ($http, $q) {

    //adds a screenshot
    this.put = function(data){
        var target = "CI/index.php/api/screenshots/";
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }

    this.delete = function(data){
        var target = "models/screenshot.php?f=delete";
        return $http.post(target, data, {
        }).success(function(response){
            return response;
        }).error(function(response){
            return response;
        });
    }
}]);