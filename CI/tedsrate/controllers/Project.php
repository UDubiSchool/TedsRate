<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('project_model', 'project');
        $this->load->model('assessment_model', 'assessment');
    }

    // public function get($id = null, $assoc = false)
    public function getBasic($id = null)
    {

        if($id == null || $id == 0) {
            $tmp = $this->project->getAll();
        } else {
            $tmp = $this->project->get($id);
        }

        foreach ($tmp as $key => $project) {
            // $stats = $this->assessment->getProjectStats($project['projectID']);
            $tmp[$key]['counts'] = $this->assessment->getProjectStats($project['projectID']);
        }
        echoJSON($tmp);
    }

    public function exportAssessments($projectID) {
        $this->load->model('question_model', 'question');
        $this->load->model('attribute_model', 'attribute');
        $this->load->model('rating_model', 'rating');
        $this->load->model('response_model', 'response');


        $assessments = $this->assessment->getProject($projectID);
        $attributes = $this->attribute->getInProject($projectID);
        $questions = $this->question->getInProject($projectID);

        $export=[
            ["AssessmentID", "UserEmail", "Artifact", "Scenario", "Persona", "Role"], //title row
        ];

        foreach ($attributes as $atKey => $attribute) {
            array_push($export[0], $attribute["attributeName"]);
        }
        foreach ($questions as $qKey => $question) {
            array_push($export[0], $question["questionName"]);
        }


        // building tuples

        foreach($assessments as $asKey => $assessment) {
            $assessmentRow = [$assessment["assessmentID"], $assessment["email"], $assessment["artifactName"], $assessment["scenarioName"], $assessment["personaName"], $assessment["roleName"]];
            $ratings = $this->rating->getAssessment($assessment["assessmentID"]);
            $responses = $this->response->getAssessment($assessment["assessmentID"]);
            foreach ($attributes as $atKey => $attribute) {

                $ratingKey = array_search($attribute['attributeID'], array_column($ratings, 'attributeID'));
                $rating = $ratingKey === false ? null : $ratings[$ratingKey]['ratingValue'];
                array_push($assessmentRow, $rating);
            }
            foreach ($questions as $qKey => $question) {
                $responseKey = array_search($question['questionID'], array_column($responses, 'questionID'));
                $response = $responseKey === false ? null : $this->parseQuestionResponse($responses[$responseKey]['questionData'], $responses[$responseKey]['responseAnswer']);
                array_push($assessmentRow, $response);
            }
            array_push($export, $assessmentRow);
        }
//        dumpArray($export);
        echoCSV($export, "assessments");
    }

    private function parseQuestionResponse ($questionData, $answer) {
        $data = json_decode($questionData, true);
        switch ($data["questionType"]) {
            case "Boolean":
                return intval($answer) === 1 ? "true" : "false";
                break;
            case "Check":
                $answers = json_decode($answer);
                $checked = [];
                foreach ($answers as $aKey => $aValue) {
                    if ($aValue === 1) {
                        array_push($checked, $aKey);
                    }
                }
                return implode(", ", $checked);
                break;
            case "Select":
                return $answer;
                break;
            case "Radio":
                return $answer;
                break;
            case "Text":
                return $answer;
        }
    }


    // private function getAssoc($project)
    // {
    //     $project['artifacts'] = $this->project->getArtifacts($project['projectID']);
    //     $project['scenarios'] = $this->project->getScenarios($project['projectID']);
    //     $project['personas'] = $this->project->getPersonas($project['projectID']);
    //     $project['roles'] = $this->project->getRoles($project['projectID']);
    //     return $project;
    // }
}
