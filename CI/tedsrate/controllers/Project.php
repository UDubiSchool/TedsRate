<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('project_model', 'project');
        $this->load->model('assessment_model', 'assessment');
    }

    // public function get($id = null, $assoc = false)
    public function getBasic($id = null)
    {

        if($id == null || $id == 0) {
            $tmp = $this->project->getAll();
        } else {
            $tmp = $this->project->get($id);
        }

        foreach ($tmp as $key => $project) {
            // $stats = $this->assessment->getProjectStats($project['projectID']);
            $tmp[$key]['counts'] = $this->assessment->getProjectStats($project['projectID']);
        }
        echoJSON($tmp);
    }


    // private function getAssoc($project)
    // {
    //     $project['artifacts'] = $this->project->getArtifacts($project['projectID']);
    //     $project['scenarios'] = $this->project->getScenarios($project['projectID']);
    //     $project['personas'] = $this->project->getPersonas($project['projectID']);
    //     $project['roles'] = $this->project->getRoles($project['projectID']);
    //     return $project;
    // }
}
