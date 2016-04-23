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
        $column = [
            'identifier' => 'scenarioID',
            'fields' => [
                'id' => 'scenarioID',
                'name' => 'scenarioName',
                'desc' => 'scenarioDescription'
            ]
        ];
        $row = [
            'identifier' => 'attributeID',
            'fields' => [
                'id' => 'attributeID',
                'name' => 'attributeName',
                'desc' => 'attributeDesc'
            ]
        ];
        $tmp = $this->stats->byArtifact($projectID, $artifactID);
        $final = $this->sortPivot($tmp, $column, $row);

        $this->load->model('screenshot_model', 'screenshot');
        $this->load->model('comment_model', 'comment');
        $this->load->model('rating_model', 'rating');
        $this->load->model('response_model', 'response');

        foreach ($final['columns'] as $key => $value) {
            $final['columns'][$key]['questions'] = $this->sortResponses($this->response->getScenario($value['id']));
        }
        foreach ($final['rows'] as $rowKey => $row) {
            foreach ($row['cells'] as $cellKey => $cell) {
                $ratings = $this->rating->getProjectArtifactAttributeScenario($row['id'], $artifactID, $cellKey, $projectID);
                foreach ($ratings as $ratingKey => $rating) {
                    $ratings[$ratingKey]['comments'] = $this->comment->getRating($rating['ratingID']);
                    $ratings[$ratingKey]['screenshots'] = $this->screenshot->getRating($rating['ratingID']);
                }
                $final['rows'][$rowKey]['cells'][$cellKey]['ratings'] = $ratings;
            }
        }

        echoJSON($final);
    }

    public function byScenario($projectID, $scenarioID)
    {
        $column = [
            'identifier' => 'artifactID',
            'fields' => [
                'id' => 'artifactID',
                'name' => 'artifactName',
                'desc' => 'artifactDescription'
            ]
        ];
        $row = [
            'identifier' => 'attributeID',
            'fields' => [
                'id' => 'attributeID',
                'name' => 'attributeName',
                'desc' => 'attributeDesc'
            ]
        ];
        $tmp = $this->stats->byScenario($projectID, $scenarioID);
        $final = $this->sortPivot($tmp, $column, $row);

        $this->load->model('screenshot_model', 'screenshot');
        $this->load->model('comment_model', 'comment');
        $this->load->model('rating_model', 'rating');
        $this->load->model('response_model', 'response');

        foreach ($final['columns'] as $key => $value) {
            $final['columns'][$key]['questions'] = $this->sortResponses($this->response->getArtifact($value['id']));
        }
        foreach ($final['rows'] as $key => $value) {
            foreach ($row['cells'] as $cellKey => $cell) {
                $ratings = $this->rating->getProjectScenarioAttributeArtifact($row['id'], $scenarioID, $cellKey, $projectID);
                foreach ($ratings as $ratingKey => $rating) {
                    $ratings[$ratingKey]['comments'] = $this->comment->getRating($rating['ratingID']);
                    $ratings[$ratingKey]['screenshots'] = $this->screenshot->getRating($rating['ratingID']);
                }
                $final['rows'][$rowKey]['cells'][$cellKey]['ratings'] = $ratings;
            }
        }

        echoJSON($final, true);
    }

    // unfinished
    public function byProject($projectID)
    {
        $tmp = $this->stats->byProject($projectID);
        $final = $this->sortPivot($tmp, $column, $row);
        echoJSON($final);
    }
    public function byConfiguration($projectID, $configurationID)
    {
        $column = [
            'identifier' => 'assessmentID',
            'fields' => [
                'id' => 'assessmentID',
                'name' => 'assessmentID',
                'userID' => 'userID',
                'completionDate' => 'completionDate',
                'email' => 'email'
            ]
        ];
        $row = [
            'identifier' => 'attributeID',
            'fields' => [
                'id' => 'attributeID',
                'name' => 'attributeName',
                'desc' => 'attributeDesc'
            ]
        ];
        $tmp = $this->stats->byConfiguration($projectID, $configurationID);
        $final = $this->sortPivot($tmp, $column, $row);

        $this->load->model('screenshot_model', 'screenshot');
        $this->load->model('comment_model', 'comment');
        $this->load->model('rating_model', 'rating');
        $this->load->model('response_model', 'response');

        foreach ($final['columns'] as $key => $value) {
            $final['columns'][$key]['questions'] = $this->sortResponses($this->response->getAssessment($value['id']));
        }
        foreach ($final['rows'] as $rowKey => $row) {
            foreach ($row['cells'] as $cellKey => $cell) {
                $ratings = $this->rating->getProjectConfigurationAttributeAssessment($row['id'], $configurationID, $cellKey, $projectID);
                foreach ($ratings as $ratingKey => $rating) {
                    $ratings[$ratingKey]['comments'] = $this->comment->getRating($rating['ratingID']);
                    $ratings[$ratingKey]['screenshots'] = $this->screenshot->getRating($rating['ratingID']);
                }
                $final['rows'][$rowKey]['cells'][$cellKey]['ratings'] = $ratings;
            }
        }

        echoJSON($final);
    }

    // sorts a data set of averages and standard deviations into a two value pivot table requires the array of data, an array specifing the columns identifier and associated fields, an array specifing the rows identifier and associated fields
    private function sortPivot($data, $column, $row) {
        $columns = [];
        $rows = [];
        foreach ($data as $key => $cell) {
            if(!array_key_exists($cell[$column['identifier']], $columns)) {
                $columns[$cell[$column['identifier']]] = [];
                foreach ($column['fields'] as $name => $value) {
                    $columns[$cell[$column['identifier']]][$name] = $cell[$value];
                }
            }
            if(!array_key_exists($cell[$row['identifier']], $rows)) {
                $rows[$cell[$row['identifier']]] = [];
                foreach ($row['fields'] as $name => $value) {
                    $rows[$cell[$row['identifier']]][$name] = $cell[$value];
                    $rows[$cell[$row['identifier']]]['cells'] = [];
                }
            }
        }

        $sorted = [];
        $counter = 0;
        foreach ($rows as $key => $rowValue) {
            $rows[$key]['cells'] = $columns;
            foreach ($data as $cellKey => $cell) {

                if($cell[$row['identifier']] == $rowValue['id']) {
                    $rows[$key]['cells'][$cell[$column['identifier']]] = [
                    'average' => $cell['average'],
                    'standardDeviation' => $cell['standardDeviation']
                    ];
                }
            }

        }

        foreach ($rows as $key => $rowValue) {
            $stdCounter = 0;
            $avgCounter = 0;
            $stdSum = 0;
            $avgSum = 0;
            foreach ($rows[$key]['cells'] as $cellKey => $cell) {
                    if( array_key_exists('average', $cell) && $cell['average'] !== null) {
                        $avgCounter++;
                        $avgSum += $cell['average'];
                    }
                    if(array_key_exists('standardDeviation', $cell) && $cell['standardDeviation'] !== null) {
                        $stdCounter++;
                        $stdSum += $cell['standardDeviation'];
                    }
            }
            if($avgCounter > 0) {
                $rows[$key]['totalAverage'] = $avgSum / $avgCounter;
            }
            if($stdCounter > 0) {
                $rows[$key]['totalStandardDeviation'] = $stdSum / $stdCounter;
            }
        }

        $final = [
        'rows' => $rows,
        'columns' => $columns
        ];
        return $final;
    }

    // sorts a data set of question responses by question. cleans the data for use in nvd3 charting
    private function sortResponses($data) {
        $questions=[];
        $chartOptions = null;
        foreach ($data as $key => $cell) {
            if(!array_key_exists($cell['questionID'], $questions)) {
                $questions[$cell['questionID']] = [
                    'id' => $cell['questionID'],
                    'name' => $cell['questionName'],
                    'desc' => $cell['questionDesc'],
                    'type' => $cell['questionTypeName'],
                    'questionData' => $cell['questionData'],
                    'responses' => [],
                    'chartData' => [],
                    'chartOptions' => []
                ];
                $questionData = json_decode($cell['questionData'], TRUE);

                $chartOptions = [
                    'chart' => [
                        // 'type' => 'pieChart',
                        'height' => 500,
                        // 'x' => "%%function(d){return d.key;}%%",
                        // 'y' => "%%function(d){return d.value;}%%",
                        'duration' => 500,
                    ]
                ];

                // if($questionData['questionType'] == 'Boolean') {
                //     $chartOptions['chart']['type'] = 'pieChart';
                //     $chartOptions['chart']['showLabels'] = true;
                //     $chartOptions['chart']['labelThreshold'] = 0.01;
                //     $chartOptions['chart']['labelSunbeamLayout'] = true;
                //     $chartOptions['chart']['legend'] = [
                //         'margin' => [
                //             'top' => 5,
                //             'right' => 35,
                //             'bottom' => 5,
                //             'left' => 0
                //         ]
                //     ];
                // }
                // if($questionData['questionType'] == 'Select' || $questionData['questionType'] == 'Radio' || $questionData['questionType'] == 'Check' || $questionData['questionType'] == 'Boolean') {
                    $chartOptions['chart']['type'] = 'discreteBarChart';
                    // $chartOptions['chart']['valueFormat'] = "%%function(d){return d3.format(',.4f')(d);}%%";
                    $chartOptions['chart']['xAxis'] = [
                        'axisLabel' => 'Answer'
                    ];
                    $chartOptions['chart']['yAxis'] = [
                        'axisLabel' => 'Count'
                    ];
                // }

                $questions[$cell['questionID']]['chartOptions'] = $chartOptions;
            }
            $response = [
                'id' => $cell['responseID'],
                'answer' => $cell['responseAnswer']
            ];
            if($questionData['questionType'] == 'Boolean') {
                if($response['answer'] == 0) {
                    $response['answer'] = 'No';
                } else {
                    $response['answer'] = 'Yes';
                }
            }
            array_push($questions[$cell['questionID']]['responses'], $response);
        }

        foreach ($questions as $questionKey => $question) {
            $tmp = [];
            foreach ($question['responses'] as $responseKey => $response) {
                if(!array_key_exists($response['answer'], $tmp)) {
                    $tmp[$response['answer']] = 1;
                } else {
                    $tmp[$response['answer']]++;
                }
            }

            if($question['chartOptions']['chart']['type'] == 'discreteBarChart') {
                foreach ($tmp as $key => $value) {
                    $toPush = [
                        'label' => $key,
                        'value' => $value
                    ];
                    array_push($questions[$questionKey]['chartData'], $toPush);
                }
                $questions[$questionKey]['chartData'] = [
                    [
                        'key' => 'Answer Counts',
                        'values' => $questions[$questionKey]['chartData']
                    ]
                ];
            } else {
                foreach ($tmp as $key => $value) {
                    $toPush = [
                        'x' => $key,
                        'y' => $value
                    ];
                    array_push($questions[$questionKey]['chartData'], $toPush);
                }
            }
        }
        return $questions;
    }
}
