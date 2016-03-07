<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');
class Assessments_api extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('assessment_model', 'assessment');
    }

    // gets a record by primary key or retrieves all records
    public function api_get($key = NULL)
    {
        // echo $key;
        // exit;
        $tmp = null;
        $id = $key;
        if($id == null) {
            $tmp = $this->assessment->getAll();
            if (!$tmp) {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No assessments were found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $id = (int) $id;
            if($id <= 0) {
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $tmp = $this->assessment->get($id);
                if (!$tmp) {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'assessment not found'
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
            'assessmentID' => intval($this->put('assessmentID')),
            'configurationID' => intval($this->put('configurationID')),
            'userID' => intval($this->put('userID'))
        ];
        $this->response($this->assessment->put($this->put()));
    }

    // creates a new record
    public function api_post($key = NULL, $xss_clean = NULL)
    {
        $this->load->model('assessment_model', 'assessment');

        $data = [
            'assessmentID' => intval($this->post('assessmentID')),
            'configurationID' => intval($this->post('configurationID')),
            'userID' => intval($this->post('userID'))
        ];

        $assessmentID = $this->assessment->post($data);

        if($assessmentID == false) {
            $this->response([
                        'status' => FALSE,
                        'message' => 'assessment failed to be created'
                    ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            $assessmentHash = hash('sha256', $assessmentID);
            $update = [
                'assessmentID' => $assessmentID,
                'assessmentIDHashed' => $assessmentHash
            ];
            if($this->assessment->put($update)) {
                $this->response([
                            'status' => TRUE,
                            'data' => ['id' => $assessmentID],
                            'message' => 'assessment was created'
                        ], REST_Controller::HTTP_CREATED);
            } else {
                $this->response([
                            'status' => FALSE,
                            'message' => 'assessment was created but failed in creating hash'
                        ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    // does a partial update of a record
    public function api_patch($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->assessment->patch($this->patch()));
    }

    // deletes a record
    public function api_delete($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->assessment->delete($key));
    }
}
