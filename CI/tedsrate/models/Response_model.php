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
                              ->from("response r")
                              ->join('assessment a', 'a.assessmentID = r.assessmentID')
                              ->join('question q', 'q.questionID = r.questionID')
                              ->where('a.assessmentID', $assessmentID)
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