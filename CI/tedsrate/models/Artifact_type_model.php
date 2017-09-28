<?php
class Artifact_type_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("artifactType")
                                ->where('artifactTypeID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("artifactType")
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('artifactType', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('artifactTypeID', $data['artifactTypeID'])
                ->update('artifactType', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('artifactTypeID', $id)
                ->delete('artifactType');
    }
}
?>