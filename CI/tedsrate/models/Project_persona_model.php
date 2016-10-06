<?php
class Project_persona_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("project_persona")
                                ->where('projectPersonaID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("project_persona")
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('project_persona', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('projectPersonaID', $data['projectPersonaID'])
                ->update('project_persona', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('projectPersonaID', $id)
                ->delete('project_persona');
    }
}
?>