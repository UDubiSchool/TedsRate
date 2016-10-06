<?php
class Language_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("language")
                                ->where('languageID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("language")
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('language', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('languageID', $data['languageID'])
                ->update('language', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('languageID', $id)
                ->delete('language');
    }
}
?>