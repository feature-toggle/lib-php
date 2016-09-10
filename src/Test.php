<?php

namespace FeatureToggle;

require 'vendor/autoload.php';
include 'FTLibPhp.php';

// Init
$ft = new FTLibPhp('5d388e5ce26035ac05d91fcfd8fb12ae','edbd9643161d885dd8d58109237d281e', [
    'api' => 'http://127.0.0.1:8000',
    'cache_timeout' => 5,
    'debug' => true
]);

$ft->cleacCache();

// Get Features
$features = $ft->getFeatures();
var_dump('getFeatures');
var_dump($features);

// Feature Enabled
var_dump('isEnabled');
var_dump('feature-1 (valid)');
$enabled = $ft->isEnabled('feature-1');
var_dump($enabled);

// Invalid Enabled
var_dump('isEnabled');
var_dump('feature-12 (invalid)');
$enabled = $ft->isEnabled('feature-12');
var_dump($enabled);
