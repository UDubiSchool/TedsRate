<?php
class Project_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        $project = $this->db
                                ->from("project")
                                ->where('projectID', $id)
                                ->get()
                                ->result_array();
        return $project;
    }

    public function getAll ()
    {
        $projects = $this->db
                              ->from("project")
                              ->get()
                              ->result_array();
        return $projects;
    }

    public function getArtifacts ($id) {
        $artifacts = $this->db
                              ->select('a.artifactID, a.artifactName, a.artifactDescription')
                              ->from('project p')
                              ->join('projectArtifact pa', 'pa.projectID = p.projectID')
                              ->join('artifact a', 'a.artifactID = pa.artifactID')
                              ->where('p.projectID', $id)
                              ->get()
                              ->result_array();
        return $artifacts;
    }

    public function getScenarios ($id) {
        $scenarios = $this->db
                              ->select('s.scenarioID, s.scenarioName, s.scenarioDescription')
                              ->from('project p')
                              ->join('project_scenario ps', 'ps.projectID = p.projectID')
                              ->join('scenario s', 's.scenarioID = ps.scenarioID')
                              ->where('p.projectID', $id)
                              ->get()
                              ->result_array();
        return $scenarios;
    }

    public function getPersonas ($id) {
        $personas = $this->db
                              ->select('pe.personaID, pe.personaName, pe.personaDesc')
                              ->from('project p')
                              ->join('project_persona pp', 'pp.projectID = p.projectID')
                              ->join('persona pe', 'pe.personaID = pp.personaID')
                              ->where('p.projectID', $id)
                              ->get()
                              ->result_array();
        return $personas;
    }

    public function getRoles ($id) {
        $roles = $this->db
                              ->select('r.roleID, r.roleName, r.roleDesc')
                              ->from('project p')
                              ->join('project_role pr', 'pr.projectID = p.projectID')
                              ->join('role r', 'r.roleID = pr.roleID')
                              ->where('p.projectID', $id)
                              ->get()
                              ->result_array();
        return $roles;
    }

    public function put ($data)
    {
      $this->db->insert('project', $data);
    }

    public function post ($data)
    {
      $this->db
                ->where('id', $data['id'])
                ->update('project', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      $this->db
                ->where('id', $id)
                ->delete('project');
    }
}
?>