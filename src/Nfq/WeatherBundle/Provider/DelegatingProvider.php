<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 10/04/16
 * Time: 23:38
 */

namespace Nfq\WeatherBundle\Provider;


use Nfq\WeatherBundle\Location\Location;
use Nfq\WeatherBundle\Parser\OpenWeatherMapParser;
use Nfq\WeatherBundle\Parser\YahooParser;
use Nfq\WeatherBundle\Provider\YahooProvider;
use Nfq\WeatherBundle\Weather\Weather;

class DelegatingProvider extends ProviderAbstract
{
    /**
     * @var array
     */
    private $providers;

    /**
     * @var string
     */
    private $apis;

    /**
     * DelegatingProvider constructor.
     * @param array $providers
     */
    public function __construct(array $providers, array $apis)
    {
        $this->providers = $providers;
        $this->apis = $apis;
//        dump($apis, $providers); exit;
    }

    /**
     * @param Location $location
     * @return Weather
     * @throws WeatherProviderException
     */
    public function fetch(Location $location): Weather
    {
        //dump($this->api); exit;

        foreach ($this->providers as $providerIterator) {
            //var_dump($providerIterator); exit;

            try {
                switch ($providerIterator) {
                    case 'Nfq\WeatherBundle\Provider\YahooProvider':
                        $provider = new $providerIterator(new YahooParser());
//                        var_dump($provider); exit;
                        break;
                    case 'Nfq\WeatherBundle\Provider\OpenWeatherMapProvider':
                        $provider = new $providerIterator(new OpenWeatherMapParser(), $this->apis['OpenWeatherMap']);
                        //var_dump($provider); exit;
                        break;
                }

                $weather = $provider->fetch($location);
                return $weather;
            } catch (\Exception $ex) {
                // This provider failed. Log error and let's try new one
                error_log('Failed to get data. Message: '.$ex->getMessage());
            }
        }

        throw new WeatherProviderException('All providers failed to respond');
    }
}