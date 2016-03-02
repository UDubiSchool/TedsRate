<?php
class Scenario_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("scenario")
                                ->where('scenarioID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("scenario")
                              ->get()
                              ->result_array();
    }

    public function getProject ($projectID)
    {
        return $this->db
                              ->from("scenario s")
                              ->join('project_scenario ps', 'ps.scenarioID = s.scenarioID')
                              ->join('project p', 'p.projectID = ps.projectID')
                              ->where('p.projectID', $projectID)
                              ->group_by('s.scenarioID')
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('scenario', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('scenarioID', $data['scenarioID'])
                ->update('scenario', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('scenarioID', $id)
                ->delete('scenario');
    }
}
?>