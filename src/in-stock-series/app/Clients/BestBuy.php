<?php

namespace App\Clients;

use App\Stock;
use Illuminate\Support\Facades\Http;


class BestBuy implements Client
{
    public function checkAvailability(Stock $stock): StockStatus
    {
        $result = Http::get($this->endpoint($stock->sku))->json();

        return new StockStatus(
            $result['onlineAvailability'],
            $this->dollarsToCents($result['salePrice']),
        );
    }

    protected function endpoint($sku): string
    {
        $key = config('services.clients.bestBuy.key');

        return "https://api.bestbuy.com/v1/products/{$sku}.json?apiKey={$key}";
    }

    private function dollarsToCents($salePrice)
    {
        return (int)($salePrice * 100);
    }
}
