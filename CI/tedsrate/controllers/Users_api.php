<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');
class Users_api extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model', 'user');
    }

    public function api_get()
    {
        $tmp = null;
        $id = $this->get('id');
        if($id === null) {
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

    public function api_put($key = null, $xss_clean = NULL)
    {
        $id = $this->get('id');
    }

    public function api_post($key = null, $xss_clean = NULL)
    {

    }

    public function api_delete($key = null, $xss_clean = NULL)
    {

    }

    private function getAssoc($project)
    {
        $project['artifacts'] = $this->project->getArtifacts($project['projectID']);
        $project['scenarios'] = $this->project->getScenarios($project['projectID']);
        $project['personas'] = $this->project->getPersonas($project['projectID']);
        $project['roles'] = $this->project->getRoles($project['projectID']);
        return $project;
    }
}
