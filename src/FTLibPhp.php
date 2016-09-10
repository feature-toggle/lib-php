<?php

namespace FeatureToggle;

class FTLibPhp
{
    private $customerKey;
    private $environmentKey;
    private $options = [
        'api' => 'https://api.featuretoggle.com',
        'cache_timeout' => 300,
        'version' => 'v1'
    ];

    public function __construct($customerKey, $environmentKey, $options)
    {
        $this->customerKey = $customerKey;
        $this->environmentKey = $environmentKey;
        $this->options = array_merge($this->options, $options);
    }

    public function apiRequest($endpoint)
    {
        $ch = curl_init($endpoint);

        curl_setopt($ch, CURLOPT_URL, $this->options['api'] . $endpoint);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: ' . $this->customerKey . ':' . $this->environmentKey,
            'Content-type: application/json',
            'X-Accept-Version: ' . $this->options['version']
        ));

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    public function getFeatures()
    {
        return $this->apiRequest('/features');
    }

    public function isEnabled($feature)
    {
        return $this->apiRequest('/features/' . $feature);
    }
}
