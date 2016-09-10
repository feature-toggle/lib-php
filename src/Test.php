<?php

namespace FeatureToggle;

include 'FTLibPhp.php';

// Init
$ft = new FTLibPhp('5d388e5ce26035ac05d91fcfd8fb12ae','edbd9643161d885dd8d58109237d281e', [
    'api' => 'http://127.0.0.1:8000'
]);

// API Request
$response = $ft->apiRequest('/features');
$data = json_decode($response);
var_dump('apiRequest');
var_dump($data);

// Get Features
$response = $ft->getFeatures();
$features = json_decode($response);
var_dump('getFeatures');
var_dump($features);

// Feature Enabled
$response = $ft->isEnabled('feature-1');
$enabled = json_decode($response);
var_dump('isEnabled');
var_dump($enabled);
