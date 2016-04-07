<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 07/04/16
 * Time: 11:16
 */

namespace Nfq\WeatherBundle\Parser;


use Nfq\WeatherBundle\Provider\WeatherProviderException;

class OpenWeatherMapParser extends WeatherProviderException
{
    /**
     * OpenWeatherMapParser constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $json
     * @return float
     * @throws WeatherProviderException
     */
    public function getTemperature($json): float
    {
        $parsed_json = $this->getParsedJson($json);

        if (null !== $parsed_json) {
            if (isset($parsed_json->cod)) {

                // Check status
                $json_code = $parsed_json->cod;
                if (200 !== $json_code) {
                    if (isset($parsed_json->message)) {
                        // Display message text from openweathermap
                        $message = sprintf('Code: %d Message: %s', $json_code, $parsed_json->message);
                    } else {
                        // Display code only with custom message
                        $message = sprintf('Code: %d Message: Unknown error occured', $json_code);
                    }
                    throw new WeatherProviderException($message);
                }
            }

        }

        // Get temperature from json
        if (isset($parsed_json->main->temp)) {
            $temperature = $parsed_json->main->temp;
        } else {
            throw new WeatherProviderException('Can\'t get temperature from openweathermap');
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