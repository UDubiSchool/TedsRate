<?php
class Project_artifact_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("projectArtifact")
                                ->where('projectArtifactID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("projectArtifact")
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('projectArtifact', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('projectArtifactID', $data['projectArtifactID'])
                ->update('projectArtifact', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('projectArtifactID', $id)
                ->delete('projectArtifact');
    }
}
?>