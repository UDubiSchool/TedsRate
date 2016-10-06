<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');
class Screenshots_api extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('screenshot_model', 'screenshot');
    }

    // gets a record by primary key or retrieves all records
    public function api_get($key = NULL)
    {
        $tmp = null;
        $id = $key;
        if($id == null) {
            $tmp = $this->screenshot->getAll();
            if (!$tmp) {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No screenshots were found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $id = (int) $id;
            if($id <= 0) {
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $tmp = $this->screenshot->get($id);
                if (!$tmp) {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'screenshot not found'
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
            'screenshotID' => intval($this->put('screenshotID')),
            'screenshotPath' => $this->put('screenshotPath'),
            'screenshotDesc' => $this->put('screenshotDesc')
        ];
        $this->response($this->screenshot->put($data));
    }

    // creates a new record
    public function api_post($key = NULL, $xss_clean = NULL)
    {
        $data = [
            'screenshotPath' => $this->post('screenshotPath'),
            'ratingID' => $this->post('ratingID')
        ];
        if($this->post('screenshotDesc')){
            $data['screenshotDesc'] = $this->post('screenshotDesc');
        }
        $res['screenshotID'] = $this->screenshot->post($data);
        $this->response($res);

    }

    // does a partial update of a record
    public function api_patch($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->screenshot->patch($this->patch()));
    }

    // deletes a record
    public function api_delete($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->screenshot->delete($key));
    }
}
