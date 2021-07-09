<?php
/*
 * Copyright (c) 2021, Bastian Leicht <mail@bastianleicht.de>
 *
 * This code is licensed under MIT license!
 */

namespace Livck;

use GuzzleHttp\Client;
use Livck\Exception\ParameterException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Livck
{
    private $httpClient;
    private $url;

    /**
     * Livck constructor.
     *
     * @param string $url
     * @param null $httpClient
     */
    public function __construct(
        string $url = 'demo.livck.com',
        $httpClient = null
    ) {
        $this->setHttpClient($httpClient);
        $this->url = $url;
    }

    /**
     * @param $httpClient Client|null
     */
    public function setHttpClient(Client $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new Client([
            'allow_redirects' => false,
            'follow_redirects' => false,
            'timeout' => 120
        ]);
    }

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @param string    $actionPath The resource path you want to request, see more at the documentation.
     * @param array     $params     Array filled with request params
     * @param string    $method     HTTP method used in the request
     *
     * @return ResponseInterface
     * @throws GuzzleException
     *
     * @throws ParameterException If the given field in params is not an array
     */
    private function request(string $actionPath, array $params = [], string $method = 'GET')
    {
        $url = $this->url . '/api/v1/' . $actionPath;

        if (!is_array($params)) {
            throw new ParameterException();
        }

        switch ($method) {
            case 'GET':
                return $this->getHttpClient()->get($url, [
                    'verify' => false,
                    'query'  => $params,
                ]);
                break;
            case 'POST':
                return $this->getHttpClient()->post($url, [
                    'verify' => false,
                    'form_params'   => $params,
                ]);
                break;
            case 'PUT':
                return $this->getHttpClient()->put($url, [
                    'verify' => false,
                    'form_params'   => $params,
                ]);
            case 'DELETE':
                return $this->getHttpClient()->delete($url, [
                    'verify' => false,
                    'form_params'   => $params,
                ]);
            default:
                throw new ParameterException('Wrong HTTP method passed');
        }
    }

    /**
     * @param $response ResponseInterface
     *
     * @return array|string
     */
    private function processRequest(ResponseInterface $response)
    {
        $response = $response->getBody()->__toString();
        $result = json_decode($response);
        if (json_last_error() == JSON_ERROR_NONE) {
            return $result;
        } else {
            return $response;
        }
    }

    /**
     * @throws GuzzleException
     */
    public function get($actionPath, $params = [])
    {
        $response = $this->request($actionPath, $params);

        return $this->processRequest($response);
    }

    /**
     * @throws GuzzleException
     */
    public function put($actionPath, $params = [])
    {
        $response = $this->request($actionPath, $params, 'PUT');

        return $this->processRequest($response);
    }

    /**
     * @throws GuzzleException
     */
    public function post($actionPath, $params = [])
    {
        $response = $this->request($actionPath, $params, 'POST');

        return $this->processRequest($response);
    }

    /**
     * @throws GuzzleException
     */
    public function delete($actionPath, $params = [])
    {
        $response = $this->request($actionPath, $params, 'DELETE');

        return $this->processRequest($response);
    }

    private $infoHandler;

    public function getInfo(): Info
    {
        if(!$this->infoHandler) {
            $this->infoHandler = new Info($this);
        }

        return $this->infoHandler;
    }
}
