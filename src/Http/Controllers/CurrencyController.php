<?php

namespace Unbank\CryptoScraper\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Unbank\CryptoScraper\BitstampScraper;
use Unbank\CryptoScraper\CurrencyExchange;

class CurrencyController {


    public function index(Request $request) {

        $source = $request->input('source', 'bitstamp');
        $from = $request->input('from');
        $to = $request->input('to', 'USD');

        if ( !empty($from) && !empty($to) && !empty($source) ) {

            $rules = [
                'from' => "string|required|exists:currencies,from",
                'to' => "string|required|exists:currencies,to",
                'source' => "string",
                'exchange' => "numeric"
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    "errors" => $validator->errors()->all()
                ]);
            }

            $from_value = $request->input('exchange', 1);
            $cex = CurrencyExchange::exchange(strtoupper($from), strtoupper($to))
                ->first();

            if ( ! $cex ) {
                return response()->json([
                    'success' => false,
                    "errors" => [[
                        "not_found" => "No echange rate found for $from to $to"
                    ]]
                ]);
            }

            $cex['exchange'] = $from_value;
            $cex['exchange_result'] =   $from_value * $cex['rate'];
            return response()->json($cex);
        }

        $exchanges = CurrencyExchange::all();
        return response()->json($exchanges);

    }


    public function update(Request $request) {

        $request->validate([
            'from' => 'required|string'
        ]);

        $source = $request->input('source', 'bitstamp');
        $from = $request->input('from');
        $to = $request->input('to', 'USD');

        $data = BitstampScraper::ticker($from.$to);
        $cex = CurrencyExchange::updateOrCreate(
            [
                "from" => $from,
                "to" => $to
            ],
            [
                "rate" => $data['last'],
                "volume" => $data['volume'],
                "source" => $source,
                "source_data" => $data,
                "currency_type" => "crypto",
            ]
        );

        return response()->json($cex);


    }


}
