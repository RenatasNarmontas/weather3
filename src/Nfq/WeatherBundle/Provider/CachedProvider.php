<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 14/04/16
 * Time: 02:21
 */

namespace Nfq\WeatherBundle\Provider;


use Nfq\WeatherBundle\Location\Location;
use Nfq\WeatherBundle\Parser\OpenWeatherMapParser;
use Nfq\WeatherBundle\Weather\Weather;
use Nfq\WeatherBundle\Parser\YahooParser;
use Nfq\WeatherBundle\Provider\YahooProvider;

class CachedProvider extends ProviderAbstract
{
    /**
     * Cache file name
     */
    const CACHE_FILE_NAME = '/tmp/weather_cache.txt';

    /**
     * @var string
     */
    private $provider;

    /**
     * @var array
     */
    private $providers;

    /**
     * @var int
     */
    private $ttl;

    /**
     * @var array
     */
    public $apis;

    /**
     * CachedProvider constructor.
     * @param string $provider
     * @param array $providers
     * @param int $ttl
     * @param array $apis
     */
    public function __construct(string $provider, array $providers, int $ttl, array $apis)
    {
        $this->provider = $provider;
        $this->providers = $providers;
        $this->ttl = $ttl;
        $this->apis = $apis;
    }

    /**
     * Fetch weather data
     * @param Location $location
     * @return Weather
     */
    public function fetch(Location $location): Weather
    {
        // Check weather data in cache file
        $weather = $this->getCachedWeatherData($this->provider, $location);
        if (null !== $weather) {
            return $weather;
        }

        switch ($this->provider) {
            case 'Nfq\WeatherBundle\Provider\YahooProvider':
                $provider = new $this->provider(new YahooParser());
                $weather = $provider->fetch($location);
                break;

            case 'Nfq\WeatherBundle\Provider\OpenWeatherMapProvider':
                $provider = new $this->provider(new OpenWeatherMapParser(), $this->apis['OpenWeatherMap']);
                $weather = $provider->fetch($location);
                break;
            
            case 'Nfq\WeatherBundle\Provider\DelegatingProvider':
                $provider = new $this->provider($this->providers, $this->apis);
                $weather = $provider->fetch($location);
                break;
        }

        // Cache weather data to file
        $this->cacheWeatherData($this->provider, $location, $weather);

        return $weather;
    }

    /**
     * Check cached Weather data in file
     * @param string $provider
     * @param Location $location
     * @return Weather|void
     */
    private function getCachedWeatherData(string $provider, Location $location)
    {
        if (!file_exists(self::CACHE_FILE_NAME)) {
            // No cache file yet. Return null
            return;
        }
        $content = file_get_contents(self::CACHE_FILE_NAME);

        $rows = explode(PHP_EOL, $content);
        foreach ($rows as $data) {

            // parse row data
            $rowData = explode(' ', $data);

            if (5 === sizeof($rowData)) {
                $cachedTimestamp = $rowData[0];
                $cachedProvider = $rowData[1];
                $cachedLat = $rowData[2];
                $cachedLon = $rowData[3];
                $cachedTemp = $rowData[4];

                $currentTimestamp = new \DateTime('now');

                // Do we have a match?
                if (($provider === $cachedProvider) &&
                    ($location->getLatitude() === floatval($cachedLat)) &&
                    ($location->getLongitude() === floatval($cachedLon)) &&
                    ($currentTimestamp->getTimestamp() - $this->ttl <= intval($cachedTimestamp))
                ) {
                    // It's a match
                    return new Weather(floatval($cachedTemp));
                }
            }
        }

        // Not found in cache. Return null
        return;
    }

    /**
     * Cache Weather data to file
     * @param string $provider
     * @param Location $location
     * @param Weather $weather
     */
    private function cacheWeatherData(string $provider, Location $location, Weather $weather)
    {
        $cache_date = new \DateTime('now');
        $cacheContent = $cache_date->getTimestamp().' '
            .$provider.' '.$location->getLatitude().' '
            .$location->getLongitude().' '
            .$weather->getTemperature().PHP_EOL;

        // Write to beginning of file
        $fileContent = '';
        if (file_exists(self::CACHE_FILE_NAME)) {
            $fileContent = file_get_contents(self::CACHE_FILE_NAME);
        }

        $cacheContent .= $fileContent;
        file_put_contents(self::CACHE_FILE_NAME, $cacheContent);
    }
}