<?php

namespace FeatureToggle;

require 'vendor/autoload.php';
include 'FTLibPhp.php';

// Init
$ft = new FTLibPhp('5d388e5ce26035ac05d91fcfd8fb12ae','edbd9643161d885dd8d58109237d281e', [
    'api' => 'http://127.0.0.1:8000',
    'debug' => true
]);

// Get Features
$result = $ft->getFeatures();
$features = $result['data'];
var_dump('getFeatures');
var_dump($features);

// Feature Enabled
$result = $ft->isEnabled('feature-1');
$enabled = $result['data'];
var_dump('isEnabled');
var_dump($enabled);

// Invalid Enabled
$result = $ft->isEnabled('feature-12');
$enabled = $result['data'];
var_dump('isEnabled');
var_dump($enabled);
