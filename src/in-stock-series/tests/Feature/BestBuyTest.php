<?php

namespace Tests\Feature;

use App\Stock;
use Tests\TestCase;
use App\Clients\BestBuy;
use RetailerWithProductSeeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @group api
 */
class BestBuyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_tracks_a_product()
    {
        // given i have a product
        $this->seed(RetailerWithProductSeeder::class);

        // with stock at best buy
        $stock = tap(Stock::first())->update([
            'sku' => '6364253',  // Nintendo Switch sku
            'url' => 'https://www.bestbuy.com/site/nintendo-switch-32gb-console-gray-joy-con/6364253.p?skuId=6364253',
        ]);

        // if i use the best but client to track that stock/sku
        try {
            (new BestBuy())->checkAvailability($stock);
        } catch (\Exception $e) {
            $this->fail('Failed to track the BestBuy API properly. ' . $e->getMessage());
        }

        $this->assertTrue(true);
    }

    /** @test */
    function it_creates_the_proper_stock_status_response()
    {
        Http::fake(fn() => ['salePrice' => 299.99, 'onlineAvailability' => true]);

        $stickStatus = (new BestBuy())->checkAvailability(new Stock());

        $this->assertEquals(29999, $stickStatus->price);
        $this->assertTrue($stickStatus->available);
    }
}
