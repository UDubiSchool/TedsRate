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