<?php
class Project_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("project")
                                ->where('projectID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("project")
                              ->order_by('projectID', 'desc')
                              ->get()
                              ->result_array();
    }

    public function getArtifacts ($id) {
        return $this->db
                            ->select('a.artifactID, a.artifactName, a.artifactDescription, a.artifactURL')
                            ->from('project p')
                            ->join('projectArtifact pa', 'pa.projectID = p.projectID')
                            ->join('artifact a', 'a.artifactID = pa.artifactID')
                            ->where('p.projectID', $id)
                            ->get()
                            ->result_array();
    }

    public function getScenarios ($id) {
        return $this->db
                            ->select('s.scenarioID, s.scenarioName, s.scenarioDescription')
                            ->from('project p')
                            ->join('project_scenario ps', 'ps.projectID = p.projectID')
                            ->join('scenario s', 's.scenarioID = ps.scenarioID')
                            ->where('p.projectID', $id)
                            ->get()
                            ->result_array();
    }

    public function getPersonas ($id) {
        return $this->db
                            ->select('pe.personaID, pe.personaName, pe.personaDesc')
                            ->from('project p')
                            ->join('project_persona pp', 'pp.projectID = p.projectID')
                            ->join('persona pe', 'pe.personaID = pp.personaID')
                            ->where('p.projectID', $id)
                            ->get()
                            ->result_array();
    }

    public function getRoles ($id) {
        return $this->db
                            ->select('r.roleID, r.roleName, r.roleDesc')
                            ->from('project p')
                            ->join('project_role pr', 'pr.projectID = p.projectID')
                            ->join('role r', 'r.roleID = pr.roleID')
                            ->where('p.projectID', $id)
                            ->get()
                            ->result_array();
    }

    public function getAssessments ($id) {
        return $this->db
                            ->select('a.assessmentID, a.userID, a.completionDate, a.issuanceDate, a.ratingURL, a.assessmentIDHashed, a.lastEditDate, atc.attributeConfigurationName, atc.attributeConfigurationDesc, p.projectName, art.artifactName, s.scenarioName, per.personaName, r.roleName, u.firstName, u.lastName, u.email')
                            ->from('assessment a')
                            ->join('configuration c', 'c.configurationID = a.configurationID')
                            ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                            ->join('attributeConfiguration atc', 'atc.attributeConfigurationID = c.attributeConfigurationID')
                            ->join('project p', 'p.projectID = ac.projectID')
                            ->join('artifact art', 'art.artifactID = ac.artifactID')
                            ->join('scenario s', 's.scenarioID = ac.scenarioID')
                            ->join('persona per', 'per.personaID = ac.personaID')
                            ->join('role r', 'r.roleID = ac.roleID')
                            ->join('user u', 'u.userID = a.userID')
                            ->where('ac.projectID', $id)
                            ->get()
                            ->result_array();
    }

    public function getCompletedAssessments ($id) {
        return $this->db
                            ->select('a.assessmentID, a.userID, a.completionDate, a.lastEditDate, a.ratingURL, a.assessmentIDHashed')
                            ->from('assessment a')
                            ->join('configuration c', 'c.configurationID = a.configurationID')
                            ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                            ->where('ac.projectID', $id)
                            ->where('a.completionDate IS NOT NULL')
                            ->get()
                            ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('project', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('projectID', $data['projectID'])
                ->update('project', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('projectID', $id)
                ->delete('project');
    }
}
?>