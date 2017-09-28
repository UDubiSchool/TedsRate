<?php
class Question_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("question")
                                ->where('questionID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("question")
                              ->get()
                              ->result_array();
    }

    public function getQuestionConfiguration($questionConfigurationID) {
        return $this->db
                              ->from('questionConfiguration qc')
                              ->join('question_questionConfiguration qqc', 'qqc.questionConfigurationID = qc.questionConfigurationID')
                              ->join('question q', 'q.questionID = qqc.questionID')
                              ->join('questionType qt', 'qt.questionTypeID = q.questionTypeID')
                              ->where("qc.questionConfigurationID", $questionConfigurationID)
                              ->get()
                              ->result_array();
    }

    public function getInProject ($projectID)
    {
        return $this->db
            ->distinct()
            ->select("q.questionID, q.questionName, q.questionDesc")
            ->from("question q")
            ->join('question_questionConfiguration qqc', 'qqc.questionID = q.questionID')
            ->join('questionConfiguration qc', 'qc.questionConfigurationID = qqc.questionConfigurationID')
            ->join('configuration c', 'c.questionConfigurationID = qc.questionConfigurationID')
            ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
            ->join('project p', 'p.projectID = ac.projectID')
            ->where('p.projectID', $projectID)
            ->get()
            ->result_array();
    }
    
    

    public function post ($data)
    {
      $this->db->insert('question', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('questionID', $data['questionID'])
                ->update('question', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('questionID', $id)
                ->delete('question');
    }
}
?>