<?php
class Project_role_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("project_role")
                                ->where('projectRoleID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("project_role")
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('project_role', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('projectRoleID', $data['projectRoleID'])
                ->update('project_role', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('projectRoleID', $id)
                ->delete('project_role');
    }
}
?>