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
        echoJSON($final);
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
            'identifier' => 'configurationID',
            'fields' => [
                'id' => 'configurationID',
                'artifactName' => 'artifactName',
                'artifactDesc' => 'artifactDescription',
                'scenarioName' => 'scenarioName',
                'scenarioDesc' => 'scenarioDescription'
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
}
