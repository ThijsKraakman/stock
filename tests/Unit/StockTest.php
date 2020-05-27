<?php

namespace Tests\Unit;

use App\Stock;
use App\Retailer;
use Tests\TestCase;
use App\Clients\Client;
use App\Clients\StockStatus;
use RetailerWithProductSeeder;
use App\Clients\ClientException;
use Facades\App\Clients\ClientFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StockTest extends TestCase
{
    use RefreshDatabase;
    public function tests_it_throws_an_exception_if_a_client_is_not_found_when_tracking()
    {
        $this->seed(RetailerWithProductSeeder::class);

        Retailer::first()->update(['name' => 'Foo retailer']);

        $this->expectException(ClientException::class);

        Stock::first()->track();
    }

    public function tests_it_updates_local_stock_after_being_tracker()
    {
        $this->seed(RetailerWithProductSeeder::class);

        ClientFactory::shouldReceive('make->checkAvailability')->andReturn(
            new StockStatus($available = true, $price = 9900)
        );

        $stock = tap(Stock::first())->track();

        $this->assertTrue($stock->in_stock);
        $this->assertEquals(9900, $stock->price);
    }
}
