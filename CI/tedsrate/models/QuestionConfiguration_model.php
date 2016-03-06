<?php
class QuestionConfiguration_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("questionConfiguration")
                                ->where('questionConfigurationID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("questionConfiguration")
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('questionConfiguration', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('questionConfigurationID', $data['questionConfigurationID'])
                ->update('questionConfiguration', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('questionConfigurationID', $id)
                ->delete('questionConfiguration');
    }
}
?>