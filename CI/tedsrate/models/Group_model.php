<?php
class Group_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get ($id)
    {
        return $this->db
                                ->select("g.groupID, g.groupName, g.groupDesc, gt.groupTypeID, g.groupWelcomeTemplate, gt.groupTypeName, gt.groupTypeDesc, l.lotteryJackpot, l.lotterySecond, l.lotteryThird, l.lotterySecondAmount, l.lotteryThirdAmount, l.lotteryStartDate, l.lotteryEndDate, l.lotteryTicketsPerAssessment, l.lotteryTicketsPerShare, l.lotteryTicketsPerComment, l.lotteryTicketsPerScreenshot")
                                ->from("group g")
                                ->join('lottery l', 'g.groupID = l.groupID', 'left')
                                ->join('groupType gt', 'gt.groupTypeID = g.groupTypeID')
                                ->where('g.groupID', $id)
                                ->get()
                                ->result_array();
    }

    public function getAll ()
    {
        return $this->db
            ->select("g.groupID, g.groupName, g.groupDesc, gt.groupTypeID, g.groupWelcomeTemplate, gt.groupTypeName, gt.groupTypeDesc, l.lotteryJackpot, l.lotterySecond, l.lotteryThird, l.lotterySecondAmount, l.lotteryThirdAmount, l.lotteryStartDate, l.lotteryEndDate, l.lotteryTicketsPerAssessment, l.lotteryTicketsPerShare, l.lotteryTicketsPerComment, l.lotteryTicketsPerScreenshot")
            ->from("group g")
            ->join('lottery l', 'g.groupID = l.groupID', 'left')
            ->join('groupType gt', 'gt.groupTypeID = g.groupTypeID')
            ->get()
            ->result_array();
    }

    // gets all items of a group for a particular user
    public function getUser ($groupID, $userID)
    {
        return $this->db
                                ->from("group g")
                                ->join('lottery l', 'g.groupID = l.groupID', 'left')
                                ->join('groupType gt', 'gt.groupTypeID = g.groupTypeID')
                                ->join('group_configuration gc', 'gc.groupID = g.groupID')
                                ->join('configuration c', 'c.configurationID = gc.configurationID')
                                ->join('assessment a', "a.configurationID = c.configurationID and a.userID = $userID", 'left')
                                ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                                ->join('project p', 'p.projectID = ac.projectID')
                                ->join('artifact art', 'art.artifactID = ac.artifactID')
                                ->join('scenario s', 's.scenarioID = ac.scenarioID')
                                ->join('persona per', 'per.personaID = ac.personaID')
                                ->join('role r', 'r.roleID = ac.roleID')
                                ->where('g.groupID', $groupID)
                                ->get()
                                ->result_array();
    }

    public function getConfigurations ($id)
    {
        return $this->db
                                ->select("c.configurationID, c.configurationIDHashed, art.artifactName, s.scenarioName, per.personaName, r.roleName, p.projectName")
                                ->from("group g")
                                ->join('lottery l', 'l.groupID = g.groupID', 'left')
                                ->join('groupType gt', 'gt.groupTypeID = g.groupTypeID')
                                ->join('group_configuration gc', 'gc.groupID = g.groupID')
                                ->join('configuration c', 'c.configurationID = gc.configurationID')
                                ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                                ->join('project p', 'p.projectID = ac.projectID')
                                ->join('artifact art', 'art.artifactID = ac.artifactID')
                                ->join('scenario s', 's.scenarioID = ac.scenarioID')
                                ->join('persona per', 'per.personaID = ac.personaID')
                                ->join('role r', 'r.roleID = ac.roleID')
                                ->where('g.groupID', $id)
                                ->get()
                                ->result_array();
    }



    public function getProject ($projectID)
    {
        return $this->db
                              ->select("*")
                              ->from("group g")
                              ->join('lottery l', 'l.groupID = g.groupID', 'left')
                              ->join('group_configuration gc', 'gc.groupID = g.groupID')
                              ->join('configuration c', 'c.configurationID = gc.configurationID')
                              ->join('groupType gt', 'gt.groupTypeID = g.groupTypeID')
                              ->join('assessmentConfiguration ac', 'ac.assessmentConfigurationID = c.assessmentConfigurationID')
                              ->join('project p', 'p.projectID = ac.projectID')
                              ->where("p.projectID", $projectID)
//                              ->distict()
                              ->get()
                              ->result_array();
    }

    public function getUserStats ($groupID, $userID)
    {
        return $this->db
                              ->select("count(distinct a.assessmentID) as numCompleted, count(distinct s.screenshotID) as numScreenshots, count(distinct c.commentID) as numComments")
                              ->from("assessment a")
                              ->join('rating r', 'r.assessmentID = a.assessmentID', 'left')
                              ->join('comment c', 'c.ratingID = r.ratingID', 'left')
                              ->join('screenshot s', 's.ratingID = r.ratingID', 'left')
                              ->where("a.groupID", $groupID)
                              ->where("a.userID", $userID)
                              ->where("a.completionDate is NOT NULL")
                              ->group_by('a.groupID')
                              ->get()
                              ->result_array();
    }


    public function getTypes ()
    {
        return $this->db
                              ->from("groupType")
                              ->get()
                              ->result_array();
    }

    // insert into the group - configuration association
    public function addConfiguration ($data)
    {
      $this->db->insert('group_configuration', $data);
      return $this->db->insert_id();
    }

    //insert into the lottery sub class
    public function addLottery ($data) {
      $this->db->insert('lottery', $data);
      return $this->db->insert_id();
    }

    public function post ($data)
    {
      $this->db->insert('group', $data);
      return $this->db->insert_id();
    }

    public function put ($data)
    {
      return $this->db
                ->where('groupID', $data['groupID'])
                ->update('group', $data);
    }

    // need to add cascade
    public function delete ($id)
    {
      return $this->db
                ->where('groupID', $id)
                ->delete('group');
    }


}
?>