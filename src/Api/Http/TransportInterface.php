<?php
/**
 * @author Jean Silva <me@jeancsil.com>
 * @license MIT
 */
namespace Jeancsil\FlightSpy\Api\Http;

use Jeancsil\FlightSpy\Api\DataTransfer\SessionParameters;

interface TransportInterface
{
    /**
     * @param SessionParameters $parameters
     * @param bool $newSession
     * @return \stdClass
     */
    public function findQuotes(SessionParameters $parameters, $newSession);
}
