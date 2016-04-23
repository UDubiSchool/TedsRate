<?php
class Comment_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("comment")
                                ->where('commentID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("comment")
                              ->get()
                              ->result_array();
    }

    public function getRating ($ratingID)
    {
        return $this->db
                              ->select('c.commentID, c.comment, c.dateCreated')
                              ->from("comment c")
                              ->join('rating r', 'r.ratingID = c.ratingID')
                              ->where('r.ratingID', $ratingID)
                              ->get()
                              ->result_array();
    }

    public function getProjectArtifactAttributeScenario ($attributeID, $artifactID, $scenarioID, $projectID)
    {
        return $this->db
                              ->select('cm.commentID, cm.comment, cm.dateCreated')
                              ->from("comment cm")
                              ->join('rating r', 'r.ratingID = cm.ratingID')
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
                              ->select('cm.commentID, cm.comment, cm.dateCreated')
                              ->from("comment cm")
                              ->join('rating r', 'r.ratingID = cm.ratingID')
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
                              ->select('cm.commentID, cm.comment, cm.dateCreated')
                              ->from("comment cm")
                              ->join('rating r', 'r.ratingID = cm.ratingID')
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
                              ->select('cm.commentID, cm.comment, cm.dateCreated')
                              ->from("comment cm")
                              ->join('rating r', 'r.ratingID = cm.ratingID')
                              ->join('assessment a', 'a.assessmentID = r.assessmentID')
                              ->where('a.assessmentID', $assessmentID)
                              ->get()
                              ->result_array();
    }

    public function getConfiguration ($configurationID)
    {
        return $this->db
                              ->select('cm.commentID, cm.comment, cm.dateCreated')
                              ->from("comment cm")
                              ->join('rating r', 'r.ratingID = cm.ratingID')
                              ->join('assessment a', 'a.assessmentID = r.assessmentID')
                              ->join('configuration c', 'c.configurationID = a.configurationID')
                              ->where('c.configurationID', $configurationID)
                              ->get()
                              ->result_array();
    }

    public function getArtifact ($artifactID)
    {
        return $this->db
                              ->select('cm.commentID, cm.comment, cm.dateCreated')
                              ->from("comment cm")
                              ->join('rating r', 'r.ratingID = cm.ratingID')
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
                              ->select('cm.commentID, cm.comment, cm.dateCreated')
                              ->from("comment cm")
                              ->join('rating r', 'r.ratingID = cm.ratingID')
                              ->join('assessment a', 'a.assessmentID = r.assessmentID')
                              ->join('configuration c', 'c.configurationID = a.configurationID')
                              ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                              ->where('ac.scenarioID', $scenarioID)
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('comment', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('commentID', $data['commentID'])
                ->update('comment', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('commentID', $id)
                ->delete('comment');
    }
}
?>