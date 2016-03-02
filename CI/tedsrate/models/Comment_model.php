<?php
class Comment_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->from("comment")
                                ->where('commentID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
                              ->from("comment")
                              ->get()
                              ->result_array();
    }

    public function getRating ($ratingID)
    {
        return $this->db
                              ->from("comment c")
                              ->join('rating r', 'r.ratingID = c.ratingID')
                              ->where('r.ratingID', $ratingID)
                              ->get()
                              ->result_array();
    }

    public function post ($data)
    {
      $this->db->insert('comment', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('commentID', $data['commentID'])
                ->update('comment', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('commentID', $id)
                ->delete('comment');
    }
}
?>