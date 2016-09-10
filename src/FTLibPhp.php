<?php

namespace FeatureToggle;

use GuzzleHttp\Client;
use \GuzzleHttp\Exception\ConnectException;
use \GuzzleHttp\Exception\ClientException;
use \GuzzleHttp\Exception\ServerException;

class FTLibPhp
{
    const VERSION = '1.0';

    private $_customerKey;
    private $_environmentKey;
    private $_options = [
        'api' => 'https://api.featuretoggle.com',
        'auth' => null,
        'cache_timeout' => 300,
        'version' => 'v1',
        'debug' => false
    ];

    private $_gzClient;

    public function __construct($customerKey, $environmentKey, $options)
    {
        $this->_customerKey = $customerKey;
        $this->_environmentKey = $environmentKey;
        $this->_options = array_merge($this->_options, $options);
        $this->_options['auth'] = "{$this->_customerKey}:{$this->_environmentKey}";

        $this->_gzClient = new Client(array(
            'base_uri' => $this->_options['api'],
        ));

    }

    protected function apiRequest($endpoint) {
        $result = array (
            'success' => false,
            'message' => '',
            'statusCode' => -1,
            'data' => null
        );
        try {
            $response = $this->_gzClient->request(
                'GET',
                $endpoint,
                array(
                    'debug' => $this->_options['debug'],
                    'headers' => array(
                        'Authorization' => $this->_options['auth'],
                        'Content-Type' => 'application/json',
                        'X-Accept-Version' => $this->_options['version'],
                        'User-Agent' => 'FeatureToggle-PHP/' . self::VERSION
                    )
                )
            );
            try {
                $data = json_decode((string) $response->getBody(), true);
                $result['statusCode'] = $response->getStatusCode();
                if ($response->getStatusCode() === 200) {
                    $result['success'] = true;
                    $result['data'] = $data;
                } else {
                    $result['message'] = $data['message'];
                }
            } catch (Exception $e) {
                $result['message'] = 'Error communicating with Feature Toggle';
            }
        } catch (ConnectionException $e) {
            $result['message'] = 'Error communicating with Feature Toggle';
        } catch (ServerException $e) {
            $result['message'] = 'Error communicating with Feature Toggle';
        } catch (ClientException $e) {
            $result['statusCode'] = $e->getResponse()->getStatusCode();
            $result['message'] = $e->getResponse()->getBody();
        }
        return $result;
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
