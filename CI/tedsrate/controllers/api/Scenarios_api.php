<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');
class Scenarios_api extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('scenario_model', 'scenario');
        $this->load->model('project_scenario_model', 'project_scenario');
    }

    // gets a record by primary key or retrieves all records
    public function api_get($key = NULL)
    {
        // echo $key;
        // exit;
        $tmp = null;
        $id = $key;
        if($id == null) {
            $tmp = $this->scenario->getAll();
            if (!$tmp) {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No scenarios were found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $id = (int) $id;
            if($id <= 0) {
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $tmp = $this->scenario->get($id);
                if (!$tmp) {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'scenario not found'
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            }
        }
       $this->response($tmp);
    }

    // does a full update of a record
    public function api_put($key = NULL, $xss_clean = NULL)
    {
        $data = [
            'scenarioID' => intval($this->put('scenarioID')),
            'scenarioName' => $this->put('scenarioName'),
            'scenarioDescription' => $this->put('scenarioDescription'),
            'languageID' => intval($this->put('languageID'))
        ];
        $this->response($this->scenario->put($this->put()));
    }

    // creates a new record
    public function api_post($key = NULL, $xss_clean = NULL)
    {
        if($this->post('scenarioID')){
            // adding an exisiting scenario to a project
            $scenarioID = intval($this->post('scenarioID'));
            if($this->post('projectID')) {
                $data = [
                    'scenarioID' => $scenarioID,
                    'projectID' => intval($this->post('projectID'))
                ];
                if($this->project_scenario->post($data)){
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Scenario was associated with its project'
                    ], REST_Controller::HTTP_CREATED);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Scenario failed to associate with its project'
                    ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'ProjectID was not supplied'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $data = [
                'scenarioName' => $this->post('scenarioName'),
                'scenarioDescription' => $this->post('scenarioDescription'),
                'languageID' => intval($this->post('languageID'))
            ];
            $scenarioID = $this->scenario->post($data);
            if($scenarioID == false) {
                $this->response(false);
            } else {
                if($this->post('projectID')) {
                    $data = [
                        'scenarioID' => $scenarioID,
                        'projectID' => intval($this->post('projectID'))
                    ];
                    if($this->project_scenario->post($data)){
                        $this->response([
                            'status' => TRUE,
                            'message' => 'Scenario was created and associated with its project'
                        ], REST_Controller::HTTP_CREATED);
                    } else {
                        $this->response([
                            'status' => FALSE,
                            'message' => 'Scenario was created but failed when trying to associate it with its project'
                        ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    }
                } else {
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Scenario was created'
                    ], REST_Controller::HTTP_CREATED);
                }
            }
        }
    }

    // does a partial update of a record
    public function api_patch($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->scenario->patch($this->patch()));
    }

    // deletes a record
    public function api_delete($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->scenario->delete($key));
    }
}
