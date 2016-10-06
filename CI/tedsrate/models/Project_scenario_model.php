<?php
class Project_scenario_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("project_scenario")
                                ->where('projectScenarioID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("project_scenario")
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('project_scenario', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('projectScenarioID', $data['projectScenarioID'])
                ->update('project_scenario', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('projectScenarioID', $id)
                ->delete('project_scenario');
    }
}
?>