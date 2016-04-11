<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 10/04/16
 * Time: 23:38
 */

namespace Nfq\WeatherBundle\Provider;


use Nfq\WeatherBundle\Location\Location;
use Nfq\WeatherBundle\Weather\Weather;

class DelegatingProvider implements WeatherProviderInterface
{
    /**
     * @var array
     */
    private $providers;

    /**
     * DelegatingProvider constructor.
     * @param array $providers
     */
    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    /**
     * @param Location $location
     * @return Weather
     * @throws WeatherProviderException
     */
    public function fetch(Location $location): Weather
    {
//        $location = new Location($lat, $lon);
//        $provider = $this->get('nfq_weather.provider.yahoo');
//        $weather = $provider->fetch($location);

        foreach ($this->providers as $prov) {
            try {
                $provider = $this->get($prov);
                $weather = $provider->fetch($location);
            } catch (\Exception $ex) {
                // This provider failed. Log error and let's try new one
                error_log('Failed to get data from '.$prov.' Message: '.$ex->getMessage());
            }
        }

        if (null === $weather) {
            throw new WeatherProviderException('All providers failed to respond');
        }

        return $weather;
    }
}