<?php

namespace Unbank\CurrencyScraper\Commands;

use Illuminate\Console\Command;
use Unbank\CurrencyScraper\BitstampScraper;
use Unbank\CurrencyScraper\CurrencyExchange;

class FetchCurrency extends Command
{
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

    private function update_exchange($exchange, $data, $source='bitstamp', $currency_type='crypto') {
        $cex = CurrencyExchange::updateOrCreate(
            $exchange,
            [
                "rate" => $data['last'],
                "volume" => $data['volume'],
                "source" => $source,
                "source_data" => $data,
                "currency_type" => "crypto",
            ]
        );
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
            ['from' => "BTC", 'to' => 'USD'],
            $btc_data
        );

        echo PHP_EOL, PHP_EOL, "LTC to USD: ", PHP_EOL, json_encode($ltc_data, true);
        $this->update_exchange(
            ['from' => "LTC", 'to' => 'USD'],
            $btc_data
        );

        echo PHP_EOL;
    }
}
