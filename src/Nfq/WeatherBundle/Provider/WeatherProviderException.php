<?php
/**
 * Created by PhpStorm.
 * User: Renatas Narmontas
 * Date: 07/04/16
 * Time: 00:03
 */

namespace Nfq\WeatherBundle\Provider;

use Exception;

class WeatherProviderException extends Exception
{
    /**
     * WeatherProviderException constructor.
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}