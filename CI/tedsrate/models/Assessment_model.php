<?php
class Assessment_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("assessment")
                                ->where('assessmentID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("assessment")
                              ->get()
                              ->result_array();
    }

    public function getProject ($projectID)
    {
        return $this->db
                              ->from("assessment a")
                              ->join('configuration c', 'a.configurationID = c.configurationID')
                              ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                              ->join('attributeConfiguration atrc', 'atrc.attributeConfigurationID = c.attributeConfigurationID')
                              ->join('project p', 'p.projectID = ac.projectID')
                              ->join('artifact art', 'art.artifactID = ac.artifactID')
                              ->join('scenario s', 's.scenarioID = ac.scenarioID')
                              ->join('persona per', 'per.personaID = ac.personaID')
                              ->join('role r', 'r.roleID = ac.roleID')
                              ->join('user u', 'u.userID = a.userID')
                              ->where("p.projectID", $projectID)
                              ->get()
                              ->result_array();
    }

    public function getProjectCompleted ($projectID)
    {
        return $this->db
                              ->from("assessment a")
                              ->join('configuration c', 'a.configurationID = c.configurationID')
                              ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                              ->join('attributeConfiguration atrc', 'atrc.attributeConfigurationID = c.attributeConfigurationID')
                              ->join('project p', 'p.projectID = ac.projectID')
                              ->join('artifact art', 'art.artifactID = ac.artifactID')
                              ->join('scenario s', 's.scenarioID = ac.scenarioID')
                              ->join('persona per', 'per.personaID = ac.personaID')
                              ->join('role r', 'r.roleID = ac.roleID')
                              ->join('user u', 'u.userID = a.userID')
                              ->where("p.projectID", $projectID)
                              ->where("a.completionDate IS NOT NULL")
                              ->get()
                              ->result_array();
    }

    public function getProjectScenario ($projectID, $scenarioID)
    {
        return $this->db
                              ->from("assessment a")
                              ->join('configuration c', 'a.configurationID = c.configurationID')
                              ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                              ->join('project p', 'p.projectID = ac.projectID')
                              ->join('scenario s', 's.scenarioID = ac.scenarioID')
                              ->where("p.projectID", $projectID)
                              ->where("s.scenarioID", $scenarioID)
                              ->get()
                              ->result_array();
    }

    public function getProjectPersona ($projectID, $personaID)
    {
        return $this->db
                              ->from("assessment a")
                              ->join('configuration c', 'a.configurationID = c.configurationID')
                              ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                              ->join('project p', 'p.projectID = ac.projectID')
                              ->join('persona per', 'per.personaID = ac.personaID')
                              ->where("p.projectID", $projectID)
                              ->where("per.personaID", $personaID)
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('assessment', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('assessmentID', $data['assessmentID'])
                ->update('assessment', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('assessmentID', $id)
                ->delete('assessment');
    }
}
?>