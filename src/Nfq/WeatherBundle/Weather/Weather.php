<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 06/04/16
 * Time: 23:39
 */

namespace Nfq\WeatherBundle\Weather;


class Weather
{
    /**
     * @var float
     */
    private $temperature;

    /**
     * Weather constructor.
     */
    public function __construct()
    {
    }

    /**
     * Get temperature
     * @return float
     */
    public function getTemperature(): float
    {
        return $this->temperature;
    }

    /**
     * Set temperature
     * @param float $temperature
     */
    public function setTemperature($temperature)
    {
        $this->temperature = $temperature;
    }
}