<?php
class Attribute_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
            ->from("attribute")
            ->where('attributeID', $id)
            ->get()
            ->result_array();
    }

    public function getAll ()
    {
        return $this->db
            ->from("attribute")
            ->get()
            ->result_array();
    }

    public function getInProject ($projectID)
    {
        return $this->db
            ->distinct()
            ->select("a.attributeName, a.attributeID")
            ->from("attribute a")
            ->join('attributeConfiguration_attribute aac', 'aac.attributeID = a.attributeID')

            ->join('attributeConfiguration ac', 'ac.attributeConfigurationID = aac.attributeConfigurationID')
            ->join('configuration c', 'c.attributeConfigurationID = ac.attributeConfigurationID')
            ->join('assessmentConfiguration asc', 'asc.assessmentConfigurationID = c.assessmentConfigurationID')
            ->join('project p', 'p.projectID = asc.projectID')
            ->where('p.projectID', $projectID)
            ->get()
            ->result_array();
    }

    public function post ($data)
    {
        $this->db->insert('attribute', $data);
        return $this->db->insert_id();
    }

    public function put ($data)
    {
        return $this->db
            ->where('attributeID', $data['attributeID'])
            ->update('attribute', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
        return $this->db
            ->where('attributeID', $id)
            ->delete('attribute');
    }
}
?>