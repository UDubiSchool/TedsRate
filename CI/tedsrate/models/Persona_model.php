<?php
class Persona_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("persona")
                                ->where('personaID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("persona")
                              ->get()
                              ->result_array();
    }

    public function getProject ($projectID)
    {
        return $this->db
                              ->from("persona pe")
                              ->join('project_persona pp', 'pp.personaID = pe.personaID')
                              ->join('project p', 'p.projectID = pp.projectID')
                              ->where('p.projectID', $projectID)
                              ->group_by('pe.personaID')
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('persona', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('personaID', $data['personaID'])
                ->update('persona', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('personaID', $id)
                ->delete('persona');
    }
}
?>