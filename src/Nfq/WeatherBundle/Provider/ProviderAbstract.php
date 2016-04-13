<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 13/04/16
 * Time: 22:00
 */

namespace Nfq\WeatherBundle\Provider;


abstract class ProviderAbstract implements WeatherProviderInterface
{
    /**
     * Return class name
     * @return string
     */
    public function toString(): string
    {
        return get_class($this);
    }
}