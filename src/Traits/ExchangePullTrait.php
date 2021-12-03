<?php


namespace Unbank\CurrencyScraper\Traits;

use Unbank\CurrencyScraper\CurrencyExchange;

trait ExchangePullTrait {


    /**
     * Update exchange rate
     *
     * @param string $crypto
     * @param string $fcurrency
     * @param array $data
     * @param string $source
     * @param string $currency_type
     * @param string $markup
     * @return object
     */
    public function update_exchange($crypto, $fcurrency, $data, $source='bitstamp', $currency_type='crypto', $markup=1) {

        // Sell Cryto
        echo PHP_EOL, "Sell rate for $crypto -> $fcurrency: ".$data['last'];
        $sell = CurrencyExchange::updateOrCreate(
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

        // Buy Crypto
        $rate = $data['last'] + ( $data['last'] * $markup  );
        $rate = $data['last'];
        $rate = 1 / $rate;
        echo PHP_EOL, "Buy rate for $fcurrency -> $crypto: ".$rate;
        $buy = CurrencyExchange::updateOrCreate(
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
        return (object) [
            'sell' => $sell,
            'buy' => $buy
        ];
    }


}

?>
