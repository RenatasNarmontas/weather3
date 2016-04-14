<?php

namespace Nfq\WeatherBundle\Controller;

use Nfq\WeatherBundle\Location\Location;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/lat/{lat}/lon/{lon}")
     */
    public function openWeatherMapAction(float $lat, float $lon)
    {
        $location = new Location($lat, $lon);
        $provider = $this->get('nfq_weather.provider.open_weather_map');
        $weather = $provider->fetch($location);
        return $this->render(
            'NfqWeatherBundle:Default:index.html.twig',
            [
                'location' => $location,
                'temperature' => $weather->getTemperature(),
                'pageGenerated' => date('Y-m-d H:i:s T')
            ]
        );
    }

    /**
     * @Route("/yahoo/lat/{lat}/lon/{lon}")
     */
    public function yahooAction(float $lat, float $lon)
    {
        $location = new Location($lat, $lon);
        $provider = $this->get('nfq_weather.provider.yahoo');
        $weather = $provider->fetch($location);
        return $this->render(
            'NfqWeatherBundle:Default:index.html.twig',
            [
                'location' => $location,
                'temperature' => $weather->getTemperature(),
                'pageGenerated' => date('Y-m-d H:i:s T')
            ]
        );
    }

    /**
     * @Route("/del/lat/{lat}/lon/{lon}")
     */
    public function delegatingAction(float $lat, float $lon)
    {
        $location = new Location($lat, $lon);
        $provider = $this->get('nfq_weather.provider');

        //dump($provider); exit;
        //$provider = $this->getParameter('nfq_weather.provider');

        $weather = $provider->fetch($location);
        return $this->render(
            'NfqWeatherBundle:Default:index.html.twig',
            [
                'location' => $location,
                'temperature' => $weather->getTemperature(),
                'pageGenerated' => date('Y-m-d H:i:s T'),
                'provider' => $provider
            ]
        );
    }
}
