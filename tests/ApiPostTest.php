<?php

namespace App\Tests;


use GuzzleHttp\Client as HttpClient;
use PHPUnit\Framework\TestCase;

class ApiPostTest extends TestCase
{
    /**
     * @var HttpClient
     */
    protected HttpClient $client;


    protected function setUp():void
    {
        $this->client = new HttpClient([
            'base_uri' => 'http://127.0.0.1:8000',
            'defaults' => [
                'exceptions' => false
            ]
        ]);
    }

    public function testItemsPost(): void
    {
        $data = [];
        $tabCurrency = ['EUR', 'US'];

        for ($i = 0; $i < 10; $i++) {
            $itemName = 'ObjectItem' . rand(0, 999);
            $seller = 'seller' . rand(0, 5);
            $rand_keys = array_rand($tabCurrency, 1);
            $currency = $tabCurrency[$rand_keys];

            $data[] = array(
                'item' => $itemName,
                'priceCurrency' => $currency,
                'priceAmount' => rand(0, 1000),
                'sellerReference' => $seller
            );

        }

        $dataHttp = array('json' => $data);
        $response = $this->client->post('/api/payout',$dataHttp);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));
        $payoutList = json_decode($response->getBody(), true);
        $this->assertIsArray( $payoutList,"assert variable is array or not");

    }


    public function testSplitedInTwoPayoutPost(): void
    {
        $data = [];
        $tabCurrency = ['EUR'];
        $expectedCount = 2;

        for ($i = 0; $i < 2; $i++) {
            $itemName = 'ObjectItem' . rand(0, 999);
            $seller = 'seller';
            $rand_keys = array_rand($tabCurrency, 1);
            $currency = $tabCurrency[$rand_keys];

            $data[] = array(
                'item' => $itemName,
                'priceCurrency' => $currency,
                'priceAmount' => 70,
                'sellerReference' => $seller
            );

        }

        $dataHttp = array('json' => $data);
        $response = $this->client->post('/api/payout',$dataHttp);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));
        $payoutList = json_decode($response->getBody(), true);
        $this->assertIsArray( $payoutList,"assert variable is array or not");
        $this->assertCount($expectedCount,$payoutList, "payout List doesn't contains 2 elements"
        );

    }

    public function testItemsSplitedInOnePayoutPost(): void
    {
        $data = [];
        $tabCurrency = ['EUR'];
        $expectedCount = 1;

        for ($i = 0; $i < 2; $i++) {
            $itemName = 'ObjectItem' . rand(0, 999);
            $seller = 'seller';
            $rand_keys = array_rand($tabCurrency, 1);
            $currency = $tabCurrency[$rand_keys];

            $data[] = array(
                'item' => $itemName,
                'priceCurrency' => $currency,
                'priceAmount' => 50,
                'sellerReference' => $seller
            );

        }


        $dataHttp = array('json' => $data);
        $response = $this->client->post('/api/payout',$dataHttp);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));
        $payoutList = json_decode($response->getBody(), true);
        $this->assertIsArray( $payoutList,"assert variable is array or not");
        $this->assertCount($expectedCount,$payoutList, "payout List doesn't contains 1 elements");

    }


    public function testItemsAmountErrorPost(): void
    {
        $data = [];
        $tabCurrency = ['EUR'];

        for ($i = 0; $i < 2; $i++) {
            $itemName = 'ObjectItem' . rand(0, 999);
            $seller = 'seller' . rand(0, 5);
            $rand_keys = array_rand($tabCurrency, 1);
            $currency = $tabCurrency[$rand_keys];

            $data[] = array(
                'item' => $itemName,
                'priceCurrency' => $currency,
                'priceAmount' => "AA",
                'sellerReference' => $seller
            );

        }

        $dataHttp = array('json' => $data);
        $response = $this->client->post('/api/payout',$dataHttp);
        $this->assertEquals(400, $response->getStatusCode());



    }
}
