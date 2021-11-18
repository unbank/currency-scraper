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

    private function update_exchange($crypto, $fcurrency, $data, $source='bitstamp', $currency_type='crypto') {

        // Sell Cryto
        // dd($data['last']);
        echo PHP_EOL, "Sell rate for $crypto -> $fcurrency: ".$data['last'];
        $cex = CurrencyExchange::updateOrCreate(
            ['from' => $crypto, 'to' => $fcurrency],
            [
                "rate" => $data['last'],
                "volume" => $data['volume'],
                "source" => $source,
                "source_data" => $data,
                "currency_type" => "crypto2currnecy",
                "transaction_type" => "sell",
                // "markup" => null
            ]
        );

        $markup = 1;

        // Buy Crypto
        $rate = $data['last'] + ( $data['last'] * $markup  );
        $rate = $data['last'];
        $rate = 1 / $rate;
        echo PHP_EOL, "Buy rate for $fcurrency -> $crypto: ".$rate;
        // dd($rate);
        $cex = CurrencyExchange::updateOrCreate(
            ['from' => $fcurrency, 'to' => $crypto],
            [
                "rate" => $rate,
                "volume" => $data['volume'],
                "source" => $source,
                "source_data" => $data,
                "currency_type" => "currnecy2crypto",
                "transaction_type" => "buy",
                "markup" => $markup
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
