<?php
class Assessment_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
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
                                ->where('assessmentID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
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
                              ->get()
                              ->result_array();
    }

    public function getProject ($projectID)
    {
        return $this->db
                              ->select("a.assessmentID, a.assessmentIDHashed, a.completionDate, a.ratingURL, a.issuanceDate, a.lastEditDate, u.userID, u.email, u.firstName, u.lastName, c.configurationID, ac.assessmentConfigurationID, atrc.attributeConfigurationID, atrc.attributeConfigurationName, atrc.attributeConfigurationDesc, p.projectName, p.projectID, art.artifactID, art.artifactName, art.artifactURL, s.scenarioID, s.scenarioName, per.personaID, per.personaName, r.roleID, r.roleName")
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
                              ->select("a.assessmentID, a.assessmentIDHashed, a.completionDate, a.ratingURL, a.issuanceDate, a.lastEditDate, u.userID, u.email, u.firstName, u.lastName, c.configurationID, ac.assessmentConfigurationID, atrc.attributeConfigurationID, atrc.attributeConfigurationName, atrc.attributeConfigurationDesc, p.projectName, p.projectID, art.artifactID, art.artifactName, art.artifactURL, s.scenarioID, s.scenarioName, per.personaID, per.personaName, r.roleID, r.roleName")
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

    public function getProjectStats ($projectID)
    {
        $completed = $this->db->from("assessment a")
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
                              ->count_all_results();
        // $this->db->reset_query();
        $assessments = $this->db->from("assessment a")
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
                              ->count_all_results();
        return  [
          "assessments" => $assessments,
          "completed" => $completed
        ];
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