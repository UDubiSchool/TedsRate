<?php
    namespace models;
    require_once('model.php');
    use PDO;

    class project_model extends model {
        public function __construct()
        {
            parent::__construct();
        }

        // get a project by their primary key
        public function get()
        {
            $projectID = $_POST['id'];

            $getProject = $this->db->prepare("SELECT * FROM project WHERE projectID=:id");
            $getProject->bindValue(':id', $projectID, PDO::PARAM_INT);
            $getProject->execute();
            $this->data['project'] = $getProject->fetch();

            $getProject->closeCursor();

            $this->echoJSON($this->data);
        }

        // get all projects
        public function getAll()
        {

            $getProject = $this->db->prepare("SELECT * FROM project");
            $getProject->execute();
            $this->data['projects'] = $getProject->fetchAll();

            $getProject->closeCursor();

            $this->echoJSON($this->data);
        }

        // get a project and all its associated artifacts, scenarios, personas and roles by its primary key
        public function getAssoc()
        {
            $projectID = $_POST['id'];

            $getProject = $this->db->prepare("SELECT * FROM project WHERE projectID=:id");
            $getProject->bindValue(':id', $projectID, PDO::PARAM_INT);
            $getProject->execute();
            $this->data['project'] = $getProject->fetch();
            $getProject->closeCursor();

            $this->data['project'] = $this->getAssoc($this->data['project']);

            $this->echoJSON($this->data);
        }

        // gets all project and thier associated artifacts, scenarios, personas and roles by primary key
        public function getAllAssoc()
        {
            $getProjects = $this->db->prepare("SELECT * FROM project");
            $getProjects->execute();
            $this->data['projects'] = $getProjects->fetchAll();
            $getProjects->closeCursor();

            foreach($this->data['projects'] as $key => $value) {
                $this->data['projects'][$key] = $this->attachAssoc($value);
            }

            $this->echoJSON($this->data);
        }



        // updates a project by id
        public function post()
        {
            $projectID = intval($_POST['id']);
            $languageID = intval($_POST['languageID']);
            $name = $_POST['projectName'];
            $desc = $_POST['projectDescription'];
            $updateProject = $this->db->prepare("UPDATE project SET projectName=:name, projectDescription=:description, languageID=:languageID WHERE projectID=:id");
            $updateProject->bindValue(':id', $projectID, PDO::PARAM_INT);
            $updateProject->bindValue(':name', $name, PDO::PARAM_STR);
            $updateProject->bindValue(':description', $desc, PDO::PARAM_STR);
            $updateProject->bindValue(':languageID', $languageID, PDO::PARAM_INT);
            $updateProject->execute();
            if ($this->numRows() == 1) {
                $this->data['project'] = true;
            } else {
                $this->data['project'] = false;
            }
            $updateProject->closeCursor();
            $this->echoJSON($this->data);
        }

        // deletes the project with the given id
        public function delete()
        {

            $projectID = intval($_POST['id']);

            $stm = $dbq->prepare("DELETE FROM project WHERE projectID=:projectID");
            $stm->bindValue(':projectID', $projectID, PDO::PARAM_INT);
            $stm->execute();
            if ($this->numRows() == 1) {
                $this->data['deleted'] = true;
            } else {
                $this->data['deleted'] = false;
            }
            $stm->closeCursor();
            $this->echoJSON($this->data);
        }

        private function attachAssoc ($project) {
            $projectID = $project['projectID'];

            $getArtifacts = $this->db->prepare("SELECT a.artifactID, a.artifactName, a.artifactURL, a.artifactTypeID, at.artifactTypeName, at.artifactTypeDescription FROM project p
                                                                    JOIN projectArtifact pa ON pa.projectID = p.projectID
                                                                    JOIN artifact a ON a.artifactID = pa.artifactID
                                                                    JOIN artifactType at on a.artifactTypeID = at.artifactTypeID
                                                                    WHERE p.projectID=:id");
            $getArtifacts->bindValue(':id', $projectID, PDO::PARAM_INT);
            $getArtifacts->execute();
            $project['artifacts'] = $getArtifacts->fetchAll();
            $getArtifacts->closeCursor();

            $getScenarios = $this->db->prepare("SELECT s.scenarioID, s.scenarioName, s.scenarioDescription FROM project p
                                                                    JOIN project_scenario ps ON ps.projectID = p.projectID
                                                                    JOIN scenario s ON s.scenarioID = ps.scenarioID
                                                                    WHERE p.projectID=:id");
            $getScenarios->bindValue(':id', $projectID, PDO::PARAM_INT);
            $getScenarios->execute();
            $project['scenarios'] = $getScenarios->fetchAll();
            $getScenarios->closeCursor();

            $getPersonas = $this->db->prepare("SELECT pe.personaID, pe.personaName, pe.personaDesc FROM project p
                                                                    JOIN project_persona pp ON pp.projectID = p.projectID
                                                                    JOIN persona pe ON pe.personaID = pp.personaID
                                                                    WHERE p.projectID=:id");
            $getPersonas->bindValue(':id', $projectID, PDO::PARAM_INT);
            $getPersonas->execute();
            $project['personas'] = $getPersonas->fetchAll();
            $getPersonas->closeCursor();

            $getRoles = $this->db->prepare("SELECT r.roleID, r.roleName, r.roleDesc FROM project p
                                                                    JOIN project_role pr ON pr.projectID = p.projectID
                                                                    JOIN role r ON r.roleID = pr.roleID
                                                                    WHERE p.projectID=:id");
            $getRoles->bindValue(':id', $projectID, PDO::PARAM_INT);
            $getRoles->execute();
            $project['roles'] = $getRoles->fetchAll();
            $getRoles->closeCursor();

            return $project;
        }


    }