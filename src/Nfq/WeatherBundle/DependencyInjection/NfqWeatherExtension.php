<?php

namespace Nfq\WeatherBundle\DependencyInjection;

use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class NfqWeatherExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $provider = $config['provider'];
        $delegatingProviders = $config['providers']['delegating']['providers'];
        $cachedProvider = $config['providers']['cached']['provider'];
        $ttl = $config['providers']['cached']['ttl'];

        $container->setAlias('nfq_weather.provider', 'nfq_weather.provider.'.$provider);

        $delegatingProvidersAsClass = array();
        $delegatingProvidersApis = array();
        foreach ($delegatingProviders as $providerIterator) {
            array_push($delegatingProvidersAsClass, 'Nfq\\WeatherBundle\Provider\\'.$providerIterator.'Provider');
            $delegatingProvidersApis[$providerIterator] = $config['providers']
                                                            [strtolower($providerIterator)]
                                                            ['api_key'];
        }

        $container->setParameter('nfq_weather.delegating_providers', $delegatingProvidersAsClass);
        $container->setParameter('api_key', $delegatingProvidersApis);

        // If cached provider is delegating, we need to specify array of providers
        if ('delegating' === strtolower($cachedProvider)) {
            $container->setParameter('nfq_weather.cached_providers', $delegatingProvidersAsClass);
        } else {
            $container->setParameter('nfq_weather.cached_providers', array());
        }

        $container->setParameter(
            'nfq_weather.cached_provider',
            'Nfq\\WeatherBundle\Provider\\'.$cachedProvider.'Provider'
        );
        $container->setParameter('ttl', $ttl);
    }
}
