<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');
class Artifacts_api extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('artifact_model', 'artifact');
        $this->load->model('project_artifact_model', 'project_artifact');
    }

    // gets a record by primary key or retrieves all records
    public function api_get($key = NULL)
    {
        // echo $key;
        // exit;
        $tmp = null;
        $id = $key;
        if($id == null) {
            $tmp = $this->artifact->getAll();
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
                $tmp = $this->artifact->get($id);
                $tmp[0]['artifactURL'] = urldecode($tmp[0]['artifactURL']);
                if (!$tmp) {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'artifact not found'
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
            'artifactID' => intval($this->put('artifactID')),
            'artifactName' => $this->put('artifactName'),
            'artifactDescription' => $this->put('artifactDescription'),
            'artifactTypeID' => intval($this->put('artifactTypeID')),
            'languageID' => intval($this->put('languageID'))
        ];
        $this->response($this->artifact->put($this->put()));
    }

    // creates a new record
    public function api_post($key = NULL, $xss_clean = NULL)
    {
        if($this->post('artifactID')){
            // adding an exisiting artifact to a project
            $artifactID = intval($this->post('artifactID'));
            if($this->post('projectID')) {
                $data = [
                    'artifactID' => $artifactID,
                    'projectID' => intval($this->post('projectID'))
                ];
                if($this->project_artifact->post($data)){
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Artifact was associated with its project'
                    ], REST_Controller::HTTP_CREATED);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Artifact failed to associate with its project'
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
                'artifactName' => $this->post('artifactName'),
                'artifactDescription' => $this->post('artifactDescription'),
                'artifactURL' => urlencode($this->post('artifactURL')),
                'artifactTypeID' => intval($this->post('artifactTypeID')),
                'languageID' => intval($this->post('languageID'))
            ];
            $artifactID = $this->artifact->post($data);
            if($artifactID == false) {
                $this->response(false);
            } else {
                if($this->post('projectID')) {
                    $data = [
                        'artifactID' => $artifactID,
                        'projectID' => intval($this->post('projectID'))
                    ];
                    if($this->project_artifact->post($data)){
                        $this->response([
                            'status' => TRUE,
                            'message' => 'Artifact was created and associated with its project'
                        ], REST_Controller::HTTP_CREATED);
                    } else {
                        $this->response([
                            'status' => FALSE,
                            'message' => 'Artifact was created but failed when trying to associate it with its project'
                        ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    }
                } else {
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Artifact was created'
                    ], REST_Controller::HTTP_CREATED);
                }
            }
        }

    }

    // does a partial update of a record
    public function api_patch($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->artifact->patch($this->patch()));
    }

    // deletes a record
    public function api_delete($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->artifact->delete($key));
    }
}
