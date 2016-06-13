<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('group_model', 'group');
    }

    public function getProject($id)
    {
        $tmp = $this->group->getProject($id);
        echoJSON($tmp);
    }

    public function getTypes()
    {
        $tmp = $this->group->getTypes();
        echoJSON($tmp);
    }
}
