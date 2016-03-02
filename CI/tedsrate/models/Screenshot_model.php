<?php
class Screenshot_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("screenshot")
                                ->where('screenshotID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("screenshot")
                              ->get()
                              ->result_array();
    }

    public function getRating ($ratingID)
    {
        return $this->db
                              ->from("screenshot s")
                              ->join('rating r', 'r.ratingID = s.ratingID')
                              ->where('r.ratingID', $ratingID)
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('screenshot', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('screenshotID', $data['screenshotID'])
                ->update('screenshot', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('screenshotID', $id)
                ->delete('screenshot');
    }
}
?>