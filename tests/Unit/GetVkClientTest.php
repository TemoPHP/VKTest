<?php

namespace Tests\Unit;

use Tests\TestCase;

class GetVkClientTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetClientsApi()
    {
        $response = $this->json('post', '/api/getClients',
            [
                'token' => "5cfb37890df078945330948ff246188b397a69f250951b256b44fb9af97d96fa0ab53b538bceae512dcc6",
                'method'=> "ads.getClients",
                'params'=>  [
                    "account_id" => 1900013439
                ]
            ]);

        $response->assertStatus(200);

        $response->assertDontSeeText('error');
    }
}
