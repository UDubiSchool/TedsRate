<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');
class Artifact_Types_api extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('artifact_type_model', 'artifact_type');
    }

    // gets a record by primary key or retrieves all records
    public function api_get($key = NULL)
    {
        $tmp = null;
        $id = $key;
        if($id == null) {
            $tmp = $this->artifact_type->getAll();
            if (!$tmp) {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No artifact types were found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $id = (int) $id;
            if($id <= 0) {
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $tmp = $this->artifact_type->get($id);
                if (!$tmp) {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'artifact type not found'
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            }
        }
       $this->response($tmp);
    }

    // does a full update of a record
    public function api_put($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->artifact_type->put($this->put()));
    }

    // creates a new record
    public function api_post($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->artifact_type->post($this->post()));
    }

    // does a partial update of a record
    public function api_patch($key = NULL, $xss_clean = NULL)
    {
        // $this->response($this->artifact_type->patch($this->patch()));
    }

    // deletes a record
    public function api_delete($key = NULL, $xss_clean = NULL)
    {
        // dumpArray($key);
        // $this->load->helper('url');
        // echo uri_string();
        $this->response($this->artifact_type->delete($key));
    }
}
