<?php
class User_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                            ->from("user")
                            ->where('userID', $id)
                            ->get()
                            ->result_array();
    }

    public function validate ($data)
    {
        return $this->db
                            ->from("user")
                            ->where($data)
                            ->get()
                            ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                          ->from("user")
                          ->get()
                          ->result_array();
    }

    public function getProject ($projectID)
    {
        return $this->db
                              ->select("u.userID, u.email, u.firstName, u.lastName")
                              ->from("user u")
                              ->join('assessment a', 'a.userID = u.userID')
                              ->join('configuration c', 'c.configurationID = a.configurationID')
                              ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                              ->join('project p', 'p.projectID = ac.projectID')
                              ->where('p.projectID', $projectID)
                              ->group_by('u.userID')
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('user', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('userID', $data['userID'])
                ->update('user', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('userID', $id)
                ->delete('user');
    }
}
?>