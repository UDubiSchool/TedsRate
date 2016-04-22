<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');
class Projects_api extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('project_model', 'project');
    }

    // gets a record by primary key or retrieves all records
    public function api_get($key = NULL)
    {
        // echo $key;
        // exit;
        $tmp = null;
        $id = $key;
        if($id == null) {
            $tmp = $this->project->getAll();
            if (!$tmp) {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No projects were found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $id = (int) $id;
            if($id <= 0) {
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $tmp = $this->project->get($id);
                if (!$tmp) {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'project not found'
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            }
        }
        foreach ($tmp as $key => $value) {
            $tmp[$key] = $this->getAssoc($value);
        }
       $this->response($tmp);
    }

    // does a full update of a record
    public function api_put($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->project->put($this->put()));
    }

    // creates a new record
    public function api_post($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->project->post($this->post()));
    }

    // does a partial update of a record
    public function api_patch($key = NULL, $xss_clean = NULL)
    {
        // $this->response($this->project->patch($this->patch()));
    }

    // deletes a record
    public function api_delete($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->project->delete($key));
    }

    private function getAssoc($project)
    {
        $this->load->model('artifact_model', 'artifact');
        $this->load->model('scenario_model', 'scenario');
        $this->load->model('persona_model', 'persona');
        $this->load->model('role_model', 'role');
        $this->load->model('assessment_model', 'assessment');
        $this->load->model('configuration_model', 'configuration');
        $this->load->model('question_model', 'question');

        $this->load->model('rating_model', 'rating');
        $this->load->model('response_model', 'resp');
        $this->load->model('comment_model', 'comment');
        $this->load->model('screenshot_model', 'screenshot');

        $project['artifacts'] = $this->artifact->getProject($project['projectID']);
        foreach ($project['artifacts'] as $key => $artifact) {
            $project['artifacts'][$key]['artifactURL'] = urldecode($artifact['artifactURL']);
        }
        $project['scenarios'] = $this->scenario->getProject($project['projectID']);
        $project['personas'] = $this->persona->getProject($project['projectID']);
        $project['roles'] = $this->role->getProject($project['projectID']);
        $project['completedAssessments'] = $this->assessment->getProjectCompleted($project['projectID']);
        $project['assessments'] = $this->assessment->getProject($project['projectID']);
        $project['counts'] = $this->assessment->getProjectStats($project['projectID']);

        foreach($project['assessments'] as $key => $assessment) {

            $project['assessments'][$key]['ratings'] = $this->rating->getAssessment($assessment['assessmentID']);
            $project['assessments'][$key]['responses'] = $this->resp->getAssessment($assessment['assessmentID']);

            foreach($project['assessments'][$key]['ratings'] as $ratingKey => $rating) {
                $project['assessments'][$key]['ratings'][$ratingKey]['comment'] = $this->comment->getRating($rating['ratingID']);
                $project['assessments'][$key]['ratings'][$ratingKey]['screenshots'] = $this->screenshot->getRating($rating['ratingID']);
            }
        }
        $project['configurations'] = $this->configuration->getProject($project['projectID']);
        foreach($project['configurations'] as $key => $configuration) {
            $project['configurations'][$key]['questions'] = $this->question->getQuestionConfiguration($configuration['questionConfigurationID']);
        }
        return $project;
    }
}
