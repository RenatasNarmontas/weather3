<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 06/04/16
 * Time: 23:43
 */

namespace Nfq\WeatherBundle\Provider;


use Nfq\WeatherBundle\Location\Location;
use Nfq\WeatherBundle\Parser\OpenWeatherMapParser;
use Nfq\WeatherBundle\Weather\Weather;

class OpenWeatherMapProvider implements WeatherProviderInterface
{
    /**
     * @var Weather
     */
    private $weather;

    /**
     * @var string
     */
    private $api;

    /**
     * @var OpenWeatherMapParser
     */
    private $owmp;

    /**
     * OpenWeatherMapProvider constructor.
     * @param OpenWeatherMapParser $owmp
     * @param string $api
     */
    public function __construct(OpenWeatherMapParser $owmp, string $api)
    {
        $this->weather = new Weather();
        $this->api = $api;
        $this->owmp = $owmp;
    }

    /**
     * @param Location $location
     * @return Weather
     * @throws WeatherProviderException
     */
    public function fetch(Location $location): Weather
    {
        $json = $this->getJson($location);

        $temperature = $this->owmp->getTemperature($json);

        $this->weather->setTemperature($temperature);

        // Return Weather object
        return $this->weather;
    }

    /**
     * @param Location $location
     * @return mixed
     * @throws WeatherProviderException
     */
    private function getJson(Location $location)
    {
        $url = sprintf(
            'http://api.openweathermap.org/data/2.5/weather?lat=%d&lon=%d&appid=%s&units=metric',
            $location->getLatitude(),
            $location->getLongtitude(),
            $this->api
        );

        if (!function_exists('curl_version')) {
            throw new WeatherProviderException('curl is disabled. Install and/or enable it');
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $json_string = curl_exec($curl);

        // Check for curl errors
        if (curl_error($curl)) {
            $curl_err = curl_error($curl);
            curl_close($curl);
            throw new WeatherProviderException(sprintf('curl error: %s', $curl_err));
        }
        curl_close($curl);

        return $json_string;
    }
}
