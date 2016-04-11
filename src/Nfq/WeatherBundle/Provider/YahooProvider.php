<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 10/04/16
 * Time: 22:21
 */

namespace Nfq\WeatherBundle\Provider;


use Nfq\WeatherBundle\Location\Location;
use Nfq\WeatherBundle\Parser\YahooParser;
use Nfq\WeatherBundle\Weather\Weather;

class YahooProvider implements WeatherProviderInterface
{

    /**
     * @var YahooParser
     */
    private $yp;

    /**
     * YahooProvider constructor.
     * @param YahooParser $yp
     */
    public function __construct(YahooParser $yp)
    {
        $this->yp = $yp;
    }

    /**
     * @param Location $location
     * @return Weather
     * @throws WeatherProviderException
     */
    public function fetch(Location $location): Weather
    {
        $json = $this->getJson($location);

        $temperature = $this->yp->getTemperature($json);

        $weather = new Weather($temperature);

        // Return Weather object
        return $weather;
    }

    private function getJson(Location $location)
    {
        $base_url = "http://query.yahooapis.com/v1/public/yql";
        $yql_query = 'select * from weather.forecast where woeid in (SELECT woeid FROM geo.places WHERE text='
            .'"(%d,%d)") and u="C"';

        $url = sprintf(
            $yql_query,
            $location->getLongitude(),
            $location->getLatitude()
        );
        $yql_query_url = $base_url."?q=".urlencode($url)
            ."&format=json";

        // Check for curl
        if (!function_exists('curl_version')) {
            throw new WeatherProviderException('curl is disabled. Install and/or enable it');
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $yql_query_url);
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