<?php

namespace Tests\Feature;

use App\User;
use App\Product;
use Tests\TestCase;
use RetailerWithProductSeeder;
use App\Notifications\ImportantStockUpdate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrackCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        $this->seed(RetailerWithProductSeeder::class);
    }

    /** @test */
    function it_tracks_product_stock()
    {
        $this->assertFalse(Product::first()->inStock());
        $this->mockClientRequest();

        $this->artisan('track')
        ->expectsOutput('All done!');

        $this->assertTrue(Product::first()->inStock());
    }

    /** @test */
    function it_notifies_the_user_when_the_stock_is_now_available()
    {
        $this->mockClientRequest();

        $this->artisan('track');

        Notification::assertSentTo(User::first(), ImportantStockUpdate::class);
    }

    /** @test */
    function it_does_not_notify_when_the_stock_remains_unavailable()
    {
        $this->mockClientRequest($available = false);

        $this->artisan('track');

        Notification::assertNothingSent();
    }
}
