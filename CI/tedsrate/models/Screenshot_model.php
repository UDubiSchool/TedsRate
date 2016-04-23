<?php
class Screenshot_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("screenshot")
                                ->where('screenshotID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("screenshot")
                              ->get()
                              ->result_array();
    }

    public function getRating ($ratingID)
    {
        return $this->db
                              ->select('s.screenshotID, s.screenshotPath, s.screenshotDesc, s.dateCreated')
                              ->from("screenshot s")
                              ->join('rating r', 'r.ratingID = s.ratingID')
                              ->where('r.ratingID', $ratingID)
                              ->get()
                              ->result_array();
    }

    public function getProjectArtifactAttributeScenario ($attributeID, $artifactID, $scenarioID, $projectID)
    {
        return $this->db
                              ->select('s.screenshotID, s.screenshotPath, s.screenshotDesc, s.dateCreated')
                              ->from("screenshot s")
                              ->join('rating r', 'r.ratingID = s.ratingID')
                              ->join('assessment a', 'a.assessmentID = r.assessmentID')
                              ->join('configuration c', 'c.configurationID = a.configurationID')
                              ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                              ->where('ac.artifactID', $artifactID)
                              ->where('ac.scenarioID', $scenarioID)
                              ->where('ac.projectID', $projectID)
                              ->where('r.attributeID', $attributeID)
                              ->get()
                              ->result_array();
    }

    public function getProjectScenarioAttributeArtifact ($attributeID, $scenarioID, $artifactID, $projectID)
    {
        return $this->db
                              ->select('s.screenshotID, s.screenshotPath, s.screenshotDesc, s.dateCreated')
                              ->from("screenshot s")
                              ->join('rating r', 'r.ratingID = s.ratingID')
                              ->join('assessment a', 'a.assessmentID = r.assessmentID')
                              ->join('configuration c', 'c.configurationID = a.configurationID')
                              ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                              ->where('ac.scenarioID', $scenarioID)
                              ->where('ac.artifactID', $artifactID)
                              ->where('ac.projectID', $projectID)
                              ->where('r.attributeID', $attributeID)
                              ->get()
                              ->result_array();
    }

    public function getProjectConfigurationAttribute ($attributeID, $configurationID, $projectID)
    {
        return $this->db
                              ->select('s.screenshotID, s.screenshotPath, s.screenshotDesc, s.dateCreated')
                              ->from("screenshot s")
                              ->join('rating r', 'r.ratingID = s.ratingID')
                              ->join('assessment a', 'a.assessmentID = r.assessmentID')
                              ->join('configuration c', 'c.configurationID = a.configurationID')
                              ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                              ->where('c.configurationID', $configurationID)
                              ->where('ac.projectID', $projectID)
                              ->where('r.attributeID', $attributeID)
                              ->get()
                              ->result_array();
    }

    public function getAssessment ($assessmentID)
    {
        return $this->db
                              ->select('s.screenshotID, s.screenshotPath, s.screenshotDesc, s.dateCreated')
                              ->from("screenshot s")
                              ->join('rating r', 'r.ratingID = s.ratingID')
                              ->join('assessment a', 'a.assessmentID = r.assessmentID')
                              ->where('a.assessmentID', $assessmentID)
                              ->get()
                              ->result_array();
    }

    public function getConfiguration ($configurationID)
    {
        return $this->db
                              ->select('s.screenshotID, s.screenshotPath, s.screenshotDesc, s.dateCreated')
                              ->from("screenshot s")
                              ->join('rating r', 'r.ratingID = s.ratingID')
                              ->join('assessment a', 'a.assessmentID = r.assessmentID')
                              ->join('configuration c', 'c.configurationID = a.configurationID')
                              ->where('c.configurationID', $configurationID)
                              ->get()
                              ->result_array();
    }

    public function getArtifact ($artifactID)
    {
        return $this->db
                              ->select('s.screenshotID, s.screenshotPath, s.screenshotDesc, s.dateCreated')
                              ->from("screenshot s")
                              ->join('rating r', 'r.ratingID = s.ratingID')
                              ->join('assessment a', 'a.assessmentID = r.assessmentID')
                              ->join('configuration c', 'c.configurationID = a.configurationID')
                              ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                              ->where('ac.artifactID', $artifactID)
                              ->get()
                              ->result_array();
    }

    public function getScenario ($scenarioID)
    {
        return $this->db
                              ->select('s.screenshotID, s.screenshotPath, s.screenshotDesc, s.dateCreated')
                              ->from("screenshot s")
                              ->join('rating r', 'r.ratingID = s.ratingID')
                              ->join('assessment a', 'a.assessmentID = r.assessmentID')
                              ->join('configuration c', 'c.configurationID = a.configurationID')
                              ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                              ->where('ac.scenarioID', $scenarioID)
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('screenshot', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('screenshotID', $data['screenshotID'])
                ->update('screenshot', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('screenshotID', $id)
                ->delete('screenshot');
    }
}
?>