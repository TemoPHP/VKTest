<?php

namespace App\Service;

use App\Jobs\GetDataFromVK;
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
     * @param array $params
     */
    public function __construct(string $token, $params = [])
    {
        $this->params = $params;
        $this->token = $token;
    }

    /**
     * @param $method
     * @return array
     */
    public function shapingAndCallMethod($method): array
    {
        try {
            $methodExplode = explode('.', $method);
            $job = new GetDataFromVK($this->token, $methodExplode, $this->params);
            dispatch_now($job);

            return $job->getResponse();
        } catch (\Throwable $th) {
            return ['error' => true, 'error_message' => $th->getMessage()];
        }
    }
}