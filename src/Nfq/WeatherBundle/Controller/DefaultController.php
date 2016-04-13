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
     * @Route("/lat/{lat}/lon/{lon}")
     */
    public function openWeatherMapAction(float $lat, float $lon)
    {
        //$location = new Location(54.641288, 24.990230);
        $location = new Location($lat, $lon);
        $provider = $this->get('nfq_weather.provider.open_weather_map');
        //$provider = $this->get('nfq_weather');
        $weather = $provider->fetch($location);
        return $this->render('NfqWeatherBundle:Default:index.html.twig',
            [
                'location' => $location,
                'temperature' => $weather->getTemperature(),
                'pageGenerated' => date('Y-m-d H:i:s T')
            ]);
    }

    /**
     * @Route("/yahoo/lat/{lat}/lon/{lon}")
     */
    public function yahooAction(float $lat, float $lon)
    {
        $location = new Location($lat, $lon);
        $provider = $this->get('nfq_weather.provider.yahoo');
        $weather = $provider->fetch($location);
        return $this->render('NfqWeatherBundle:Default:index.html.twig',
            [
                'location' => $location,
                'temperature' => $weather->getTemperature(),
                'pageGenerated' => date('Y-m-d H:i:s T')
            ]);


//        $BASE_URL = "http://query.yahooapis.com/v1/public/yql";
//        $yql_query = 'select * from weather.forecast where woeid in (SELECT woeid FROM geo.places WHERE text="('
//            .$lon.','.$lat
//            .')") and u="C"';
//        $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query). "&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
//        var_dump($yql_query_url); die;
//
//        // Make call with cURL
//        $session = curl_init($yql_query_url);
//        curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
//        $json = curl_exec($session);
//        // Convert JSON to PHP object
//        $phpObj =  json_decode($json);
////        var_dump($phpObj);
////        die;
    }
    /**
     * @Route("/del/lat/{lat}/lon/{lon}")
     */
    public function delegatingAction(float $lat, float $lon)
    {
        $location = new Location($lat, $lon);
        $provider = $this->get('nfq_weather.provider');
        dump($provider); exit;
        //$provider = $this->getParameter('nfq_weather.provider');

        $weather = $provider->fetch($location);
        return $this->render('NfqWeatherBundle:Default:index.html.twig',
            [
                'location' => $location,
                'temperature' => $weather->getTemperature(),
                'pageGenerated' => date('Y-m-d H:i:s T')
            ]);

    }

    /**
     * @Route("/test")
     */
    public function testAction()
    {
        //$provider = $this->container->getParameter('');
        //var_dump($provider); die;
    }
}
