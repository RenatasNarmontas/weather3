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
    }

    /**
     * @param Location $location
     * @return Weather
     * @throws WeatherProviderException
     */
    public function fetch(Location $location): Weather
    {
        foreach ($this->providers as $providerIterator) {
            try {
                switch ($providerIterator) {
                    case 'Nfq\WeatherBundle\Provider\YahooProvider':
                        $provider = new $providerIterator(new YahooParser());
                        break;

                    case 'Nfq\WeatherBundle\Provider\OpenWeatherMapProvider':
                        $provider = new $providerIterator(new OpenWeatherMapParser(), $this->apis['OpenWeatherMap']);
                        break;

                    default:
                        throw new WeatherProviderException(sprintf('Unknown provider: %s', $providerIterator));
                }

                $weather = $provider->fetch($location);
                return $weather;
            } catch (\Exception $ex) {
                // This provider failed. Log error and let's try new one
                error_log(sprintf('Failed to get data. Message: %s', $ex->getMessage()));
            }
        }

        throw new WeatherProviderException('All providers failed to respond');
    }
}