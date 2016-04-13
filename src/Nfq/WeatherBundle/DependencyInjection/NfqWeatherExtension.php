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
        //var_dump($provider); exit;

        $delegatingProviders = $config['providers']['delegating']['providers'];

        $container->setAlias('nfq_weather.provider', 'nfq_weather.provider.'.$provider);

        $delegatingProvidersAsClass = array();
        $delegatingProvidersApis = array();
        foreach ($delegatingProviders as $providerIterator)
        {
            array_push($delegatingProvidersAsClass, 'Nfq\\WeatherBundle\Provider\\'.$providerIterator.'Provider');
            $delegatingProvidersApis[$providerIterator] = $config['providers'][strtolower($providerIterator)]['api_key'];
        }

        $container->setParameter('nfq_weather.delegating_providers', $delegatingProvidersAsClass);
        $container->setParameter('api_key', $delegatingProvidersApis);





//        $container->setParameter('nfq_weather.provider', $config['provider']);

        // Testing code in documentation
//        $loader1 = new YamlConfigLoader(new FileLocator(__DIR__.'/../Resources/config'));
//        //$loader1 = new YamlConfigLoader(__DIR__.'/../Resources/config');
//        $loader1->load('config.yml');

//        var_dump($config['nfq_weather.provider']);die;


//        $configDirectories = array(__DIR__.'/../Resources/config');
//        $locator = new FileLocator($configDirectories);
//        $yamlConfigFiles = $locator->locate('config.yml', null, true);

        //var_dump($locator); die;
        //var_dump($yamlConfigFiles); die;

//        $loaderNew = new YamlConfigLoader($locator);
//        $loaderNew->load($yamlConfigFiles);
//        $config = $loaderNew->load($yamlConfigFiles);
//        var_dump($config); die;


//        $container->setParameter('nfq_weather.provider', $config['nfq_weather.provider']);


//
//        $loaderResolver = new LoaderResolver(array(new YamlConfigLoader($locator, $yamlConfigFiles)));
//        $delegatingLoader = new DelegatingLoader($loaderResolver);
//
//        $delegatingLoader->load(__DIR__.'/config.yml');

        //object(Symfony\Component\Config\Loader\DelegatingLoader)#3582 (1) { ["resolver":protected]=> object(Symfony\Component\Config\Loader\LoaderResolver)#3580 (1) { ["loaders":"Symfony\Component\Config\Loader\LoaderResolver":private]=> array(1) { [0]=> object(Nfq\WeatherBundle\Config\YamlConfigLoader)#3581 (3) { ["locator":protected]=> object(Symfony\Component\Config\FileLocator)#3579 (1) { ["paths":protected]=> array(1) { [0]=> string(99) "/home/reno/phpstormProjects/weather3/src/Nfq/WeatherBundle/DependencyInjection/../Resources/config/" } } ["currentDir":"Symfony\Component\Config\Loader\FileLoader":private]=> NULL ["resolver":protected]=> *RECURSION* } } } }
        //var_dump($delegatingLoader); die;

    }
}
