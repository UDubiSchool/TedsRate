<?php
class Response_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("response")
                                ->where('responseID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("response")
                              ->get()
                              ->result_array();
    }

    public function getAssessment ($assessmentID)
    {
        return $this->db
                              ->select("r.responseID, r.responseAnswer, q.questionID, q.questionName, q.questionDesc, q.questionData, qt.questionTypeID, qt.questionTypeName, qt.questionTypeDesc, a.assessmentID")
                              ->from("response r")
                              ->join('assessment a', 'a.assessmentID = r.assessmentID')
                              ->join('question q', 'q.questionID = r.questionID')
                              ->join('questionType qt', 'qt.questionTypeID = q.questionTypeID')
                              ->where('a.assessmentID', $assessmentID)
                              ->order_by('q.questionID', 'ASC')
                              ->get()
                              ->result_array();
    }

    public function getConfiguration ($configurationID)
    {
        return $this->db
                              ->select("r.responseID, r.responseAnswer, q.questionID, q.questionName, q.questionDesc, q.questionData, qt.questionTypeID, qt.questionTypeName, qt.questionTypeDesc, a.assessmentID")
                              ->from("response r")
                              ->join('assessment a', 'a.assessmentID = r.assessmentID')
                              ->join('configuration c', 'c.configurationID = a.configurationID')
                              ->join('question q', 'q.questionID = r.questionID')
                              ->join('questionType qt', 'qt.questionTypeID = q.questionTypeID')
                              ->where('c.configurationID', $configurationID)
                              ->order_by('q.questionID', 'ASC')
                              ->get()
                              ->result_array();
    }

    public function getArtifact ($artifactID)
    {
        return $this->db
                              ->select("r.responseID, r.responseAnswer, q.questionID, q.questionName, q.questionDesc, q.questionData, qt.questionTypeID, qt.questionTypeName, qt.questionTypeDesc, a.assessmentID")
                              ->from("response r")
                              ->join('assessment a', 'a.assessmentID = r.assessmentID')
                              ->join('configuration c', 'c.configurationID = a.configurationID')
                              ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                              ->join('question q', 'q.questionID = r.questionID')
                              ->join('questionType qt', 'qt.questionTypeID = q.questionTypeID')
                              ->where('ac.artifactID', $artifactID)
                              ->order_by('q.questionID', 'ASC')
                              ->get()
                              ->result_array();
    }

    public function getScenario ($scenarioID)
    {
        return $this->db
                              ->select("r.responseID, r.responseAnswer, q.questionID, q.questionName, q.questionDesc, q.questionData, qt.questionTypeID, qt.questionTypeName, qt.questionTypeDesc, a.assessmentID")
                              ->from("response r")
                              ->join('assessment a', 'a.assessmentID = r.assessmentID')
                              ->join('configuration c', 'c.configurationID = a.configurationID')
                              ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                              ->join('question q', 'q.questionID = r.questionID')
                              ->join('questionType qt', 'qt.questionTypeID = q.questionTypeID')
                              ->where('ac.scenarioID', $scenarioID)
                              ->order_by('q.questionID', 'ASC')
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('response', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('responseID', $data['responseID'])
                ->update('response', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('responseID', $id)
                ->delete('response');
    }
}
?>