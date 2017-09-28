<?php
class AttributeConfiguration_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("attributeConfiguration")
                                ->where('attributeConfigurationID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("attributeConfiguration")
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('attributeConfiguration', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('attributeConfigurationID', $data['attributeConfigurationID'])
                ->update('attributeConfiguration', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('attributeConfigurationID', $id)
                ->delete('attributeConfiguration');
    }
}
?>