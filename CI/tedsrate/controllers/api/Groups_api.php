<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');
class Groups_api extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('group_model', 'group');
    }

    // gets a record by primary key or retrieves all records
    public function api_get($key = NULL)
    {
        // echo $key;
        // exit;
        $tmp = null;
        $id = $key;
        if($id == null) {
            $tmp = $this->group->getAll();
            if (!$tmp) {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No groups were found'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                foreach ($tmp as $groupKey => $group) {
                    $tmp[$groupKey]['configurations'] = $this->group->getConfigurations($group['groupID']);
                }
            }
        } else {
            $id = (int) $id;
            if($id <= 0) {
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $tmp = $this->group->get($id);
                if (!$tmp) {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'group not found'
                    ], REST_Controller::HTTP_NOT_FOUND);
                } else {
                    $tmp[0]['configurations'] = $this->group->getConfigurations($id);
                }
            }
        }
       $this->response($tmp);
    }

    // does a full update of a record
    public function api_put($key = NULL, $xss_clean = NULL)
    {
        $data = [
            'groupID' => intval($this->put('groupID')),
            'groupTypeID' => intval($this->put('groupTypeID')),
            'groupName' => $this->put('groupName'),
            'groupDesc' => $this->put('groupDesc'),
            'groupWelcomeTemplate' => $this->put('groupWelcomeTemplate')
        ];
        $this->response($this->group->put($this->put()));
    }

    // creates a new record
    public function api_post($key = NULL, $xss_clean = NULL)
    {
        if($this->post('groupID')){
            // // adding an exisiting group to a project
        } else {
            $data = [
                'groupTypeID' => intval($this->post('groupTypeID')),
                'groupName' => $this->post('groupName'),
                'groupDesc' => $this->post('groupDesc'),
                'groupWelcomeTemplate' => $this->post('groupWelcomeTemplate')
            ];
            $groupID = $this->group->post($data);


            if($this->post('configurations')) {
                foreach ($this->post('configurations') as $configurationKey => $configuration) {
                    $configurationID = intval($configuration['configurationID']);
                    $this->group->addConfiguration(['groupID' => $groupID, 'configurationID' => $configurationID]);
                }
            }
            if($this->post('lotteryJackpot')) {
                $lotteryData = [
                    'groupID' => $groupID,
                    'lotteryJackpot' => $this->post('lotteryJackpot'),
                    'lotterySecond' => $this->post('lotterySecond'),
                    'lotteryThird' => $this->post('lotteryThird'),
                    'lotterySecondAmount' => $this->post('lotterySecondAmount'),
                    'lotteryThirdAmount' => $this->post('lotteryThirdAmount'),
                    'lotteryStartDate' => $this->post('lotteryStartDate'),
                    'lotteryEndDate' => $this->post('lotteryEndDate'),
                    'lotteryTicketsPerAssessment' => $this->post('lotteryTicketsPerAssessment'),
                    'lotteryTicketsPerShare' => $this->post('lotteryTicketsPerShare'),
                    'lotteryTicketsPerComment' => $this->post('lotteryTicketsPerComment'),
                    'lotteryTicketsPerScreenshot' => $this->post('lotteryTicketsPerScreenshot')
                ];
                $groupID = $this->group->addLottery($lotteryData);

            }



            if($groupID == false) {
                $this->response([
                            'status' => FALSE,
                            'message' => 'group failed to be created'
                        ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            } else {
                $this->response([
                            'status' => TRUE,
                            'data' => ['id' => $groupID],
                            'message' => 'group was created'
                        ], REST_Controller::HTTP_CREATED);
            }
        }
    }

    // does a partial update of a record
    public function api_patch($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->group->patch($this->patch()));
    }

    // deletes a record
    public function api_delete($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->group->delete($key));
    }
}
