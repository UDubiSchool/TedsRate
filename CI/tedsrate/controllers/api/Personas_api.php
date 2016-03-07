<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');
class Personas_api extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('persona_model', 'persona');
        $this->load->model('project_persona_model', 'project_persona');
    }

    // gets a record by primary key or retrieves all records
    public function api_get($key = NULL)
    {
        // echo $key;
        // exit;
        $tmp = null;
        $id = $key;
        if($id == null) {
            $tmp = $this->persona->getAll();
            if (!$tmp) {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No personas were found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $id = (int) $id;
            if($id <= 0) {
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $tmp = $this->persona->get($id);
                if (!$tmp) {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'persona not found'
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
            'personaID' => intval($this->put('personaID')),
            'personaName' => $this->put('personaName'),
            'personaDesc' => $this->put('personaDesc'),
            'languageID' => intval($this->put('languageID'))
        ];
        $this->response($this->persona->put($this->put()));
    }

    // creates a new record
    public function api_post($key = NULL, $xss_clean = NULL)
    {
        if($this->post('personaID')){
            // adding an exisiting persona to a project
            $personaID = intval($this->post('personaID'));
            if($this->post('projectID')) {
                $data = [
                    'personaID' => $personaID,
                    'projectID' => intval($this->post('projectID'))
                ];
                if($this->project_persona->post($data)){
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Persona was associated with its project'
                    ], REST_Controller::HTTP_CREATED);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Persona failed to associate with its project'
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
                'personaName' => $this->post('personaName'),
                'personaDesc' => $this->post('personaDesc'),
                'languageID' => intval($this->post('languageID'))
            ];
            $personaID = $this->persona->post($data);
            if($personaID == false) {
                $this->response(false);
            } else {
                if($this->post('projectID')) {
                    $data = [
                        'personaID' => $personaID,
                        'projectID' => intval($this->post('projectID'))
                    ];
                    if($this->project_persona->post($data)){
                        $this->response([
                            'status' => TRUE,
                            'message' => 'Persona was created and associated with its project'
                        ], REST_Controller::HTTP_CREATED);
                    } else {
                        $this->response([
                            'status' => FALSE,
                            'message' => 'Persona was created but failed when trying to associate it with its project'
                        ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    }
                } else {
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Persona was created'
                    ], REST_Controller::HTTP_CREATED);
                }
            }
        }
    }

    // does a partial update of a record
    public function api_patch($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->persona->patch($this->patch()));
    }

    // deletes a record
    public function api_delete($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->persona->delete($key));
    }
}
