<?php
class Artifact_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("artifact")
                                ->where('artifactID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("artifact")
                              ->get()
                              ->result_array();
    }

    public function getProject ($projectID)
    {
        return $this->db
                              ->from("artifact a")
                              ->join('projectArtifact pa', 'pa.artifactID = a.artifactID')
                              ->join('project p', 'p.projectID = pa.projectID')
                              ->where('p.projectID', $projectID)
                              ->group_by('a.artifactID')
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('artifact', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('artifactID', $data['artifactID'])
                ->update('artifact', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('artifactID', $id)
                ->delete('artifact');
    }
}
?>