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
     * @param $email
     * @param $password
     * @param array $httpClientConfig
     *
     * @throws BadResponseException
     */
    public function __construct($email, $password, $httpClientConfig = [])
    {
        $this->initHttpClient($httpClientConfig);
        $response = $this->makeRequest('user/login', [
            'email' => $email,
            'password' => $password
        ]);


        if ($response->isResponseSuccess()) {
            $this->apiSecret = $response->getDataItem('api_secret');
            $this->client = $response->getDataItem('id');
        } else {
            throw  new BadResponseException($response->getDataItem('message'));
        }
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
     * Basic method for all api calls
     * @param $method
     * @param array $options
     * @return mixed
     * @throws BadResponseException
     */
    protected function runApiMethod($method, $options = [])
    {
        $response = $this->makeRequest($method, $options);
        return $response->getData();
    }



    /**
     * @param $uri
     * @param $options
     * @param string $method
     * @return ApiResponse
     * @throws BadResponseException
     */
    protected function makeRequest($uri, $options, $method = 'POST')
    {
        if ($this->client && $this->apiSecret) {
            $options = array_merge($options, $this->generateAuthData());
        }

        $response = $this->httpClient->request($method, $uri, ['form_params' => $options]);
        if ($response->getStatusCode() == 200) {
            return $this->parseResponse($response);
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

    protected function parseResponse(Response $response)
    {
        $data = $response->getBody()->getContents();
        $factory = new JsonResponseFactory();
        return $factory->getApiResponse($data);
    }

    protected function generateAuthData()
    {
        $time = time();

        return [
            'client' => $this->client,
            'time' => $time,
            'sign' => md5($this->client . $this->apiSecret . $time )
        ];
    }


}