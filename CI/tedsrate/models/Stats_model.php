<?php
class Stats_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function byArtifact ($projectID, $artifactID)
    {
        return $this->db
                                ->select(' attr.attributeID, attr.attributeName, attr.attributeLaymanDesc as attributeDesc, s.scenarioID, s.scenarioName, s.scenarioDescription, AVG(r.ratingValue) as AttributeAverage, STDDEV_SAMP(r.ratingValue) as AttributeStandardDeviation')
                                ->from("project p")
                                ->join('assessmentConfiguration ac', 'ac.projectID = p.projectID')
                                ->join('artifact art', 'art.artifactID = ac.artifactID')
                                ->join('scenario s', 's.scenarioID = ac.scenarioID')
                                ->join('configuration c', 'c.assessmentConfigurationID = ac.assessmentConfigurationID')
                                ->join('assessment a', 'a.configurationID = c.configurationID')
                                ->join('rating r', 'r.assessmentID = a.assessmentID')
                                ->join('attribute attr', 'attr.attributeID = r.attributeID')
                                ->where('p.projectID', $projectID)
                                ->where('ac.artifactID', $artifactID)
                                ->group_by(array("attr.attributeID", "s.scenarioID"))
                                ->get()
                                ->result_array();
    }

    public function byScenario ($projectID, $scenarioID)
    {
        return $this->db
                                ->select(' attr.attributeID, attr.attributeName, attr.attributeLaymanDesc as attributeDesc, art.artifactID, art.artifactName, art.artifactDescription, AVG(r.ratingValue) as AttributeAverage, STDDEV_SAMP(r.ratingValue) as AttributeStandardDeviation')
                                ->from("project p")
                                ->join('assessmentConfiguration ac', 'ac.projectID = p.projectID')
                                ->join('artifact art', 'art.artifactID = ac.artifactID')
                                ->join('scenario s', 's.scenarioID = ac.scenarioID')
                                ->join('configuration c', 'c.assessmentConfigurationID = ac.assessmentConfigurationID')
                                ->join('assessment a', 'a.configurationID = c.configurationID')
                                ->join('rating r', 'r.assessmentID = a.assessmentID')
                                ->join('attribute attr', 'attr.attributeID = r.attributeID')
                                ->where('p.projectID', $projectID)
                                ->where('ac.scenarioID', $scenarioID)
                                ->group_by(array("attr.attributeID", "ac.artifactID"))
                                ->get()
                                ->result_array();
    }

    public function byProject ($projectID)
    {
        return $this->db
                                ->select(' attr.attributeID, attr.attributeName, attr.attributeLaymanDesc as attributeDesc, AVG(r.ratingValue) as AttributeAverage, STDDEV_SAMP(r.ratingValue) as AttributeStandardDeviation')
                                ->from("project p")
                                ->join('assessmentConfiguration ac', 'ac.projectID = p.projectID')
                                ->join('artifact art', 'art.artifactID = ac.artifactID')
                                ->join('scenario s', 's.scenarioID = ac.scenarioID')
                                ->join('configuration c', 'c.assessmentConfigurationID = ac.assessmentConfigurationID')
                                ->join('assessment a', 'a.configurationID = c.configurationID')
                                ->join('rating r', 'r.assessmentID = a.assessmentID')
                                ->join('attribute attr', 'attr.attributeID = r.attributeID')
                                ->where('p.projectID', $projectID)
                                ->group_by(array("attr.attributeID"))
                                ->get()
                                ->result_array();
    }

    public function byConfiguration ($projectID, $configurationID)
    {
        return $this->db
                                ->select(' attr.attributeID, attr.attributeName, attr.attributeLaymanDesc as attributeDesc, art.artifactID, art.artifactName, art.artifactDescription, s.scenarioID, s.scenarioName, s.scenarioDescription, AVG(r.ratingValue) as AttributeAverage, STDDEV_SAMP(r.ratingValue) as AttributeStandardDeviation')
                                ->from("project p")
                                ->join('assessmentConfiguration ac', 'ac.projectID = p.projectID')
                                ->join('artifact art', 'art.artifactID = ac.artifactID')
                                ->join('scenario s', 's.scenarioID = ac.scenarioID')
                                ->join('configuration c', 'c.assessmentConfigurationID = ac.assessmentConfigurationID')
                                ->join('assessment a', 'a.configurationID = c.configurationID')
                                ->join('rating r', 'r.assessmentID = a.assessmentID')
                                ->join('attribute attr', 'attr.attributeID = r.attributeID')
                                ->where('p.projectID', $projectID)
                                ->where('c.configurationID', $configurationID)
                                ->group_by(array("attr.attributeID", "c.configurationID"))
                                ->get()
                                ->result_array();
    }


}
?>