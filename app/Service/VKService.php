<?php

namespace App\Service;

use App\Jobs\GetClientsFromVk;
use VK\Client\VKApiClient;
use VK\Exceptions\Api\VKApiWeightedFloodException;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

class VKService
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var array
     */
    private $params;

    /**
     * VKService constructor.
     *
     * @param string $token
     */
    public function __construct(string $token, $params = [])
    {
        $this->VKApiClient = new VKApiClient;
        $this->params = $params;
        $this->token = $token;
    }

    public function getParam($name)
    {
        return $this->params[$name] ?? null;
    }

    /**
     * @param int $account_id
     * @return mixed
     * @throws VKApiWeightedFloodException
     * @throws VKApiException
     * @throws VKClientException
     */
    public function adsGetClients()
    {
        try {
            $job = new GetClientsFromVk($this->token, $this->getParam('account_id'));
            dispatch_now($job);
            $result = $job->getResponse();
        } catch (VKApiWeightedFloodException | VKApiException | VKClientException $e) {
            $result = ['error' => true, 'error_message' => $e->getMessage()];
        }

        return $result;
    }

    /**
     * @param $method
     * @return array
     */
    public function shapingAndCallMethod($method): array
    {
        try {
            $methodExplode = explode('.', $method);
            $methodName = $methodExplode[0] . ucfirst($methodExplode[1]);
            return $this->{$methodName}();
        } catch (\Throwable $th) {
            return ['error' => true, 'error_message' => 'method not exist'];
        }
    }
}