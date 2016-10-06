<?php
class AssessmentConfiguration_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("assessmentConfiguration")
                                ->where('assessmentConfigurationID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("assessmentConfiguration")
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $query = "CALL addAssessmentConfiguration($data[projectID],$data[artifactID],$data[scenarioID],$data[personaID],$data[roleID], @assessmentConfigurationID)";

      $this->db->query($query);
      $retID = $this->db->query('SELECT @assessmentConfigurationID as output');
      return $retID->row()->output;
    }

    public function put ($data)
    {
      return $this->db
                ->where('assessmentConfigurationID', $data['assessmentConfigurationID'])
                ->update('assessmentConfiguration', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('assessmentConfigurationID', $id)
                ->delete('assessmentConfiguration');
    }
}
?>