<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 06/04/16
 * Time: 23:46
 */

namespace Nfq\WeatherBundle\Location;


class Location
{
    /**
     * @var float
     */
    private $longtitude;

    /**
     * @var float
     */
    private $latitude;

    /**
     * Location constructor.
     * @param float $longtitude
     * @param float $latitude
     */
    public function __construct(float $latitude, float $longtitude)
    {
        $this->latitude = $latitude;
        $this->longtitude = $longtitude;
    }

    /**
     * @return float
     */
    public function getLongtitude()
    {
        return $this->longtitude;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }
}