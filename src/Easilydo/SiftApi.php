<?php

namespace Easilydo;

use Easilydo\Exceptions\SiftApiException;
use Easilydo\Exceptions\SiftRequestException;
use Easilydo\SignatureGenerator;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

/**
 * Class SiftApi
 *
 * @package Easilydo
 */
class SiftApi
{
    /**
     * @const string The Sift API URL
     */
    const API_URL = 'https://api.easilydo.com';

    /**
     * @var string The API Key used with requests
     */
    private $apiKey;

    /**
     * @var string The API Secret used in generating request signatures
     */
    private $apiSecret;

    /**
     * @var \GuzzleHttp\Client The Guzzle Client
     */
    private $guzzleClient;

    /**
     * Instantiates a new SiftApi object
     *
     * @param string $apiKey
     * @param string $apiSecret
     * @param string $guzzleClient
     *
     * @throws Easilydo\Exceptions\SiftApiException
     */
    public function __construct($apiKey, $apiSecret, $guzzleClient = NULL)
    {
        if (!is_string($apiKey) || empty($apiKey)) {
            throw new SiftApiException('Required "apiKey" must be a string and cannot be empty');
        }
        if (!is_string($apiSecret) || empty($apiSecret)) {
            throw new SiftApiException('Required "$apiSecret" must be a string and cannot be empty');
        }
        if ($guzzleClient !== NULL) {
            if (!($guzzleClient instanceof \GuzzleHttp\Client)) {
                throw new SiftApiException('"$guzzleClient" must be an instance of \\GuzzleHttp\\Client');
            } elseif ($guzzleClient->getConfig('base_uri') != self::API_URL) {
                throw new SiftApiException('"$guzzleClient" config base_uri must be SiftApi::API_URL');
            }
        }
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->guzzleClient = $guzzleClient ?: new \GuzzleHttp\Client([
            'base_uri' => self::API_URL
        ]);
    }

    /**
     * Generates default params
     */
     private function generateDefaultParams()
     {
         return [
             'api_key' => $this->apiKey,
             'timestamp' => time()
         ];
     }

    /**
     * Makes a HTTP request to the path
     *
     * @param string $method GET/POST/PUT/DELETE
     * @param string $path The path of the request being made
     * @param string $params The url parameters
     * @param string $data The data in the request body
     *
     * @throws Easilydo\Exceptions\SiftRequestException
     * @return array The json_decoded response body
     */
    private function request($method, $path, array $params = [], array $data = [])
    {
        $queryParams = array_merge($params, $this->generateDefaultParams());
        $mergedParams = array_merge($queryParams, $data);
        $queryParams['signature'] = SignatureGenerator::generate($this->apiSecret, $method, $path, $mergedParams);

        try {
            $rawResponse = $this->guzzleClient->request($method, $path, [
                'body' => http_build_query($data),
                'headers'  => ['content-type' => 'application/x-www-form-urlencoded'],
                'query' => $queryParams
            ]);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                throw new SiftRequestException($response->getReasonPhrase(), $response->getStatusCode());
            }
            throw new SiftRequestException();
        }

        $body = json_decode((string) $rawResponse->getBody(), true);
        if ($body['code'] != 200) {
            throw new SiftRequestException($body['message'], $body['code']);
        }

