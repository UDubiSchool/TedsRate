<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');
class Roles_api extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('role_model', 'role');
        $this->load->model('project_role_model', 'project_role');
    }

    // gets a record by primary key or retrieves all records
    public function api_get($key = NULL)
    {
        // echo $key;
        // exit;
        $tmp = null;
        $id = $key;
        if($id == null) {
            $tmp = $this->role->getAll();
            if (!$tmp) {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No roles were found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $id = (int) $id;
            if($id <= 0) {
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $tmp = $this->role->get($id);
                if (!$tmp) {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'role not found'
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
            'roleID' => intval($this->put('roleID')),
            'roleName' => $this->put('roleName'),
            'roleDesc' => $this->put('roleDesc'),
            'languageID' => intval($this->put('languageID'))
        ];
        $this->response($this->role->put($this->put()));
    }

    // creates a new record
    public function api_post($key = NULL, $xss_clean = NULL)
    {
        if($this->post('roleID')){
            // adding an exisiting role to a project
            $roleID = intval($this->post('roleID'));
            if($this->post('projectID')) {
                $data = [
                    'roleID' => $roleID,
                    'projectID' => intval($this->post('projectID'))
                ];
                if($this->project_role->post($data)){
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Role was associated with its project'
                    ], REST_Controller::HTTP_CREATED);
                } else {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'Role failed to associate with its project'
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
                'roleName' => $this->post('roleName'),
                'roleDesc' => $this->post('roleDesc'),
                'languageID' => intval($this->post('languageID'))
            ];
            $roleID = $this->role->post($data);
            if($roleID == false) {
                $this->response(false);
            } else {
                if($this->post('projectID')) {
                    $data = [
                        'roleID' => $roleID,
                        'projectID' => intval($this->post('projectID'))
                    ];
                    if($this->project_role->post($data)){
                        $this->response([
                            'status' => TRUE,
                            'message' => 'Role was created and associated with its project'
                        ], REST_Controller::HTTP_CREATED);
                    } else {
                        $this->response([
                            'status' => FALSE,
                            'message' => 'Role was created but failed when trying to associate it with its project'
                        ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                    }
                } else {
                    $this->response([
                        'status' => TRUE,
                        'message' => 'Role was created'
                    ], REST_Controller::HTTP_CREATED);
                }
            }
        }
    }

    // does a partial update of a record
    public function api_patch($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->role->patch($this->patch()));
    }

    // deletes a record
    public function api_delete($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->role->delete($key));
    }
}
