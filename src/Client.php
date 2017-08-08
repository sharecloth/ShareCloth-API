<?php

namespace ShareCloth\Api;


use GuzzleHttp\Psr7\Response;
use ShareCloth\Api\Exception\BadResponseException;
use ShareCloth\Api\Response\ApiResponse;
use ShareCloth\Api\Response\JsonResponseFactory;

class Client implements ClientInterface
{

    /** @var  \GuzzleHttp\Client */
    protected $httpClient;

    /** @var  string */
    protected $apiSecret;

    /** @var  string */
    protected $client;

    /** @var string */
    protected $baseUri = 'http://api.sharecloth.com/v1/';

    /** @var  integer */
    protected $timeout = 300;

    /**
     * Client constructor.
     * @param $apiSecret
     * @param array $httpClientConfig
     *
     */
    public function __construct($apiSecret, $httpClientConfig = [])
    {
        $this->initHttpClient($httpClientConfig);
        $this->apiSecret = $apiSecret;
    }


    public function getApiSecret()
    {
        return $this->apiSecret;
    }

    /**
     * @param $options
     * @return ApiResponse
     * @throws BadResponseException
     */
    public function itemsList($options)
    {
        return $this->runApiMethod('items/list', $options);
    }

    /**
     * @param $options
     * @return ApiResponse
     * @throws BadResponseException
     */
    public function avatarList($options = [])
    {
        return $this->runApiMethod('avatar/list', $options);
    }

    /**
     * @return ApiResponse
     * @throws BadResponseException
     */
    public function optionsCollection()
    {
        return $this->runApiMethod('options/collections');
    }

    /**
     * @param $options
     * @return ApiResponse
     * @throws BadResponseException
     */
    public function optionsCollectionSizes($options)
    {
        return $this->runApiMethod('options/collection_sizes', $options);
    }

    /**
     * @param $options
     * @return ApiResponse
     * @throws BadResponseException
     */
    public function optionsAddSizingCollection($options)
    {
        return $this->runApiMethod('options/add-sizing-collection', $options);

    }

    /**
     * @return ApiResponse
     * @throws BadResponseException
     */
    public function optionTypes()
    {
        return $this->runApiMethod('options/types');
    }

    /**
     * @param $options
     * @return array
     * @throws BadResponseException
     */
    public function optionsAddCollection($options)
    {
        return $this->runApiMethod('options/add-collection', $options);
    }


    /**
     * @param $options
     * @return array
     * @throws BadResponseException
     */
    public function optionsSizes($options)
    {
        return $this->runApiMethod('options/sizes', $options);
    }

    /**
     * @param array $options
     * @return mixed
     * @throws BadResponseException
     */
    public function avatarCreate($options = [])
    {
        return $this->runApiMethod('avatar/create', $options);
    }

    /**
     * @param array $options
     * @return mixed
     * @throws BadResponseException
     */
    public function avatarUpdate($options = [])
    {
        return $this->runApiMethod('avatar/update', $options);
    }


    /**
     * @param $options
     * @param null $pattern
     * @param array|null $sketches
     * @param null $texture
     * @param null $curve
     * @param null $gdcb
     *
     *
     * @return array
     */
    public function productUpload($options, $pattern = null, array $sketches = null, $texture = null, $curve = null, $gdcb = null)
    {
        $data = [];

        foreach ($options as $key => $value) {
            $data[] = [
                'name' => $key,
                'contents' => $value
            ];
        }

        if (file_exists($pattern)) {
            $data[] = [
                'name' => 'pattern_file',
                'contents' => fopen($pattern, 'r')
            ];
        }

        if (!empty($sketches)) {
            foreach ($sketches as $sketchFile) {
                if (file_exists($sketchFile)) {
                    $data[] = [
                        'name' => 'sketch_files[]',
                        'contents' => fopen($sketchFile, 'r')
                    ];
                }
            }
        }

        if (file_exists($texture)) {
            $data[] = [
                'name' => 'texture',
                'contents' => fopen($texture, 'r')
            ];
        }

        if (file_exists($curve)) {
            $data[] = [
                'name' => 'curve',
                'contents' => fopen($curve, 'r')
            ];
        }

        if (file_exists($gdcb)) {
            $data[] = [
                'name' => 'gdcb',
                'contents' => fopen($gdcb, 'r')
            ];
        }


        return $this->runApiMethod('product/upload', [], 'POST', $data);
    }

    /**
     * Basic method for all api calls
     * @param $uri
     * @param array $options
     * @param string $method
     * @param array $multipart
     * @return mixed
     */
    protected function runApiMethod($uri, $options = [], $method = 'POST', $multipart = [])
    {
        $response = $this->makeRequest($uri, $options, $method, $multipart);
        return $response->getData();
    }


    /**
     * @param $uri
     * @param $formParams
     * @param string $method
     * @param array $multipart
     * @return ApiResponse
     * @throws BadResponseException
     */
    protected function makeRequest($uri, $formParams, $method = 'POST', $multipart = [])
    {
        if ($this->apiSecret) {
            $formParams = array_merge($formParams, ['api_secret' => $this->apiSecret]);
        }

        $options = [
            'form_params' => $formParams,
        ];

        if ($multipart) {
            unset($options['form_params']);
            $options['multipart'] = $multipart;

            if ($this->apiSecret) {
                $options['multipart'][] = [
                    'name' => 'api_secret',
                    'contents' => $this->apiSecret,
                ];
            }
        }

        $response = $this->httpClient->request($method, $uri, $options);
        if ($response->getStatusCode() == 200) {
            $parsed =  $this->parseResponse($response);
            if (! $parsed->isResponseSuccess() ) {
                throw new BadResponseException($parsed->getErrorMessage());
            }

            return $parsed;
        }

        throw new BadResponseException($response->getStatusCode());
    }

    /**
     * @param $httpClientConfig
     */
    protected function initHttpClient($httpClientConfig)
    {
        $config = array_merge([
            'base_uri' => $this->baseUri,
            'timeout' => $this->timeout,
        ], $httpClientConfig);

        $this->httpClient = new \GuzzleHttp\Client($config);
    }

    /**
     * Parse response from API and returns response object
     * @param Response $response
     * @return ApiResponse
     */
    protected function parseResponse(Response $response)
    {
        $data = $response->getBody()->getContents();
        $factory = new JsonResponseFactory();
        return $factory->getApiResponse($data);
    }


}