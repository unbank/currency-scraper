<?php


namespace Unbank\CurrencyScraper;


class BitstampScraper {

    const API_URL = "https://www.bitstamp.net";

    public static function sendGetRequest($path, $headers=[]) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => static::API_URL."/$path",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $headers
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);
        return $data;
    }

    public static function ticker($ticker) {
        return static::sendGetRequest("api/v2/ticker/$ticker");
    }

    public static function btc2usd() {
        return static::ticker("btcusd");
    }

    public static function ltc2usd() {
        return static::ticker("ltcusd");
    }
}


?>
