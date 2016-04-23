<?php
class Rating_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("rating")
                                ->where('ratingID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("rating")
                              ->get()
                              ->result_array();
    }

    public function getProjectArtifactAttributeScenario ($attributeID, $artifactID, $scenarioID, $projectID)
    {
        return $this->db
                              ->select('r.ratingID, r.ratingValue')
                              ->from("rating r")
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
                              ->select('r.ratingID, r.ratingValue')
                              ->from("rating r")
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

    public function getProjectConfigurationAttributeAssessment ($attributeID, $configurationID, $assessmentID, $projectID)
    {
        return $this->db
                              ->select('r.ratingID, r.ratingValue')
                              ->from("rating r")
                              ->join('assessment a', 'a.assessmentID = r.assessmentID')
                              ->join('configuration c', 'c.configurationID = a.configurationID')
                              ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                              ->where('c.configurationID', $configurationID)
                              ->where('a.assessmentID', $assessmentID)
                              ->where('ac.projectID', $projectID)
                              ->where('r.attributeID', $attributeID)
                              ->get()
                              ->result_array();
    }

    public function getAssessment ($assessmentID)
    {
        return $this->db
                              ->select("r.ratingID, r.ratingValue, at.attributeID, at.attributeName, at.attributeDesc, at.attributeLaymanDesc")
                              ->from("rating r")
                              ->join('assessment a', 'a.assessmentID = r.assessmentID')
                              ->join('attribute at', 'at.attributeID = r.attributeID')
                              ->where('a.assessmentID', $assessmentID)
                              ->order_by('at.attributeID', 'ASC')
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('rating', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('ratingID', $data['ratingID'])
                ->update('rating', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('ratingID', $id)
                ->delete('rating');
    }
}
?>