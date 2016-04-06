<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stats extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('stats_model', 'stats');
    }

    public function byArtifact($projectID, $artifactID)
    {
        $tmp = $this->stats->byArtifact($projectID, $artifactID);
        echoJSON($tmp);
    }
}
