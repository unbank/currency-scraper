<?php

namespace Unbank\CurrencyScraper\Commands;

use Illuminate\Console\Command;
use Unbank\CurrencyScraper\BitstampScraper;
use Unbank\CurrencyScraper\CurrencyExchange;
use Unbank\CurrencyScraper\Traits\ExchangePullTrait;

class FetchCurrency extends Command
{
    use ExchangePullTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:scraper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Crypto Currency';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $btc_data = BitstampScraper::btc2usd();
        $ltc_data = BitstampScraper::ltc2usd();
        echo "BTC to USD: ", PHP_EOL, json_encode($btc_data, true);
        $this->update_exchange(
            "BTC", "USD",
            $btc_data
        );


        echo PHP_EOL, PHP_EOL, "LTC to USD: ", PHP_EOL, json_encode($ltc_data, true);
        $this->update_exchange(
            "LTC", "USD",
            $btc_data
        );

        echo PHP_EOL;
    }
}
