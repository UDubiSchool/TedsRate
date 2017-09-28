<?php
class UiConfiguration_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("uiConfiguration")
                                ->where('uiConfigurationID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("uiConfiguration")
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('uiConfiguration', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('uiConfigurationID', $data['uiConfigurationID'])
                ->update('uiConfiguration', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('uiConfigurationID', $id)
                ->delete('uiConfiguration');
    }
}
?>