        return $body;
    }

    /**
     * Performs discovery on the input eml file
     *
     * @param string $email The contents of the eml file
     *
     * @throws Easilydo\Exceptions\SiftRequestException
     * @return array The json_decoded response body
     */
    public function discovery($email)
    {
        return $this->request('POST', '/v1/discovery', [], ['email' => trim($email)]);
    }

    /**
     * Deletes the user with the input username
     *
     * @param string $username The username of the user to be deleted
     *
     * @throws Easilydo\Exceptions\SiftRequestException
     * @return array The json_decoded response body
     */
    public function deleteUser($username)
    {
        return $this->request('DELETE', "/v1/users/$username");
    }

    /**
     * Adds a user with the input username
     *
     * @param string $locale The locale of the user (e.g. en_US)
     * @param string $username The username of the user to be added
     *
     * @throws Easilydo\Exceptions\SiftRequestException
     * @return array The json_decoded response body
     */
    public function addUser($locale, $username)
    {
        return $this->request('POST', '/v1/users', [], ['locale' => $locale, 'username' => $username]);
    }

    /**
     * Gets the email connections of a user
     *
     * @param string $username The username of the user
     * @param integer $limit The maximum number of results to return
     * @param integer $offset Start the list at this offset (zero-based)
     *
     * @throws Easilydo\Exceptions\SiftRequestException
     * @return array The json_decoded response body
     */
    public function getEmailConnections($username, $limit = 100, $offset = 0, $includeInvalid = false)
    {
        $params = ['limit' => $limit, 'offset' => $offset];
        if ($includeInvalid) {
            $params['include_invalid'] = 1
        }

        return $this->request('GET', "/v1/users/$username/email_connections", $params);
    }

    /**
     * Adds an email connections to a user
     *
     * @param string $username The username of the user
     * @param Easilydo\EmailConnections\Connection The connection to be added
     *
     * @throws Easilydo\Exceptions\SiftApiException If $connection is not an instance of Connection
     * @throws Easilydo\Exceptions\SiftRequestException
     * @return array The json_decoded response body
     */
    public function addEmailConnection($username, $connection)
    {
        if (!($connection instanceof \Easilydo\EmailConnections\Connection)) {
            throw new SiftApiException(
                'Required "connection" must be an instance of \Easilydo\EmailConnections\Connection'
            );
        }
        return $this->request('POST', "/v1/users/$username/email_connections", [], $connection->getAddBody());
    }

    /**
     * Deletes an email connections from a user
     *
     * @param string $username The username of the user
     * @param string $connectionId The id of the connection to be removed
     *
     * @throws Easilydo\Exceptions\SiftRequestException
     * @return array The json_decoded response body
     */
    public function deleteEmailConnection($username, $connectionId)
    {
        return $this->request('DELETE', "/v1/users/$username/email_connections/$connectionId");
    }

    /**
     * Gets the list of sifts for a specified user
     *
     * @param string $username The username of the user
     * @param integer $limit The maximum number of results to return
     * @param integer $offset Start the list at this offset (zero-based)
     * @param integer $lastUpdateTime Only sifts with last update time greater than this will be included
     * @param array $domains The list of domains (E.g. flight, hotel, rentalcar, ...)
     *
     * @throws Easilydo\Exceptions\SiftRequestException
     * @return array The json_decoded response body
     */
    public function getSifts($username, $limit = 100, $offset = 0, $lastUpdateTime = 0, array $domains = [])
    {
        $params = [
            'limit' => $limit,
            'offset' => $offset,
            'last_update_time' => $lastUpdateTime
        ];

        if (count($domains) > 0) {
            $params['domains'] = implode(',', $domains);
        }

        return $this->request('GET', "/v1/users/$username/sifts", $params);
    }

    /**
     * Gets the sift with the specified id for the specified user
     *
     * @param string $username The username of the user
     * @param string $siftId The id of the sift
     *
     * @throws Easilydo\Exceptions\SiftRequestException
     * @return array The json_decoded response body
     */
    public function getSift($username, $siftId, $includeEml = false)
    {
        $params = [];
        if ($includeEml) {
            $params['include_eml'] = 1;
        }
        return $this->request('GET', "/v1/users/$username/sifts/$siftId", $params);
    }

    /**
     * Retrieves a new connect token for a given username
     *
     * @param string $username The username of the user
     *
     * @throws Easilydo\Exceptions\SiftRequestException
     * @return array The json_decoded response body
     */
    public function getConnectToken($username)
    {
        return $this->request('POST', "/v1/connect_token", [], ['username' => $username]);
    }

    /**
     * Notify Easilydo of emails that sift did not parse correctly
     *
     * @param string $email The contents of the eml file
     * @param string $locale The locale of the email (e.g. en_US)
     * @param string $timezone The timezone of the email, e.g. America/Los_Angeles
     *
     * @throws Easilydo\Exceptions\SiftRequestException
     * @return array The json_decoded response body
     */
    public function sendFeedback($email, $locale, $timezone)
    {
        return $this->request('POST', "/v1/feedback", [], [
            'email' => $email,
            'locale' => $locale,
            'timezone' => $timezone
        ]);
    }

    /**
     * Generates the url of the email connection webpage
     *
     * @param string $username The username of the user
     * @param string $redirectUrl The URL that you want to redirect the person logging in back to
     * @param string $token The connect token that should be generated by calling getConnectToken (optional)
     *
     * @throws Easilydo\Exceptions\SiftRequestException
     * @return string The connect email url
     */
    public function getConnectEmailUrl($username, $redirectUrl = NULL, $token = NULL)
    {
        if ($token === NULL) {
            $token = $this->getConnectToken($username)['result']['connect_token'];
        }

        $params = [
            'api_key' => $this->apiKey,
            'username' => $username,
            'token' => $token
        ];

        if ($redirectUrl !== NULL) {
            $params['redirect_url'] = $redirectUrl;
        }

        $query = http_build_query($params);
        return "https://api.easilydo.com/v1/connect_email?$query";
    }
}
?>
