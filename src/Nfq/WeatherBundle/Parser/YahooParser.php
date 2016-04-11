<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 10/04/16
 * Time: 22:23
 */

namespace Nfq\WeatherBundle\Parser;


use Nfq\WeatherBundle\Provider\WeatherProviderException;

class YahooParser extends WeatherProviderException
{

    /**
     * YahooParser constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $json
     * @return float
     * @throws WeatherProviderException
     */
    public function getTemperature($json): float
    {
        $parsed_json = $this->getParsedJson($json);

        if (null === $parsed_json) {
            throw new WeatherProviderException('Can\'t get JSON from yahoo');
        }

        if (isset($parsed_json->query->results) && (null === $parsed_json->query->results)) {
            // Example: {"query":{"count":0,"created":"2016-04-10T19:54:00Z","lang":"en-US","results":null}}
            throw new WeatherProviderException('Yahoo didn\'t returned required data');
        }

        // Get temperature from json
        if (isset($parsed_json->query->results->channel->item->condition->temp)) {
            $temperature = $parsed_json->query->results->channel->item->condition->temp;
        } else {
            throw new WeatherProviderException('Can\'t get temperature from yahoo');
        }

        return $temperature;
    }

    /**
     * @param $json_string
     * @return mixed
     */
    private function getParsedJson($json_string)
    {
        // Parse json
        $parsed_json = json_decode($json_string);

        return $parsed_json;
    }
}