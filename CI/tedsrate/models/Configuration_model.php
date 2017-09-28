<?php
class Configuration_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("configuration c")
                                ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                                ->join('attributeConfiguration atrc', 'atrc.attributeConfigurationID = c.attributeConfigurationID')
                                ->join('questionConfiguration qc', 'qc.questionConfigurationID = c.questionConfigurationID')
                                ->join('uiConfiguration uc', 'uc.uiConfigurationID = c.uiConfigurationID')
                                ->join('project p', 'p.projectID = ac.projectID')
                                ->join('artifact art', 'art.artifactID = ac.artifactID')
                                ->join('scenario s', 's.scenarioID = ac.scenarioID')
                                ->join('persona per', 'per.personaID = ac.personaID')
                                ->join('role r', 'r.roleID = ac.roleID')
                                ->where('configurationID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("configuration c")
                              ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                              ->join('attributeConfiguration atrc', 'atrc.attributeConfigurationID = c.attributeConfigurationID')
                              ->join('questionConfiguration qc', 'qc.questionConfigurationID = c.questionConfigurationID')
                              ->join('uiConfiguration uc', 'uc.uiConfigurationID = c.uiConfigurationID')
                              ->join('project p', 'p.projectID = ac.projectID')
                              ->join('artifact art', 'art.artifactID = ac.artifactID')
                              ->join('scenario s', 's.scenarioID = ac.scenarioID')
                              ->join('persona per', 'per.personaID = ac.personaID')
                              ->join('role r', 'r.roleID = ac.roleID')
                              ->get()
                              ->result_array();
    }

    public function getProject ($projectID)
    {
        return $this->db
                              ->select("c.configurationID, c.configurationIDHashed, ac.assessmentConfigurationID, p.projectID, p.projectName, p.projectDescription, art.artifactID, art.artifactName, art.artifactDescription, art.artifactURL, s.scenarioID, s.scenarioName, s.scenarioDescription, per.personaID, per.personaName, per.personaDesc, r.roleID, r.roleName, r.roleDesc, atrc.attributeConfigurationID, atrc.attributeConfigurationName, atrc.attributeConfigurationDesc, qc.questionConfigurationID, qc.questionConfigurationName, qc.questionConfigurationDesc, uc.uiConfigurationID, uc.uiConfigurationName, uc.uiConfigurationDesc")
                              ->from("configuration c")
                              ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                              ->join('attributeConfiguration atrc', 'atrc.attributeConfigurationID = c.attributeConfigurationID')
                              ->join('questionConfiguration qc', 'qc.questionConfigurationID = c.questionConfigurationID')
                              ->join('uiConfiguration uc', 'uc.uiConfigurationID = c.uiConfigurationID')
                              ->join('project p', 'p.projectID = ac.projectID')
                              ->join('artifact art', 'art.artifactID = ac.artifactID')
                              ->join('scenario s', 's.scenarioID = ac.scenarioID')
                              ->join('persona per', 'per.personaID = ac.personaID')
                              ->join('role r', 'r.roleID = ac.roleID')
                              ->where("p.projectID", $projectID)
                              ->get()
                              ->result_array();
    }

    public function getUserAssessment ($configurationID, $userID)
    {
        return $this->db
                                ->from("assessment a")
                                ->where('configurationID', $configurationID)
                                ->where('userID', $userID)
                                ->get()
                                ->result_array();
    }


    public function post ($data)
    {
      $this->db->insert('configuration', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('configurationID', $data['configurationID'])
                ->update('configuration', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('configurationID', $id)
                ->delete('configuration');
    }
}
?>