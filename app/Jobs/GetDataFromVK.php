<?php

namespace App\Jobs;

use App\Service\VKService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use VK\Client\VKApiClient;

class GetDataFromVK implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * @var array
     */
    private $response = [];

    /**
     * @var VKApiClient
     */
    private $VKApiClient;

    /**
     * @var int
     */
    private $maxIteration = 10;

    /**
     * @var string
     */
    private $token;

    /**
     * @var int
     */
    private $countResult = 5;

    /**
     * @var array
     */
    private $params;

    /**
     * @var array
     */
    private $methodExplode;

    /**
     * GetDataFromVK constructor.
     *
     * @param string $token
     * @param array $methodExplode
     * @param array $params
     */
    public function __construct(string $token, array $methodExplode, array $params = [])
    {
        $this->params = $params;
        $this->token = $token;
        $this->methodExplode = $methodExplode;
        $this->VKApiClient = new VKApiClient;
    }

    /**
     * Запрос к ВК
     */
    public function handle()
    {
        do {
            $this->executeAction();
            $this->maxIteration -= 1;
        } while ($this->countResult >= count($this->response) || $this->maxIteration > 0);
    }

    private function executeAction()
    {
        Redis::throttle('VKService')->allow(1)->every(1)->then(function (){
            $this->response[] = $this->VKApiClient->{$this->methodExplode[0]}()->{$this->methodExplode[1]}($this->token, $this->params);
        }, function () {
            $this->release();
        });
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }
}
