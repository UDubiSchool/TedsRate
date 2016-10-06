<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');
class QuestionConfigurations_api extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('questionConfiguration_model', 'questionConfiguration');
    }

    // gets a record by primary key or retrieves all records
    public function api_get($key = NULL)
    {
        $tmp = null;
        $id = $key;
        if($id == null) {
            $tmp = $this->questionConfiguration->getAll();
            if (!$tmp) {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No questionConfigurations were found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $id = (int) $id;
            if($id <= 0) {
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $tmp = $this->questionConfiguration->get($id);
                if (!$tmp) {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'questionConfiguration not found'
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            }
        }
       $this->response($tmp);
    }

    // does a full update of a record
    public function api_put($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->questionConfiguration->put($this->put()));
    }

    // creates a new record
    public function api_post($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->questionConfiguration->post($this->post()));
    }

    // does a partial update of a record
    public function api_patch($key = NULL, $xss_clean = NULL)
    {
        // $this->response($this->questionConfiguration->patch($this->patch()));
    }

    // deletes a record
    public function api_delete($key = NULL, $xss_clean = NULL)
    {
        dumpArray($key);
        // $this->load->helper('url');
        // echo uri_string();
        $this->response($this->questionConfiguration->delete($key));
    }
}
