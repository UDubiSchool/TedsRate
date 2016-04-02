<?php
class Role_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("role")
                                ->where('roleID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("role")
                              ->get()
                              ->result_array();
    }

    public function getProject ($projectID)
    {
        return $this->db
                              ->select("r.roleID, r.roleName, r.roleDesc")
                              ->from("role r")
                              ->join('project_role pr', 'pr.roleID = r.roleID')
                              ->join('project p', 'p.projectID = pr.projectID')
                              ->where('p.projectID', $projectID)
                              ->group_by('r.roleID')
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('role', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('roleID', $data['roleID'])
                ->update('role', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('roleID', $id)
                ->delete('role');
    }
}
?>