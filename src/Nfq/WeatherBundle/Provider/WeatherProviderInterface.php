<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 06/04/16
 * Time: 23:44
 */

namespace Nfq\WeatherBundle\Provider;


use Nfq\WeatherBundle\Location\Location;
use Nfq\WeatherBundle\Weather\Weather;

interface WeatherProviderInterface
{
    /**
     * @param Location $location
     * @return Weather
     * @throws WeatherProviderException
     */
    public function fetch(Location $location): Weather;

}