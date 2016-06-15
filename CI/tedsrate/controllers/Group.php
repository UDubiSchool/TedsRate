<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('group_model', 'group');
    }

    public function getProject($id)
    {
        $tmp = $this->group->getProject($id);
        echoJSON($tmp);
    }

    public function getTypes()
    {
        $tmp = $this->group->getTypes();
        echoJSON($tmp);
    }

    public function getUser($groupID, $userID)
    {
        $this->load->model('configuration_model', 'configuration');
        $tmp = $this->group->get($groupID);
        foreach ($tmp as $key => $value) {
            $tmp[$key]['configurations'] = $this->group->getconfigurations($groupID);
            foreach ($tmp[$key]['configurations'] as $key2 => $value2) {
                $assessment = $this->configuration->getUserAssessment($value2['configurationID'], $userID);
                $tmp[$key]['configurations'][$key2]['assessment'] = ($assessment ? $assessment[0] : null);
            }

        }
        echoJSON($tmp);
    }

    public function getUserStats($groupID, $userID)
    {
        $tmp = $this->group->getUserStats($groupID, $userID);
        echoJSON($tmp);
    }
}
