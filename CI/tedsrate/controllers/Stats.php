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
        $scenarios = [];
        $attributes = [];
        $tmp = $this->stats->byArtifact($projectID, $artifactID);
        foreach ($tmp as $key => $cell) {
            if(!array_key_exists($cell['scenarioID'], $scenarios)) {
                $scenarios[$cell['scenarioID']] = [
                    'scenarioID' => $cell['scenarioID'],
                    'scenarioName' => $cell['scenarioName'],
                    'scenarioDesc' => $cell['scenarioDescription']
                ];
            }
            if(!array_key_exists($cell['attributeID'], $attributes)) {
                $attributes[$cell['attributeID']] = [
                    'attributeID' => $cell['attributeID'],
                    'attributeName' => $cell['attributeName'],
                    'attributeDesc' => $cell['attributeDesc'],
                    'scenarios' => []
                ];

            }

        }
        $sorted = [];
        $counter = 0;
        foreach ($attributes as $key => $attribute) {
            $attributes[$key]['scenarios'] = $scenarios;
            foreach ($tmp as $cellKey => $cell) {
                if($cell['attributeID'] == $attribute['attributeID']) {
                    $attributes[$key]['scenarios'][$cell['scenarioID']] = $cell;
                }
            }

        }
        foreach ($attributes as $key => $attribute) {
            $stdCounter = 0;
            $avgCounter = 0;
            $stdSum = 0;
            $avgSum = 0;
            foreach ($attributes[$key]['scenarios'] as $cellKey => $cell) {
                    if( array_key_exists('AttributeAverage', $cell) && $cell['AttributeAverage'] !== null) {
                        $avgCounter++;
                        $avgSum += $cell['AttributeAverage'];
                    }
                    if(array_key_exists('AttributeStandardDeviation', $cell) && $cell['AttributeStandardDeviation'] !== null) {
                        $stdCounter++;
                        $stdSum += $cell['AttributeStandardDeviation'];
                    }
            }
            if($avgCounter > 0) {
                $attributes[$key]['totalAverage'] = $avgSum / $avgCounter;
            }
            if($stdCounter > 0) {
                $attributes[$key]['totalStandardDeviation'] = $stdSum / $stdCounter;
            }
        }

        $final = [
        'attributes' => $attributes,
        'scenarios' => $scenarios
        ];
        echoJSON($final);
    }

    public function byScenario($projectID, $scenarioID)
    {
        $tmp = $this->stats->byScenario($projectID, $scenarioID);
        echoJSON($tmp);
    }

    public function byProject($projectID)
    {
        $tmp = $this->stats->byProject($projectID);
        echoJSON($tmp);
    }

    public function byConfiguration($projectID, $configurationID)
    {
        $tmp = $this->stats->byConfiguration($projectID, $configurationID);
        echoJSON($tmp);
    }
}
