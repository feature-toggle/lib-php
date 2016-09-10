<?php

namespace FeatureToggle;

use GuzzleHttp\Client;
use \GuzzleHttp\Exception\ConnectException;
use \GuzzleHttp\Exception\ClientException;
use \GuzzleHttp\Exception\ServerException;

use phpFastCache\CacheManager;

class FTLibPhp
{
    const VERSION = '1.0';

    private $_customerKey;
    private $_environmentKey;
    private $_options = [
        'api' => 'https://api.featuretoggle.com',
        'auth' => null,
        'cache_timeout' => 300, // in seconds
        'version' => 'v1',
        'debug' => false
    ];

    private $_gzClient;
    private $_cacheManager;

    public function __construct($customerKey, $environmentKey, $options)
    {
        $this->_customerKey = $customerKey;
        $this->_environmentKey = $environmentKey;
        $this->_options = array_merge($this->_options, $options);
        $this->_options['auth'] = "{$this->_customerKey}:{$this->_environmentKey}";

        $this->_gzClient = new Client(array(
            'base_uri' => $this->_options['api'],
        ));

        CacheManager::setDefaultConfig(array(
            "path" => sys_get_temp_dir(),
        ));
        //CacheManager::CachingMethod("phpfastcache");
        $this->_cacheManager = CacheManager::getInstance('files');
    }

    public function cleacCache() {
        $this->_cacheManager->clear();
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
        $CachedString = $this->_cacheManager->getItem('features');

        if (is_null($CachedString->get())) {
            $result = $this->apiRequest('/features');

            $CachedString->set($result['data']['features'])->expiresAfter($this->_options['cache_timeout']);
            $this->_cacheManager->save($CachedString);
        }

        return $CachedString->get();

    }

    public function isEnabled($key)
    {
        $CachedString = $this->_cacheManager->getItem($key);

        if (is_null($CachedString->get())) {
            $result = $this->apiRequest('/features/' . $key);

            $CachedString->set($result['data']['enabled'])->expiresAfter($this->_options['cache_timeout']);
            $this->_cacheManager->save($CachedString);
        }

        return $CachedString->get();
    }
}
