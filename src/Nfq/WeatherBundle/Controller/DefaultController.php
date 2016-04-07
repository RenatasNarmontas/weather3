<?php

namespace Nfq\WeatherBundle\Controller;

use Nfq\WeatherBundle\Location\Location;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->testAction(54.683972, 25.292726);
    }

    /**
     * @Route("/lon/{lon}/lat/{lat}")
     */
    public function testAction(float $lon, float $lat)
    {
        //$location = new Location(54.641288, 24.990230);
        $location = new Location($lon, $lat);
        $provider = $this->get('nfq_weather.provider.open_weather_map');
        $weather = $provider->fetch($location);
        return $this->render('NfqWeatherBundle:Default:index.html.twig',
            [
                'latitude' => $location->getLatitude(),
                'longtitude' => $location->getLongtitude(),
                'temperature' => $weather->getTemperature(),
                'pageGenerated' => date('Y-m-d H:i:s T')
            ]);
    }
}
