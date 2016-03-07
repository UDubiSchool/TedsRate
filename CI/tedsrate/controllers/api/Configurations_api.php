<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'libraries/REST_Controller.php');
class Configurations_api extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('configuration_model', 'configuration');
    }

    // gets a record by primary key or retrieves all records
    public function api_get($key = NULL)
    {
        // echo $key;
        // exit;
        $tmp = null;
        $id = $key;
        if($id == null) {
            $tmp = $this->configuration->getAll();
            if (!$tmp) {
                $this->response([
                    'status' => FALSE,
                    'message' => 'No configurations were found'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $id = (int) $id;
            if($id <= 0) {
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $tmp = $this->configuration->get($id);
                if (!$tmp) {
                    $this->response([
                        'status' => FALSE,
                        'message' => 'configuration not found'
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
            'configurationID' => intval($this->put('configurationID')),
            'attributeConfigurationID' => intval($this->put('attributeConfigurationID')),
            'assessmentConfigurationID' => intval($this->put('assessmentConfigurationID')),
            'uiConfigurationID' => intval($this->put('uiConfigurationID')),
            'questionConfigurationID' => intval($this->put('questionConfigurationID'))
        ];
        $this->response($this->configuration->put($this->put()));
    }

    // creates a new record
    public function api_post($key = NULL, $xss_clean = NULL)
    {
        $this->load->model('assessmentConfiguration_model', 'assessmentConfiguration');
        // $this->load->model('attributeConfiguration_model', 'attributeConfiguration');
        // $this->load->model('uiConfiguration_model', 'uiConfiguration');
        // $this->load->model('questionConfiguration_model', 'questionConfiguration');



        if($this->post('configurationID')){
            // // adding an exisiting configuration to a project
            // $configurationID = intval($this->post('configurationID'));
            // if($this->post('projectID')) {
            //     $data = [
            //         'configurationID' => $configurationID,
            //         'projectID' => intval($this->post('projectID'))
            //     ];
            //     if($this->project_configuration->post($data)){
            //         $this->response([
            //             'status' => TRUE,
            //             'message' => 'Artifact was associated with its project'
            //         ], REST_Controller::HTTP_CREATED);
            //     } else {
            //         $this->response([
            //             'status' => FALSE,
            //             'message' => 'Artifact failed to associate with its project'
            //         ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            //     }
            // } else {
            //     $this->response([
            //         'status' => FALSE,
            //         'message' => 'ProjectID was not supplied'
            //     ], REST_Controller::HTTP_BAD_REQUEST);
            // }
        } else {
            $data = [
                'configurationID' => intval($this->post('configurationID')),
                'attributeConfigurationID' => intval($this->post('attributeConfigurationID')),
                // 'assessmentConfigurationID' => intval($this->post('assessmentConfigurationID')),
                'uiConfigurationID' => intval($this->post('uiConfigurationID')),
                'questionConfigurationID' => intval($this->post('questionConfigurationID'))
            ];
            if($this->post('assessmentConfigurationID')) {
                $data['assessmentConfigurationID'] = intval($this->post('assessmentConfigurationID'));
            } elseif ($this->post('projectID') && $this->post('artifactID') && $this->post('scenarioID') && $this->post('personaID') && $this->post('roleID')) {
                $assessmentConfigurationData = [
                    'projectID' => intval($this->post('projectID')),
                    'artifactID' => intval($this->post('artifactID')),
                    'scenarioID' => intval($this->post('scenarioID')),
                    'personaID' => intval($this->post('personaID')),
                    'roleID' => intval($this->post('roleID')),
                ];
                $data['assessmentConfigurationID'] = $this->assessmentConfiguration->post($assessmentConfigurationData);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'You did not provide all required fields'
                ], REST_Controller::HTTP_NOT_ACCEPTABLE);
            }

            $configurationID = $this->configuration->post($data);

            if($configurationID == false) {
                $this->response([
                            'status' => FALSE,
                            'message' => 'Configuration failed to be created'
                        ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            } else {
                $confHash = hash('sha256', $configurationID);
                $update = [
                    'configurationID' => $configurationID,
                    'configurationIDHashed' => $confHash
                ];
                if($this->configuration->put($update)) {
                    $this->response([
                                'status' => TRUE,
                                'data' => ['id' => $configurationID],
                                'message' => 'Configuration was created'
                            ], REST_Controller::HTTP_CREATED);
                } else {
                    $this->response([
                                'status' => FALSE,
                                'message' => 'Configuration was created but failed in creating hash'
                            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }

            }
        }

    }

    // does a partial update of a record
    public function api_patch($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->configuration->patch($this->patch()));
    }

    // deletes a record
    public function api_delete($key = NULL, $xss_clean = NULL)
    {
        $this->response($this->configuration->delete($key));
    }
}
