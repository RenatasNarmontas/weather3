<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 13/04/16
 * Time: 22:00
 */

namespace Nfq\WeatherBundle\Provider;


use Nfq\WeatherBundle\Location\Location;
use Nfq\WeatherBundle\Weather\Weather;

abstract class ProviderAbstract implements WeatherProviderInterface
{
    /**
     * @param Location $location
     * @return Weather
     */
    abstract public function fetch(Location $location): Weather;

    /**
     * Return class name
     * @return string
     */
    public function __toString(): string
    {
        return get_class($this);
    }
}