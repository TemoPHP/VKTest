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

class GetClientsFromVk implements ShouldQueue
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
     * @var string
     */
    private $accountId;

    /**
     * GetClientsFromVk constructor.
     *
     * @param string $token
     * @param string $accountId
     */
    public function __construct(string $token, string $accountId)
    {
        $this->accountId = $accountId;
        $this->token = $token;
        $this->VKApiClient = new VKApiClient;
    }

    /**
     * Запрос к ВК
     */
    public function handle()
    {
        do {
            $this->getClients();
            $this->maxIteration -= 1;
        } while ($this->countResult >= count($this->response) || $this->maxIteration > 0);
    }

    private function getClients()
    {
        Redis::throttle('VKService')->allow(1)->every(0.7)->then(function (){
            $this->response[] = $this->VKApiClient->ads()->getClients($this->token, ['account_id' => $this->accountId]);
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
