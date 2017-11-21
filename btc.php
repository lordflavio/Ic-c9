<?php

class BTCPrice {


/*
Historical BPI data
We offer historical data from our Bitcoin Price Index through the following endpoint:

    http://api.coindesk.com/v1/bpi/historical/close.json

By default, this will return the previous 31 days' worth of data. This endpoint accepts the following optional parameters:

    ?index=[USD/CNY]The index to return data for. Defaults to USD.
    ?currency=<VALUE>The currency to return the data in, specified in ISO 4217 format. Defaults to USD.
    ?start=<VALUE>&end=<VALUE> Allows data to be returned for a specific date range. Must be listed as a pair of start and end parameters, with dates supplied in the YYYY-MM-DD format, e.g. 2013-09-01 for September 1st, 2013.
    ?for=yesterdaySpecifying this will return a single value for the previous day. Overrides the start/end parameter.

*/


		  //public function __construct(){}

			static public function getRange($start_date, $end_date){
				$url = "http://api.coindesk.com/v1/bpi/historical/close.json?start=$start_date&end=$end_date";

                //for debug
                //echo $url;
                
                // create curl resource
                $ch = curl_init();
                
                // set url
                curl_setopt($ch, CURLOPT_URL, $url);
                
                //return the transfer as a string
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                
                // $output contains the output string
                $output = curl_exec($ch);
                
                // close curl resource to free up system resources
                curl_close($ch);

				return $output;
			}

}


$json = json_decode(
	BTCPrice::getRange("2013-09-01","2013-09-05")
);
echo "<pre>";
print_r($json);


?>