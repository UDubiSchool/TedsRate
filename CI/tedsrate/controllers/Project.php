<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('project_model', 'project');
    }

    public function get($id = null, $assoc = false)
    {

        if($id == null || $id == 0) {
            $tmp = $this->project->getAll();
        } else {
            $tmp = $this->project->get($id);
        }

        if($assoc == 'true') {
            foreach ($tmp as $key => $value) {
                $tmp[$key] = $this->getAssoc($value);
            }
        }
        // $data['projects'] = $tmp;
        echoJSON($tmp);
    }

    public function put()
    {

    }

    public function post()
    {

    }

    public function delete()
    {

    }

    private function getAssoc($project)
    {
        $project['artifacts'] = $this->project->getArtifacts($project['projectID']);
        $project['scenarios'] = $this->project->getScenarios($project['projectID']);
        $project['personas'] = $this->project->getPersonas($project['projectID']);
        $project['roles'] = $this->project->getRoles($project['projectID']);
        return $project;
    }
}
