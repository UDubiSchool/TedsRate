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
//        return $this->db
//                              ->select("r.responseID, r.responseAnswer, q.questionID, q.questionName, q.questionDesc, q.questionData, qt.questionTypeID, qt.questionTypeName, qt.questionTypeDesc, a.assessmentID")
//                              ->from("response r")
//                              ->join('assessment a', 'a.assessmentID = r.assessmentID')
//                              ->join('question q', 'q.questionID = r.questionID')
//                              ->join('questionType qt', 'qt.questionTypeID = q.questionTypeID')
//                              ->where('a.assessmentID', $assessmentID)
//                              ->order_by('q.questionID', 'ASC')
//                              ->get()
//                              ->result_array();
        $this->load->model('assessment_model', 'assessment');

        $assessment = $this->assessment->get($assessmentID);
        $assessment = $assessment[0];

        return $this->db->query("SELECT q.questionID, q.questionName, q.questionDesc, q.questionData, q.questionRequired, qt.questionTypeName, qp.projectID, qs.scenarioID, qart.artifactID, qper.personaID, qrol.roleID, r.responseID, r.responseAnswer
                    FROM question q
                    INNER JOIN response r ON q.questionID = r.questionID
                    INNER JOIN question_questionConfiguration qqc ON qqc.questionID = q.questionID
                    INNER JOIN questionConfiguration qc ON qc.questionConfigurationID = qqc.questionConfigurationID
                    AND qc.questionConfigurationID = $assessment[questionConfigurationID]
                    INNER JOIN questionType qt ON qt.questionTypeID = q.questionTypeID
                    LEFT JOIN question_project qp ON q.questionID = qp.questionID
                    AND qp.projectID = $assessment[projectID]
                    LEFT JOIN question_scenario qs ON q.questionID = qs.questionID
                    AND qs.scenarioID = $assessment[scenarioID]
                    LEFT JOIN question_attribute qatt ON q.questionID = qatt.questionID
                    LEFT JOIN question_artifact qart ON q.questionID = qart.questionID
                    AND qart.artifactID = $assessment[artifactID]
                    LEFT JOIN question_persona qper ON q.questionID = qper.questionID
                    AND qper.personaID = $assessment[personaID]
                    LEFT JOIN question_role qrol ON q.questionID = qrol.questionID
                    AND qrol.roleID = $assessment[roleID]
                    ORDER BY qt.questionTypeID ASC, q.questionID ASC")->result_array();
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